<?php
if ( ! defined( "ABSPATH" ) ) exit;

// AJAX: auto-detect or create guest dashboard page
add_action( "wp_ajax_wcmamtx_guest_dashboard_autolink", "wcmamtx_guest_dashboard_autolink_cb" );
function wcmamtx_guest_dashboard_autolink_cb() {
    check_ajax_referer( "wcmamtx_guest_dashboard_nonce", "nonce" );
    if ( ! current_user_can( "manage_options" ) ) wp_send_json_error( "Unauthorized" );

    $existing = array();
    $all_pages = get_posts( array( "post_type" => "page", "post_status" => array( "publish", "draft" ), "posts_per_page" => -1 ) );
    foreach ( $all_pages as $p ) {
        if ( has_shortcode( $p->post_content, "wcmamtx_guest_dashboard" ) ) { $existing = array( $p ); break; }
    }

    if ( ! empty( $existing ) ) {
        $page   = $existing[0];
        $layout = wcmamtx_get_layout();
        $layout["guest_dashboard_page"] = $page->ID;
        update_option( "wcmamtx_layout", $layout );
        wp_send_json_success( array(
            "page_id"    => $page->ID,
            "page_title" => $page->post_title,
            "message"    => __( "Existing page found and linked.", "customize-my-account-for-woocommerce" ),
        ) );
    }

    $page_id = wp_insert_post( array(
        "post_title"   => __( "Guest Dashboard", "customize-my-account-for-woocommerce" ),
        "post_content" => "[wcmamtx_guest_dashboard]",
        "post_status"  => "publish",
        "post_type"    => "page",
    ) );
    if ( is_wp_error( $page_id ) ) wp_send_json_error( $page_id->get_error_message() );

    $layout = wcmamtx_get_layout();
    $layout["guest_dashboard_page"] = $page_id;
    update_option( "wcmamtx_layout", $layout );
    wp_send_json_success( array(
        "page_id"    => $page_id,
        "page_title" => __( "Guest Dashboard", "customize-my-account-for-woocommerce" ),
        "message"    => __( "Page created and linked automatically.", "customize-my-account-for-woocommerce" ),
    ) );
}

// Redirect logged-out visitors from /my-account/ to the guest dashboard page
add_action( "template_redirect", "wcmamtx_guest_dashboard_redirect" );
function wcmamtx_guest_dashboard_redirect() {
    if ( is_user_logged_in() ) return;

    // Check we are on the My Account page using the WC page ID directly
    // is_account_page() can be unreliable for logged-out users
    $myaccount_page_id = (int) get_option( "woocommerce_myaccount_page_id" );
    if ( ! $myaccount_page_id ) return;
    if ( ! is_page( $myaccount_page_id ) ) return;

    $layout  = wcmamtx_get_layout();
    $enabled = isset( $layout["guest_dashboard_enable"] ) ? $layout["guest_dashboard_enable"] : "02";
    $page_id = isset( $layout["guest_dashboard_page"] )   ? (int) $layout["guest_dashboard_page"] : 0;

    if ( $enabled !== "01" || ! $page_id ) return;
    if ( get_queried_object_id() === $page_id ) return;

    // Only redirect on bare /my-account/ - not on endpoint URLs like /my-account/orders/
    foreach ( WC()->query->get_query_vars() as $key => $var ) {
        if ( is_wc_endpoint_url( $key ) ) return;
    }

    // Allow bypass via query param (used by the Login link on guest dashboard)
    if ( isset( $_GET["skip_guest_dashboard"] ) && $_GET["skip_guest_dashboard"] === "yes" ) return;

    wp_safe_redirect( get_permalink( $page_id ) );
    exit;
}
