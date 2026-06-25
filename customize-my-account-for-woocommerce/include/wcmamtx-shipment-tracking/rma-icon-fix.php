<?php
/**
 * Replace rma_get_menu_icon SVG icons with wcmamtx_get_account_menu_li_icon_html output.
 * Hooks late into wp_enqueue_scripts to override the rmaData.menuItems already set,
 * and enqueues the plugin's Font Awesome stylesheet.
 */
add_action( 'wp_enqueue_scripts', function () {
    if ( ! function_exists( 'is_account_page' ) || ! is_account_page() ) return;
    if ( ! function_exists( 'wcmamtx_get_account_menu_items' ) ) return;
    if ( ! function_exists( 'wcmamtx_get_account_menu_li_icon_html' ) ) return;

    // Enqueue Font Awesome from the pro plugin
    wp_enqueue_style(
        'wcmamtx-font-awesome',
        plugins_url( 'assets/css/all.min.css', WP_CONTENT_DIR . '/plugins/customize-my-account-for-woocommerce-pro/customize-my-account-for-woocommerce-pro.php' )
    );

    // Rebuild menuItems with iconHtml instead of icon key
    $menu_items     = wcmamtx_get_account_menu_items();
    $menu_with_urls = [];

    foreach ( $menu_items as $endpoint => $value ) {
        $name        = ( isset( $value['endpoint_name'] ) && $value['endpoint_name'] !== '' ) ? $value['endpoint_name'] : $value;
        $endkey      = isset( $value['endpoint_key'] ) ? $value['endpoint_key'] : $endpoint;
        $icon_source = isset( $value['icon_source'] ) ? $value['icon_source'] : 'default';

        ob_start();
        wcmamtx_get_account_menu_li_icon_html( $icon_source, $value, $endkey );
        $icon_html = trim( ob_get_clean() );

        $menu_with_urls[] = [
            'key'      => $endpoint,
            'label'    => '' . $name . ' ',
            'url'      => wc_get_account_endpoint_url( $endkey ),
            'icon'     => '',           // keep for compat, unused now
            'iconHtml' => $icon_html,
        ];
    }

    // Override the previously localized rmaData.menuItems
    wp_add_inline_script(
        'rma-app',
        'if(window.rmaData){window.rmaData.menuItems=' . wp_json_encode( $menu_with_urls ) . ';}',
        'before'
    );
}, 99 );
