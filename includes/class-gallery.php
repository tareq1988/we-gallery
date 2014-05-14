<?php

/**
 * WeGallery Class
 *
 * The gallery class that handles individual gallery data
 *
 * @author Tareq Hasan <tareq@wedevs.com>
 */
class We_Gallery_Gallery {

    public $ID;
    public $post;

    /**
     * __construct function.
     *
     * @access public
     * @param mixed $gallery
     */
    function __construct( $gallery ) {
        global $post;

        if ( false === $gallery ) {
            $the_gallery = $post;

        } elseif ( is_numeric( $gallery ) ) {
            $the_gallery = get_post( $gallery );

        } elseif ( is_a( $gallery, 'WP_Post') ) {
            $the_gallery = $gallery;

        } else {
            $the_gallery = get_post( absint( $gallery ) );
        }

        if ( ! $the_gallery ) {
            return false;
        }

        $this->ID   = $the_gallery->ID;
        $this->post = $the_gallery;
    }

    /**
     * Get images ids from a gallery
     *
     * @return array attachmet ids
     */
    public function get_image_ids() {
        $image_ids = get_post_meta( $this->ID, wegal_get_meta_key(), true );

        if ( !is_array( $image_ids ) ) {
            return array();
        }

        return $image_ids;
    }

    /**
     * Get a single image for the gallery
     *
     * @param  int $attachment_id
     * @return array attachment details
     */
    public function get_image( $attachment_id, $context = 'grid' ) {
        $attachment = get_post( $attachment_id );

        if ( !$attachment || $attachment->post_type != 'attachment' ) {
            return;
        }

        $image_data = array(
            'id'          => $attachment_id,
            'caption'     => $attachment->post_excerpt,
            'title'       => $attachment->post_title,
            'description' => $attachment->post_content,
            'url'         => wp_get_attachment_url( $attachment_id ),
            'sizes' => array(
                'thumb' => wp_get_attachment_image( $attachment_id, 'thumbnail' ),
                'full'  => wp_get_attachment_image( $attachment_id, 'full' ),
            ),
        );

        if ( $context == 'slider' ) {
            $image_data['sizes']['slider'] = wp_get_attachment_image( $attachment_id, 'weslider-slide-image' );
        }

        return apply_filters( 'wegal_get_image', $image_data, $attachment_id, $this->ID, $this->post );
    }

    /**
     * Get all images from a gallery
     *
     * @return array
     */
    public function get_images( $context = 'grid' ) {
        $image_ids = $this->get_image_ids();

        if ( !$image_ids ) {
            return false;
        }

        $image_array = array();
        foreach ($image_ids as $attachment_id) {
            if ( $image = $this->get_image( $attachment_id, $context ) ) {
                $image_array[] = $image;
            }
        }

        return apply_filters( 'wegal_get_images', $image_array, $image_ids, $this->ID, $this->post );
    }
}