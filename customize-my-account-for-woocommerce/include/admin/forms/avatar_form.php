<?php 


$avatar_settings = (array) get_option( 'wcmamtx_avatar_settings' );

if (array_key_exists(0, $avatar_settings)) {
    $avatar_settings['round_avatar'] = "yes";
    $avatar_settings['intro_text_hello'] = "yes";
    $avatar_settings['disable_avatar'] = "yes";
    $avatar_settings['custom_avatar_content'] = "yes";
    $avatar_settings['avatar_size'] = "150";
    $avatar_settings['min_height']  = "150";
    $avatar_settings['min_width']   = "150";
    $avatar_settings['webcam_capture'] = "yes";

    $avatar_settings['max_height']  = "150";
    $avatar_settings['max_width']   = "150";

}
?> 

<table class="widefat wcmamtx_options_table">

    <tr>
		<td><label><?php echo esc_html__('Shortcode','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<code>[sysbasics_user_avatar size="200"]</code>
			<p><?php echo esc_html__('Use this shortcode to insert this user avatar outside my account page and woocommerce','customize-my-account-for-woocommerce'); ?></p>
		</td>
			
	</tr>


	<tr>
		<td><label><?php echo esc_html__('Display avatar','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<input type="checkbox" data-toggle="toggle"  data-on="<?php echo 'Yes'; ?>" data-off="<?php echo 'No'; ?>" class="wcmamtx_show_avatar_checkbox" name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[disable_avatar]" value="yes" <?php if (isset($avatar_settings['disable_avatar']) && ($avatar_settings['disable_avatar'] == "yes")) { echo 'checked'; } ?>>
		</td>
			
	</tr>


	<tr class="wcmamtx_show_avatar_tr" style="<?php if (isset($avatar_settings['disable_avatar']) && ($avatar_settings['disable_avatar'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
		<td><label><?php echo esc_html__('Do not Allow user to upload avatar','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<input type="checkbox" data-toggle="toggle"  data-on="<?php echo 'Yes'; ?>" data-off="<?php echo 'No'; ?>"  name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[allow_avatar_change]" value="yes" <?php if (isset($avatar_settings['allow_avatar_change']) && ($avatar_settings['allow_avatar_change'] == "yes")) { echo 'checked'; } ?>>
		</td>
			
	</tr>

	<tr class="wcmamtx_show_avatar_tr" style="<?php if (isset($avatar_settings['disable_avatar']) && ($avatar_settings['disable_avatar'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
		<td><label><?php echo esc_html__('Round avatar instead of square','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<input type="checkbox" data-toggle="toggle"  data-on="<?php echo 'Yes'; ?>" data-off="<?php echo 'No'; ?>"  name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[round_avatar]" value="yes" <?php if (isset($avatar_settings['round_avatar']) && ($avatar_settings['round_avatar'] == "yes")) { echo 'checked'; } ?>>
		</td>
			
	</tr>

	<tr class="wcmamtx_show_avatar_tr" style="<?php if (isset($avatar_settings['disable_avatar']) && ($avatar_settings['disable_avatar'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
		<td><label><?php echo esc_html__('Do not use Gravtar as default avatar','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<input type="checkbox" data-toggle="toggle"  data-on="<?php echo 'Yes'; ?>" data-off="<?php echo 'No'; ?>"  name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[disable_gravtar]" value="yes" <?php if (isset($avatar_settings['disable_gravtar']) && ($avatar_settings['disable_gravtar'] == "yes")) { echo 'checked'; } ?>>
		</td>
			
	</tr>

	<tr class="wcmamtx_show_avatar_tr" style="<?php if (isset($avatar_settings['disable_avatar']) && ($avatar_settings['disable_avatar'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
		<td><label><?php echo esc_html__('Max image size','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<input size="3" type="number" value="<?php if (isset($avatar_settings['max_size']) ) { echo $avatar_settings['max_size']; } else { echo '1024'; } ?>" name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[max_size]">KB
		</td>
			
	</tr>

	<tr class="wcmamtx_show_avatar_tr" style="<?php if (isset($avatar_settings['disable_avatar']) && ($avatar_settings['disable_avatar'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
		<td><label><?php echo esc_html__('Allowed Formats','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<?php

			$default_options_format = array ( 0 => 'jpg', 1 => 'jpeg', 2 => 'jpe', 3 => 'gif', 4 => 'png', 5 => 'webp' );
			
			$all_format_options = array(
				'jpg'  => 'JPG',
				'jpeg' => 'JPEG',
				'jpe'  => 'JPE',
				'gif'  => 'GIF',
				'png'  => 'PNG',
				'webp' => 'WEBP',
			);

			$chosen_formats = isset($avatar_settings['allowed_formats']) ? $avatar_settings['allowed_formats'] : $default_options_format;

		


			?>
			<select class="wcmamtx_allowed_formats_multiselect" name="<?php  echo esc_html__($this->wcmamtx_avatar_settings_page); ?>[allowed_formats][]" multiple>
				

				<?php foreach ($all_format_options as $akey=>$avalue) { ?>
					<option value="<?php echo $akey; ?>" <?php if (in_array($akey, $chosen_formats)) { echo 'selected'; } ?>>
						<?php echo $avalue; ?>
					</option>
				<?php } ?>

			</select>
		</td>
			
	</tr>

	


	<tr class="wcmamtx_show_avatar_tr" style="<?php if (isset($avatar_settings['disable_avatar']) && ($avatar_settings['disable_avatar'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
		<td><label><?php echo esc_html__('Webcam capture','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<input type="checkbox" data-toggle="toggle"  data-on="<?php echo 'Yes'; ?>" data-off="<?php echo 'No'; ?>"  name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[webcam_capture]" value="yes" <?php if (isset($avatar_settings['webcam_capture']) && ($avatar_settings['webcam_capture'] == "yes")) { echo 'checked'; } ?>>
		</td>
			
	</tr>


	<tr class="wcmamtx_show_avatar_tr" style="<?php if (isset($avatar_settings['disable_avatar']) && ($avatar_settings['disable_avatar'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
		<td><label><?php echo esc_html__('Enable cropping','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<input type="checkbox" data-toggle="toggle"  data-on="<?php echo 'Yes'; ?>" data-off="<?php echo 'No'; ?>"  name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[enable_cropping]" value="yes" <?php if (isset($avatar_settings['enable_cropping']) && ($avatar_settings['enable_cropping'] == "yes")) { echo 'checked'; } ?>>
		</td>
			
	</tr>

	<tr class="wcmamtx_show_avatar_tr" style="<?php if (isset($avatar_settings['disable_avatar']) && ($avatar_settings['disable_avatar'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
		<td><label><?php echo esc_html__('Default avatar size','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<input type="range" min="96" max="350" value="<?php if (isset($avatar_settings['avatar_size']) ) { echo $avatar_settings['avatar_size']; } else { echo '200'; } ?>" name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[avatar_size]">
            &emsp;
			<?php echo esc_html__('Min height','customize-my-account-for-woocommerce'); ?>
			<input size="3" type="number" min="96" max="350" value="<?php if (isset($avatar_settings['min_height']) ) { echo $avatar_settings['min_height']; } else { echo '150'; } ?>" name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[min_height]">px

			&emsp;
			<?php echo esc_html__('Min width','customize-my-account-for-woocommerce'); ?>
			<input size="3"  type="number" min="96" max="350" value="<?php if (isset($avatar_settings['min_width']) ) { echo $avatar_settings['min_width']; } else { echo '150'; } ?>" name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[min_width]">px

			&emsp;
			<?php echo esc_html__('Min height','customize-my-account-for-woocommerce'); ?>
			<input size="3" type="number" min="96" max="350" value="<?php if (isset($avatar_settings['max_height']) ) { echo $avatar_settings['max_height']; } else { echo '200'; } ?>" name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[max_height]">px

			&emsp;
			<?php echo esc_html__('Min width','customize-my-account-for-woocommerce'); ?>
			<input size="3"  type="number" min="96" max="350" value="<?php if (isset($avatar_settings['max_width']) ) { echo $avatar_settings['max_width']; } else { echo '200'; } ?>" name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[max_width]">px
		</td>
			
	</tr>

	<tr class="wcmamtx_show_avatar_tr" style="<?php if (isset($avatar_settings['disable_avatar']) && ($avatar_settings['disable_avatar'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
		<td><label><?php echo esc_html__('Override default avatar texts','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<input type="checkbox" class="wcmamtx_override_texts_checkbox" data-toggle="toggle"  data-on="<?php echo esc_html__('Yes','customize-my-account-for-woocommerce'); ?>" data-off="<?php echo esc_html__('No','customize-my-account-for-woocommerce'); ?>"  name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[override_texts]" value="yes" <?php if (isset($avatar_settings['override_texts']) && ($avatar_settings['override_texts'] == "yes")) { echo 'checked'; } ?>>
		</td>
			
	</tr>

	<tr class="wcmamtx_override_texts_tr" style="<?php if (isset($avatar_settings['override_texts']) && ($avatar_settings['override_texts'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
		<td><label><?php echo esc_html__('Default texts','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td><?php

		     $default_text1 = esc_html__('Restore Default','customize-my-account-for-woocommerce');
		     $default_text2 = esc_html__('Manage Gravtar','customize-my-account-for-woocommerce');
		     $default_text3 = esc_html__('Upload Photo','customize-my-account-for-woocommerce');
		     $default_text4 = esc_html__('Use Camera','customize-my-account-for-woocommerce');

		    ?>
			<input type="text" class=""  name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[text1]" value="<?php if (isset($avatar_settings['text1']) && ($avatar_settings['text1'] != "")) { echo $avatar_settings['text1']; } else { echo $default_text1; } ?>">

			<input type="text" class=""  name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[text2]" value="<?php if (isset($avatar_settings['text2']) && ($avatar_settings['text2'] != "")) { echo $avatar_settings['text2']; } else { echo $default_text2; } ?>">

			<input type="text" class=""  name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[text3]" value="<?php if (isset($avatar_settings['text3']) && ($avatar_settings['text3'] != "")) { echo $avatar_settings['text3']; } else { echo $default_text3; } ?>">
		

			<input type="text" class=""  name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[text4]" value="<?php if (isset($avatar_settings['text4']) && ($avatar_settings['text4'] != "")) { echo $avatar_settings['text4']; } else { echo $default_text4; } ?>">
		</td>
			
	</tr>



	<tr class="wcmamtx_show_avatar_tr" style="<?php if (isset($avatar_settings['disable_avatar']) && ($avatar_settings['disable_avatar'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
                    <td>
                        <label class=""><?php  echo esc_html__('Custom Camera Icon','customize-my-account-for-woocommerce'); ?></label>
                    </td>
                    <td>
                        <?php

                        $upload_path = wp_upload_dir();

                        

                        $swatchimage = isset($avatar_settings['upload_icon']) ? $avatar_settings['upload_icon'] : "";

                         if (isset($swatchimage)) {
                            $swatchurl     = wp_get_attachment_thumb_url( $swatchimage );
                         } 
                        ?>
                        <div class="facility_thumbnail" id="facility_thumbnail" style="float:left;">
                            <img src="<?php if (isset($swatchurl) && ($swatchurl != '')) { echo $swatchurl; } else { echo wcmamtx_placeholder_img_src(); }  ?>" width="60px" height="60px" />
                            <div  class="image-upload-div" >
                                <input type="hidden" class="facility_thumbnail_id" name="<?php  echo esc_html__($this->wcmamtx_avatar_settings_page); ?>[upload_icon]" value="<?php if (isset($swatchimage)) { echo $swatchimage; } ?>"/>
                                <button type="submit" class="upload_image_button button"><?php echo esc_html__( 'Upload/Add image', 'customize-my-account-for-woocommerce' ); ?></button>
                                <button type="submit" class="remove_image_button button"><?php echo esc_html__( 'Remove image', 'customize-my-account-for-woocommerce' ); ?></button>
                            </div>
                        </div>


                    </td>

    </tr>

    <tr class="" style="">
		<td><label><?php echo esc_html__('Custom content after avatar','customize-my-account-for-woocommerce'); ?></label>
		</td>
		<td>
			<input type="checkbox" data-toggle="toggle" class="wcmamtx_content_avatar_checkbox" data-on="<?php echo 'Yes'; ?>" data-off="<?php echo 'No'; ?>"  name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[custom_avatar_content]" value="yes" <?php if (isset($avatar_settings['custom_avatar_content']) && ($avatar_settings['custom_avatar_content'] == "yes")) { echo 'checked'; } ?>>
        </td>
			
	</tr>

	<tr class="wcmamtx_show_avatar_content_tr" style="<?php if (isset($avatar_settings['custom_avatar_content']) && ($avatar_settings['custom_avatar_content'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
		<td><label><?php echo esc_html__('Custom content','customize-my-account-for-woocommerce'); ?></label>
		</td>
		<td>
			<?php 
			$editor_content = isset($avatar_settings['content_avatar']) ? $avatar_settings['content_avatar'] : '<p class="wcmamtx_default_text_below_avatar" style="text-align: center;">Hello <strong>{username}</strong> (not <strong>{username}</strong>? <a href="{user_logout_link}">Log out</a>)</p>';



			$editor_id      = 'wcmamtx_content_avatar';
			$editor_name    = ''.$this->wcmamtx_avatar_settings_page.'[content_avatar]';

			wp_editor( $editor_content, $editor_id, $settings = array(
				'textarea_name' => $editor_name,
                                'editor_height' => 180, // In pixels, takes precedence and has no default value
                                'textarea_rows' => 16,
                                'editor_width' => 180,
                                'wpautop'       => true,
                            ) ); 
                            ?>
                            <a target="_blank" class="wcmamtx_accordion_label_small" href="https://www.sysbasics.com/knowledge-base/list-of-content-variables/" class=""><?php  echo esc_html__('Supported Variables','customize-my-account-for-woocommerce'); ?>
                            </a> <a target="_blank" class="wcmamtx_accordion_label_small float_right" href="https://www.sysbasics.com/knowledge-base/create-your-own-custom-content-variable/" class=""><?php  echo esc_html__('Create your own custom variable','customize-my-account-for-woocommerce'); ?>
                        </a>
                    </td>

     </tr>


</table>

