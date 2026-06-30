var $vas = jQuery.noConflict();
(function( $vas ) {
    'use strict';

    $vas(function() {

      $vas(".wcmamtx_dismiss_dashboard_text_notice").on('click',function(event) {
        
        event.preventDefault();

        $vas(".wcmamtx_notice_div").remove();

        $vas.ajax({
                data: {action: "wcmamtx_dismiss_dashboard_text_notice"  },
                type: 'POST',
                url: wcmamtxfrontend.ajax_url,
                success: function( response ) { 
                    
                }
        });

        return false;

      });


      $vas(".wcmamtx_dismiss_dashboard_text_notice2").on('click',function(event) {
        
        event.preventDefault();

        $vas(".wcmamtx_notice_div2").remove();

        $vas.ajax({
                data: {action: "wcmamtx_dismiss_dashboard_text_notice2"  },
                type: 'POST',
                url: wcmamtxfrontend.ajax_url,
                success: function( response ) { 
                    
                }
        });

        return false;

      });
        
      $vas(".wcmamtx_group").on('click',function(event) {
          event.preventDefault();

          var parentli = $vas(this).parents("li");

          if (parentli.hasClass("open")) {
             parentli.find("ul.wcmamtx_sub_level").hide();
             parentli.removeClass("open");
             parentli.addClass("closed");
             parentli.find("i.wcmamtx_group_fa").removeClass("fa-chevron-up").addClass("fa-chevron-down");

          } else if (parentli.hasClass("closed")) {

             $vas("li.wcmamtx_group2.open").find("ul.wcmamtx_sub_level").hide();
             $vas("li.wcmamtx_group2.open").find('.wcmamtx_group_fa').removeClass("fa-chevron-up").addClass("fa-chevron-down");
             $vas("li.wcmamtx_group2.open").removeClass("open").addClass("closed");
             


             parentli.find("ul.wcmamtx_sub_level").show();
             parentli.removeClass("closed");
             parentli.addClass("open");

             parentli.find("i.wcmamtx_group_fa").removeClass("fa-chevron-down").addClass("fa-chevron-up");
          }

          return false;
          
      });

        $vas(".wcmamtx_group_sub").on('click',function(event) {
          event.preventDefault();

          var parentli = $vas(this).parents("li");

          if (parentli.hasClass("open")) {
             parentli.find("ul.wcmamtx_sub_level").hide();
             parentli.removeClass("open");
             parentli.addClass("closed");
             parentli.find("i.wcmamtx_group_fa").removeClass("fa-chevron-up").addClass("fa-chevron-down");

          } else if (parentli.hasClass("closed")) {

             $vas("li.wcmamtx_group2_sub.open").find("ul.wcmamtx_sub_level").hide();
             $vas("li.wcmamtx_group2_sub.open").find('.wcmamtx_group_fa').removeClass("fa-chevron-up").addClass("fa-chevron-down");
             $vas("li.wcmamtx_group2_sub.open").removeClass("open").addClass("closed");
             


             parentli.find("ul.wcmamtx_sub_level").show();
             parentli.removeClass("closed");
             parentli.addClass("open");

             parentli.find("i.wcmamtx_group_fa").removeClass("fa-chevron-down").addClass("fa-chevron-up");
          }

          return false;
          
      });



        $vas(document).on('click', '.wcmamtx_upload_avatar', function(event) {
            event.preventDefault();
            $vas('#mywcmamtx_modal').show();
            $vas('#mywcmamtx_modal_webcam').hide();
            $vas('#cropper-wrapper').hide();
            return false;
        });

       $vas(document).on('click', '.wcmamtx_modal_trigger_webcam', function(event) {
            event.preventDefault();
            $vas('#mywcmamtx_modal_webcam').show();
       $vas('#mywcmamtx_modal').hide();

       if( jQuery('#web_cam').length ){

        Webcam.set({
            width: 300,
            height: 300,
            image_format: 'jpeg',
            jpeg_quality: 90
        });

        Webcam.attach( '.my_camera' );


        jQuery('.takeimage').click(function(){
            Webcam.snap( function(data_uri) {
                $vas(".image-tag").val(data_uri);
                document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
                document.getElementById('web_cam_submit').style.display = 'block';
            } );

        });

        Webcam.on('error', function(err) {
            $vas("#mywcmamtx_modal_webcam").addClass("blocked");
            $vas("#mywcmamtx_modal_webcam").find(".my_camera").addClass("blocked");
            $vas("#mywcmamtx_modal_webcam").find("div.wcmamtx_modal-content.webcam").addClass("blocked");
            //console.error("Webcam.js Error:", err);
            $vas(".wcmamtx_modal-content.webcam").find(".takeimage").hide();
            $vas(".wcmamtx_modal-content.webcam").find(".wcmamtx_no_camera_message").show();
         
            
        });

       }
        return false;
       });

       
    

      $vas(document).on('click', '.wcmamtx_modal_close', function() {
          $vas('#mywcmamtx_modal').hide();
          $vas('#custom-file-uploader').show();
          $vas('#crop-avatar').hide();
      });

      $vas(document).on('click', '.wcmamtx_modal_close_webcam', function() {
          $vas('#mywcmamtx_modal_webcam').hide();
      });
        
   


   $vas(document).on('click', '.wcmamtx_modal_trigger_upload', function() {
      $vas('#wcmamtx_wp-user-file').trigger('click');
    });

   $vas(document).on('click', '.wcmamtx_restore_default_link', function(event) {
        event.preventDefault();

        $vas('#wcmamtx_upload_response').text(wcmamtxfrontend.restoring_text);
        $vas('#wcmamtx_upload_response').show();


      // Setup data object to send over to WordPress backend
        var requestData = {
            action: 'wcmamtx_restore_avatar_function', // The hook trigger name
            nonce: wcmamtxfrontend.nonce     // Custom data payload
        };

        var default_pic = wcmamtxfrontend.default_pic;

        // Fire the AJAX request
        $vas.post(wcmamtxfrontend.ajax_url, requestData, function(response) {
           
            if (response.success) {
                
                   $vas('#wcmamtx_upload_response').text(response.data);

               
               
                    $vas('#wcmamtx_upload_response').show();


    

                    if (wcmamtxfrontend.mode == "gravtar") {
                         var baseUrl = default_pic.split("?")[0]; 
                         default_pic =  baseUrl + "?s=200&time=" + new Date().getTime();

                    }
    

                    $vas("#custom-file-uploader").find("img.avatar.photo.modal_popup").attr("src",default_pic);
                    $vas("#custom-file-uploader").find("img.avatar.photo.modal_popup").attr("srcset",default_pic);
                   
                    
                    $vas(".wcmamtx_upload_div").find("img.avatar.photo").attr("src",default_pic);
                    $vas(".wcmamtx_upload_div").find("img.avatar.photo").attr("srcset",default_pic);


                    


                    $vas("li#wp-admin-bar-my-account").find("img.avatar.photo").attr("src",default_pic);
                    $vas("li#wp-admin-bar-user-info").find("img.avatar.photo").attr("src",default_pic);

                    $vas("li#wp-admin-bar-my-account").find("img.avatar.photo").attr("srcset",default_pic);
                    $vas("li#wp-admin-bar-user-info").find("img.avatar.photo").attr("srcset",default_pic);


                    $vas("li.wcmamtx_menu_logged_in").find("img.avatar.photo").attr("src",default_pic);
                    $vas("li.wcmamtx_menu_logged_in").find("img.avatar.photo").attr("src",default_pic);

                    $vas("li.wcmamtx_menu_logged_in").find("img.avatar.photo").attr("srcset",default_pic);
                    $vas("li.wcmamtx_menu_logged_in").find("img.avatar.photo").attr("srcset",default_pic);

                    
                    
                    $vas('#wcmamtx_upload_response').hide(200);

                    if (wcmamtxfrontend.mode == "gravtar") {
                      $vas(".wcmamtx_manage_gravtar_link").show();
                    }

                    
                    $vas(".wcmamtx_restore_default_link").hide();

                    $vas('#mywcmamtx_modal').hide();

                
            } else {

                    $vas('#wcmamtx_upload_response').text(response.data);

               
               
                    $vas('#wcmamtx_upload_response').show();


    

                    
            }
        }, 'json'); // Explicitly parse the response as JSON format
      
      return false;
      });



          let cropper;

   $vas(document).on('click', '#crop-avatar', function() {
    const canvas = cropper.getCroppedCanvas({
        width: 300,
        height: 300
    });
    canvas.toBlob(function(blob) {
        let formData = new FormData();
        formData.append('security', wcmamtxfrontend.nonce);
        formData.append('action', 'wcmam_save_avatar');
        formData.append('avatar', blob, 'avatar.png');
        $vas.ajax({
            url: wcmamtxfrontend.ajax_url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                location.reload();
            }
        });
    });
   });

   $vas(document).on('change', '#wcmamtx_wp-user-file', function() {
        var fileInput = $vas('#wcmamtx_wp-user-file')[0].files[0];
        if (!fileInput) {
            alert(wcmamtxfrontend.file_text);
            return;
        }


        if (wcmamtxfrontend.allow_cropper == "yes") {

            $vas('#custom-file-uploader').hide();
            $vas('#crop-avatar').show();

            const reader = new FileReader();

            reader.onload = function(event) {

                $vas('#cropper-wrapper').show();

                $vas('#cropper-image').attr(
                    'src',
                    event.target.result
                    );

                if (cropper) {
                    cropper.destroy();
                }

                cropper = new Cropper(
                    document.getElementById('cropper-image'),
                    {
                        aspectRatio: 1,
                        viewMode: 1,
                        autoCropArea: 1,
                        responsive: true
                    }
                    );
            };

            reader.readAsDataURL(fileInput);

        } else {

        

        // 1. Pack data into FormData object
        var formData = new FormData();
        formData.append('action', 'handle_ajax_file_upload'); // Required by WP
        formData.append('security', $vas('#security_nonce').val());
        formData.append('file_data', fileInput);

        // 2. Dispatch AJAX request
        $vas.ajax({
            url: wcmamtxfrontend.ajax_url, // Ensure global ajaxurl is defined or localized
            type: 'POST',
            data: formData,
            processData: false, // Prevents jQuery from converting data into a query string
            contentType: false, // Tells browser to use multipart/form-data boundary
            beforeSend: function() {
                $vas('#wcmamtx_upload_response').text(wcmamtxfrontend.uploading_text);
                $vas('#wcmamtx_upload_response').show();
            },
            success: function(response) {
                if (response.success) {
                    var sucess_data = response.data;
                    
                    $vas("#custom-file-uploader").find("img.avatar.photo.modal_popup").attr("src",sucess_data.url);
                    $vas("#custom-file-uploader").find("img.avatar.photo.modal_popup").attr("srcset",sucess_data.url);
                    
                    $vas('#wcmamtx_upload_response').html('<span style="color:green;">' + sucess_data.message + '</span>');
                    
                    $vas(".wcmamtx_upload_div").find("img.avatar.photo").attr("src",sucess_data.url);
                    $vas(".wcmamtx_upload_div").find("img.avatar.photo").attr("srcset",sucess_data.url);


                    $vas("li#wp-admin-bar-my-account").find("img.avatar.photo").attr("src",sucess_data.url);
                    $vas("li#wp-admin-bar-user-info").find("img.avatar.photo").attr("src",sucess_data.url);

                    $vas("li#wp-admin-bar-my-account").find("img.avatar.photo").attr("srcset",sucess_data.url);
                    $vas("li#wp-admin-bar-user-info").find("img.avatar.photo").attr("srcset",sucess_data.url);


                     $vas("li.wcmamtx_menu_logged_in").find("img.avatar.photo").attr("src",sucess_data.url);
                    $vas("li.wcmamtx_menu_logged_in").find("img.avatar.photo").attr("src",sucess_data.url);

                    $vas("li.wcmamtx_menu_logged_in").find("img.avatar.photo").attr("srcset",sucess_data.url);
                    $vas("li.wcmamtx_menu_logged_in").find("img.avatar.photo").attr("srcset",sucess_data.url);

                    
                    

                    $vas(".wcmamtx_manage_gravtar_link").hide();
                    $vas(".wcmamtx_restore_default_link").show();
                    $vas('#wcmamtx_upload_response').show();


                    $vas('#wcmamtx_upload_response').hide(200);

                    $vas('#mywcmamtx_modal').hide();


                } else {
                    $vas('#wcmamtx_upload_response').html('<span style="color:red;">' + response.data + '</span>');
                    $vas('#wcmamtx_upload_response').show();
                    $vas('#wcmamtx_upload_response').hide(10000);
                }

                

                
            },
            error: function() {
                $vas('#wcmamtx_upload_response').text(wcmamtxfrontend.error_text);

                $vas('#wcmamtx_upload_response').show();

                $vas('#wcmamtx_upload_response').hide(10000);
            }
        });
        

        }

    });





    
  });






 
})( jQuery );


jQuery(function($){

    $('.wcmam-order-filters button').on('click', function(){

        var filter = $(this).data('filter');

        $('.wcmam-order-filters button').removeClass('active');
        $(this).addClass('active');

        if(filter === 'all'){

            $('.wcmam-order-card').fadeIn(200);

        } else {

            $('.wcmam-order-card').hide();

            $('.wcmam-order-card[data-status="'+filter+'"]')
            .fadeIn(200);

        }

    });


    $(".wcmam-order-filters button").each(function(index, element) {
        var filter = $(this).data('filter');

        if (!$('.wcmam-order-card[data-status="'+filter+'"]').length) {
            if ((filter != "all") && (!$(this).hasClass("wcmam-date-range-btn"))) {
                $(this).addClass('wcmamtx_hidden_order_status');
            }
            
        }


    });

});