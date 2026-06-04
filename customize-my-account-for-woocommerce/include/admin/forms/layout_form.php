<?php 


$wcmamtx_layout = (array) get_option( 'wcmamtx_layout' );




$dash_style = isset($wcmamtx_layout['dash_style']) ? $wcmamtx_layout['dash_style'] : "01";
$sidebar_style = isset($wcmamtx_layout['sidebar_style']) ? $wcmamtx_layout['sidebar_style'] : "01";

?> 

<table class="widefat wcmamtx_options_table">

	

	<tr>
		<td><label><?php echo esc_html__('Dashboard links style','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<select class="wcmamtx_layout_design_select" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[dash_style]" style="display: none;">			
					<option value="01" <?php if (isset($dash_style) && ($dash_style == 01)) { echo 'selected'; } ?>></option> 
					<option value="02" <?php if (isset($dash_style) && ($dash_style == 02)) { echo 'selected'; } ?>></option> 
				    
			</select>

			
		</td>
	</tr>

	<tr>
		<td><label><?php echo esc_html__('Sidebar Position','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<select class="wcmamtx_layout_sidebar_select" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[sidebar_style]" style="display: none;">			
					<option value="01" <?php if (isset($sidebar_style) && ($sidebar_style == 01)) { echo 'selected'; } ?>></option> 
					<option value="02" <?php if (isset($sidebar_style) && ($sidebar_style == 02)) { echo 'selected'; } ?>></option> 
			</select>

			
		</td>
	</tr>
</table>