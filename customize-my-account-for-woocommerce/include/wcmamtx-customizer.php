<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! defined( 'WCMAMTX_CUSTOMIZER_SLUG' ) ) if ( ! defined( 'WCMAMTX_CUSTOMIZER_SLUG' ) ) define( 'WCMAMTX_CUSTOMIZER_SLUG', 'wcmamtx_frontend_customizer' );
if ( ! defined( 'WCMAMTX_CUSTOMIZER_OPT' ) )  if ( ! defined( 'WCMAMTX_CUSTOMIZER_OPT' ) )  define( 'WCMAMTX_CUSTOMIZER_OPT',  'wcmamtx_layout' );

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
        echo '<a href="' . esc_url( $url ) . '" target="_blank" class="btn wcmamtx_live_customizer btn-warning" style="position: fixed;
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
        'nav_style'                       => ['01','02','03','04'],
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
    }
    $layout        = (array) get_option( WCMAMTX_CUSTOMIZER_OPT );
    $layout[$key]  = $value;
    update_option( WCMAMTX_CUSTOMIZER_OPT, $layout );
    wp_cache_delete( WCMAMTX_CUSTOMIZER_OPT, 'options' );
    wp_send_json_success( ['key' => $key, 'value' => $value] );
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
    $settings        = (array) get_option( 'wcmamtx_avatar_settings' );
    $settings[$key]  = $value;
    update_option( 'wcmamtx_avatar_settings', $settings );
    wp_cache_delete( 'wcmamtx_avatar_settings', 'options' );
    wp_send_json_success( ['key' => $key, 'value' => $value] );
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
    $ajax_url    = admin_url( 'admin-ajax.php' );
    $back_url    = admin_url( 'admin.php?page=wcmamtx_advanced_settings&tab=wcmamtx_layout' );

    $g = function($k, $d) use ($layout) { return isset($layout[$k]) ? $layout[$k] : $d; };

    $nav_style     = $g('nav_style',                '02');
    $sidebar_style = $g('sidebar_style',            '01');
    $dash_style    = $g('dash_style',               '01');
    $profilebox    = $g('profilebox_override',      '02');
    $dashlinks     = $g('dashlink_layout_override', '01');
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
    $av_formats_def   = ['jpg','jpeg','jpe','gif','png','webp'];
    $av_formats       = isset($av['allowed_formats']) ? $av['allowed_formats'] : $av_formats_def;
    $av_all_formats   = ['jpg'=>'JPG','jpeg'=>'JPEG','jpe'=>'JPE','gif'=>'GIF','png'=>'PNG','webp'=>'WEBP'];

    // Nav Menu Widget
    $nw_override   = $g('navigationwidget_layout_override', '01');
    $nw_show       = $g('nav_header_widget',               'yes');
    $nw_location   = $g('widget_menu_location',            '');
    $nw_text_in    = $g('nav_header_widget_text',          'My Account');
    $nw_text_out   = $g('nav_header_widget_text_logout',   'Log In');
    $nw_logged_in  = $g('show_only_logged_in',             'no');
    $nw_dis_avatar = $g('navwidget_disable_avatar',        'no');
    $nw_dis_user   = $g('navwidget_disable_username',      'no');
    $menu_locations = array_keys( get_nav_menu_locations() );

    $nav_options = [
        '01' => __('Theme Default','customize-my-account-for-woocommerce'),
        '02' => __('Clean',        'customize-my-account-for-woocommerce'),
        '03' => __('Banking App',  'customize-my-account-for-woocommerce'),
        '04' => __('React Based',  'customize-my-account-for-woocommerce'),
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
#cz-pro-modal-overlay,#cz-banking-modal-overlay,#cz-profilebox-modal-overlay,#cz-linkbox-modal-overlay,#cz-dashcard-modal-overlay,#cz-dashtile-modal-overlay,#cz-thankyou-modal-overlay,#cz-orderpay-modal-overlay,#cz-defaulttab-modal-overlay{display:none;position:absolute;inset:0;background:rgba(0,0,0,.55);z-index:9999;align-items:center;justify-content:center;}
#cz-pro-modal-overlay.open,#cz-banking-modal-overlay.open,#cz-profilebox-modal-overlay.open,#cz-linkbox-modal-overlay.open,#cz-dashcard-modal-overlay.open,#cz-dashtile-modal-overlay.open,#cz-thankyou-modal-overlay.open,#cz-orderpay-modal-overlay.open,#cz-defaulttab-modal-overlay.open{display:flex;}
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
</style>
</head>
<body class="wp-customizer">
<div id="wcmamtx-customizer">

<!-- TOPBAR -->
<div id="wcmcz-topbar">
    <span class="cz-logo">
        <span class="dashicons dashicons-art"></span>
        <?php esc_html_e('SysBasics My Account Customizer','customize-my-account-for-woocommerce'); ?>
    </span>
    <div class="cz-device-btns">
        <button data-device="desktop" class="active" title="<?php esc_attr_e('Desktop','customize-my-account-for-woocommerce'); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg></button>
        <button data-device="mobile" title="<?php esc_attr_e('Mobile','customize-my-account-for-woocommerce'); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2"/><circle cx="12" cy="18" r="1" fill="currentColor" stroke="none"/></svg></button>
    </div>
    <div class="cz-actions">
        <span id="cz-save-status"></span>
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
                                <?php foreach($nav_options as $val=>$label): ?>
                                <?php if($val==='03'): ?>
                                <button class="cz-toggle cz-pro-locked" type="button" title="Pro feature" data-modal="cz-banking-modal-overlay" style="text-align:left;padding:10px 12px;"><?php echo esc_html($label); ?><span class="cz-pro-badge">&#128274;</span></button>
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
                            <?php echo wp_kses( __( 'This will fully replace your WooCommerce My Account page with a React based navigation system. <strong>User avatar is currently not supported.</strong> If you experience any third party plugin JavaScript getting broken, consider switching back to any of the other available navigation styles.', 'customize-my-account-for-woocommerce' ), ['strong'=>[]] ); ?>
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
                                <button class="cz-toggle cz-pro-locked" type="button" title="Pro feature"><?php esc_html_e('Full Width','customize-my-account-for-woocommerce'); ?><span class="cz-pro-badge">&#128274;</span></button>
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
                    <span class="cz-accordion-title"><span class="dashicons dashicons-list-view"></span><?php esc_html_e('Nav Menu Widget','customize-my-account-for-woocommerce'); ?></span>
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
                    </span>
                    <span class="cz-accordion-chevron">&#9660;</span>
                </div>
                <div class="cz-accordion-body" id="cz-acc-Endpoints">
                    <div class="cz-group">
                        
                        <div class="cz-field">
                            <div class="cz-toggle-group" style="flex-direction:column;">
                                
                            <a target="_blank" href="<?php echo esc_url( admin_url( 'admin.php?page=wcmamtx_advanced_settings' ) ); ?>" class="cz-btn cz-btn-success" id="">
                                  <?php esc_html_e( 'Manage Endpoints', 'customize-my-account-for-woocommerce-pro' ); ?>
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
                                    <input type="number" class="cz-number-input cz-avatar-number" data-opt="avatar_size" value="<?php echo esc_attr($av_size); ?>" min="96" max="350"> px
                                </div>
                            </div>
                            <div class="cz-field">
                                <label class="cz-label"><?php esc_html_e('Min dimensions','customize-my-account-for-woocommerce'); ?></label>
                                <div class="cz-inline-row">
                                    
                                    <input type="number" class="cz-number-input cz-avatar-number" data-opt="min_height" value="<?php echo esc_attr($av_min_h); ?>" min="96" max="350">
                                    
                                    <input type="number" class="cz-number-input cz-avatar-number" data-opt="min_width"  value="<?php echo esc_attr($av_min_w); ?>" min="96" max="350"> 
                                </div>
                            </div>
                            <div class="cz-field">
                                <label class="cz-label"><?php esc_html_e('Max dimensions','customize-my-account-for-woocommerce'); ?></label>
                                <div class="cz-inline-row">
                                    
                                    <input type="number" class="cz-number-input cz-avatar-number" data-opt="max_height" value="<?php echo esc_attr($av_max_h); ?>" min="96" max="350">
                                   
                                    <input type="number" class="cz-number-input cz-avatar-number" data-opt="max_width"  value="<?php echo esc_attr($av_max_w); ?>" min="96" max="350">
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
                            <div id="cz-avatar-content" style="<?php echo $av_custom_cont==='yes'?'':"display:none;"; ?>">
                                <div style="background:#1e1e2e;border:1px solid #2d2d3f;border-radius:6px;padding:10px 12px;">
                                    <p style="font-size:12px;color:#a0a0b8;line-height:1.6;margin:0 0 8px 0;"><?php esc_html_e('Controls text displayed after the avatar and before the navigation menu.','customize-my-account-for-woocommerce'); ?></p>
                                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=wcmamtx_advanced_settings&tab=wcmamtx_avatar_settings#wcmamtx_custom_content_textarea_tr' ) ); ?>" target="_blank" style="font-size:12px;color:#a78bfa;text-decoration:none;display:inline-flex;align-items:center;gap:5px;"><span class="dashicons dashicons-edit" style="font-size:13px;width:13px;height:13px;line-height:1;"></span><?php esc_html_e('Edit content in Avatar Settings','customize-my-account-for-woocommerce'); ?></a>
                                </div>
                            </div>
                        </div>

                    </div><!-- /avatar subopts -->


                <?php do_action( 'wcmamtx_customizer_section_avatar' ); // Add options to User Avatar accordion ?>
                </div><!-- /accordion-body avatar -->
            </div><!-- /accordion avatar -->


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
                                <a href="<?php echo esc_url( admin_url('admin.php?page=wc-orders') ); ?>" target="_blank" class="cz-tracking-link"><span class="dashicons dashicons-cart" style="font-size:13px;width:13px;height:13px;line-height:1;"></span><?php esc_html_e('Add tracking info to your orders','customize-my-account-for-woocommerce'); ?></a>
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
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wcmamtx_advanced_settings&tab=wcmamtx_layout' ) ); ?>" target="_blank" class="cz-footer-btn cz-footer-btn-outline">
            <span class="dashicons dashicons-admin-settings" style="font-size:14px;width:14px;height:14px;line-height:1;"></span>
            <?php esc_html_e( 'All Settings', 'customize-my-account-for-woocommerce' ); ?>
        </a>
        <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" target="_blank" class="cz-footer-btn cz-footer-btn-purple">
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
            saveAvatarOption(opt, val);
        });
    });

    // Avatar number inputs
    document.querySelectorAll('.cz-avatar-number').forEach(function(inp){
        inp.addEventListener('change',function(){ saveAvatarOption(inp.dataset.opt, inp.value); });
    });

    // Avatar text inputs
    document.querySelectorAll('.cz-avatar-text').forEach(function(inp){
        inp.addEventListener('blur',function(){ saveAvatarOption(inp.dataset.opt, inp.value); });
        inp.addEventListener('keydown',function(e){
            if(e.key==='Enter'){e.preventDefault(); saveAvatarOption(inp.dataset.opt, inp.value);}
        });
    });

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

})();

// Init link box menus Select2 on page load
setTimeout(function() {
    var lbWrap = document.getElementById('cz-acc-wrap-linkboxmenus');
    if (lbWrap && lbWrap.style.display !== 'none' && typeof window.initLinkBoxSelect2 === 'function') {
        window.initLinkBoxSelect2();
    }
}, 0);
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
    <p><?php esc_html_e('Right sidebar and Full Width layout are available in the Pro version. Upgrade to unlock all layout options and more.','customize-my-account-for-woocommerce'); ?></p>
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
<script>
(function(){
  var overlay   = document.getElementById('cz-pro-modal-overlay');
  var overlayBk = document.getElementById('cz-banking-modal-overlay');
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
    [overlay,overlayBk,overlayPb,overlayLb,overlayDc,overlayDt,overlayTy,overlayOp,overlayDt2].forEach(function(o){if(o)o.classList.remove('open');});
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
  [overlay,overlayBk,overlayPb,overlayLb,overlayDc,overlayDt,overlayTy,overlayOp,overlayDt2].forEach(function(ov){
    if(ov) ov.addEventListener('click', function(e){ if(e.target===ov) closeAll(); });
  });
  document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeAll(); });
})();
</script>
</body>
</html>
<?php
    echo ob_get_clean();
    exit;
}