<?php

function wegal_get_post_type() {
    return 'we_gallery';
}

function wegal_get_meta_key() {
    return '_wegal_images';
}

function wegal_get_gallery( $the_gallery, $args = array() ) {
    return We_Gallery_Plugin::init()->factory->get_gallery( $the_gallery, $args );
}