<?php 


$avatar_settings = (array) get_option( 'wcmamtx_avatar_settings' );

if (array_key_exists(0, $avatar_settings)) {
    $avatar_settings['round_avatar'] = "yes";
    $avatar_settings['intro_text_hello'] = "yes";
}
?> 

<table class="widefat wcmamtx_options_table">

    <tr>
		<td><label><?php echo esc_html__('Shortcode','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<code>[sysbasics_user_avatar]</code>
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
		<td><label><?php echo esc_html__('Do not Allow user to upload avatar','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<input type="checkbox" data-toggle="toggle"  data-on="<?php echo 'Yes'; ?>" data-off="<?php echo 'No'; ?>"  name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[allow_avatar_change]" value="yes" <?php if (isset($avatar_settings['allow_avatar_change']) && ($avatar_settings['allow_avatar_change'] == "yes")) { echo 'checked'; } ?>>
		</td>
			
	</tr>

	<tr class="wcmamtx_show_avatar_tr" style="<?php if (isset($avatar_settings['disable_avatar']) && ($avatar_settings['disable_avatar'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
		<td><label><?php echo esc_html__('Default avatar size','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<input type="range" min="96" max="350" value="<?php if (isset($avatar_settings['avatar_size']) ) { echo $avatar_settings['avatar_size']; } else { echo '250'; } ?>" name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[avatar_size]">
            &emsp;
			<?php echo esc_html__('Min height','customize-my-account-for-woocommerce'); ?>
			<input size="3" type="number" min="96" max="350" value="<?php if (isset($avatar_settings['min_height']) ) { echo $avatar_settings['min_height']; } else { echo '267'; } ?>" name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[min_height]">px

			&emsp;
			<?php echo esc_html__('Min width','customize-my-account-for-woocommerce'); ?>
			<input size="3"  type="number" min="96" max="350" value="<?php if (isset($avatar_settings['min_width']) ) { echo $avatar_settings['min_width']; } else { echo '267'; } ?>" name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[min_width]">px
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
		     $default_text3 = esc_html__('Click to upload','customize-my-account-for-woocommerce');

		    ?>
			<input type="text" class=""  name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[text1]" value="<?php if (isset($avatar_settings['text1']) && ($avatar_settings['text1'] != "")) { echo $avatar_settings['text1']; } else { echo $default_text1; } ?>">

			<input type="text" class=""  name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[text2]" value="<?php if (isset($avatar_settings['text2']) && ($avatar_settings['text2'] != "")) { echo $avatar_settings['text2']; } else { echo $default_text2; } ?>">

			<input type="text" class=""  name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[text3]" value="<?php if (isset($avatar_settings['text3']) && ($avatar_settings['text3'] != "")) { echo $avatar_settings['text3']; } else { echo $default_text3; } ?>">
		</td>
			
	</tr>

	<tr class="wcmamtx_show_avatar_tr" style="<?php if (isset($avatar_settings['disable_avatar']) && ($avatar_settings['disable_avatar'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
		<td><label><?php echo esc_html__('Hide username, login text after avatar','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<input type="checkbox" data-toggle="toggle"  data-on="<?php echo esc_html__('Yes','customize-my-account-for-woocommerce'); ?>" data-off="<?php echo esc_html__('No','customize-my-account-for-woocommerce'); ?>"  name="<?php  echo $this->wcmamtx_avatar_settings_page; ?>[intro_text_hello]" value="yes" <?php if (isset($avatar_settings['intro_text_hello']) && ($avatar_settings['intro_text_hello'] == "yes")) { echo 'checked'; } ?>>
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
                            <div  class="image-upload-div" idval="<?php echo $key; ?>" >
                                <input type="hidden" class="facility_thumbnail_id" name="<?php  echo esc_html__($this->wcmamtx_avatar_settings_page); ?>[upload_icon]" value="<?php if (isset($swatchimage)) { echo $swatchimage; } ?>"/>
                                <button type="submit" class="upload_image_button button"><?php echo esc_html__( 'Upload/Add image', 'wcva' ); ?></button>
                                <button type="submit" class="remove_image_button button"><?php echo esc_html__( 'Remove image', 'wcva' ); ?></button>
                            </div>
                        </div>


                    </td>

    </tr>

    <tr class="wcmamtx_show_avatar_tr" style="<?php if (isset($avatar_settings['disable_avatar']) && ($avatar_settings['disable_avatar'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
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
			$editor_content = isset($avatar_settings['content_avatar']) ? $avatar_settings['content_avatar'] : '<p style="text-align: center;">Hello <strong>{username}</strong> (not <strong>{username}</strong>? <a href="{user_logout_link}">Log out</a>)</p>';



			$editor_id      = 'wcmamtx_content_avatar';
			$editor_name    = ''.$this->wcmamtx_avatar_settings_page.'[content_avatar]';

			wp_editor( $editor_content, $editor_id, $settings = array(
				'textarea_name' => $editor_name,
                                'editor_height' => 180, // In pixels, takes precedence and has no default value
                                'textarea_rows' => 16,
                                'editor_width' => 180
                            ) ); 
                            ?>
                            <a target="_blank" class="wcmamtx_accordion_label_small" href="https://www.sysbasics.com/knowledge-base/list-of-content-variables/" class=""><?php  echo esc_html__('Supported Variables','customize-my-account-for-woocommerce'); ?>
                            </a> <a target="_blank" class="wcmamtx_accordion_label_small float_right" href="https://www.sysbasics.com/knowledge-base/create-your-own-custom-content-variable/" class=""><?php  echo esc_html__('Create your own custom variable','customize-my-account-for-woocommerce'); ?>
                        </a>
                    </td>

     </tr>


</table>

