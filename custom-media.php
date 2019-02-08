<?php function src_add_media_image($arg,$src_custom_buttons = false ,$value = ""){

    $defaults = array(
        'useid' => false ,
        'hidden' => true,

        'parent_div_class'=> 'custom-image-upload',

        'field_label' => 'upload_image_field_label',
        'field_name' => 'upload_image_field',
        'field_id' => 'upload_image_field',
        'field_class' => 'upload_image_field',

        'upload_button_id' => 'upload_logo_button',
        'upload_button_class' => 'upload_logo_button',
        'upload_button_text' => 'Upload',

        'remove_button_id' => 'remove_logo_button',
        'remove_button_class' => 'remove_logo_button',
        'remove_button_text' => 'Remove',

        'preview_div_class' => 'preview',
        'preview_div_class2' => 'preview remove_box',
        'preview_div_id' => 'preview',

        'height' => '100',
        'width' => '100'
    );
    $arguments = wp_parse_args($arg,$defaults);

    extract($arguments);

    wp_enqueue_media();
?>

   <?php if( ! $src_custom_buttons ): ?>
   <div class="<?php echo $parent_div_class; ?>" id="<?php echo $parent_div_class; ?>">

        <input name="<?php echo $field_name; ?>" id="<?php echo $field_id; ?>" class="<?php echo $field_class; ?>" <?php if($hidden): ?>  type="hidden" <?php else: ?> type="text" <?php endif; ?> value="<?php if ( $value != "") { echo stripslashes($value); }  ?>" />

        <input type="button" class="button button-primary <?php echo $upload_button_class; ?>" id="<?php echo $upload_button_id; ?>"  value="<?php echo $upload_button_text; ?>">

        <input type="button" class="button button-primary <?php echo $remove_button_class; ?>" id="<?php echo $remove_button_id; ?>" <?php  if ( $value == "") {  ?> disabled="disabled" <?php } ?> value="<?php echo $remove_button_text; ?>">

        <div class="<?php echo $preview_div_class; ?>" style="float: none; <?php  if ( $value == "") { ?> display: none; <?php } ?>">
            <img
            src="<?php  echo stripslashes($value);  ?>"
            style="max-width:150px; height:auto; margin: 10px 0px;">
        </div>

        <div style="clear: both;"></div>
    </div>
   <?php endif; ?>

    <?php
        $usesep = ($useid) ? "#" : ".";
        if($useid):

         $field_class = $field_id;
         $upload_button_class = $upload_button_id;
         $remove_button_class = $remove_button_id;
         $preview_div_class = $preview_div_id;

        endif;
    ?>

    <script type="text/javascript">

        jQuery(document).ready(function($){
            $('<?php echo $usesep.$remove_button_class; ?>').click(function(e) {
                <?php if(!$useid): ?>
                    $(this).parent().find("<?php echo $usesep.$field_class; ?>").val("");
                    $(this).parent().find("<?php echo $usesep.$preview_div_class; ?> img").attr("src","").fadeOut("slow");
                    <?php else: ?>
                    $("<?php echo $usesep.$field_class; ?>").val("");
                    $("<?php echo $usesep.$preview_div_class; ?> img").attr("src","").fadeOut("slow");
                <?php endif; ?>
                $(this).attr("disabled","disabled");
                return false;
            });
            // if($('.preview img').attr('src')=='') $('.preview').hide();
            // Uploading files
            var _custom_media = true,
            _orig_send_attachment = wp.media.editor.send.attachment;

            $('<?php echo $usesep.$upload_button_class; ?>').click(function(e) {
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = $(this);
            var id = button.attr('id').replace('_button', '');
            _custom_media = true;
            wp.media.editor.send.attachment = function(props, attachment){
                if ( _custom_media ) {
                    <?php if(!$useid): ?>
                        //console.log("<?php echo $usesep.$preview_div_class; ?>");
                        button.parent().find("<?php echo $usesep.$field_class; ?>").val(attachment.url);
                        button.parent().find("<?php echo $usesep.$preview_div_class; ?> img").attr("src",attachment.url).fadeIn("slow");
                        button.parent().find("<?php echo $usesep.$remove_button_class; ?>").removeAttr("disabled");
                        if($('.preview img').length > 0){ $('.preview').css('display','block'); };
                        <?php else: ?>
                        $("<?php echo $usesep.$field_class; ?>").val(attachment.url);
                        $("<?php echo $usesep.$preview_div_class; ?> img").attr("src",attachment.url).fadeIn("slow");
                        $("<?php echo $usesep.$remove_button_class; ?>").removeAttr("disabled");
                    <?php endif; ?>
                } else {
                    return _orig_send_attachment.apply( this, [props, attachment] );
                };
                $('.preview').removeClass('remove_box');
                }
                wp.media.editor.open(button);
                return false;
            });
        });

    </script>
   <?php
}

function src_image_upload_buttons( $field_name, $value = "", $field_id ) {
    ?>
    <div class="custom-image-upload">
        <button type="button" class="button button-primary wgt_src_upload_image">
            <?php echo empty( $value ) ? 'Upload' : 'Change Image'; ?>
        </button>
        <input type="button" class="button button-primary wgt_src_remove_image"
            <?php echo empty( $value ) ? 'disabled="disabled"' : ''; ?> value="Remove">
        <input type="hidden" value="<?php echo empty( $value ) ? '' : $value; ?>" id="<?php echo $field_id; ?>"
            class="wgt_src_uploaded_image_source" name="<?php echo $field_name; ?>">
        <div class="wgt_src_image_priview">
            <?php if( !empty( $value ) ): ?>
            <img src="<?php echo $value; ?>" alt="" style="max-width:150px; height:auto; margin: 10px 0px;">
            <?php endif; ?>
        </div>
        <div style="clear: both;"></div>
    </div>
    <?php
}


/**
 * Admin footer
 */
add_action('admin_footer', function(){
    global $pagenow;

    if ( 'widgets.php' == $pagenow ) {
        ?>
        <script type="text/javascript">
            jQuery(document).on("click", '.wgt_src_upload_image', function(event) {
                event.preventDefault();
                var image = wp.media({
                    title: 'Upload Image',
                    multiple: false
                }).open();
                image.on('select', function() {
                    var uploaded_image = image.state().get('selection').first().toJSON();
                    jQuery('.wgt_src_uploaded_image_source').val(uploaded_image.url);
                    jQuery('.wgt_src_upload_image').text("Change Image");
                    jQuery('.wgt_src_remove_image').removeAttr("disabled");
                    jQuery('.wgt_src_image_priview').html(
                        '<img src="'+uploaded_image.url+'" alt="" style="max-width:150px; height:auto; margin: 10px 0px;">'
                    );
                    jQuery(event.target).parents('.widget').find('input[name="savewidget"]').removeAttr("disabled");
                });
            });

            jQuery(document).on("click", '.wgt_src_remove_image', function(e) {
                jQuery('.wgt_src_image_priview').html("");
                jQuery('.wgt_src_remove_image').attr("disabled", "disabled");
                jQuery('.wgt_src_upload_image').text("Upload");
                jQuery('.wgt_src_uploaded_image_source').val("");
                jQuery(this).parents('.widget').find('input[name="savewidget"]').removeAttr("disabled");
            });
        </script>
        <?php
    }
}, 20);
