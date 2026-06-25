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