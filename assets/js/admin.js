/*jshint devel:true */
/*global wp */
/*global _ */
/*global Backbone */

;(function($) {

    // var ImageView = Backbone.View.extend({
    //     el: '#wegal-image-wrap',

    //     template: '<img src="<%= url %>" alt="" data-id="">',

    //     initialize: function() {
    //         this.collection.on('add', this.render, this);
    //     },

    //     render: function() {
    //         console.log('init render');
    //         var self = this;

    //         _.each(this.collection.models, function(model) {
    //             var template = _.template(self.template, { url: model.get('url') } );
    //             console.log(template);
    //         });
    //     }
    // });

    // var ImageModel = Backbone.Model.extend({
    //     idAttribute: "id",

    //     defaults: {
    //         id:    null,
    //         url:           null,
    //         alt:           null,
    //         title:         null,
    //         sizes:         null
    //     },
    // });

    // var ImageCollection = Backbone.Collection.extend({
    //     model: ImageModel,
    // });

    // var Gallery = new ImageCollection();

    // var Gallery = new ImageCollection([
    //     new ImageModel({
    //         id: 184,
    //         url: 'http://hotel.wordpress.dev/wp-content/uploads/2014/03/angelic_girl-wallpaper-2560x1600-e1399819411960.jpg',
    //         alt: '',
    //         title: 'angelic_girl-wallpaper-2560x1600',
    //         sizes: {
    //             full: {

    //             }
    //         }
    //     })
    // ]);

    // new ImageView( {
    //     collection: Gallery
    // });

    var Editor = $('#wegal-image-wrap');
    var WeGallery = {

        init: function() {
            console.log('ehlo!');

            $('a#wegal-add-image').on('click', this.imageUpload);
            $('#wegal-image-wrap').on('click', 'a.image-delete', this.removeBanner);

            this.makeSortable();
        },

        imageUpload: function(e) {
            e.preventDefault();

            var file_frame,
                self = $(this);

            if ( file_frame ) {
                file_frame.open();
                return;
            }

            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                title: self.data( 'uploader-title' ),
                button: {
                    text: self.data( 'uploader-button' )
                },
                multiple: true
            });

            file_frame.on( 'select', function() {
                var attachment = file_frame.state().get('selection').toJSON();

                // console.log(attachment);
                var template = '<div class="thumb">' +
                    '<img src="<%= url %>" alt="">' +
                    '<a href="#" class="image-delete">&times</a>' +
                    '<input type="hidden" name="_wegal_image[]" value="<%= id %>">' +
                '</div>';

                var images = '';
                _.each(attachment, function(image){
                    var url = '';
                    console.log(image);

                    if ( typeof image.sizes.thumbnail !== 'undefined' ) {
                        url = image.sizes.thumbnail.url;
                    } else {
                        url = image.url;
                    }

                    images += _.template(template, {
                        url: url,
                        id: image.id
                    });

                });

                console.log(images);
                $('#wegal-image-wrap').append(images);
            });

            file_frame.open();

        },

        removeBanner: function(e) {
            e.preventDefault();

            $(this).closest('.thumb').remove();
        },

        makeSortable: function() {
            $('#wegal-image-wrap').sortable({
                items: '.thumb'
            });
        }
    };

    $(function() {
        WeGallery.init();
    });

})(jQuery);