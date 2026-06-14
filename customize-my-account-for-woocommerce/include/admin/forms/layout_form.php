<?php 


$wcmamtx_layout = (array) get_option( 'wcmamtx_layout' );

if (array_key_exists(0, $wcmamtx_layout)) {
    $wcmamtx_layout['formlogin_layout_override'] = "01";

    $wcmamtx_layout['navigationwidget_layout_override'] = "01";
    
    $wcmamtx_layout['nav_header_widget'] = "yes";
    
}




$nav_style = isset($wcmamtx_layout['nav_style']) ? $wcmamtx_layout['nav_style'] : wcmamtx_get_clean_design_theme_array();

$dash_style = isset($wcmamtx_layout['dash_style']) ? $wcmamtx_layout['dash_style'] : "01";
$sidebar_style = isset($wcmamtx_layout['sidebar_style']) ? $wcmamtx_layout['sidebar_style'] : "01";

$order_style = isset($wcmamtx_layout['order_style']) ? $wcmamtx_layout['order_style'] : "01";
$order_template_override = isset($wcmamtx_layout['order_template_override']) ? $wcmamtx_layout['order_template_override'] : "01";

$download_style = isset($wcmamtx_layout['download_style']) ? $wcmamtx_layout['download_style'] : "01";

$download_template_override = isset($wcmamtx_layout['download_template_override']) ? $wcmamtx_layout['download_template_override'] : "01";

$dashlink_layout_override = isset($wcmamtx_layout['dashlink_layout_override']) ? $wcmamtx_layout['dashlink_layout_override'] : "01";

$view_order_style = isset($wcmamtx_layout['view_order_style']) ? $wcmamtx_layout['view_order_style'] : "01";

$view_order_template_override = isset($wcmamtx_layout['view_order_template_override']) ? $wcmamtx_layout['view_order_template_override'] : "01";


$thankyou_style = isset($wcmamtx_layout['thankyou_style']) ? $wcmamtx_layout['thankyou_style'] : "01";

$thankyou_template_override = isset($wcmamtx_layout['thankyou_template_override']) ? $wcmamtx_layout['thankyou_template_override'] : "01";

$dashlink_box_override = isset($wcmamtx_layout['dashlink_box_override']) ? $wcmamtx_layout['dashlink_box_override'] : "01";

$linkbox_style = isset($wcmamtx_layout['linkbox_style']) ? $wcmamtx_layout['linkbox_style'] : "02";

$profilebox_override = isset($wcmamtx_layout['profilebox_override']) ? $wcmamtx_layout['profilebox_override'] : "01";

$profilebox_style = isset($wcmamtx_layout['profilebox_style']) ? $wcmamtx_layout['profilebox_style'] : "02";

$orderpay_style = isset($wcmamtx_layout['orderpay_style']) ? $wcmamtx_layout['orderpay_style'] : "01";

$orderpay_template_override = isset($wcmamtx_layout['orderpay_template_override']) ? $wcmamtx_layout['orderpay_template_override'] : "01";


$formlogin_layout_override = isset($wcmamtx_layout['formlogin_layout_override']) ? $wcmamtx_layout['formlogin_layout_override'] : "02";

$google_client_id = isset( $wcmamtx_layout['google_client_id'] ) ? $wcmamtx_layout['google_client_id'] : '';
$google_client_secret = isset( $wcmamtx_layout['google_client_secret'] ) ? $wcmamtx_layout['google_client_secret'] : '';

$frontend_url = get_permalink(get_option('woocommerce_myaccount_page_id'));
?>


<a type="button" target="_blank" href="<?php echo $frontend_url; ?>" id="wcmamtx_frontend_link" class="btn btn-sm btn-primary wcmamtx_frontend_link wcmamtx_frontend_link_top_sticky" >
	<span class="dashicons dashicons-welcome-view-site"></span>
	<?php echo esc_html__( 'Frontend' ,'customize-my-account-for-woocommerce'); ?>
</a>
<div class="wcmamtx-layout-settings">

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
        		
        		<option value="01" vpreview="<?php echo wcmamtx_PLUGIN_URL; ?>assets/images/profilebox1.png" gtext="<?php echo esc_html__('Link Box','customize-my-account-for-woocommerce'); ?>" <?php if (isset($profilebox_style) && ($profilebox_style == 01)) { echo 'selected'; } ?> disabled></option> 
        	</select>

		</div>

        


	</div>

	

		<!-- Social Login Style -->
	<div class="wcmamtx-setting-card">

		<div class="wcmamtx-card-header">
			<div>
				<h2><?php esc_html_e('Login & Register','customize-my-account-for-woocommerce'); ?></h2>
				<p><?php esc_html_e('Optimize your WooCommerce My Account login page along with google social login.','customize-my-account-for-woocommerce'); ?></p>

				<select class="wcmamtx_layout_select_override wcmamtx_layout_order_select_override" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[formlogin_layout_override]" style="">			
					<option value="01" <?php if (isset($formlogin_layout_override) && ($formlogin_layout_override == 01)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Enable','customize-my-account-for-woocommerce'); ?>
					</option> 
					<option value="02" <?php if (isset($formlogin_layout_override) && ($formlogin_layout_override == 02)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Disable','customize-my-account-for-woocommerce'); ?>
					</option> 
			</select>
			</div>
		</div>

		<div class="wcmamtx-card-body" style="<?php if (isset($formlogin_layout_override) && ($formlogin_layout_override == 01)) { echo 'display:block;'; } else { echo 'display:none;'; } ?>">



			<div class="form-group">
				<label for="wcmamtx_google_client_id">
					<strong><?php esc_html_e( 'Google Client ID', 'customize-my-account-for-woocommerce' ); ?></strong>
				</label>

				<input
				type="text"
				id="wcmamtx_google_client_id"
				class="regular-text"
				name="<?php echo esc_attr( $this->wcmamtx_layout_page ); ?>[google_client_id]"
				value="<?php echo esc_attr( $google_client_id ); ?>"
				placeholder="xxxxxxxxxxxxxxxx.apps.googleusercontent.com"
				/>

				
			</div>

			<div class="form-group" style="margin-top:15px;">
				<label for="wcmamtx_google_client_secret">
					<strong><?php esc_html_e( 'Google Client Secret', 'customize-my-account-for-woocommerce' ); ?></strong>
				</label>

				<input
				type="text"
				id="wcmamtx_google_client_secret"
				class="regular-text"
				name="<?php echo esc_attr( $this->wcmamtx_layout_page ); ?>[google_client_secret]"
				value="<?php echo esc_attr( $google_client_secret ); ?>"
				autocomplete="off"
				/>

				
			</div>


			<div class="form-group" style="margin-top:15px;">
				<label for="wcmamtx_google_client_secret">
					<strong><?php esc_html_e( 'Redirect url', 'customize-my-account-for-woocommerce' ); ?></strong>
				</label>

				<code><?php echo home_url('/?wcmamtx-social=google'); ?></code>

				
			</div>

			

			<a href="#" class="wcmamtx_template_override_a">
				<?php echo esc_html__('Override from child theme or main theme ?','customize-my-account-for-woocommerce'); ?>
			</a>

			<p class="wcmamtx_template_override wcmamtx_layout_template_override alert alert-success">
				<?php echo esc_html__('You can override this template easily from your child theme. Copy the file from','customize-my-account-for-woocommerce'); ?>&emsp;
				<code><?php echo ''.wcmamtx_PLUGIN_URL.'templates/myaccount/form-login.php'; ?></code>

				<?php echo esc_html__('and paste it into your child theme','customize-my-account-for-woocommerce'); ?>&emsp;

				<code><?php echo ''.get_stylesheet_directory().'/wcmamtx_template/form-login.php'; ?></code>
			</p>

		</div>




	</div>

	 <?php
    $spending_layout_override = isset($wcmamtx_layout['spending_layout_override']) ? $wcmamtx_layout['spending_layout_override'] : "01";
    ?>


    <!-- My Account Navigation Menu widget Style -->
	<div class="wcmamtx-setting-card">

		<div class="wcmamtx-card-header">
			<div>
				<h2><?php esc_html_e('Customer spending chart','customize-my-account-for-woocommerce'); ?></h2>
				<p><?php esc_html_e('Add customer spending widgets along with chart after dashboard.','customize-my-account-for-woocommerce'); ?></p>

				<select class="wcmamtx_layout_select_override wcmamtx_layout_order_select_override" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[spending_layout_override]" style="">			
					<option value="01" <?php if (isset($spending_layout_override) && ($spending_layout_override == 01)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Enable','customize-my-account-for-woocommerce'); ?>
					</option> 
					<option value="02" <?php if (isset($spending_layout_override) && ($spending_layout_override == 02)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Disable','customize-my-account-for-woocommerce'); ?>
					</option> 
			</select>
			</div>
		</div>

		<div class="wcmamtx-card-body" style="<?php if (isset($spending_layout_override) && ($spending_layout_override == 01)) { echo 'display:block;'; } else { echo 'display:none;'; } ?>">
			<?php include('layouts/spending.php'); ?>

		</div>

	</div>

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

		</div>

        


	</div>

    <!-- Dashboard Link Boxes -->

	<div class="wcmamtx-setting-card">

		<div class="wcmamtx-card-header">
			<div>
				<h2><?php esc_html_e('Dashboard Link Boxes','customize-my-account-for-woocommerce'); ?></h2>
				<p><?php esc_html_e('Amazon like linkboxes on dashboard.','customize-my-account-for-woocommerce'); ?></p>

				<select class="wcmamtx_layout_select_override wcmamtx_layout_order_select_override" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[dashlink_box_override]" style="">			
					<option value="01" <?php if (isset($dashlink_box_override) && ($dashlink_box_override == 01)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Enable','customize-my-account-for-woocommerce'); ?>
					</option> 
					<option value="02" <?php if (isset($dashlink_box_override) && ($dashlink_box_override == 02)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Disable','customize-my-account-for-woocommerce'); ?>
					</option> 
			</select>
			</div>
		</div>

		<div class="wcmamtx-card-body" style="<?php if (isset($dashlink_box_override) && ($dashlink_box_override == 01)) { echo 'display:block;'; } else { echo 'display:none;'; } ?>">

			<select class="wcmamtx_layout_linkbox_select" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[linkbox_style]" style="display: none;">			
        		
        		<option value="01" vpreview="<?php echo wcmamtx_PLUGIN_URL; ?>assets/images/linkbox1.png" gtext="<?php echo esc_html__('Link Box','customize-my-account-for-woocommerce'); ?>" <?php if (isset($linkbox_style) && ($linkbox_style == 01)) { echo 'selected'; } ?> disabled></option> 
        	</select>

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
					<option value="02" gtext="<?php echo esc_html__('Right Sidebar','customize-my-account-for-woocommerce'); ?>" <?php if (isset($sidebar_style) && ($sidebar_style == 02)) { echo 'selected'; } ?> disabled></option> 
					<option value="03" gtext="<?php echo esc_html__('No navigation','customize-my-account-for-woocommerce'); ?>" <?php if (isset($sidebar_style) && ($sidebar_style == 02)) { echo 'selected'; } ?> disabled></option> 
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

    <?php
    $navigationwidget_layout_override = isset($wcmamtx_layout['navigationwidget_layout_override']) ? $wcmamtx_layout['navigationwidget_layout_override'] : "01";
    ?>


    <!-- My Account Navigation Menu widget Style -->
	<div class="wcmamtx-setting-card">

		<div class="wcmamtx-card-header">
			<div>
				<h2><?php esc_html_e('My Account Navigation Menu widget','customize-my-account-for-woocommerce'); ?></h2>
				<p><?php esc_html_e('Add My Account Navigation menu widget with all chosen endpoints.','customize-my-account-for-woocommerce'); ?></p>

				<select class="wcmamtx_layout_select_override wcmamtx_layout_order_select_override" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[navigationwidget_layout_override]" style="">			
					<option value="01" <?php if (isset($navigationwidget_layout_override) && ($navigationwidget_layout_override == 01)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Enable','customize-my-account-for-woocommerce'); ?>
					</option> 
					<option value="02" <?php if (isset($navigationwidget_layout_override) && ($navigationwidget_layout_override == 02)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Disable','customize-my-account-for-woocommerce'); ?>
					</option> 
			</select>
			</div>
		</div>

		<div class="wcmamtx-card-body" style="<?php if (isset($navigationwidget_layout_override) && ($navigationwidget_layout_override == 01)) { echo 'display:block;'; } else { echo 'display:none;'; } ?>">
			<?php include('layouts/navigationwidget.php'); ?>

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


        <!-- Thank you template -->
    <div class="wcmamtx-setting-card">

        <div class="wcmamtx-card-header">
            <h2><?php esc_html_e('Thankyou Template','customize-my-account-for-woocommerce'); ?></h2>
            <p><?php esc_html_e('Customize WooCommerce order received page layout.','customize-my-account-for-woocommerce'); ?></p>
            <select class="wcmamtx_layout_select_override wcmamtx_layout_thankyou_select_override" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[thankyou_template_override]" style="">			
					<option value="01" <?php if (isset($thankyou_template_override) && ($thankyou_template_override == 01)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Sourced from this plugin','customize-my-account-for-woocommerce'); ?>
					</option> 
					<option value="02" <?php if (isset($thankyou_template_override) && ($thankyou_template_override == 02)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Prevent override from this plugin','customize-my-account-for-woocommerce'); ?>
					</option> 
			</select>
        </div>

        <div class="wcmamtx-card-body" style="<?php if (isset($thankyou_template_override) && ($thankyou_template_override == 01)) { echo 'display:block;'; } else { echo 'display:none;'; } ?>">

        	<select class="wcmamtx_layout_thankyou_select" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[thankyou_style]" style="display: none;">			
        		<option value="01" vpreview="" gtext="<?php echo esc_html__('Default','customize-my-account-for-woocommerce'); ?>" <?php if (isset($thankyou_style) && ($thankyou_style == 01)) { echo 'selected'; } ?>></option> 
        		<option value="02" vpreview="<?php echo wcmamtx_PLUGIN_URL; ?>assets/images/thankyou2.png" gtext="<?php echo esc_html__('Optimized','customize-my-account-for-woocommerce'); ?>" <?php if (isset($thankyou_style) && ($thankyou_style == 02)) { echo 'selected'; } ?> disabled></option> 
        	</select>

        	



        </div>

        

    </div>


    <!-- Order Pay template -->
    <div class="wcmamtx-setting-card">

        <div class="wcmamtx-card-header">
            <h2><?php esc_html_e('Order Pay Template','customize-my-account-for-woocommerce'); ?></h2>
            <p><?php esc_html_e('Custom order pay template for pending payment orders.','customize-my-account-for-woocommerce'); ?></p>
            <select class="wcmamtx_layout_select_override wcmamtx_layout_orderpay_select_override" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[orderpay_template_override]" style="">			
					<option value="01" <?php if (isset($orderpay_template_override) && ($orderpay_template_override == 01)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Sourced from this plugin','customize-my-account-for-woocommerce'); ?>
					</option> 
					<option value="02" <?php if (isset($orderpay_template_override) && ($orderpay_template_override == 02)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Prevent override from this plugin','customize-my-account-for-woocommerce'); ?>
					</option> 
			</select>
        </div>

        <div class="wcmamtx-card-body" style="<?php if (isset($orderpay_template_override) && ($orderpay_template_override == 01)) { echo 'display:block;'; } else { echo 'display:none;'; } ?>">

        	<select class="wcmamtx_layout_orderpay_select" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[orderpay_style]" style="display: none;">			
        		<option value="01" vpreview=""  gtext="<?php echo esc_html__('Default','customize-my-account-for-woocommerce'); ?>" <?php if (isset($orderpay_style) && ($orderpay_style == 01)) { echo 'selected'; } ?>></option> 
        		<option value="02" vpreview="<?php echo wcmamtx_PLUGIN_URL; ?>assets/images/orderpay2.png" gtext="<?php echo esc_html__('Optimized','customize-my-account-for-woocommerce'); ?>" <?php if (isset($orderpay_style) && ($orderpay_style == 02)) { echo 'selected'; } ?> disabled></option> 
        	</select>

        	



        </div>

        

    </div>


</div>