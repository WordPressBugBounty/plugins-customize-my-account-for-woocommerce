<?php 


$wcmamtx_layout = (array) get_option( 'wcmamtx_layout' );




$dash_style = isset($wcmamtx_layout['dash_style']) ? $wcmamtx_layout['dash_style'] : "01";
$sidebar_style = isset($wcmamtx_layout['sidebar_style']) ? $wcmamtx_layout['sidebar_style'] : "01";
$nav_style = isset($wcmamtx_layout['nav_style']) ? $wcmamtx_layout['nav_style'] : "01";
$order_style = isset($wcmamtx_layout['order_style']) ? $wcmamtx_layout['order_style'] : "01";
$order_template_override = isset($wcmamtx_layout['order_template_override']) ? $wcmamtx_layout['order_template_override'] : "01";

$download_style = isset($wcmamtx_layout['download_style']) ? $wcmamtx_layout['download_style'] : "01";

$download_template_override = isset($wcmamtx_layout['download_template_override']) ? $wcmamtx_layout['download_template_override'] : "01";

$dashlink_layout_override = isset($wcmamtx_layout['dashlink_layout_override']) ? $wcmamtx_layout['dashlink_layout_override'] : "01";

$view_order_style = isset($wcmamtx_layout['view_order_style']) ? $wcmamtx_layout['view_order_style'] : "01";

$view_order_template_override = isset($wcmamtx_layout['view_order_template_override']) ? $wcmamtx_layout['view_order_template_override'] : "01";

?>



<div class="wcmamtx-layout-settings">

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
				<option value="01" gtext="<?php echo esc_html__('Default','customize-my-account-for-woocommerce'); ?>"  <?php if (isset($dash_style) && ($dash_style == 01)) { echo 'selected'; } ?>></option> 
				<option value="02" gtext="<?php echo esc_html__('Classic','customize-my-account-for-woocommerce'); ?>"  <?php if (isset($dash_style) && ($dash_style == 02)) { echo 'selected'; } ?>></option> 
				<option value="03" gtext="<?php echo esc_html__('Card','customize-my-account-for-woocommerce'); ?>"  <?php if (isset($dash_style) && ($dash_style == 03)) { echo 'selected'; } ?>></option> 
				<option value="04" gtext="<?php echo esc_html__('Tile','customize-my-account-for-woocommerce'); ?>"  <?php if (isset($dash_style) && ($dash_style == 04)) { echo 'selected'; } ?>></option> 
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

		</div>

        


	</div>



    <!-- Sidebar -->
    <div class="wcmamtx-setting-card">

        <div class="wcmamtx-card-header">
            <h2><?php esc_html_e('Sidebar Position','customize-my-account-for-woocommerce'); ?></h2>
            <p><?php esc_html_e('Select the sidebar placement.','customize-my-account-for-woocommerce'); ?></p>
        </div>

        <div class="wcmamtx-card-body">

            <select class="wcmamtx_layout_sidebar_select" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[sidebar_style]" style="display: none;">			
					<option value="01" gtext="<?php echo esc_html__('Left Sidebar','customize-my-account-for-woocommerce'); ?>" <?php if (isset($sidebar_style) && ($sidebar_style == 01)) { echo 'selected'; } ?>></option> 
					<option value="02" gtext="<?php echo esc_html__('Right Sidebar','customize-my-account-for-woocommerce'); ?>" <?php if (isset($sidebar_style) && ($sidebar_style == 02)) { echo 'selected'; } ?>></option> 
			</select>

            <!-- sidebar previews -->

        </div>

    </div>



    <!-- Navigation -->
    <div class="wcmamtx-setting-card">

    	<div class="wcmamtx-card-header">
    		<h2><?php esc_html_e('Navigation Style','customize-my-account-for-woocommerce'); ?></h2>
    		<p><?php esc_html_e('Choose the account menu design.','customize-my-account-for-woocommerce'); ?></p>
    	</div>

    	<div class="wcmamtx-card-body">

    		<select class="wcmamtx_layout_navigation_select" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[nav_style]" style="display: none;">			
    			<option value="01" gtext="<?php echo esc_html__('Theme inherited (default)','customize-my-account-for-woocommerce'); ?>"  <?php if (isset($nav_style) && ($nav_style == 01)) { echo 'selected'; } ?>></option> 
    			<option value="02" gtext="<?php echo esc_html__('Clean','customize-my-account-for-woocommerce'); ?>"  <?php if (isset($nav_style) && ($nav_style == 02)) { echo 'selected'; } ?>></option> 
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



    <!-- Orders -->
    <div class="wcmamtx-setting-card">

        <div class="wcmamtx-card-header">
            <h2><?php esc_html_e('Orders Template','customize-my-account-for-woocommerce'); ?></h2>
            <p><?php esc_html_e('Customize WooCommerce orders page layout.','customize-my-account-for-woocommerce'); ?></p>
            <select class="wcmamtx_layout_select_override wcmamtx_layout_order_select_override" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[order_template_override]" style="">			
					<option value="01" <?php if (isset($order_template_override) && ($order_template_override == 01)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Sourced from this plugin','customize-my-account-for-woocommerce'); ?>
					</option> 
					<option value="02" <?php if (isset($order_template_override) && ($order_template_override == 02)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Prevent override from this plugin','customize-my-account-for-woocommerce'); ?>
					</option> 
			</select>
        </div>

        <div class="wcmamtx-card-body" style="<?php if (isset($order_template_override) && ($order_template_override == 01)) { echo 'display:block;'; } else { echo 'display:none;'; } ?>">

        	<select class="wcmamtx_layout_order_select" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[order_style]" style="display: none;">			
        		<option value="01" gtext="<?php echo esc_html__('Optimized','customize-my-account-for-woocommerce'); ?>" <?php if (isset($order_style) && ($order_style == 01)) { echo 'selected'; } ?>></option> 
        		<option value="02" gtext="<?php echo esc_html__('Default','customize-my-account-for-woocommerce'); ?>" <?php if (isset($order_style) && ($order_style == 02)) { echo 'selected'; } ?>></option> 
        	</select>




        	<a href="#" class="wcmamtx_template_override_a">
        		<?php echo esc_html__('Override from child theme or main theme ?','customize-my-account-for-woocommerce'); ?>
        	</a>

        	<p class="wcmamtx_template_override wcmamtx_layout_template_override_order alert alert-success">
        		<?php echo esc_html__('You can override this template easily from your child theme. Copy the file from','customize-my-account-for-woocommerce'); ?>&emsp;
        		<code><?php echo ''.wcmamtx_PLUGIN_URL.'templates/myaccount/order/<span class="wcmamtx_layout_order_override_no">'.$order_style.'</span>.php'; ?></code>

        		<?php echo esc_html__('and paste it into your child theme','customize-my-account-for-woocommerce'); ?>&emsp;

        		<code><?php echo ''.get_stylesheet_directory().'/wcmamtx_template/order/<span class="wcmamtx_layout_order_override_no">'.$order_style.'</span>.php'; ?></code>
        	</p>


        </div>

        

    </div>



    <!-- Downloads -->
    <div class="wcmamtx-setting-card">

        <div class="wcmamtx-card-header">
            <h2><?php esc_html_e('Downloads Template','customize-my-account-for-woocommerce'); ?></h2>
            <p><?php esc_html_e('Customize WooCommerce downloads page layout.','customize-my-account-for-woocommerce'); ?></p>
            <select class="wcmamtx_layout_select_override wcmamtx_layout_download_select_override" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[download_template_override]" style="">			
					<option value="01" <?php if (isset($download_template_override) && ($download_template_override == 01)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Sourced from this plugin','customize-my-account-for-woocommerce'); ?>
					</option> 
					<option value="02" <?php if (isset($download_template_override) && ($download_template_override == 02)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Prevent override from this plugin','customize-my-account-for-woocommerce'); ?>
					</option> 
			</select>
        </div>

        <div class="wcmamtx-card-body" style="<?php if (isset($download_template_override) && ($download_template_override == 01)) { echo 'display:block;'; } else { echo 'display:none;'; } ?>">

        	<select class="wcmamtx_layout_download_select" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[download_style]" style="display: none;">			
        		<option value="01" gtext="<?php echo esc_html__('Optimized','customize-my-account-for-woocommerce'); ?>" <?php if (isset($download_style) && ($download_style == 01)) { echo 'selected'; } ?>></option> 
        		<option value="02" gtext="<?php echo esc_html__('Default','customize-my-account-for-woocommerce'); ?>" <?php if (isset($download_style) && ($download_style == 02)) { echo 'selected'; } ?>></option> 
        	</select>

        	<a href="#" class="wcmamtx_template_override_a">
        		<?php echo esc_html__('Override from child theme or main theme ?','customize-my-account-for-woocommerce'); ?>
        	</a>

        	<p class="wcmamtx_template_override wcmamtx_layout_template_override_download alert alert-success">
        		<?php echo esc_html__('You can override this template easily from your child theme. Copy the file from','customize-my-account-for-woocommerce'); ?>&emsp;
        		<code><?php echo ''.wcmamtx_PLUGIN_URL.'templates/myaccount//order/downloads/<span class="wcmamtx_layout_download_override_no">'.$download_style.'</span>.php'; ?></code>

        		<?php echo esc_html__('and paste it into your child theme','customize-my-account-for-woocommerce'); ?>&emsp;

        		<code><?php echo ''.get_stylesheet_directory().'/wcmamtx_template/order/downloads/<span class="wcmamtx_layout_download_override_no">'.$download_style.'</span>.php'; ?></code>
        	</p>



        </div>

        

    </div>

        <!-- view_orders -->
    <div class="wcmamtx-setting-card">

        <div class="wcmamtx-card-header">
            <h2><?php esc_html_e('View Order Template','customize-my-account-for-woocommerce'); ?></h2>
            <p><?php esc_html_e('Customize WooCommerce view order page layout.','customize-my-account-for-woocommerce'); ?></p>
            <select class="wcmamtx_layout_select_override wcmamtx_layout_view_order_select_override" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[view_order_template_override]" style="">			
					<option value="01" <?php if (isset($view_order_template_override) && ($view_order_template_override == 01)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Sourced from this plugin','customize-my-account-for-woocommerce'); ?>
					</option> 
					<option value="02" <?php if (isset($view_order_template_override) && ($view_order_template_override == 02)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Prevent override from this plugin','customize-my-account-for-woocommerce'); ?>
					</option> 
			</select>
        </div>

        <div class="wcmamtx-card-body" style="<?php if (isset($view_order_template_override) && ($view_order_template_override == 01)) { echo 'display:block;'; } else { echo 'display:none;'; } ?>">

        	<select class="wcmamtx_layout_view_order_select" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[view_order_style]" style="display: none;">			
        		<option value="01" gtext="<?php echo esc_html__('Optimized','customize-my-account-for-woocommerce'); ?>" <?php if (isset($view_order_style) && ($view_order_style == 01)) { echo 'selected'; } ?>></option> 
        		<option value="02" gtext="<?php echo esc_html__('Default','customize-my-account-for-woocommerce'); ?>" <?php if (isset($view_order_style) && ($view_order_style == 02)) { echo 'selected'; } ?>></option> 
        	</select>

        	<a href="#" class="wcmamtx_template_override_a">
        		<?php echo esc_html__('Override from child theme or main theme ?','customize-my-account-for-woocommerce'); ?>
        	</a>

        	<p class="wcmamtx_template_override wcmamtx_layout_template_override_view_order alert alert-success">
        		<?php echo esc_html__('You can override this template easily from your child theme. Copy the file from','customize-my-account-for-woocommerce'); ?>&emsp;
        		<code><?php echo ''.wcmamtx_PLUGIN_URL.'templates/myaccount/view-order/<span class="wcmamtx_layout_view_order_override_no">'.$view_order_style.'</span>.php'; ?></code>

        		<?php echo esc_html__('and paste it into your child theme','customize-my-account-for-woocommerce'); ?>&emsp;

        		<code><?php echo ''.get_stylesheet_directory().'/wcmamtx_template/view-order/<span class="wcmamtx_layout_view_order_override_no">'.$view_order_style.'</span>.php'; ?></code>
        	</p>



        </div>

        

    </div>

</div>