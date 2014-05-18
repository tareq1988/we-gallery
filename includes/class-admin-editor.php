<?php

/**
 * Admin Editor Class
 *
 * @author Tareq Hasan <tareq@wedevs.com>
 */
class We_Gallery_Admin_Editor {

    function __construct() {

        add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts') );
        add_action( 'add_meta_boxes_we_gallery', array($this, 'add_meta_box') );
        add_action( 'save_post', array( $this, 'save_images' ), 1, 2 ); // save the custom fields

        add_action( 'media_buttons', array( $this, 'add_media_button' ), 20 );
        add_action( 'admin_footer', array( $this, 'media_thickbox_content' ) );

        // custom columns
        add_filter( 'manage_edit-we_gallery_columns', array( $this, 'admin_column' ), 10, 2 );
        add_action( 'manage_we_gallery_posts_custom_column', array( $this, 'admin_column_value' ), 10, 2 );

        add_filter( 'post_updated_messages', array($this, 'gallery_updated_message') );
        add_filter( 'wp_prepare_attachment_for_js', array($this, 'attachment_enhancements'), 10, 2 );

        add_action( 'wp_ajax_wegal_save_image_details', array($this, 'update_image' ) );
    }

    /**
     * Enqueue scripts and styles for form builder
     *
     * @global string $pagenow
     * @return void
     */
    function enqueue_scripts() {
        global $pagenow, $current_screen;

        if ( !in_array( $pagenow, array( 'post.php', 'post-new.php') ) ) {
            return;
        }

        wp_enqueue_script( 'wegal-shortcode', WEGAL_ASSET_URI . '/js/admin-shortcode.js', array('jquery') );

        if ( $current_screen->post_type != 'we_gallery' ) {
            return;
        }

        // scripts
        wp_enqueue_media();
        wp_enqueue_script( 'thickbox' );
        wp_enqueue_script( 'wegal-admin', WEGAL_ASSET_URI . '/js/admin.js', array('jquery', 'underscore'),"1" );

        // styles
        wp_enqueue_style( 'thickbox' );
        wp_enqueue_style( 'wegal-admin', WEGAL_ASSET_URI . '/css/admin.css' );
        wp_localize_script( 'wegal-admin', 'wegalAdmin', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'wegal-update-image' )
        ) );
    }

    /**
     * Adds a media button (for inserting a gallery) to the Post Editor
     *
     * @param  int  $editor_id The editor ID
     * @return void
     */
    function add_media_button( $editor_id ) {
        ?>
            <a href="#TB_inline?width=480&amp;inlineId=wegal-select-gallery" class="button thickbox insert-gallery" data-editor="<?php echo esc_attr( $editor_id ); ?>" title="<?php _e( 'Add a Gallery', 'wegal' ); ?>">
                <?php echo '<span class="wp-media-buttons-icon dashicons dashicons-images-alt2"></span>' . __( ' Add Gallery', 'wegal' ); ?>
            </a>
        <?php

    }

    /**
     * Prints the thickbox popup content
     *
     * @return void
     */
    public function media_thickbox_content() {
        global $pagenow;

        if ( !in_array( $pagenow, array( 'post.php', 'post-new.php') ) ) {
            return;
        }

        include dirname( __FILE__ ) . '/views/shortcode-builder.php';
    }

    /**
     * Gallery update messages
     *
     * @param  array $messages
     * @return array
     */
    function gallery_updated_message( $messages ) {
        $message = array(
             0 => '',
             1 => __('Gallery updated.', 'wegal' ),
             2 => __('Custom field updated.', 'wegal'),
             3 => __('Custom field deleted.', 'wegal'),
             4 => __('Gallery updated.', 'wegal'),
             5 => isset($_GET['revision']) ? sprintf( __('Gallery restored to revision from %s', 'wegal'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
             6 => __('Gallery published.', 'wegal'),
             7 => __('Gallery saved.', 'wegal'),
             8 => __('Gallery submitted.', 'wegal' ),
             9 => '',
            10 => __('Gallery draft updated.', 'wegal'),
        );

        $messages['we_gallery'] = $message;

        return $messages;
    }

    /**
     * Columns form builder list table
     *
     * @param type $columns
     * @return string
     */
    function admin_column( $columns ) {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __( 'Gallery Name', 'wegal' ),
            'num_image' => __( 'Images', 'wegal' ),
            'shortcode' => __( 'Shortcode', 'wegal' ),
            'date' => __( 'Date', 'wegal' ),
        );

        return $columns;
    }

    /**
     * Custom Column value for post form builder
     *
     * @param string $column_name
     * @param int $post_id
     */
    function admin_column_value( $column_name, $post_id ) {
        switch ($column_name) {
            case 'num_image':

                $images = $this->get_images( $post_id );
                if ( is_array( $images ) && count( $images ) ) {
                    $number = count( $images );

                    printf( _n( __( '1 Image', 'wegal' ), __( '%d Images', 'wegal'), $number, 'wegal' ), $number );

                } else {
                    echo __( 'No images', 'wegal' );
                }
                break;

            case 'shortcode':
                echo '[wegallery id="' . $post_id . '"]';
                break;

        }
    }

    /**
     * Add meta boxes to gallery builder
     *
     * @return void
     */
    function add_meta_box() {

        // remove core submit div
        remove_meta_box('submitdiv', 'we_gallery', 'side');

        add_meta_box( 'wegal-gallery', __( 'Gallery Images', 'wegal' ), array($this, 'gallery_editor'), 'we_gallery', 'normal', 'high' );
        add_meta_box( 'wegal-submitdiv', __( 'Publish', 'wegal' ), array($this, 'publish_button'), 'we_gallery', 'side', 'core' );
    }

    /**
     * Get image ids
     *
     * @param  int $post_id
     * @return array
     */
    function get_images( $post_id ) {
        return get_post_meta( $post_id, wegal_get_meta_key(), true );
    }

    /**
     * Gallery editor area
     *
     * @return void
     */
    function gallery_editor() {
        global $post;

        $images = $this->get_images( $post->ID );
        ?>

        <input type="hidden" name="wegal_form_nonce" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />

        <div id="wegal-image-wrap" class="clearifx">

            <?php if ( $images ) {

                foreach ($images as $image_id) {
                    $image_src = wp_get_attachment_image( $image_id, 'thumbnail' );

                    if ( $image_src ) {
                        ?>

                        <div class="thumb">
                            <?php echo $image_src; ?>

                            <a href="#" class="image-delete">&times;</a>
                            <a href="#" class="image-edit dashicons dashicons-edit" data-attachment_id="<?php echo $image_id; ?>">&nbsp;</a>
                            <input name="_wegal_image[]" value="<?php echo $image_id; ?>" type="hidden">
                        </div>

                        <?php
                    }
                }
            } ?>

        </div>
        <?php

        include dirname( __FILE__ ) . '/views/image-details-popup.php';
    }


    /**
     * Replaces the default publish button with our own
     *
     * @return void
     */
    function publish_button() {
        global $post, $pagenow;

        $post_type = $post->post_type;
        $post_type_object = get_post_type_object($post_type);
        $can_publish = current_user_can($post_type_object->cap->publish_posts);
        ?>
        <div class="submitbox" id="submitpost">

            <div class="wegal-buttons">
                <a title="<?php _e( 'Add Images', 'wegal' ); ?>" class="button button-secondary" id="wegal-add-image" href="#" data-uploader-title="<?php _e( 'Add Images to gallery', 'wegal' ); ?>" data-uploader-button="<?php _e( 'Add Images', 'wegal' ); ?>">
                    <span class="dashicons dashicons-plus"></span> <?php _e( 'Add Images', 'wegal' ); ?>
                </a>
            </div>

            <div id="major-publishing-actions">
                <div id="delete-action">
                <?php
                if ( current_user_can( "delete_post", $post->ID ) ) {
                    if ( !EMPTY_TRASH_DAYS ) {
                        $delete_text = __('Delete Permanently');
                    } else {
                        $delete_text = __('Move to Trash');
                    }
                    ?>
                    <a class="submitdelete deletion" href="<?php echo get_delete_post_link($post->ID); ?>"><?php echo $delete_text; ?></a><?php
                } ?>
                </div>

                <div id="publishing-action">

                    <span class="spinner"></span>
                        <?php
                        if ( !in_array( $post->post_status, array('publish', 'future', 'private') ) || 0 == $post->ID ) {
                            if ( $can_publish ) :
                                if ( !empty( $post->post_date_gmt ) && time() < strtotime( $post->post_date_gmt . ' +0000' ) ) :
                                    ?>
                                    <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Schedule' ) ?>" />
                                    <?php submit_button( __( 'Schedule' ), 'primary button-large', 'publish', false, array('accesskey' => 'p') ); ?>

                                <?php else : ?>

                                    <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Publish' ) ?>" />

                                    <?php submit_button( __( 'Publish' ), 'primary button-large', 'publish', false, array('accesskey' => 'p') ); ?>
                                <?php endif;

                            else :
                                ?>
                                <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Submit for Review' ) ?>" />
                                <?php submit_button( __( 'Submit for Review' ), 'primary button-large', 'publish', false, array('accesskey' => 'p') ); ?>
                            <?php
                            endif;
                        } else {
                            ?>
                            <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Update' ) ?>" />
                            <input name="save" type="submit" class="button button-primary button-large" id="publish" accesskey="p" value="<?php esc_attr_e( 'Update' ) ?>" />
                    <?php }
                ?>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <?php
    }

    /**
     * Saves the form ID from form selection meta box
     *
     * @param int $post_id
     * @param object $post
     * @return int|void
     */
    function save_images( $post_id, $post ) {
        if ( !isset($_POST['wegal_form_nonce'])) {
            return;
        }

        if ( !wp_verify_nonce( $_POST['wegal_form_nonce'], plugin_basename( __FILE__ ) ) ) {
            return;
        }

        // Is the user allowed to edit the post or page?
        if ( !current_user_can( 'edit_post', $post->ID ) ) {
            return $post->ID;
        }

        $images = isset( $_POST['_wegal_image'] ) ? array_map('intval', $_POST['_wegal_image']) : array();

        update_post_meta( $post->ID, wegal_get_meta_key(), $images );
    }

    /**
     * Update image details via ajax
     *
     * @return void
     */
    function update_image() {
        if ( ! $id = absint( $_REQUEST['id'] ) ) {
            wp_send_json_error();
        }

        check_ajax_referer( 'wegal-update-image' );

        $post = get_post( $id, ARRAY_A );

        if ( 'attachment' != $post['post_type'] ) {
            wp_send_json_error();
        }

        $post['post_title']   = $_POST['title'];
        $post['post_excerpt'] = $_POST['caption'];
        $post['post_content'] = $_POST['description'];

        $alt  = wp_unslash( $_POST['alt'] );
        $tags = wp_unslash( $_POST['tags'] );

        if ( $alt != get_post_meta( $id, '_wp_attachment_image_alt', true ) ) {
            $alt = wp_strip_all_tags( $alt, true );
            update_post_meta( $id, '_wp_attachment_image_alt', wp_slash( $alt ) );
        }

        if ( $tags != get_post_meta( $id, '_wp_attachment_image_tags', true ) ) {
            $tags = wp_strip_all_tags( $tags, true );
            update_post_meta( $id, '_wp_attachment_image_tags', wp_slash( $tags ) );
        }

        wp_update_post( $post );
        wp_send_json_success();
    }

    /**
     * Adds tags on the ajax request for WordPress attachment
     *
     * @param  array $response
     * @param  WP_Post $attachment
     * @return array
     */
    function attachment_enhancements($response, $attachment){
        $id = $response['id'];

        if ( 'attachment' == $attachment->post_type ) {
            $response['tags'] = get_post_meta( $id, '_wp_attachment_image_tags', true );
        }

        return $response;
    }


}