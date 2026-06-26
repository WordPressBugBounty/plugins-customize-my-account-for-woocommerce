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