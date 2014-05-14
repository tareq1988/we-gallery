<?php
/**
 * Gallery template file
 *
 * You can override this file by copying this file to your current themes directory.
 * Simply create a folder "wegallery" and place this file.
 */
?>

<div class="wegal-gallery-wrap gallery-grid">

    <ul class="wegallery-gallery wegallery-<?php echo $id; ?> wegal-col-<?php echo $col; ?>">

        <?php foreach ($gallery_images as $image) {

            if ( $link == 'file' ) {
                $url = esc_url( $image['url'] );
            } elseif ( $link == 'post' ) {
                $url = get_permalink( $image['id'] );
            } else {
                $url = false;
            }
            ?>

            <li class="wegal-thumb">
                <div class="wegal-inside">
                    <?php if ( ! $url ) { ?>
                        <?php echo $image['sizes']['full'] ?>
                    <?php } else { ?>
                        <a href="<?php echo $url; ?>" data-type="<?php echo $link; ?>" rel="wegal-image">
                            <?php echo $image['sizes']['thumb'] ?>
                        </a>
                    <?php } ?>

                    <?php if ( $caption == 'yes' ) { ?>

                        <div class="wegal-caption"><?php echo wp_kses_post( $image['caption'] ); ?></div>

                    <?php } ?>
                </div>
            </li>

        <?php } ?>
    </ul>
</div>