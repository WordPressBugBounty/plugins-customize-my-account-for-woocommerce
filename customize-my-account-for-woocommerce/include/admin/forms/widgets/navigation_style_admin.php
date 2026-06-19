    <!-- Navigation -->
    <div class="wcmamtx-setting-card">

    	<div class="wcmamtx-card-header">
    		<h2><?php esc_html_e('Navigation Style','customize-my-account-for-woocommerce'); ?></h2>
    		<p><?php esc_html_e('Choose the account menu design.','customize-my-account-for-woocommerce'); ?></p>
    	</div>

    	<div class="wcmamtx-card-body">

    		<select class="wcmamtx_layout_navigation_select" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[nav_style]" style="display: none;">			
    			<option value="01" vpreview="" gtext="<?php echo esc_html__('Theme inherited (default)','customize-my-account-for-woocommerce'); ?>"  <?php if (isset($nav_style) && ($nav_style == 01)) { echo 'selected'; } ?>></option> 
    			<option value="02" vpreview="" gtext="<?php echo esc_html__('Clean','customize-my-account-for-woocommerce'); ?>"  <?php if (isset($nav_style) && ($nav_style == 02)) { echo 'selected'; } ?>></option>
    			
    			<option value="03" vpreview="https://www.sysbasics.com/wp-content/uploads/2026/06/screen-capture-2.webm" gtext="<?php echo esc_html__('Banking App','customize-my-account-for-woocommerce'); ?>"  <?php if (isset($nav_style) && ($nav_style == 03)) { echo 'selected'; } ?> disabled></option> 
    			
    		</select>

    		<a href="#" class="wcmamtx_template_override_a">
    			<?php echo esc_html__('Override from child theme or main theme ?','customize-my-account-for-woocommerce'); ?>
    		</a>
    		<p class="wcmamtx_template_override wcmamtx_layout_template_override_navigation alert alert-success">
    			<?php echo esc_html__('You can override this template easily from your child theme. Copy the file from','customize-my-account-for-woocommerce'); ?>&emsp;
    			<code><?php echo ''.wcmamtx_PLUGIN_URL.'templates/myaccount/navigation/<span class="wcmamtx_layout_navigation_override_no">'.$nav_style.'</span>.php'; ?></code>

    			<?php echo esc_html__('and paste it into your child theme','customize-my-account-for-woocommerce'); ?>&emsp;

    			<code><?php echo ''.get_stylesheet_directory().'/wcmamtx_template/navigation/<span class="wcmamtx_layout_navigation_override_no">'.$nav_style.'</span>.php'; ?></code>
    		</p>

    	</div>

    </div>