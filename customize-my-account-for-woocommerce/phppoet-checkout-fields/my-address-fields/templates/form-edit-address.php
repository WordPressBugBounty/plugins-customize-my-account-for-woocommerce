<?php
/**
 * Edit address form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

$page_title = ( 'billing' === $load_address ) ? esc_html__( 'Billing address', 'customize-my-account-for-woocommerce' ) : esc_html__( 'Shipping address', 'customize-my-account-for-woocommerce' );

do_action( 'woocommerce_before_edit_account_address_form' ); ?>

<?php if ( ! $load_address ) : ?>
	<?php wc_get_template( 'myaccount/my-address.php' ); ?>
<?php else : ?>

	<form method="post">

		<h2><?php echo apply_filters( 'woocommerce_my_account_edit_address_title', $page_title, $load_address ); ?></h2><?php // @codingStandardsIgnoreLine ?>

		<div class="woocommerce-address-fields">
			<?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>

			<div class="woocommerce-address-fields__field-wrapper">
				<?php

				$extra_settings    = (array) get_option('pcfme_extra_settings');
                $extra_settings    = array_filter($extra_settings);

				$additional_fields_location = isset($extra_settings['additional_fields_location']) ? $extra_settings['additional_fields_location'] : "billing";



				$additional_fields = (array) get_option( 'pcfme_additional_settings' );

				

				switch($load_address) {
					case "billing":

					$plugin_fields1 = (array) get_option( 'pcfme_billing_settings' );

					if (isset($plugin_fields1) && (sizeof($plugin_fields1) > 1)) { 
						$address = $plugin_fields1;
					} else {
						$address = $address;

					}

					foreach ( $address as $key => $field ) {
						if ($field['hide'] != 1) {
							woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) );
						}

					}

                    if (($additional_fields_location == "billing") && (isset($additional_fields))) {

                    	foreach ($additional_fields as $akey=>$avalue) {
                    		woocommerce_form_field( $akey, $avalue, wc_get_post_data_by_key( $akey, $avalue['value'] ) );
                    	}
                    }

					break;

                    case "shipping":

                    $plugin_fields2 = (array) get_option( 'pcfme_shipping_settings' );

					if (isset($plugin_fields2) && (sizeof($plugin_fields2) > 1)) { 
						$address = $plugin_fields2;
					} else {
						$address = $address;

						
					}

					foreach ( $address as $key => $field ) {
						if ($field['hide'] != 1) {
							woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) );
						}
						
					}

					if (($additional_fields_location == "shipping") && (isset($additional_fields))) {

                    	foreach ($additional_fields as $akey=>$avalue) {
                    		woocommerce_form_field( $akey, $avalue, wc_get_post_data_by_key( $akey, $avalue['value'] ) );
                    	}
                    }
                    break;


				}


                

				
				?>
			</div>

			<?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>

			<p>
				<button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="save_address" value="<?php esc_attr_e( 'Save address', 'customize-my-account-for-woocommerce' ); ?>"><?php esc_html_e( 'Save address', 'customize-my-account-for-woocommerce' ); ?></button>
				<?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
				<input type="hidden" name="action" value="edit_address" />
			</p>
		</div>

	</form>

<?php endif; ?>

<?php do_action( 'woocommerce_after_edit_account_address_form' ); ?>
