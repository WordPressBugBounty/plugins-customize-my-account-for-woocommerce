<?php

defined( 'ABSPATH' ) || exit;

define( 'RMA_VERSION', '2.0.3' );
define( 'RMA_DIR',     plugin_dir_path( __FILE__ ) );
define( 'RMA_URL',     plugin_dir_url( __FILE__ ) );

/* ---------------------------------------------------------------
   0. Avatar URL helper — mirrors wcmamtx_get_avatar_default() logic
      so the React nav respects the plugin's avatar settings:
      - local uploaded avatar via get_avatar_data filter (automatic)
      - "disable Gravatar" setting + custom default avatar
--------------------------------------------------------------- */
function rma_resolve_avatar_url( $user_id ) {
    $url = get_avatar_url( $user_id, [ 'size' => 80 ] );

    $avatar_settings = (array) get_option( 'wcmamtx_avatar_settings' );
    if (
        isset( $avatar_settings['disable_gravtar'] ) &&
        'yes' === $avatar_settings['disable_gravtar'] &&
        false !== strpos( $url, 'gravatar.com' )
    ) {
        $custom_def_id = isset( $avatar_settings['custom_default_avatar'] ) ? (int) $avatar_settings['custom_default_avatar'] : 0;
        $custom_url    = $custom_def_id > 0 ? (string) wp_get_attachment_url( $custom_def_id ) : '';
        $url           = $custom_url ?: wcmamtx_PLUGIN_URL . 'assets/images/default_avatar.jpg';
    }

    return $url;
}

/* ---------------------------------------------------------------
   1. Enqueue assets on My Account page
--------------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', function () {
    if ( ! function_exists( 'is_account_page' ) || ! is_account_page() ) return;

    wp_enqueue_script( 'react' );
    wp_enqueue_script( 'react-dom' );

    wp_enqueue_style( 'rma-styles', RMA_URL . 'assets/myaccount.css', [], RMA_VERSION );
    wp_enqueue_script( 'rma-app',   RMA_URL . 'assets/myaccount.js', [ 'react', 'react-dom' ], RMA_VERSION, true );

    $current_user   = wp_get_current_user();
    $menu_items     = wcmamtx_get_account_menu_items();
    $menu_with_urls = [];
    foreach ( $menu_items as $endpoint => $value ) {

        if (isset($value['endpoint_name']) && ($value['endpoint_name'] != '')) {
            $name = $value['endpoint_name'];
        } else {
            $name = $value;
        }

        $endkey = isset($value['endpoint_key']) ? $value['endpoint_key'] : $endpoint;

        $icon_source = isset($value['icon_source']) ? $value['icon_source'] : "default";

        $menu_with_urls[] = [
            'key'   => $endpoint,
            'label' => ''. $name .' ',
            'url'   => wc_get_account_endpoint_url( $endkey ),
            'icon'  => rma_get_menu_icon( $endkey ),
        ];
   

    }

    global $wcmamtx_upload_avatar_tab;
    $avatar_html = ( is_object( $wcmamtx_upload_avatar_tab ) && method_exists( $wcmamtx_upload_avatar_tab, 'wcmamtx_shortcode' ) )
        ? $wcmamtx_upload_avatar_tab->wcmamtx_shortcode( [] )
        : '';

    // Append custom avatar content — mirrors default logic in nav 02/05
    $_avatar_settings = (array) get_option( 'wcmamtx_avatar_settings' );
    if ( ! isset( $_avatar_settings['disable_avatar'] ) ) {
        $_avatar_settings['custom_avatar_content'] = 'yes';
    }
    $_fresh = (array) get_option( 'wcmamtx_avatar_settings' );
    if ( array_key_exists( 'custom_avatar_content', $_fresh ) ) {
        $_avatar_settings['custom_avatar_content'] = $_fresh['custom_avatar_content'];
    }
    if ( isset( $_avatar_settings['custom_avatar_content'] ) && 'yes' === $_avatar_settings['custom_avatar_content'] ) {
        $editor_content_avatar = isset( $_avatar_settings['content_avatar'] )
            ? $_avatar_settings['content_avatar']
            : '<p class="wcmamtx_default_text_below_avatar" style="text-align: center;">Hello <strong>{display_name}</strong> (not <strong>{display_name}</strong>? <a href="{user_logout_link}">Log out</a>)</p>';
        $editor_content_avatar = wcmamtx_parse_smart_tag_function( $editor_content_avatar );
        $avatar_html .= '<div id="wcmamtx-avatar-content-output">' . wp_kses_post( $editor_content_avatar ) . '</div>';
    }

    wp_localize_script( 'rma-app', 'rmaData', [
        'menuItems'    => $menu_with_urls,
        'currentPath'  => isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '',
        'userName'     => $current_user->display_name,
        'userEmail'    => $current_user->user_email,
        'avatarUrl'    => rma_resolve_avatar_url( $current_user->ID ),
        'avatarHtml'   => $avatar_html,
        'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
        'nonce'        => wp_create_nonce( 'rma_nonce' ),
        'myaccountUrl' => trailingslashit( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ),
        'logoutUrl'    => wc_get_account_endpoint_url( 'customer-logout' ),
        'addingtext'   => esc_html('Adding ...','customize-my-account-for-woocommerce')
    ] );
} );

/* ---------------------------------------------------------------
   2. AJAX handler — render a WC account section server-side
      ROOT CAUSE FIX: woocommerce_account_content() reads directly
      from $wp->query_vars (not $wp_query), so we must write there.
--------------------------------------------------------------- */
add_action( 'wp_ajax_rma_load_content', 'rma_ajax_load_content' );
function rma_ajax_load_content() {
    check_ajax_referer( 'rma_nonce', 'nonce' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( 'Unauthorized' );
    }

    $endpoint = isset( $_POST['endpoint'] ) ? sanitize_key( $_POST['endpoint'] ) : 'dashboard';
    $param    = isset( $_POST['param'] )    ? sanitize_text_field( wp_unslash( $_POST['param'] ) ) : '';

    global $wp, $wp_query;

    $wc_query_vars = WC()->query->get_query_vars();

    // Clear ALL WC endpoint keys from $wp->query_vars
    // (WC iterates this directly to find the active endpoint)
    foreach ( $wc_query_vars as $key => $var ) {
        unset( $wp->query_vars[ $var ] );
    }

    // Set only the requested endpoint (dashboard = no key needed)
    if ( 'dashboard' !== $endpoint ) {
        $var = $wc_query_vars[ $endpoint ] ?? $endpoint;
        $wp->query_vars[ $var ] = ( '' !== $param ) ? $param : 1;
    }

    // Make is_page() / is_account_page() return true
    $wp_query->is_page           = true;
    $wp_query->queried_object_id = (int) get_option( 'woocommerce_myaccount_page_id' );
    $wp_query->queried_object    = get_post( $wp_query->queried_object_id );

    // Ensure WC customer is initialised (may be null in AJAX context)
    if ( is_null( WC()->customer ) ) {
        WC()->customer = new WC_Customer( get_current_user_id(), true );
    }

    ob_start();
    do_action( 'woocommerce_account_content' );
    $html = ob_get_clean();

    // Restore — remove our injected vars so the rest of the AJAX request is clean
    foreach ( $wc_query_vars as $key => $var ) {
        unset( $wp->query_vars[ $var ] );
    }

    wp_send_json_success( [ 'html' => $html, 'endpoint' => $endpoint ] );
}

/* ---------------------------------------------------------------
   3. Template overrides
--------------------------------------------------------------- */
add_filter( 'woocommerce_locate_template', function ( $template, $template_name ) {
    if ( 'myaccount/navigation.php' === $template_name ) {
        return RMA_DIR . 'templates/navigation.php';
    }
    if ( 'myaccount/my-account.php' === $template_name ) {
        return RMA_DIR . 'templates/my-account.php';
    }
    return $template;
}, 10, 2 );

/* ---------------------------------------------------------------
   4. Icon map
--------------------------------------------------------------- */
function rma_get_menu_icon( $key ) {
    $map = [
        'dashboard'       => 'grid',
        'orders'          => 'package',
        'downloads'       => 'download',
        'edit-address'    => 'map-pin',
        'edit-account'    => 'user',
        'payment-methods' => 'credit-card',
        'customer-logout' => 'log-out',
    ];
    return $map[ $key ] ?? 'circle';
}