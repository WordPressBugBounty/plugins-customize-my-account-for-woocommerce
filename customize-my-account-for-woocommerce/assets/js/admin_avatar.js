var $var = jQuery.noConflict();
(function( $var ) {
    'use strict';


            var parentId = $var('.image-upload-div').closest('div').attr('idval');
                 // Only show the "remove image" button when needed
                 var srcvalue    = $var('.facility_thumbnail_id').val();

                 if ( !srcvalue ){
                    jQuery('.remove_image_button').hide();
                 }  
                // Uploading files
                var file_frame;

                jQuery(document).on( 'click', '.upload_image_button', function( event ){
                   

                    event.preventDefault();

                    // If the media frame already exists, reopen it.
                    if ( file_frame ) {
                        file_frame.open();
                        return;
                    }

                    // Create the media frame.
                    file_frame = wp.media.frames.downloadable_file = wp.media({
                        title: wcmamtxadmin_avatar.uploadimage,
                        button: {
                            text: wcmamtxadmin_avatar.useimage,
                        },
                        multiple: false
                    });

                    // When an image is selected, run a callback.
                    file_frame.on( 'select', function() {
                        var attachment = file_frame.state().get('selection').first().toJSON();

                        jQuery('.facility_thumbnail_id').val( attachment.id );
                        jQuery('#facility_thumbnail img').attr('src', attachment.url );
                        jQuery('.imagediv img').attr('src', attachment.url );
                        jQuery('.remove_image_button').show();
                        jQuery('.wcva_imgsrc_sub_header').attr('src', attachment.url );
                    });

                    // Finally, open the modal.
                    file_frame.open();
                });

                jQuery(document).on( 'click', '.remove_image_button', function( event ){

                    jQuery('#facility_thumbnail img').attr('src', wcmamtxadmin_avatar.placeholder );
                    jQuery('.imagediv img').attr('src', '');
                    jQuery('.facility_thumbnail_id').val('');
                    jQuery('.remove_image_button').hide();
                    jQuery('.wcva_imgsrc_sub_header').attr('src', wcmamtxadmin_avatar.placeholder );
                    return false;
                });

                

     

})( jQuery );

$var( function() {

    $var(".wcmamtx_show_avatar_checkbox").on('change',function() {

        if ($var(this).prop("checked")) {
         $var(".wcmamtx_show_avatar_tr").show();
     } else {
        $var(".wcmamtx_show_avatar_tr").hide();
    }




    });

    $var(".wcmamtx_content_avatar_checkbox").on('change',function() {

        if ($var(this).prop("checked")) {
         $var(".wcmamtx_show_avatar_content_tr").show();
     } else {
        $var(".wcmamtx_show_avatar_content_tr").hide();
    }




    });

});