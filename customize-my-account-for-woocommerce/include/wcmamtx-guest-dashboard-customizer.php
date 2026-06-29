<?php
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'WCMAMTX_GUEST_CUSTOMIZER_SLUG', 'wcmamtx_guest_dashboard_customizer' );

// ── Register menu page ────────────────────────────────────────────────
add_action( 'admin_menu', function() {
    add_menu_page(
        __( 'Guest Dashboard Customizer', 'customize-my-account-for-woocommerce' ),
        __( 'Guest Dashboard', 'customize-my-account-for-woocommerce' ),
        'manage_options',
        WCMAMTX_GUEST_CUSTOMIZER_SLUG,
        'wcmamtx_guest_customizer_render_page',
        'dashicons-groups',
        59
    );
}, 999 );
add_action( 'admin_head', function() { remove_menu_page( WCMAMTX_GUEST_CUSTOMIZER_SLUG ); } );

// ── Strip WP admin styles on this page ───────────────────────────────
add_action( 'admin_enqueue_scripts', function( $hook ) {
    if ( $hook !== 'toplevel_page_' . WCMAMTX_GUEST_CUSTOMIZER_SLUG ) return;
    foreach ( ['colors','wp-admin','common','forms','admin-menu','dashboard','list-tables','edit','media','themes','nav-menus','widgets','wp-auth-check'] as $s ) {
        wp_dequeue_style( $s );
    }
}, 99 );

// ── AJAX: save a single wcmamtx_layout key ───────────────────────────
add_action( 'wp_ajax_wcmamtx_guest_customizer_save', function() {
    check_ajax_referer( 'wcmamtx_guest_customizer_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
    $key   = isset( $_POST['key'] )   ? sanitize_key( $_POST['key'] ) : '';
    $value = isset( $_POST['value'] ) ? sanitize_text_field( wp_unslash( $_POST['value'] ) ) : '';
    if ( ! $key ) wp_send_json_error( 'Missing key' );
    $allowed = [
        'guest_nav_active_color'  => 'text',
        'guest_avatar_size'       => 'posint',
        'guest_card_radius'       => 'posint',
        'guest_cta_text'          => 'text',
        'guest_dashboard_enable'          => ['01','02'],
        'guest_dashlinks_override'         => ['01','02'],
        'guest_sc1_override'              => ['01','02'],
        'guest_sc2_override'              => ['01','02'],
        'guest_sc1_value'                 => 'text',
        'guest_sc2_value'                 => 'text',
        'guest_dashlinks_priority'        => 'priority',
        'guest_sc1_priority'              => 'priority',
        'guest_sc2_priority'              => 'priority',
    ];
    if ( ! array_key_exists( $key, $allowed ) ) wp_send_json_error( 'Key not allowed' );
    $rule = $allowed[ $key ];
    if ( is_array( $rule ) ) {
        if ( ! in_array( $value, $rule, true ) ) wp_send_json_error( 'Invalid value' );
    } elseif ( $rule === 'posint' ) {
        $value = (string) absint( $value );
    }
    $layout         = wcmamtx_get_layout();
    $layout[ $key ] = $value;
    update_option( 'wcmamtx_layout', $layout );
    wp_cache_delete( 'wcmamtx_layout', 'options' );
    wp_send_json_success( [ 'key' => $key, 'value' => $value ] );
} );

// ── AJAX: save endpoint order and visibility (arrays) ──
add_action( 'wp_ajax_wcmamtx_guest_customizer_save_array', function() {
    check_ajax_referer( 'wcmamtx_guest_customizer_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
    $key  = isset( $_POST['key'] ) ? sanitize_key( $_POST['key'] ) : '';
    $vals = isset( $_POST['values'] ) ? array_map( 'sanitize_key', (array) $_POST['values'] ) : [];
    if ( ! in_array( $key, ['guest_endpoint_order','guest_hidden_endpoints'], true ) ) wp_send_json_error( 'Key not allowed' );
    $layout = wcmamtx_get_layout();
    $layout[$key] = $vals;
    update_option( 'wcmamtx_layout', $layout );
    wp_cache_delete( 'wcmamtx_layout', 'options' );
    wp_send_json_success( ['key' => $key, 'count' => count($vals)] );
} );

// ── Render page ───────────────────────────────────────────────────────
function wcmamtx_guest_customizer_render_page() {
    if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );
    ob_end_clean();
    ob_start();

    $layout   = wcmamtx_get_layout();
    $g        = fn( $k, $d ) => isset( $layout[ $k ] ) ? $layout[ $k ] : $d;

    $nonce    = wp_create_nonce( 'wcmamtx_guest_customizer_nonce' );
    $ajax_url = admin_url( 'admin-ajax.php' );
    $back_url = admin_url( 'admin.php?page=wcmamtx_frontend_customizer' );

    // Preview URL — the linked guest dashboard page (shows shortcode output)
    $page_id     = (int) $g( 'guest_dashboard_page', 0 );
    $preview_url = $page_id ? get_permalink( $page_id ) : home_url( '/' );

    // Saved values
    $enabled     = $g( 'guest_dashboard_enable',  '02' );
    $nav_color   = $g( 'guest_nav_active_color',  '#2563eb' );
    $avatar_size = $g( 'guest_avatar_size',        '110' );
    $card_radius = $g( 'guest_card_radius',        '14' );
    $cta_text    = $g( 'guest_cta_text',           __( 'Login to View and Manage Your Orders', 'customize-my-account-for-woocommerce' ) );

    // Build clean endpoint list (no logout, separater, heading)
    $all_ep = [];
    $wcmamtx_tabs_raw = (array) get_option( 'wcmamtx_advanced_settings' );
    foreach ( wc_get_account_menu_items() as $k => $v ) {
        if ( $k === 'customer-logout' ) continue;
        $type = isset( $wcmamtx_tabs_raw[$k]['wcmamtx_type'] ) ? $wcmamtx_tabs_raw[$k]['wcmamtx_type'] : 'endpoint';
        if ( in_array( $type, ['separater','heading'], true ) ) continue;
        $all_ep[$k] = $v;
    }
    $saved_order   = (array) $g( 'guest_endpoint_order', [] );
    $hidden_ep     = (array) $g( 'guest_hidden_endpoints', [] );
    // Merge saved order with any new items
    $ep_keys = array_keys( $all_ep );

    // Dashboard widgets priority
    $guest_dashlinks_priority = (int) $g( 'guest_dashlinks_priority', 10 );
    $guest_sc1_priority       = (int) $g( 'guest_sc1_priority',       20 );
    $guest_sc2_priority       = (int) $g( 'guest_sc2_priority',       30 );
    $guest_sc1_override       = $g( 'guest_sc1_override', '02' );
    $guest_sc2_override       = $g( 'guest_sc2_override', '02' );
    $guest_sc1_value          = $g( 'guest_sc1_value', '' );
    $guest_sc2_value          = $g( 'guest_sc2_value', '' );
    $guest_priority_widgets = [
        'guest_dashlinks_priority' => [ 'label' => __( 'Dashboard Links', 'customize-my-account-for-woocommerce' ), 'priority' => $guest_dashlinks_priority, 'icon' => 'dashicons-grid-view',  'toggle_key' => 'guest_dashlinks_override', 'enabled' => $g( 'guest_dashlinks_override', '01' ) === '01' ],
        'guest_sc1_priority'       => [ 'label' => __( 'Shortcode 1',     'customize-my-account-for-woocommerce' ), 'priority' => $guest_sc1_priority,       'icon' => 'dashicons-shortcode', 'toggle_key' => 'guest_sc1_override', 'enabled' => $guest_sc1_override === '01' ],
        'guest_sc2_priority'       => [ 'label' => __( 'Shortcode 2',     'customize-my-account-for-woocommerce' ), 'priority' => $guest_sc2_priority,       'icon' => 'dashicons-shortcode', 'toggle_key' => 'guest_sc2_override', 'enabled' => $guest_sc2_override === '01' ],
    ];
    uasort( $guest_priority_widgets, fn($a,$b) => $a['priority'] <=> $b['priority'] );
    if ( $saved_order ) {
        $ordered = array_filter( $saved_order, fn($k) => isset( $all_ep[$k] ) );
        foreach ( $ep_keys as $k ) { if ( ! in_array( $k, $ordered, true ) ) $ordered[] = $k; }
    } else {
        $ordered = $ep_keys;
    }

    ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php esc_html_e( 'Guest Dashboard Live Customizer', 'customize-my-account-for-woocommerce' ); ?></title>
<link rel="stylesheet" href="<?php echo esc_url( includes_url( 'css/dashicons.min.css' ) ); ?>">
<style>
/* ── Reset ── */
* { box-sizing: border-box; margin: 0; padding: 0; }
#wpwrap,#wpcontent,#wpbody,#wpbody-content { float:none!important; margin:0!important; padding:0!important; width:100%!important; }
#adminmenuwrap,#adminmenuback,#wpadminbar { display:none!important; }
body.wp-customizer { margin:0!important; padding:0!important; }
html,body { height:100%; overflow:hidden; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif; background:#1e1e2e; }
#wcgd-cz { display:flex; flex-direction:column; height:100vh; }

/* ── Topbar ── */
#wcgd-topbar { display:flex; align-items:center; justify-content:space-between; height:48px; background:#1e1e2e; padding:0 16px; flex-shrink:0; border-bottom:1px solid #2d2d3f; z-index:100; }
.cz-logo { display:flex; align-items:center; gap:10px; color:#fff; font-weight:700; font-size:14px; text-decoration:none; }
.cz-logo .dashicons { color:#a78bfa; font-size:20px; }
.cz-actions { display:flex; align-items:center; gap:10px; }
.cz-btn { display:inline-flex; align-items:center; gap:6px; padding:7px 16px; border-radius:6px; border:none; font-size:13px; font-weight:600; cursor:pointer; transition:all .2s; text-decoration:none; }
.cz-btn-ghost { background:transparent; color:#a0a0b8; border:1px solid #2d2d3f; }
.cz-btn-ghost:hover { color:#fff; border-color:#555; }
.cz-device-btns { display:flex; gap:6px; }
.cz-device-btns button { background:transparent; border:1px solid #2d2d3f; color:#a0a0b8; width:34px; height:34px; border-radius:6px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .2s; }
.cz-device-btns button.active,.cz-device-btns button:hover { background:#2d2d3f; color:#fff; border-color:#a78bfa; }
#cz-save-status { font-size:12px; color:#a0a0b8; min-width:80px; text-align:right; }
#cz-save-status.saved { color:#22c55e; }
#cz-save-status.saving { color:#f59e0b; }

/* ── Body ── */
#wcgd-body { display:flex!important; flex:1!important; overflow:hidden!important; }

/* ── Panel ── */
#wcgd-panel { width:300px; min-width:240px; background:#13131f; display:flex; flex-direction:column; overflow:hidden; border-right:1px solid #2d2d3f; flex-shrink:0; }
#wcgd-panel-content { flex:1; overflow-y:auto; padding:8px; scrollbar-width:thin; scrollbar-color:#2d2d3f transparent; }
#wcgd-panel-content::-webkit-scrollbar { width:4px; }
#wcgd-panel-content::-webkit-scrollbar-thumb { background:#2d2d3f; border-radius:4px; }

/* ── Panel footer ── */
#wcgd-panel-footer { padding:10px 8px; border-top:1px solid #2d2d3f; display:flex; gap:8px; flex-shrink:0; }
.cz-footer-btn { flex:1; display:flex; align-items:center; justify-content:center; gap:6px; padding:8px 12px; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; text-decoration:none; border:none; transition:all .2s; }
.cz-footer-btn-outline { background:transparent; color:#a0a0b8; border:1px solid #2d2d3f; }
.cz-footer-btn-outline:hover { color:#fff; border-color:#555; }
.cz-footer-btn-purple { background:#7c3aed; color:#fff; }
.cz-footer-btn-purple:hover { background:#6d28d9; color:#fff; }

/* ── Accordion ── */
.cz-accordion { border:1px solid #2d2d3f; border-radius:10px; margin-bottom:8px; overflow:hidden; }
.cz-accordion-header { display:flex; align-items:center; justify-content:space-between; padding:12px 14px; background:#1e1e2e; cursor:pointer; user-select:none; transition:background .2s; }
.cz-accordion-header:hover { background:#252538; }
.cz-accordion-title { display:flex; align-items:center; gap:8px; color:#e0e0f0; font-size:13px; font-weight:600; }
.cz-accordion-title .dashicons { color:#a78bfa; font-size:16px; width:16px; height:16px; }
.cz-accordion-chevron { color:#555; font-size:10px; transition:transform .2s; }
.cz-accordion.open .cz-accordion-chevron { transform:rotate(180deg); }
.cz-accordion-body { display:none; padding:12px; border-top:1px solid #2d2d3f; background:#13131f; }
.cz-accordion.open .cz-accordion-body { display:block; }

/* ── Fields ── */
.cz-group { margin-bottom:16px; }
.cz-group:last-child { margin-bottom:0; }
.cz-group-title { font-size:11px; font-weight:700; color:#6b6b85; text-transform:uppercase; letter-spacing:.06em; margin-bottom:8px; }
.cz-field { margin-bottom:10px; }
.cz-field:last-child { margin-bottom:0; }
.cz-label { font-size:12px; color:#a0a0b8; display:block; margin-bottom:6px; }
.cz-toggle-group { display:flex; flex-wrap:wrap; gap:6px; }
.cz-toggle { background:#1e1e2e; border:1px solid #2d2d3f; color:#a0a0b8; padding:6px 12px; border-radius:6px; font-size:12px; font-weight:500; cursor:pointer; transition:all .2s; }
.cz-toggle:hover { border-color:#7c3aed; color:#c4b5fd; }
.cz-toggle.active { background:#4c1d95; border-color:#7c3aed; color:#fff; }
.cz-text-input { width:100%; background:#1e1e2e; border:1px solid #2d2d3f; color:#e0e0f0; padding:8px 10px; border-radius:6px; font-size:13px; outline:none; }
.cz-text-input:focus { border-color:#a78bfa; }
.cz-number-input { width:80px; background:#1e1e2e; border:1px solid #2d2d3f; color:#e0e0f0; padding:7px 8px; border-radius:6px; font-size:13px; outline:none; text-align:center; }
.cz-number-input:focus { border-color:#a78bfa; }
.cz-color-input { width:40px; height:34px; padding:2px; border:1px solid #2d2d3f; border-radius:6px; background:transparent; cursor:pointer; }
.cz-inline-row { display:flex; align-items:center; gap:8px; }
.cz-inline-label { font-size:11px; color:#6b6b85; }

/* ── Preview ── */
#wcgd-preview-wrap { flex:1!important; float:none!important; display:flex; flex-direction:column; background:#1a1a2e; overflow:hidden; position:relative; min-width:0; }
#wcgd-preview-bar { display:flex; align-items:center; gap:8px; padding:8px 14px; background:#13131f; border-bottom:1px solid #2d2d3f; flex-shrink:0; }
#wcgd-preview-url { flex:1; background:#1e1e2e; border:1px solid #2d2d3f; color:#a0a0b8; padding:5px 10px; border-radius:20px; font-size:12px; outline:none; }
#wcgd-preview-container { flex:1; display:flex; align-items:center; justify-content:center; padding:12px; overflow:hidden; transition:all .3s; }
#wcgd-iframe { width:100%; height:100%; border:none; border-radius:10px; background:#fff; box-shadow:0 8px 40px rgba(0,0,0,.5); transition:all .3s; }
#wcgd-preview-container.tablet #wcgd-iframe { width:768px; }
#wcgd-preview-container.mobile #wcgd-iframe { width:560px; border-radius:24px; box-shadow:0 0 0 10px #1e1e2e,0 8px 40px rgba(0,0,0,.5); }
#wcgd-loader { position:absolute; inset:0; background:rgba(13,13,26,.85); display:flex; align-items:center; justify-content:center; z-index:10; opacity:0; pointer-events:none; transition:opacity .2s; }
#wcgd-loader.visible { opacity:1; pointer-events:all; }
.cz-spinner { width:36px; height:36px; border:3px solid #2d2d3f; border-top-color:#a78bfa; border-radius:50%; animation:czSpin .7s linear infinite; }
@keyframes czSpin { to { transform:rotate(360deg); } }
/* ── Endpoint sort list ── */
.cz-sort-list { list-style:none; margin:0; padding:0; display:flex; flex-direction:column; gap:4px; }
.cz-sort-item { display:flex; align-items:center; gap:8px; background:#1e1e2e; border:1px solid #2d2d3f; border-radius:7px; padding:8px 10px; cursor:grab; user-select:none; transition:opacity .2s, border-color .15s, background .15s; }
.cz-sort-item:active { cursor:grabbing; }
.cz-sort-item.cz-dragging { opacity:.3; }
.cz-sort-item.cz-drag-over { border-color:#a78bfa; background:#252538; }
.cz-sort-item--hidden { opacity:.45; }
.cz-drag-handle { display:flex; align-items:center; cursor:grab; flex-shrink:0; }
.cz-ep-label { flex:1; font-size:12px; color:#e0e0f0; font-weight:500; }

/* ── Endpoint toggle switch ── */
.cz-ep-toggle { position:relative; display:inline-flex; align-items:center; width:32px; height:18px; flex-shrink:0; cursor:pointer; }
.cz-ep-toggle input { opacity:0; width:0; height:0; position:absolute; }
.cz-ep-slider { position:absolute; inset:0; background:#2d2d3f; border-radius:18px; transition:background .2s; }
.cz-ep-slider:before { content:''; position:absolute; width:12px; height:12px; left:3px; top:3px; background:#6b6b85; border-radius:50%; transition:transform .2s, background .2s; }
.cz-ep-toggle input:checked + .cz-ep-slider { background:#a78bfa; }
.cz-ep-toggle input:checked + .cz-ep-slider:before { transform:translateX(14px); background:#fff; }

/* ── Dashboard widget priority list ── */
#wcgd-priority-list{list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:4px;}
.cz-priority-item{display:flex;align-items:center;gap:8px;background:#1e1e2e;border:1px solid #2d2d3f;border-radius:6px;padding:8px 10px;cursor:grab;user-select:none;transition:background .15s,border-color .15s;}
.cz-priority-item:active{cursor:grabbing;}
.cz-priority-item.cz-dragging{opacity:.4;}
.cz-priority-item.cz-drag-over{border-color:#a78bfa;background:#252538;}
.cz-priority-label{font-size:12px;color:#e0e0f0;font-weight:500;flex:1;}
.cz-widget-toggle{position:relative;display:inline-flex;align-items:center;width:32px;height:18px;flex-shrink:0;cursor:pointer;margin-left:auto;}
.cz-widget-toggle input{opacity:0;width:0;height:0;position:absolute;}
.cz-widget-toggle-slider{position:absolute;inset:0;background:#2d2d3f;border-radius:18px;transition:background .2s;}
.cz-widget-toggle-slider:before{content:"";position:absolute;width:12px;height:12px;left:3px;top:3px;background:#6b6b85;border-radius:50%;transition:transform .2s,background .2s;}
.cz-widget-toggle input:checked + .cz-widget-toggle-slider{background:#a78bfa;}
.cz-widget-toggle input:checked + .cz-widget-toggle-slider:before{transform:translateX(14px);background:#fff;}

</style>
</head>
<body class="wp-customizer">
<div id="wcgd-cz">

    <!-- Topbar -->
    <div id="wcgd-topbar">
        <a href="<?php echo esc_url( $back_url ); ?>" class="cz-logo">
            <span class="dashicons dashicons-groups"></span>
            <?php esc_html_e( 'Guest Dashboard Live Customizer', 'customize-my-account-for-woocommerce' ); ?>
        </a>
        <div class="cz-actions">
            <div class="cz-device-btns">
                <button data-device="desktop" class="active" title="Desktop"><span class="dashicons dashicons-desktop" style="font-size:16px;width:16px;height:16px;"></span></button>
                <button data-device="tablet"  title="Tablet"><span class="dashicons dashicons-tablet" style="font-size:16px;width:16px;height:16px;"></span></button>
                <button data-device="mobile"  title="Mobile"><span class="dashicons dashicons-smartphone" style="font-size:16px;width:16px;height:16px;"></span></button>
            </div>
            <span id="cz-save-status"></span>
            <a href="<?php echo esc_url( $back_url ); ?>" class="cz-btn cz-btn-ghost">
                <span class="dashicons dashicons-arrow-left-alt" style="font-size:14px;width:14px;height:14px;"></span>
                <?php esc_html_e( 'Exit', 'customize-my-account-for-woocommerce' ); ?>
            </a>
        </div>
    </div>

    <!-- Body -->
    <div id="wcgd-body">

        <!-- Panel -->
        <div id="wcgd-panel">
            <div id="wcgd-panel-content">

                <!-- STATUS -->
                <div class="cz-accordion" data-accordion="status">
                    <div class="cz-accordion-header" data-target="status">
                        <span class="cz-accordion-title"><span class="dashicons dashicons-admin-settings"></span><?php esc_html_e( 'Status', 'customize-my-account-for-woocommerce' ); ?></span>
                        <span class="cz-accordion-chevron">&#9660;</span>
                    </div>
                    <div class="cz-accordion-body">
                        <div class="cz-group">
                            <div class="cz-field">
                                <label class="cz-label"><?php esc_html_e( 'Enable Guest Dashboard', 'customize-my-account-for-woocommerce' ); ?></label>
                                <div class="cz-toggle-group">
                                    <button class="cz-toggle <?php echo $enabled === '01' ? 'active' : ''; ?>" data-key="guest_dashboard_enable" data-value="01"><?php esc_html_e( 'Yes', 'customize-my-account-for-woocommerce' ); ?></button>
                                    <button class="cz-toggle <?php echo $enabled !== '01' ? 'active' : ''; ?>" data-key="guest_dashboard_enable" data-value="02"><?php esc_html_e( 'No', 'customize-my-account-for-woocommerce' ); ?></button>
                                </div>
                            </div>
                            <?php if ( $page_id ) : ?>
                            <div class="cz-field">
                                <label class="cz-label"><?php esc_html_e( 'Linked Page', 'customize-my-account-for-woocommerce' ); ?></label>
                                <a href="<?php echo esc_url( $preview_url ); ?>" target="_blank" style="font-size:12px;color:#a78bfa;text-decoration:none;display:inline-flex;align-items:center;gap:5px;">
                                    <span class="dashicons dashicons-external" style="font-size:13px;width:13px;height:13px;"></span>
                                    <?php echo esc_html( get_the_title( $page_id ) ); ?>
                                </a>
                            </div>
                            <?php else : ?>
                            <div class="cz-field">
                                <p style="font-size:11px;color:#ef4444;line-height:1.5;"><?php esc_html_e( 'No guest page linked. Go back to the customizer to link or create one.', 'customize-my-account-for-woocommerce' ); ?></p>
                                <a href="<?php echo esc_url( $back_url ); ?>" style="font-size:12px;color:#a78bfa;text-decoration:none;margin-top:6px;display:inline-block;">&larr; <?php esc_html_e( 'Back to customizer', 'customize-my-account-for-woocommerce' ); ?></a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>


                <!-- ENDPOINTS -->
                <div class="cz-accordion" data-accordion="endpoints">
                    <div class="cz-accordion-header" data-target="endpoints">
                        <span class="cz-accordion-title"><span class="dashicons dashicons-networking"></span><?php esc_html_e( 'Endpoints', 'customize-my-account-for-woocommerce' ); ?></span>
                        <span class="cz-accordion-chevron">&#9660;</span>
                    </div>
                    <div class="cz-accordion-body">
                        <div class="cz-group">
                            <p style="font-size:10px;color:#6b6b85;margin-bottom:8px;line-height:1.5;"><?php esc_html_e( 'Drag to reorder. Toggle to show or hide each item on the guest dashboard.', 'customize-my-account-for-woocommerce' ); ?></p>
                            <ul class="cz-sort-list" id="wcgd-ep-list">
                            <?php foreach ( $ordered as $ep_key ) :
                                if ( ! isset( $all_ep[ $ep_key ] ) ) continue;
                                $ep_label   = $all_ep[ $ep_key ];
                                $ep_visible = ! in_array( $ep_key, $hidden_ep, true );
                                $ep_icon    = 'dashicons-arrow-right-alt2';
                                if ( $ep_key === 'dashboard' )     $ep_icon = 'dashicons-dashboard';
                                elseif ( $ep_key === 'orders' )    $ep_icon = 'dashicons-cart';
                                elseif ( $ep_key === 'downloads' ) $ep_icon = 'dashicons-download';
                                elseif ( strpos( $ep_key, 'address' ) !== false ) $ep_icon = 'dashicons-location';
                                elseif ( strpos( $ep_key, 'account' ) !== false ) $ep_icon = 'dashicons-admin-users';
                            ?>
                            <li class="cz-sort-item<?php echo ! $ep_visible ? ' cz-sort-item--hidden' : ''; ?>" data-ep="<?php echo esc_attr( $ep_key ); ?>" draggable="true">
                                <span class="cz-drag-handle" title="<?php esc_attr_e( 'Drag to reorder', 'customize-my-account-for-woocommerce' ); ?>">
                                    <span class="dashicons dashicons-menu" style="font-size:14px;width:14px;height:14px;color:#4b4b6b;"></span>
                                </span>
                                <span class="dashicons <?php echo esc_attr( $ep_icon ); ?>" style="font-size:14px;width:14px;height:14px;color:#6b6b85;flex-shrink:0;"></span>
                                <span class="cz-ep-label"><?php echo esc_html( $ep_label ); ?></span>
                                <label class="cz-ep-toggle" title="<?php esc_attr_e( 'Show / Hide', 'customize-my-account-for-woocommerce' ); ?>">
                                    <input type="checkbox" class="cz-ep-cb" <?php checked( $ep_visible ); ?> data-ep="<?php echo esc_attr( $ep_key ); ?>">
                                    <span class="cz-ep-slider"></span>
                                </label>
                            </li>
                            <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>


                <!-- DASHBOARD WIDGETS -->
                <div class="cz-accordion" data-accordion="widgets">
                    <div class="cz-accordion-header" data-target="widgets">
                        <span class="cz-accordion-title"><span class="dashicons dashicons-welcome-widgets-menus"></span><?php esc_html_e( 'Dashboard Widgets', 'customize-my-account-for-woocommerce' ); ?></span>
                        <span class="cz-accordion-chevron">&#9660;</span>
                    </div>
                    <div class="cz-accordion-body" id="cz-acc-widgets">
                        <div class="cz-group">
                            <div class="cz-group-title"><?php esc_html_e( 'Dashboard Order', 'customize-my-account-for-woocommerce' ); ?></div>
                            <div class="cz-field">
                                <p class="cz-label" style="margin-bottom:8px;"><?php esc_html_e( 'Drag to reorder', 'customize-my-account-for-woocommerce' ); ?></p>
                                <ul id="wcgd-priority-list">
                                    <?php foreach ( $guest_priority_widgets as $pw_key => $pw ) : ?>
                                    <li class="cz-priority-item"
                                        data-key="<?php echo esc_attr( $pw_key ); ?>"
                                        data-toggle-key="<?php echo esc_attr( $pw['toggle_key'] ); ?>"
                                        draggable="true">
                                        <span class="cz-drag-handle"><span class="dashicons dashicons-menu"></span></span>
                                        <span class="dashicons <?php echo esc_attr( $pw['icon'] ); ?>"></span>
                                        <span class="cz-priority-label"><?php echo esc_html( $pw['label'] ); ?></span>
                                        <label class="cz-widget-toggle" title="<?php esc_attr_e( 'Enable / Disable', 'customize-my-account-for-woocommerce' ); ?>">
                                            <input type="checkbox" class="cz-widget-checkbox" <?php checked( $pw['enabled'] ); ?>>
                                            <span class="cz-widget-toggle-slider"></span>
                                        </label>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SHORTCODE 1 -->
                <div class="cz-accordion" id="wcgd-sc-wrap-1" style="<?php echo $guest_sc1_override === '01' ? '' : 'display:none;'; ?>">
                    <div class="cz-accordion-header" data-target="wcgd_sc1">
                        <span class="cz-accordion-title"><span class="dashicons dashicons-shortcode"></span><?php esc_html_e( 'Shortcode 1', 'customize-my-account-for-woocommerce' ); ?></span>
                        <span class="cz-accordion-chevron">&#9660;</span>
                    </div>
                    <div class="cz-accordion-body" id="cz-acc-wcgd_sc1">
                        <div class="cz-group">
                            <label class="cz-label"><?php esc_html_e( 'Shortcode', 'customize-my-account-for-woocommerce' ); ?></label>
                            <input type="text" class="cz-text-input" data-key="guest_sc1_value" value="<?php echo esc_attr( $guest_sc1_value ); ?>" placeholder="[your_shortcode]">
                            <p style="font-size:10px;color:#6b6b85;margin-top:5px;"><?php esc_html_e( 'Enter a shortcode to render on the guest dashboard.', 'customize-my-account-for-woocommerce' ); ?></p>
                        </div>
                    </div>
                </div>

                <!-- SHORTCODE 2 -->
                <div class="cz-accordion" id="wcgd-sc-wrap-2" style="<?php echo $guest_sc2_override === '01' ? '' : 'display:none;'; ?>">
                    <div class="cz-accordion-header" data-target="wcgd_sc2">
                        <span class="cz-accordion-title"><span class="dashicons dashicons-shortcode"></span><?php esc_html_e( 'Shortcode 2', 'customize-my-account-for-woocommerce' ); ?></span>
                        <span class="cz-accordion-chevron">&#9660;</span>
                    </div>
                    <div class="cz-accordion-body" id="cz-acc-wcgd_sc2">
                        <div class="cz-group">
                            <label class="cz-label"><?php esc_html_e( 'Shortcode', 'customize-my-account-for-woocommerce' ); ?></label>
                            <input type="text" class="cz-text-input" data-key="guest_sc2_value" value="<?php echo esc_attr( $guest_sc2_value ); ?>" placeholder="[your_shortcode]">
                            <p style="font-size:10px;color:#6b6b85;margin-top:5px;"><?php esc_html_e( 'Enter a shortcode to render on the guest dashboard.', 'customize-my-account-for-woocommerce' ); ?></p>
                        </div>
                    </div>
                </div>

                <!-- CTA TEXT -->
                <div class="cz-accordion" data-accordion="cta">
                    <div class="cz-accordion-header" data-target="cta">
                        <span class="cz-accordion-title"><span class="dashicons dashicons-editor-quote"></span><?php esc_html_e( 'CTA Text', 'customize-my-account-for-woocommerce' ); ?></span>
                        <span class="cz-accordion-chevron">&#9660;</span>
                    </div>
                    <div class="cz-accordion-body">
                        <div class="cz-group">
                            <div class="cz-field">
                                <label class="cz-label"><?php esc_html_e( 'Login CTA Text', 'customize-my-account-for-woocommerce' ); ?></label>
                                <input type="text" class="cz-text-input" data-key="guest_cta_text" value="<?php echo esc_attr( $cta_text ); ?>" placeholder="<?php esc_attr_e( 'Login to View and Manage Your Orders', 'customize-my-account-for-woocommerce' ); ?>">
                                <p style="font-size:10px;color:#6b6b85;margin-top:5px;line-height:1.5;"><?php esc_html_e( '"Login" at the start will be wrapped in a link automatically.', 'customize-my-account-for-woocommerce' ); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SHORTCODE INFO -->
                <div class="cz-accordion" data-accordion="shortcode">
                    <div class="cz-accordion-header" data-target="shortcode">
                        <span class="cz-accordion-title"><span class="dashicons dashicons-info"></span><?php esc_html_e( 'Page Shortcode', 'customize-my-account-for-woocommerce' ); ?></span>
                        <span class="cz-accordion-chevron">&#9660;</span>
                    </div>
                    <div class="cz-accordion-body">
                        <div class="cz-group">
                            <p style="font-size:11px;color:#a0a0b8;line-height:1.6;margin-bottom:10px;"><?php esc_html_e( 'Place this shortcode on your guest dashboard page:', 'customize-my-account-for-woocommerce' ); ?></p>
                            <code style="display:block;background:#0d0d1a;border:1px solid #2d2d3f;border-radius:6px;padding:9px;font-size:13px;color:#86efac;text-align:center;">[wcmamtx_guest_dashboard]</code>
                        </div>
                    </div>
                </div>

            </div><!-- /panel-content -->

            <!-- Panel footer -->
            <div id="wcgd-panel-footer">
                <a href="<?php echo esc_url( $back_url ); ?>" class="cz-footer-btn cz-footer-btn-outline">
                    <span class="dashicons dashicons-arrow-left-alt" style="font-size:14px;width:14px;height:14px;line-height:1;"></span>
                    <?php esc_html_e( 'Back', 'customize-my-account-for-woocommerce' ); ?>
                </a>
                <?php if ( $page_id ) : ?>
                <a href="<?php echo esc_url( $preview_url ); ?>" target="_blank" class="cz-footer-btn cz-footer-btn-purple">
                    <span class="dashicons dashicons-external" style="font-size:14px;width:14px;height:14px;line-height:1;"></span>
                    <?php esc_html_e( 'Open in tab', 'customize-my-account-for-woocommerce' ); ?>
                </a>
                <?php endif; ?>
            </div>
        </div><!-- /panel -->

        <!-- Preview -->
        <div id="wcgd-preview-wrap">
            <div id="wcgd-preview-bar">
                <span class="dashicons dashicons-groups" style="color:#a78bfa;font-size:16px;width:16px;height:16px;"></span>
                <input type="text" id="wcgd-preview-url" value="<?php echo esc_url( $preview_url ); ?>" readonly>
                <button id="cz-refresh-btn" class="cz-btn cz-btn-ghost" style="padding:5px 10px;" title="Refresh">
                    <span class="dashicons dashicons-update" style="font-size:14px;width:14px;height:14px;"></span>
                </button>
            </div>
            <div id="wcgd-preview-container">
                <div id="wcgd-loader"><div class="cz-spinner"></div></div>
                <iframe id="wcgd-iframe" src="<?php echo esc_url( $preview_url ); ?>"></iframe>
            </div>
        </div>

    </div><!-- /body -->
</div><!-- /wcgd-cz -->

<script>
(function(){
    'use strict';
    var AJAX_URL = <?php echo wp_json_encode( $ajax_url ); ?>;
    var NONCE    = <?php echo wp_json_encode( $nonce ); ?>;
    var PREVIEW  = <?php echo wp_json_encode( $preview_url ); ?>;
    var i18n = {
        saving:       <?php echo wp_json_encode( __( 'Saving…',      'customize-my-account-for-woocommerce' ) ); ?>,
        saved:        <?php echo wp_json_encode( __( 'Saved',         'customize-my-account-for-woocommerce' ) ); ?>,
        errorSaving:  <?php echo wp_json_encode( __( 'Error saving',  'customize-my-account-for-woocommerce' ) ); ?>,
        networkError: <?php echo wp_json_encode( __( 'Network error', 'customize-my-account-for-woocommerce' ) ); ?>,
    };

    var iframe = document.getElementById('wcgd-iframe');
    var loader = document.getElementById('wcgd-loader');
    var status = document.getElementById('cz-save-status');
    var saveTimer;

    function setSaveStatus(state, text) {
        status.className   = state;
        status.textContent = text;
        clearTimeout(saveTimer);
        if (state === 'saved') saveTimer = setTimeout(function(){ status.textContent = ''; status.className = ''; }, 3000);
    }
    function showLoader(){ loader.classList.add('visible'); }
    function hideLoader(){ loader.classList.remove('visible'); }

    function injectIframeCSS() {
        try {
            var iDoc = iframe.contentDocument || iframe.contentWindow.document;
            if (!iDoc) return;
            var existing = iDoc.getElementById('wcgd-injected-css');
            if (existing) existing.parentNode.removeChild(existing);
            var s = iDoc.createElement('style');
            s.id = 'wcgd-injected-css';
            s.textContent = '#wpadminbar{display:none!important;}html{margin-top:0!important;}body{margin-top:0!important;}';
            (iDoc.head || iDoc.documentElement).appendChild(s);
        } catch(e) {}
    }
    iframe.addEventListener('load', function(){ hideLoader(); injectIframeCSS(); });

    function saveOption(key, value, reload) {
        setSaveStatus('saving', i18n.saving);
        var fd = new FormData();
        fd.append('action', 'wcmamtx_guest_customizer_save');
        fd.append('nonce',  NONCE);
        fd.append('key',    key);
        fd.append('value',  value);
        fetch(AJAX_URL, { method: 'POST', body: fd })
            .then(function(r){ return r.json(); })
            .then(function(res){
                if (res.success) {
                    setSaveStatus('saved', i18n.saved);
                    if (reload) { showLoader(); iframe.src = PREVIEW + '?wcgd=' + Date.now(); }
                } else {
                    setSaveStatus('', i18n.errorSaving);
                }
            })
            .catch(function(){ setSaveStatus('', i18n.networkError); });
    }

    // Accordion open/close
    document.querySelectorAll('.cz-accordion-header').forEach(function(hdr){
        hdr.addEventListener('click', function(){
            hdr.closest('.cz-accordion').classList.toggle('open');
        });
    });

    // Toggle buttons (enable/disable)
    document.querySelectorAll('.cz-toggle[data-key]').forEach(function(btn){
        btn.addEventListener('click', function(){
            btn.closest('.cz-toggle-group').querySelectorAll('.cz-toggle').forEach(function(b){ b.classList.remove('active'); });
            btn.classList.add('active');
            saveOption(btn.dataset.key, btn.dataset.value, true);
        });
    });

    // Number inputs (avatar size, card radius) — debounced
    document.querySelectorAll('.cz-number-input[data-key]').forEach(function(inp){
        var t;
        inp.addEventListener('input', function(){
            clearTimeout(t);
            t = setTimeout(function(){ saveOption(inp.dataset.key, inp.value, true); }, 700);
        });
    });

    // Color input — live preview + debounced save
    var colorInp   = document.getElementById('cz-nav-color');
    var colorLabel = document.getElementById('cz-nav-color-label');
    if (colorInp) {
        var ct;
        colorInp.addEventListener('input', function(){
            if (colorLabel) colorLabel.textContent = colorInp.value;
            // Inject live CSS into iframe immediately
            try {
                var iDoc = iframe.contentDocument || iframe.contentWindow.document;
                var el = iDoc.getElementById('wcgd-live-color');
                if (!el) { el = iDoc.createElement('style'); el.id = 'wcgd-live-color'; (iDoc.head || iDoc.documentElement).appendChild(el); }
                el.textContent =
                    '.wcmamtx-guest-dashboard ul.wcmamtx_vertical li.woocommerce-MyAccount-navigation-link--dashboard{background:' + colorInp.value + '!important;border-color:' + colorInp.value + '!important;}' +
                    '.wcmamtx-guest-dashboard ul.wcmamtx_vertical li.woocommerce-MyAccount-navigation-link--dashboard a{color:#fff!important;}';
            } catch(e){}
            clearTimeout(ct);
            ct = setTimeout(function(){ saveOption('guest_nav_active_color', colorInp.value, false); }, 800);
        });
    }

    // CTA text input — debounced save + reload
    document.querySelectorAll('.cz-text-input[data-key]').forEach(function(inp){
        var t;
        inp.addEventListener('input', function(){
            clearTimeout(t);
            t = setTimeout(function(){ saveOption(inp.dataset.key, inp.value, true); }, 800);
        });
    });

    // Device preview
    document.querySelectorAll('.cz-device-btns button').forEach(function(btn){
        btn.addEventListener('click', function(){
            document.querySelectorAll('.cz-device-btns button').forEach(function(b){ b.classList.remove('active'); });
            btn.classList.add('active');
            var c = document.getElementById('wcgd-preview-container');
            c.className = '';
            if (btn.dataset.device !== 'desktop') c.classList.add(btn.dataset.device);
        });
    });

    // Refresh button
    document.getElementById('cz-refresh-btn').addEventListener('click', function(){
        showLoader();
        iframe.src = PREVIEW + '?wcgd=' + Date.now();
    });

    // ── Endpoints: drag-to-reorder + toggle visibility ──
    (function(){
        var AJAX_ARR = '<?php echo esc_js( admin_url( "admin-ajax.php" ) ); ?>';
        var NONCE_A  = <?php echo wp_json_encode( $nonce ); ?>;

        function saveArray(key, vals) {
            var fd = new FormData();
            fd.append('action', 'wcmamtx_guest_customizer_save_array');
            fd.append('nonce',  NONCE_A);
            fd.append('key',    key);
            vals.forEach(function(v){ fd.append('values[]', v); });
            fetch(AJAX_URL, {method:'POST', body:fd})
                .then(function(r){ return r.json(); })
                .then(function(res){ setSaveStatus(res.success ? 'saved' : '', i18n.saved); })
                .catch(function(){ setSaveStatus('', i18n.networkError); });
        }

        function getOrder() {
            return Array.from(document.querySelectorAll('#wcgd-ep-list .cz-sort-item')).map(function(li){ return li.dataset.ep; });
        }
        function getHidden() {
            return Array.from(document.querySelectorAll('#wcgd-ep-list .cz-ep-cb:not(:checked)')).map(function(cb){ return cb.dataset.ep; });
        }
        function persist() {
            setSaveStatus('saving', i18n.saving);
            var order  = getOrder();
            var hidden = getHidden();
            // Save both arrays, then refresh preview once both are done
            var fd1 = new FormData();
            fd1.append('action', 'wcmamtx_guest_customizer_save_array');
            fd1.append('nonce',  NONCE_A);
            fd1.append('key',    'guest_endpoint_order');
            order.forEach(function(v){ fd1.append('values[]', v); });

            var fd2 = new FormData();
            fd2.append('action', 'wcmamtx_guest_customizer_save_array');
            fd2.append('nonce',  NONCE_A);
            fd2.append('key',    'guest_hidden_endpoints');
            hidden.forEach(function(v){ fd2.append('values[]', v); });

            Promise.all([
                fetch(AJAX_URL, {method:'POST', body:fd1}).then(function(r){ return r.json(); }),
                fetch(AJAX_URL, {method:'POST', body:fd2}).then(function(r){ return r.json(); })
            ]).then(function(){
                setSaveStatus('saved', i18n.saved);
                showLoader();
                iframe.src = PREVIEW + '?wcgd=' + Date.now();
            }).catch(function(){ setSaveStatus('', i18n.networkError); });
        }

        // Toggle visibility
        document.querySelectorAll('.cz-ep-cb').forEach(function(cb){
            cb.addEventListener('change', function(){
                var li = cb.closest('.cz-sort-item');
                li.classList.toggle('cz-sort-item--hidden', !cb.checked);
                persist();
            });
        });

        // Drag-to-reorder
        var list = document.getElementById('wcgd-ep-list');
        var dragged = null;

        list.addEventListener('dragstart', function(e){
            dragged = e.target.closest('.cz-sort-item');
            if (!dragged) return;
            dragged.classList.add('cz-dragging');
            e.dataTransfer.effectAllowed = 'move';
        });
        list.addEventListener('dragend', function(){
            if (dragged) { dragged.classList.remove('cz-dragging'); dragged = null; }
            document.querySelectorAll('.cz-sort-item').forEach(function(li){ li.classList.remove('cz-drag-over'); });
            persist();
        });
        list.addEventListener('dragover', function(e){
            e.preventDefault();
            var target = e.target.closest('.cz-sort-item');
            if (!target || target === dragged) return;
            document.querySelectorAll('.cz-sort-item').forEach(function(li){ li.classList.remove('cz-drag-over'); });
            target.classList.add('cz-drag-over');
            var rect = target.getBoundingClientRect();
            var mid  = rect.top + rect.height / 2;
            if (e.clientY < mid) {
                list.insertBefore(dragged, target);
            } else {
                list.insertBefore(dragged, target.nextSibling);
            }
        });
        list.addEventListener('dragleave', function(e){
            var target = e.target.closest('.cz-sort-item');
            if (target) target.classList.remove('cz-drag-over');
        });
        list.addEventListener('drop', function(e){ e.preventDefault(); });
    })();

    // ── Dashboard Widgets: priority drag + shortcode toggle ──
    (function(){
        var list    = document.getElementById('wcgd-priority-list');
        var dragging = null;
        if (!list) return;

        function handleScToggle(cb) {
            var li = cb.closest('.cz-priority-item');
            if (!li) return;
            var tk = li.dataset.toggleKey;
            var wrapId = tk === 'guest_sc1_override' ? 'wcgd-sc-wrap-1' : (tk === 'guest_sc2_override' ? 'wcgd-sc-wrap-2' : null);
            if (!wrapId) return;
            var wrap = document.getElementById(wrapId);
            if (wrap) wrap.style.display = cb.checked ? '' : 'none';
        }

        function savePriorities() {
            var items = list.querySelectorAll('.cz-priority-item');
            var step  = 10;
            var saves = [];
            items.forEach(function(item, index){
                saves.push({ key: item.dataset.key, value: String((index + 1) * step) });
            });
            var chain = Promise.resolve();
            saves.forEach(function(s){
                chain = chain.then(function(){
                    return new Promise(function(resolve){
                        setSaveStatus('saving', i18n.saving);
                        var fd = new FormData();
                        fd.append('action', 'wcmamtx_guest_customizer_save');
                        fd.append('nonce',  NONCE);
                        fd.append('key',    s.key);
                        fd.append('value',  s.value);
                        fetch(AJAX_URL, {method:'POST', body:fd})
                            .then(function(r){ return r.json(); })
                            .then(function(){ resolve(); })
                            .catch(function(){ resolve(); });
                    });
                });
            });
            chain.then(function(){
                setSaveStatus('saved', i18n.saved);
                showLoader();
                iframe.src = PREVIEW + '?wcgd=' + Date.now();
            });
        }

        // Drag events
        list.addEventListener('dragstart', function(e){
            dragging = e.target.closest('.cz-priority-item');
            if (!dragging) return;
            dragging.classList.add('cz-dragging');
            e.dataTransfer.effectAllowed = 'move';
        });
        list.addEventListener('dragend', function(){
            if (dragging) dragging.classList.remove('cz-dragging');
            list.querySelectorAll('.cz-priority-item').forEach(function(i){ i.classList.remove('cz-drag-over'); });
            dragging = null;
            savePriorities();
        });
        list.addEventListener('dragover', function(e){
            e.preventDefault();
            var target = e.target.closest('.cz-priority-item');
            if (!target || target === dragging) return;
            list.querySelectorAll('.cz-priority-item').forEach(function(i){ i.classList.remove('cz-drag-over'); });
            target.classList.add('cz-drag-over');
            var rect = target.getBoundingClientRect();
            if (e.clientY < rect.top + rect.height / 2) {
                list.insertBefore(dragging, target);
            } else {
                list.insertBefore(dragging, target.nextSibling);
            }
        });
        list.addEventListener('drop', function(e){ e.preventDefault(); });

        // Shortcode toggle checkboxes: show/hide SC accordion + save override key
        document.addEventListener('change', function(e){
            var cb = e.target;
            if (!cb.classList.contains('cz-widget-checkbox')) return;
            var li = cb.closest('.cz-priority-item');
            if (!li) return;
            var tk = li.dataset.toggleKey;
            if (tk !== 'guest_sc1_override' && tk !== 'guest_sc2_override' && tk !== 'guest_dashlinks_override') return;
            handleScToggle(cb);
            setSaveStatus('saving', i18n.saving);
            var fd = new FormData();
            fd.append('action', 'wcmamtx_guest_customizer_save');
            fd.append('nonce',  NONCE);
            fd.append('key',    tk);
            fd.append('value',  cb.checked ? '01' : '02');
            fetch(AJAX_URL, {method:'POST', body:fd})
                .then(function(r){ return r.json(); })
                .then(function(res){
                    setSaveStatus(res.success ? 'saved' : '', i18n.saved);
                    if (res.success) {
                        showLoader();
                        iframe.src = PREVIEW + '?wcgd=' + Date.now();
                    }
                })
                .catch(function(){ setSaveStatus('', i18n.networkError); });
        });

        // Init SC accordion visibility on load
        document.querySelectorAll('.cz-widget-checkbox').forEach(function(cb){
            var li = cb.closest('.cz-priority-item');
            if (!li) return;
            var tk = li.dataset.toggleKey;
            if (tk !== 'guest_sc1_override' && tk !== 'guest_sc2_override' && tk !== 'guest_dashlinks_override') return;
            handleScToggle(cb);
        });
    })();

})();
</script>
</body>
</html>
    <?php
    echo ob_get_clean();
    exit;
}
