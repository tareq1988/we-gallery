<div id="wegal-popup-box" class="wegal-poup-container" style="display: none;">

    <div class="wegal-box-head">
        <?php _e( 'Update Image Details' ); ?>
        <div class="wegal-popup-close"></div>
    </div>

    <div class="wegal-box-inside">
        <div id="wegal-ajax-content">
            <span class="spinner"></span>
        </div>
    </div>

    <div class="wegal-popup-buttons">
        <?php submit_button( __( 'Update Image' ), 'button-primary alignright', 'wegal-image-update', false ); ?>
        <div class="clear"></div>
    </div>
</div>

<div id="wegal-popup-overlay" style="display: none;"></div>

<script id="wegal-tmpl-image-editor" type="text/template">
    <div class="wegal-input-box">
        <label for="wegal-input-title"><?php _e( 'Title', 'wegal' ); ?></label>
        <input type="text" value="<%= image.title %>" id="wegal-input-title">
    </div>

    <div class="wegal-input-box">
        <label for="wegal-input-caption"><?php _e( 'Caption', 'wegal' ); ?></label>
        <textarea id="wegal-input-caption" cols="30" rows="3"><%= image.caption %></textarea>
    </div>

    <div class="wegal-input-box">
        <label for="wegal-input-alt"><?php _e( 'Alt Text', 'wegal' ); ?></label>
        <input type="text" value="<%= image.alt %>" id="wegal-input-alt">
    </div>

    <div class="wegal-input-box">
        <label for="wegal-input-description"><?php _e( 'Description', 'wegal' ); ?></label>
        <textarea id="wegal-input-description" cols="30" rows="3"><%= image.description %></textarea>
    </div>

    <div class="wegal-input-box">
        <label for="wegal-input-tags"><?php _e( 'Tags', 'wegal' ); ?></label>
        <input type="text" value="<%= image.tags %>" id="wegal-input-tags">
    </div>

    <input type="hidden" id="wegal-input-att-id" value="<%= image.id %>">
</script>