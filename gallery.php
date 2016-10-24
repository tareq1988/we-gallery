<?php
/**
 * Plugin Name: We Gallery
 * Plugin URI: https://wedevs.com/
 * Description: The missing gallery of WordPress. Simple, yet the effective gallery plugin!
 * Version: 1.1
 * Author: Tareq Hasan
 * Author URI: https://tareq.co/
 * License: GPL2
 * TextDomain: wegal
 */

/**
 * Copyright (c) 2016 Tareq Hasan (email: tareq@wedevs.com). All rights reserved.
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
 * We_Gallery_Plugin class
 *
 * The class that holds the entire We_Gallery_Plugin plugin
 *
 * @author Tareq Hasan <tareq@wedevs.com>
 */
class We_Gallery_Plugin {

    /**
     * Constructor for the We_Gallery class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @uses add_action()
     * @uses add_shortcode()
     */
    public function __construct() {

        spl_autoload_register( array( $this, 'autoload' ) );

        $this->set_constants();
        $this->file_includes();
        $this->instantiate();

        // Localize our plugin
        add_action( 'init', array( $this, 'localization_setup' ) );
        add_action( 'init', array( $this, 'register_post_type' ), 0 );

        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );

        // Loads frontend scripts and styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_footer', array( $this, 'popup_script' ) );

        add_action( 'after_setup_theme', array( $this, 'image_sizes' ) );

        // shortcode handler
        add_shortcode( 'wegallery', array( $this, 'shortcode' ) );
    }

    /**
     * Autoload class files on demand
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
     * Initializes the We_Gallery_Plugin() class
     *
     * Checks for an existing We_Gallery_Plugin() instance
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
     * Show action links on the plugin screen
     *
     * @param mixed $links
     * @return array
     */
    public function action_links( $links ) {
        return array_merge( array(
            '<a href="' . admin_url( 'edit.php?post_type=we_gallery' ) . '">' . __( 'Settings', 'wegal' ) . '</a>',
            '<a href="' . esc_url( 'http://wedevs.com/support' ) . '">' . __( 'Support', 'wegal' ) . '</a>',
        ), $links );
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
        wp_enqueue_script( 'flexslider', WEGAL_ASSET_URI . '/js/jquery.flexslider.min.js', array( 'jquery' ), false, true );
        wp_enqueue_script( 'wegal-magnific', WEGAL_ASSET_URI . '/js/jquery.magnific-popup.min.js', array( 'jquery' ), false, true );
    }

    /**
     * Register our gallery post type
     *
     * @return void
     */
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

        register_post_type( wegal_get_post_type(), $args );
    }

    /**
     * Add slider image size
     *
     * It matches the content area width of the theme
     *
     * @return void
     */
    function image_sizes() {
        global $content_width;

        $default_width = 640;
        $default_height = 360;

        $theme_width = empty( $content_width ) ? $default_width : $content_width;
        $theme_height = empty( $content_width ) ? $default_height : round( ( $default_height * $theme_width ) / $default_width );

        add_image_size( 'weslider-slide-image', $theme_width, $theme_height, true);
    }

    /**
     * Shortcode handler function
     *
     * @param  array $atts
     * @param  string $contents
     * @return string
     */
    public static function shortcode( $atts, $contents = '' ) {
        extract( shortcode_atts( array(
          'id'        => 0,
          'type'      => 'grid',
          'col'       => 3,
          'link'      => 'file',
          'caption'   => 'yes',
          'title'     => 'no',
          'desc'      => 'no',
          'animation' => 'slide',
          'direction' => 'yes',
          'nav'       => 'no',
        ), $atts ) );

        if ( ! $id || ! in_array( $type, array('grid', 'slider') ) ) {
            return;
        }

        $gallery = wegal_get_gallery( $id );

        // bail out if no gallery found
        if ( !$gallery ) {
            return;
        }

        $gallery_images = $gallery->get_images( $type );

        // bail out if there are no images
        if ( ! $gallery_images ) {
            return;
        }

        if ( $type == 'grid' ) {
            $template_path = wegal_get_template( 'gallery-grid' );
        } else {

            $animation     = in_array($animation, array('slide', 'fade')) ? $animation : 'slide';
            $direction     = ( $direction == 'yes' ) ? 'true' : 'false';
            $nav           = ( $nav == 'yes' ) ? 'true' : 'false';

            $template_path = wegal_get_template( 'gallery-slider' );
        }

        ob_start();

        if ( file_exists( $template_path ) ) {
            include $template_path;
        }

        $content = ob_get_clean();

        return apply_filters( 'wegallery_shortcode', $content, $gallery_images, $id, $gallery );
    }

    /**
     * Image Popup js
     *
     * @return void
     */
    public function popup_script() {
        ?>
        <script type="text/javascript">
            jQuery(function($) {
                $('.wegal-gallery-wrap').magnificPopup({
                    delegate: 'a[data-type="file"]',
                    type: 'image',
                    zoom: {
                        enabled: true,
                    },
                    gallery:{
                        enabled:true
                    }
                });
            });
        </script>
        <?php
    }

} // We_Gallery_Plugin

$wegal = We_Gallery_Plugin::init();
