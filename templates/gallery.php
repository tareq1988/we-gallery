<div class="wegal-gallery-wrap">

    <ul class="wegallery-gallery wegallery-<?php echo $gallery_id; ?> wegal-col-<?php echo $column; ?>">

        <?php foreach ($gallery_images as $image) { ?>

            <li class="wegal-thumb">
                <a href="<?php echo esc_url( $image['url'] ); ?>" rel="wegal-image">
                    <?php echo $image['sizes']['thumb'] ?>
                </a>
            </li>

        <?php } ?>

    </ul>

</div>