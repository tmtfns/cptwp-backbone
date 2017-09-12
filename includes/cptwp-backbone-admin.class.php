<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// cptwp_item
//cptwp_category
if( ! class_exists( 'CptwpBackboneAdmin' ) ) {
    /**
     * CptwpBackbone Admin class.
     */
    class CptwpBackboneAdmin {

        /**
         * constructor
         */
        
        public function __construct() { 
            
            add_action( 'admin_enqueue_scripts', array( $this, 'styles_and_scripts' ) );
            //add_action( 'init', array( $this, 'init' ));
            
            add_filter( 'manage_cptwp_category_custom_column', array( $this, 'manage_categories_columns' ), 10, 3 );
            add_filter( 'manage_edit-cptwp_category_columns', array( $this, 'categories_columns' )); 
            
            add_filter( 'manage_cptwp_item_posts_custom_column', array( $this, 'manage_items_columns' ), 10, 2 );
            add_filter( 'manage_edit-cptwp_item_columns', array( $this, 'items_columns' ) );
            
            add_action( 'add_meta_boxes', array( $this, 'item_meta_box' ) );
            add_action( 'save_post_cptwp_item',  array( $this, 'save_item' ), 10, 3 );
            
            
        }
        
//        public function init() {
//            CptwpBackboneInit::init();
//
//        }
        
        public function styles_and_scripts() {
            //get insert
            wp_register_style( 'cptwp-backbone-admin_style', CPTWP_BACKBONE_URI . '/css/admin.css' );
            wp_enqueue_style( 'cptwp-backbone-admin_style' );
            wp_register_script( 'cptwp-backbone-admin_script', CPTWP_BACKBONE_URI . '/js/admin.js', array( 'jquery' ), NULL, TRUE );
            wp_enqueue_media();
            wp_enqueue_script( 'cptwp-backbone-admin_script' );
        }
        
        public function categories_columns( $columns ) { 
            $columns['cptwp_shortcode'] = __( 'Shortcode', 'cptwp-backbone' );
            unset( $columns['description']);
            return $columns;
        }        
        
        public function manage_categories_columns( $content, $column_name, $term_id ) {
            if ( 'cptwp_shortcode' == $column_name ) {               
                $content = '[ctpwp-list id='. $term_id .']';
            }            
            return $content;
        }
        
        public function items_columns( $columns ) {            
            $new_columns = array (
                'cb' => $columns['cb'],
                'title' => $columns['title'],
                'item_short_description' => __( 'Short description', 'cptwp-backbone' ),
                'item_thumb' => __( 'Thumbnail', 'cptwp-backbone' ),
                'date' => $columns['date'],
            );
            return $new_columns;
        }        
        
        public function manage_items_columns( $column_name, $post_id ) {
            
            switch ( $column_name) {
                case 'item_short_description': {
                     echo get_post_meta( $post_id, 'item_alt', true );
                    break;
                } 
                case 'item_thumb': {
                    $img_id = get_post_meta( $post_id, 'item_img', true );                    
                    $img = (empty( $img_id)) ? '' : wp_get_attachment_image( $img_id, 'thumbnail', false, array( 'style' => 'max-width:100px;height:auto;' ) );
                    echo $img;
                    break;
                }   
                    
            }            
        }
        public function item_meta_box() {
            add_meta_box(
                'cptwp-fields',
                __( 'Content', 'cptwp-backbone' ),
                    array( $this, 'item_meta_box_callback' ),
                'cptwp_item',
                'normal',
                'high'
            );
        }
        
        public function save_item( $post_id, $post, $update ) {
            if (wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id )) {
                return;
            }
            if ( ! current_user_can( 'edit_post', $post_id ) ) 
                return;
            if ((! isset($_POST['cptwp_back_nonce'])) || (! wp_verify_nonce( $_POST['cptwp_back_nonce'], __CLASS__ ))) 
            {
                return;
            } 
            if ( isset( $_POST['item_alt'] ) ) {
                update_post_meta( $post_id, 'item_alt', sanitize_text_field( $_POST['item_alt'] ) );
            }
            if ( isset( $_POST['item_description'] ) ) {
                update_post_meta( $post_id, 'item_description', nl2br(wp_kses( $_POST['item_description'] ) ) );
            }
            if ( isset( $_POST['item_img'] ) ) {
                update_post_meta( $post_id, 'item_img', (int) $_POST['item_img'] );
            }
            
        }
        
        public function item_meta_box_callback( $post ) {
            wp_nonce_field(__CLASS__, 'cptwp_back_nonce' );
            $meta = get_post_meta( $post->ID );
            ?> 
<div class="wrap">
    <div class="form-group">
        <label for="item_alt"><?php esc_html_e( 'Short description', 'cptwp-backbone' ); ?></label>
        <input type="text" id="item_alt" name="item_alt" value="<?php if ( isset( $meta['item_alt'] ) ) echo esc_attr( $meta['item_alt'][0] ); ?>">
    </div>
    <div class="form-group">
        <label for="item_description"><?php esc_html_e( 'Description', 'cptwp-backbone' ); ?></label>
        <?php 
        wp_editor(get_post_meta( $post->ID, 'item_description', true ),
                'item_description',
                array(
                    'textarea_name' => 'item_description',
                    'textarea_rows' => '10',
                    'media_buttons' => false
                ));       
        ?>        
    </div>
    <div class="cptwp-image-wrap">
        <?php
            $image_val = get_post_meta( $post->ID, 'item_img', true );            
            $image = ( empty( $image_val ) ) ? '' : wp_get_attachment_image( $image_val, 'full', false, array( 'style' => 'max-width:100%;height:auto;' ) );
        ?>
        <input type="hidden" id="cptwp-media-img" name="item_img" value="<?php echo esc_attr( $image_val ); ?>" />
        <input type='button' id="cptwp-media-upload" class="button cptwp-media-upload" value="Upload" data-title="<?php echo __( 'Images library', 'cptwp-backbone' ); ?>" data-button="<?php echo __( 'Select an image', 'cptwp-backbone' ); ?>" />
        <div class="cptwp-image-preview" id="cptwp-media-img-container"><?php echo $image; ?></div>
    </div>
</div>
            <?php
        }
       
        
        
    }
    
    new CptwpBackboneAdmin();
}

