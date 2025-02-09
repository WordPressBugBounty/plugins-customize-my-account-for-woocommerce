<?php 
$wcmamtx_pro_settings  = (array) get_option('wcmamtx_pro_settings');  

?>
<table class="widefat wcmamtx_options_table">
    
    <tr>
		<td><label><?php echo esc_html__('Enable Mobile Friendly Sticky Sidebar Menu','customize-my-account-for-woocommerce-pro'); ?></label> <br />
		</td>
		<td>
			<input type="checkbox" data-toggle="toggle"  data-on="<?php echo esc_html__('Yes','customize-my-account-for-woocommerce-pro'); ?>" data-off="<?php echo esc_html__('No','customize-my-account-for-woocommerce-pro'); ?>" class="" name="<?php  echo esc_html__($this->wcmamtx_pro_settings); ?>[sticky_sidebar]" value="yes" <?php if (isset($wcmamtx_pro_settings['sticky_sidebar']) && ($wcmamtx_pro_settings['sticky_sidebar'] == "yes")) { echo 'checked'; } ?>>
			
			<p>
				<a href="https://www.sysbasics.com/knowledge-base/sticky-sidebar-menu-compatible-themes-list/" target="_blank">
					<?php echo esc_html__('List of supported themes','customize-my-account-for-woocommerce-pro'); ?>
				</a>
			</p>
		</td>
	</tr>

	<tr>
		<td><label><?php echo esc_html__('Change Default My Account Page','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<?php wcmamtx_show_disabled_toggle_image(); ?>
		</td>
	</tr>

	<tr>
		<td>
			<label>
				<?php echo esc_html__('Enable Dashboard Links','customize-my-account-for-woocommerce'); ?>
			    &emsp;<a target="_blank" href="https://i0.wp.com/www.sysbasics.com/wp-content/uploads/2023/11/1-2.png?w=1233&ssl=1"><?php echo esc_html__('Preview','customize-my-account-for-woocommerce'); ?></a>
			</label>
		</td>
		<td>
			<?php wcmamtx_show_disabled_toggle_image(); ?>
		</td>
	</tr>

	<tr>
		<td><label><?php echo esc_html__('Horizontal Navigation Menu','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<?php wcmamtx_show_disabled_toggle_image(); ?>
		</td>
	</tr>

	<tr>
		<td><label><?php echo esc_html__('Enable Ajax Navigation Between Endpoints','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<?php wcmamtx_show_disabled_toggle_image(); ?>
		</td>
	</tr>

	<tr>
		<td><label><?php echo esc_html__('My Account Navigation Menu Widget','customize-my-account-for-woocommerce'); ?></label> <br />
		</td>
		<td>
			<?php wcmamtx_show_disabled_toggle_image(); ?>
		</td>
	</tr>
</table>