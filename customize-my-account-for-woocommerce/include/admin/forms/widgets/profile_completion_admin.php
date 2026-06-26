	<div class="wcmamtx-setting-card">

		<div class="wcmamtx-card-header">
			<div>
				<h2><?php esc_html_e('Profile completion wizard','customize-my-account-for-woocommerce'); ?></h2>
				<p><?php esc_html_e('Encourage customer to complete their profile.','customize-my-account-for-woocommerce'); ?></p>

				<select class="wcmamtx_layout_select_override wcmamtx_layout_order_select_override" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[profilebox_override]" style="">			
					<option value="01" <?php if (isset($profilebox_override) && ($profilebox_override == 01)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Enable','customize-my-account-for-woocommerce'); ?>
					</option> 
					<option value="02" <?php if (isset($profilebox_override) && ($profilebox_override == 02)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Disable','customize-my-account-for-woocommerce'); ?>
					</option> 
			</select>
			</div>
		</div>

		<div class="wcmamtx-card-body" style="<?php if (isset($profilebox_override) && ($profilebox_override == 01)) { echo 'display:block;'; } else { echo 'display:none;'; } ?>">

			<select class="wcmamtx_layout_profilebox_select" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[profilebox_style]" style="display: none;">			
        		
        		<option value="01" vpreview="<?php echo wcmamtx_PLUGIN_URL; ?>assets/images/profilebox1.png" gtext="<?php echo esc_html__('Profile Wizard','customize-my-account-for-woocommerce'); ?>" <?php if (isset($profilebox_style) && ($profilebox_style == 01)) { echo 'selected'; } ?> disabled></option> 
        	</select>

		</div>

        


	</div>