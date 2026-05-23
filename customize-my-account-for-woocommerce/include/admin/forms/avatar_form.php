<?php 


$avatar_settings = (array) get_option( 'wcmamtx_avatar_settings' );


?> 

<table class="widefat wcmamtx_options_table">




	<tr>
		<td><label><?php echo esc_html__('Display avatar','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<input type="checkbox" data-toggle="toggle"  data-on="<?php echo 'Yes'; ?>" data-off="<?php echo 'No'; ?>" class="wcmamtx_show_avatar_checkbox" name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[disable_avatar]" value="yes" <?php if (isset($avatar_settings['disable_avatar']) && ($avatar_settings['disable_avatar'] == "yes")) { echo 'checked'; } ?>>
			
	</tr>

	<tr>
		<td><label><?php echo esc_html__('Do not Allow user to upload avatar','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<input type="checkbox" data-toggle="toggle"  data-on="<?php echo 'Yes'; ?>" data-off="<?php echo 'No'; ?>" class="wcmamtx_show_avatar_checkbox" name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[allow_avatar_change]" value="yes" <?php if (isset($avatar_settings['allow_avatar_change']) && ($avatar_settings['allow_avatar_change'] == "yes")) { echo 'checked'; } ?>>
			
	</tr>

	<tr>
		<td><label><?php echo esc_html__('Default avatar size','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			

			<input type="range" min="96" max="350" value="<?php if (isset($avatar_settings['avatar_size']) ) { echo $avatar_settings['avatar_size']; } else { echo '250'; } ?>" name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[avatar_size]">
			
	</tr>

	<tr>
		<td><label><?php echo esc_html__('Hide username, login text after avatar','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<input type="checkbox" data-toggle="toggle"  data-on="<?php echo esc_html__('Yes','customize-my-account-for-woocommerce'); ?>" data-off="<?php echo esc_html__('No','customize-my-account-for-woocommerce'); ?>" class="wcmamtx_show_avatar_checkbox" name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[intro_text_hello]" value="yes" <?php if (isset($avatar_settings['intro_text_hello']) && ($avatar_settings['intro_text_hello'] == "yes")) { echo 'checked'; } ?>>
			
	</tr>

	<tr >
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
                            <div  class="image-upload-div" idval="<?php echo $key; ?>" >
                                <input type="hidden" class="facility_thumbnail_id" name="<?php  echo esc_html__($this->wcmamtx_avatar_settings_page); ?>[upload_icon]" value="<?php if (isset($swatchimage)) { echo $swatchimage; } ?>"/>
                                <button type="submit" class="upload_image_button button"><?php echo esc_html__( 'Upload/Add image', 'wcva' ); ?></button>
                                <button type="submit" class="remove_image_button button"><?php echo esc_html__( 'Remove image', 'wcva' ); ?></button>
                            </div>
                        </div>


                    </td>

    </tr>


</table>

