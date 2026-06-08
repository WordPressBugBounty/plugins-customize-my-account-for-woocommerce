<form id="custom-file-uploader">
	<?php
					$modal_popup = true;

					
					echo wcmamtx_get_avatar_default($profileuser,$avatar_size,$atts,$modal_popup);

					$allow_avatar_change = 'yes';

					$avatar_settings = (array) get_option( 'wcmamtx_avatar_settings' );

					if (isset($avatar_settings['allow_avatar_change']) && ($avatar_settings['allow_avatar_change'] == "yes")) {

						$allow_avatar_change = 'no';
					} else {
						$allow_avatar_change = 'yes';
					}

					$default_upload_icon = ''.wcmamtx_PLUGIN_URL.'assets/images/camera.svg';


					$swatchimage = isset($avatar_settings['upload_icon']) ? $avatar_settings['upload_icon'] : "";

					if (isset($swatchimage) && ($swatchimage != "")) {
						$swatchurl     = wp_get_attachment_thumb_url( $swatchimage );
					} 

					$default_upload_icon = isset($swatchurl) && ($swatchimage != "") ? $swatchurl : $default_upload_icon;

					$clickupload_text = esc_html__( 'Upload Photo', 'customize-my-account-for-woocommerce' );

					

					if (isset($avatar_settings['override_texts']) && ($avatar_settings['override_texts'] == "yes") && (isset($avatar_settings['text3'])) && ($avatar_settings['text3'] != "")) { 
						$clickupload_text = $avatar_settings['text3'];
					}

					$webcam_capture = isset($avatar_settings['webcam_capture']) && ($avatar_settings['webcam_capture'] == "yes") ? "yes": "no";

					

					if ($webcam_capture == "yes") {

						$usecamera_text = esc_html__( 'Use Camera', 'customize-my-account-for-woocommerce' );

						if (isset($avatar_settings['override_texts']) && ($avatar_settings['override_texts'] == "yes") && (isset($avatar_settings['text4'])) && ($avatar_settings['text4'] != "")) { 
							$usecamera_text = $avatar_settings['text4'];
						}

					}

					

					?>

					
                    <button type="button" class="button wcmamtx_modal_trigger_upload hide-if-no-js">
                    <?php echo $clickupload_text; ?>
                    </button>

                    <?php if ($webcam_capture == "yes") { ?>

                    	<button type="button" class="button wcmamtx_modal_trigger_webcam hide-if-no-js">
                    		<?php echo $usecamera_text; ?>
                    	</button>

                    <?php } ?>
					

					<?php

					$default_source = isset($avatar_settings['disable_gravtar']) && ($avatar_settings['disable_gravtar'] == "yes") ? "local" : "gravtar";

					$options = get_option( 'wcmamtx_upload_avatar_tab_caps' );
					if ( empty( $options['wcmamtx_upload_avatar_tab_caps'] ) || current_user_can( 'upload_files' ) ) {
				    // Nonce security ftw
						

				    // File upload input
						//echo '<p><input type="file" name="basic-user-avatar" class="wcmamtx_file_input_upload" id="basic-local-avatar" /></p>';

						if ( empty( $profileuser->sysbasics_user_avatar ) ) {
                            $restore_css = "display:none;";
                            $gravtar_css = "display:none;";
                            if ($default_source == "gravtar") {
                            	$gravtar_css = "display:;";
                            }
						} else {
                            $restore_css = "display:;";
                            $gravtar_css = "display:none;";
                            
						}

							if ($default_source == "gravtar") { 

								$gravtar_text = esc_html__( 'Manage Gravtar', 'customize-my-account-for-woocommerce' );

								if (isset($avatar_settings['override_texts']) && ($avatar_settings['override_texts'] == "yes") && (isset($avatar_settings['text2'])) && ($avatar_settings['text2'] != "")) { 
									$gravtar_text = $avatar_settings['text2'];
								}


								?>

								<a href="https://gravatar.com/profile" style="<?php echo $gravtar_css; ?>" target="_blank" class="wcmamtx_manage_gravtar_link"><i class="fa fa-refresh"></i><?php echo $gravtar_text; ?>
							    </a>

						    <?php }

						
							$restore_text = esc_html__( 'Restore Default', 'customize-my-account-for-woocommerce' );

							if (isset($avatar_settings['override_texts']) && ($avatar_settings['override_texts'] == "yes") && (isset($avatar_settings['text1'])) && ($avatar_settings['text1'] != "")) { 
								$restore_text = $avatar_settings['text1'];
							}
							?>

							<a href="#" style="<?php echo  $restore_css; ?>" class="wcmamtx_restore_default_link"><i class="fa fa-refresh"></i><?php echo $restore_text; ?>
						    </a>

						<?php
										

					

						

					} 
					?>
    <?php wp_nonce_field('ajax_file_upload_nonce', 'security_nonce'); ?>
    <input type="file" id="wcmamtx_wp-user-file" name="basic-user-avatar" accept="image/*" />
    <?php echo '<input type="submit" name="manage_avatar_submit" class="wcmamtx_update_avatar_btn" value="" />'; ?>
    <div id="wcmamtx_upload_response"></div>

</form>
<div id="cropper-wrapper" style="display:none;">
	<img id="cropper-image">
</div>

<button type="button" class="wcmamtx_crop_upload_icon" id="crop-avatar" style="display:none;">
	<?php echo esc_html__( 'Upload Photo', 'customize-my-account-for-woocommerce' ); ?>
</button>
    