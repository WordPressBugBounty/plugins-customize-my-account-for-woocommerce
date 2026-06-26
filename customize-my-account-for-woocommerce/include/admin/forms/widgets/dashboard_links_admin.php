	<!-- Dashboard Style -->
	<div class="wcmamtx-setting-card">

		<div class="wcmamtx-card-header">
			<div>
				<h2><?php esc_html_e('Dashboard Links Style','customize-my-account-for-woocommerce'); ?></h2>
				<p><?php esc_html_e('Choose how dashboard links are displayed to customers.','customize-my-account-for-woocommerce'); ?></p>

				<select class="wcmamtx_layout_select_override wcmamtx_layout_order_select_override" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[dashlink_layout_override]" style="">			
					<option value="01" <?php if (isset($dashlink_layout_override) && ($dashlink_layout_override == 01)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Enable','customize-my-account-for-woocommerce'); ?>
					</option> 
					<option value="02" <?php if (isset($dashlink_layout_override) && ($dashlink_layout_override == 02)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Disable','customize-my-account-for-woocommerce'); ?>
					</option> 
			</select>
			</div>
		</div>

		<div class="wcmamtx-card-body" style="<?php if (isset($download_template_override) && ($download_template_override == 01)) { echo 'display:block;'; } else { echo 'display:none;'; } ?>">

			<select class="wcmamtx_layout_design_select" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[dash_style]" style="display: none;">			
				<option value="01" vpreview="" gtext="<?php echo esc_html__('Default','customize-my-account-for-woocommerce'); ?>"  <?php if (isset($dash_style) && ($dash_style == 01)) { echo 'selected'; } ?>></option> 
				<option value="02" vpreview="" gtext="<?php echo esc_html__('Classic','customize-my-account-for-woocommerce'); ?>"  <?php if (isset($dash_style) && ($dash_style == 02)) { echo 'selected'; } ?>></option> 
				<option value="03" vpreview="<?php echo wcmamtx_PLUGIN_URL; ?>assets/images/layout3.png" gtext="<?php echo esc_html__('Card','customize-my-account-for-woocommerce'); ?>"  <?php if (isset($dash_style) && ($dash_style == 03)) { echo 'selected'; } ?> disabled></option> 
				<option value="04" vpreview="<?php echo wcmamtx_PLUGIN_URL; ?>assets/images/layout4.png" gtext="<?php echo esc_html__('Tile','customize-my-account-for-woocommerce'); ?>"  <?php if (isset($dash_style) && ($dash_style == 04)) { echo 'selected'; } ?> disabled></option> 
			</select>

			

			<a href="#" class="wcmamtx_template_override_a">
				<?php echo esc_html__('Override from child theme or main theme ?','customize-my-account-for-woocommerce'); ?>
			</a>

			<p class="wcmamtx_template_override wcmamtx_layout_template_override alert alert-success">
				<?php echo esc_html__('You can override this template easily from your child theme. Copy the file from','customize-my-account-for-woocommerce'); ?>&emsp;
				<code><?php echo ''.wcmamtx_PLUGIN_URL.'include/frontend/dashlinks/<span class="wcmamtx_layout_template_override_no">'.$dash_style.'</span>.php'; ?></code>

				<?php echo esc_html__('and paste it into your child theme','customize-my-account-for-woocommerce'); ?>&emsp;

				<code><?php echo ''.get_stylesheet_directory().'/wcmamtx_template/dashlinks/<span class="wcmamtx_layout_template_override_no">'.$dash_style.'</span>.php'; ?></code>
			</p>


			<table>  




				<tr class="nav_header_widget_tr" style="">
					<td><label><?php echo esc_html__('Dashboard Links location priority on dashboard','customize-my-account-for-woocommerce'); ?></label> <br />
					</td>
					<td>
						<input type="number" class="" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[dashlinks_dashboard_priority]" value="<?php echo $dashlinks_dashboard_priority; ?>">

					</td>
				</tr>
			</table>

		</div>

        


	</div>