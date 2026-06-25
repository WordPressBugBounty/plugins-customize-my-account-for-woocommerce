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