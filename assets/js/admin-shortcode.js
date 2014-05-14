/*jshint devel:true */
/*global send_to_editor */
/*global tb_remove */

jQuery(function($) {

    $('#wegal-gallery-insert').on('click', function(e) {
        e.preventDefault();

        var shortcode  = '[wegallery ',
            gallery_id = jQuery('#wegal-gallery-dropdown').val(),
            type       = jQuery('#wegal-gallery-type').val();

        if ( gallery_id === '-1' ) {
            alert( 'Please select a gallery' );
            return;
        }

        shortcode += 'id="' + gallery_id + '" ';

        if ( type === 'grid' ) {
            var cols    = jQuery('#wegal-gallery-cols').val();
            var caption = jQuery('input[type="radio"][name="wegal-gallery-caption"]:checked').val();

            shortcode += 'type="grid" col="' + cols + '" caption="' + caption + '"';
        } else {
            var title     = jQuery('#wegal-gallery-title').is(':checked') ? 'yes' : 'no';
            var desc      = jQuery('#wegal-gallery-desc').is(':checked') ? 'yes' : 'no';
            var animation = jQuery('#wegal-gallery-animation').val();
            var direction = jQuery('input[type="radio"][name="wegal-gallery-direction"]:checked').val();
            var nav       = jQuery('input[type="radio"][name="wegal-gallery-nav"]:checked').val();

            shortcode += 'type="slider" title="' + title + '" desc="' + desc + '"';
            shortcode += ' animation="' + animation + '" direction="' + direction + '"';
            shortcode += ' nav="' + nav + '"';
        }

        var link = jQuery('#wegal-gallery-link').val();

        shortcode += ' link="' + link + '"]';

        send_to_editor(shortcode);
        tb_remove();
    });

    $('#wegal-gallery-type').on('change', function() {
        var val = $(this).val();

        if ( val === 'grid' ) {
            $('.show-if-grid').show();
            $('.show-if-slider').hide();
        } else {
            $('.show-if-grid').hide();
            $('.show-if-slider').show();
        }
    });

    $('#wegal-gallery-type').trigger('change');

});