<?php
/*
Plugin Name: weGallery
Plugin URI: http://wedevs.com/
Description: A simple gallery for WordPress at its best
Version: 0.1
Author: Tareq Hasan
Author URI: http://tareq.wedevs.com/
License: GPL2
*/

/**
 * Copyright (c) 2014 Tareq Hasan (email: tareq@wedevs.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * We_Gallery class
 *
 * @class We_Gallery The class that holds the entire We_Gallery plugin
 */
class We_Gallery_Plugin {

    public static $post_type = 'we_gallery';

    public $factory = null;

    /**
     * Constructor for the We_Gallery class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @uses is_admin()
     * @uses add_action()
     */
    public function __construct() {

        spl_autoload_register( array( $this, 'autoload' ) );

        $this->set_constants();
        $this->file_includes();
        $this->instantiate();

        // Localize our plugin
        add_action( 'init', array( $this, 'localization_setup' ) );
        add_action( 'init', array( $this, 'register_post_type' ), 0 );

        // Loads frontend scripts and styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        // shortcode handler
        add_shortcode( 'wegallery', array( $this, 'shortcode' ) );
    }

    /**
     * Autoload class files on demand
     *
     * `WPUF_Form_Posting` becomes => form-posting.php
     * `WPUF_Dashboard` becomes => dashboard.php
     *
     * @param string $class requested class name
     */
    function autoload( $class ) {

        if ( stripos( $class, 'We_Gallery' ) !== false ) {

            $class_name = str_replace( array('We_Gallery', '_'), array('', '-'), $class );
            $filename = dirname( __FILE__ ) . '/includes/class' . strtolower( $class_name ) . '.php';

            if ( file_exists( $filename ) ) {
                require_once $filename;
            }
        }
    }

    /**
     * Initializes the We_Gallery() class
     *
     * Checks for an existing We_Gallery() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self;
        }

        return $instance;
    }

    /**
     * Defines required constants
     *
     * @return void
     */
    private function set_constants() {

        define('WEGAL_DIR', dirname( __FILE__ ) );
        define('WEGAL_URI', plugins_url( '', __FILE__ ) );
        define('WEGAL_ASSET_URI', WEGAL_URI . '/assets' );

    }

    /**
     * Includes required files
     *
     * @return void
     */
    private function file_includes() {

        require_once WEGAL_DIR . '/includes/functions.php';

    }

    /**
     * Instantiate required classes
     *
     * @return void
     */
    private function instantiate() {

        if ( is_admin() ) {
            new We_Gallery_Admin_Editor();
        }

        $this->factory = new We_Gallery_Factory();
    }

    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup() {
        load_plugin_textdomain( 'wegal', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Enqueue admin scripts
     *
     * Allows plugin assets to be loaded.
     *
     * @uses wp_enqueue_script()
     * @uses wp_localize_script()
     * @uses wp_enqueue_style
     */
    public function enqueue_scripts() {

        /**
         * All styles goes here
         */
        wp_enqueue_style( 'wegal-styles', WEGAL_ASSET_URI . '/css/style.css', false, date( 'Ymd' ) );

        /**
         * All scripts goes here
         */
        wp_enqueue_script( 'wegal-scripts', WEGAL_ASSET_URI . '/js/script.js', array( 'jquery' ), false, true );
    }

    function register_post_type() {

        $labels = array(
            'name'                => _x( 'Galleries', 'Post Type General Name', 'wegal' ),
            'singular_name'       => _x( 'Gallery', 'Post Type Singular Name', 'wegal' ),
            'menu_name'           => __( 'Galleries', 'wegal' ),
            'parent_item_colon'   => __( 'Parent Gallery:', 'wegal' ),
            'all_items'           => __( 'All Galleries', 'wegal' ),
            'view_item'           => __( 'View Gallery', 'wegal' ),
            'add_new_item'        => __( 'Add New Gallery', 'wegal' ),
            'add_new'             => __( 'Add New', 'wegal' ),
            'edit_item'           => __( 'Edit Gallery', 'wegal' ),
            'update_item'         => __( 'Update Gallery', 'wegal' ),
            'search_items'        => __( 'Search Gallery', 'wegal' ),
            'not_found'           => __( 'Not found', 'wegal' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'wegal' ),
        );

        $args = array(
            'label'               => __( 'we_gallery', 'wegal' ),
            'description'         => __( 'Gallery post type', 'wegal' ),
            'labels'              => $labels,
            'supports'            => array( 'title', ),
            'hierarchical'        => false,
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-images-alt2',
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'capability_type'     => 'page',
        );

        register_post_type( self::$post_type, $args );
    }

    public function shortcode( $atts, $contents = '' ) {
        $atts = shortcode_atts( array(
          'id'  => 0,
          'col' => 3
        ), $atts );

        $gallery_id = (int) $atts['id'];
        $column     = (int) $atts['col'];

        if ( ! $gallery_id ) {
            return;
        }

        $gallery = wegal_get_gallery( $gallery_id );

        // bail out if no gallery found
        if ( !$gallery ) {
            return;
        }

        $gallery_images = $gallery->get_images();

        // bail out if there are no images
        if ( ! $gallery_images ) {
            return;
        }

        $template_path = wegal_get_template( 'gallery' );

        ob_start();

        if ( file_exists( $template_path ) ) {
            include $template_path;
        }

        $content = ob_get_clean();

        return apply_filters( 'wegallery_shortcode', $content, $gallery_images, $gallery_id, $gallery );
    }

} // We_Gallery

$wegal = We_Gallery_Plugin::init();
