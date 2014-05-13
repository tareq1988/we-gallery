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
        directionNav: true
    });
});
</script>