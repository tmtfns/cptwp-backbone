<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

function cptwp_backbone_delete_plugin() {
    global $wpdb;


    $posts = get_posts(
            array(
                    'numberposts' => -1,
                    'post_type' => 'cptwp_item',
                    'post_status' => 'any',
            )
    );

    foreach ( $posts as $post ) {
            wp_delete_post( $post->ID, true );
    }
    
}

cptwp_backbone_delete_plugin();

