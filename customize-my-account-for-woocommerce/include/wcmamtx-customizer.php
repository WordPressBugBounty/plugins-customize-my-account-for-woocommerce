<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! defined( 'WCMAMTX_CUSTOMIZER_SLUG' ) ) if ( ! defined( 'WCMAMTX_CUSTOMIZER_SLUG' ) ) define( 'WCMAMTX_CUSTOMIZER_SLUG', 'wcmamtx_frontend_customizer' );
if ( ! defined( 'WCMAMTX_CUSTOMIZER_OPT' ) )  if ( ! defined( 'WCMAMTX_CUSTOMIZER_OPT' ) )  define( 'WCMAMTX_CUSTOMIZER_OPT',  'wcmamtx_layout' );
if ( ! defined( 'WCMAMTX_QH_API_KEY' ) )      define( 'WCMAMTX_QH_API_KEY', 'wcmamtx_qh_key_7f3a9b' );

add_action( 'admin_menu', function() {
    add_menu_page(
        __( 'My Account Customizer', 'customize-my-account-for-woocommerce' ),
        __( 'Customizer', 'customize-my-account-for-woocommerce' ),
        'manage_options', WCMAMTX_CUSTOMIZER_SLUG,
        'wcmamtx_customizer_render_page', 'dashicons-art', 56
    );
}, 999 );
add_action( 'admin_head', function() { remove_menu_page( WCMAMTX_CUSTOMIZER_SLUG ); } );

add_action( 'admin_enqueue_scripts', function( $hook ) {
    if ( $hook !== 'toplevel_page_' . WCMAMTX_CUSTOMIZER_SLUG ) return;
    foreach ( ['colors','wp-admin','common','forms','admin-menu','dashboard','list-tables','edit','media','themes','nav-menus','widgets','wp-auth-check'] as $s ) {
        wp_dequeue_style( $s );
    }
}, 99 );

add_action( 'wcmamtx_add_author_links', function() {
    $url = admin_url( 'admin.php?page=' . WCMAMTX_CUSTOMIZER_SLUG );
        $tab  = isset( $_GET['tab'] )  ? sanitize_text_field( wp_unslash( $_GET['tab'] ) )  : '';

    if ($tab == "wcmamtx_layout") {
        echo '<a href="' . esc_url( $url ) . '"  class="btn wcmamtx_live_customizer btn-warning" style="position: fixed;
    top: 220px;
    right: -45px;
    transform: translateX(-50%);
    z-index: 9999;
    padding: 8px 24px;
    font-size: 13px;
    font-weight: 700;
    border-radius: 6px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.22);
    transition: box-shadow 0.2s ease, transform 0.15s ease;
    cursor: pointer;
    border: none;">';
        echo '<span class="dashicons dashicons-art" style="font-size:16px;line-height:1.5;"></span>';
        echo esc_html__( 'Live Customizer', 'customize-my-account-for-woocommerce' );
        echo '</a>';
    }
}, 20 );

add_action( 'wp_ajax_wcmamtx_customizer_save', function() {
    check_ajax_referer( 'wcmamtx_cz_layout', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
    $key   = isset( $_POST['key'] )   ? sanitize_key( $_POST['key'] ) : '';
    $value = isset( $_POST['value'] ) ? sanitize_text_field( wp_unslash( $_POST['value'] ) ) : '';
    if ( ! $key ) wp_send_json_error( 'Missing key' );
    $layout_allowed = [
        'nav_style'                       => ['01','02','03','04','05','06','07','08'],
        'sidebar_style'                   => ['01'],
        'dash_style'                      => ['01','02','03','04'],
        'order_template_override'         => ['01','02'],
        'order_style'                     => ['01','02'],
        'download_template_override'      => ['01','02'],
        'download_style'                  => ['01','02'],
        'view_order_template_override'    => ['01','02'],
        'view_order_style'                => ['01','02'],
        'thankyou_template_override'      => ['01','02'],
        'thankyou_style'                  => ['01','02'],
        'orderpay_template_override'      => ['01','02'],
        'orderpay_style'                  => ['01','02'],
        'nav_header_widget'               => ['yes','no'],
        'show_only_logged_in'             => ['yes','no'],
        'navwidget_disable_avatar'        => ['yes','no'],
        'navwidget_disable_username'      => ['yes','no'],
        'navigationwidget_layout_override'=> ['01','02'],
        'spending_layout_override'        => ['01','02'],
        'spendingchart_override'          => ['01','02'],
        'dashlink_layout_override'        => ['01','02'],
        'profilebox_override'             => ['01','02'],
        'dashlink_box_override'           => ['01','02'],
        'spendingbox_dashboard_priority'  => 'priority',
        'spendingchart_dashboard_priority'=> 'priority',
        'dashlinks_dashboard_priority'    => 'priority',
        'profilebox_dashboard_priority'   => 'priority',
        'linkbox_dashboard_priority'      => 'priority',
        'nav_header_widget_text'          => 'text',
        'nav_header_widget_text_logout'   => 'text',
        'widget_menu_location'            => 'menuint',
        'shipment_tracking_override'      => ['01','02'],
        'shortcode1_override'             => ['01','02'],
        'shortcode2_override'             => ['01','02'],
        'shortcode1_value'                => 'text',
        'shortcode2_value'                => 'text',
        'shortcode1_dashboard_priority'   => 'priority',
        'shortcode2_dashboard_priority'   => 'priority',
        'guest_dashboard_enable'          => ['01','02'],
        'guest_dashboard_page'            => 'posint',
        'guest_modal_popup'               => ['yes','no'],
        'popup_login_enable'              => ['01','02'],
        'navwidget_loggedout_popup'       => ['yes','no'],
        'formlogin_layout_override'       => ['01','02'],
        'google_social_login'             => ['yes','no'],
        'google_client_id'                => 'text',
        'google_client_secret'            => 'text',
        'facebook_social_login'           => ['yes','no'],
        'facebook_app_id'                 => 'text',
        'facebook_app_secret'             => 'text',
        'login_page_headline'             => 'text',
        'login_page_subtitle'             => 'text',
        'login_page_badge_text'           => 'text',
        'login_page_gradient_start'       => 'color',
        'login_page_gradient_end'         => 'color',
        'login_page_bg_image'             => 'url',
        'login_page_bg_size'             => 'text',
        'login_page_text_color'           => 'color',
        'login_page_badge_bg'             => 'color',
    ];
    if ( ! array_key_exists( $key, $layout_allowed ) ) wp_send_json_error( 'Invalid key' );
    $layout_rule = $layout_allowed[$key];
    if ( is_array( $layout_rule ) ) {
        if ( ! in_array( $value, $layout_rule, true ) ) wp_send_json_error( 'Invalid value' );
    } elseif ( $layout_rule === 'priority' ) {
        $value = (string) absint( $value );
        if ( empty( $value ) ) wp_send_json_error( 'Invalid value' );
    } elseif ( $layout_rule === 'menuint' ) {
        $value = (string) absint( $value );
    } elseif ( $layout_rule === 'color' ) {
        $value = sanitize_hex_color( $value );
        if ( $value === null ) wp_send_json_error( 'Invalid color' );
    } elseif ( $layout_rule === 'url' ) {
        $value = esc_url_raw( wp_unslash( $value ) );
    }
    $layout        = (array) get_option( WCMAMTX_CUSTOMIZER_OPT );
    $layout[$key]  = $value;
    update_option( WCMAMTX_CUSTOMIZER_OPT, $layout );
    wp_cache_delete( WCMAMTX_CUSTOMIZER_OPT, 'options' );
    wp_send_json_success( ['key' => $key, 'value' => $value] );
} );

add_action( 'wp_ajax_wcmamtx_customizer_save_bulk', function() {
    check_ajax_referer( 'wcmamtx_cz_layout', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
    $pairs = isset( $_POST['pairs'] ) ? json_decode( wp_unslash( $_POST['pairs'] ), true ) : null;
    if ( ! is_array( $pairs ) ) wp_send_json_error( 'Invalid pairs' );
    $layout_allowed = [
        'nav_style'                       => ['01','02','03','04','05','06','07','08'],
        'sidebar_style'                   => ['01'],
        'dash_style'                      => ['01','02','03','04'],
        'order_template_override'         => ['01','02'],
        'order_style'                     => ['01','02'],
        'download_template_override'      => ['01','02'],
        'download_style'                  => ['01','02'],
        'view_order_template_override'    => ['01','02'],
        'view_order_style'                => ['01','02'],
        'thankyou_template_override'      => ['01','02'],
        'thankyou_style'                  => ['01','02'],
        'orderpay_template_override'      => ['01','02'],
        'orderpay_style'                  => ['01','02'],
        'nav_header_widget'               => ['yes','no'],
        'show_only_logged_in'             => ['yes','no'],
        'navwidget_disable_avatar'        => ['yes','no'],
        'navwidget_disable_username'      => ['yes','no'],
        'navigationwidget_layout_override'=> ['01','02'],
        'spending_layout_override'        => ['01','02'],
        'spendingchart_override'          => ['01','02'],
        'dashlink_layout_override'        => ['01','02'],
        'profilebox_override'             => ['01','02'],
        'dashlink_box_override'           => ['01','02'],
        'spendingbox_dashboard_priority'  => 'priority',
        'spendingchart_dashboard_priority'=> 'priority',
        'dashlinks_dashboard_priority'    => 'priority',
        'profilebox_dashboard_priority'   => 'priority',
        'linkbox_dashboard_priority'      => 'priority',
        'nav_header_widget_text'          => 'text',
        'nav_header_widget_text_logout'   => 'text',
        'widget_menu_location'            => 'menuint',
        'shipment_tracking_override'      => ['01','02'],
        'shortcode1_override'             => ['01','02'],
        'shortcode2_override'             => ['01','02'],
        'shortcode1_value'                => 'text',
        'shortcode2_value'                => 'text',
        'shortcode1_dashboard_priority'   => 'priority',
        'shortcode2_dashboard_priority'   => 'priority',
        'guest_dashboard_enable'          => ['01','02'],
        'guest_dashboard_page'            => 'posint',
        'guest_modal_popup'               => ['yes','no'],
        'popup_login_enable'              => ['01','02'],
        'navwidget_loggedout_popup'       => ['yes','no'],
        'formlogin_layout_override'       => ['01','02'],
        'google_social_login'             => ['yes','no'],
        'google_client_id'                => 'text',
        'google_client_secret'            => 'text',
        'facebook_social_login'           => ['yes','no'],
        'facebook_app_id'                 => 'text',
        'facebook_app_secret'             => 'text',
        'login_page_headline'             => 'text',
        'login_page_subtitle'             => 'text',
        'login_page_badge_text'           => 'text',
        'login_page_gradient_start'       => 'color',
        'login_page_gradient_end'         => 'color',
        'login_page_bg_image'             => 'url',
        'login_page_bg_size'              => 'text',
        'login_page_text_color'           => 'color',
        'login_page_badge_bg'             => 'color',
    ];
    $layout = (array) get_option( WCMAMTX_CUSTOMIZER_OPT );
    foreach ( $pairs as $pair ) {
        $key       = isset( $pair['key'] )   ? sanitize_key( $pair['key'] )   : '';
        $raw_value = isset( $pair['value'] )  ? $pair['value']                 : '';
        $value     = sanitize_text_field( wp_unslash( $raw_value ) );
        if ( ! $key || ! array_key_exists( $key, $layout_allowed ) ) continue;
        $rule = $layout_allowed[ $key ];
        if ( is_array( $rule ) ) {
            if ( ! in_array( $value, $rule, true ) ) continue;
        } elseif ( $rule === 'priority' ) {
            $value = (string) absint( $value );
            if ( empty( $value ) ) continue;
        } elseif ( $rule === 'menuint' || $rule === 'posint' ) {
            $value = (string) absint( $value );
        } elseif ( $rule === 'color' ) {
            $value = sanitize_hex_color( $value );
            if ( $value === null ) continue;
        } elseif ( $rule === 'url' ) {
            $value = esc_url_raw( wp_unslash( $raw_value ) );
        }
        $layout[ $key ] = $value;
    }
    update_option( WCMAMTX_CUSTOMIZER_OPT, $layout );
    wp_cache_delete( WCMAMTX_CUSTOMIZER_OPT, 'options' );
    wp_send_json_success();
} );

add_action( 'wp_ajax_wcmamtx_upload_login_bg', function() {
    check_ajax_referer( 'wcmamtx_cz_layout', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
    if ( ! function_exists( 'media_handle_upload' ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }
    if ( empty( $_FILES['file'] ) ) wp_send_json_error( 'No file uploaded' );
    $id = media_handle_upload( 'file', 0 );
    if ( is_wp_error( $id ) ) wp_send_json_error( $id->get_error_message() );
    wp_send_json_success( [ 'url' => wp_get_attachment_url( $id ) ] );
} );

add_action( 'wp_ajax_wcmamtx_customizer_save_avatar', function() {
    check_ajax_referer( 'wcmamtx_cz_avatar', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
    $key   = isset( $_POST['key'] )   ? sanitize_key( $_POST['key'] ) : '';
    $value = isset( $_POST['value'] ) ? sanitize_text_field( wp_unslash( $_POST['value'] ) ) : '';
    if ( ! $key ) wp_send_json_error( 'Missing key' );
    $avatar_allowed = [
        'disable_avatar'       => ['yes','no'],
        'allow_avatar_change'  => ['yes','no'],
        'webcam_capture'       => ['yes','no'],
        'enable_cropping'      => ['yes','no'],
        'disable_gravtar'      => ['yes','no'],
        'override_texts'       => ['yes','no'],
        'custom_avatar_content'=> ['yes','no'],
        'avatar_size'          => 'posint',
        'min_height'           => 'posint',
        'min_width'            => 'posint',
        'max_height'           => 'posint',
        'max_width'            => 'posint',
        'max_size'             => 'posint',
        'text1'                => 'text',
        'text2'                => 'text',
        'text3'                => 'text',
        'text4'                => 'text',
    ];
    if ( ! array_key_exists( $key, $avatar_allowed ) ) wp_send_json_error( 'Invalid key' );
    $avatar_rule = $avatar_allowed[$key];
    if ( is_array( $avatar_rule ) ) {
        if ( ! in_array( $value, $avatar_rule, true ) ) wp_send_json_error( 'Invalid value' );
    } elseif ( $avatar_rule === 'posint' ) {
        $value = (string) absint( $value );
    }
    $settings = (array) get_option( 'wcmamtx_avatar_settings' );
    unset( $settings[0] );
    $settings[$key] = $value;
    update_option( 'wcmamtx_avatar_settings', $settings );
    wp_cache_delete( 'wcmamtx_avatar_settings', 'options' );
    wp_send_json_success( ['key' => $key, 'value' => $value] );
} );

// AJAX handler for content_avatar (HTML — uses wp_kses_post, not sanitize_text_field)
add_action( 'wp_ajax_wcmamtx_customizer_save_avatar_content', function() {
    check_ajax_referer( 'wcmamtx_cz_avatar', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
    $html     = isset( $_POST['content'] ) ? wp_kses_post( wp_unslash( $_POST['content'] ) ) : '';
    $settings = (array) get_option( 'wcmamtx_avatar_settings' );
    unset( $settings[0] );
    $settings['content_avatar'] = $html;
    update_option( 'wcmamtx_avatar_settings', $settings );
    wp_cache_delete( 'wcmamtx_avatar_settings', 'options' );
    wp_send_json_success();
} );

// AJAX handler for linkbox nav menus (array value)
add_action( 'wp_ajax_wcmamtx_customizer_save_linkbox_menus', function() {
    check_ajax_referer( 'wcmamtx_cz_menus', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
    $menus   = isset( $_POST['menus'] ) ? array_map( 'absint', (array) $_POST['menus'] ) : [];
    $layout  = (array) get_option( WCMAMTX_CUSTOMIZER_OPT );
    $layout['linkbox_nav_menus'] = $menus;
    update_option( WCMAMTX_CUSTOMIZER_OPT, $layout );
    wp_cache_delete( WCMAMTX_CUSTOMIZER_OPT, 'options' );
    wp_send_json_success();
} );

// AJAX handler for allowed_formats (array value)
add_action( 'wp_ajax_wcmamtx_customizer_save_avatar_formats', function() {
    check_ajax_referer( 'wcmamtx_cz_formats', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
    $formats  = isset( $_POST['formats'] ) ? array_map( 'sanitize_key', (array) $_POST['formats'] ) : [];
    $settings = (array) get_option( 'wcmamtx_avatar_settings' );
    unset( $settings[0] ); // Remove corrupt numeric key
    $settings['allowed_formats'] = $formats;
    update_option( 'wcmamtx_avatar_settings', $settings );
    wp_cache_delete( 'wcmamtx_avatar_settings', 'options' );
    wp_send_json_success();
} );

// AJAX handler — upload image file and save attachment ID to avatar settings
add_action( 'wp_ajax_wcmamtx_customizer_upload_avatar_img', function() {
    check_ajax_referer( 'wcmamtx_cz_img', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
    $key = isset( $_POST['key'] ) ? sanitize_key( $_POST['key'] ) : '';
    if ( ! in_array( $key, [ 'upload_icon', 'custom_default_avatar' ], true ) ) wp_send_json_error( 'Invalid key' );
    if ( empty( $_FILES['file'] ) ) wp_send_json_error( 'No file' );
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    $attachment_id = media_handle_upload( 'file', 0 );
    if ( is_wp_error( $attachment_id ) ) wp_send_json_error( $attachment_id->get_error_message() );
    $settings        = (array) get_option( 'wcmamtx_avatar_settings' );
    $settings[ $key ] = $attachment_id;
    update_option( 'wcmamtx_avatar_settings', $settings );
    wp_cache_delete( 'wcmamtx_avatar_settings', 'options' );
    wp_send_json_success( [ 'id' => $attachment_id, 'url' => wp_get_attachment_url( $attachment_id ) ] );
} );

// AJAX handler — Quick Help contact form (proxies to sysbasics.com REST endpoint)
add_action( 'wp_ajax_wcmamtx_quick_help_submit', function() {
    check_ajax_referer( 'wcmamtx_cz_quick_help', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
    $email   = isset( $_POST['email'] )   ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
    if ( ! is_email( $email ) ) wp_send_json_error( 'Invalid email address.' );
    if ( empty( $message ) ) wp_send_json_error( 'Message cannot be empty.' );
    $response = wp_remote_post( 'https://sysbasics.com/wp-json/wcmamtx/v1/quick-help', [
        'timeout' => 15,
        'body'    => [
            'email'   => $email,
            'message' => $message,
            'site'    => get_site_url(),
            'version' => function_exists( 'wcmamtx_get_plugin_version_number' ) ? wcmamtx_get_plugin_version_number() : '',
            'api_key' => WCMAMTX_QH_API_KEY,
        ],
    ] );
    if ( is_wp_error( $response ) ) wp_send_json_error( $response->get_error_message() );
    $body = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( ! empty( $body['success'] ) ) {
        wp_send_json_success();
    } else {
        wp_send_json_error( ! empty( $body['data'] ) ? $body['data'] : 'Failed to send message.' );
    }
} );

// AJAX handler — remove image (set key to 0)
add_action( 'wp_ajax_wcmamtx_customizer_remove_avatar_img', function() {
    check_ajax_referer( 'wcmamtx_cz_img', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
    $key = isset( $_POST['key'] ) ? sanitize_key( $_POST['key'] ) : '';
    if ( ! in_array( $key, [ 'upload_icon', 'custom_default_avatar' ], true ) ) wp_send_json_error( 'Invalid key' );
    $settings = (array) get_option( 'wcmamtx_avatar_settings' );
    unset( $settings[ $key ] );
    update_option( 'wcmamtx_avatar_settings', $settings );
    wp_cache_delete( 'wcmamtx_avatar_settings', 'options' );
    wp_send_json_success();
} );

add_action( 'wp_ajax_wcmamtx_dismiss_review_notice', function() {
    check_ajax_referer( 'wcmamtx_review_notice', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error();
    update_option( 'wcmamtx_review_dismissed', 1 );
    wp_send_json_success();
} );

function wcmamtx_customizer_render_page() {
    if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );
    ob_end_clean();
    ob_start();

    $layout      = (array) get_option( WCMAMTX_CUSTOMIZER_OPT );
    $preview_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
    $nonce_layout  = wp_create_nonce( 'wcmamtx_cz_layout' );
    $nonce_avatar  = wp_create_nonce( 'wcmamtx_cz_avatar' );
    $nonce_menus   = wp_create_nonce( 'wcmamtx_cz_menus' );
    $nonce_formats = wp_create_nonce( 'wcmamtx_cz_formats' );
    $nonce_img        = wp_create_nonce( 'wcmamtx_cz_img' );
    $nonce_quick_help = wp_create_nonce( 'wcmamtx_cz_quick_help' );
    $admin_user_email = wp_get_current_user()->user_email;
    $ajax_url    = admin_url( 'admin-ajax.php' );
    $back_url    = admin_url( 'admin.php?page=wcmamtx_advanced_settings&tab=wcmamtx_layout' );

    $g = function($k, $d) use ($layout) { return isset($layout[$k]) ? $layout[$k] : $d; };

    $nav_style     = $g('nav_style',                '02');
    $sidebar_style = $g('sidebar_style',            '01');
    $dash_style    = $g('dash_style',               '01');
    $profilebox    = $g('profilebox_override',      '02');
    $dashlinks     = $g('dashlink_layout_override', '01');
    $guest_enable              = $g('guest_dashboard_enable', '02');
    $guest_modal_popup         = $g('guest_modal_popup',         'no');
    $guest_page_id             = (int) $g('guest_dashboard_page', 0);
    $popup_login_enable        = $g('popup_login_enable', '02');
    $formlogin_layout_override = $g('formlogin_layout_override', '02');
    $google_client_id          = $g('google_client_id', '');
    $google_client_secret      = $g('google_client_secret', '');
    $google_social_login       = $g('google_social_login', $google_client_id !== '' ? 'yes' : 'no');
    $facebook_app_id           = $g('facebook_app_id', '');
    $facebook_app_secret       = $g('facebook_app_secret', '');
    $facebook_social_login     = $g('facebook_social_login', $facebook_app_id !== '' ? 'yes' : 'no');
    $login_page_headline       = $g( 'login_page_headline', '' );
    $login_page_subtitle       = $g( 'login_page_subtitle', '' );
    $login_page_badge_text     = $g( 'login_page_badge_text', '' );
    $login_page_gradient_start = $g( 'login_page_gradient_start', '#667eea' );
    $login_page_gradient_end   = $g( 'login_page_gradient_end', '#764ba2' );
    $login_page_bg_image       = $g( 'login_page_bg_image', '' );
    $login_page_bg_size        = $g( 'login_page_bg_size', 'cover' );
    $login_page_text_color     = $g( 'login_page_text_color', '' );
    $login_page_badge_bg       = $g( 'login_page_badge_bg', '' );
    $all_pages_cz              = get_pages( array( 'post_status' => 'publish', 'sort_column' => 'post_title' ) );
    $spending      = $g('spending_layout_override', '01');
    $spendingchart = $g('spendingchart_override',   '01');
    $dashlink_box  = $g('dashlink_box_override',    '02');
    $tracking_on   = $g('shipment_tracking_override', '02') === '01';



    // Avatar settings (separate option)
    $av = (array) get_option( 'wcmamtx_avatar_settings' );

    $avatar_settings = (array) get_option( 'wcmamtx_avatar_settings' );
    
    if ( array_key_exists( 0, $av ) ) {
        $av = ['disable_avatar'=>'yes','round_avatar'=>'yes','webcam_capture'=>'yes',
               'avatar_size'=>'200','min_height'=>'150','min_width'=>'150','max_height'=>'200','max_width'=>'200'];
    }
    $av_g = function($k,$d) use ($av){ return isset($av[$k]) ? $av[$k] : $d; };

    // Review notice: seed install time for existing installs that predate this feature
    add_option( 'wcmamtx_install_time', time() );
    $install_time       = (int) get_option( 'wcmamtx_install_time', 0 );
    $installed_24h      = $install_time > 0 && ( time() - $install_time ) > DAY_IN_SECONDS;
    $notice_dismissed   = (bool) get_option( 'wcmamtx_review_dismissed', false );
    $has_real_settings  = ! empty( $av ) && ! array_key_exists( 0, (array) get_option( 'wcmamtx_avatar_settings' ) );
    $show_review_notice = $installed_24h && ! $notice_dismissed && $has_real_settings;
    $review_nonce       = wp_create_nonce( 'wcmamtx_review_notice' );
    $av_show          = $av_g('disable_avatar','yes');
    $av_round         = $av_g('round_avatar','yes');
    $av_no_upload     = $av_g('allow_avatar_change','no');
    $av_no_gravatar   = $av_g('disable_gravtar','no');
    $av_max_size      = $av_g('max_size','1024');
    $av_webcam        = $av_g('webcam_capture','yes');
    $av_cropping      = $av_g('enable_cropping','no');
    $av_size          = $av_g('avatar_size','200');
    $av_min_h         = $av_g('min_height','150');
    $av_min_w         = $av_g('min_width','150');
    $av_max_h         = $av_g('max_height','200');
    $av_max_w         = $av_g('max_width','200');
    $av_override_txt  = $av_g('override_texts','no');
    $av_text1         = $av_g('text1',__('Restore Default','customize-my-account-for-woocommerce'));
    $av_text2         = $av_g('text2',__('Manage Gravatar','customize-my-account-for-woocommerce'));
    $av_text3         = $av_g('text3',__('Upload Photo','customize-my-account-for-woocommerce'));
    $av_text4         = $av_g('text4',__('Use Camera','customize-my-account-for-woocommerce'));
    $av_custom_cont   = $av_g('custom_avatar_content','yes');
    $av_content       = isset($avatar_settings['content_avatar']) ? $avatar_settings['content_avatar'] : '<p class="wcmamtx_default_text_below_avatar" style="text-align: center;">Hello <strong>{display_name}</strong> (not <strong>{display_name}</strong>? <a href="{user_logout_link}">Log out</a>)</p>';
    $av_formats_def      = ['jpg','jpeg','jpe','gif','png','webp'];
    $av_formats          = isset($av['allowed_formats']) ? $av['allowed_formats'] : $av_formats_def;
    $av_upload_icon_id   = isset($av['upload_icon']) ? (int)$av['upload_icon'] : 0;
    $av_upload_icon_url  = $av_upload_icon_id ? (string) wp_get_attachment_url( $av_upload_icon_id ) : '';
    $av_def_avatar_id    = isset($av['custom_default_avatar']) ? (int)$av['custom_default_avatar'] : 0;
    $av_def_avatar_url   = $av_def_avatar_id ? (string) wp_get_attachment_url( $av_def_avatar_id ) : '';
    $av_all_formats   = ['jpg'=>'JPG','jpeg'=>'JPEG','jpe'=>'JPE','gif'=>'GIF','png'=>'PNG','webp'=>'WEBP'];

    // Nav Menu Widget
    $nw_override   = $g('navigationwidget_layout_override', '01');
    $nw_show       = $g('nav_header_widget',               'yes');
    $nw_location   = $g('widget_menu_location',            '');
    $nw_text_in    = $g('nav_header_widget_text',          'My Account');
    $nw_text_out   = $g('nav_header_widget_text_logout',   'Log In');
    $nw_logged_in  = $g('show_only_logged_in',             'no');
    $nw_dis_avatar       = $g('navwidget_disable_avatar',        'no');
    $nw_dis_user         = $g('navwidget_disable_username',      'no');
    $nw_loggedout_popup  = $g('navwidget_loggedout_popup',       'no');
    $menu_locations = array_keys( get_nav_menu_locations() );

    $nav_options = [
        '01' => __('Theme Default',    'customize-my-account-for-woocommerce'),
        '02' => __('Clean',            'customize-my-account-for-woocommerce'),
        '03' => __('Banking App',      'customize-my-account-for-woocommerce'),
        '04' => __('React Based',      'customize-my-account-for-woocommerce'),
        '05' => __('Minimal Pill',     'customize-my-account-for-woocommerce'),
        '06' => __('Top Horizontal Bar',          'customize-my-account-for-woocommerce'),
        '08' => __('Dark Sidebar',     'customize-my-account-for-woocommerce'),
    ];
    $widget_fields = [
        'profilebox_override'      => ['label'=>__('Profile Complition Wizard',  'customize-my-account-for-woocommerce'),'value'=>$profilebox],
        'dashlink_layout_override' => ['label'=>__('Dashboard Links',   'customize-my-account-for-woocommerce'),'value'=>$dashlinks],
        'spending_layout_override' => ['label'=>__('Spending Boxes',          'customize-my-account-for-woocommerce'),'value'=>$spending],
        'spendingchart_override'   => ['label'=>__('Spending Chart',          'customize-my-account-for-woocommerce'),'value'=>$spendingchart],
        'dashlink_box_override'    => ['label'=>__('Link Boxes',   'customize-my-account-for-woocommerce'),'value'=>$dashlink_box],
    ];

    // Dashboard widget priority order
    $priority_widgets = [
        'spendingbox_dashboard_priority'   => ['label'=>__('Spending Boxes','customize-my-account-for-woocommerce'),'priority'=>(int)$g('spendingbox_dashboard_priority',10), 'icon'=>'dashicons-chart-bar',  'toggle_key'=>'spending_layout_override', 'enabled'=>$g('spending_layout_override','02')==='01'],
        'spendingchart_dashboard_priority' => ['label'=>__('Spending Chart','customize-my-account-for-woocommerce'),'priority'=>(int)$g('spendingchart_dashboard_priority',20),'icon'=>'dashicons-chart-line', 'toggle_key'=>'spendingchart_override',   'enabled'=>$g('spendingchart_override','01')==='01'],
        'dashlinks_dashboard_priority'     => ['label'=>__('Dashboard Links','customize-my-account-for-woocommerce'),'priority'=>(int)$g('dashlinks_dashboard_priority',30),    'icon'=>'dashicons-grid-view',  'toggle_key'=>'dashlink_layout_override', 'enabled'=>$g('dashlink_layout_override','02')==='01'],
        'profilebox_dashboard_priority'    => ['label'=>__('Profile Completion Wizard','customize-my-account-for-woocommerce'),'priority'=>(int)$g('profilebox_dashboard_priority',40),'icon'=>'dashicons-id',         'toggle_key'=>'profilebox_override',      'enabled'=>$g('profilebox_override','02')==='01'],
        'linkbox_dashboard_priority'       => ['label'=>__('Link Boxes',   'customize-my-account-for-woocommerce'),'priority'=>(int)$g('linkbox_dashboard_priority',50),     'icon'=>'dashicons-tagcloud',   'toggle_key'=>'dashlink_box_override',    'enabled'=>$g('dashlink_box_override','02')==='01'],
        'shortcode1_dashboard_priority'    => ['label'=>__('Shortcode 1','customize-my-account-for-woocommerce'),'priority'=>(int)$g('shortcode1_dashboard_priority',60),'icon'=>'dashicons-shortcode','toggle_key'=>'shortcode1_override','enabled'=>$g('shortcode1_override','02')==='01'],
        'shortcode2_dashboard_priority'    => ['label'=>__('Shortcode 2','customize-my-account-for-woocommerce'),'priority'=>(int)$g('shortcode2_dashboard_priority',70),'icon'=>'dashicons-shortcode','toggle_key'=>'shortcode2_override','enabled'=>$g('shortcode2_override','02')==='01'],
    ];
    uasort($priority_widgets, fn($a,$b) => $a['priority'] - $b['priority']);

    // Template preview URLs
    $tpl_preview_urls = [
        'orders'     => wc_get_account_endpoint_url('orders'),
        'downloads'  => wc_get_account_endpoint_url('downloads'),
        'view_order' => (function(){
            $orders = wc_get_orders(['limit'=>1,'status'=>'any']);
            return !empty($orders)
                ? wc_get_account_endpoint_url('view-order') . $orders[0]->get_id()
                : wc_get_account_endpoint_url('orders');
        })(),
        'thankyou'   => get_permalink(get_option('woocommerce_myaccount_page_id')),
        'orderpay'   => get_permalink(get_option('woocommerce_myaccount_page_id')),
    ];

    // Link Boxes nav menus
    $linkbox_nav_menus_selected = isset($layout['linkbox_nav_menus']) ? (array)$layout['linkbox_nav_menus'] : [];
    $all_nav_menus = wp_get_nav_menus();
    $linkbox_enabled   = $g('dashlink_box_override','02') === '01';
    $dashlinks_enabled = $g('dashlink_layout_override','02') === '01';
    $dash_style_val    = $g('dash_style','01');

    // Template vars
    $tpl = [
        'orders'     => ['label'=>__('Orders',    'customize-my-account-for-woocommerce'),'icon'=>'dashicons-cart',         'override_key'=>'order_template_override',      'style_key'=>'order_style',      'override'=>$g('order_template_override','01'),      'style'=>$g('order_style','01'),      'styles'=>['01'=>__('Optimized','customize-my-account-for-woocommerce'),'02'=>__('Default','customize-my-account-for-woocommerce')]],
        'downloads'  => ['label'=>__('Downloads', 'customize-my-account-for-woocommerce'),'icon'=>'dashicons-download',      'override_key'=>'download_template_override',   'style_key'=>'download_style',   'override'=>$g('download_template_override','01'),   'style'=>$g('download_style','01'),   'styles'=>['01'=>__('Optimized','customize-my-account-for-woocommerce'),'02'=>__('Default','customize-my-account-for-woocommerce')]],
        'view_order' => ['label'=>__('View Order','customize-my-account-for-woocommerce'),'icon'=>'dashicons-visibility',    'override_key'=>'view_order_template_override', 'style_key'=>'view_order_style', 'override'=>$g('view_order_template_override','01'), 'style'=>$g('view_order_style','01'), 'styles'=>['01'=>__('Optimized','customize-my-account-for-woocommerce'),'02'=>__('Default','customize-my-account-for-woocommerce')]],
        'thankyou'   => ['label'=>__('Thank You', 'customize-my-account-for-woocommerce'),'icon'=>'dashicons-yes-alt',       'override_key'=>'thankyou_template_override',   'style_key'=>'thankyou_style',   'override'=>$g('thankyou_template_override','01'),   'style'=>$g('thankyou_style','01'),   'styles'=>['01'=>__('Optimized','customize-my-account-for-woocommerce'),'02'=>__('Default','customize-my-account-for-woocommerce')]],
        'orderpay'   => ['label'=>__('Order Pay', 'customize-my-account-for-woocommerce'),'icon'=>'dashicons-money-alt',     'override_key'=>'orderpay_template_override',   'style_key'=>'orderpay_style',   'override'=>$g('orderpay_template_override','01'),   'style'=>$g('orderpay_style','01'),   'styles'=>['01'=>__('Optimized','customize-my-account-for-woocommerce'),'02'=>__('Default','customize-my-account-for-woocommerce')]],
    ];
    ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php esc_html_e('My Account Customizer','customize-my-account-for-woocommerce'); ?></title>
<link rel="stylesheet" href="<?php echo esc_url(includes_url('css/dashicons.min.css')); ?>">
<link rel="stylesheet" href="<?php echo esc_url(wcmamtx_PLUGIN_URL.'assets/css/select2.css'); ?>">

<style>
/* Reset WP admin */
div.woocommerce-MyAccount-content.endpoint-dashboard div.wcmamtx_fornt_prevew_demo_only,a.skip-link.screen-reader-text{
    display: none !important;
}

.cz-inline-row {
    color: white;
}
button.cz-toggle:disabled {
    font-size: 10px;
}
#wpwrap,#wpcontent,#wpbody,#wpbody-content{float:none!important;margin:0!important;padding:0!important;width:100%!important;}
#adminmenuwrap,#adminmenuback,#wpadminbar{display:none!important;}
body.wp-customizer{margin:0!important;padding:0!important;}
* { box-sizing:border-box; margin:0; padding:0; }
html,body{height:100%;overflow:hidden;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;background:#1e1e2e;}
#wcmamtx-customizer{display:flex;flex-direction:column;height:100vh;}
/* TOPBAR */
#wcmcz-topbar{display:flex;align-items:center;justify-content:space-between;height:48px;background:#1e1e2e;padding:0 16px;flex-shrink:0;border-bottom:1px solid #2d2d3f;z-index:100;}
.cz-logo{display:flex;align-items:center;gap:10px;color:#fff;font-weight:700;font-size:14px;text-decoration:none;}
.cz-logo .dashicons{color:#a78bfa;font-size:20px;}
.cz-actions{display:flex;align-items:center;gap:10px;}
.cz-btn{display:inline-flex;align-items:center;gap:6px;padding:7px 16px;border-radius:6px;border:none;font-size:13px;font-weight:600;cursor:pointer;transition:all .2s;text-decoration:none;}
.cz-btn-ghost{background:transparent;color:#a0a0b8;border:1px solid #2d2d3f;}
.cz-btn-ghost:hover{color:#fff;border-color:#555;}
.cz-btn-success{background:#22c55e;color:#fff;}
.cz-btn-success:hover{background:#16a34a;}
.cz-device-btns{display:flex;gap:6px;}
.cz-device-btns button{background:transparent;border:1px solid #2d2d3f;color:#a0a0b8;width:34px;height:34px;border-radius:6px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s;}
.cz-device-btns button.active,.cz-device-btns button:hover{background:#2d2d3f;color:#fff;border-color:#a78bfa;}
#cz-save-status{font-size:12px;color:#a0a0b8;min-width:80px;text-align:right;}
#cz-save-status.saved{color:#22c55e;}
#cz-save-status.saving{color:#f59e0b;}
/* BODY */
#wcmcz-body{display:flex!important;flex:1!important;overflow:hidden!important;flex-direction:row!important;}
/* PANEL */
#wcmcz-panel{width:300px;min-width:240px;background:#13131f;display:flex;flex-direction:column;overflow:hidden;border-right:1px solid #2d2d3f;flex-shrink:0;}
#wcmcz-panel-content{flex:1;overflow-y:auto;padding:8px;scrollbar-width:thin;scrollbar-color:#2d2d3f transparent;}
#wcmcz-panel-content::-webkit-scrollbar{width:4px;}
#wcmcz-panel-content::-webkit-scrollbar-thumb{background:#2d2d3f;border-radius:4px;}
/* ACCORDION */
.cz-accordion{border:1px solid #2d2d3f;border-radius:10px;margin-bottom:8px;overflow:hidden;}
.cz-accordion-header{display:flex;align-items:center;justify-content:space-between;padding:12px 14px;background:#1e1e2e;cursor:pointer;user-select:none;transition:background .2s;}
.cz-accordion-header:hover{background:#252538;}
.cz-accordion-header.active{background:#252538;border-bottom:1px solid #2d2d3f;}
.cz-accordion-title{display:flex;align-items:center;gap:8px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#a0a0b8;}
.cz-accordion-header.active .cz-accordion-title{color:#a78bfa;}
.cz-accordion-title .dashicons{font-size:16px;width:16px;height:16px;}
.cz-accordion-chevron{font-size:11px;color:#6b6b85;transition:transform .25s;display:inline-block;}
.cz-accordion-header.active .cz-accordion-chevron{transform:rotate(180deg);color:#a78bfa;}
.cz-accordion-body{display:none;padding:14px;background:#13131f;}
.cz-accordion-body.open{display:block;}
/* FIELDS */
.cz-group{background:#1e1e2e;border-radius:10px;padding:14px;margin-bottom:10px;}
.cz-group:last-child{margin-bottom:0;}
.cz-group-title{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#6b6b85;margin-bottom:10px;}
.cz-field{margin-bottom:12px;}
.cz-field:last-child{margin-bottom:0;}
.cz-label{display:block;font-size:12px;color:#a0a0b8;margin-bottom:6px;font-weight:500;}
.cz-select{width:100%;background:#13131f;border:1px solid #2d2d3f;color:#e0e0f0;padding:8px 10px;border-radius:6px;font-size:13px;outline:none;cursor:pointer;}
.cz-select:focus{border-color:#a78bfa;}
.cz-toggle-group{display:flex;gap:6px;flex-wrap:wrap;}
.cz-toggle{flex:1;min-width:50px;background:#13131f;border:1px solid #2d2d3f;color:#a0a0b8;padding:7px 10px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;text-align:left;transition:all .2s;}
.cz-toggle.active{background:#a78bfa;color:#fff;border-color:#a78bfa;}
.cz-toggle.cz-pro-locked{opacity:.55;cursor:not-allowed;position:relative;}
.cz-pro-badge{font-size:11px;margin-left:5px;vertical-align:middle;}
#cz-pro-modal-overlay,#cz-banking-modal-overlay,#cz-nav-pro-modal-overlay,#cz-topbar-nav-modal-overlay,#cz-profilebox-modal-overlay,#cz-linkbox-modal-overlay,#cz-dashcard-modal-overlay,#cz-dashtile-modal-overlay,#cz-thankyou-modal-overlay,#cz-orderpay-modal-overlay,#cz-defaulttab-modal-overlay{display:none;position:absolute;inset:0;background:rgba(0,0,0,.55);z-index:9999;align-items:center;justify-content:center;}
#cz-pro-modal-overlay.open,#cz-banking-modal-overlay.open,#cz-nav-pro-modal-overlay.open,#cz-topbar-nav-modal-overlay.open,#cz-profilebox-modal-overlay.open,#cz-linkbox-modal-overlay.open,#cz-dashcard-modal-overlay.open,#cz-dashtile-modal-overlay.open,#cz-thankyou-modal-overlay.open,#cz-orderpay-modal-overlay.open,#cz-defaulttab-modal-overlay.open{display:flex;}
#cz-modal-box{background:#1e1e2e;border:1px solid #2d2d3f;border-radius:12px;padding:28px 28px 22px;max-width:380px;width:90%;text-align:center;position:relative;}
#cz-modal-box .cz-pm-icon{font-size:36px;margin-bottom:12px;}
#cz-modal-box h3{color:#a78bfa;font-size:17px;font-weight:700;margin-bottom:8px;}
#cz-modal-box p{color:#a0a0b8;font-size:13px;line-height:1.6;margin-bottom:18px;}
#cz-modal-box .cz-pm-btn{display:inline-flex;align-items:center;gap:6px;background:#a78bfa;color:#fff;border:none;border-radius:7px;padding:10px 22px;font-size:13px;font-weight:700;cursor:pointer;text-decoration:none;}
#cz-modal-box .cz-pm-btn:hover{background:#7F77DD;}
#cz-modal-box .cz-pm-close{position:absolute;top:12px;right:14px;background:none;border:none;color:#6b6b85;font-size:20px;cursor:pointer;line-height:1;}
#cz-modal-box .cz-pm-close:hover{color:#fff;}
.cz-priority-item.cz-widget-pro-locked{cursor:pointer;border-color:#a78bfa33;}
.cz-priority-item.cz-widget-pro-locked:hover{border-color:#a78bfa;background:#252538;}
#cz-tracking-info{margin-top:8px;}
.cz-tracking-card{border-radius:6px;padding:10px 12px;margin-bottom:6px;}
.cz-tracking-card.does{background:#0d1f0d;border:1px solid #1e4620;}
.cz-tracking-card.doesnt{background:#1f0d0d;border:1px solid #621e1e;}
.cz-tracking-card p{font-size:10px;font-weight:600;margin:0 0 5px 0;text-transform:uppercase;letter-spacing:.06em;}
.cz-tracking-card.does p{color:#86efac;}
.cz-tracking-card.doesnt p{color:#fca5a5;}
.cz-tracking-card ul{margin:0;padding:0;list-style:none;}
.cz-tracking-card li{font-size:12px;line-height:1.6;display:flex;align-items:flex-start;gap:5px;padding:2px 0;}
.cz-tracking-card.does li{color:#bbf7d0;}
.cz-tracking-card.doesnt li{color:#fecaca;}
.cz-tracking-card .cz-ti{flex-shrink:0;font-size:13px;margin-top:2px;}
.cz-tracking-card.does .cz-ti{color:#22c55e;}
.cz-tracking-card.doesnt .cz-ti{color:#ef4444;}
.cz-tracking-link{display:inline-flex;align-items:center;gap:5px;font-size:12px;color:#a78bfa;text-decoration:none;margin-top:6px;padding:5px 10px;background:#1e1535;border:1px solid #4c1d95;border-radius:6px;}
.cz-tracking-link:hover{background:#2d1f52;color:#c4b5fd;border-color:#7c3aed;}
#cz-modal-box .cz-pm-preview-img{width:100%;border-radius:6px;margin-bottom:14px;border:1px solid #2d2d3f;display:block;}
#cz-modal-box .cz-pm-actions{display:flex;gap:10px;justify-content:center;flex-wrap:wrap;}
#cz-modal-box .cz-pm-btn-ghost{display:inline-flex;align-items:center;gap:6px;background:transparent;color:#a78bfa;border:1px solid #a78bfa;border-radius:7px;padding:9px 20px;font-size:13px;font-weight:700;cursor:pointer;text-decoration:none;}
#cz-modal-box .cz-pm-btn-ghost:hover{background:#a78bfa;color:#fff;}
.cz-text-input{width:100%;background:#13131f;border:1px solid #2d2d3f;color:#e0e0f0;padding:8px 10px;border-radius:6px;font-size:13px;outline:none;}
.cz-text-input:focus{border-color:#a78bfa;}
.cz-number-input{width:72px;background:#13131f;border:1px solid #2d2d3f;color:#e0e0f0;padding:7px 8px;border-radius:6px;font-size:13px;outline:none;text-align:center;}
.cz-number-input:focus{border-color:#a78bfa;}
.cz-avatar-slider{flex:1;-webkit-appearance:none;appearance:none;height:4px;background:#2d2d3f;border-radius:2px;outline:none;cursor:pointer;}
.cz-avatar-slider::-webkit-slider-thumb{-webkit-appearance:none;appearance:none;width:16px;height:16px;background:#a78bfa;border-radius:50%;cursor:pointer;}
.cz-avatar-slider::-moz-range-thumb{width:16px;height:16px;background:#a78bfa;border-radius:50%;cursor:pointer;border:none;}
.cz-slider-val{min-width:32px;text-align:right;color:#e0e0f0;font-size:13px;font-weight:600;}
#cz-review-notice{background:#1a1a2e;border:1px solid #2d2d3f;border-left:3px solid #a78bfa;border-radius:8px;padding:10px 12px;margin-bottom:8px;font-size:12px;color:#a0a0b8;line-height:1.6;}
#cz-review-notice a{color:#a78bfa;text-decoration:none;font-weight:600;}
#cz-review-notice a:hover{text-decoration:underline;}
#cz-review-dismiss{background:none;border:none;color:#5a5a7a;font-size:11px;cursor:pointer;padding:0;margin-top:5px;display:inline-block;text-decoration:underline;}
#cz-review-dismiss:hover{color:#a0a0b8;}
.cz-multiselect{width:100%;background:#13131f;border:1px solid #2d2d3f;color:#e0e0f0;padding:6px;border-radius:6px;font-size:12px;outline:none;min-height:90px;}
.cz-multiselect:focus{border-color:#a78bfa;}
.cz-multiselect option:checked{background:#a78bfa;color:#fff;}
.select2-container { width:100% !important; }
.select2-container--default .select2-selection--multiple { background:#13131f !important; border:1px solid #2d2d3f !important; border-radius:6px !important; min-height:36px !important; }
.select2-container--default .select2-selection--multiple .select2-selection__choice { background:#a78bfa !important; border:none !important; color:#fff !important; border-radius:4px !important; padding:2px 8px !important; font-size:11px !important; }
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove { color:#fff !important; margin-right:4px !important; }
.select2-container--default .select2-selection--multiple .select2-selection__rendered { padding:4px 6px !important; }
.select2-dropdown { background:#1e1e2e !important; border:1px solid #2d2d3f !important; border-radius:6px !important; z-index:99999 !important; }
.select2-container--default .select2-results__option { color:#a0a0b8 !important; font-size:12px !important; padding:6px 10px !important; }
.select2-container--default .select2-results__option--highlighted { background:#a78bfa !important; color:#fff !important; }
.select2-container--default .select2-results__option[aria-selected=true] { background:#252538 !important; color:#a78bfa !important; }
.select2-search--dropdown .select2-search__field { background:#13131f !important; border:1px solid #2d2d3f !important; color:#e0e0f0 !important; border-radius:4px !important; padding:5px 8px !important; }
.cz-textarea{width:100%;background:#13131f;border:1px solid #2d2d3f;color:#e0e0f0;padding:8px 10px;border-radius:6px;font-size:12px;font-family:inherit;outline:none;resize:vertical;min-height:80px;line-height:1.5;}
.cz-textarea:focus{border-color:#a78bfa;}
.cz-inline-row{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
.cz-inline-label{font-size:11px;color:#6b6b85;}
/* Priority drag list */
#cz-priority-list{list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:4px;}
.cz-priority-item{display:flex;align-items:center;gap:8px;background:#1e1e2e;border:1px solid #2d2d3f;border-radius:6px;padding:8px 10px;cursor:grab;user-select:none;transition:background .15s,border-color .15s;}
.cz-priority-item:active{cursor:grabbing;}
.cz-priority-item.cz-dragging{opacity:.4;}
.cz-priority-item.cz-drag-over{border-color:#a78bfa;background:#252538;}
.cz-drag-handle{color:#6b6b85;display:flex;align-items:center;}
.cz-priority-label{font-size:12px;color:#e0e0f0;font-weight:500;flex:1;}
.cz-priority-item .dashicons{font-size:14px;width:14px;height:14px;color:#a78bfa;}
.cz-drag-handle .dashicons{color:#6b6b85;}
/* Widget enable/disable toggle */
.cz-priority-item.cz-disabled{opacity:.45;}
.cz-widget-toggle{position:relative;display:inline-flex;align-items:center;width:32px;height:18px;flex-shrink:0;cursor:pointer;margin-left:auto;}
.cz-widget-toggle input{opacity:0;width:0;height:0;position:absolute;}
.cz-widget-toggle-slider{position:absolute;inset:0;background:#2d2d3f;border-radius:18px;transition:background .2s;}
.cz-widget-toggle-slider:before{content:"";position:absolute;width:12px;height:12px;left:3px;top:3px;background:#6b6b85;border-radius:50%;transition:transform .2s,background .2s;}
.cz-widget-toggle input:checked + .cz-widget-toggle-slider{background:#a78bfa;}
.cz-widget-toggle input:checked + .cz-widget-toggle-slider:before{transform:translateX(14px);background:#fff;}
/* PANEL FOOTER */
#wcmcz-panel-footer {
    flex-shrink:0;
    padding:12px;
    border-top:1px solid #2d2d3f;
    background:#13131f;
    display:flex;
    gap:8px;
}
#wcmcz-panel-footer .cz-footer-btn {
    flex:1;
    padding:9px 12px;
    border-radius:8px;
    border:none;
    font-size:12px;
    font-weight:600;
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:6px;
    transition:all .2s;
    text-decoration:none;
}
.cz-footer-btn-outline {
    background:transparent;
    color:#a0a0b8;
    border:1px solid #2d2d3f;
}
.cz-footer-btn-outline:hover { color:#fff; border-color:#a78bfa; background:#252538; }
.cz-footer-btn-purple {
    background:#a78bfa;
    color:#fff;
    border:1px solid #a78bfa;
}
.cz-footer-btn-purple:hover { background:#9163ff; border-color:#9163ff; }
/* PREVIEW */
#wcmcz-preview-wrap{flex:1!important;float:none!important;display:flex;flex-direction:column;background:#1a1a2e;overflow:hidden;position:relative;min-width:0;}
#wcmcz-preview-bar{display:flex;align-items:center;gap:8px;padding:8px 14px;background:#13131f;border-bottom:1px solid #2d2d3f;flex-shrink:0;}
.cz-dots span{display:inline-block;width:10px;height:10px;border-radius:50%;margin-right:4px;}
.dot-red{background:#ff5f56;}.dot-yellow{background:#ffbd2e;}.dot-green{background:#27c93f;}
#wcmcz-preview-url{flex:1;background:#1e1e2e;border:1px solid #2d2d3f;color:#a0a0b8;padding:5px 10px;border-radius:20px;font-size:12px;outline:none;}
#wcmcz-preview-container{flex:1;display:flex;align-items:center;justify-content:center;padding:12px;overflow:auto;transition:all .3s;}
#wcmcz-iframe{width:100%;height:100%;border:none;border-radius:10px;background:#fff;box-shadow:0 8px 40px rgba(0,0,0,.5);transition:all .3s;flex-shrink:0;}
#wcmcz-preview-container.tablet #wcmcz-iframe{width:768px;max-width:768px;}
#wcmcz-preview-container.mobile #wcmcz-iframe{width:100%;max-width:540px;border-radius:24px;box-shadow:0 0 0 10px #1e1e2e,0 8px 40px rgba(0,0,0,.5);}
#wcmcz-loader{position:absolute;inset:0;background:rgba(13,13,26,.85);display:flex;align-items:center;justify-content:center;z-index:10;opacity:0;pointer-events:none;transition:opacity .2s;}
#wcmcz-loader.visible{opacity:1;pointer-events:all;}
.cz-spinner{width:36px;height:36px;border:3px solid #2d2d3f;border-top-color:#a78bfa;border-radius:50%;animation:czSpin .7s linear infinite;}
@keyframes czSpin{to{transform:rotate(360deg);}}
a.wcmamtx_accordion_label_small
 {
    color: white;
    font-size: 10px;
}
.cz-img-upload-row {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 6px;
    flex-wrap: wrap;
}
#cz-quick-help-overlay{display:none;position:absolute;inset:0;background:rgba(0,0,0,.6);z-index:9999;align-items:center;justify-content:center;}
#cz-quick-help-overlay.open{display:flex;}
#cz-quick-help-overlay #cz-modal-box{text-align:left;max-width:420px;}
#cz-quick-help-overlay #cz-modal-box h3{text-align:left;}
#cz-quick-help-overlay #cz-modal-box p{text-align:left;}
#cz-quick-help-overlay .cz-text-input{width:100%;box-sizing:border-box;}
#cz-quick-help-overlay textarea.cz-text-input{resize:vertical;min-height:110px;font-family:inherit;line-height:1.5;}
.cz-qh-actions{display:flex;gap:10px;align-items:center;flex-wrap:wrap;}
</style>
</head>
<body class="wp-customizer">
<div id="wcmamtx-customizer">

<!-- TOPBAR -->
<div id="wcmcz-topbar">
    <span class="cz-logo">
        <span class="dashicons dashicons-art"></span>
        <?php esc_html_e('SysBasics My Account Customizer','customize-my-account-for-woocommerce'); ?>
        <a href="<?php echo esc_url( admin_url() ); ?>" target="_blank" rel="noopener" class="cz-btn cz-btn-ghost" style="font-size:11px;padding:2px 8px;opacity:.75;margin-left:6px;gap:4px;" title="<?php esc_attr_e( 'Go to WP Admin', 'customize-my-account-for-woocommerce' ); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <?php esc_html_e( 'WP Admin', 'customize-my-account-for-woocommerce' ); ?>
        </a>
    </span>
    <div class="cz-device-btns">
        <button data-device="desktop" class="active" title="<?php esc_attr_e('Desktop','customize-my-account-for-woocommerce'); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg></button>
        <button data-device="mobile" title="<?php esc_attr_e('Mobile','customize-my-account-for-woocommerce'); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2"/><circle cx="12" cy="18" r="1" fill="currentColor" stroke="none"/></svg></button>
    </div>
    <div class="cz-actions">
        <span id="cz-save-status"></span>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wcmamtx_onboarding' ) ); ?>" class="cz-btn cz-btn-ghost" style="font-size:12px;opacity:.75;">
            ✦ <?php esc_html_e( 'Setup Wizard', 'customize-my-account-for-woocommerce' ); ?>
        </a>
        <button class="cz-btn cz-btn-ghost" id="cz-quick-help-btn" style="color:#a78bfa;gap:5px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;vertical-align:middle;"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <?php esc_html_e('Quick Help','customize-my-account-for-woocommerce'); ?>
        </button>
        <button class="cz-btn cz-btn-ghost" id="cz-refresh-btn">
            <span class="dashicons dashicons-update" style="font-size:16px;line-height:1.6;"></span>
            <?php esc_html_e('Refresh','customize-my-account-for-woocommerce'); ?>
        </button>
       
    </div>
</div>

<!-- MAIN -->
<div id="wcmcz-body">

    <!-- PANEL -->
    <div id="wcmcz-panel" style="display:flex;flex-direction:column;">
        <div id="wcmcz-panel-content">

            <?php if ( $show_review_notice ) : ?>
            <div id="cz-review-notice">
                &#9733; <?php esc_html_e( 'Enjoying the plugin? Your review means the world to us!', 'customize-my-account-for-woocommerce' ); ?>
                <br>
                <a href="https://wordpress.org/support/plugin/customize-my-account-for-woocommerce/reviews/#new-post" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Leave a review &rarr;', 'customize-my-account-for-woocommerce' ); ?></a>
                <br>
                <button id="cz-review-dismiss" data-nonce="<?php echo esc_attr( $review_nonce ); ?>"><?php esc_html_e( 'Maybe later', 'customize-my-account-for-woocommerce' ); ?></button>
            </div>
            <script>
            (function(){
                var btn = document.getElementById('cz-review-dismiss');
                if (!btn) return;
                btn.addEventListener('click', function() {
                    document.getElementById('cz-review-notice').style.display = 'none';
                    var fd = new FormData();
                    fd.append('action', 'wcmamtx_dismiss_review_notice');
                    fd.append('nonce', btn.dataset.nonce);
                    fetch(<?php echo wp_json_encode( admin_url('admin-ajax.php') ); ?>, { method: 'POST', body: fd });
                });
            })();
            </script>
            <?php endif; ?>

            <!-- NAVIGATION -->
            <div class="cz-accordion" data-accordion="navigation">
                <div class="cz-accordion-header" data-target="navigation">
                    <span class="cz-accordion-title"><span class="dashicons dashicons-leftright"></span><?php esc_html_e('Sidebar Navigation','customize-my-account-for-woocommerce'); ?></span>
                    <span class="cz-accordion-chevron">&#9660;</span>
                </div>
                <div class="cz-accordion-body" id="cz-acc-navigation">
                    <div class="cz-group">
                        <div class="cz-group-title"><?php esc_html_e('Navigation Style','customize-my-account-for-woocommerce'); ?></div>
                        <div class="cz-field">
                            <div class="cz-toggle-group" style="flex-direction:column;">
                                <?php
                            $pro_only_nav = ['03','06','07','08'];
                            foreach($nav_options as $val=>$label): ?>
                                <?php if(in_array($val,$pro_only_nav,true)): ?>
                                <?php $nav_modal_id = ($val === '03') ? 'cz-banking-modal-overlay' : ($val === '06' ? 'cz-topbar-nav-modal-overlay' : 'cz-nav-pro-modal-overlay'); ?>
                                <button class="cz-toggle cz-pro-locked" type="button" title="Pro feature" data-modal="<?php echo esc_attr($nav_modal_id); ?>" style="text-align:left;padding:10px 12px;"><?php echo esc_html($label); ?><span class="cz-pro-badge">&#128274;</span></button>
                                <?php else: ?>
                                <button class="cz-toggle <?php echo esc_attr($nav_style===$val?'active':''); ?>" data-key="nav_style" data-value="<?php echo esc_attr($val); ?>" style="text-align:left;padding:10px 12px;"><?php echo esc_html($label); ?></button>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div id="cz-react-nav-notice" style="display:<?php echo esc_attr($nav_style==='04'?'block':'none'); ?>;margin-top:8px;background:#2a1f3d;border:1px solid #7c3aed;border-radius:6px;padding:10px 12px;">
                        <p style="font-size:12px;color:#c4b5fd;line-height:1.7;margin:0;">
                            <span style="font-size:14px;margin-right:6px;">&#9888;</span>
                            <?php echo wp_kses( __( 'This will fully replace your WooCommerce My Account page with a React based navigation system.If you experience any third party plugin JavaScript getting broken, consider switching back to any of the other available navigation styles.', 'customize-my-account-for-woocommerce' ), ['strong'=>[]] ); ?>
                        </p>
                    </div>
                </div>

                <?php do_action( 'wcmamtx_customizer_section_navigation' ); // Add options to Sidebar Navigation accordion ?>
            </div>

            <!-- WIDGETS -->
            <div class="cz-accordion" data-accordion="widgets">
                <div class="cz-accordion-header" data-target="widgets">
                    <span class="cz-accordion-title"><span class="dashicons dashicons-welcome-widgets-menus"></span><?php esc_html_e('Dashboard Widgets','customize-my-account-for-woocommerce'); ?></span>
                    <span class="cz-accordion-chevron">&#9660;</span>
                </div>
                <div class="cz-accordion-body" id="cz-acc-widgets">
                    

                    <div class="cz-group">
                        <div class="cz-group-title"><?php esc_html_e('Dashboard Order','customize-my-account-for-woocommerce'); ?></div>
                        <div class="cz-field">
                            <p class="cz-label" style="margin-bottom:8px;"><?php esc_html_e('Drag to reorder','customize-my-account-for-woocommerce'); ?></p>
                            <ul id="cz-priority-list">
                                <?php foreach($priority_widgets as $key => $pw):
                                $is_locked = in_array($pw['toggle_key'], ['profilebox_override','dashlink_box_override'], true);
                                $modal_id  = $pw['toggle_key'] === 'profilebox_override' ? 'cz-profilebox-modal-overlay' : 'cz-linkbox-modal-overlay';
                                ?>
                                <li class="cz-priority-item<?php echo $is_locked ? ' cz-widget-pro-locked' : ''; ?>"
                                    data-key="<?php echo esc_attr($key); ?>"
                                    data-toggle-key="<?php echo esc_attr($pw['toggle_key']); ?>"
                                    <?php if($is_locked): ?>data-modal="<?php echo esc_attr($modal_id); ?>" style="cursor:pointer;"<?php endif; ?>>
                                    <span class="cz-drag-handle">
                                        <?php if($is_locked): ?><span style="color:#a78bfa;">&#128274;</span><?php else: ?><span class="dashicons dashicons-menu"></span><?php endif; ?>
                                    </span>
                                    <span class="dashicons <?php echo esc_attr($pw['icon']); ?>"></span>
                                    <span class="cz-priority-label">
                                        <?php echo esc_html($pw['label']); ?>
                                        <?php if($is_locked): ?><span style="font-size:10px;color:#a78bfa;font-weight:700;margin-left:4px;">PRO</span><?php endif; ?>
                                    </span>
                                    <?php if($is_locked): ?>
                                    <span style="font-size:11px;color:#a78bfa;font-weight:700;margin-left:auto;">Unlock &#9656;</span>
                                    <?php else: ?>
                                    <label class="cz-widget-toggle" title="<?php esc_attr_e('Enable / Disable','customize-my-account-for-woocommerce'); ?>">
                                        <input type="checkbox" class="cz-widget-checkbox" <?php echo $pw['enabled'] ? 'checked' : ''; ?>>
                                        <span class="cz-widget-toggle-slider"></span>
                                    </label>
                                    <?php endif; ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- SHORTCODE 1 ACCORDION -->
                <div class="cz-accordion" id="wcmamtx-sc-wrap-1" style="<?php echo $g('shortcode1_override','02')==='01' ? '' : 'display:none;'; ?>">
                    <div class="cz-accordion-header" data-target="wcmamtx_sc1">
                        <span class="cz-accordion-title"><span class="dashicons dashicons-shortcode"></span><?php esc_html_e('Shortcode 1','customize-my-account-for-woocommerce'); ?></span>
                        <span class="cz-accordion-chevron">&#9660;</span>
                    </div>
                    <div class="cz-accordion-body" id="cz-acc-wcmamtx_sc1">
                        <div class="cz-group">
                            <label class="cz-label"><?php esc_html_e('Shortcode','customize-my-account-for-woocommerce'); ?></label>
                            <input type="text" class="cz-text-input" data-key="shortcode1_value" value="<?php echo esc_attr($g('shortcode1_value','')); ?>" placeholder="[your_shortcode]">
                            <p style="font-size:10px;color:#6b6b85;margin-top:5px;"><?php esc_html_e('Enter a shortcode. It will be rendered on the My Account dashboard.','customize-my-account-for-woocommerce'); ?></p>
                        </div>
                    </div>
                </div>
                <!-- SHORTCODE 2 ACCORDION -->
                <div class="cz-accordion" id="wcmamtx-sc-wrap-2" style="<?php echo $g('shortcode2_override','02')==='01' ? '' : 'display:none;'; ?>">
                    <div class="cz-accordion-header" data-target="wcmamtx_sc2">
                        <span class="cz-accordion-title"><span class="dashicons dashicons-shortcode"></span><?php esc_html_e('Shortcode 2','customize-my-account-for-woocommerce'); ?></span>
                        <span class="cz-accordion-chevron">&#9660;</span>
                    </div>
                    <div class="cz-accordion-body" id="cz-acc-wcmamtx_sc2">
                        <div class="cz-group">
                            <label class="cz-label"><?php esc_html_e('Shortcode','customize-my-account-for-woocommerce'); ?></label>
                            <input type="text" class="cz-text-input" data-key="shortcode2_value" value="<?php echo esc_attr($g('shortcode2_value','')); ?>" placeholder="[your_shortcode]">
                            <p style="font-size:10px;color:#6b6b85;margin-top:5px;"><?php esc_html_e('Enter a shortcode. It will be rendered on the My Account dashboard.','customize-my-account-for-woocommerce'); ?></p>
                        </div>
                    </div>
                </div>
                <?php do_action( 'wcmamtx_customizer_section_widgets' ); // Add options to Dashboard Widgets accordion ?>
            </div>

                        <!-- LAYOUT -->
            <div class="cz-accordion" data-accordion="layout">
                <div class="cz-accordion-header" data-target="layout">
                    <span class="cz-accordion-title"><span class="dashicons dashicons-editor-table"></span><?php esc_html_e('Layout','customize-my-account-for-woocommerce'); ?></span>
                    <span class="cz-accordion-chevron">&#9660;</span>
                </div>
                <div class="cz-accordion-body" id="cz-acc-layout">
                    <div class="cz-group">
                        <div class="cz-group-title"><?php esc_html_e('Sidebar Position','customize-my-account-for-woocommerce'); ?></div>
                        <div class="cz-field">
                            <div class="cz-toggle-group" style="flex-direction:column;">
                                <button class="cz-toggle <?php echo esc_attr($sidebar_style==='01'?'active':''); ?>" data-key="sidebar_style" data-value="01"><?php esc_html_e('Left','customize-my-account-for-woocommerce'); ?></button>
                                <button class="cz-toggle cz-pro-locked" type="button" title="Pro feature"><?php esc_html_e('Right','customize-my-account-for-woocommerce'); ?><span class="cz-pro-badge">&#128274;</span></button>
                                
                            </div>
                        </div>
                    </div>

                </div>

                <?php do_action( 'wcmamtx_customizer_section_layout' ); // Add options to Layout accordion ?>
            </div>

            <!-- LINK BOXES MENUS -->
            <div class="cz-accordion" data-accordion="linkboxmenus" id="cz-acc-wrap-linkboxmenus" style="display:none;">
                <div class="cz-accordion-header" data-target="linkboxmenus">
                    <span class="cz-accordion-title"><span class="dashicons dashicons-tagcloud"></span><?php esc_html_e('Link Box Menus','customize-my-account-for-woocommerce'); ?></span>
                    <span class="cz-accordion-chevron">&#9660;</span>
                </div>
                <div class="cz-accordion-body" id="cz-acc-linkboxmenus">
                    <div class="cz-group">
                        <div class="cz-group-title"><?php esc_html_e('Navigation Menus','customize-my-account-for-woocommerce'); ?></div>
                        <div class="cz-field">
                            <label class="cz-label"><?php esc_html_e('Select menus to display as link boxes','customize-my-account-for-woocommerce'); ?></label>
                            <select id="cma_selected_menu_items" multiple style="width:100%">
                                <?php foreach($all_nav_menus as $menu): ?>
                                <option value="<?php echo esc_attr($menu->term_id); ?>" <?php echo in_array((string)$menu->term_id, array_map('strval',$linkbox_nav_menus_selected)) ? 'selected' : ''; ?>>
                                    <?php echo esc_html($menu->name); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div><!-- /accordion linkboxmenus -->

            <!-- DASHBOARD LINKS STYLE -->
            <div class="cz-accordion" data-accordion="dashlinkstype" id="cz-acc-wrap-dashlinkstype" style="<?php echo $dashlinks_enabled ? '' : 'display:none;'; ?>">
                <div class="cz-accordion-header" data-target="dashlinkstype">
                    <span class="cz-accordion-title"><span class="dashicons dashicons-grid-view"></span><?php esc_html_e('Dashboard Links Style','customize-my-account-for-woocommerce'); ?></span>
                    <span class="cz-accordion-chevron">&#9660;</span>
                </div>
                <div class="cz-accordion-body" id="cz-acc-dashlinkstype">
                    <div class="cz-group">
                        <div class="cz-group-title"><?php esc_html_e('Display Style','customize-my-account-for-woocommerce'); ?></div>
                        <div class="cz-field">
                            <div class="cz-toggle-group" style="flex-direction:column;">
                                <?php
                                $dash_style_options = [
                                    '01' => __('Default','customize-my-account-for-woocommerce'),
                                    '02' => __('Classic','customize-my-account-for-woocommerce'),
                                    '03' => __('Card',   'customize-my-account-for-woocommerce'),
                                    '04' => __('Tile',   'customize-my-account-for-woocommerce'),
                                ];
                                foreach($dash_style_options as $val => $label):
                                $is_dash_locked = in_array($val, ['03','04'], true);
                                $dash_modal_id  = ($val === '03') ? 'cz-dashcard-modal-overlay' : 'cz-dashtile-modal-overlay';
                                ?>
                                <?php if($is_dash_locked): ?>
                                <button class="cz-toggle cz-pro-locked" type="button" title="Pro feature" data-modal="<?php echo esc_attr($dash_modal_id); ?>" style="text-align:left;padding:10px 12px;">
                                    <?php echo esc_html($label); ?><span class="cz-pro-badge">&#128274;</span>
                                </button>
                                <?php else: ?>
                                <button class="cz-toggle <?php echo esc_attr($dash_style_val === $val ? 'active' : ''); ?>" data-key="dash_style" data-value="<?php echo esc_attr($val); ?>" style="text-align:left;padding:10px 12px;">
                                    <?php echo esc_html($label); ?>
                                </button>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /accordion dashlinkstype -->

            <!-- NAV MENU WIDGET -->
            <div class="cz-accordion" data-accordion="navwidget">
                <div class="cz-accordion-header" data-target="navwidget">
                    <span class="cz-accordion-title"><span class="dashicons dashicons-list-view"></span><?php esc_html_e('Nav Menu Widget','customize-my-account-for-woocommerce'); ?><span style="background:linear-gradient(135deg,#ef4444,#f97316);color:#fff;font-size:9px;font-weight:700;padding:2px 6px;border-radius:20px;letter-spacing:.4px;text-transform:uppercase;margin-left:6px;vertical-align:middle;">Hot</span></span>

                    <span class="cz-accordion-chevron">&#9660;</span>
                </div>
                <div class="cz-accordion-body" id="cz-acc-navwidget">
                    <div class="cz-group">
                        <div class="cz-group-title"><?php esc_html_e('Widget Status','customize-my-account-for-woocommerce'); ?></div>
                        <div class="cz-field">
                            <div class="cz-toggle-group">
                                <button class="cz-toggle <?php echo $nw_override==='01'?'active':''; ?>" data-key="navigationwidget_layout_override" data-value="01"><?php esc_html_e('Enable','customize-my-account-for-woocommerce'); ?></button>
                                <button class="cz-toggle <?php echo $nw_override!=='01'?'active':''; ?>" data-key="navigationwidget_layout_override" data-value="02"><?php esc_html_e('Disable','customize-my-account-for-woocommerce'); ?></button>
                            </div>
                        </div>
                    </div>
                    <div class="cz-group" id="cz-navwidget-subopts" style="<?php echo $nw_override==='01'?'':'display:none;'; ?>">
                        <div class="cz-group-title"><?php esc_html_e('Widget Options','customize-my-account-for-woocommerce'); ?></div>
                        
                        <div class="cz-field">
                            <label class="cz-label"><?php esc_html_e('Menu Location','customize-my-account-for-woocommerce'); ?></label>
                            <select class="cz-select" data-key="widget_menu_location">
                                <?php foreach($menu_locations as $loc): ?>
                                <option value="<?php echo esc_attr($loc); ?>" <?php selected($nw_location,$loc); ?>><?php echo esc_html($loc); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="cz-field">
                            <label class="cz-label"><?php esc_html_e('Widget text (logged in)','customize-my-account-for-woocommerce'); ?></label>
                            <input type="text" class="cz-text-input" data-key="nav_header_widget_text" value="<?php echo esc_attr($nw_text_in); ?>" placeholder="<?php esc_attr_e('My Account','customize-my-account-for-woocommerce'); ?>">
                        </div>
                        <div class="cz-field">
                            <label class="cz-label"><?php esc_html_e('Widget text (logged out)','customize-my-account-for-woocommerce'); ?></label>
                            <input type="text" class="cz-text-input" data-key="nav_header_widget_text_logout" value="<?php echo esc_attr($nw_text_out); ?>" placeholder="<?php esc_attr_e('Log In','customize-my-account-for-woocommerce'); ?>">
                        </div>
                        <div class="cz-field">
                            <label class="cz-label"><?php esc_html_e('Show only for logged in users','customize-my-account-for-woocommerce'); ?></label>
                            <div class="cz-toggle-group">
                                <button class="cz-toggle <?php echo $nw_logged_in==='yes'?'active':''; ?>" data-key="show_only_logged_in" data-value="yes"><?php esc_html_e('Yes','customize-my-account-for-woocommerce'); ?></button>
                                <button class="cz-toggle <?php echo $nw_logged_in!=='yes'?'active':''; ?>" data-key="show_only_logged_in" data-value="no"><?php esc_html_e('No','customize-my-account-for-woocommerce'); ?></button>
                            </div>
                        </div>
                        <div class="cz-field">
                            <label class="cz-label"><?php esc_html_e('Disable avatar','customize-my-account-for-woocommerce'); ?></label>
                            <div class="cz-toggle-group">
                                <button class="cz-toggle <?php echo $nw_dis_avatar==='yes'?'active':''; ?>" data-key="navwidget_disable_avatar" data-value="yes"><?php esc_html_e('Yes','customize-my-account-for-woocommerce'); ?></button>
                                <button class="cz-toggle <?php echo $nw_dis_avatar!=='yes'?'active':''; ?>" data-key="navwidget_disable_avatar" data-value="no"><?php esc_html_e('No','customize-my-account-for-woocommerce'); ?></button>
                            </div>
                        </div>
                        <div class="cz-field">
                            <label class="cz-label"><?php esc_html_e('Disable username','customize-my-account-for-woocommerce'); ?></label>
                            <div class="cz-toggle-group">
                                <button class="cz-toggle <?php echo $nw_dis_user==='yes'?'active':''; ?>" data-key="navwidget_disable_username" data-value="yes"><?php esc_html_e('Yes','customize-my-account-for-woocommerce'); ?></button>
                                <button class="cz-toggle <?php echo $nw_dis_user!=='yes'?'active':''; ?>" data-key="navwidget_disable_username" data-value="no"><?php esc_html_e('No','customize-my-account-for-woocommerce'); ?></button>
                            </div>
                        </div>
                        <div class="cz-field" id="cz-nw-popup-field" style="<?php echo $nw_logged_in === 'yes' ? 'display:none;' : ''; ?>padding-top:10px;border-top:1px solid rgba(255,255,255,0.06);margin-top:6px;">
                            <label class="cz-label"><?php esc_html_e('Enable login/register popup form','customize-my-account-for-woocommerce'); ?></label>
                            <div class="cz-toggle-group">
                                <button class="cz-toggle <?php echo $nw_loggedout_popup==='yes'?'active':''; ?>" data-key="navwidget_loggedout_popup" data-value="yes"><?php esc_html_e('Yes','customize-my-account-for-woocommerce'); ?></button>
                                <button class="cz-toggle <?php echo $nw_loggedout_popup!=='yes'?'active':''; ?>" data-key="navwidget_loggedout_popup" data-value="no"><?php esc_html_e('No','customize-my-account-for-woocommerce'); ?></button>
                            </div>
                            <p style="font-size:10px;color:#6b6b85;margin-top:5px;line-height:1.5;"><?php esc_html_e('Opens the login &amp; register form in a popup when the guest nav button is clicked.','customize-my-account-for-woocommerce'); ?></p>
                        </div>
                    </div>
                </div>

                <?php do_action( 'wcmamtx_customizer_section_navwidget' ); // Add options to Nav Menu Widget accordion ?>
            </div>

                                                        <!-- TEMPLATES -->
            <div class="cz-accordion" data-accordion="templates">
                <div class="cz-accordion-header" data-target="templates">
                    <span class="cz-accordion-title"><span class="dashicons dashicons-layout"></span><?php esc_html_e('Templates','customize-my-account-for-woocommerce'); ?></span>
                    <span class="cz-accordion-chevron">&#9660;</span>
                </div>
                <div class="cz-accordion-body" id="cz-acc-templates">
                    <?php
                    $tpl_pro_locked = ['thankyou','orderpay'];
                    $tpl_modal_map  = [
                        'thankyou' => 'cz-thankyou-modal-overlay',
                        'orderpay' => 'cz-orderpay-modal-overlay',
                    ];
                    foreach($tpl as $tpl_key => $t):
                    $is_tpl_locked = in_array($tpl_key, $tpl_pro_locked, true);
                    $tpl_modal_id  = isset($tpl_modal_map[$tpl_key]) ? $tpl_modal_map[$tpl_key] : '';
                    ?>
                    <?php if($is_tpl_locked): ?>
                    <div class="cz-group" data-tpl="<?php echo esc_attr($tpl_key); ?>" style="cursor:pointer;border:1px solid #a78bfa33;border-radius:8px;" onclick="openModal('<?php echo esc_attr($tpl_modal_id); ?>')">
                        <div class="cz-group-title" style="display:flex;align-items:center;justify-content:space-between;">
                            <span>
                                <span class="dashicons <?php echo esc_attr($t['icon']); ?>" style="font-size:13px;width:13px;height:13px;vertical-align:middle;margin-right:4px;"></span>
                                <?php echo esc_html($t['label']); ?>
                                <span style="font-size:10px;color:#a78bfa;font-weight:700;margin-left:6px;">PRO</span>
                            </span>
                            <span style="font-size:11px;color:#a78bfa;font-weight:700;">Unlock &#9656;</span>
                        </div>
                        <div class="cz-field">
                            <div class="cz-toggle-group">
                                <button class="cz-toggle" style="opacity:.45;cursor:not-allowed;" disabled><?php esc_html_e('This Plugin','customize-my-account-for-woocommerce'); ?> &#128274;</button>
                                <button class="cz-toggle active" style="opacity:.45;cursor:not-allowed;" disabled><?php esc_html_e('No Override','customize-my-account-for-woocommerce'); ?></button>
                            </div>
                        </div>
                        <div class="cz-field">
                            <button type="button" onclick="event.stopPropagation(); openModal('<?php echo esc_attr($tpl_modal_id); ?>')" class="cz-tracking-link" style="margin-top:4px;width:100%;justify-content:center;background:transparent;border:none;cursor:pointer;">
                                <span class="dashicons dashicons-art" style="font-size:13px;width:13px;height:13px;line-height:1;"></span>
                                <?php esc_html_e( 'Live Customizer', 'customize-my-account-for-woocommerce' ); ?> <span style="font-size:10px;color:#a78bfa;font-weight:700;margin-left:4px;">PRO &#128274;</span>
                            </button>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="cz-group" data-tpl="<?php echo esc_attr($tpl_key); ?>">
                        <div class="cz-group-title">
                            <span class="dashicons <?php echo esc_attr($t['icon']); ?>" style="font-size:13px;width:13px;height:13px;vertical-align:middle;margin-right:4px;"></span>
                            <?php echo esc_html($t['label']); ?>
                        </div>
                        <div class="cz-field">
                            <label class="cz-label"><?php esc_html_e('Source','customize-my-account-for-woocommerce'); ?></label>
                            <div class="cz-toggle-group">
                                <button class="cz-toggle cz-tpl-override <?php echo $t['override']==='01' ? 'active' : ''; ?>" data-key="<?php echo esc_attr($t['override_key']); ?>" data-value="01"><?php esc_html_e('This Plugin','customize-my-account-for-woocommerce'); ?></button>
                                <button class="cz-toggle cz-tpl-override <?php echo $t['override']==='02' ? 'active' : ''; ?>" data-key="<?php echo esc_attr($t['override_key']); ?>" data-value="02"><?php esc_html_e('No Override','customize-my-account-for-woocommerce'); ?></button>
                            </div>
                        </div>
                        <div class="cz-field cz-tpl-style-wrap" style="<?php echo $t['override']==='01' ? '' : 'display:none;'; ?>">
                            <label class="cz-label"><?php esc_html_e('Style','customize-my-account-for-woocommerce'); ?></label>
                            <div class="cz-toggle-group">
                                <?php foreach($t['styles'] as $sv => $sl): ?>
                                <button class="cz-toggle cz-tpl-style <?php echo $t['style']===$sv ? 'active' : ''; ?>" data-key="<?php echo esc_attr($t['style_key']); ?>" data-value="<?php echo esc_attr($sv); ?>"><?php echo esc_html($sl); ?></button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php
                        $tpl_free_customizer_map = [
                            'orders'     => [ 'page' => 'wcmamtx_orders_customizer',    'label' => __( 'Orders Live Customizer',    'customize-my-account-for-woocommerce' ), 'icon' => 'dashicons-cart' ],
                            'downloads'  => [ 'page' => 'wcmamtx_downloads_customizer', 'label' => __( 'Downloads Live Customizer', 'customize-my-account-for-woocommerce' ), 'icon' => 'dashicons-download' ],
                            'view_order' => [ 'page' => 'wcmamtx_view_order_customizer','label' => __( 'View Order Live Customizer','customize-my-account-for-woocommerce' ), 'icon' => 'dashicons-visibility' ],
                        ];
                        if ( isset( $tpl_free_customizer_map[ $tpl_key ] ) ) :
                            $tpl_cz = $tpl_free_customizer_map[ $tpl_key ];
                        ?>
                        <div class="cz-field">
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $tpl_cz['page'] ) ); ?>" target="_blank" class="cz-tracking-link" style="margin-top:4px;width:100%;justify-content:center;">
                                <span class="dashicons <?php echo esc_attr( $tpl_cz['icon'] ); ?>" style="font-size:13px;width:13px;height:13px;line-height:1;"></span>
                                <?php echo esc_html( $tpl_cz['label'] ); ?>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <?php do_action( 'wcmamtx_customizer_section_templates' ); // Add options to Templates accordion ?>
            </div><!-- /accordion templates -->
<!-- Endpoints -->
                <div class="cz-accordion" data-accordion="Endpoints">
                <div class="cz-accordion-header" data-target="Endpoints">
                    <span class="cz-accordion-title">
                        <span class="dashicons dashicons-networking"></span>
                        <?php esc_html_e( 'Endpoints', 'customize-my-account-for-woocommerce' ); ?>
                        <span style="background:linear-gradient(135deg,#ef4444,#f97316);color:#fff;font-size:9px;font-weight:700;padding:2px 6px;border-radius:20px;letter-spacing:.4px;text-transform:uppercase;margin-left:6px;vertical-align:middle;">Hot</span>
                    </span>
                    <span class="cz-accordion-chevron">&#9660;</span>
                </div>
                <div class="cz-accordion-body" id="cz-acc-Endpoints">
                    <div class="cz-group">
                        
                        <div class="cz-field">
                            <div class="cz-toggle-group" style="flex-direction:column;">
                                
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=wcmamtx_advanced_settings' ) ); ?>" class="cz-btn cz-btn-success" id="">
                                  <?php esc_html_e( 'Manage Endpoints', 'customize-my-account-for-woocommerce' ); ?>
                            </a>
                                
                            </div>
                        </div>
                    </div>
                </div>

                <?php do_action( 'wcmamtx_customizer_section_endpoints' ); // Add options to Endpoints accordion ?>
                </div><!-- /accordion Endpoints -->


            <!-- USER AVATAR -->
            <div class="cz-accordion" data-accordion="avatar">
                <div class="cz-accordion-header" data-target="avatar">
                    <span class="cz-accordion-title"><span class="dashicons dashicons-id-alt"></span><?php esc_html_e('User Avatar','customize-my-account-for-woocommerce'); ?></span>
                    <span class="cz-accordion-chevron">&#9660;</span>
                </div>
                <div class="cz-accordion-body" id="cz-acc-avatar">

                    <!-- Display avatar -->
                    <div class="cz-group">
                        <div class="cz-group-title"><?php esc_html_e('Visibility','customize-my-account-for-woocommerce'); ?></div>
                        <div class="cz-field">
                            <label class="cz-label"><?php esc_html_e('Display avatar','customize-my-account-for-woocommerce'); ?></label>
                            <div class="cz-toggle-group">
                                <button class="cz-toggle cz-avatar-toggle <?php echo $av_show==='yes'?'active':''; ?>" data-opt="disable_avatar" data-value="yes"><?php esc_html_e('Yes','customize-my-account-for-woocommerce'); ?></button>
                                <button class="cz-toggle cz-avatar-toggle <?php echo $av_show!=='yes'?'active':''; ?>" data-opt="disable_avatar" data-value="no"><?php esc_html_e('No','customize-my-account-for-woocommerce'); ?></button>
                            </div>
                        </div>
                    </div>

                    <!-- Sub-options when avatar enabled -->
                    <div id="cz-avatar-subopts" style="<?php echo $av_show==='yes'?'':'display:none;'; ?>">

                        <div class="cz-group">
                            <div class="cz-group-title"><?php esc_html_e('Appearance','customize-my-account-for-woocommerce'); ?></div>

                            <div class="cz-field">
                                <label class="cz-label"><?php esc_html_e('Default avatar size','customize-my-account-for-woocommerce'); ?></label>
                                <div class="cz-inline-row">
                                    <input type="range" class="cz-avatar-slider cz-avatar-number" data-opt="avatar_size" value="<?php echo esc_attr($av_size); ?>" min="96" max="350" oninput="this.nextElementSibling.textContent=this.value">
                                    <span class="cz-slider-val"><?php echo esc_attr($av_size); ?></span> px
                                </div>
                            </div>
                        </div>

                        <div class="cz-group">
                            <div class="cz-group-title"><?php esc_html_e('Upload Settings','customize-my-account-for-woocommerce'); ?></div>
                            <div class="cz-field">
                                <label class="cz-label"><?php esc_html_e('Disallow user upload','customize-my-account-for-woocommerce'); ?></label>
                                <div class="cz-toggle-group">
                                    <button class="cz-toggle cz-avatar-toggle <?php echo $av_no_upload==='yes'?'active':''; ?>" data-opt="allow_avatar_change" data-value="yes"><?php esc_html_e('Yes','customize-my-account-for-woocommerce'); ?></button>
                                    <button class="cz-toggle cz-avatar-toggle <?php echo $av_no_upload!=='yes'?'active':''; ?>" data-opt="allow_avatar_change" data-value="no"><?php esc_html_e('No','customize-my-account-for-woocommerce'); ?></button>
                                </div>
                            </div>
                            <div class="cz-field">
                                <label class="cz-label"><?php esc_html_e('Custom Camera Icon','customize-my-account-for-woocommerce'); ?></label>
                                <input type="file" class="cz-img-file-input" data-img-target="upload_icon" accept="image/*" style="display:none;">
                                <div class="cz-img-upload-row" data-img-target="upload_icon">
                                    <img class="cz-img-preview" src="<?php echo esc_url( $av_upload_icon_url ?: wcmamtx_PLUGIN_URL.'assets/images/camera.svg' ); ?>" style="width:36px;height:36px;object-fit:cover;border-radius:4px;border:1px solid #2d2d3f;background:#1e1e2e;flex-shrink:0;">
                                    <button type="button" class="cz-img-upload-btn cz-btn cz-btn-ghost" data-img-target="upload_icon"><?php esc_html_e('Upload','customize-my-account-for-woocommerce'); ?></button>
                                    <button type="button" class="cz-img-remove-btn cz-btn cz-btn-ghost" data-img-target="upload_icon" style="<?php echo $av_upload_icon_id ? '' : 'display:none;'; ?>"><?php esc_html_e('Remove','customize-my-account-for-woocommerce'); ?></button>
                                </div>
                            </div>
                            <div class="cz-field">
                                <label class="cz-label"><?php esc_html_e('Max image size','customize-my-account-for-woocommerce'); ?></label>
                                <div class="cz-inline-row">
                                    <input type="number" class="cz-number-input cz-avatar-number" data-opt="max_size" value="<?php echo esc_attr($av_max_size); ?>"> KB
                                </div>
                            </div>
                            <div class="cz-field">
                                <label class="cz-label"><?php esc_html_e('Allowed formats','customize-my-account-for-woocommerce'); ?></label>
                                <select class="cz-multiselect" id="cz-avatar-formats" multiple>
                                    <?php foreach($av_all_formats as $fk=>$fv): ?>
                                    <option value="<?php echo esc_attr($fk); ?>" <?php echo in_array($fk,$av_formats)?'selected':''; ?>><?php echo esc_html($fv); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="cz-field">
                                <label class="cz-label"><?php esc_html_e('Webcam capture','customize-my-account-for-woocommerce'); ?></label>
                                <div class="cz-toggle-group">
                                    <button class="cz-toggle cz-avatar-toggle <?php echo $av_webcam==='yes'?'active':''; ?>" data-opt="webcam_capture" data-value="yes"><?php esc_html_e('Yes','customize-my-account-for-woocommerce'); ?></button>
                                    <button class="cz-toggle cz-avatar-toggle <?php echo $av_webcam!=='yes'?'active':''; ?>" data-opt="webcam_capture" data-value="no"><?php esc_html_e('No','customize-my-account-for-woocommerce'); ?></button>
                                </div>
                            </div>
                            <div class="cz-field">
                                <label class="cz-label"><?php esc_html_e('Enable cropping','customize-my-account-for-woocommerce'); ?></label>
                                <div class="cz-toggle-group">
                                    <button class="cz-toggle cz-avatar-toggle <?php echo $av_cropping==='yes'?'active':''; ?>" data-opt="enable_cropping" data-value="yes"><?php esc_html_e('Yes','customize-my-account-for-woocommerce'); ?></button>
                                    <button class="cz-toggle cz-avatar-toggle <?php echo $av_cropping!=='yes'?'active':''; ?>" data-opt="enable_cropping" data-value="no"><?php esc_html_e('No','customize-my-account-for-woocommerce'); ?></button>
                                </div>
                            </div>
                            <div class="cz-field">
                                <label class="cz-label"><?php esc_html_e('No Gravatar as default','customize-my-account-for-woocommerce'); ?></label>
                                <div class="cz-toggle-group">
                                    <button class="cz-toggle cz-avatar-toggle <?php echo $av_no_gravatar==='yes'?'active':''; ?>" data-opt="disable_gravtar" data-value="yes"><?php esc_html_e('Yes','customize-my-account-for-woocommerce'); ?></button>
                                    <button class="cz-toggle cz-avatar-toggle <?php echo $av_no_gravatar!=='yes'?'active':''; ?>" data-opt="disable_gravtar" data-value="no"><?php esc_html_e('No','customize-my-account-for-woocommerce'); ?></button>
                                </div>
                            </div>
                            <div id="cz-avatar-default-wrap" style="<?php echo $av_no_gravatar==='yes' ? '' : 'display:none;'; ?>">
                                <div class="cz-field">
                                    <label class="cz-label"><?php esc_html_e('Custom Default Avatar','customize-my-account-for-woocommerce'); ?></label>
                                    <input type="file" class="cz-img-file-input" data-img-target="custom_default_avatar" accept="image/*" style="display:none;">
                                    <div class="cz-img-upload-row" data-img-target="custom_default_avatar">
                                        <img class="cz-img-preview" src="<?php echo esc_url( $av_def_avatar_url ?: wcmamtx_PLUGIN_URL.'assets/images/default_avatar.jpg' ); ?>" style="width:48px;height:48px;object-fit:cover;border-radius:50%;border:2px solid #2d2d3f;flex-shrink:0;">
                                        <button type="button" class="cz-img-upload-btn cz-btn cz-btn-ghost" data-img-target="custom_default_avatar"><?php esc_html_e('Upload','customize-my-account-for-woocommerce'); ?></button>
                                        <button type="button" class="cz-img-remove-btn cz-btn cz-btn-ghost" data-img-target="custom_default_avatar" style="<?php echo $av_def_avatar_id ? '' : 'display:none;'; ?>"><?php esc_html_e('Remove','customize-my-account-for-woocommerce'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="cz-group">
                            <div class="cz-group-title"><?php esc_html_e('Button Texts','customize-my-account-for-woocommerce'); ?></div>
                            <div class="cz-field">
                                <label class="cz-label"><?php esc_html_e('Override default texts','customize-my-account-for-woocommerce'); ?></label>
                                <div class="cz-toggle-group">
                                    <button class="cz-toggle cz-avatar-toggle <?php echo $av_override_txt==='yes'?'active':''; ?>" data-opt="override_texts" data-value="yes"><?php esc_html_e('Yes','customize-my-account-for-woocommerce'); ?></button>
                                    <button class="cz-toggle cz-avatar-toggle <?php echo $av_override_txt!=='yes'?'active':''; ?>" data-opt="override_texts" data-value="no"><?php esc_html_e('No','customize-my-account-for-woocommerce'); ?></button>
                                </div>
                            </div>
                            <div id="cz-avatar-texts" style="<?php echo $av_override_txt==='yes'?'':'display:none;'; ?>">
                                <div class="cz-field">
                                    <label class="cz-label"><?php esc_html_e('Restore Default','customize-my-account-for-woocommerce'); ?></label>
                                    <input type="text" class="cz-text-input cz-avatar-text" data-opt="text1" value="<?php echo esc_attr($av_text1); ?>">
                                </div>
                                <div class="cz-field">
                                    <label class="cz-label"><?php esc_html_e('Manage Gravatar','customize-my-account-for-woocommerce'); ?></label>
                                    <input type="text" class="cz-text-input cz-avatar-text" data-opt="text2" value="<?php echo esc_attr($av_text2); ?>">
                                </div>
                                <div class="cz-field">
                                    <label class="cz-label"><?php esc_html_e('Upload Photo','customize-my-account-for-woocommerce'); ?></label>
                                    <input type="text" class="cz-text-input cz-avatar-text" data-opt="text3" value="<?php echo esc_attr($av_text3); ?>">
                                </div>
                                <div class="cz-field">
                                    <label class="cz-label"><?php esc_html_e('Use Camera','customize-my-account-for-woocommerce'); ?></label>
                                    <input type="text" class="cz-text-input cz-avatar-text" data-opt="text4" value="<?php echo esc_attr($av_text4); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="cz-group">
                            <div class="cz-group-title"><?php esc_html_e('Custom Content After Avatar','customize-my-account-for-woocommerce'); ?></div>
                            <div class="cz-field">
                                <label class="cz-label"><?php esc_html_e('Enable custom content','customize-my-account-for-woocommerce'); ?></label>
                                <div class="cz-toggle-group">
                                    <button class="cz-toggle cz-avatar-toggle <?php echo $av_custom_cont==='yes'?'active':''; ?>" data-opt="custom_avatar_content" data-value="yes"><?php esc_html_e('Yes','customize-my-account-for-woocommerce'); ?></button>
                                    <button class="cz-toggle cz-avatar-toggle <?php echo $av_custom_cont!=='yes'?'active':''; ?>" data-opt="custom_avatar_content" data-value="no"><?php esc_html_e('No','customize-my-account-for-woocommerce'); ?></button>
                                </div>
                            </div>
                            <div id="cz-avatar-content" style="<?php echo $av_custom_cont==='yes'?'':"display:none;"; ?>margin-top:10px;">
                                <div style="border:1px solid #2d2d3f;border-radius:8px;overflow:hidden;">
                                    <!-- Toolbar -->
                                    <div style="display:flex;align-items:center;gap:3px;padding:5px 8px;background:#1a1a2e;border-bottom:1px solid #2d2d3f;flex-wrap:wrap;row-gap:4px;">
                                        <?php
                                        $czb = 'type="button" style="min-width:26px;height:26px;display:inline-flex;align-items:center;justify-content:center;background:#13131f;border:1px solid #2d2d3f;color:#b0b0c8;border-radius:5px;font-size:12px;cursor:pointer;padding:0 6px;flex-shrink:0;"';
                                        $czs = '<span style="width:1px;height:16px;background:#2d2d3f;margin:0 2px;flex-shrink:0;"></span>';
                                        $czc = 'type="button" class="cz-ac-tag-btn" style="background:#1e1e2e;border:1px solid #3d2d6e;color:#c4b5fd;border-radius:10px;padding:1px 8px;font-size:10px;cursor:pointer;white-space:nowrap;line-height:22px;flex-shrink:0;"';
                                        ?>
                                        <button <?php echo $czb; ?> class="cz-ac-fmt" data-cmd="bold" title="Bold"><b>B</b></button>
                                        <button <?php echo $czb; ?> class="cz-ac-fmt" data-cmd="italic" title="Italic"><em>I</em></button>
                                        <button <?php echo $czb; ?> class="cz-ac-fmt" data-cmd="underline" title="Underline"><u>U</u></button>
                                        <?php echo $czs; ?>
                                        <button <?php echo $czb; ?> id="cz-ac-link" title="<?php esc_attr_e('Insert link','customize-my-account-for-woocommerce'); ?>">&#128279;</button>
                                        <button <?php echo $czb; ?> id="cz-ac-unlink" title="<?php esc_attr_e('Remove link','customize-my-account-for-woocommerce'); ?>" style="min-width:26px;height:26px;display:inline-flex;align-items:center;justify-content:center;background:#13131f;border:1px solid #2d2d3f;color:#b0b0c8;border-radius:5px;font-size:10px;cursor:pointer;padding:0 6px;flex-shrink:0;">&#10006;</button>
                                        <?php echo $czs; ?>
                                        <span style="font-size:10px;color:#6b6b85;white-space:nowrap;line-height:26px;flex-shrink:0;"><?php esc_html_e('+ tag:','customize-my-account-for-woocommerce'); ?></span>
                                        <button <?php echo $czc; ?> data-tag="display_name">&#128100; <?php esc_html_e('Name','customize-my-account-for-woocommerce'); ?></button>
                                        <button <?php echo $czc; ?> data-tag="first_name"><?php esc_html_e('First','customize-my-account-for-woocommerce'); ?></button>
                                        <button <?php echo $czc; ?> data-tag="last_name"><?php esc_html_e('Last','customize-my-account-for-woocommerce'); ?></button>
                                        <button <?php echo $czc; ?> data-tag="user_email"><?php esc_html_e('Email','customize-my-account-for-woocommerce'); ?></button>
                                        <button <?php echo $czc; ?> data-tag="current_date"><?php esc_html_e('Date','customize-my-account-for-woocommerce'); ?></button>
                                        <button <?php echo $czc; ?> data-tag="site_name"><?php esc_html_e('Site','customize-my-account-for-woocommerce'); ?></button>
                                        <button <?php echo $czc; ?> id="cz-ac-logout-link">&#128275; <?php esc_html_e('Logout Link','customize-my-account-for-woocommerce'); ?></button>
                                    </div>
                                    <!-- Editor -->
                                    <div id="cz-ac-editor"
                                         contenteditable="true"
                                         spellcheck="true"
                                         style="min-height:90px;max-height:200px;overflow-y:auto;padding:10px 12px;background:#0d0d1a;color:#e0e0f0;font-size:13px;line-height:1.7;outline:none;word-break:break-word;"><?php echo wp_kses_post( $av_content ); ?></div>
                                    <!-- Footer -->
                                    <div style="display:flex;align-items:center;justify-content:space-between;padding:4px 10px;background:#1a1a2e;border-top:1px solid #2d2d3f;">
                                        <span id="cz-ac-status" style="font-size:10px;color:#6b6b85;"></span>
                                        <button type="button" id="cz-ac-reset" style="font-size:10px;color:#6b6b85;background:none;border:none;cursor:pointer;padding:2px 4px;line-height:1;"><?php esc_html_e('&#x21BA; Reset to default','customize-my-account-for-woocommerce'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div><!-- /avatar subopts -->


                <?php do_action( 'wcmamtx_customizer_section_avatar' ); // Add options to User Avatar accordion ?>
                </div><!-- /accordion-body avatar -->
            </div><!-- /accordion avatar -->

            <!-- MY ACCOUNT FIELDS (addon upsell) -->
            <div class="cz-accordion" data-accordion="myaccountfields">
                <div class="cz-accordion-header" data-target="myaccountfields">
                    <span class="cz-accordion-title">
                        <span class="dashicons dashicons-forms"></span>
                        <?php esc_html_e( 'My Account Fields', 'customize-my-account-for-woocommerce' ); ?>
                    </span>
                    <span class="cz-accordion-chevron">&#9660;</span>
                </div>
                <div class="cz-accordion-body" id="cz-acc-myaccountfields">
                    <div class="cz-group">
                        <p style="font-size:12px;color:#c4c4d4;line-height:1.7;margin:0 0 14px;">
                            <?php esc_html_e( 'To manage your my account and user registration fields consider buying this addon', 'customize-my-account-for-woocommerce' ); ?>
                        </p>
                        <a href="https://www.sysbasics.com/product/woocommerce-my-account-fields/" target="_blank" rel="noopener"
                           style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#7c3aed,#a855f7);color:#fff;font-size:12px;font-weight:700;padding:8px 16px;border-radius:8px;text-decoration:none;letter-spacing:.3px;transition:opacity .18s;">
                            <?php esc_html_e( 'Get My Account Fields', 'customize-my-account-for-woocommerce' ); ?> &rarr;
                        </a>
                    </div>
                </div>
            </div><!-- /accordion myaccountfields -->

            <!-- GUEST DASHBOARD -->
            <div class="cz-accordion" data-accordion="guestdashboard">
                <div class="cz-accordion-header" data-target="guestdashboard">
                    <span class="cz-accordion-title">
                        <span class="dashicons dashicons-groups"></span>
                        <?php esc_html_e('Guest Dashboard','customize-my-account-for-woocommerce'); ?>
                        <span style="background:linear-gradient(135deg,#ef4444,#f97316);color:#fff;font-size:9px;font-weight:700;padding:2px 6px;border-radius:20px;letter-spacing:.4px;text-transform:uppercase;margin-left:6px;vertical-align:middle;">Hot</span>
                    </span>
                    <span class="cz-accordion-chevron">&#9660;</span>
                </div>
                <div class="cz-accordion-body" id="cz-acc-guestdashboard">
                    <div class="cz-group">
                        <div class="cz-group-title"><?php esc_html_e('Guest Dashboard','customize-my-account-for-woocommerce'); ?></div>
                        <p style="font-size:11px;color:#6b6b85;margin-bottom:10px;line-height:1.6;"><?php esc_html_e('Show a guest-friendly My Account sidebar and dashboard cards to logged-out visitors.','customize-my-account-for-woocommerce'); ?></p>

                        <!-- Enable toggle -->
                        <div class="cz-field">
                            <label class="cz-label"><?php esc_html_e('Enable','customize-my-account-for-woocommerce'); ?></label>
                            <div class="cz-toggle-group">
                                <button class="cz-toggle <?php echo $guest_enable==='01'?'active':''; ?>" id="cz-guest-enable-on"  data-guest-toggle="01"><?php esc_html_e('Yes','customize-my-account-for-woocommerce'); ?></button>
                                <button class="cz-toggle <?php echo $guest_enable!=='01'?'active':''; ?>" id="cz-guest-enable-off" data-guest-toggle="02"><?php esc_html_e('No','customize-my-account-for-woocommerce'); ?></button>
                            </div>
                        </div>

                        <!-- Sub-options (shown when enabled) -->
                        <div id="cz-guest-subopts" style="<?php echo $guest_enable==='01'?'':'display:none;'; ?>margin-top:10px;">

                            <!-- Page selector -->
                            <div class="cz-field">
                                <label class="cz-label"><?php esc_html_e('Guest Dashboard Page','customize-my-account-for-woocommerce'); ?></label>
                                <select id="cz-guest-page-select" style="width:100%;">
                                    <option value="">&mdash; <?php esc_html_e('Select a page','customize-my-account-for-woocommerce'); ?> &mdash;</option>
                                    <?php foreach ( $all_pages_cz as $pg ) : ?>
                                    <option value="<?php echo esc_attr($pg->ID); ?>" <?php selected($guest_page_id,$pg->ID); ?>>
                                        <?php echo esc_html($pg->post_title); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Auto-detect / create button -->
                            <div class="cz-field">
                                <button type="button" id="cz-guest-autolink-btn"
                                    style="width:100%;background:#1e1e2e;border:1px solid #a78bfa;color:#a78bfa;padding:8px 12px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;transition:all .2s;">
                                    <span class="dashicons dashicons-search" style="font-size:13px;width:13px;height:13px;line-height:1;"></span>
                                    <?php esc_html_e('Auto-detect or Create Page','customize-my-account-for-woocommerce'); ?>
                                </button>
                                <p id="cz-guest-autolink-status" style="font-size:11px;margin-top:5px;min-height:14px;"></p>
                            </div>

                            <!-- Info notice -->
                            <div style="background:#13131f;border:1px solid #2563eb;border-radius:6px;padding:10px 12px;margin-top:4px;">
                                <p style="font-size:11px;color:#93c5fd;line-height:1.6;margin:0;">
                                    <span style="margin-right:4px;">&#8505;</span>
                                    <?php esc_html_e('Selected page must contain','customize-my-account-for-woocommerce'); ?>
                                    <code style="background:#0d0d1a;padding:1px 5px;border-radius:3px;color:#a78bfa;">[wcmamtx_guest_dashboard]</code>.
                                    <?php esc_html_e('Logged-out visitors will be redirected here.','customize-my-account-for-woocommerce'); ?>
                                </p>
                            </div>

                            
                            <div class="cz-field" style="margin-top:8px;">
                                <a href="<?php echo esc_url(admin_url('admin.php?page=wcmamtx_guest_dashboard_customizer')); ?>" class="cz-tracking-link">
                                    <span class="dashicons dashicons-art" style="font-size:13px;width:13px;height:13px;"></span>
                                    <?php esc_html_e('Guest Dashboard Live Customizer','customize-my-account-for-woocommerce'); ?>
                                </a>
                            </div>

                            <div class="cz-field" style="margin-top:8px;padding-top:10px;border-top:1px solid rgba(255,255,255,0.06);">
                                <label class="cz-label"><?php esc_html_e('Enable modal popup login','customize-my-account-for-woocommerce'); ?></label>
                                <div class="cz-toggle-group">
                                    <button class="cz-toggle <?php echo $guest_modal_popup==='yes'?'active':''; ?>" data-key="guest_modal_popup" data-value="yes"><?php esc_html_e('Yes','customize-my-account-for-woocommerce'); ?></button>
                                    <button class="cz-toggle <?php echo $guest_modal_popup!=='yes'?'active':''; ?>" data-key="guest_modal_popup" data-value="no"><?php esc_html_e('No','customize-my-account-for-woocommerce'); ?></button>
                                </div>
                                <p style="font-size:10px;color:#6b6b85;margin-top:5px;line-height:1.5;"><?php esc_html_e('Replaces login links on the guest dashboard with a popup login/register form.','customize-my-account-for-woocommerce'); ?></p>
                            </div>

                        </div><!-- /#cz-guest-subopts -->
                    </div>
                </div><!-- /.cz-accordion-body -->
                <?php do_action('wcmamtx_customizer_section_guestdashboard'); ?>
            </div><!-- /.accordion guestdashboard -->

            <!-- LOGIN & REGISTER -->
            <div class="cz-accordion" data-accordion="loginregister">
                <div class="cz-accordion-header" data-target="loginregister">
                    <span class="cz-accordion-title"><span class="dashicons dashicons-lock"></span><?php esc_html_e( 'Login &amp; Register', 'customize-my-account-for-woocommerce' ); ?></span>
                    <span class="cz-accordion-chevron">&#9660;</span>
                </div>
                <div class="cz-accordion-body" id="cz-acc-loginregister">
                    <div class="cz-group">
                        <div class="cz-field">
                            <label class="cz-label"><?php esc_html_e( 'Enable Custom Login &amp; Register Page', 'customize-my-account-for-woocommerce' ); ?></label>
                            <div class="cz-toggle-group">
                                <button class="cz-toggle formlogin-toggle <?php echo $formlogin_layout_override === '01' ? 'active' : ''; ?>" data-key="formlogin_layout_override" data-value="01"><?php esc_html_e( 'Enable', 'customize-my-account-for-woocommerce' ); ?></button>
                                <button class="cz-toggle formlogin-toggle <?php echo $formlogin_layout_override !== '01' ? 'active' : ''; ?>" data-key="formlogin_layout_override" data-value="02"><?php esc_html_e( 'Disable', 'customize-my-account-for-woocommerce' ); ?></button>
                            </div>
                        </div>
                        <div id="cz-login-register-fields" style="<?php echo $formlogin_layout_override === '01' ? '' : 'display:none;'; ?>">
                            <div class="cz-field" style="margin-top:14px;padding-top:14px;border-top:1px solid rgba(255,255,255,0.06);">
                                <label class="cz-label"><?php esc_html_e( 'Google Social Login', 'customize-my-account-for-woocommerce' ); ?></label>
                                <div class="cz-toggle-group">
                                    <button class="cz-toggle google-social-toggle <?php echo $google_social_login === 'yes' ? 'active' : ''; ?>" data-key="google_social_login" data-value="yes"><?php esc_html_e( 'Enable', 'customize-my-account-for-woocommerce' ); ?></button>
                                    <button class="cz-toggle google-social-toggle <?php echo $google_social_login !== 'yes' ? 'active' : ''; ?>" data-key="google_social_login" data-value="no"><?php esc_html_e( 'Disable', 'customize-my-account-for-woocommerce' ); ?></button>
                                </div>
                            </div>
                            <div id="cz-google-fields" style="<?php echo $google_social_login === 'yes' ? '' : 'display:none;'; ?>">
                                <div class="cz-field">
                                    <label class="cz-label"><?php esc_html_e( 'Google Client ID', 'customize-my-account-for-woocommerce' ); ?></label>
                                    <input type="text" class="cz-text-input" data-key="google_client_id" value="<?php echo esc_attr( $google_client_id ); ?>" placeholder="xxxxxxxx.apps.googleusercontent.com">
                                </div>
                                <div class="cz-field">
                                    <label class="cz-label"><?php esc_html_e( 'Google Client Secret', 'customize-my-account-for-woocommerce' ); ?></label>
                                    <input type="text" class="cz-text-input" data-key="google_client_secret" value="<?php echo esc_attr( $google_client_secret ); ?>" autocomplete="off" placeholder="GOCSPX-...">
                                </div>
                                <div class="cz-field">
                                    <label class="cz-label"><?php esc_html_e( 'Redirect URL', 'customize-my-account-for-woocommerce' ); ?></label>
                                    <code style="display:block;background:#0d0d1a;border:1px solid #2d2d3f;border-radius:6px;padding:8px;font-size:11px;color:#86efac;word-break:break-all;line-height:1.5;"><?php echo esc_url( home_url( '/?wcmamtx-social=google' ) ); ?></code>
                                    <p style="font-size:10px;color:#6b6b85;margin-top:5px;line-height:1.5;"><?php esc_html_e( 'Add this as an authorized redirect URI in Google Cloud Console.', 'customize-my-account-for-woocommerce' ); ?></p>
                                </div>
                            </div>
                            <div class="cz-field" style="margin-top:14px;padding-top:14px;border-top:1px solid rgba(255,255,255,0.06);">
                                <label class="cz-label"><?php esc_html_e( 'Facebook Social Login', 'customize-my-account-for-woocommerce' ); ?></label>
                                <div class="cz-toggle-group">
                                    <button class="cz-toggle facebook-social-toggle <?php echo $facebook_social_login === 'yes' ? 'active' : ''; ?>" data-key="facebook_social_login" data-value="yes"><?php esc_html_e( 'Enable', 'customize-my-account-for-woocommerce' ); ?></button>
                                    <button class="cz-toggle facebook-social-toggle <?php echo $facebook_social_login !== 'yes' ? 'active' : ''; ?>" data-key="facebook_social_login" data-value="no"><?php esc_html_e( 'Disable', 'customize-my-account-for-woocommerce' ); ?></button>
                                </div>
                            </div>
                            <div id="cz-facebook-fields" style="<?php echo $facebook_social_login === 'yes' ? '' : 'display:none;'; ?>">
                                <div class="cz-field">
                                    <label class="cz-label"><?php esc_html_e( 'Facebook App ID', 'customize-my-account-for-woocommerce' ); ?></label>
                                    <input type="text" class="cz-text-input" data-key="facebook_app_id" value="<?php echo esc_attr( $facebook_app_id ); ?>" placeholder="123456789012345">
                                </div>
                                <div class="cz-field">
                                    <label class="cz-label"><?php esc_html_e( 'Facebook App Secret', 'customize-my-account-for-woocommerce' ); ?></label>
                                    <input type="text" class="cz-text-input" data-key="facebook_app_secret" value="<?php echo esc_attr( $facebook_app_secret ); ?>" autocomplete="off" placeholder="abcdef1234567890...">
                                </div>
                                <div class="cz-field">
                                    <label class="cz-label"><?php esc_html_e( 'Redirect URL', 'customize-my-account-for-woocommerce' ); ?></label>
                                    <code style="display:block;background:#0d0d1a;border:1px solid #2d2d3f;border-radius:6px;padding:8px;font-size:11px;color:#86efac;word-break:break-all;line-height:1.5;"><?php echo esc_url( home_url( '/?wcmamtx-social=facebook' ) ); ?></code>
                                    <p style="font-size:10px;color:#6b6b85;margin-top:5px;line-height:1.5;"><?php esc_html_e( 'Add this as a Valid OAuth Redirect URI in your Facebook App settings.', 'customize-my-account-for-woocommerce' ); ?></p>
                                </div>
                            </div>
                            <div class="cz-field" style="margin-top:10px;padding-top:10px;border-top:1px solid rgba(255,255,255,0.06);">
                                <a href="#" class="cz-template-override-toggle" style="font-size:12px;color:#a78bfa;text-decoration:none;"><?php esc_html_e( 'Template Override From Child Theme ?', 'customize-my-account-for-woocommerce' ); ?></a>
                                <div class="cz-template-override-info" style="display:none;margin-top:8px;">
                                    <p style="font-size:11px;color:#6b6b85;line-height:1.6;"><?php esc_html_e( 'Copy the template from:', 'customize-my-account-for-woocommerce' ); ?></p>
                                    <code style="display:block;background:#0d0d1a;border:1px solid #2d2d3f;border-radius:5px;padding:6px 8px;font-size:10px;color:#a78bfa;word-break:break-all;margin:4px 0;"><?php echo esc_html( wcmamtx_PLUGIN_URL . 'templates/myaccount/form-login.php' ); ?></code>
                                    <p style="font-size:11px;color:#6b6b85;line-height:1.6;margin-top:4px;"><?php esc_html_e( 'Into your child theme at:', 'customize-my-account-for-woocommerce' ); ?></p>
                                    <code style="display:block;background:#0d0d1a;border:1px solid #2d2d3f;border-radius:5px;padding:6px 8px;font-size:10px;color:#a78bfa;word-break:break-all;margin-top:4px;"><?php echo esc_html( get_stylesheet_directory() . '/wcmamtx_template/form-login.php' ); ?></code>
                                </div>
                            </div>
                            <!-- Login Page Designer -->
                            <div class="cz-field" style="margin-top:14px;padding-top:14px;border-top:1px solid rgba(255,255,255,0.06);">
                                <button type="button" id="cz-login-page-design-btn" style="display:flex;align-items:center;gap:8px;width:100%;padding:10px 14px;background:linear-gradient(135deg,<?php echo esc_attr( $login_page_gradient_start ); ?> 0%,<?php echo esc_attr( $login_page_gradient_end ); ?> 100%);border:none;border-radius:8px;color:#fff;font-size:12px;font-weight:600;cursor:pointer;letter-spacing:.3px;text-align:left;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                    <?php esc_html_e( 'Customize Login Page Design', 'customize-my-account-for-woocommerce' ); ?>
                                    <svg id="cz-login-design-chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="margin-left:auto;transition:transform .2s;flex-shrink:0;"><path d="M6 9l6 6 6-6"/></svg>
                                </button>
                            </div>
                            <div id="cz-login-designer" data-ajax="<?php echo esc_url( $ajax_url ); ?>" data-nonce="<?php echo esc_attr( $nonce_layout ); ?>" data-saving="<?php echo esc_attr( __( 'Saving', 'customize-my-account-for-woocommerce' ) ); ?>" data-saved="<?php echo esc_attr( __( 'Saved', 'customize-my-account-for-woocommerce' ) ); ?>" style="display:none;margin-top:2px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:8px;padding:14px;">
                                <div style="font-size:10px;color:#6b6b85;margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px;"><?php esc_html_e( 'Live Preview', 'customize-my-account-for-woocommerce' ); ?></div>
                                <div id="cz-lp-left-preview" style="background:linear-gradient(135deg,<?php echo esc_attr( $login_page_gradient_start ); ?> 0%,<?php echo esc_attr( $login_page_gradient_end ); ?> 100%);border-radius:8px;padding:18px 14px;display:flex;align-items:center;justify-content:center;min-height:110px;margin-bottom:14px;">
                                    <div style="text-align:center;max-width:100%;">
                                        <div id="cz-lp-preview-headline" style="font-size:13px;font-weight:700;color:#fff;margin-bottom:6px;line-height:1.4;word-break:break-word;"><?php echo esc_html( $login_page_headline ?: __( 'Everything you need, in one place.', 'customize-my-account-for-woocommerce' ) ); ?></div>
                                        <div id="cz-lp-preview-subtitle" style="font-size:9px;color:rgba(255,255,255,.8);margin-bottom:8px;line-height:1.5;word-break:break-word;"><?php echo esc_html( $login_page_subtitle ?: __( 'Track orders, manage addresses, download purchases — your account is your command centre.', 'customize-my-account-for-woocommerce' ) ); ?></div>
                                        <div id="cz-lp-preview-badge" style="display:inline-flex;align-items:center;gap:4px;background:<?php echo esc_attr( $login_page_badge_bg ?: 'rgba(255,255,255,.15)' ); ?>;border:1px solid rgba(255,255,255,.25);border-radius:50px;padding:3px 9px;font-size:8px;color:#fff;">
                                            <svg width="8" height="8" viewBox="0 0 24 24" fill="white"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                            <span id="cz-lp-preview-badge-text"><?php echo esc_html( $login_page_badge_text ?: __( 'Trusted by thousands of shoppers', 'customize-my-account-for-woocommerce' ) ); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="cz-field">
                                    <label class="cz-label"><?php esc_html_e( 'Headline', 'customize-my-account-for-woocommerce' ); ?></label>
                                    <input type="text" class="cz-lp-field" data-key="login_page_headline" data-preview="cz-lp-preview-headline" data-default="<?php echo esc_attr( __( 'Everything you need, in one place.', 'customize-my-account-for-woocommerce' ) ); ?>" value="<?php echo esc_attr( $login_page_headline ); ?>" placeholder="<?php esc_attr_e( 'Everything you need, in one place.', 'customize-my-account-for-woocommerce' ); ?>" style="width:100%;box-sizing:border-box;padding:7px 10px;background:#0d0d1a;border:1px solid #2d2d3f;border-radius:6px;color:#fff;font-size:12px;outline:none;">
                                </div>
                                <div class="cz-field">
                                    <label class="cz-label"><?php esc_html_e( 'Subtitle', 'customize-my-account-for-woocommerce' ); ?></label>
                                    <input type="text" class="cz-lp-field" data-key="login_page_subtitle" data-preview="cz-lp-preview-subtitle" data-default="<?php echo esc_attr( __( 'Track orders, manage addresses, download purchases — your account is your command centre.', 'customize-my-account-for-woocommerce' ) ); ?>" value="<?php echo esc_attr( $login_page_subtitle ); ?>" placeholder="<?php esc_attr_e( 'Track orders, manage addresses...', 'customize-my-account-for-woocommerce' ); ?>" style="width:100%;box-sizing:border-box;padding:7px 10px;background:#0d0d1a;border:1px solid #2d2d3f;border-radius:6px;color:#fff;font-size:12px;outline:none;">
                                </div>
                                <div class="cz-field">
                                    <label class="cz-label"><?php esc_html_e( 'Badge Text', 'customize-my-account-for-woocommerce' ); ?></label>
                                    <input type="text" class="cz-lp-field" data-key="login_page_badge_text" data-badge-preview="cz-lp-preview-badge-text" data-default="<?php echo esc_attr( __( 'Trusted by thousands of shoppers', 'customize-my-account-for-woocommerce' ) ); ?>" value="<?php echo esc_attr( $login_page_badge_text ); ?>" placeholder="<?php esc_attr_e( 'Trusted by thousands of shoppers', 'customize-my-account-for-woocommerce' ); ?>" style="width:100%;box-sizing:border-box;padding:7px 10px;background:#0d0d1a;border:1px solid #2d2d3f;border-radius:6px;color:#fff;font-size:12px;outline:none;">
                                </div>
                                <div class="cz-field">
                                    <label class="cz-label"><?php esc_html_e( 'Panel Gradient', 'customize-my-account-for-woocommerce' ); ?></label>
                                    <div style="display:flex;gap:10px;align-items:flex-end;">
                                        <div style="flex:1;">
                                            <label style="font-size:10px;color:#6b6b85;display:block;margin-bottom:4px;"><?php esc_html_e( 'From', 'customize-my-account-for-woocommerce' ); ?></label>
                                            <input type="color" class="cz-lp-color" data-key="login_page_gradient_start" value="<?php echo esc_attr( $login_page_gradient_start ); ?>" style="width:100%;height:34px;border:1px solid #2d2d3f;border-radius:6px;padding:2px 4px;background:#0d0d1a;cursor:pointer;">
                                        </div>
                                        <div style="flex:1;">
                                            <label style="font-size:10px;color:#6b6b85;display:block;margin-bottom:4px;"><?php esc_html_e( 'To', 'customize-my-account-for-woocommerce' ); ?></label>
                                            <input type="color" class="cz-lp-color" data-key="login_page_gradient_end" value="<?php echo esc_attr( $login_page_gradient_end ); ?>" style="width:100%;height:34px;border:1px solid #2d2d3f;border-radius:6px;padding:2px 4px;background:#0d0d1a;cursor:pointer;">
                                        </div>
                                    </div>
                                </div>
                                <!-- Background Image -->
                                <div class="cz-field" style="border-top:1px solid rgba(255,255,255,.06);margin-top:12px;padding-top:12px;">
                                    <label class="cz-label"><?php esc_html_e( 'Background Image', 'customize-my-account-for-woocommerce' ); ?></label>
                                    <div id="cz-lp-bg-thumb" style="display:<?php echo $login_page_bg_image ? '' : 'none'; ?>;border-radius:6px;overflow:hidden;height:64px;margin-bottom:8px;<?php if ( $login_page_bg_image ) { $_bsize = ( ! $login_page_bg_size || $login_page_bg_size === 'cover' ) ? 'cover' : esc_attr( $login_page_bg_size ) . '%'; echo 'background:url(' . esc_url( $login_page_bg_image ) . ') center/' . $_bsize . ' no-repeat #1a1a2e;'; } ?>position:relative;">
                                        <button type="button" id="cz-lp-bg-remove" style="position:absolute;top:4px;right:4px;width:20px;height:20px;background:rgba(0,0,0,.65);border:none;border-radius:50%;cursor:pointer;color:#fff;font-size:14px;line-height:1;display:flex;align-items:center;justify-content:center;">&times;</button>
                                    </div>
                                    <label id="cz-lp-bg-upload-btn" style="display:flex;align-items:center;justify-content:center;gap:6px;padding:8px;background:#0d0d1a;border:1px dashed rgba(167,139,250,.4);border-radius:6px;cursor:pointer;font-size:11px;color:#a78bfa;" data-uploading="<?php echo esc_attr( __( 'Uploading…', 'customize-my-account-for-woocommerce' ) ); ?>" data-choose="<?php echo esc_attr( __( 'Choose Image', 'customize-my-account-for-woocommerce' ) ); ?>">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                        <span id="cz-lp-bg-upload-label"><?php esc_html_e( 'Choose Image', 'customize-my-account-for-woocommerce' ); ?></span>
                                        <input type="file" id="cz-lp-bg-file" accept="image/jpeg,image/png,image/gif,image/webp" style="display:none;">
                                    </label>
                                    <div id="cz-lp-bg-size-wrap" style="display:<?php echo $login_page_bg_image ? '' : 'none'; ?>;margin-top:10px;">
                                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                                            <label style="font-size:10px;color:#6b6b85;"><?php esc_html_e( 'Image Size', 'customize-my-account-for-woocommerce' ); ?></label>
                                            <span id="cz-lp-bg-size-label" style="font-size:10px;color:#a78bfa;font-weight:600;"><?php echo ( ! $login_page_bg_size || $login_page_bg_size === 'cover' ) ? esc_html__( 'Fill', 'customize-my-account-for-woocommerce' ) : esc_html( $login_page_bg_size ) . '%'; ?></span>
                                        </div>
                                        <input type="range" id="cz-lp-bg-size-range" min="0" max="200" step="10" value="<?php echo ( ! $login_page_bg_size || $login_page_bg_size === 'cover' ) ? '0' : esc_attr( $login_page_bg_size ); ?>" style="width:100%;accent-color:#a78bfa;cursor:pointer;">
                                        <div style="display:flex;justify-content:space-between;font-size:9px;color:#6b6b85;margin-top:3px;">
                                            <span><?php esc_html_e( 'Fill', 'customize-my-account-for-woocommerce' ); ?></span>
                                            <span>200%</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Text Colour -->
                                <div class="cz-field" style="border-top:1px solid rgba(255,255,255,.06);margin-top:12px;padding-top:12px;">
                                    <label class="cz-label"><?php esc_html_e( 'Text Colour', 'customize-my-account-for-woocommerce' ); ?></label>
                                    <input type="color" id="cz-lp-text-color" data-key="login_page_text_color" value="<?php echo esc_attr( $login_page_text_color ?: '#ffffff' ); ?>" style="width:100%;height:34px;border:1px solid #2d2d3f;border-radius:6px;padding:2px 4px;background:#0d0d1a;cursor:pointer;">
                                </div>
                                <!-- Badge Button Background -->
                                <div class="cz-field" style="margin-top:10px;">
                                    <label class="cz-label"><?php esc_html_e( 'Badge Background', 'customize-my-account-for-woocommerce' ); ?></label>
                                    <input type="color" id="cz-lp-badge-bg" data-key="login_page_badge_bg" value="<?php echo esc_attr( $login_page_badge_bg ?: '#ffffff29' ); ?>" style="width:100%;height:34px;border:1px solid #2d2d3f;border-radius:6px;padding:2px 4px;background:#0d0d1a;cursor:pointer;">
                                </div>
                                <div style="text-align:right;margin-top:8px;">
                                    <button type="button" id="cz-lp-reset" style="font-size:10px;color:#6b6b85;background:none;border:none;cursor:pointer;padding:2px 0;text-decoration:underline;"><?php esc_html_e( 'Reset to defaults', 'customize-my-account-for-woocommerce' ); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /accordion loginregister -->

                <!-- OTHER TOOLS -->
                <div class="cz-accordion" data-accordion="othertools">
                    <div class="cz-accordion-header" data-target="othertools">
                        <span class="cz-accordion-title"><span class="dashicons dashicons-admin-tools"></span><?php esc_html_e('Other Tools','customize-my-account-for-woocommerce'); ?></span>
                        <span class="cz-accordion-chevron">&#9660;</span>
                    </div>
                    <div class="cz-accordion-body" id="cz-acc-othertools">
                        <div class="cz-group">
                            <div class="cz-group-title"><?php esc_html_e('Shipment Tracking','customize-my-account-for-woocommerce'); ?></div>
                            <div class="cz-field">
                                <label class="cz-label"><?php esc_html_e('Shipment Tracking','customize-my-account-for-woocommerce'); ?></label>
                                <div class="cz-toggle-group">
                                    <button class="cz-toggle <?php echo esc_attr($tracking_on?'active':''); ?>" data-key="shipment_tracking_override" data-value="01"><?php esc_html_e('Enable','customize-my-account-for-woocommerce'); ?></button>
                                    <button class="cz-toggle <?php echo esc_attr(!$tracking_on?'active':''); ?>" data-key="shipment_tracking_override" data-value="02"><?php esc_html_e('Disable','customize-my-account-for-woocommerce'); ?></button>
                                </div>
                            </div>
                            <div id="cz-tracking-info" style="<?php echo $tracking_on ? ''  : 'display:none;'; ?>">
                                <div class="cz-tracking-card does">
                                    <p><?php esc_html_e('Features','customize-my-account-for-woocommerce'); ?></p>
                                    <ul>
                                        <li><span class="dashicons dashicons-yes-alt cz-ti"></span><?php esc_html_e('Courier name &amp; tracking URL fields on order edit page.','customize-my-account-for-woocommerce'); ?></li>
                                        <li><span class="dashicons dashicons-yes-alt cz-ti"></span><?php esc_html_e('Tracking column in WooCommerce orders list.','customize-my-account-for-woocommerce'); ?></li>
                                        <li><span class="dashicons dashicons-yes-alt cz-ti"></span><?php esc_html_e('Clickable tracking link shown to customers on orders &amp; view order page.','customize-my-account-for-woocommerce'); ?></li>
                                        <li><span class="dashicons dashicons-yes-alt cz-ti"></span><?php esc_html_e('Quick Add Tracking AJAX button when no info saved yet.','customize-my-account-for-woocommerce'); ?></li>
                                    </ul>
                                </div>
                                <div class="cz-tracking-card doesnt">
                                    <p><?php esc_html_e('Limitations','customize-my-account-for-woocommerce'); ?></p>
                                    <ul>
                                        <li><span class="dashicons dashicons-no-alt cz-ti"></span><?php esc_html_e('No API/carrier integration — manual entry only.','customize-my-account-for-woocommerce'); ?></li>
                                        <li><span class="dashicons dashicons-no-alt cz-ti"></span><?php esc_html_e('Cannot detect live delivery status.','customize-my-account-for-woocommerce'); ?></li>
                                        <li><span class="dashicons dashicons-no-alt cz-ti"></span><?php esc_html_e('Does not auto-email customers when tracking is added.','customize-my-account-for-woocommerce'); ?></li>
                                    </ul>
                                </div>
                                <a href="<?php echo esc_url( admin_url('admin.php?page=wc-orders') ); ?>" class="cz-tracking-link"><span class="dashicons dashicons-cart" style="font-size:13px;width:13px;height:13px;line-height:1;"></span><?php esc_html_e('Add tracking info to your orders','customize-my-account-for-woocommerce'); ?></a>
                            </div>
                        </div>
                        <div class="cz-group cz-pro-locked-group" style="cursor:pointer;" onclick="openModal('cz-defaulttab-modal-overlay')">
                            <div class="cz-group-title" style="display:flex;align-items:center;justify-content:space-between;">
                                <span><?php esc_html_e('Change Default My Account Tab','customize-my-account-for-woocommerce'); ?> <span class="cz-pro-badge">&#128274;</span></span>
                                <span style="font-size:11px;color:#a78bfa;font-weight:700;">Unlock &#9656;</span>
                            </div>
                        </div>

                        <?php do_action( 'wcmamtx_customizer_section_othertools' ); // Add options to Other Tools accordion ?>
                    </div><!-- /accordion othertools -->
                </div><!-- /accordion othertools wrapper -->
                <?php do_action( 'wcmamtx_customizer_after_all_sections' ); ?>
        </div><!-- /panel-content -->

    <!-- PANEL FOOTER -->
    <div id="wcmcz-panel-footer">
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wcmamtx_advanced_settings&tab=wcmamtx_layout' ) ); ?>" class="cz-footer-btn cz-footer-btn-outline">
            <span class="dashicons dashicons-admin-settings" style="font-size:14px;width:14px;height:14px;line-height:1;"></span>
            <?php esc_html_e( 'All Settings', 'customize-my-account-for-woocommerce' ); ?>
        </a>
        <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="cz-footer-btn cz-footer-btn-purple">
            <span class="dashicons dashicons-external" style="font-size:14px;width:14px;height:14px;line-height:1;"></span>
            <?php esc_html_e( 'View Page', 'customize-my-account-for-woocommerce' ); ?>
        </a>
    </div>    </div><!-- /panel -->

    <!-- PREVIEW -->
    <div id="wcmcz-preview-wrap">
        <div id="wcmcz-preview-bar">
            <div class="cz-dots">
                <span class="dot-red"></span><span class="dot-yellow"></span><span class="dot-green"></span>
            </div>
            <input type="text" id="wcmcz-preview-url" value="<?php echo esc_url($preview_url); ?>" readonly>
        </div>
        <div id="wcmcz-preview-container">
            <div id="wcmcz-loader"><div class="cz-spinner"></div></div>
            <iframe id="wcmcz-iframe" src="<?php echo esc_url($preview_url); ?>"></iframe>
        </div>
    </div>

</div><!-- /body -->
</div><!-- /customizer -->

<script src="<?php echo esc_url(includes_url('js/jquery/jquery.min.js')); ?>"></script>
<script src="<?php echo esc_url(includes_url('js/jquery/jquery-migrate.min.js')); ?>"></script>
<script src="<?php echo esc_url(wcmamtx_PLUGIN_URL.'assets/js/select2.js'); ?>"></script>

<script>
(function(){
    'use strict';
    var AJAX_URL = <?php echo wp_json_encode($ajax_url); ?>;
    var NONCE_LAYOUT  = <?php echo wp_json_encode($nonce_layout); ?>;
    var NONCE_AVATAR  = <?php echo wp_json_encode($nonce_avatar); ?>;
    var NONCE_MENUS   = <?php echo wp_json_encode($nonce_menus); ?>;
    var NONCE_FORMATS = <?php echo wp_json_encode($nonce_formats); ?>;
    var NONCE_IMG     = <?php echo wp_json_encode($nonce_img); ?>;
    var PLACEHOLDER_CAMERA  = <?php echo wp_json_encode(wcmamtx_PLUGIN_URL.'assets/images/camera.svg'); ?>;
    var PLACEHOLDER_AVATAR  = <?php echo wp_json_encode(wcmamtx_PLUGIN_URL.'assets/images/default_avatar.jpg'); ?>;
    var PREVIEW  = <?php echo wp_json_encode($preview_url); ?>;
    var TPL_PREVIEW_URLS = <?php echo wp_json_encode($tpl_preview_urls); ?>;
    var i18n = {
        saving:        <?php echo wp_json_encode(__('Saving',            'customize-my-account-for-woocommerce')); ?>,
        saved:         <?php echo wp_json_encode(__('Saved',            'customize-my-account-for-woocommerce')); ?>,
        published:     <?php echo wp_json_encode(__('Published!',        'customize-my-account-for-woocommerce')); ?>,
        errorSaving:   <?php echo wp_json_encode(__('Error saving',            'customize-my-account-for-woocommerce')); ?>,
        networkError:  <?php echo wp_json_encode(__('Network error',           'customize-my-account-for-woocommerce')); ?>,
        selectFormats: <?php echo wp_json_encode(__('Select formats',          'customize-my-account-for-woocommerce')); ?>,
    };
    <?php $_czu = wp_get_current_user(); ?>
    var CZ_SMART_TAGS = <?php echo wp_json_encode([
        'display_name'     => $_czu->display_name,
        'first_name'       => $_czu->user_firstname,
        'last_name'        => $_czu->user_lastname,
        'username'         => $_czu->user_login,
        'user_email'       => $_czu->user_email,
        'user_id'          => (string) get_current_user_id(),
        'site_name'        => get_option( 'blogname' ),
        'site_url'         => get_option( 'siteurl' ),
        'admin_email'      => get_option( 'admin_email' ),
        'current_date'     => date_i18n( get_option( 'date_format' ) ),
        'current_time'     => date_i18n( get_option( 'time_format' ) ),
        'user_logout_link' => function_exists( 'wc_logout_url' ) ? wc_logout_url() : wp_logout_url(),
    ]); ?>;
    var CZ_AC_DEFAULT = <?php echo wp_json_encode( '<p class="wcmamtx_default_text_below_avatar" style="text-align:center;">Hello <strong>{display_name}</strong> (not <strong>{display_name}</strong>? <a href="{user_logout_link}">Log out</a>)</p>' ); ?>;
    var iframe   = document.getElementById('wcmcz-iframe');
    var loader   = document.getElementById('wcmcz-loader');
    var status   = document.getElementById('cz-save-status');
    var saveTimer;

    function setSaveStatus(state, text) {
        status.className = state; status.textContent = text;
        clearTimeout(saveTimer);
        if (state === 'saved') saveTimer = setTimeout(function(){ status.textContent=''; status.className=''; }, 3000);
    }
    function showLoader(){ loader.classList.add('visible'); }
    function hideLoader(){ loader.classList.remove('visible'); }
    function injectIframeCSS() {
      try {
        var iDoc = iframe.contentDocument || iframe.contentWindow.document;
        if (!iDoc) return;
        var existing = iDoc.getElementById('wcmcz-injected-css');
        if (existing) existing.parentNode.removeChild(existing);
        var s = iDoc.createElement('style');
        s.id = 'wcmcz-injected-css';
        s.textContent = '#wpadminbar{display:none!important;}html{margin-top:0!important;}body{margin-top:0!important;}';
        (iDoc.head || iDoc.documentElement).appendChild(s);
      } catch(e) {}
    }
    iframe.addEventListener('load', function(){ hideLoader(); injectIframeCSS(); });

    function saveOption(key, value, reload) {
        setSaveStatus('saving', i18n.saving);
        var fd = new FormData();
        fd.append('action','wcmamtx_customizer_save');
        fd.append('nonce', NONCE_LAYOUT);
        fd.append('key',   key);
        fd.append('value', value);
        fetch(AJAX_URL, {method:'POST',body:fd})
            .then(function(r){ return r.json(); })
            .then(function(res){
                if (res.success) {
                    setSaveStatus('saved', i18n.saved);
                    if (reload){ showLoader(); iframe.src = PREVIEW+'?wcmcz='+Date.now(); }
                } else { setSaveStatus('', i18n.errorSaving); }
            })
            .catch(function(){ setSaveStatus('', i18n.networkError); });
    }

    // Accordion
    document.querySelectorAll('.cz-accordion-header').forEach(function(header){
        header.addEventListener('click', function(){
            var target = header.dataset.target;
            var body   = document.getElementById('cz-acc-'+target);
            var isOpen = body.classList.contains('open');
            document.querySelectorAll('.cz-accordion-header').forEach(function(h){ h.classList.remove('active'); });
            document.querySelectorAll('.cz-accordion-body').forEach(function(b){ b.classList.remove('open'); });
            if (!isOpen){
                header.classList.add('active');
                body.classList.add('open');
                // Init Select2 for link box menus accordion
                if (target === 'linkboxmenus') {
                    setTimeout(window.initLinkBoxSelect2lect2, 50);
                }
    // Re-init Select2 + TinyMCE when avatar accordion opens (needs visible element)
                if (target === 'avatar') {
                    setTimeout(function(){
                        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
                            var $sel = jQuery('#cz-avatar-formats');
                            if (!$sel.hasClass('select2-hidden-accessible')) {
                                $sel.select2({ placeholder: i18n.selectFormats, width: '100%', dropdownParent: jQuery('#cz-acc-avatar') });
                                $sel.on('change', saveAvatarFormats);
                            }
                        }
                                    }, 50);
                }
            }
        });
    });

    // Template toggles — show/hide style row + update preview URL
    function getTplPreviewUrl(group) {
        var tplKey = group ? group.dataset.tpl : null;
        return (tplKey && TPL_PREVIEW_URLS[tplKey]) ? TPL_PREVIEW_URLS[tplKey] : PREVIEW;
    }
    document.querySelectorAll('.cz-tpl-override, .cz-tpl-style').forEach(function(btn){
        btn.addEventListener('click', function(){
            var group = btn.closest('.cz-group');
            // Show/hide style row for source toggles
            if (btn.classList.contains('cz-tpl-override')) {
                var wrap = group.querySelector('.cz-tpl-style-wrap');
                if (wrap) wrap.style.display = btn.dataset.value === '01' ? '' : 'none';
            }
            // Save first, then reload preview with template-specific URL
            var previewUrl = getTplPreviewUrl(group);
            setSaveStatus('saving', i18n.saving);
            var fd = new FormData();
            fd.append('action', 'wcmamtx_customizer_save');
            fd.append('nonce', NONCE_LAYOUT);
            fd.append('key', btn.dataset.key);
            fd.append('value', btn.dataset.value);
            fetch(AJAX_URL, { method: 'POST', body: fd })
                .then(function(r) { return r.json(); })
                .then(function(res) {
                    if (res.success) {
                        setSaveStatus('saved', i18n.saved);
                        document.getElementById('wcmcz-preview-url').value = previewUrl;
                        showLoader();
                        iframe.src = previewUrl + '?wcmcz=' + Date.now();
                    } else {
                        setSaveStatus('', i18n.errorSaving);
                    }
                })
                .catch(function() { setSaveStatus('', i18n.networkError); });
        });
    });

    // Toggle buttons
    document.querySelectorAll('.cz-toggle').forEach(function(btn){
        btn.addEventListener('click', function(){
            btn.closest('.cz-toggle-group').querySelectorAll('.cz-toggle').forEach(function(b){ b.classList.remove('active'); });
            btn.classList.add('active');
            // Show/hide navwidget subopts
            // Show/hide Shipment Tracking info panel
            if (btn.dataset.key === 'shipment_tracking_override') {
                var trackInfo = document.getElementById('cz-tracking-info');
                if (trackInfo) trackInfo.style.display = btn.dataset.value === '01' ? '' : 'none';
            }
            // Show/hide React Based notice
            if (btn.dataset.key === 'nav_style') {
                var reactNotice = document.getElementById('cz-react-nav-notice');
                if (reactNotice) reactNotice.style.display = btn.dataset.value === '04' ? 'block' : 'none';
            }
            if (btn.dataset.key === 'navigationwidget_layout_override') {
                var sub = document.getElementById('cz-navwidget-subopts');
                if (sub) sub.style.display = btn.dataset.value === '01' ? '' : 'none';
                // Save nav_header_widget first, then save override key with reload
                var navVal = btn.dataset.value === '01' ? 'yes' : 'no';
                var navKey = btn.dataset.key;
                var navData = btn.dataset.value;
                var fd1 = new FormData();
                fd1.append('action','wcmamtx_customizer_save'); fd1.append('nonce',NONCE_LAYOUT);
                fd1.append('key','nav_header_widget'); fd1.append('value',navVal);
                fetch(AJAX_URL,{method:'POST',body:fd1})
                    .then(function(){
                        saveOption(navKey, navData, true);
                    });
                return;
            }
            // Template toggles handle their own preview URL - just save, no generic reload
            if (btn.classList.contains('cz-tpl-override') || btn.classList.contains('cz-tpl-style')) {
                saveOption(btn.dataset.key, btn.dataset.value, false);
            } else {
                saveOption(btn.dataset.key, btn.dataset.value, true);
            }
        });
    });

    // Selects
    document.querySelectorAll('.cz-select').forEach(function(sel){
        sel.addEventListener('change', function(){ saveOption(sel.dataset.key, sel.value, true); });
    });

    // Text inputs
    document.querySelectorAll('.cz-text-input').forEach(function(inp){
        inp.addEventListener('blur', function(){ saveOption(inp.dataset.key, inp.value, true); });
        inp.addEventListener('keydown', function(e){
            if (e.key === 'Enter'){ e.preventDefault(); saveOption(inp.dataset.key, inp.value, true); }
        });
    });

    // Refresh
    document.getElementById('cz-refresh-btn').addEventListener('click', function(){
        showLoader(); iframe.src = PREVIEW+'?wcmcz='+Date.now();
    });

    // Publish
    var publishBtn = document.getElementById('cz-publish-btn');
    if (publishBtn) publishBtn.addEventListener('click', function(){
        var saves = [];
        document.querySelectorAll('.cz-toggle.active[data-key]').forEach(function(b){ saves.push({key:b.dataset.key,value:b.dataset.value}); });
        document.querySelectorAll('.cz-select[data-key]').forEach(function(s){ saves.push({key:s.dataset.key,value:s.value}); });
        document.querySelectorAll('.cz-text-input[data-key]').forEach(function(i){ saves.push({key:i.dataset.key,value:i.value}); });
        var chain = Promise.resolve();
        saves.forEach(function(s){
            chain = chain.then(function(){
                var fd=new FormData();
                fd.append('action','wcmamtx_customizer_save');
                fd.append('nonce',NONCE_LAYOUT); fd.append('key',s.key); fd.append('value',s.value);
                return fetch(AJAX_URL,{method:'POST',body:fd});
            });
        });
        chain.then(function(){ setSaveStatus('saved', i18n.published); showLoader(); iframe.src=PREVIEW+'?wcmcz='+Date.now(); });
    });


    // ---- AVATAR: separate option store ----
    function saveAvatarOption(key, value) {
        setSaveStatus('saving', i18n.saving);
        var fd = new FormData();
        fd.append('action','wcmamtx_customizer_save_avatar');
        fd.append('nonce', NONCE_AVATAR);
        fd.append('key',   key);
        fd.append('value', value);
        fetch(AJAX_URL,{method:'POST',body:fd})
            .then(function(r){return r.json();})
            .then(function(res){
                setSaveStatus(res.success?'saved':'', i18n.saved);
                if(res.success){ showLoader(); iframe.src=PREVIEW+'?wcmcz='+Date.now(); }
            })
            .catch(function(){ setSaveStatus('', i18n.networkError); });
    }

    function saveAvatarFormats() {
        var sel = document.getElementById('cz-avatar-formats');
        if (!sel) return;
        var formats = Array.from(sel.selectedOptions).map(function(o){return o.value;});
        setSaveStatus('saving', i18n.saving);
        var fd = new FormData();
        fd.append('action','wcmamtx_customizer_save_avatar_formats');
        fd.append('nonce',NONCE_FORMATS);
        formats.forEach(function(f){ fd.append('formats[]',f); });
        fetch(AJAX_URL,{method:'POST',body:fd})
            .then(function(r){return r.json();})
            .then(function(res){ setSaveStatus(res.success?'saved':'', res.success ? i18n.saved : i18n.errorSaving); });
    }

    // Avatar toggles
    document.querySelectorAll('.cz-avatar-toggle').forEach(function(btn){
        btn.addEventListener('click',function(){
            btn.closest('.cz-toggle-group').querySelectorAll('.cz-toggle').forEach(function(b){b.classList.remove('active');});
            btn.classList.add('active');
            var opt = btn.dataset.opt, val = btn.dataset.value;
            // Show/hide sub-sections
            if (opt === 'disable_avatar') {
                var sub = document.getElementById('cz-avatar-subopts');
                if (sub) sub.style.display = val==='yes' ? '' : 'none';
            }
            if (opt === 'override_texts') {
                var tx = document.getElementById('cz-avatar-texts');
                if (tx) tx.style.display = val==='yes' ? '' : 'none';
            }
            if (opt === 'custom_avatar_content') {
                var cc = document.getElementById('cz-avatar-content');
                if (cc) cc.style.display = val==='yes' ? '' : 'none';
            }
            if (opt === 'disable_gravtar') {
                var dw = document.getElementById('cz-avatar-default-wrap');
                if (dw) dw.style.display = val==='yes' ? '' : 'none';
            }
            saveAvatarOption(opt, val);
        });
    });

    // Avatar number inputs — save on change (slider release / number blur)
    document.querySelectorAll('.cz-avatar-number').forEach(function(inp){
        inp.addEventListener('change',function(){ saveAvatarOption(inp.dataset.opt, inp.value); });
    });

    // Avatar size slider — live preview while dragging (no save; change event handles save)
    (function(){
        var sizeSlider = document.querySelector('.cz-avatar-slider[data-opt="avatar_size"]');
        if (!sizeSlider) return;
        sizeSlider.addEventListener('input', function(){
            var px = parseInt(this.value, 10);
            if (!iframe) return;
            try {
                var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                var s = iframeDoc.getElementById('cz-live-avatar-size');
                if (!s) {
                    s = iframeDoc.createElement('style');
                    s.id = 'cz-live-avatar-size';
                    iframeDoc.head.appendChild(s);
                }
                s.textContent = '.wcmamtx_avatar_wrap img{width:' + px + 'px!important;height:' + px + 'px!important;}';
            } catch(e) {}
        });
    }());

    // Avatar text inputs
    document.querySelectorAll('.cz-avatar-text').forEach(function(inp){
        inp.addEventListener('blur',function(){ saveAvatarOption(inp.dataset.opt, inp.value); });
        inp.addEventListener('keydown',function(e){
            if(e.key==='Enter'){e.preventDefault(); saveAvatarOption(inp.dataset.opt, inp.value);}
        });
    });

    // ---- AVATAR CONTENT WYSIWYG EDITOR ----
    (function() {
        var editor = document.getElementById('cz-ac-editor');
        if (!editor) return;

        // Inject editor CSS
        var st = document.createElement('style');
        st.textContent =
            '#cz-ac-editor a{color:#a78bfa;text-decoration:underline;}' +
            '#cz-ac-editor .cz-ac-tag{display:inline-block;background:#2d1f5e;color:#c4b5fd;border-radius:3px;padding:0 5px;font-size:11px;white-space:nowrap;cursor:default;user-select:none;-webkit-user-select:none;}' +
            '.cz-ac-fmt:hover,.cz-ac-tag-btn:hover{opacity:.75;}';
        document.head.appendChild(st);

        var KNOWN = ['display_name','first_name','last_name','username','user_email','user_id',
                     'user_logout_link','site_name','site_url','admin_email','current_date','current_time'];
        var TAG_RE = new RegExp('\\{(' + KNOWN.join('|') + ')\\}', 'g');

        function makeTagSpan(tag) {
            var s = document.createElement('span');
            s.className = 'cz-ac-tag';
            s.contentEditable = 'false';
            s.dataset.tag = tag;
            s.textContent = '{' + tag + '}';
            return s;
        }

        function convertTagsInNode(node) {
            if (node.nodeType === 3) {
                TAG_RE.lastIndex = 0;
                if (!TAG_RE.test(node.nodeValue)) return;
                TAG_RE.lastIndex = 0;
                var frag = document.createDocumentFragment(), last = 0, m;
                while ((m = TAG_RE.exec(node.nodeValue)) !== null) {
                    if (m.index > last) frag.appendChild(document.createTextNode(node.nodeValue.slice(last, m.index)));
                    frag.appendChild(makeTagSpan(m[1]));
                    last = m.index + m[0].length;
                }
                if (last < node.nodeValue.length) frag.appendChild(document.createTextNode(node.nodeValue.slice(last)));
                node.parentNode.replaceChild(frag, node);
            } else if (node.nodeType === 1 && !node.classList.contains('cz-ac-tag')) {
                Array.from(node.childNodes).forEach(convertTagsInNode);
            }
        }

        function getContent() {
            var clone = editor.cloneNode(true);
            clone.querySelectorAll('.cz-ac-tag').forEach(function(s) {
                s.parentNode.replaceChild(document.createTextNode('{' + s.dataset.tag + '}'), s);
            });
            return clone.innerHTML;
        }

        function livePreview(html) {
            var resolved = html.replace(/\{(\w+)\}/g, function(_, tag) {
                return Object.prototype.hasOwnProperty.call(CZ_SMART_TAGS, tag) ? CZ_SMART_TAGS[tag] : '{' + tag + '}';
            });
            try {
                var doc = iframe.contentDocument || iframe.contentWindow.document;
                var target = doc.getElementById('wcmamtx-avatar-content-output');
                if (target) target.innerHTML = resolved;
            } catch(e) {}
        }

        var acStatus = document.getElementById('cz-ac-status');
        var saveTimer;
        function saveContent() {
            var html = getContent();
            if (acStatus) acStatus.textContent = i18n.saving + '…';
            var fd = new FormData();
            fd.append('action',  'wcmamtx_customizer_save_avatar_content');
            fd.append('nonce',   NONCE_AVATAR);
            fd.append('content', html);
            fetch(AJAX_URL, {method:'POST', body:fd})
                .then(function(r){ return r.json(); })
                .then(function(res) {
                    if (acStatus) acStatus.textContent = res.success ? ('✓ ' + i18n.saved) : i18n.errorSaving;
                    if (res.success) { showLoader(); iframe.src = PREVIEW + '?wcmcz=' + Date.now(); }
                })
                .catch(function() { if (acStatus) acStatus.textContent = i18n.networkError; });
        }

        function triggerDebouncedSave() {
            clearTimeout(saveTimer);
            saveTimer = setTimeout(saveContent, 800);
        }

        convertTagsInNode(editor);

        editor.addEventListener('input', function() {
            livePreview(getContent());
            triggerDebouncedSave();
        });

        document.querySelectorAll('.cz-ac-fmt, #cz-ac-unlink, .cz-ac-tag-btn, #cz-ac-logout-link').forEach(function(btn) {
            btn.addEventListener('mousedown', function(e) { e.preventDefault(); });
        });

        document.querySelectorAll('.cz-ac-fmt').forEach(function(btn) {
            btn.addEventListener('click', function() {
                editor.focus();
                document.execCommand(btn.dataset.cmd, false, null);
                livePreview(getContent());
                triggerDebouncedSave();
            });
        });

        var savedRange = null;
        ['mouseup','keyup'].forEach(function(ev) {
            editor.addEventListener(ev, function() {
                var sel = window.getSelection();
                if (sel && sel.rangeCount) savedRange = sel.getRangeAt(0).cloneRange();
            });
        });

        document.getElementById('cz-ac-link').addEventListener('click', function() {
            var url = prompt('Enter link URL:');
            if (!url) return;
            editor.focus();
            if (savedRange) {
                var sel = window.getSelection();
                sel.removeAllRanges();
                sel.addRange(savedRange);
            }
            document.execCommand('createLink', false, url);
            livePreview(getContent());
            triggerDebouncedSave();
        });

        document.getElementById('cz-ac-unlink').addEventListener('click', function() {
            editor.focus();
            document.execCommand('unlink', false, null);
            livePreview(getContent());
            triggerDebouncedSave();
        });

        function insertAtCursor(node) {
            editor.focus();
            var sel = window.getSelection();
            if (sel && sel.rangeCount) {
                var range = sel.getRangeAt(0);
                range.deleteContents();
                range.insertNode(node);
                range.setStartAfter(node);
                range.collapse(true);
                sel.removeAllRanges();
                sel.addRange(range);
            } else {
                editor.appendChild(node);
            }
        }

        document.querySelectorAll('.cz-ac-tag-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                insertAtCursor(makeTagSpan(btn.dataset.tag));
                livePreview(getContent());
                triggerDebouncedSave();
            });
        });

        var logoutBtn = document.getElementById('cz-ac-logout-link');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function() {
                var a = document.createElement('a');
                a.href = '{user_logout_link}';
                a.textContent = 'Log out';
                insertAtCursor(a);
                livePreview(getContent());
                triggerDebouncedSave();
            });
        }

        var resetBtn = document.getElementById('cz-ac-reset');
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                if (!confirm('Reset content to the default text?')) return;
                editor.innerHTML = CZ_AC_DEFAULT;
                convertTagsInNode(editor);
                livePreview(getContent());
                clearTimeout(saveTimer);
                saveTimer = setTimeout(saveContent, 100);
            });
        }
    }());

    // Avatar image upload (camera icon + default avatar) via file input + fetch
    (function() {
        // Upload button → trigger hidden file input
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.cz-img-upload-btn');
            if (!btn) return;
            e.preventDefault();
            var target = btn.dataset.imgTarget;
            var inp = document.querySelector('.cz-img-file-input[data-img-target="' + target + '"]');
            if (inp) inp.click();
        });

        // File chosen → preview + upload
        document.addEventListener('change', function(e) {
            var inp = e.target.closest('.cz-img-file-input');
            if (!inp) return;
            var target = inp.dataset.imgTarget;
            var file = inp.files && inp.files[0];
            if (!file) return;

            // Instant local preview
            var reader = new FileReader();
            reader.onload = function(ev) {
                var row = document.querySelector('.cz-img-upload-row[data-img-target="' + target + '"]');
                if (row) row.querySelector('.cz-img-preview').src = ev.target.result;
            };
            reader.readAsDataURL(file);

            // Upload to WP media library + save ID
            setSaveStatus('saving', i18n.saving);
            var fd = new FormData();
            fd.append('action', 'wcmamtx_customizer_upload_avatar_img');
            fd.append('nonce',  NONCE_IMG);
            fd.append('key',    target);
            fd.append('file',   file);
            fetch(AJAX_URL, {method:'POST', body:fd})
                .then(function(r){return r.json();})
                .then(function(res){
                    if (res.success) {
                        setSaveStatus('saved', i18n.saved);
                        var row = document.querySelector('.cz-img-upload-row[data-img-target="' + target + '"]');
                        if (row) {
                            row.querySelector('.cz-img-preview').src = res.data.url;
                            var rm = row.querySelector('.cz-img-remove-btn');
                            if (rm) rm.style.display = '';
                        }
                        showLoader();
                        iframe.src = PREVIEW + '?wcmcz=' + Date.now();
                    } else {
                        setSaveStatus('', i18n.errorSaving);
                    }
                    inp.value = '';
                })
                .catch(function(){ setSaveStatus('', i18n.networkError); inp.value = ''; });
        });

        // Remove button → revert to placeholder + clear setting
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.cz-img-remove-btn');
            if (!btn) return;
            e.preventDefault();
            var target = btn.dataset.imgTarget;
            var placeholder = target === 'upload_icon' ? PLACEHOLDER_CAMERA : PLACEHOLDER_AVATAR;
            var row = document.querySelector('.cz-img-upload-row[data-img-target="' + target + '"]');
            if (row) {
                row.querySelector('.cz-img-preview').src = placeholder;
                btn.style.display = 'none';
            }
            setSaveStatus('saving', i18n.saving);
            var fd = new FormData();
            fd.append('action', 'wcmamtx_customizer_remove_avatar_img');
            fd.append('nonce',  NONCE_IMG);
            fd.append('key',    target);
            fetch(AJAX_URL, {method:'POST', body:fd})
                .then(function(r){return r.json();})
                .then(function(res){
                    setSaveStatus(res.success?'saved':'', res.success ? i18n.saved : i18n.errorSaving);
                    if (res.success) { showLoader(); iframe.src = PREVIEW + '?wcmcz=' + Date.now(); }
                })
                .catch(function(){ setSaveStatus('', i18n.networkError); });
        });
    })();

    // Allowed formats multiselect — init Select2 then listen on change
    function initSelect2Formats() {
        if (typeof jQuery === 'undefined' || typeof jQuery.fn.select2 === 'undefined') {
            setTimeout(initSelect2Formats, 100);
            return;
        }
        jQuery('#cz-avatar-formats').select2({
            placeholder: i18n.selectFormats,
            width: '100%',
            dropdownParent: jQuery('#cz-acc-avatar')
        });
        jQuery('#cz-avatar-formats').on('change', saveAvatarFormats);
    }
    initSelect2Formats();
    // Open first accordion on page load
    (function(){
        var firstHeader = document.querySelector('.cz-accordion-header');
        if (firstHeader) firstHeader.click();
    })();

    // Device buttons
    document.querySelectorAll('.cz-device-btns button').forEach(function(btn){
        btn.addEventListener('click', function(){
            document.querySelectorAll('.cz-device-btns button').forEach(function(b){ b.classList.remove('active'); });
            btn.classList.add('active');
            var c = document.getElementById('wcmcz-preview-container');
            var ifrm = document.getElementById('wcmcz-iframe');
            // Remove all device classes then add the new one
            c.classList.remove('mobile','tablet','desktop');
            if (btn.dataset.device !== 'desktop') c.classList.add(btn.dataset.device);
            // Reset iframe to full size for desktop, let CSS control others
            if (btn.dataset.device === 'desktop') {
                ifrm.style.width = '';
                ifrm.style.height = '';
            } else {
                ifrm.style.width = '';
                ifrm.style.height = '';
            }
        });
    });

    // ---- DASHBOARD PRIORITY DRAG & DROP ----
    (function() {
        var list = document.getElementById('cz-priority-list');
        if (!list) return;
        var dragging = null;

        function savePriorities() {
            var items = list.querySelectorAll('.cz-priority-item');
            var step = 10;
            var saves = [];
            items.forEach(function(item, index) {
                saves.push({ key: item.dataset.key, value: String((index + 1) * step) });
            });
            // Save sequentially using existing saveOption()
            var chain = Promise.resolve();
            saves.forEach(function(s) {
                chain = chain.then(function() {
                    return new Promise(function(resolve) {
                        setSaveStatus('saving', i18n.saving);
                        var fd = new FormData();
                        fd.append('action', 'wcmamtx_customizer_save');
                        fd.append('nonce', NONCE_LAYOUT);
                        fd.append('key', s.key);
                        fd.append('value', s.value);
                        fetch(AJAX_URL, { method: 'POST', body: fd })
                            .then(function(r) { return r.json(); })
                            .then(function() { resolve(); })
                            .catch(function() { resolve(); });
                    });
                });
            });
            chain.then(function() {
                setSaveStatus('saved', i18n.saved);
                showLoader();
                iframe.src = PREVIEW + '?wcmcz=' + Date.now();
            });
        }

        list.addEventListener('dragstart', function(e) {
            dragging = e.target.closest('.cz-priority-item');
            if (!dragging) return;
            dragging.classList.add('cz-dragging');
            e.dataTransfer.effectAllowed = 'move';
        });
        list.addEventListener('dragend', function() {
            if (dragging) dragging.classList.remove('cz-dragging');
            list.querySelectorAll('.cz-priority-item').forEach(function(i) { i.classList.remove('cz-drag-over'); });
            dragging = null;
            savePriorities();
        });
        list.addEventListener('dragover', function(e) {
            e.preventDefault();
            var target = e.target.closest('.cz-priority-item');
            if (!target || target === dragging) return;
            list.querySelectorAll('.cz-priority-item').forEach(function(i) { i.classList.remove('cz-drag-over'); });
            target.classList.add('cz-drag-over');
            var rect = target.getBoundingClientRect();
            var mid = rect.top + rect.height / 2;
            if (e.clientY < mid) {
                list.insertBefore(dragging, target);
            } else {
                list.insertBefore(dragging, target.nextSibling);
            }
        });

        // Make items draggable
        list.querySelectorAll('.cz-priority-item').forEach(function(item) {
            item.setAttribute('draggable', 'true');
        });

        // Checkbox: enable/disable widget
        list.querySelectorAll('.cz-widget-checkbox').forEach(function(cb) {
            cb.addEventListener('change', function(e) {
                e.stopPropagation();
                var li = cb.closest('.cz-priority-item');
                var toggleKey = li.dataset.toggleKey;
                var val = cb.checked ? '01' : '02';
                li.classList.toggle('cz-disabled', !cb.checked);
                saveOption(toggleKey, val, true);
                // Show/hide Link Box Menus accordion when Link Boxes toggled
                // Link Boxes is a Pro feature - keep accordion always hidden
                if (toggleKey === 'dashlink_box_override') {
                    var lbWrap = document.getElementById('cz-acc-wrap-linkboxmenus');
                    if (lbWrap) lbWrap.style.display = 'none';
                }
                // Show/hide Dashboard Links Style accordion when Dashboard Links toggled
                if (toggleKey === 'dashlink_layout_override') {
                    var dlWrap = document.getElementById('cz-acc-wrap-dashlinkstype');
                    if (dlWrap) dlWrap.style.display = cb.checked ? '' : 'none';
                }
                // Show\/hide Shortcode accordion when Shortcode widget toggled
                if (toggleKey === 'shortcode1_override') {
                    var sc1Wrap = document.getElementById('wcmamtx-sc-wrap-1');
                    if (sc1Wrap) sc1Wrap.style.display = cb.checked ? '' : 'none';
                }
                if (toggleKey === 'shortcode2_override') {
                    var sc2Wrap = document.getElementById('wcmamtx-sc-wrap-2');
                    if (sc2Wrap) sc2Wrap.style.display = cb.checked ? '' : 'none';
                }
            });
            cb.closest('.cz-widget-toggle').addEventListener('mousedown', function(e) {
                e.stopPropagation();
            });
            if (!cb.checked) cb.closest('.cz-priority-item').classList.add('cz-disabled');
        });

        // Select2 for link box menus
        window.initLinkBoxSelect2 = function(selectedVals) {
            if (typeof jQuery === 'undefined' || typeof jQuery.fn.select2 === 'undefined') {
                setTimeout(function(){ window.initLinkBoxSelect2(selectedVals); }, 100); return;
            }
            var $sel = jQuery('#cma_selected_menu_items');

            // Determine which values to select
            var toSelect;
            if (selectedVals !== undefined) {
                toSelect = selectedVals;
            } else if ($sel.data('cz-selected') !== undefined) {
                toSelect = $sel.data('cz-selected');
            } else {
                // First load: read from PHP-rendered selected attributes
                toSelect = [];
                $sel.find('option').each(function() {
                    if (this.defaultSelected) toSelect.push(jQuery(this).val());
                });
                $sel.data('cz-selected', toSelect);
            }

            // Destroy existing instance
            if ($sel.hasClass('select2-hidden-accessible')) {
                $sel.select2('destroy');
            }

            // Apply selections to underlying options
            $sel.find('option').each(function() {
                jQuery(this).prop('selected', toSelect.indexOf(jQuery(this).val()) !== -1);
            });

            // Init fresh Select2
            $sel.select2({
                placeholder: <?php echo wp_json_encode(__('Select menus','customize-my-account-for-woocommerce')); ?>,
                width: '100%',
                dropdownParent: jQuery('#cz-acc-linkboxmenus')
            });

            // Handle selection changes
            $sel.on('select2:select select2:unselect', function() {
                var selected = [];
                $sel.find('option:selected').each(function() {
                    selected.push(jQuery(this).val());
                });
                $sel.data('cz-selected', selected);

                // Save to DB
                setSaveStatus('saving', i18n.saving);
                var fd = new FormData();
                fd.append('action', 'wcmamtx_customizer_save_linkbox_menus');
                fd.append('nonce', NONCE_MENUS);
                selected.forEach(function(v) { fd.append('menus[]', v); });
                fetch(AJAX_URL, { method: 'POST', body: fd })
                    .then(function(r) { return r.json(); })
                    .then(function(res) {
                        setSaveStatus(res.success ? 'saved' : '', res.success ? i18n.saved : i18n.errorSaving);
                        if (res.success) { showLoader(); iframe.src = PREVIEW + '?wcmcz=' + Date.now(); }
                        // Reinit to reflect the change visually
                        window.initLinkBoxSelect2(selected);
                    })
                    .catch(function() { setSaveStatus('', i18n.networkError); });
            });
        }
    })();


    // ---- GUEST DASHBOARD ----
    (function(){
        var GUEST_NONCE = <?php echo wp_json_encode( wp_create_nonce( 'wcmamtx_guest_dashboard_nonce' ) ); ?>;

        function saveGuestOpt(key, val) {
            setSaveStatus('saving', i18n.saving);
            var fd = new FormData();
            fd.append('action', 'wcmamtx_customizer_save');
            fd.append('nonce',  NONCE_LAYOUT);
            fd.append('key',    key);
            fd.append('value',  val);
            fetch(AJAX_URL, {method:'POST', body:fd})
                .then(function(r){ return r.json(); })
                .then(function(res){ setSaveStatus(res.success ? 'saved' : '', i18n.saved); })
                .catch(function(){ setSaveStatus('', i18n.networkError); });
        }

        // Enable / Disable toggles
        document.querySelectorAll('[data-guest-toggle]').forEach(function(btn){
            btn.addEventListener('click', function(){
                document.querySelectorAll('[data-guest-toggle]').forEach(function(b){ b.classList.remove('active'); });
                btn.classList.add('active');
                var val = btn.dataset.guestToggle;
                var sub = document.getElementById('cz-guest-subopts');
                if (sub) sub.style.display = val === '01' ? '' : 'none';
                saveGuestOpt('guest_dashboard_enable', val);
            });
        });

        // Init Select2 + bind autolink button once accordion opens
        var guestReady = false;
        function initGuestPanel() {
            if (guestReady) return;
            guestReady = true;

            // Select2
            if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
                jQuery('#cz-guest-page-select').select2({
                    allowClear: true,
                    width: '100%',
                    dropdownParent: jQuery('#cz-acc-guestdashboard')
                });
                jQuery('#cz-guest-page-select').on('change', function(){
                    saveGuestOpt('guest_dashboard_page', jQuery(this).val() || '0');
                });
            }

            // Auto-detect / create button
            var autoBtn = document.getElementById('cz-guest-autolink-btn');
            if (autoBtn) {
                autoBtn.addEventListener('click', function(){
                    var statusEl = document.getElementById('cz-guest-autolink-status');
                    autoBtn.disabled = true;
                    autoBtn.style.opacity = '0.6';
                    if (statusEl) { statusEl.style.color = '#6b6b85'; statusEl.textContent = <?php echo wp_json_encode( __('Searching...','customize-my-account-for-woocommerce') ); ?>; }

                    var fd = new FormData();
                    fd.append('action', 'wcmamtx_guest_dashboard_autolink');
                    fd.append('nonce',  GUEST_NONCE);
                    fetch(AJAX_URL, {method:'POST', body:fd})
                        .then(function(r){ return r.json(); })
                        .then(function(res){
                            autoBtn.disabled = false;
                            autoBtn.style.opacity = '1';
                            if (res.success) {
                                if (statusEl) { statusEl.style.color = '#22c55e'; statusEl.textContent = '\u2713 ' + res.data.message; }
                                var $s = jQuery('#cz-guest-page-select');
                                if ($s.length) {
                                    if (!$s.find('option[value=' + res.data.page_id + ']').length) {
                                        $s.append(new Option(res.data.page_title, res.data.page_id));
                                    }
                                    $s.val(res.data.page_id).trigger('change');
                                }
                                showLoader();
                                iframe.src = PREVIEW + '?wcmcz=' + Date.now();
                            } else {
                                if (statusEl) { statusEl.style.color = '#ef4444'; statusEl.textContent = res.data || <?php echo wp_json_encode( __('Error.','customize-my-account-for-woocommerce') ); ?>; }
                            }
                        })
                        .catch(function(){
                            autoBtn.disabled = false;
                            autoBtn.style.opacity = '1';
                            if (statusEl) { statusEl.style.color = '#ef4444'; statusEl.textContent = i18n.networkError; }
                        });
                });
            }
        }

        // Trigger init when accordion header is clicked
        document.querySelectorAll('.cz-accordion-header').forEach(function(hdr){
            if (hdr.dataset.target === 'guestdashboard') {
                hdr.addEventListener('click', function(){ setTimeout(initGuestPanel, 100); });
            }
        });
    })();


})();

    // ---- SHORTCODE WIDGETS ----
    (function(){
        // Show/hide shortcode accordions when widget toggle changes
        function handleScToggle(cb) {
            var toggleKey = cb.closest('.cz-priority-item') ? cb.closest('.cz-priority-item').dataset.toggleKey : null;
            if (!toggleKey) return;
            var wrapId = toggleKey === 'shortcode1_override' ? 'wcmamtx-sc-wrap-1' : (toggleKey === 'shortcode2_override' ? 'wcmamtx-sc-wrap-2' : null);
            if (!wrapId) return;
            var wrap = document.getElementById(wrapId);
            if (wrap) wrap.style.display = cb.checked ? '' : 'none';
        }
        // Hook into existing cz-widget-checkbox change events for shortcode keys
        document.addEventListener('change', function(e) {
            var cb = e.target;
            if (!cb.classList.contains('cz-widget-checkbox')) return;
            var li = cb.closest('.cz-priority-item');
            if (!li) return;
            var tk = li.dataset.toggleKey;
            if (tk !== 'shortcode1_override' && tk !== 'shortcode2_override') return;
            handleScToggle(cb);
        });
        // Init on load
        document.querySelectorAll('.cz-widget-checkbox').forEach(function(cb){
            var li = cb.closest('.cz-priority-item');
            if (!li) return;
            var tk = li.dataset.toggleKey;
            if (tk !== 'shortcode1_override' && tk !== 'shortcode2_override') return;
            handleScToggle(cb);
        });
        // Save shortcode input values
        document.querySelectorAll('.wcmamtx-sc-input').forEach(function(inp){
            inp.addEventListener('blur', function(){ saveOption(inp.dataset.scKey, inp.value, true); });
            inp.addEventListener('keydown', function(e){
                if(e.key==='Enter'){ e.preventDefault(); saveOption(inp.dataset.scKey, inp.value, true); }
            });
        });
    })();
// Init link box menus Select2 on page load
setTimeout(function() {
    var lbWrap = document.getElementById('cz-acc-wrap-linkboxmenus');
    if (lbWrap && lbWrap.style.display !== 'none' && typeof window.initLinkBoxSelect2 === 'function') {
        window.initLinkBoxSelect2();
    }
}, 0);

// Login & Register: toggle show/hide Google credentials panel
document.querySelectorAll('.formlogin-toggle').forEach(function(btn){
    btn.addEventListener('click', function(){
        var fields = document.getElementById('cz-login-register-fields');
        if (fields) fields.style.display = btn.dataset.value === '01' ? '' : 'none';
    });
});

document.querySelectorAll('[data-key="show_only_logged_in"]').forEach(function(btn){
    btn.addEventListener('click', function(){
        var field = document.getElementById('cz-nw-popup-field');
        if (field) field.style.display = btn.dataset.value === 'no' ? '' : 'none';
    });
});

document.querySelectorAll('.google-social-toggle').forEach(function(btn){
    btn.addEventListener('click', function(){
        var fields = document.getElementById('cz-google-fields');
        if (fields) fields.style.display = btn.dataset.value === 'yes' ? '' : 'none';
    });
});

document.querySelectorAll('.facebook-social-toggle').forEach(function(btn){
    btn.addEventListener('click', function(){
        var fields = document.getElementById('cz-facebook-fields');
        if (fields) fields.style.display = btn.dataset.value === 'yes' ? '' : 'none';
    });
});

document.querySelectorAll('.cz-template-override-toggle').forEach(function(link){
    link.addEventListener('click', function(e){
        e.preventDefault();
        var info = link.nextElementSibling;
        if (info) info.style.display = info.style.display === 'none' ? '' : 'none';
    });
});

// Login Page Designer
(function(){
    var designBtn  = document.getElementById('cz-login-page-design-btn');
    var designPanel = document.getElementById('cz-login-designer');
    var chevron    = document.getElementById('cz-login-design-chevron');
    var previewBox = document.getElementById('cz-lp-left-preview');

    var lpStatusEl  = document.getElementById('cz-save-status');
    var lpSaveTimer;
    function lpSetStatus(state, text) {
        if (!lpStatusEl) return;
        lpStatusEl.className = state;
        lpStatusEl.textContent = text;
        clearTimeout(lpSaveTimer);
        if (state === 'saved') lpSaveTimer = setTimeout(function(){ lpStatusEl.textContent = ''; lpStatusEl.className = ''; }, 3000);
    }
    function lpSave(key, value) {
        if (!designPanel) return;
        lpSetStatus('saving', designPanel.getAttribute('data-saving'));
        var fd = new FormData();
        fd.append('action', 'wcmamtx_customizer_save');
        fd.append('nonce',  designPanel.getAttribute('data-nonce'));
        fd.append('key',    key);
        fd.append('value',  value);
        fetch(designPanel.getAttribute('data-ajax'), {method:'POST', body:fd, credentials:'same-origin'})
            .then(function(r){ return r.json(); })
            .then(function(res){ lpSetStatus(res.success ? 'saved' : '', res.success ? designPanel.getAttribute('data-saved') : ''); })
            .catch(function(){ lpSetStatus('', ''); });
    }
    function lpSaveBulk(pairs) {
        if (!designPanel) return;
        lpSetStatus('saving', designPanel.getAttribute('data-saving'));
        var fd = new FormData();
        fd.append('action', 'wcmamtx_customizer_save_bulk');
        fd.append('nonce',  designPanel.getAttribute('data-nonce'));
        fd.append('pairs',  JSON.stringify(pairs));
        fetch(designPanel.getAttribute('data-ajax'), {method:'POST', body:fd, credentials:'same-origin'})
            .then(function(r){ return r.json(); })
            .then(function(res){ lpSetStatus(res.success ? 'saved' : '', res.success ? designPanel.getAttribute('data-saved') : ''); })
            .catch(function(){ lpSetStatus('', ''); });
    }

    if (designBtn && designPanel) {
        designBtn.addEventListener('click', function(){
            var open = designPanel.style.display !== 'none';
            designPanel.style.display = open ? 'none' : '';
            if (chevron) chevron.style.transform = open ? '' : 'rotate(180deg)';
        });
    }

    var bgThumb    = document.getElementById('cz-lp-bg-thumb');
    var bgSizeWrap = document.getElementById('cz-lp-bg-size-wrap');
    var bgSizeRange= document.getElementById('cz-lp-bg-size-range');
    var bgSizeLbl  = document.getElementById('cz-lp-bg-size-label');
    var bgUploadBtn= document.getElementById('cz-lp-bg-upload-btn');
    var bgUploadLbl= document.getElementById('cz-lp-bg-upload-label');
    var bgFile     = document.getElementById('cz-lp-bg-file');
    var bgRemove   = document.getElementById('cz-lp-bg-remove');

    function getBgUrl() {
        if (!bgThumb || bgThumb.style.display === 'none') return '';
        var m = bgThumb.style.background.match(/url\(["']?([^"')]+)["']?\)/);
        return m ? m[1] : '';
    }
    function applyBgToPreview(url, sizeVal) {
        if (!previewBox) return;
        if (url) {
            var cssSize = (!sizeVal || sizeVal === '0') ? 'cover' : (sizeVal + '%');
            previewBox.style.background = 'url(' + url + ') center/' + cssSize + ' no-repeat #1a1a2e';
        } else {
            updateGradientPreview();
        }
    }
    function updateGradientPreview(){
        if (getBgUrl()) return; // image overrides gradient
        var s = document.querySelector('.cz-lp-color[data-key="login_page_gradient_start"]');
        var e = document.querySelector('.cz-lp-color[data-key="login_page_gradient_end"]');
        var grad = 'linear-gradient(135deg,' + (s ? s.value : '#667eea') + ' 0%,' + (e ? e.value : '#764ba2') + ' 100%)';
        if (previewBox) previewBox.style.background = grad;
        if (designBtn) designBtn.style.background = grad;
    }
    // Init preview if image already stored
    (function(){ var u = getBgUrl(); if (u && bgSizeRange) applyBgToPreview(u, bgSizeRange.value); })();

    document.querySelectorAll('.cz-lp-field').forEach(function(inp){
        inp.addEventListener('input', function(){
            var val = inp.value || inp.getAttribute('data-default');
            var pid = inp.getAttribute('data-preview');
            var bid = inp.getAttribute('data-badge-preview');
            if (pid) { var el = document.getElementById(pid); if (el) el.textContent = val; }
            if (bid) { var el2 = document.getElementById(bid); if (el2) el2.textContent = val; }
        });
        inp.addEventListener('blur', function(){
            lpSave(inp.getAttribute('data-key'), inp.value);
        });
        inp.addEventListener('keydown', function(e){
            if (e.key === 'Enter'){ e.preventDefault(); lpSave(inp.getAttribute('data-key'), inp.value); }
        });
    });

    document.querySelectorAll('.cz-lp-color').forEach(function(inp){
        inp.addEventListener('input', updateGradientPreview);
        inp.addEventListener('change', function(){
            updateGradientPreview();
            lpSave(inp.getAttribute('data-key'), inp.value);
        });
    });

    // Background image upload
    if (bgFile) {
        bgFile.addEventListener('change', function(){
            var file = bgFile.files[0];
            if (!file || !designPanel) return;
            if (bgUploadLbl) bgUploadLbl.textContent = bgUploadBtn ? bgUploadBtn.getAttribute('data-uploading') : '…';
            lpSetStatus('saving', designPanel.getAttribute('data-saving'));
            var fd = new FormData();
            fd.append('action', 'wcmamtx_upload_login_bg');
            fd.append('nonce',  designPanel.getAttribute('data-nonce'));
            fd.append('file',   file);
            fetch(designPanel.getAttribute('data-ajax'), {method:'POST', body:fd, credentials:'same-origin'})
                .then(function(r){ return r.json(); })
                .then(function(res){
                    if (bgUploadLbl) bgUploadLbl.textContent = bgUploadBtn ? bgUploadBtn.getAttribute('data-choose') : 'Choose Image';
                    bgFile.value = '';
                    if (res.success) {
                        var url = res.data.url;
                        var sizeVal = bgSizeRange ? bgSizeRange.value : '0';
                        var cssSize = (!sizeVal || sizeVal === '0') ? 'cover' : (sizeVal + '%');
                        if (bgThumb) { bgThumb.style.background = 'url(' + url + ') center/' + cssSize + ' no-repeat #1a1a2e'; bgThumb.style.display = ''; }
                        if (bgSizeWrap) bgSizeWrap.style.display = '';
                        applyBgToPreview(url, sizeVal);
                        lpSave('login_page_bg_image', url);
                        lpSetStatus('saved', designPanel.getAttribute('data-saved'));
                    } else {
                        lpSetStatus('', '');
                    }
                })
                .catch(function(){ if (bgUploadLbl && bgUploadBtn) bgUploadLbl.textContent = bgUploadBtn.getAttribute('data-choose'); bgFile.value = ''; lpSetStatus('',''); });
        });
    }

    if (bgRemove) {
        bgRemove.addEventListener('click', function(){
            if (bgThumb) bgThumb.style.display = 'none';
            if (bgSizeWrap) bgSizeWrap.style.display = 'none';
            applyBgToPreview('', '');
            lpSave('login_page_bg_image', '');
        });
    }

    if (bgSizeRange) {
        bgSizeRange.addEventListener('input', function(){
            var v = bgSizeRange.value;
            if (bgSizeLbl) bgSizeLbl.textContent = (v == '0') ? '<?php echo esc_js( __( 'Fill', 'customize-my-account-for-woocommerce' ) ); ?>' : v + '%';
            var cssSize = (v == '0') ? 'cover' : (v + '%');
            if (bgThumb) bgThumb.style.backgroundSize = cssSize;
            var url = getBgUrl();
            if (url) applyBgToPreview(url, v);
        });
        bgSizeRange.addEventListener('change', function(){
            var v = bgSizeRange.value;
            lpSave('login_page_bg_size', v == '0' ? 'cover' : v);
        });
    }

    // Text colour picker
    var textColorPicker = document.getElementById('cz-lp-text-color');
    function applyPreviewTextColor(color) {
        if (!previewBox) return;
        previewBox.querySelectorAll('#cz-lp-preview-headline,#cz-lp-preview-subtitle,#cz-lp-preview-badge-text').forEach(function(el){
            el.style.color = color;
            el.style.webkitTextFillColor = color;
        });
    }
    if (textColorPicker) {
        applyPreviewTextColor(textColorPicker.value);
        textColorPicker.addEventListener('input', function(){ applyPreviewTextColor(textColorPicker.value); });
        textColorPicker.addEventListener('change', function(){ lpSave('login_page_text_color', textColorPicker.value); });
    }

    // Badge background picker
    var badgeBgPicker  = document.getElementById('cz-lp-badge-bg');
    var previewBadge   = document.getElementById('cz-lp-preview-badge');
    if (badgeBgPicker) {
        badgeBgPicker.addEventListener('input', function(){
            if (previewBadge) previewBadge.style.background = badgeBgPicker.value;
        });
        badgeBgPicker.addEventListener('change', function(){
            lpSave('login_page_badge_bg', badgeBgPicker.value);
        });
    }

    var resetBtn = document.getElementById('cz-lp-reset');
    if (resetBtn) {
        resetBtn.addEventListener('click', function(){
            var pairs = [];
            document.querySelectorAll('.cz-lp-field').forEach(function(inp){
                inp.value = '';
                var def = inp.getAttribute('data-default');
                var pid = inp.getAttribute('data-preview');
                var bid = inp.getAttribute('data-badge-preview');
                if (pid) { var el = document.getElementById(pid); if (el) el.textContent = def; }
                if (bid) { var el2 = document.getElementById(bid); if (el2) el2.textContent = def; }
                pairs.push({key: inp.getAttribute('data-key'), value: ''});
            });
            var defaults = { login_page_gradient_start: '#667eea', login_page_gradient_end: '#764ba2' };
            document.querySelectorAll('.cz-lp-color').forEach(function(inp){
                var defVal = defaults[inp.getAttribute('data-key')] || '#667eea';
                inp.value = defVal;
                pairs.push({key: inp.getAttribute('data-key'), value: defVal});
            });
            if (bgThumb) bgThumb.style.display = 'none';
            if (bgSizeWrap) bgSizeWrap.style.display = 'none';
            pairs.push({key: 'login_page_bg_image', value: ''});
            if (textColorPicker) { textColorPicker.value = '#ffffff'; applyPreviewTextColor('#ffffff'); }
            pairs.push({key: 'login_page_text_color', value: ''});
            if (badgeBgPicker) { badgeBgPicker.value = '#ffffff29'; if (previewBadge) previewBadge.style.background = 'rgba(255,255,255,.15)'; }
            pairs.push({key: 'login_page_badge_bg', value: ''});
            lpSaveBulk(pairs);
            updateGradientPreview();
        });
    }
})();
</script>
<div id="cz-defaulttab-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="cz-dtab-title">
  <div id="cz-modal-box">
    <button class="cz-pm-close cz-pm-close-all" aria-label="Close">&times;</button>
    <div class="cz-pm-icon">&#128274;</div>
    <h3 id="cz-dtab-title"><?php esc_html_e('Pro Feature','customize-my-account-for-woocommerce'); ?></h3>
    <p><?php esc_html_e('Change the default My Account tab so customers land on any page you choose — Orders, Downloads, your custom endpoint, and more. Available in the Pro version.','customize-my-account-for-woocommerce'); ?></p>
    <div class="cz-pm-actions">
      <a href="https://sysbasics.com/go/customize/" target="_blank" class="cz-pm-btn">&#9889; <?php esc_html_e('Upgrade to Pro','customize-my-account-for-woocommerce'); ?></a>
    </div>
  </div>
</div>
<div id="cz-thankyou-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="cz-tym-title">
  <div id="cz-modal-box" style="max-width:440px;">
    <button class="cz-pm-close cz-pm-close-all" aria-label="Close">&times;</button>
    <img src="<?php echo esc_url(wcmamtx_PLUGIN_URL.'assets/images/thankyou2.png'); ?>" alt="Thank You page preview" class="cz-pm-preview-img">
    <h3 id="cz-tym-title"><?php esc_html_e('Pro Feature','customize-my-account-for-woocommerce'); ?></h3>
    <p><?php esc_html_e('Thank You page template is available in the Pro version. Give customers a branded post-purchase experience.','customize-my-account-for-woocommerce'); ?></p>
    <div class="cz-pm-actions">
      <a href="https://sysbasics.com/go/customize/" target="_blank" class="cz-pm-btn">&#9889; <?php esc_html_e('Upgrade to Pro','customize-my-account-for-woocommerce'); ?></a>
    </div>
  </div>
</div>
<div id="cz-orderpay-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="cz-opm-title">
  <div id="cz-modal-box" style="max-width:440px;">
    <button class="cz-pm-close cz-pm-close-all" aria-label="Close">&times;</button>
    <img src="<?php echo esc_url(wcmamtx_PLUGIN_URL.'assets/images/orderpay2.png'); ?>" alt="Order Pay page preview" class="cz-pm-preview-img">
    <h3 id="cz-opm-title"><?php esc_html_e('Pro Feature','customize-my-account-for-woocommerce'); ?></h3>
    <p><?php esc_html_e('Order Pay page template is available in the Pro version. Style the payment page to match your brand.','customize-my-account-for-woocommerce'); ?></p>
    <div class="cz-pm-actions">
      <a href="https://sysbasics.com/go/customize/" target="_blank" class="cz-pm-btn">&#9889; <?php esc_html_e('Upgrade to Pro','customize-my-account-for-woocommerce'); ?></a>
    </div>
  </div>
</div>
<div id="cz-dashcard-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="cz-dcm-title">
  <div id="cz-modal-box" style="max-width:440px;">
    <button class="cz-pm-close cz-pm-close-all" aria-label="Close">&times;</button>
    <img src="<?php echo esc_url(wcmamtx_PLUGIN_URL.'assets/images/layout3.png'); ?>" alt="Card style preview" class="cz-pm-preview-img">
    <h3 id="cz-dcm-title"><?php esc_html_e('Pro Feature','customize-my-account-for-woocommerce'); ?></h3>
    <p><?php esc_html_e('Card style for Dashboard Links is available in the Pro version. Display your account links as elegant cards.','customize-my-account-for-woocommerce'); ?></p>
    <div class="cz-pm-actions">
      <a href="https://sysbasics.com/go/customize/" target="_blank" class="cz-pm-btn">&#9889; <?php esc_html_e('Upgrade to Pro','customize-my-account-for-woocommerce'); ?></a>
    </div>
  </div>
</div>
<div id="cz-dashtile-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="cz-dtm-title">
  <div id="cz-modal-box" style="max-width:440px;">
    <button class="cz-pm-close cz-pm-close-all" aria-label="Close">&times;</button>
    <img src="<?php echo esc_url(wcmamtx_PLUGIN_URL.'assets/images/layout4.png'); ?>" alt="Tile style preview" class="cz-pm-preview-img">
    <h3 id="cz-dtm-title"><?php esc_html_e('Pro Feature','customize-my-account-for-woocommerce'); ?></h3>
    <p><?php esc_html_e('Tile style for Dashboard Links is available in the Pro version. Display your account links as a beautiful tile grid.','customize-my-account-for-woocommerce'); ?></p>
    <div class="cz-pm-actions">
      <a href="https://sysbasics.com/go/customize/" target="_blank" class="cz-pm-btn">&#9889; <?php esc_html_e('Upgrade to Pro','customize-my-account-for-woocommerce'); ?></a>
    </div>
  </div>
</div>
<div id="cz-profilebox-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="cz-pbm-title">
  <div id="cz-modal-box" style="max-width:440px;">
    <button class="cz-pm-close cz-pm-close-all" aria-label="Close">&times;</button>
    <img src="<?php echo esc_url(wcmamtx_PLUGIN_URL.'assets/images/profilebox1.png'); ?>" alt="Profile Completion Wizard preview" class="cz-pm-preview-img">
    <h3 id="cz-pbm-title"><?php esc_html_e('Pro Feature','customize-my-account-for-woocommerce'); ?></h3>
    <p><?php esc_html_e('Profile Completion Wizard is available in the Pro version. Help users complete their profile with a guided wizard on their dashboard.','customize-my-account-for-woocommerce'); ?></p>
    <div class="cz-pm-actions">
      <a href="https://sysbasics.com/go/customize/" target="_blank" class="cz-pm-btn">&#9889; <?php esc_html_e('Upgrade to Pro','customize-my-account-for-woocommerce'); ?></a>
    </div>
  </div>
</div>
<div id="cz-linkbox-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="cz-lbm-title">
  <div id="cz-modal-box" style="max-width:440px;">
    <button class="cz-pm-close cz-pm-close-all" aria-label="Close">&times;</button>
    <img src="<?php echo esc_url(wcmamtx_PLUGIN_URL.'assets/images/linkbox1.png'); ?>" alt="Link Boxes preview" class="cz-pm-preview-img">
    <h3 id="cz-lbm-title"><?php esc_html_e('Pro Feature','customize-my-account-for-woocommerce'); ?></h3>
    <p><?php esc_html_e('Link Boxes widget is available in the Pro version. Display stylish navigation boxes on your customer dashboard.','customize-my-account-for-woocommerce'); ?></p>
    <div class="cz-pm-actions">
      <a href="https://sysbasics.com/go/customize/" target="_blank" class="cz-pm-btn">&#9889; <?php esc_html_e('Upgrade to Pro','customize-my-account-for-woocommerce'); ?></a>
    </div>
  </div>
</div>
<div id="cz-pro-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="cz-pm-title">
  <div id="cz-modal-box">
    <button class="cz-pm-close cz-pm-close-all" aria-label="Close">&times;</button>
    <div class="cz-pm-icon">&#128274;</div>
    <h3 id="cz-pm-title"><?php esc_html_e('Pro Feature','customize-my-account-for-woocommerce'); ?></h3>
    <p><?php esc_html_e('Right sidebar Layout is available in the Pro version. Upgrade to unlock all layout options and more.','customize-my-account-for-woocommerce'); ?></p>
    <div class="cz-pm-actions">
      <a href="https://sysbasics.com/go/customize/" target="_blank" class="cz-pm-btn">&#9889; <?php esc_html_e('Upgrade to Pro','customize-my-account-for-woocommerce'); ?></a>
    </div>
  </div>
</div>
<div id="cz-banking-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="cz-bm-title">
  <div id="cz-modal-box">
    <button class="cz-pm-close cz-pm-close-all" aria-label="Close">&times;</button>
    <div class="cz-pm-icon">&#128274;</div>
    <h3 id="cz-bm-title"><?php esc_html_e('Pro Feature','customize-my-account-for-woocommerce'); ?></h3>
    <p><?php esc_html_e('Banking App navigation style is available in the Pro version. Upgrade to unlock this and more layouts.','customize-my-account-for-woocommerce'); ?></p>
    <div class="cz-pm-actions">
      <a href="https://sysbasics.com/go/customize/" target="_blank" class="cz-pm-btn">&#9889; <?php esc_html_e('Upgrade to Pro','customize-my-account-for-woocommerce'); ?></a>
      <a href="https://www.sysbasics.com/wp-content/uploads/2026/06/screen-capture-2.webm" target="_blank" class="cz-pm-btn-ghost">&#9654; <?php esc_html_e('Preview','customize-my-account-for-woocommerce'); ?></a>
    </div>
  </div>
</div>
<div id="cz-nav-pro-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="cz-np-title">
  <div id="cz-modal-box">
    <button class="cz-pm-close cz-pm-close-all" aria-label="Close">&times;</button>
    <div class="cz-pm-icon">&#128274;</div>
    <h3 id="cz-np-title"><?php esc_html_e('Pro Feature','customize-my-account-for-woocommerce'); ?></h3>
    <p><?php esc_html_e('This navigation style is available in the Pro version. Upgrade to unlock all layout options and more.','customize-my-account-for-woocommerce'); ?></p>
    <div class="cz-pm-actions">
      <a href="https://sysbasics.com/go/customize/" target="_blank" class="cz-pm-btn">&#9889; <?php esc_html_e('Upgrade to Pro','customize-my-account-for-woocommerce'); ?></a>
    </div>
  </div>
</div>
<div id="cz-topbar-nav-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="cz-tn-title">
  <div id="cz-modal-box" style="max-width:480px;">
    <button class="cz-pm-close cz-pm-close-all" aria-label="Close">&times;</button>
    <div class="cz-pm-icon">&#128274;</div>
    <h3 id="cz-tn-title"><?php esc_html_e('Pro Feature','customize-my-account-for-woocommerce'); ?></h3>
    <p><?php esc_html_e('Top Horizontal Bar navigation style is available in the Pro version.','customize-my-account-for-woocommerce'); ?></p>
    <img src="<?php echo esc_url( wcmamtx_PLUGIN_URL . 'assets/images/navigation6.png' ); ?>" alt="<?php esc_attr_e('Top Horizontal Bar preview','customize-my-account-for-woocommerce'); ?>" style="width:100%;border-radius:8px;margin:12px 0 18px;display:block;">
    <div class="cz-pm-actions">
      <a href="https://sysbasics.com/go/customize/" target="_blank" class="cz-pm-btn">&#9889; <?php esc_html_e('Upgrade to Pro','customize-my-account-for-woocommerce'); ?></a>
    </div>
  </div>
</div>
<script>
(function(){
  var overlay   = document.getElementById('cz-pro-modal-overlay');
  var overlayBk = document.getElementById('cz-banking-modal-overlay');
  var overlayNp = document.getElementById('cz-nav-pro-modal-overlay');
  var overlayTn = document.getElementById('cz-topbar-nav-modal-overlay');
  var overlayPb = document.getElementById('cz-profilebox-modal-overlay');
  var overlayLb = document.getElementById('cz-linkbox-modal-overlay');
  var overlayDc = document.getElementById('cz-dashcard-modal-overlay');
  var overlayDt = document.getElementById('cz-dashtile-modal-overlay');
  var overlayDt2 = document.getElementById('cz-defaulttab-modal-overlay');
  var overlayTy = document.getElementById('cz-thankyou-modal-overlay');
  var overlayOp = document.getElementById('cz-orderpay-modal-overlay');
  function openModal(id){
    var m=document.getElementById(id); if(m) m.classList.add('open');
  }
  window.openModal = openModal;
  function closeAll(){
    [overlay,overlayBk,overlayNp,overlayTn,overlayPb,overlayLb,overlayDc,overlayDt,overlayTy,overlayOp,overlayDt2].forEach(function(o){if(o)o.classList.remove('open');});
  }
  document.querySelectorAll('.cz-pro-locked').forEach(function(b){
    b.addEventListener('click', function(e){
      e.preventDefault(); e.stopPropagation();
      openModal(b.dataset.modal || 'cz-pro-modal-overlay');
    });
  });
  document.querySelectorAll('.cz-widget-pro-locked').forEach(function(li){
    li.addEventListener('click', function(e){
      e.stopPropagation();
      openModal(li.dataset.modal || 'cz-pro-modal-overlay');
    });
  });
  document.querySelectorAll('.cz-pm-close-all').forEach(function(btn){
    btn.addEventListener('click', closeAll);
  });
  [overlay,overlayBk,overlayNp,overlayTn,overlayPb,overlayLb,overlayDc,overlayDt,overlayTy,overlayOp,overlayDt2].forEach(function(ov){
    if(ov) ov.addEventListener('click', function(e){ if(e.target===ov) closeAll(); });
  });
  document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeAll(); });
})();
</script>
<div id="cz-quick-help-overlay" role="dialog" aria-modal="true" aria-labelledby="cz-qh-title">
  <div id="cz-modal-box" style="max-width:420px;text-align:left;">
    <button class="cz-pm-close" id="cz-quick-help-close" aria-label="Close">&times;</button>
    <h3 id="cz-qh-title" style="text-align:left;"><?php esc_html_e( 'Quick Help', 'customize-my-account-for-woocommerce' ); ?></h3>
    <p style="text-align:left;"><?php esc_html_e( 'Have a question or issue? Send us a message and we\'ll get back to you.', 'customize-my-account-for-woocommerce' ); ?></p>
    <div class="cz-field">
        <label class="cz-label" for="cz-qh-email"><?php esc_html_e( 'Your Email', 'customize-my-account-for-woocommerce' ); ?></label>
        <input type="email" id="cz-qh-email" class="cz-text-input" value="<?php echo esc_attr( $admin_user_email ); ?>">
    </div>
    <div class="cz-field" style="margin-bottom:18px;">
        <label class="cz-label" for="cz-qh-message"><?php esc_html_e( 'Message', 'customize-my-account-for-woocommerce' ); ?></label>
        <textarea id="cz-qh-message" class="cz-text-input" placeholder="<?php esc_attr_e( 'Describe your issue or question…', 'customize-my-account-for-woocommerce' ); ?>"></textarea>
    </div>
    <div class="cz-qh-actions">
        <button type="button" id="cz-qh-submit" class="cz-pm-btn"><?php esc_html_e( 'Send Message', 'customize-my-account-for-woocommerce' ); ?></button>
        <span id="cz-qh-status" style="font-size:12px;color:#a0a0b8;"></span>
    </div>
  </div>
</div>
<script>
(function(){
    var AJAX_URL = <?php echo wp_json_encode( $ajax_url ); ?>;
    var NONCE_QH = <?php echo wp_json_encode( $nonce_quick_help ); ?>;
    var overlay   = document.getElementById('cz-quick-help-overlay');
    var openBtn   = document.getElementById('cz-quick-help-btn');
    var closeBtn  = document.getElementById('cz-quick-help-close');
    var submitBtn = document.getElementById('cz-qh-submit');
    var statusEl  = document.getElementById('cz-qh-status');
    if (!overlay) return;
    function openModal()  { overlay.classList.add('open');    }
    function closeModal() { overlay.classList.remove('open'); statusEl.textContent = ''; }
    if (openBtn)  openBtn.addEventListener('click',  openModal);
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', function(e){ if (e.target === overlay) closeModal(); });
    document.addEventListener('keydown', function(e){
        if (e.key === 'Escape' && overlay.classList.contains('open')) closeModal();
    });
    if (submitBtn) {
        submitBtn.addEventListener('click', function(){
            var email   = document.getElementById('cz-qh-email').value.trim();
            var message = document.getElementById('cz-qh-message').value.trim();
            if (!email || !message) {
                statusEl.style.color = '#f87171';
                statusEl.textContent = <?php echo wp_json_encode( __( 'Please fill in all fields.', 'customize-my-account-for-woocommerce' ) ); ?>;
                return;
            }
            statusEl.style.color  = '#a0a0b8';
            statusEl.textContent  = <?php echo wp_json_encode( __( 'Sending…', 'customize-my-account-for-woocommerce' ) ); ?>;
            submitBtn.disabled    = true;
            var fd = new FormData();
            fd.append('action',  'wcmamtx_quick_help_submit');
            fd.append('nonce',   NONCE_QH);
            fd.append('email',   email);
            fd.append('message', message);
            fetch(AJAX_URL, { method: 'POST', body: fd })
                .then(function(r){ return r.json(); })
                .then(function(res){
                    submitBtn.disabled = false;
                    if (res.success) {
                        statusEl.style.color = '#4ade80';
                        statusEl.textContent = <?php echo wp_json_encode( __( "Message sent! We'll get back to you soon.", 'customize-my-account-for-woocommerce' ) ); ?>;
                        document.getElementById('cz-qh-message').value = '';
                        setTimeout(closeModal, 3000);
                    } else {
                        statusEl.style.color = '#f87171';
                        statusEl.textContent = (res.data && typeof res.data === 'string')
                            ? res.data
                            : <?php echo wp_json_encode( __( 'Failed to send. Please try again.', 'customize-my-account-for-woocommerce' ) ); ?>;
                    }
                })
                .catch(function(){
                    submitBtn.disabled   = false;
                    statusEl.style.color = '#f87171';
                    statusEl.textContent = <?php echo wp_json_encode( __( 'Network error. Please try again.', 'customize-my-account-for-woocommerce' ) ); ?>;
                });
        });
    }
})();
</script>
</body>
</html>
<?php
    echo ob_get_clean();
    exit;
}