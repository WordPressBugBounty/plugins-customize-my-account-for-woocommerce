<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$_td = 'customize-my-account-for-woocommerce';

// ─────────────────────────────────────────────────────────────────────────────
// AJAX handler – delete tracking info
// ─────────────────────────────────────────────────────────────────────────────
add_action( 'wp_ajax_wcmamtx_delete_tracking_ajax', function() {
    $order_id = isset( $_POST['order_id'] ) ? absint( $_POST['order_id'] ) : 0;
    if ( ! $order_id ) wp_send_json_error( 'Invalid order.' );

    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce(
        sanitize_text_field( wp_unslash( $_POST['nonce'] ) ),
        'wcmamtx_tracking_ajax_' . $order_id
    ) ) {
        wp_send_json_error( 'Security check failed.' );
    }
    if ( ! current_user_can( 'edit_shop_orders' ) ) {
        wp_send_json_error( 'Permission denied.' );
    }

    delete_post_meta( $order_id, '_wcmamtx_courier_name' );
    delete_post_meta( $order_id, '_wcmamtx_tracking_url' );

    wp_send_json_success( array(
        'message'  => __( 'Tracking info deleted.', 'customize-my-account-for-woocommerce' ),
        'order_id' => $order_id,
    ) );
} );

// ─────────────────────────────────────────────────────────────────────────────
// Inject delete button + JS into the ORDERS LIST page
// ─────────────────────────────────────────────────────────────────────────────
add_action( 'admin_footer', function() {
    if ( ! function_exists( 'wcmamtx_tracking_enabled' ) || ! wcmamtx_tracking_enabled() ) return;

    $screen = get_current_screen();
    if ( ! $screen ) return;
    $is_orders_list = (
        $screen->id === 'woocommerce_page_wc-orders' ||
        $screen->id === 'edit-shop_order'
    ) && ! ( isset( $_GET['action'] ) && $_GET['action'] === 'edit' );
    if ( ! $is_orders_list ) return;

    $i18n = array(
        'deleteTitle'   => __( 'Delete tracking info',                          'customize-my-account-for-woocommerce' ),
        'confirmDelete' => __( 'Delete tracking info for this order? This cannot be undone.', 'customize-my-account-for-woocommerce' ),
        'addTracking'   => __( 'Add Tracking',                                  'customize-my-account-for-woocommerce' ),
        'errorDelete'   => __( 'Error deleting tracking info.',                  'customize-my-account-for-woocommerce' ),
        'errorRequest'  => __( 'Request failed. Please try again.',              'customize-my-account-for-woocommerce' ),
    );
    ?>
    <style>
        .wcmamtx-delete-tracking-btn { color:#787c82!important; border-color:#787c82!important; }
        .wcmamtx-delete-tracking-btn:hover { color:#fff!important; background:#d63638!important; border-color:#d63638!important; }
        .wcmamtx-delete-tracking-btn:focus { box-shadow:0 0 0 1px #d63638!important; }
    </style>
    <script>
    (function($){
        var i18n = <?php echo wp_json_encode( $i18n ); ?>;

        function injectDeleteButtons() {
            $('td').each(function(){
                var cell    = $(this);
                var editBtn = cell.find('.wcmamtx-edit-tracking-btn');
                if ( editBtn.length && ! cell.find('.wcmamtx-delete-tracking-btn').length ) {
                    var orderId = editBtn.data('order-id');
                    var nonce   = editBtn.data('nonce');
                    var delBtn  = $('<button>',{
                        type    : 'button',
                        'class' : 'button button-small wcmamtx-delete-tracking-btn',
                        title   : i18n.deleteTitle,
                        'data-order-id' : orderId,
                        'data-nonce'    : nonce,
                        css     : { marginTop:'4px', marginLeft:'4px' }
                    }).append('<span class="dashicons dashicons-trash" style="vertical-align:middle;font-size:14px;line-height:1.8;"></span>');
                    editBtn.after(delBtn);
                }
            });
        }
        $(document).ready(injectDeleteButtons);

        $(document).on('click', '.wcmamtx-delete-tracking-btn', function(){
            if ( ! confirm( i18n.confirmDelete ) ) return;

            var btn     = $(this);
            var orderId = btn.data('order-id');
            var nonce   = btn.data('nonce');
            btn.prop('disabled', true);
            btn.find('.dashicons').removeClass('dashicons-trash').addClass('dashicons-update wcmamtx-spin');

            $.post( ajaxurl,
                { action: 'wcmamtx_delete_tracking_ajax', order_id: orderId, nonce: nonce },
                function(res) {
                    if ( res.success ) {
                        var cell = btn.closest('td');
                        cell.html(
                            '<button type="button"'
                            + ' class="button button-small wcmamtx-add-tracking-btn"'
                            + ' data-order-id="' + orderId + '"'
                            + ' data-nonce="' + nonce + '"'
                            + '><span class="dashicons dashicons-plus-alt2" style="vertical-align:middle;font-size:14px;"></span> '
                            + $('<div>').text( i18n.addTracking ).html()
                            + '</button>'
                        );
                    } else {
                        alert( res.data || i18n.errorDelete );
                        btn.prop('disabled', false);
                        btn.find('.dashicons').removeClass('dashicons-update wcmamtx-spin').addClass('dashicons-trash');
                    }
                }
            ).fail(function(){
                alert( i18n.errorRequest );
                btn.prop('disabled', false);
                btn.find('.dashicons').removeClass('dashicons-update wcmamtx-spin').addClass('dashicons-trash');
            });
        });
    })(jQuery);
    </script>
    <?php
}, 99 );

// ─────────────────────────────────────────────────────────────────────────────
// Inject delete button + JS into the order EDIT page metabox
// ─────────────────────────────────────────────────────────────────────────────
add_action( 'admin_footer', function() {
    if ( ! function_exists( 'wcmamtx_tracking_enabled' ) || ! wcmamtx_tracking_enabled() ) return;

    $screen = get_current_screen();
    if ( ! $screen ) return;
    $is_order_edit = (
        ( $screen->id === 'woocommerce_page_wc-orders' && isset( $_GET['action'] ) && $_GET['action'] === 'edit' )
        || $screen->id === 'shop_order'
    );
    if ( ! $is_order_edit ) return;

    $order_id = 0;
    if ( isset( $_GET['id'] ) ) {
        $order_id = absint( $_GET['id'] );
    } elseif ( isset( $GLOBALS['post'] ) && $GLOBALS['post']->post_type === 'shop_order' ) {
        $order_id = (int) $GLOBALS['post']->ID;
    }
    if ( ! $order_id ) return;

    $courier = get_post_meta( $order_id, '_wcmamtx_courier_name', true );
    $url     = get_post_meta( $order_id, '_wcmamtx_tracking_url', true );
    if ( ! $courier && ! $url ) return;

    $nonce = wp_create_nonce( 'wcmamtx_tracking_ajax_' . $order_id );

    $i18n = array(
        'deleteLabel'   => __( 'Delete Tracking Info',                          'customize-my-account-for-woocommerce' ),
        'deletingLabel' => __( 'Deleting…',                                     'customize-my-account-for-woocommerce' ),
        'deleteTitle'   => __( 'Delete tracking info',                          'customize-my-account-for-woocommerce' ),
        'confirmDelete' => __( 'Delete tracking info for this order? This cannot be undone.', 'customize-my-account-for-woocommerce' ),
        'errorDelete'   => __( 'Error deleting tracking info.',                  'customize-my-account-for-woocommerce' ),
        'errorRequest'  => __( 'Request failed. Please try again.',              'customize-my-account-for-woocommerce' ),
    );
    ?>
    <script>
    (function($){
        $(document).ready(function(){
            var i18n      = <?php echo wp_json_encode( $i18n ); ?>;
            var orderId   = <?php echo (int) $order_id; ?>;
            var nonce     = '<?php echo esc_js( $nonce ); ?>';
            var metabox   = $('#wcmamtx_shipment_tracking_box');
            if ( ! metabox.length ) return;
            if ( metabox.find('.wcmamtx-metabox-delete-btn').length ) return;

            var savedInfo = metabox.find('#wcmamtx_tracking_saved_info');

            var delBtn = $('<button>', {
                type    : 'button',
                'class' : 'button wcmamtx-metabox-delete-btn',
                title   : i18n.deleteTitle,
                css     : { marginTop:'8px', width:'100%', display:'flex', alignItems:'center', justifyContent:'center', gap:'6px', color:'#787c82', borderColor:'#787c82' }
            }).html('<span class="dashicons dashicons-trash" style="font-size:15px;line-height:1.7;"></span> ' + $('<div>').text( i18n.deleteLabel ).html() );

            if ( savedInfo.length ) { savedInfo.after( delBtn ); }
            else { metabox.find('.inside').append( delBtn ); }

            delBtn.on('mouseenter', function(){ $(this).css({ color:'#fff', background:'#d63638', borderColor:'#d63638' }); });
            delBtn.on('mouseleave', function(){ $(this).css({ color:'#787c82', background:'',       borderColor:'#787c82' }); });

            delBtn.on('click', function(){
                if ( ! confirm( i18n.confirmDelete ) ) return;

                delBtn.prop('disabled', true)
                      .html('<span class="dashicons dashicons-update wcmamtx-spin" style="font-size:15px;line-height:1.7;"></span> ' + $('<div>').text( i18n.deletingLabel ).html() );

                $.post( ajaxurl,
                    { action: 'wcmamtx_delete_tracking_ajax', order_id: orderId, nonce: nonce },
                    function(res) {
                        if ( res.success ) {
                            metabox.find('#wcmamtx_courier_name').val('');
                            metabox.find('#wcmamtx_tracking_url').val('');
                            savedInfo.hide();
                            delBtn.remove();
                        } else {
                            alert( res.data || i18n.errorDelete );
                            delBtn.prop('disabled', false)
                                  .html('<span class="dashicons dashicons-trash" style="font-size:15px;line-height:1.7;"></span> ' + $('<div>').text( i18n.deleteLabel ).html() );
                        }
                    }
                ).fail(function(){
                    alert( i18n.errorRequest );
                    delBtn.prop('disabled', false)
                          .html('<span class="dashicons dashicons-trash" style="font-size:15px;line-height:1.7;"></span> ' + $('<div>').text( i18n.deleteLabel ).html() );
                });
            });
        });
    })(jQuery);
    </script>
    <?php
}, 99 );