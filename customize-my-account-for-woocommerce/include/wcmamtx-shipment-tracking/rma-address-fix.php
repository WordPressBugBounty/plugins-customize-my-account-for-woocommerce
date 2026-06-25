<?php
/**
 * Fix: edit-address with no param should show the WooCommerce
 * address chooser (billing + shipping cards), not a form.
 *
 * We remove the original rma_ajax_load_content handler and replace
 * it with one that intercepts this specific case.
 */
add_action( 'init', function () {
    remove_action( 'wp_ajax_rma_load_content', 'rma_ajax_load_content' );
    add_action( 'wp_ajax_rma_load_content', 'rma_ajax_load_content_fixed', 5 );
}, 20 );

function rma_ajax_load_content_fixed() {
    check_ajax_referer( 'rma_nonce', 'nonce' );

    $endpoint = isset( $_POST['endpoint'] ) ? sanitize_key( $_POST['endpoint'] ) : 'dashboard';
    $param    = isset( $_POST['param'] )    ? sanitize_text_field( wp_unslash( $_POST['param'] ) ) : '';

    // Special case: edit-address with no sub-type -> show the address chooser page
    if ( 'edit-address' === $endpoint && '' === $param ) {
        global $wp_query;
        $wp_query->is_page           = true;
        $wp_query->queried_object_id = (int) get_option( 'woocommerce_myaccount_page_id' );
        $wp_query->queried_object    = get_post( $wp_query->queried_object_id );

        if ( is_null( WC()->customer ) ) {
            WC()->customer = new WC_Customer( get_current_user_id(), true );
        }

        ob_start();
        wc_get_template( 'myaccount/my-address.php', [
            'customer' => WC()->customer,
        ] );
        $html = ob_get_clean();

        wp_send_json_success( [ 'html' => $html, 'endpoint' => $endpoint ] );
        return;
    }

    // All other endpoints: delegate to original handler
    rma_ajax_load_content();
}
