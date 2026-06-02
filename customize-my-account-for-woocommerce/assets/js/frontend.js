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



         $vas('.wcmamtx_upload_avatar').on('click', function(event) {
       event.preventDefault();
       $vas('#mywcmamtx_modal').show();
       $vas('#mywcmamtx_modal_webcam').hide();
       return false;
      });

      $vas('.wcmamtx_modal_trigger_webcam').on('click', function(event) {
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

       
    

      $vas('.wcmamtx_modal_close').on('click', function() {
          $vas('#mywcmamtx_modal').hide();
      });

      $vas('.wcmamtx_modal_close_webcam').on('click', function() {
          $vas('#mywcmamtx_modal_webcam').hide();
      });
        
   


   $vas('.wcmamtx_modal_trigger_upload').on('click', function() {
      $vas('#wcmamtx_wp-user-file').trigger('click');
    });

   $vas('.wcmamtx_restore_default_link').on('click', function(event) {
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
                
                

                
            } else {

                    $vas('#wcmamtx_upload_response').text(response.data);

               
               
                    $vas('#wcmamtx_upload_response').show();
                    $vas("#custom-file-uploader").find("img.avatar.photo.modal_popup").attr("src",default_pic);
                    $vas("#custom-file-uploader").find("img.avatar.photo.modal_popup").attr("srcset",default_pic);
                   
                    
                    $vas(".wcmamtx_upload_div").find("img.avatar.photo").attr("src",default_pic);
                    $vas(".wcmamtx_upload_div").find("img.avatar.photo").attr("srcset",default_pic);
                    
                    $vas('#wcmamtx_upload_response').hide(200);

                    if (wcmamtxfrontend.mode == "gravtar") {
                      $vas(".wcmamtx_manage_gravtar_link").show();
                    }

                    
                    $vas(".wcmamtx_restore_default_link").hide();

                    $vas('#mywcmamtx_modal').hide();
            }
        }, 'json'); // Explicitly parse the response as JSON format
      
      return false;
      });


    $vas('#wcmamtx_wp-user-file').on('change', function() {
       var fileInput = $vas('#wcmamtx_wp-user-file')[0].files[0];
        if (!fileInput) {
            alert(wcmamtxfrontend.file_text);
            return;
        }

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
    });





    
  });

 
})( jQuery );

// Toggle the visibility of a dropdown menu
const toggleDropdown = (dropdown, menu, isOpen) => {
  dropdown.classList.toggle("open", isOpen);
  menu.style.height = isOpen ? `${menu.scrollHeight}px` : 0;
};

// Close all open dropdowns
const closeAllDropdowns = () => {
  document.querySelectorAll(".dropdown-container.open").forEach((openDropdown) => {
    toggleDropdown(openDropdown, openDropdown.querySelector(".dropdown-menu"), false);
  });
};

// Attach click event to all dropdown toggles
document.querySelectorAll(".dropdown-toggle").forEach((dropdownToggle) => {
  dropdownToggle.addEventListener("click", (e) => {
    e.preventDefault();

    const dropdown = dropdownToggle.closest(".dropdown-container");
    const menu = dropdown.querySelector(".dropdown-menu");
    const isOpen = dropdown.classList.contains("open");

    closeAllDropdowns(); // Close all open dropdowns
    toggleDropdown(dropdown, menu, !isOpen); // Toggle current dropdown visibility
  });
});