<div class="wrap">
    <h1><?php echo $GLOBALS['title']; ?></h1>
    <form  action="?page=robots-edit" method="post" data-ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>">
        <div>
            <label for="robots-data" id="theme-plugin-editor-label"><?php _e( 'File Content:', 'xilu-robots-editor' );?></label>
            <textarea id="robots-data" name="robots-data"><?php echo trim(esc_textarea($val)); ?></textarea>
        </div>
        <div>
            <div class="editor-notices">
                <div class="notice inline is-dismissible" style="display:none;">
                    <p></p>
                    <button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e( 'Close', 'xilu-robots-editor' );?></span></button>
                </div>
            </div>
            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save File', 'xilu-robots-editor' );?>">
                <span class="spinner"></span>
            </p>
        </div>
    </form>
</div>