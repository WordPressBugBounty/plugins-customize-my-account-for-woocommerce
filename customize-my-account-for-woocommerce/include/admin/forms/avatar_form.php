<?php 


$avatar_settings = (array) get_option( 'wcmamtx_avatar_settings' );


?> 

<table class="widefat wcmamtx_options_table">




	<tr>
		<td><label><?php echo esc_html__('Display avatar before navigation','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<input type="checkbox" data-toggle="toggle"  data-on="<?php echo esc_html__('Yes','customize-my-account-for-woocommerce'); ?>" data-off="<?php echo esc_html__('No','customize-my-account-for-woocommerce'); ?>" class="wcmamtx_show_avatar_checkbox" name="<?php  echo esc_html__($this->wcmamtx_avatar_settings_page); ?>[disable_avatar]" value="yes" <?php if (isset($avatar_settings['disable_avatar']) && ($avatar_settings['disable_avatar'] == "yes")) { echo 'checked'; } ?>>
			
	</tr>

	<tr>
		<td><label><?php echo esc_html__('Allow user to upload avatar','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<input type="checkbox" data-toggle="toggle"  data-on="<?php echo esc_html__('Yes','customize-my-account-for-woocommerce'); ?>" data-off="<?php echo esc_html__('No','customize-my-account-for-woocommerce'); ?>" class="wcmamtx_show_avatar_checkbox" name="<?php  echo esc_html__($this->wcmamtx_avatar_settings_page); ?>[allow_avatar_change]" value="yes" <?php if (isset($avatar_settings['allow_avatar_change']) && ($avatar_settings['allow_avatar_change'] == "yes")) { echo 'checked'; } ?>>
			
	</tr>

	<tr>
		<td><label><?php echo esc_html__('Default avatar size','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			

			<input type="range" min="96" max="250" value="<?php if (isset($avatar_settings['avatar_size']) ) { echo $avatar_settings['avatar_size']; } else { echo '96'; } ?>" name="<?php  echo esc_html__($this->wcmamtx_avatar_settings_page); ?>[avatar_size]">
			
	</tr>

</table>

