<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// cptwp_item
//cptwp_category
if( ! class_exists( 'CptwpBackboneFront' ) ) {
    /**
     * CptwpBackbone Front class.
     */
    class CptwpBackboneFront {

        /**
         * constructor
         */
        
        public function __construct() { 
            add_action( 'wp_enqueue_scripts', array( $this, 'styles' ));
            add_shortcode( 'ctpwp-list', array( $this, 'shortcode' )); 
            
        }        
        
        public function shortcode( $attributes ) {
            global $wp_query;
            $attrs = shortcode_atts( array(
                'id' => 0,                
            ), $attributes );
            
            $args = array(
                'post_type' => 'cptwp_item',
                'orderby' => 'ID',
                'order'   => 'DESC',
                'post_status' => array( 'publish' ),
                'nopaging' => true,
                
            );
            if ( $attrs['id'] > 0) {               
                $args['tax_query'] = array(
                    //'relation' => 'AND',
                    array(
                            'taxonomy' => 'cptwp_category',//                          
                            'field'    => 'term_id',
                            'terms' => $attrs['id'],   
                            
                    ),
                );
            }
            
            $query = new WP_Query( $args );           
            if ( $query->have_posts() ) : 
                ?> 
<div class="cptwp-category-wrap">
                <?php
                global $post;
                while ( $query->have_posts() ) : 
                    $query->the_post(); 
                    $alt = get_post_meta( $post->ID, 'item_alt', true );
                    $description = get_post_meta( $post->ID, 'item_description', true );
                    $image_val = get_post_meta( $post->ID, 'item_img', true );
                    if (! empty( $image_val)) {
                        $image = '<img class="cptwp-front-image" src="' .
                        wp_get_attachment_image_src( $image_val, 'full' )[0] .
                        '" alt="' . $alt . '">';
                    } else $image = '';
                    ?> 
<div class="cptwp-front-item">
    <div class="cptwp-front-img-wrap">
        <?php echo $image; ?>
    </div>
    <div class="cptwp-front-item-desc">
        <?php echo $description; ?>
    </div>
</div>
                    <?php
                    
                endwhile; 
                ?></div> <?php
            add_action( 'wp_footer',  array( $this, 'scripts' ) );
            else:
                
            endif;
            wp_reset_query();        
        }
        public function styles() { 
            wp_register_style( 'cptwp_front_style', CPTWP_BACKBONE_URI . '/css/style.css' );
            wp_enqueue_style( 'cptwp_front_style' );
          
        }
        public function scripts() {            
            wp_register_script( 'cptwp_front_script', CPTWP_BACKBONE_URI . '/js/front.js' , array( 'jquery' ), NULL, TRUE );            
            wp_enqueue_script( 'cptwp_front_script' );
        }
    }
    new CptwpBackboneFront();
}


