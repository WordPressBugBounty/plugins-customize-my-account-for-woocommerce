<?php 


$wcmamtx_layout = (array) get_option( 'wcmamtx_layout' );




$dash_style = isset($wcmamtx_layout['dash_style']) ? $wcmamtx_layout['dash_style'] : "01";
$sidebar_style = isset($wcmamtx_layout['sidebar_style']) ? $wcmamtx_layout['sidebar_style'] : "01";
$nav_style = isset($wcmamtx_layout['nav_style']) ? $wcmamtx_layout['nav_style'] : "01";
$order_style = isset($wcmamtx_layout['order_style']) ? $wcmamtx_layout['order_style'] : "01";
?> 

<table class="widefat wcmamtx_options_table">

	

	<tr>
		<td><label><?php echo esc_html__('Dashboard links style','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<select class="wcmamtx_layout_design_select" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[dash_style]" style="display: none;">			
					<option value="01" gtext="<?php echo esc_html__('Default','customize-my-account-for-woocommerce'); ?>"  <?php if (isset($dash_style) && ($dash_style == 01)) { echo 'selected'; } ?>></option> 
					<option value="02" gtext="<?php echo esc_html__('Classic','customize-my-account-for-woocommerce'); ?>"  <?php if (isset($dash_style) && ($dash_style == 02)) { echo 'selected'; } ?>></option> 
				    <option value="03" gtext="<?php echo esc_html__('Card','customize-my-account-for-woocommerce'); ?>"  <?php if (isset($dash_style) && ($dash_style == 03)) { echo 'selected'; } ?>></option> 
					<option value="04" gtext="<?php echo esc_html__('Tile','customize-my-account-for-woocommerce'); ?>"  <?php if (isset($dash_style) && ($dash_style == 04)) { echo 'selected'; } ?>></option> 
			</select>

			<p class="wcmamtx_layout_template_override alert alert-success">
				<?php echo esc_html__('You can override this template easily from your child theme. Copy the file from','customize-my-account-for-woocommerce'); ?>&emsp;
				<code><?php echo ''.wcmamtx_PLUGIN_URL.'include/frontend/dashlinks/<span class="wcmamtx_layout_template_override_no">'.$dash_style.'</span>.php'; ?></code>

				<?php echo esc_html__('and paste it into your child theme','customize-my-account-for-woocommerce'); ?>&emsp;

				<code><?php echo ''.get_stylesheet_directory().'/wcmamtx_template/dashlinks/<span class="wcmamtx_layout_template_override_no">'.$dash_style.'</span>.php'; ?></code>
			</p>
		</td>
	</tr>

	<tr>
		<td><label><?php echo esc_html__('Sidebar position','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<select class="wcmamtx_layout_sidebar_select" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[sidebar_style]" style="display: none;">			
					<option value="01" gtext="<?php echo esc_html__('Left Sidebar','customize-my-account-for-woocommerce'); ?>" <?php if (isset($sidebar_style) && ($sidebar_style == 01)) { echo 'selected'; } ?>></option> 
					<option value="02" gtext="<?php echo esc_html__('Right Sidebar','customize-my-account-for-woocommerce'); ?>" <?php if (isset($sidebar_style) && ($sidebar_style == 02)) { echo 'selected'; } ?>></option> 
			</select>

			
		</td>
	</tr>

	<tr>
		<td><label><?php echo esc_html__('Navigation style','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<select class="wcmamtx_layout_navigation_select" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[nav_style]" style="display: none;">			
					<option value="01" gtext="<?php echo esc_html__('Theme inherited (default)','customize-my-account-for-woocommerce'); ?>"  <?php if (isset($nav_style) && ($nav_style == 01)) { echo 'selected'; } ?>></option> 
					<option value="02" gtext="<?php echo esc_html__('Clean','customize-my-account-for-woocommerce'); ?>"  <?php if (isset($nav_style) && ($nav_style == 02)) { echo 'selected'; } ?>></option> 
			</select>

			<p class="wcmamtx_layout_template_override_navigation alert alert-success">
				<?php echo esc_html__('You can override this template easily from your child theme. Copy the file from','customize-my-account-for-woocommerce'); ?>&emsp;
				<code><?php echo ''.wcmamtx_PLUGIN_URL.'include/frontend/navigation/<span class="wcmamtx_layout_navigation_override_no">'.$nav_style.'</span>.php'; ?></code>

				<?php echo esc_html__('and paste it into your child theme','customize-my-account-for-woocommerce'); ?>&emsp;

				<code><?php echo ''.get_stylesheet_directory().'/wcmamtx_template/navigation/<span class="wcmamtx_layout_navigation_override_no">'.$nav_style.'</span>.php'; ?></code>
			</p>
		</td>
	</tr>

	<tr>
		<td><label><?php echo esc_html__('Orders template','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<select class="wcmamtx_layout_order_select" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[order_style]" style="display: none;">			
					<option value="01" gtext="<?php echo esc_html__('Optimzed','customize-my-account-for-woocommerce'); ?>" <?php if (isset($order_style) && ($order_style == 01)) { echo 'selected'; } ?>></option> 
					<option value="02" gtext="<?php echo esc_html__('Default','customize-my-account-for-woocommerce'); ?>" <?php if (isset($order_style) && ($order_style == 02)) { echo 'selected'; } ?>></option> 
			</select>

			<p class="wcmamtx_layout_template_override_order alert alert-success">
				<?php echo esc_html__('You can override this template easily from your child theme. Copy the file from','customize-my-account-for-woocommerce'); ?>&emsp;
				<code><?php echo ''.wcmamtx_PLUGIN_URL.'include/frontend/order/<span class="wcmamtx_layout_order_override_no">'.$order_style.'</span>.php'; ?></code>

				<?php echo esc_html__('and paste it into your child theme','customize-my-account-for-woocommerce'); ?>&emsp;

				<code><?php echo ''.get_stylesheet_directory().'/wcmamtx_template/order/<span class="wcmamtx_layout_order_override_no">'.$order_style.'</span>.php'; ?></code>
			</p>
		</td>
	</tr>
</table>