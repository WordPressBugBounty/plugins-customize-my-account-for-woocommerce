<?php
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'WCMAMTX_ORDERS_CUSTOMIZER_SLUG', 'wcmamtx_orders_customizer' );

// ── Register menu page ──────────────────────────────────────────────────────
add_action( 'admin_menu', function() {
    add_menu_page(
        __( 'Orders Customizer', 'customize-my-account-for-woocommerce' ),
        __( 'Orders', 'customize-my-account-for-woocommerce' ),
        'manage_options',
        WCMAMTX_ORDERS_CUSTOMIZER_SLUG,
        'wcmamtx_orders_customizer_render_page',
        'dashicons-cart',
        58
    );
}, 999 );
add_action( 'admin_head', function() { remove_menu_page( WCMAMTX_ORDERS_CUSTOMIZER_SLUG ); } );

// ── Strip WP admin styles on this page ──────────────────────────────────────
add_action( 'admin_enqueue_scripts', function( $hook ) {
    if ( $hook !== 'toplevel_page_' . WCMAMTX_ORDERS_CUSTOMIZER_SLUG ) return;
    foreach ( ['colors','wp-admin','common','forms','admin-menu','dashboard','list-tables','edit','media','themes','nav-menus','widgets','wp-auth-check'] as $s ) {
        wp_dequeue_style( $s );
    }
}, 99 );

// ── AJAX: save a single wcmamtx_layout key ───────────────────────────────────
add_action( 'wp_ajax_wcmamtx_orders_customizer_save', function() {
    check_ajax_referer( 'wcmamtx_orders_customizer_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
    $key   = isset( $_POST['key'] )   ? sanitize_key( $_POST['key'] ) : '';
    $value = isset( $_POST['value'] ) ? sanitize_text_field( wp_unslash( $_POST['value'] ) ) : '';
    if ( ! $key ) wp_send_json_error( 'Missing key' );
    // Only allow keys relevant to view-order
    $allowed = [ 'order_template_override', 'order_style' ];
    if ( ! in_array( $key, $allowed, true ) ) wp_send_json_error( 'Key not allowed' );
    $layout         = (array) get_option( 'wcmamtx_layout' );
    $layout[ $key ] = $value;
    update_option( 'wcmamtx_layout', $layout );
    wp_cache_delete( 'wcmamtx_layout', 'options' );
    wp_send_json_success( [ 'key' => $key, 'value' => $value ] );
} );

// ── Render page ──────────────────────────────────────────────────────────────
function wcmamtx_orders_customizer_render_page() {
    if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );
    ob_end_clean();
    ob_start();

    $layout      = (array) get_option( 'wcmamtx_layout' );
    $g           = fn( $k, $d ) => isset( $layout[ $k ] ) ? $layout[ $k ] : $d;

    $nonce       = wp_create_nonce( 'wcmamtx_orders_customizer_nonce' );
    $ajax_url    = admin_url( 'admin-ajax.php' );
    $back_url    = admin_url( 'admin.php?page=wcmamtx_frontend_customizer' );

    // Preview URL — use a real view-order page
    $preview_url = wc_get_account_endpoint_url( 'orders' );

    // Current saved values
    $override    = $g( 'order_template_override', '01' );
    $style       = $g( 'order_style', '01' );

    $styles = [
        '01' => __( 'Optimized', 'customize-my-account-for-woocommerce' ),
        '02' => __( 'Default',   'customize-my-account-for-woocommerce' ),
    ];

    ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php esc_html_e( 'Orders Live Customizer', 'customize-my-account-for-woocommerce' ); ?></title>
<link rel="stylesheet" href="<?php echo esc_url( includes_url( 'css/dashicons.min.css' ) ); ?>">
<style>
/* ── Reset ── */
* { box-sizing: border-box; margin: 0; padding: 0; }
#wpwrap,#wpcontent,#wpbody,#wpbody-content { float:none!important; margin:0!important; padding:0!important; width:100%!important; }
#adminmenuwrap,#adminmenuback,#wpadminbar { display:none!important; }
body.wp-customizer { margin:0!important; padding:0!important; }
html,body { height:100%; overflow:hidden; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif; background:#1e1e2e; }
#wcvo-cz { display:flex; flex-direction:column; height:100vh; }

/* ── Topbar ── */
#wcvo-topbar { display:flex; align-items:center; justify-content:space-between; height:48px; background:#1e1e2e; padding:0 16px; flex-shrink:0; border-bottom:1px solid #2d2d3f; z-index:100; }
.cz-logo { display:flex; align-items:center; gap:10px; color:#fff; font-weight:700; font-size:14px; text-decoration:none; }
.cz-logo .dashicons { color:#a78bfa; font-size:20px; }
.cz-actions { display:flex; align-items:center; gap:10px; }
.cz-btn { display:inline-flex; align-items:center; gap:6px; padding:7px 16px; border-radius:6px; border:none; font-size:13px; font-weight:600; cursor:pointer; transition:all .2s; text-decoration:none; }
.cz-btn-ghost { background:transparent; color:#a0a0b8; border:1px solid #2d2d3f; }
.cz-btn-ghost:hover { color:#fff; border-color:#555; }
.cz-btn-success { background:#22c55e; color:#fff; }
.cz-btn-success:hover { background:#16a34a; }
.cz-device-btns { display:flex; gap:6px; }
.cz-device-btns button { background:transparent; border:1px solid #2d2d3f; color:#a0a0b8; width:34px; height:34px; border-radius:6px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .2s; }
.cz-device-btns button.active,.cz-device-btns button:hover { background:#2d2d3f; color:#fff; border-color:#a78bfa; }
#cz-save-status { font-size:12px; color:#a0a0b8; min-width:80px; text-align:right; }
#cz-save-status.saved { color:#22c55e; }
#cz-save-status.saving { color:#f59e0b; }

/* ── Body ── */
#wcvo-body { display:flex!important; flex:1!important; overflow:hidden!important; }

/* ── Panel ── */
#wcvo-panel { width:300px; min-width:240px; background:#13131f; display:flex; flex-direction:column; overflow:hidden; border-right:1px solid #2d2d3f; flex-shrink:0; }
#wcvo-panel-content { flex:1; overflow-y:auto; padding:8px; scrollbar-width:thin; scrollbar-color:#2d2d3f transparent; }
#wcvo-panel-content::-webkit-scrollbar { width:4px; }
#wcvo-panel-content::-webkit-scrollbar-thumb { background:#2d2d3f; border-radius:4px; }

/* ── Panel footer ── */
#wcvo-panel-footer { padding:10px 8px; border-top:1px solid #2d2d3f; display:flex; gap:8px; flex-shrink:0; }
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
.cz-tpl-style-wrap { margin-top:8px; }

/* ── Preview ── */
#wcvo-preview-wrap { flex:1!important; float:none!important; display:flex; flex-direction:column; background:#1a1a2e; overflow:hidden; position:relative; min-width:0; }
#wcvo-preview-bar { display:flex; align-items:center; gap:8px; padding:8px 14px; background:#13131f; border-bottom:1px solid #2d2d3f; flex-shrink:0; }
#wcvo-preview-url { flex:1; background:#1e1e2e; border:1px solid #2d2d3f; color:#a0a0b8; padding:5px 10px; border-radius:20px; font-size:12px; outline:none; }
#wcvo-preview-container { flex:1; display:flex; align-items:center; justify-content:center; padding:12px; overflow:hidden; transition:all .3s; }
#wcvo-preview-container.tablet { max-width:768px; margin:0 auto; }
#wcvo-preview-container.mobile { max-width:560px; margin:0 auto; }
#wcvo-iframe { width:100%; height:100%; border:none; border-radius:10px; background:#fff; box-shadow:0 8px 40px rgba(0,0,0,.5); transition:all .3s; }
#wcvo-preview-container.tablet #wcvo-iframe { width:768px; }
#wcvo-preview-container.mobile #wcvo-iframe { width:560px; border-radius:24px; box-shadow:0 0 0 10px #1e1e2e,0 8px 40px rgba(0,0,0,.5); }
#wcvo-loader { position:absolute; inset:0; background:rgba(13,13,26,.85); display:flex; align-items:center; justify-content:center; z-index:10; opacity:0; pointer-events:none; transition:opacity .2s; }
#wcvo-loader.visible { opacity:1; pointer-events:all; }
.cz-spinner { width:36px; height:36px; border:3px solid #2d2d3f; border-top-color:#a78bfa; border-radius:50%; animation:czSpin .7s linear infinite; }
@keyframes czSpin { to { transform:rotate(360deg); } }
</style>
</head>
<body class="wp-customizer">
<div id="wcvo-cz">

    <!-- Topbar -->
    <div id="wcvo-topbar">
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wcmamtx_advanced_settings' ) ); ?>" class="cz-logo">
            <span class="dashicons dashicons-cart"></span>
            <?php esc_html_e( 'SysBasics Orders Live Customizer', 'customize-my-account-for-woocommerce' ); ?>
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
    <div id="wcvo-body">

        <!-- Panel -->
        <div id="wcvo-panel">
            <div id="wcvo-panel-content">

                <!-- TEMPLATE SOURCE -->
                <div class="cz-accordion open" data-accordion="template">
                    <div class="cz-accordion-header" data-target="template">
                        <span class="cz-accordion-title"><span class="dashicons dashicons-layout"></span><?php esc_html_e( 'Template', 'customize-my-account-for-woocommerce' ); ?></span>
                        <span class="cz-accordion-chevron">&#9660;</span>
                    </div>
                    <div class="cz-accordion-body">
                        <div class="cz-group">
                            <div class="cz-group-title"><?php esc_html_e( 'Source', 'customize-my-account-for-woocommerce' ); ?></div>
                            <div class="cz-field">
                                <div class="cz-toggle-group">
                                    <button class="cz-toggle cz-tpl-override <?php echo $override === '01' ? 'active' : ''; ?>" data-key="order_template_override" data-value="01"><?php esc_html_e( 'This Plugin', 'customize-my-account-for-woocommerce' ); ?></button>
                                    <button class="cz-toggle cz-tpl-override <?php echo $override === '02' ? 'active' : ''; ?>" data-key="order_template_override" data-value="02"><?php esc_html_e( 'No Override', 'customize-my-account-for-woocommerce' ); ?></button>
                                </div>
                            </div>
                            <div class="cz-field cz-tpl-style-wrap" style="<?php echo $override === '01' ? '' : 'display:none;'; ?>">
                                <label class="cz-label"><?php esc_html_e( 'Style', 'customize-my-account-for-woocommerce' ); ?></label>
                                <div class="cz-toggle-group">
                                    <?php foreach ( $styles as $sv => $sl ) : ?>
                                    <button class="cz-toggle cz-tpl-style <?php echo $style === $sv ? 'active' : ''; ?>" data-key="order_style" data-value="<?php echo esc_attr( $sv ); ?>"><?php echo esc_html( $sl ); ?></button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- PRO FEATURES -->
                <div class="cz-accordion">
                    <div class="cz-accordion-header">
                        <span class="cz-accordion-title">
                            <span class="dashicons dashicons-star-filled" style="color:#f59e0b;"></span>
                            <?php esc_html_e( 'Pro Features', 'customize-my-account-for-woocommerce' ); ?>
                            <span style="font-size:10px;background:#4c1d95;color:#c4b5fd;padding:2px 7px;border-radius:20px;font-weight:700;margin-left:4px;">PRO</span>
                        </span>
                        <span class="cz-accordion-chevron">&#9660;</span>
                    </div>
                    <div class="cz-accordion-body" style="padding:0;">
                        <div style="padding:12px;">

                            <div style="background:#0f0f1a;border:1px solid #2d2d3f;border-radius:8px;overflow:hidden;margin-bottom:10px;">
                                <div style="padding:10px 12px;border-bottom:1px solid #2d2d3f;">
                                    <p style="font-size:11px;font-weight:700;color:#a78bfa;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px;"><?php esc_html_e( 'Column Visibility', 'customize-my-account-for-woocommerce' ); ?></p>
                                    <p style="font-size:11px;color:#6b6b85;line-height:1.6;margin-bottom:8px;"><?php esc_html_e( 'Show or hide columns in the Orders table — date, status, total, actions and more.', 'customize-my-account-for-woocommerce' ); ?></p>
                                    <div style="display:flex;flex-direction:column;gap:5px;">
                                        <?php
                                        $ord_cols = [
                                            'dashicons-calendar-alt' => __( 'Order Date',    'customize-my-account-for-woocommerce' ),
                                            'dashicons-tag'          => __( 'Order Status',  'customize-my-account-for-woocommerce' ),
                                            'dashicons-money-alt'    => __( 'Order Total',   'customize-my-account-for-woocommerce' ),
                                            'dashicons-cart'         => __( 'Product Names', 'customize-my-account-for-woocommerce' ),
                                            'dashicons-button'       => __( 'Actions Column','customize-my-account-for-woocommerce' ),
                                        ];
                                        foreach ( $ord_cols as $icon => $label ) : ?>
                                        <div style="display:flex;align-items:center;justify-content:space-between;">
                                            <span style="display:flex;align-items:center;gap:5px;font-size:11px;color:#a0a0b8;">
                                                <span class="dashicons <?php echo esc_attr($icon); ?>" style="font-size:13px;width:13px;height:13px;color:#6b6b85;"></span>
                                                <?php echo esc_html($label); ?>
                                            </span>
                                            <span style="width:28px;height:16px;background:#2d2d3f;border-radius:16px;display:inline-flex;align-items:center;padding:2px;opacity:.5;"><span style="width:12px;height:12px;background:#6b6b85;border-radius:50%;display:inline-block;"></span></span>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div style="padding:10px 12px;border-bottom:1px solid #2d2d3f;">
                                    <p style="font-size:11px;font-weight:700;color:#a78bfa;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px;"><?php esc_html_e( 'Drag & Drop Reorder', 'customize-my-account-for-woocommerce' ); ?></p>
                                    <p style="font-size:11px;color:#6b6b85;line-height:1.6;margin-bottom:8px;"><?php esc_html_e( 'Reorder the columns of the Orders table by dragging them into your preferred sequence.', 'customize-my-account-for-woocommerce' ); ?></p>
                                    <div style="display:flex;flex-direction:column;gap:4px;opacity:.5;">
                                        <?php
                                        $ord_order_rows = [
                                            __( 'Order Number', 'customize-my-account-for-woocommerce' ),
                                            __( 'Order Date',   'customize-my-account-for-woocommerce' ),
                                            __( 'Order Status', 'customize-my-account-for-woocommerce' ),
                                        ];
                                        foreach ( $ord_order_rows as $row ) : ?>
                                        <div style="display:flex;align-items:center;gap:6px;background:#1e1e2e;border:1px solid #2d2d3f;border-radius:5px;padding:5px 8px;">
                                            <span class="dashicons dashicons-menu" style="font-size:13px;width:13px;height:13px;color:#6b6b85;"></span>
                                            <span style="font-size:11px;color:#a0a0b8;"><?php echo esc_html($row); ?></span>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div style="padding:10px 12px;">
                                    <p style="font-size:11px;font-weight:700;color:#a78bfa;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px;"><?php esc_html_e( 'Custom Shortcode Row', 'customize-my-account-for-woocommerce' ); ?></p>
                                    <p style="font-size:11px;color:#6b6b85;line-height:1.6;margin-bottom:8px;"><?php esc_html_e( 'Append a custom shortcode row below each order. Supports dynamic order attributes:', 'customize-my-account-for-woocommerce' ); ?></p>
                                    <div style="background:#0d0d1a;border:1px solid #2d2d3f;border-radius:6px;padding:8px 10px;font-size:11px;color:#86efac;font-family:monospace;line-height:1.8;opacity:.6;">
                                        [my_tracking order_id="{order_id}"<br>
                                        &nbsp;&nbsp;status="{order_status}"<br>
                                        &nbsp;&nbsp;date="{order_date}"]  
                                    </div>
                                </div>
                            </div>

                            <a href="https://sysbasics.com/go/customize/" target="_blank" style="display:flex;align-items:center;justify-content:center;gap:6px;width:100%;padding:9px 14px;background:#7c3aed;color:#fff;border-radius:8px;font-size:12px;font-weight:700;text-decoration:none;transition:background .2s;" onmouseover="this.style.background='#6d28d9'" onmouseout="this.style.background='#7c3aed'">
                                &#9889; <?php esc_html_e( 'Unlock Pro Features', 'customize-my-account-for-woocommerce' ); ?>
                            </a>

                        </div>
                    </div>
                </div>

                <!-- TEMPLATE OVERRIDE INFO -->
                <div class="cz-accordion" id="wcvo-override-accordion" data-accordion="override" style="<?php echo $override === '01' ? '' : 'display:none;'; ?>">
                    <div class="cz-accordion-header" data-target="override">
                        <span class="cz-accordion-title"><span class="dashicons dashicons-media-code"></span><?php esc_html_e( 'Child Theme Override', 'customize-my-account-for-woocommerce' ); ?></span>
                        <span class="cz-accordion-chevron">&#9660;</span>
                    </div>
                    <div class="cz-accordion-body">
                        <div class="cz-group">
                            <p style="font-size:11px;color:#a0a0b8;line-height:1.6;margin-bottom:10px;"><?php esc_html_e( 'You can override this template from your child theme. Copy the file from:', 'customize-my-account-for-woocommerce' ); ?></p>
                            <code id="wcvo-tpl-source" style="display:block;background:#0d0d1a;border:1px solid #2d2d3f;border-radius:6px;padding:9px;font-size:11px;color:#86efac;line-height:1.6;word-break:break-all;margin-bottom:10px;"><?php echo esc_html( wcmamtx_PLUGIN_URL . 'templates/myaccount/order/' . $style . '.php' ); ?></code>
                            <p style="font-size:11px;color:#a0a0b8;line-height:1.6;margin-bottom:10px;"><?php esc_html_e( 'and paste it into your child theme at:', 'customize-my-account-for-woocommerce' ); ?></p>
                            <code id="wcvo-tpl-dest" style="display:block;background:#0d0d1a;border:1px solid #2d2d3f;border-radius:6px;padding:9px;font-size:11px;color:#fbbf24;line-height:1.6;word-break:break-all;"><?php echo esc_html( get_stylesheet_directory() . '/wcmamtx_template/order/' . $style . '.php' ); ?></code>
                        </div>
                    </div>
                </div>

            </div><!-- /panel-content -->

            <!-- Panel footer -->
            <div id="wcvo-panel-footer">
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wcmamtx_advanced_settings&tab=wcmamtx_layout' ) ); ?>" target="_blank" class="cz-footer-btn cz-footer-btn-outline">
                    <span class="dashicons dashicons-admin-settings" style="font-size:14px;width:14px;height:14px;line-height:1;"></span>
                    <?php esc_html_e( 'All Settings', 'customize-my-account-for-woocommerce' ); ?>
                </a>
                <a href="<?php echo esc_url( $preview_url ); ?>" target="_blank" class="cz-footer-btn cz-footer-btn-purple">
                    <span class="dashicons dashicons-external" style="font-size:14px;width:14px;height:14px;line-height:1;"></span>
                    <?php esc_html_e( 'Open in tab', 'customize-my-account-for-woocommerce' ); ?>
                </a>
            </div>
        </div><!-- /panel -->

        <!-- Preview -->
        <div id="wcvo-preview-wrap">
            <div id="wcvo-preview-bar">
                <span class="dashicons dashicons-cart" style="color:#a78bfa;font-size:16px;width:16px;height:16px;"></span>
                <input type="text" id="wcvo-preview-url" value="<?php echo esc_url( $preview_url ); ?>" readonly>
                <button id="cz-refresh-btn" class="cz-btn cz-btn-ghost" style="padding:5px 10px;" title="Refresh">
                    <span class="dashicons dashicons-update" style="font-size:14px;width:14px;height:14px;"></span>
                </button>
            </div>
            <div id="wcvo-preview-container">
                <div id="wcvo-loader"><div class="cz-spinner"></div></div>
                <iframe id="wcvo-iframe" src="<?php echo esc_url( $preview_url ); ?>"></iframe>
            </div>
        </div>

    </div><!-- /body -->
</div><!-- /wcvo-cz -->

<script>
(function(){
    'use strict';
    var AJAX_URL = <?php echo wp_json_encode( $ajax_url ); ?>;
    var NONCE    = <?php echo wp_json_encode( $nonce ); ?>;
    var PREVIEW  = <?php echo wp_json_encode( $preview_url ); ?>;
    var i18n = {
        saving:       <?php echo wp_json_encode( __( 'Saving…',       'customize-my-account-for-woocommerce' ) ); ?>,
        saved:        <?php echo wp_json_encode( __( 'Saved',          'customize-my-account-for-woocommerce' ) ); ?>,
        errorSaving:  <?php echo wp_json_encode( __( 'Error saving',   'customize-my-account-for-woocommerce' ) ); ?>,
        networkError: <?php echo wp_json_encode( __( 'Network error',  'customize-my-account-for-woocommerce' ) ); ?>,
    };

    var iframe = document.getElementById('wcvo-iframe');
    var loader = document.getElementById('wcvo-loader');
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

    // Inject CSS into iframe to hide admin bar
    function injectIframeCSS() {
        try {
            var iDoc = iframe.contentDocument || iframe.contentWindow.document;
            if (!iDoc) return;
            var existing = iDoc.getElementById('wcvo-injected-css');
            if (existing) existing.parentNode.removeChild(existing);
            var s = iDoc.createElement('style');
            s.id = 'wcvo-injected-css';
            s.textContent = '#wpadminbar{display:none!important;}html{margin-top:0!important;}body{margin-top:0!important;}';
            (iDoc.head || iDoc.documentElement).appendChild(s);
        } catch(e) {}
    }
    iframe.addEventListener('load', function(){ hideLoader(); injectIframeCSS(); });

    // Save option + optional iframe reload
    function saveOption(key, value, reload) {
        setSaveStatus('saving', i18n.saving);
        var fd = new FormData();
        fd.append('action', 'wcmamtx_orders_customizer_save');
        fd.append('nonce',  NONCE);
        fd.append('key',    key);
        fd.append('value',  value);
        fetch(AJAX_URL, { method: 'POST', body: fd })
            .then(function(r){ return r.json(); })
            .then(function(res){
                if (res.success) {
                    setSaveStatus('saved', i18n.saved);
                    if (reload) { showLoader(); iframe.src = PREVIEW + '?wco_orders=' + Date.now(); }
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

    // Template override toggles
    document.querySelectorAll('.cz-tpl-override').forEach(function(btn){
        btn.addEventListener('click', function(){
            btn.closest('.cz-toggle-group').querySelectorAll('.cz-toggle').forEach(function(b){ b.classList.remove('active'); });
            btn.classList.add('active');
            // Show/hide style row
            var wrap = btn.closest('.cz-accordion-body').querySelector('.cz-tpl-style-wrap');
            if (wrap) wrap.style.display = btn.dataset.value === '01' ? '' : 'none';
            // Save then reload
            saveOption(btn.dataset.key, btn.dataset.value, true);
        });
    });

    // Show/hide Child Theme Override accordion based on source toggle
    var overrideAcc = document.getElementById('wcvo-override-accordion');
    document.querySelectorAll('.cz-tpl-override').forEach(function(btn){
        btn.addEventListener('click', function(){
            if (overrideAcc) overrideAcc.style.display = btn.dataset.value === '01' ? '' : 'none';
        });
    });

    // Style toggles
    document.querySelectorAll('.cz-tpl-style').forEach(function(btn){
        btn.addEventListener('click', function(){
            btn.closest('.cz-toggle-group').querySelectorAll('.cz-toggle').forEach(function(b){ b.classList.remove('active'); });
            btn.classList.add('active');
            saveOption(btn.dataset.key, btn.dataset.value, true);
        });
    });

    // Device preview
    document.querySelectorAll('.cz-device-btns button').forEach(function(btn){
        btn.addEventListener('click', function(){
            document.querySelectorAll('.cz-device-btns button').forEach(function(b){ b.classList.remove('active'); });
            btn.classList.add('active');
            var container = document.getElementById('wcvo-preview-container');
            container.className = '';
            if (btn.dataset.device !== 'desktop') container.classList.add(btn.dataset.device);
        });
    });

    // Update template override paths when style changes
    var tplSource = document.getElementById('wcvo-tpl-source');
    var tplDest   = document.getElementById('wcvo-tpl-dest');
    var pluginUrl   = <?php echo wp_json_encode( wcmamtx_PLUGIN_URL . 'templates/myaccount/order/' ); ?>;
    var themeDir    = <?php echo wp_json_encode( get_stylesheet_directory() . '/wcmamtx_template/order/' ); ?>;
    function updateTplPaths(styleNum) {
        if (tplSource) tplSource.textContent = pluginUrl + styleNum + '.php';
        if (tplDest)   tplDest.textContent   = themeDir  + styleNum + '.php';
    }
    document.querySelectorAll('.cz-tpl-style').forEach(function(btn) {
        btn.addEventListener('click', function() {
            updateTplPaths(btn.dataset.value);
        });
    });

    // Refresh button
    document.getElementById('cz-refresh-btn').addEventListener('click', function(){
        showLoader();
        iframe.src = PREVIEW + '?wco_orders=' + Date.now();
    });

})();
</script>
</body>
</html>
    <?php
    echo ob_get_clean();
    exit;
}
