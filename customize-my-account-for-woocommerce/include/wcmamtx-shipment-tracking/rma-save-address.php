<?php
/**
 * AJAX handler to save WooCommerce billing/shipping address.
 *
 * Bypasses WC_Form_Handler::save_address() which requires a page-context nonce.
 * Instead we verify with our own rma_nonce, then save directly via WC_Customer.
 */
add_action( 'wp_ajax_rma_save_address', 'rma_ajax_save_address' );
function rma_ajax_save_address() {
    check_ajax_referer( 'rma_nonce', 'nonce' );

    $address_type = isset( $_POST['address_type'] ) ? sanitize_key( $_POST['address_type'] ) : 'billing';
    if ( ! in_array( $address_type, [ 'billing', 'shipping' ], true ) ) {
        wp_send_json_error( [ 'message' => 'Invalid address type' ] );
    }

    $user_id = get_current_user_id();
    if ( ! $user_id ) {
        wp_send_json_error( [ 'message' => 'Not logged in' ] );
    }

    // Ensure WC session is active (needed for wc_add_notice / wc_notice_count)
    if ( ! WC()->session ) {
        WC()->session = new WC_Session_Handler();
        WC()->session->init();
    }
    wc_clear_notices();

    $customer = new WC_Customer( $user_id );

    // Get the address fields WC expects for this country
    $country_key = $address_type . '_country';
    $country     = isset( $_POST[ $country_key ] ) ? wc_clean( wp_unslash( $_POST[ $country_key ] ) ) : '';
    if ( ! $country ) {
        wp_send_json_error( [ 'message' => 'Missing country' ] );
    }

    $address_fields = WC()->countries->get_address_fields( $country, $address_type . '_' );

    foreach ( $address_fields as $key => $field ) {
        $type  = isset( $field['type'] ) ? $field['type'] : 'text';
        $value = 'checkbox' === $type
            ? (int) isset( $_POST[ $key ] )
            : ( isset( $_POST[ $key ] ) ? wc_clean( wp_unslash( $_POST[ $key ] ) ) : '' );

        $value = apply_filters( 'woocommerce_process_myaccount_field_' . $key, $value );

        // Required field validation
        if ( ! empty( $field['required'] ) && '' === $value ) {
            /* translators: %s: field label */
            wc_add_notice( sprintf( __( '%s is a required field.', 'woocommerce' ), $field['label'] ), 'error', [ 'id' => $key ] );
        }

        // Format/validate rules
        if ( '' !== $value && ! empty( $field['validate'] ) ) {
            foreach ( (array) $field['validate'] as $rule ) {
                switch ( $rule ) {
                    case 'postcode':
                        $value = wc_format_postcode( $value, $country );
                        if ( '' !== $value && ! WC_Validation::is_postcode( $value, $country ) ) {
                            wc_add_notice( __( 'Please enter a valid postcode / ZIP.', 'woocommerce' ), 'error' );
                        }
                        break;
                    case 'phone':
                        if ( ! WC_Validation::is_phone( $value ) ) {
                            wc_add_notice( sprintf( __( '%s is not a valid phone number.', 'woocommerce' ), '<strong>' . $field['label'] . '</strong>' ), 'error' );
                        }
                        break;
                    case 'email':
                        $value = strtolower( $value );
                        if ( ! is_email( $value ) ) {
                            wc_add_notice( sprintf( __( '%s is not a valid email address.', 'woocommerce' ), '<strong>' . $field['label'] . '</strong>' ), 'error' );
                        }
                        break;
                }
            }
        }

        // Set value on customer object
        try {
            if ( is_callable( [ $customer, "set_$key" ] ) ) {
                $customer->{"set_$key"}( $value );
            } else {
                $customer->update_meta_data( $key, $value );
            }
        } catch ( WC_Data_Exception $e ) {
            if ( 'customer_invalid_billing_email' !== $e->getErrorCode() ) {
                wc_add_notice( $e->getMessage(), 'error' );
            }
        }
    }

    do_action( 'woocommerce_after_save_address_validation', $user_id, $address_type, $address_fields, $customer );

    // Collect notices before checking errors
    $notices = [];
    foreach ( wc_get_notices() as $type => $type_notices ) {
        foreach ( $type_notices as $notice ) {
            $notices[] = [
                'type'    => $type,
                'message' => wp_strip_all_tags( is_array( $notice ) ? $notice['notice'] : $notice ),
            ];
        }
    }
    wc_clear_notices();

    if ( 0 === count( array_filter( $notices, fn($n) => $n['type'] === 'error' ) ) ) {
        $customer->save();
        do_action( 'woocommerce_customer_save_address', $user_id, $address_type, $address_fields, $customer );
    }

    wp_send_json_success( [ 'notices' => $notices ] );
}
