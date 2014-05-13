<?php

/**
 * Get the post type name of gallery
 *
 * @return string post type name
 */
function wegal_get_post_type() {
    return 'we_gallery';
}

/**
 * Get the meta key name that holds the image ids
 *
 * @return string gallery image meta key
 */
function wegal_get_meta_key() {
    return '_wegal_images';
}

/**
 * Getter function to get a single image gallery
 *
 * @param  int|obj $the_gallery gallery id or gallery post object
 * @param  array  $args
 * @return \We_Gallery_Gallery
 */
function wegal_get_gallery( $the_gallery, $args = array() ) {
    return new We_Gallery_Gallery( $the_gallery, $args );
}

/**
 * Get galleries
 *
 * @param  array  $args
 * @return \WP_Query
 */
function wegal_get_galleries( $args = array() ) {
    $defaults = array(
        'post_type'      => wegal_get_post_type(),
        'post_status'    => array( 'publish' ),
        'posts_per_page' => 10,
        'order_by'       => 'post_date',
        'order'          => 'ASC'
    );

    $args = wp_parse_args( $args, $defaults );
    $args = apply_filters( 'wegal_get_galleries', $args );

    return new WP_Query( $args );
}

/**
 * Get a list of galleries
 *
 * @return array key value pair of gallery names
 */
function wegal_get_gallery_list() {
    $the_query = wegal_get_galleries( array( 'posts_per_page' => '-1' ) );
    $galleries = $the_query->get_posts();
    $gallery_array = array();

    if ( $galleries ) {
        foreach ($galleries as $gallery) {
            $gallery_array[$gallery->ID] = $gallery->post_title;
        }
    }

    return $gallery_array;
}

/**
 * Get gallery dropdown
 *
 * @return string
 */
function wegal_get_gallery_dropdown() {
    $options = array( '-1' => __( '- Select Gallery', 'wegal' ) );

    if ( $list = wegal_get_gallery_list() ) {
        $list = $options + $list; // array_merge resets the array key
    }

    $dropdown = '';
    foreach ($list as $key => $value) {
        $dropdown .= sprintf( '<option value="%s">%s</a>', esc_attr( $key ), esc_attr( $value ) ) . "\n";
    }

    return $dropdown;
}

/**
 * Get a overridable file path
 *
 * Searches to the child theme directory, then parent theme directory.
 * If not found, fallbacks to the plugins template file.
 *
 * @param  string $file name of the php file without extension
 * @return string path of the file
 */
function wegal_get_template( $file ) {

    $folder = 'wegallery';
    $file_path = DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $file . '.php';

    if ( is_child_theme() && file_exists( get_stylesheet_directory() . $file_path ) ) {
        return get_stylesheet_directory() . $file_path;
    } elseif ( file_exists( get_template_directory() . $file_path ) ) {
        return get_template_directory() . $file_path;
    } else {
        return WEGAL_DIR . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $file . '.php';
    }
}

/**
 * Template function for showing a gallery in themes
 *
 * @param  int $gallery_id
 * @param  array  $args
 * @return void
 */
function wegal_show_gallery( $gallery_id, $args = array() ) {
    $args['id'] = $gallery_id;

    echo We_Gallery_Plugin::shortcode( $args );
}