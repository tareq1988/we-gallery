<div id="wegal-select-gallery" style="display: none;">

    <div class="wegal-popup-container">

        <h3><?php _e( 'Select a gallery to insert', 'wegal' ); ?></h3>

        <div class="gallery-select wegal-div">
            <label for="wegal-gallery-dropdown" class="label"><?php _e( 'Gallery', 'wegal' ); ?></label>

            <select name="wegal_gallery" id="wegal-gallery-dropdown">
                <?php echo wegal_get_gallery_dropdown(); ?>
            </select>
        </div>

        <div class="wegal-div">
            <label for="wegal-gallery-type" class="label"><?php _e( 'Type', 'wegal' ); ?></label>

            <select id="wegal-gallery-type">
                <option value="grid"><?php _e( 'Grid', 'wegal' ); ?></option>
                <option value="slider"><?php _e( 'Slider', 'wegal' ); ?></option>
            </select>
        </div>

        <div class="wegal-div show-if-grid">
            <label for="wegal-gallery-cols" class="label"><?php _e( 'Columns', 'wegal' ); ?></label>

            <select id="wegal-gallery-cols">
                <option value="2"><?php _e( '2 Columns', 'wegal' ); ?></option>
                <option value="3" selected="selected"><?php _e( '3 Columns', 'wegal' ); ?></option>
                <option value="4"><?php _e( '4 Columns', 'wegal' ); ?></option>
                <option value="5"><?php _e( '5 Columns', 'wegal' ); ?></option>
            </select>
        </div>

        <div class="wegal-div">
            <label for="wegal-gallery-link" class="label"><?php _e( 'Link To', 'wegal' ); ?></label>

            <select id="wegal-gallery-link">
                <option value="file"><?php _e( 'Media File', 'wegal' ); ?></option>
                <option value="post"><?php _e( 'Attachment Page', 'wegal' ); ?></option>
                <option value="none"><?php _e( 'None', 'wegal' ); ?></option>
            </select>
        </div>

        <div class="wegal-div show-if-grid">
            <label for="wegal-gallery-caption" class="label"><?php _e( 'Caption', 'wegal' ); ?></label>

            <label><input type="radio" name="wegal-gallery-caption" checked="checked" value="yes"><?php _e( 'Show', 'wegal' ); ?></label>
            <label><input type="radio" name="wegal-gallery-caption" value="no"><?php _e( 'Hide', 'wegal' ); ?></label>
        </div>

        <div class="wegal-div show-if-slider">
            <label for="wegal-gallery-title" class="checkbox">
                <input type="checkbox" id="wegal-gallery-title" value="yes">
                <?php _e( 'Show Title', 'wegal' ); ?>
            </label>
        </div>

        <div class="wegal-div show-if-slider">
            <label for="wegal-gallery-desc" class="checkbox">
                <input type="checkbox" id="wegal-gallery-desc" value="yes">
                <?php _e( 'Show Description', 'wegal' ); ?>
            </label>
        </div>

        <div class="wegal-div show-if-slider">
            <label for="wegal-gallery-animation" class="label"><?php _e( 'Animation', 'wegal' ); ?></label>

            <select id="wegal-gallery-animation">
                <option value="slide"><?php _e( 'Slide', 'wegal' ); ?></option>
                <option value="fade"><?php _e( 'Fade', 'wegal' ); ?></option>
            </select>
        </div>

        <div class="wegal-div show-if-slider">
            <label for="wegal-gallery-nav" class="label"><?php _e( 'Navigation', 'wegal' ); ?></label>

            <label><input type="radio" name="wegal-gallery-nav" checked="checked" value="yes"><?php _e( 'Show', 'wegal' ); ?></label>
            <label><input type="radio" name="wegal-gallery-nav" value="no"><?php _e( 'Hide', 'wegal' ); ?></label>
        </div>

        <div class="wegal-div show-if-slider">
            <label for="wegal-gallery-direction" class="label"><?php _e( 'Direction', 'wegal' ); ?></label>

            <label><input type="radio" name="wegal-gallery-direction" checked="checked" value="yes"><?php _e( 'Show', 'wegal' ); ?></label>
            <label><input type="radio" name="wegal-gallery-direction" value="no"><?php _e( 'Hide', 'wegal' ); ?></label>
        </div>

        <div class="submit-button wegal-div">
            <button id="wegal-gallery-insert" class="button-primary"><?php _e( 'Insert Gallery', 'wegal' ); ?></button>
            <button id="wegal-gallery-close" class="button-secondary" style="margin-left: 5px;" onClick="tb_remove();"><?php _e( 'Close', 'wegal' ); ?></a>
        </div>

    </div>
</div>

<style type="text/css">
    .wegal-popup-container {
        padding: 15px 0 0 20px;
    }
    .wegal-div {
        padding: 0 0 10px 0;
        clear: left;
    }
    .wegal-div label.label {
        float: left;
        width: 15%;
    }

    .wegal-div label.checkbox {
        width: 100%;
        padding-left: 15%;
    }
</style>