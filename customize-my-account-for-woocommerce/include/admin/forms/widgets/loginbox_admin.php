		<!-- Social Login Style -->
	<div class="wcmamtx-setting-card wcmamtx_login_card">

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