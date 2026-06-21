<?php
/**
 * Shipment Tracking Feature for Customize My Account for WooCommerce
 * Hooked via filters/actions – no core file edits needed.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ─────────────────────────────────────────────────────────────
// 1. REGISTER layout option key
// ─────────────────────────────────────────────────────────────
define( 'WCMAMTX_TRACKING_OPT', 'wcmamtx_layout' );

function wcmamtx_tracking_enabled() {
    $layout = (array) get_option( WCMAMTX_TRACKING_OPT );
    return isset( $layout['shipment_tracking_override'] ) && $layout['shipment_tracking_override'] === '01';
}

// ─────────────────────────────────────────────────────────────
// 2. ADD VERTICAL TAB BUTTON in Design & Layout sidebar
// ─────────────────────────────────────────────────────────────
add_filter( 'wcmamtx_add_new_layout_settings_tab', function( $html ) {
    $html .= '<button class="wcmamtx_tab_link" data-tab="shipment_tracking">
        <span class="dashicons dashicons-location"></span>
        ' . esc_html__( 'Shipment Tracking', 'customize-my-account-for-woocommerce' ) . '
    </button>';
    return $html;
} );

// ─────────────────────────────────────────────────────────────
// 3. ADD TAB CONTENT PANE
// ─────────────────────────────────────────────────────────────
add_filter( 'wcmamtx_add_new_layout_settings_content', function( $html ) {
    $layout   = (array) get_option( WCMAMTX_TRACKING_OPT );
    $override = isset( $layout['shipment_tracking_override'] ) ? $layout['shipment_tracking_override'] : '02';
    $is_on    = ( $override === '01' );

    ob_start();
    ?>
    <div class="tab-pane" id="shipment_tracking">
        <div class="wcmamtx-setting-card wcmamtx_shipment_tracking_backend">

            <div class="wcmamtx-card-header">
                <div>
                    <h2><?php esc_html_e( 'Shipment Tracking', 'customize-my-account-for-woocommerce' ); ?></h2>
                    <p><?php esc_html_e( 'Add courier name and tracking URL fields to orders and display tracking info in the WooCommerce orders list and view order page.', 'customize-my-account-for-woocommerce' ); ?></p>

                    <select
                        class="wcmamtx_layout_select_override wcmamtx_layout_order_select_override"
                        name="wcmamtx_layout[shipment_tracking_override]"
                        id="wcmamtx_shipment_tracking_select"
                    >
                        <option value="01" <?php selected( $override, '01' ); ?>>
                            <?php esc_html_e( 'Enable', 'customize-my-account-for-woocommerce' ); ?>
                        </option>
                        <option value="02" <?php selected( $override, '02' ); ?>>
                            <?php esc_html_e( 'Disable', 'customize-my-account-for-woocommerce' ); ?>
                        </option>
                    </select>
                </div>
            </div>

            <div class="wcmamtx-card-body" style="<?php echo $is_on ? 'display:block;' : 'display:none;'; ?>">

                <div class="form-group">
                    <h4 style="margin-top:0;"><?php esc_html_e( 'What this feature does', 'customize-my-account-for-woocommerce' ); ?></h4>
                    <ul style="margin:0 0 0 1.2em; padding:0; color:#555; font-size:13px; line-height:1.8;">
                        <li> <span class="dashicons dashicons-yes-alt" style="color:#00a32a; vertical-align:middle;"></span><?php esc_html_e( 'Adds Courier Name and Tracking URL fields to the order edit page in WooCommerce admin.', 'customize-my-account-for-woocommerce' ); ?></li>
                        <li> <span class="dashicons dashicons-yes-alt" style="color:#00a32a; vertical-align:middle;"></span><?php esc_html_e( 'Adds a Tracking column on the WooCommerce orders list (admin.php?page=wc-orders).', 'customize-my-account-for-woocommerce' ); ?></li>
                        <li> <span class="dashicons dashicons-yes-alt" style="color:#00a32a; vertical-align:middle;"></span><?php esc_html_e( 'Shows courier name and a clickable tracking link when info is saved.', 'customize-my-account-for-woocommerce' ); ?></li>
                        <li> <span class="dashicons dashicons-yes-alt" style="color:#00a32a; vertical-align:middle;"></span><?php esc_html_e( 'Shows a quick Add Tracking button that opens an AJAX modal when no info is saved yet.', 'customize-my-account-for-woocommerce' ); ?></li>
                        <li> <span class="dashicons dashicons-yes-alt" style="color:#00a32a; vertical-align:middle;"></span><?php esc_html_e( 'It shows tracking info on orders page and view order page if it exits.', 'customize-my-account-for-woocommerce' ); ?></li>
                    </ul>
                </div>
                
                <div class="form-group">
                    <h4 style="margin-top:0;"><?php esc_html_e( 'What this feature does', 'customize-my-account-for-woocommerce' ); ?></h4>
                    <ul style="margin:0 0 0 1.2em; padding:0; color:#555; font-size:13px; line-height:1.8;">
                        <li> <span class="dashicons dashicons-no-alt" style="color:#00a32a; vertical-align:middle;"></span><?php esc_html_e( 'It does not perform any API check with your courier partner.', 'customize-my-account-for-woocommerce' ); ?></li>
                        <li> <span class="dashicons dashicons-no-alt" style="color:#00a32a; vertical-align:middle;"></span><?php esc_html_e( 'It can not detect weather shipment is actually delivered or in transit. It simply provides Easy UI to attach shipment tracking detail to order.', 'customize-my-account-for-woocommerce' ); ?></li>
                        <li> <span class="dashicons dashicons-no-alt" style="color:#00a32a; vertical-align:middle;"></span><?php esc_html_e( 'It does not email customer if shop owners adds shipping data to their order.', 'customize-my-account-for-woocommerce' ); ?></li>
                    </ul>
                </div>


            </div>
        </div>
    </div>
    <?php
    return $html . ob_get_clean();
} );

// ─────────────────────────────────────────────────────────────
// 4. ORDER EDIT PAGE – add Tracking fields panel
// ─────────────────────────────────────────────────────────────
add_action( 'add_meta_boxes', function() {
    if ( ! wcmamtx_tracking_enabled() ) return;

    // Works for both legacy CPT orders and HPOS orders
    $screens = array( 'shop_order', wc_get_page_screen_id( 'shop-order' ) );
    foreach ( $screens as $screen ) {
        add_meta_box(
            'wcmamtx_shipment_tracking_box',
            __( 'Shipment Tracking', 'customize-my-account-for-woocommerce' ),
            'wcmamtx_shipment_tracking_meta_box_cb',
            $screen,
            'side',
            'default'
        );
    }
} );

function wcmamtx_shipment_tracking_meta_box_cb( $post_or_order ) {
    $order_id = is_a( $post_or_order, 'WC_Order' ) ? $post_or_order->get_id() : $post_or_order->ID;
    $courier  = get_post_meta( $order_id, '_wcmamtx_courier_name', true );
    $url      = get_post_meta( $order_id, '_wcmamtx_tracking_url', true );
    wp_nonce_field( 'wcmamtx_tracking_save_' . $order_id, 'wcmamtx_tracking_nonce' );
    ?>
    <style>
        .wcmamtx-tracking-field { margin-bottom: 12px; }
        .wcmamtx-tracking-field label { display:block; font-weight:600; margin-bottom:4px; font-size:12px; color:#1d2327; }
        .wcmamtx-tracking-field input { width:100%; box-sizing:border-box; }
        #wcmamtx_tracking_saved_info { background:#f0f6ff; border:1px solid #b8d4f8; border-radius:4px; padding:8px 10px; margin-top:8px; font-size:12px; display:none; }
        #wcmamtx_tracking_saved_info a { color:#2271b1; word-break:break-all; }
    </style>
    <div class="wcmamtx-tracking-field">
        <label for="wcmamtx_courier_name"><?php esc_html_e( 'Courier Name', 'customize-my-account-for-woocommerce' ); ?></label>
        <input
            type="text"
            id="wcmamtx_courier_name"
            name="wcmamtx_courier_name"
            value="<?php echo esc_attr( $courier ); ?>"
            placeholder="<?php esc_attr_e( 'e.g. FedEx, DHL, UPS', 'customize-my-account-for-woocommerce' ); ?>"
            class="regular-text"
        />
    </div>
    <div class="wcmamtx-tracking-field">
        <label for="wcmamtx_tracking_url"><?php esc_html_e( 'Tracking URL', 'customize-my-account-for-woocommerce' ); ?></label>
        <input
            type="url"
            id="wcmamtx_tracking_url"
            name="wcmamtx_tracking_url"
            value="<?php echo esc_attr( $url ); ?>"
            placeholder="https://"
            class="regular-text"
        />
    </div>
    <?php if ( $courier || $url ) : ?>
    <div id="wcmamtx_tracking_saved_info" style="display:block;">
        <?php if ( $courier ) : ?>
            <strong><?php esc_html_e( 'Courier:', 'customize-my-account-for-woocommerce' ); ?></strong> <?php echo esc_html( $courier ); ?><br>
        <?php endif; ?>
        <?php if ( $url ) : ?>
            <strong><?php esc_html_e( 'Track:', 'customize-my-account-for-woocommerce' ); ?></strong>
            <a href="<?php echo esc_url( $url ); ?>" target="_blank"><?php echo esc_url( $url ); ?></a>
        <?php endif; ?>
    </div>
    <?php endif;
}

// Save meta box (legacy CPT)
add_action( 'save_post_shop_order', 'wcmamtx_save_tracking_fields', 10, 1 );
// Save for HPOS
add_action( 'woocommerce_process_shop_order_meta', 'wcmamtx_save_tracking_fields', 10, 1 );

function wcmamtx_save_tracking_fields( $order_id ) {
    if ( ! isset( $_POST['wcmamtx_tracking_nonce'] ) ) return;
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wcmamtx_tracking_nonce'] ) ), 'wcmamtx_tracking_save_' . $order_id ) ) return;
    if ( ! current_user_can( 'edit_shop_orders' ) ) return;

    if ( isset( $_POST['wcmamtx_courier_name'] ) ) {
        update_post_meta( $order_id, '_wcmamtx_courier_name', sanitize_text_field( wp_unslash( $_POST['wcmamtx_courier_name'] ) ) );
    }
    if ( isset( $_POST['wcmamtx_tracking_url'] ) ) {
        update_post_meta( $order_id, '_wcmamtx_tracking_url', esc_url_raw( wp_unslash( $_POST['wcmamtx_tracking_url'] ) ) );
    }
}

// ─────────────────────────────────────────────────────────────
// 5. ORDERS LIST COLUMN
// ─────────────────────────────────────────────────────────────

// Register the column
add_filter( 'manage_woocommerce_page_wc-orders_columns', 'wcmamtx_add_tracking_column' );
add_filter( 'manage_edit-shop_order_columns',             'wcmamtx_add_tracking_column' );

function wcmamtx_add_tracking_column( $columns ) {
    if ( ! wcmamtx_tracking_enabled() ) return $columns;
    // Insert before the last column (actions)
    $new = array();
    foreach ( $columns as $key => $label ) {
        if ( $key === 'wc_actions' ) {
            $new['wcmamtx_tracking'] = __( 'Tracking', 'customize-my-account-for-woocommerce' );
        }
        $new[ $key ] = $label;
    }
    // If wc_actions wasn't found, append
    if ( ! array_key_exists( 'wcmamtx_tracking', $new ) ) {
        $new['wcmamtx_tracking'] = __( 'Tracking', 'customize-my-account-for-woocommerce' );
    }
    return $new;
}

// Render cell content (HPOS)
add_action( 'manage_woocommerce_page_wc-orders_custom_column', 'wcmamtx_render_tracking_column', 10, 2 );
// Render cell content (legacy CPT)
add_action( 'manage_shop_order_posts_custom_column', 'wcmamtx_render_tracking_column_legacy', 10, 2 );

function wcmamtx_render_tracking_column( $column, $order ) {
    if ( $column !== 'wcmamtx_tracking' ) return;
    if ( ! wcmamtx_tracking_enabled() ) return;
    $order_id = is_a( $order, 'WC_Order' ) ? $order->get_id() : (int) $order;
    wcmamtx_output_tracking_cell( $order_id );
}

function wcmamtx_render_tracking_column_legacy( $column, $post_id ) {
    if ( $column !== 'wcmamtx_tracking' ) return;
    if ( ! wcmamtx_tracking_enabled() ) return;
    wcmamtx_output_tracking_cell( $post_id );
}

function wcmamtx_output_tracking_cell( $order_id ) {
    $courier = get_post_meta( $order_id, '_wcmamtx_courier_name', true );
    $url     = get_post_meta( $order_id, '_wcmamtx_tracking_url', true );
    $nonce   = wp_create_nonce( 'wcmamtx_tracking_ajax_' . $order_id );

    if ( $courier || $url ) {
        echo '<div class="wcmamtx-tracking-info">';
        if ( $courier ) {
            echo '<span class="wcmamtx-courier-name"><strong>' . esc_html( $courier ) . '</strong></span>';
        }
        if ( $url ) {
            echo '<br><a href="' . esc_url( $url ) . '" target="_blank" class="button button-small" style="margin-top:4px;">';
            echo '<span class="dashicons dashicons-location" style="vertical-align:middle;margin-right:3px;font-size:14px;"></span>';
            echo esc_html__( 'Track', 'customize-my-account-for-woocommerce' );
            echo '</a>';
        }
        // Edit button - opens the modal pre-filled with existing values
        echo '<button type="button"'
            . ' class="button button-small wcmamtx-edit-tracking-btn"'
            . ' style="margin-top:4px;margin-left:4px;"'
            . ' title="' . esc_attr__( 'Edit tracking info', 'customize-my-account-for-woocommerce' ) . '"'
            . ' data-order-id="' . esc_attr( $order_id ) . '"'
            . ' data-nonce="' . esc_attr( $nonce ) . '"'
            . ' data-courier="' . esc_attr( $courier ) . '"'
            . ' data-url="' . esc_attr( $url ) . '"'
            . '><span class="dashicons dashicons-edit" style="vertical-align:middle;font-size:14px;line-height:1.8;"></span></button>';
        echo '</div>';
    } else {
        echo '<button type="button"'
            . ' class="button button-small wcmamtx-add-tracking-btn"'
            . ' data-order-id="' . esc_attr( $order_id ) . '"'
            . ' data-nonce="' . esc_attr( $nonce ) . '"'
            . '><span class="dashicons dashicons-plus-alt2" style="vertical-align:middle;font-size:14px;"></span> '
            . esc_html__( 'Add Tracking', 'customize-my-account-for-woocommerce' )
            . '</button>';
    }
}

// ─────────────────────────────────────────────────────────────
// 6. AJAX – save tracking from modal
// ─────────────────────────────────────────────────────────────
add_action( 'wp_ajax_wcmamtx_save_tracking_ajax', function() {
    $order_id = isset( $_POST['order_id'] ) ? absint( $_POST['order_id'] ) : 0;
    if ( ! $order_id ) wp_send_json_error( 'Invalid order.' );

    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wcmamtx_tracking_ajax_' . $order_id ) ) {
        wp_send_json_error( 'Security check failed.' );
    }
    if ( ! current_user_can( 'edit_shop_orders' ) ) {
        wp_send_json_error( 'Permission denied.' );
    }

    $courier = isset( $_POST['courier'] ) ? sanitize_text_field( wp_unslash( $_POST['courier'] ) ) : '';
    $url     = isset( $_POST['tracking_url'] ) ? esc_url_raw( wp_unslash( $_POST['tracking_url'] ) ) : '';

    update_post_meta( $order_id, '_wcmamtx_courier_name', $courier );
    update_post_meta( $order_id, '_wcmamtx_tracking_url', $url );

    wp_send_json_success( array(
        'courier' => $courier,
        'url'     => $url,
        'message' => __( 'Tracking info saved!', 'customize-my-account-for-woocommerce' ),
    ) );
} );

// ─────────────────────────────────────────────────────────────
// 7. ENQUEUE modal CSS/JS on orders list page
// ─────────────────────────────────────────────────────────────
add_action( 'admin_footer', function() {
    if ( ! wcmamtx_tracking_enabled() ) return;

    // Only on the orders list screen
    $screen = get_current_screen();
    if ( ! $screen ) return;
    $is_orders = (
        $screen->id === 'woocommerce_page_wc-orders' ||
        $screen->id === 'edit-shop_order'
    );
    if ( ! $is_orders ) return;

    ?>
    <style>
        /* ── Tracking column ── */
        .wcmamtx-courier-name { font-size: 12px; color: #1d2327; }
        .wcmamtx-add-tracking-btn { white-space: nowrap; }
        .wcmamtx-add-tracking-btn .dashicons { line-height: 1.8; }
        /* ── Modal overlay ── */
        #wcmamtx-tracking-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.55);
            z-index: 99999;
            align-items: center;
            justify-content: center;
        }
        #wcmamtx-tracking-modal-overlay.wcmamtx-modal-open { display: flex; }
        #wcmamtx-tracking-modal {
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 8px 40px rgba(0,0,0,.22);
            width: 420px;
            max-width: 96vw;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        #wcmamtx-tracking-modal .wcmamtx-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid #e5e5e5;
        }
        #wcmamtx-tracking-modal .wcmamtx-modal-header h3 {
            margin: 0;
            font-size: 15px;
            font-weight: 600;
            color: #1d2327;
        }
        #wcmamtx-tracking-modal .wcmamtx-modal-close {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 20px;
            color: #787c82;
            line-height: 1;
            padding: 0;
        }
        #wcmamtx-tracking-modal .wcmamtx-modal-close:hover { color: #d63638; }
        #wcmamtx-tracking-modal .wcmamtx-modal-body { padding: 20px; }
        #wcmamtx-tracking-modal .wcmamtx-field { margin-bottom: 14px; }
        #wcmamtx-tracking-modal .wcmamtx-field label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #1d2327;
        }
        #wcmamtx-tracking-modal .wcmamtx-field input {
            width: 100%;
            box-sizing: border-box;
            height: 36px;
            border: 1px solid #8c8f94;
            border-radius: 3px;
            padding: 0 10px;
            font-size: 13px;
        }
        #wcmamtx-tracking-modal .wcmamtx-field input:focus {
            border-color: #2271b1;
            outline: 2px solid transparent;
            box-shadow: 0 0 0 1px #2271b1;
        }
        #wcmamtx-tracking-modal .wcmamtx-modal-footer {
            padding: 12px 20px;
            border-top: 1px solid #e5e5e5;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        #wcmamtx-tracking-save-btn {
            background: #2271b1;
            border: none;
            border-radius: 3px;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            padding: 8px 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        #wcmamtx-tracking-save-btn:hover { background: #135e96; }
        #wcmamtx-tracking-save-btn:disabled { opacity: .6; cursor: default; }
        #wcmamtx-tracking-modal-msg {
            font-size: 12px;
            display: none;
        }
        #wcmamtx-tracking-modal-msg.success { color: #00a32a; }
        #wcmamtx-tracking-modal-msg.error   { color: #d63638; }
    </style>

    <!-- Modal HTML -->
    <div id="wcmamtx-tracking-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="wcmamtx-modal-title">
        <div id="wcmamtx-tracking-modal">
            <div class="wcmamtx-modal-header">
                <h3 id="wcmamtx-modal-title">
                    <span class="dashicons dashicons-location" style="vertical-align:middle; color:#2271b1;"></span>
                    <?php esc_html_e( 'Add Shipment Tracking', 'customize-my-account-for-woocommerce' ); ?>
                    <span id="wcmamtx-modal-order-label" style="color:#787c82; font-weight:400; font-size:13px;"></span>
                </h3>
                <button class="wcmamtx-modal-close" id="wcmamtx-modal-close-btn" aria-label="<?php esc_attr_e('Close','customize-my-account-for-woocommerce'); ?>">&times;</button>
            </div>
            <div class="wcmamtx-modal-body">
                <div class="wcmamtx-field">
                    <label for="wcmamtx-modal-courier"><?php esc_html_e( 'Courier Name', 'customize-my-account-for-woocommerce' ); ?></label>
                    <input type="text" id="wcmamtx-modal-courier" placeholder="<?php esc_attr_e('e.g. FedEx, DHL, UPS','customize-my-account-for-woocommerce'); ?>" />
                </div>
                <div class="wcmamtx-field">
                    <label for="wcmamtx-modal-url"><?php esc_html_e( 'Tracking URL', 'customize-my-account-for-woocommerce' ); ?></label>
                    <input type="url" id="wcmamtx-modal-url" placeholder="https://" />
                </div>
            </div>
            <div class="wcmamtx-modal-footer">
                <button id="wcmamtx-tracking-save-btn">
                    <span class="dashicons dashicons-saved" style="font-size:16px;"></span>
                    <?php esc_html_e( 'Save Tracking', 'customize-my-account-for-woocommerce' ); ?>
                </button>
                <span id="wcmamtx-tracking-modal-msg"></span>
            </div>
        </div>
    </div>

    <script>
    (function($){
        var overlay  = $('#wcmamtx-tracking-modal-overlay');
        var saveBtn  = $('#wcmamtx-tracking-save-btn');
        var msgEl    = $('#wcmamtx-tracking-modal-msg');
        var currentOrderId = null;
        var currentNonce   = null;
        var currentBtn     = null;

        // Open modal
        $(document).on('click', '.wcmamtx-add-tracking-btn, .wcmamtx-edit-tracking-btn', function(){
            var isEdit = $(this).hasClass('wcmamtx-edit-tracking-btn');
            currentBtn     = $(this);
            currentOrderId = $(this).data('order-id');
            currentNonce   = $(this).data('nonce');
            $('#wcmamtx-modal-order-label').text(' — Order #' + currentOrderId);
            $('#wcmamtx-modal-mode-label').text(
                isEdit
                    ? '<?php echo esc_js( __( "Edit Shipment Tracking", "customize-my-account-for-woocommerce" ) ); ?>'
                    : '<?php echo esc_js( __( "Add Shipment Tracking", "customize-my-account-for-woocommerce" ) ); ?>'
            );
            $('#wcmamtx-modal-courier').val( isEdit ? $(this).data('courier') : '' );
            $('#wcmamtx-modal-url').val( isEdit ? $(this).data('url') : '' );
            msgEl.hide().removeClass('success error').text('');
            overlay.addClass('wcmamtx-modal-open');
            $('#wcmamtx-modal-courier').focus();
        });

        // Close modal
        function closeModal() {
            overlay.removeClass('wcmamtx-modal-open');
            currentOrderId = null;
            currentNonce   = null;
            currentBtn     = null;
        }
        $('#wcmamtx-modal-close-btn').on('click', closeModal);
        overlay.on('click', function(e){
            if ($(e.target).is(overlay)) closeModal();
        });
        $(document).on('keydown', function(e){
            if (e.key === 'Escape') closeModal();
        });

        // Save
        saveBtn.on('click', function(){
            var courier = $('#wcmamtx-modal-courier').val().trim();
            var url     = $('#wcmamtx-modal-url').val().trim();

            if (!courier && !url) {
                msgEl.text('<?php echo esc_js( __('Please enter at least a courier name or tracking URL.','customize-my-account-for-woocommerce') ); ?>')
                     .removeClass('success').addClass('error').show();
                return;
            }

            saveBtn.prop('disabled', true).find('.dashicons').removeClass('dashicons-saved').addClass('dashicons-update wcmamtx-spin');
            msgEl.hide();

            $.post(
                '<?php echo esc_url( admin_url('admin-ajax.php') ); ?>',
                {
                    action:       'wcmamtx_save_tracking_ajax',
                    order_id:     currentOrderId,
                    nonce:        currentNonce,
                    courier:      courier,
                    tracking_url: url
                },
                function(res) {
                    saveBtn.prop('disabled', false).find('.dashicons').removeClass('dashicons-update wcmamtx-spin').addClass('dashicons-saved');
                    if (res.success) {
                        msgEl.text(res.data.message).removeClass('error').addClass('success').show();

                        // Replace the button in the table cell with the tracking info
                        if (currentBtn) {
                            var cell = currentBtn.closest('td');
                            var newHtml = '<div class="wcmamtx-tracking-info">';
                            if (res.data.courier) {
                                newHtml += '<span class="wcmamtx-courier-name"><strong>' + $('<div>').text(res.data.courier).html() + '</strong></span><br>';
                            }
                            if (res.data.url) {
                                newHtml += '<a href="' + $('<div>').text(res.data.url).html() + '" target="_blank" class="button button-small" style="margin-top:4px;">'
                                        + '<span class="dashicons dashicons-location" style="vertical-align:middle; margin-right:3px; font-size:14px;"></span>Track</a>';
                            }
                                                        // Re-add edit button with refreshed values
                            var ec = (res.data.courier||'').replace(/"/g,'&quot;');
                            var eu = (res.data.url||'').replace(/"/g,'&quot;');
                            newHtml += '<button type=\"button\" class=\"button button-small wcmamtx-edit-tracking-btn\" style=\"margin-top:4px;margin-left:4px;\"'
                                + ' data-order-id=\"' + currentOrderId + '\"'
                                + ' data-nonce=\"' + currentNonce + '\"'
                                + ' data-courier=\"' + ec + '\"'
                                + ' data-url=\"' + eu + '\"'
                                + '><span class=\"dashicons dashicons-edit\" style=\"vertical-align:middle;font-size:14px;line-height:1.8;\"></span></button>';
newHtml += '</div>';
                            cell.html(newHtml);
                        }

                        setTimeout(closeModal, 1200);
                    } else {
                        msgEl.text(res.data || '<?php echo esc_js( __('Error saving tracking.','customize-my-account-for-woocommerce') ); ?>').removeClass('success').addClass('error').show();
                    }
                }
            ).fail(function(){
                saveBtn.prop('disabled', false);
                msgEl.text('<?php echo esc_js( __('Request failed. Please try again.','customize-my-account-for-woocommerce') ); ?>').removeClass('success').addClass('error').show();
            });
        });

    })(jQuery);
    </script>
    <style>
    @keyframes wcmamtx-spin-anim { to { transform: rotate(360deg); } }
    .wcmamtx-spin { display:inline-block; animation: wcmamtx-spin-anim .6s linear infinite; }
    </style>
    <?php
} );
