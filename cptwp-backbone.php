<?php
/*
Plugin Name: CPTWP Backbone
Plugin URI:  https://github.com/tmtfns/WPInsertCode
Description: The backbone of the wordpress plugin allows you to add custom post type.
Version:     0.1
Author:      tatiana
Author URI:  tmtfns@gmail.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: CptwpBackbone
Domain Path: /languages

CPTWP Backbone is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
CPTWP Backbone is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with CPTWP Backbone. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/
/*
 * please, replace everywhere
 * CptwpBackbone with your plugin classes names
 * cptwp_item with your custom post type name
 * cptwp_category with your custom post type category
 * ctpwp-list with your shortcode 
 * 
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists( 'CptwpBackbone' ) ) {
    /**
     * Main CptwpBackbone class.
     */
    class CptwpBackbone {

        /**
         * constructor
         */
        
        public function __construct() { 
            
            if( !defined( 'CPTWP_BACKBONE_FOLDER' ) ) {
                define( 'CPTWP_BACKBONE_FOLDER', dirname(  __FILE__ ) ); // Plugin folder
            }
            if( !defined( 'CPTWP_BACKBONE_URI' ) ) {
                define( 'CPTWP_BACKBONE_URI',  plugins_url( '',  __FILE__ ) ); // Plugin folder
            }
                    
            register_activation_hook( __FILE__,  array( $this, 'activate' ));
            register_deactivation_hook( __FILE__,  array( $this, 'deactivate' ));
            // it is not best way to add your style sheet file. The second variant is preferable
            $this->includes();
            add_action( 'init', array( 'CptwpBackboneInit', 'init' ));
            if (is_admin()) {
                $this->admin_includes();
            }           
        }        
        
        private function includes() {
            require_once CPTWP_BACKBONE_FOLDER . '/includes/cptwp-backbone-init.class.php';
            require_once CPTWP_BACKBONE_FOLDER . '/includes/cptwp-backbone-front.class.php';
        }
        
        private function admin_includes() {
            
            require_once CPTWP_BACKBONE_FOLDER . '/includes/cptwp-backbone-admin.class.php';
        }
        
        
        
        /**
         * activation  plugin
         * check and add if not exist options
         * does not overwrite old options
         */
        
        public static function activate() {
            require_once CPTWP_BACKBONE_FOLDER . '/includes/cptwp-backbone-init.class.php';
            CptwpBackboneInit::init();
            flush_rewrite_rules();
            //options

        }          
        /**
         * deactivation  plugin
         * empty         
         */
        
        public static function deactivate() {
            
        }        
        
    }
    
    new CptwpBackbone();
}
