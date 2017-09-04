<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// cptwp_item
//cptwp_category
if( ! class_exists( 'CptwpBackboneInit' ) ) {
    /**
     * CptwpBackbone Init class.
     */
    class CptwpBackboneInit {       
        
        public static function init() {
            self::register_cpt();
            self::register_taxonomies();
        }
        
        public static function register_cpt() {
            $single = 'CPTWP item';
            $plural = 'CPTWP items';
        
            $labels = array(
                'name' => __( $plural, 'cptwp-backbone' ), 
                'singular_name' => __( $single, 'cptwp-backbone' ),
                'add_new' => __( $single, 'cptwp-backbone' ),
                'add_new_item' => __( 'add new ' . $single, 'cptwp-backbone' ),
                'edit_item' => __( 'edit ' . $single, 'cptwp-backbone' ),
                'new_item' => __( 'create new ' . $single, 'cptwp-backbone' ),
                'view_item' => __( 'view ' . $single, 'cptwp-backbone' ),
                'view_items' => __( 'view ' . $plural, 'cptwp-backbone' ),
                'search_items' => __( 'search ' . $plural, 'cptwp-backbone' ),
                'not_found' => __( 'not found ' . $plural, 'cptwp-backbone' ),
                'not_found_in_trash' => __( 'not found ' . $plural, 'cptwp-backbone' ),
                'all_items' => __( 'All ' . $plural, 'cptwp-backbone' ),
                'items_list' => __( 'The list of ' . $plural, 'cptwp-backbone' ),
                'menu_name' => __( 'CPTWP', 'cptwp-backbone' ),
                'name_admin_bar' => __( 'new CPTWP ' . $single, 'cptwp-backbone' ),
            );
            $args = array(
                'labels'              => $labels,
                'public'              => true,
                'publicly_queryable'  => true,
                'exclude_from_search' => true,
                'show_ui'             => true,
                'show_in_menu'        => true, 
                'show_in_rest'        => false,
                'query_var'           => true,
                'rewrite'             => array( 
                                        'slug' => 'cptwp-items',
                                        'with_front' => true
                                        ),
                'capability_type'     => 'page',
                'has_archive'         => true,
                'hierarchical'        => false,
                'menu_position'       => 87,
                'menu_icon'             =>CPTWP_BACKBONE_URI . '/images/icon.png', //'dashicons-feedback',
                'supports'            => array( 'title' ),
                'taxonomies'          => array( 'cptwp_category' )
            );
            register_post_type( 'cptwp_item', $args);     
            
        }
        
        public static function register_taxonomies() {
            $single = 'CPTWP category';
            $plural = 'CPTWP categories';
            $labels = array(
                'name' => __( $plural, 'cptwp-backbone' ), 
                'singular_name' => __( $single, 'cptwp-backbone' ),
                'add_new' => __( $plural, 'cptwp-backbone' ),
                'add_new_item' => __( 'add new ' . $single, 'cptwp-backbone' ),
                'edit_item' => __( 'edit ' . $single, 'cptwp-backbone' ),
                'new_item' => __( 'create new ' . $single, 'cptwp-backbone' ),
                'view_item' => __( 'view ' . $single, 'cptwp-backbone' ),
                'view_items' => __( 'view ' . $plural, 'cptwp-backbone' ),
                'search_items' => __( 'search ' . $plural, 'cptwp-backbone' ),
                'not_found' => __( 'not found ' . $plural, 'cptwp-backbone' ),
                'not_found_in_trash' => __( 'not found ' . $plural, 'cptwp-backbone' ),
                'all_items' => __( 'All ' . $plural, 'cptwp-backbone' ),
                'items_list' => __( 'The list of ' . $plural, 'cptwp-backbone' ),
                'menu_name' => __( 'CPTWP Category', 'cptwp-backbone' ),
                'name_admin_bar' => __( 'new CPTWP Categories' . $single, 'cptwp-backbone' ),
            );
            $args = array(
                'hierarchical' => true,
                'labels' => $labels,
                'show_ui' => true,
                'query_var' => true,
                'rewrite' => array( 'slug' => 'cptwp-category', 'with_front' => true ),
            );
            register_taxonomy( 'cptwp_category', array( 'cptwp_item' ), $args );            
            
        }       
        
    }    
}

