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