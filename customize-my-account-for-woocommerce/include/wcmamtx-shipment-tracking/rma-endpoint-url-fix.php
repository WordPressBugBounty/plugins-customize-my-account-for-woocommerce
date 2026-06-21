<?php
/**
 * Fix: wc_get_endpoint_url() uses get_permalink() as its base when called
 * with no $permalink argument (e.g. from my-address.php template).
 * In the AJAX context this returns the wrong URL (e.g. /edit-address/billing/
 * instead of /pro/index.php/my-account/edit-address/billing/).
 *
 * We hook into woocommerce_get_endpoint_url and correct any URL whose
 * $permalink base resolved to something outside the my-account page.
 */
add_filter( 'woocommerce_get_endpoint_url', function( $url, $ep, $value, $permalink ) {
    // Only act during AJAX requests
    if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
        return $url;
    }

    $myaccount_id  = (int) get_option( 'woocommerce_myaccount_page_id' );
    $myaccount_url = trailingslashit( get_permalink( $myaccount_id ) );

    // If the generated URL does NOT start with the correct my-account URL, rebuild it
    if ( strpos( $url, $myaccount_url ) !== 0 ) {
        if ( get_option( 'permalink_structure' ) ) {
            if ( $value ) {
                $url = trailingslashit( $myaccount_url . $ep ) . trailingslashit( $value );
            } else {
                $url = trailingslashit( $myaccount_url . $ep );
            }
        } else {
            $url = add_query_arg( $ep, $value, $myaccount_url );
        }
    }

    return $url;
}, 99, 4 );
