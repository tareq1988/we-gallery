<?php

/**
 * Factory class for a gallery
 *
 * @author Tareq Hasan <tareq@wedevs.com>
 */
class We_Gallery_Factory {

    /**
     * Get gallery single gallery
     *
     * @param  mixed $the_gallery
     * @param  array   $args
     * @return \We_Gallery_Gallery
     */
    public function get_gallery( $the_gallery = false, $args = array() ) {
        global $post;

        if ( false === $the_gallery ) {
            $the_gallery = $post;
        } elseif ( is_numeric( $the_gallery ) ) {
            $the_gallery = get_post( $the_gallery );
        }

        if ( ! $the_gallery ) {
            return false;
        }

        return new We_Gallery_Gallery( $the_gallery, $args );
    }
}