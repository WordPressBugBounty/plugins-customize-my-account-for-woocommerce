<?php 


$wcmamtx_layout = (array) get_option( 'wcmamtx_layout' );

$default_column = isset($wcmamtx_layout['columns']) ? $wcmamtx_layout['columns'] : "03";

$default_style = isset($wcmamtx_layout['style']) ? $wcmamtx_layout['style'] : "01";

?> 

<table class="widefat wcmamtx_options_table">

	<tr>
		<td><label><?php echo esc_html__('Dashboard link columns','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<select class="" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[columns]">
				
					<option value="01" <?php if (isset($default_column) && ($default_column == 01)) { echo 'selected'; } ?>>01</option> 
					<option value="02" <?php if (isset($default_column) && ($default_column == 02)) { echo 'selected'; } ?>>02</option> 
					<option value="03" <?php if (isset($default_column) && ($default_column == 03)) { echo 'selected'; } ?>>03</option> 
					<option value="04" <?php if (isset($default_column) && ($default_column == 04)) { echo 'selected'; } ?>>04</option>  
				
			</select>
		</td>
	</tr>

	<tr>
		<td><label><?php echo esc_html__('Navigation style','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<select class="" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[style]">
				
					<option value="01" <?php if (isset($default_style) && ($default_style == 01)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Default (Theme inherited)','customize-my-account-for-woocommerce'); ?>	
					</option> 
					<option value="02" <?php if (isset($default_style) && ($default_style == 02)) { echo 'selected'; } ?>>
						<?php echo esc_html__('Style 01','customize-my-account-for-woocommerce'); ?>
							
					</option> 
				
			</select>
		</td>
	</tr>
</table>