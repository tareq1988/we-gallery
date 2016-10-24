/*jshint devel:true */
/*global wp */
/*global _ */
/*global Backbone */

;(function($) {

    var WeGallery = {

        init: function() {

            $('a#wegal-add-image').on('click', this.imageUpload);
            $('#wegal-image-wrap').on('click', 'a.image-delete', this.removeImage);

            $('#wegal-image-wrap').on('click', 'a.image-edit', this.showPopup);
            $('.wegal-popup-close').on('click', this.closePopup);
            $('input#wegal-image-update').on('click', this.updateImage);

            this.makeSortable();
        },

        /**
         * Image upload/insert handler
         *
         * @param  {obj} e
         * @return {null}
         */
        imageUpload: function(e) {
            e.preventDefault();

            var file_frame,
                self = $(this);

            if ( file_frame ) {
                file_frame.open();
                return;
            }

            // Create the media frame.
            file_frame = wp.media({
                title: self.data( 'uploader-title' ),
                button: {
                    text: self.data( 'uploader-button' )
                },
                library: {
                    type: 'image'
                },
                multiple: true
            });

            file_frame.on( 'select', function() {
                var attachment = file_frame.state().get('selection').toJSON();

                var template = '<div class="thumb">' +
                    '<img src="<%= url %>" alt="">' +
                    '<a href="#" class="image-delete">&times</a>' +
                    '<a href="#" class="image-edit dashicons dashicons-edit" data-attachment_id="<%= id %>">&nbsp;</a>' +
                    '<input type="hidden" name="_wegal_image[]" value="<%= id %>">' +
                '</div>';

                var images = '';
                _.each(attachment, function(image){
                    var url = '';

                    if ( typeof image.sizes.thumbnail !== 'undefined' ) {
                        url = image.sizes.thumbnail.url;
                    } else {
                        url = image.url;
                    }

                    images += _.template(template)({
                        url: url,
                        id: image.id
                    });

                });

                $('#wegal-image-wrap').append(images);
            });

            file_frame.open();
        },

        /**
         * Remove a gallery image
         *
         * @param  {obj} e
         * @return {null}
         */
        removeImage: function(e) {
            e.preventDefault();

            $(this).closest('.thumb').remove();
        },

        /**
         * Make image sortable
         *
         * @return {void}
         */
        makeSortable: function() {
            $('#wegal-image-wrap').sortable({
                items: '.thumb'
            });
        },

        /**
         * Close the popup
         *
         * @param  {obj} e
         * @return {null}
         */
        closePopup: function(e) {
            e.preventDefault();

            WeGallery.hidePopup();
        },

        /**
         * Hide the popup
         *
         * @return {void}
         */
        hidePopup: function() {
            $('#wegal-popup-box').hide();
            $('#wegal-popup-overlay').hide();
            $('#wegal-ajax-content').html('<span class="spinner"></span>');
        },

        /**
         * Show the popup modal
         *
         * @param  {obj} e
         * @return {void}
         */
        showPopup: function(e) {
            e.preventDefault();

            var self = $(this),
                attachment_id = self.data('attachment_id'),
                model = new wp.media.model.Attachment({ id: attachment_id });



            $('#wegal-popup-overlay').show();
            $('#wegal-popup-box').show();

            model.fetch({
                success: function(model, resp) {
                    var tpl = _.template( $('#wegal-tmpl-image-editor').html() )( {image: resp} );
                    $('#wegal-ajax-content').html(tpl);
                },

                error: function(model, resp) {

                }
            });
        },

        /**
         * Update image details
         *
         * @param  {obj} e
         * @return {void}
         */
        updateImage: function(e) {
            e.preventDefault();

            var data = {
                id: $('#wegal-input-att-id').val(),
                title: $('#wegal-input-title').val(),
                caption: $('#wegal-input-caption').val(),
                alt: $('#wegal-input-alt').val(),
                tags: $('#wegal-input-tags').val(),
                description: $('#wegal-input-description').val(),
                _wpnonce: wegalAdmin.nonce,
                action: 'wegal_save_image_details'
            };

            $.post(wegalAdmin.ajaxurl, data);

            WeGallery.hidePopup();
        }
    };

    /**
     * Initialize on DOM ready
     */
    $(function() {
        WeGallery.init();
    });

})(jQuery);