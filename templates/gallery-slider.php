<?php
/**
 * Gallery template file
 *
 * You can override this file by copying this file to your current themes directory.
 * Simply create a folder "wegallery" and place this file.
 */
?>

<div class="wegal-gallery-wrap gallery-slider">
    <div class="wegal-flexslider" id="wegallery-slier-<?php echo $id; ?>">
        <ul class="wegallery-gallery slides">

            <?php foreach ($gallery_images as $image) { ?>

                <li>
                    <?php echo $image['sizes']['full'] ?>

                    <div class="wegal-flex-caption">
                        <?php if ( $title == 'yes' && ! empty( $image['title'] ) ) {
                            echo '<h3>' . wp_kses_post( $image['title'] ) . '</h3>';
                        } ?>

                        <?php if ( $desc == 'yes' && ! empty( $image['description'] ) ) {
                            echo '<div class="details">' . wp_kses_post( $image['description'] ) . '</div>';
                        } ?>
                    </div>
                </li>

            <?php } ?>
        </ul>
    </div>
</div>

<script type="text/javascript">
jQuery(function($) {
    $('#wegallery-slier-<?php echo $id; ?>').flexslider({
        animation: 'slide',
        controlNav: false,
        directionNav: true,
        // smoothHeight: true
    });
});
</script>