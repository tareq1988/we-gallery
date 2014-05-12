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

        if ( is_a( $gallery, 'WP_Post') ) {
            $this->ID   = $gallery->ID;
            $this->post = $gallery;
        } else {
            $this->ID   = absint( $gallery );
            $this->post = get_post( $this->ID );
        }
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
    public function get_image( $attachment_id ) {
        $full_url = wp_get_attachment_url( $attachment_id );

        if ( !$full_url ) {
            return false;
        }

        $image_data = array(
            'id'  => $attachment_id,
            'url' => $full_url,
            'sizes' => array(
                'thumb' => wp_get_attachment_image( $attachment_id, 'thumbnail' ),
                'full'  => wp_get_attachment_image( $attachment_id, 'full' )
            )
        );

        return apply_filters( 'wegal_get_image', $image_data, $attachment_id, $this->ID, $this->post );
    }

    /**
     * Get all images from a gallery
     *
     * @return array
     */
    public function get_images() {
        $image_ids = $this->get_image_ids();

        if ( !$image_ids ) {
            return false;
        }

        $image_array = array();
        foreach ($image_ids as $attachment_id) {
            $image_array[] = $this->get_image($attachment_id);
        }

        return apply_filters( 'wegal_get_images', $image_array, $image_ids, $this->ID, $this->post );
    }
}