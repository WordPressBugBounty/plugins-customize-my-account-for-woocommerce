<?php


defined( 'ABSPATH' ) || exit;

$order      = wc_get_order( $order_id );
$order_date = $order->get_date_created();
$status     = $order->get_status();
$items      = $order->get_items();

// Map WooCommerce statuses to display labels and badge classes
$status_map = [
    'pending'    => [ 'label' => 'Pending payment',  'class' => 'wcmamtx_badge-pending' ],
    'processing' => [ 'label' => 'Processing',       'class' => 'wcmamtx_badge-processing' ],
    'on-hold'    => [ 'label' => 'On hold',          'class' => 'wcmamtx_badge-onhold' ],
    'completed'  => [ 'label' => 'Completed',        'class' => 'wcmamtx_badge-completed' ],
    'cancelled'  => [ 'label' => 'Cancelled',        'class' => 'wcmamtx_badge-cancelled' ],
    'refunded'   => [ 'label' => 'Refunded',         'class' => 'wcmamtx_badge-refunded' ],
    'failed'     => [ 'label' => 'Failed',           'class' => 'wcmamtx_badge-failed' ],
];

$status_label = $status_map[ $status ]['label'] ?? ucfirst( $status );
$status_class = $status_map[ $status ]['class'] ?? 'wcmamtx_badge-default';

// Timeline step states: 'done', 'active', 'pending'
$timeline_steps = [
    'pending'    => 0,
    'processing' => 1,
    'on-hold'    => 1,
    'completed'  => 3,
    'cancelled'  => -1,
    'refunded'   => -1,
    'failed'     => -1,
];
$current_step = $timeline_steps[ $status ] ?? 0;

function wcmamtx_view_order_step_class( $step_index, $current ) {
    if ( $current < 0 ) return 'wcmamtx-tl-dot wcmamtx-tl-dot--cancelled';
    if ( $step_index < $current )  return 'wcmamtx-tl-dot wcmamtx-tl-dot--done';
    if ( $step_index === $current ) return 'wcmamtx-tl-dot wcmamtx-tl-dot--active';
    return 'wcmamtx-tl-dot wcmamtx-tl-dot--pending';
}


/* ==========================================================
   1. REMOVE DEFAULT "ORDER AGAIN" BUTTON FROM ORDERS TABLE
   ========================================================== */

add_filter( 'woocommerce_my_account_my_orders_actions', 'custom_remove_default_order_again', 10, 2 );

function custom_remove_default_order_again( $actions, $order ) {
    unset( $actions['order-again'] );
    return $actions;
}


/* ==========================================================
   2. ADD CUSTOM "ORDER AGAIN" BUTTON TO ORDERS TABLE
   (appended back with custom markup class)
   ========================================================== */

add_filter( 'woocommerce_my_account_my_orders_actions', 'custom_add_order_again_button', 20, 2 );

function custom_add_order_again_button( $actions, $order ) {
    if ( ! $order->has_status( apply_filters( 'woocommerce_valid_order_statuses_for_order_again', [ 'completed' ] ) ) ) {
        return $actions;
    }

    if ( ! is_user_logged_in() ) {
        return $actions;
    }

    $actions['order-again'] = [
        'url'  => wp_nonce_url(
            add_query_arg(
                [ 'order_again' => $order->get_id() ],
                wc_get_cart_url()
            ),
            'woocommerce-order_again'
        ),
        'name' => __( 'Order again', 'customize-my-account-for-woocommerce' ),
        'classes' => 'custom-order-again-btn', // custom CSS class
    ];

    return $actions;
}


/* ==========================================================
   3. CUSTOM "ORDER AGAIN" BUTTON ON VIEW ORDER PAGE
   ========================================================== */

add_action( 'woocommerce_order_details_after_order_table', 'custom_view_order_again_button', 10, 1 );

function custom_view_order_again_button( $order ) {
    // Only show for completed orders to logged-in users
    if ( ! $order->has_status( apply_filters( 'woocommerce_valid_order_statuses_for_order_again', [ 'completed' ] ) ) ) {
        return;
    }

    if ( ! is_user_logged_in() ) {
        return;
    }

    $order_again_url = wp_nonce_url(
        add_query_arg(
            [ 'order_again' => $order->get_id() ],
            wc_get_cart_url()
        ),
        'woocommerce-order_again'
    );

    ?>
    <a href="<?php echo esc_url( $order_again_url ); ?>"
       class="wcmamtx_view_order-btn"
       aria-label="<?php esc_attr_e( 'Order the same items again', 'customize-my-account-for-woocommerce' ); ?>">
        <i class="fa fa-repeat"></i>
        <?php esc_html_e( 'Order again', 'customize-my-account-for-woocommerce' ); ?>
    </a>
    <?php
}
?>

<!-- =============================================
     VIEW ORDER TEMPLATE
     ============================================= -->
<div class="wcmamtx_view_order-wrap">

    <?php do_action( 'woocommerce_before_view_order_page' ); ?>

    <?php if ( $notices = wc_get_notices() ) : ?>
        <?php wc_print_notices(); ?>
    <?php endif; ?>

    <!-- ---- Header ---- -->
    <div class="wcmamtx_view_order-header">
        <div>
            <h2 class="wcmamtx_view_order-header__title">
                <?php printf( esc_html__( 'Order #%s', 'customize-my-account-for-woocommerce' ), $order->get_order_number() ); ?>
            </h2>
            <p class="wcmamtx_view_order-header__meta">
                <?php
                printf(
                    esc_html__( 'Placed on %s &mdash; %d item(s)', 'customize-my-account-for-woocommerce' ),
                    esc_html( wc_format_datetime( $order_date ) ),
                    count( $items )
                );
                ?>
            </p>
        </div>
        <span class="wcmamtx_view_order-badge <?php echo esc_attr( $status_class ); ?>">
            <?php echo esc_html( $status_label ); ?>
        </span>
    </div>


    <?php if ( $order && $order->has_downloadable_item() && $order->is_download_permitted() ) {

        $wcmamtx_layout = (array) get_option( 'wcmamtx_layout' );

        $download_style = isset($wcmamtx_layout['download_style']) ? $wcmamtx_layout['download_style'] : "01";

        $woocommerce_path = WC()->plugin_path();

        
        $download_template = $woocommerce_path . '/templates/myaccount/downloads.php';

        $download_template_override = isset($wcmamtx_layout['download_template_override']) ? $wcmamtx_layout['download_template_override'] : "01";
        


        if ( ($download_template_override != "02")) {
             $download_template = wcmamtx_plugin_path() . '/templates/myaccount/order/order-downloads.php';
        }


       

        $download_template = apply_filters("wcmamtx_override_download_template",$download_template,$wcmamtx_layout);

        $file_to_check = "wcmamtx_template/order/downloads/$download_style.php"; // Change to your relative file path

        if ( file_exists( get_stylesheet_directory() . '/' . $file_to_check ) ) {
         // The file exists in the active child theme
               $download_template = ''.get_stylesheet_directory().'/wcmamtx_template/order/downloads/'.$download_style.'.php';
        }


        $downloads = $order->get_downloadable_items();



        include($download_template);
       }

    ?>

    <!-- ---- Items + Totals ---- -->
    <div class="wcmamtx_view_order-card">
        <p class="wcmamtx_view_order-card__title"><?php esc_html_e( 'Items ordered', 'customize-my-account-for-woocommerce' ); ?></p>

        <table class="wcmamtx_view_order-items-table">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Product', 'customize-my-account-for-woocommerce' ); ?></th>
                    <th><?php esc_html_e( 'Qty', 'customize-my-account-for-woocommerce' ); ?></th>
                    <th><?php esc_html_e( 'Price', 'customize-my-account-for-woocommerce' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $items as $item_id => $item ) :
                    $product     = $item->get_product();
                    $product_url = $product ? get_permalink( $product->get_id() ) : '';
                    $image_url   = $product ? wp_get_attachment_image_url( $product->get_image_id(), 'thumbnail' ) : '';
                ?>
                <tr>
                    <td>
                        <div class="wcmamtx_view_order-product-cell">
                            <?php if ( $image_url ) : ?>
                                <img src="<?php echo esc_url( $image_url ); ?>"
                                     alt="<?php echo esc_attr( $item->get_name() ); ?>"
                                     class="wcmamtx_view_order-product-thumb" />
                            <?php else : ?>
                                <div class="wcmamtx_view_order-product-thumb wcmamtx_view_order-product-thumb--placeholder">&#128722;</div>
                            <?php endif; ?>

                            <div>
                                <?php if ( $product_url ) : ?>
                                    <a href="<?php echo esc_url( $product_url ); ?>" class="wcmamtx_view_order-product__name">
                                        <?php echo esc_html( $item->get_name() ); ?>
                                    </a>
                                <?php else : ?>
                                    <span class="wcmamtx_view_order-product__name"><?php echo esc_html( $item->get_name() ); ?></span>
                                <?php endif; ?>

                                <?php
                                // Print variation meta (colour, size, etc.)
                                $meta_data = $item->get_formatted_meta_data( '' );
                                if ( $meta_data ) :
                                    $meta_parts = [];
                                    foreach ( $meta_data as $meta ) {
                                        $meta_parts[] = wp_kses_post( $meta->display_key ) . ': ' . wp_kses_post( $meta->display_value );
                                    }
                                    echo '<p class="wcmamtx_view_order-product__meta">' . implode( ' &middot; ', $meta_parts ) . '</p>';
                                endif;
                                ?>
                            </div>
                        </div>
                    </td>
                    <td>&times;<?php echo esc_html( $item->get_quantity() ); ?></td>
                    <td><?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Totals -->
        <table class="wcmamtx_view_order-totals">
            <tbody>
                <tr>
                    <td><?php esc_html_e( 'Subtotal', 'customize-my-account-for-woocommerce' ); ?></td>
                    <td><?php echo wp_kses_post( $order->get_subtotal_to_display() ); ?></td>
                </tr>

                <?php foreach ( $order->get_shipping_methods() as $shipping ) : ?>
                <tr>
                    <td><?php esc_html_e( 'Shipping', 'customize-my-account-for-woocommerce' ); ?> (<?php echo esc_html( $shipping->get_name() ); ?>)</td>
                    <td><?php echo wp_kses_post( wc_price( $shipping->get_total() ) ); ?></td>
                </tr>
                <?php endforeach; ?>

                <?php foreach ( $order->get_fees() as $fee ) : ?>
                <tr>
                    <td><?php echo esc_html( $fee->get_name() ); ?></td>
                    <td><?php echo wp_kses_post( wc_price( $fee->get_total() ) ); ?></td>
                </tr>
                <?php endforeach; ?>

                <?php if ( $order->get_total_discount() ) : ?>
                <tr>
                    <td><?php esc_html_e( 'Discount', 'customize-my-account-for-woocommerce' ); ?></td>
                    <td>-<?php echo wp_kses_post( wc_price( $order->get_total_discount() ) ); ?></td>
                </tr>
                <?php endif; ?>

                <?php if ( wc_tax_enabled() && $order->get_total_tax() ) : ?>
                <tr>
                    <td><?php esc_html_e( 'Tax', 'customize-my-account-for-woocommerce' ); ?></td>
                    <td><?php echo wp_kses_post( wc_price( $order->get_total_tax() ) ); ?></td>
                </tr>
                <?php endif; ?>

                <tr class="wcmamtx_view_order-totals__grand">
                    <td><?php esc_html_e( 'Order total', 'customize-my-account-for-woocommerce' ); ?></td>
                    <td><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    




    <!-- ---- Addresses ---- -->
    <div class="wcmamtx_view_order-address-grid">
        <div class="wcmamtx_view_order-card" style="margin-bottom:0">
            <p class="wcmamtx_view_order-card__title"><?php esc_html_e( 'Billing address', 'customize-my-account-for-woocommerce' ); ?></p>
            <div class="wcmamtx_view_order-address">
                <?php echo wp_kses_post( $order->get_formatted_billing_address() ?: __( 'N/A', 'customize-my-account-for-woocommerce' ) ); ?>
                <?php if ( $phone = $order->get_billing_phone() ) : ?>
                    <p><?php echo esc_html( $phone ); ?></p>
                <?php endif; ?>
                <?php if ( $email = $order->get_billing_email() ) : ?>
                    <p><?php echo esc_html( $email ); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="wcmamtx_view_order-card" style="margin-bottom:0">
            <p class="wcmamtx_view_order-card__title"><?php esc_html_e( 'Shipping address', 'customize-my-account-for-woocommerce' ); ?></p>
            <div class="wcmamtx_view_order-address">
                <?php echo wp_kses_post( $order->get_formatted_shipping_address() ?: __( 'Same as billing', 'customize-my-account-for-woocommerce' ) ); ?>
            </div>
        </div>
    </div>

    <!-- spacing row -->
    <div style="margin-bottom:1rem"></div>

    <!-- ---- Order Status Timeline ---- -->
    <div class="wcmamtx_view_order-card">
        <p class="wcmamtx_view_order-card__title"><?php esc_html_e( 'Order progress', 'customize-my-account-for-woocommerce' ); ?></p>

        <?php if ( $status === 'cancelled' || $status === 'failed' || $status === 'refunded' ) : ?>
            <p style="font-size:13px;color:#b91c1c;">
                <?php
                printf(
                    esc_html__( 'This order was %s on %s.', 'customize-my-account-for-woocommerce' ),
                    esc_html( $status_label ),
                    esc_html( wc_format_datetime( $order_date ) )
                );
                ?>
            </p>
        <?php else : ?>

        <?php
        $steps = [
            [ 'index' => 0, 'label' => __( 'Order placed', 'customize-my-account-for-woocommerce' ),      'date' => wc_format_datetime( $order_date ) ],
            [ 'index' => 1, 'label' => __( 'Processing', 'customize-my-account-for-woocommerce' ),         'date' => $current_step >= 1 ? wc_format_datetime( $order_date ) : __( 'Pending', 'customize-my-account-for-woocommerce' ) ],
            [ 'index' => 2, 'label' => __( 'Delivered / Completed', 'customize-my-account-for-woocommerce' ), 'date' => $current_step >= 3 ? wc_format_datetime( $order->get_date_completed() ) : __( 'Pending', 'customize-my-account-for-woocommerce' ) ],
        ];
        ?>
        <div class="wcmamtx_view_order-timeline">
            <?php foreach ( $steps as $i => $step ) :
                $is_last = ( $i === count( $steps ) - 1 );
                $dot_class = wcmamtx_view_order_step_class( $step['index'], $current_step );
            ?>
            <div class="wcmamtx_view_order-tl-item">
                <div class="wcmamtx_view_order-tl-track">
                    <div class="<?php echo esc_attr( $dot_class ); ?>"></div>
                    <?php if ( ! $is_last ) : ?><div class="wcmamtx_view_order-tl-line"></div><?php endif; ?>
                </div>
                <div class="wcmamtx_view_order-tl-content">
                    <p class="wcmamtx_view_order-tl-content__label"><?php echo esc_html( $step['label'] ); ?></p>
                    <p class="wcmamtx_view_order-tl-content__date"><?php echo esc_html( $step['date'] ); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php endif; ?>
    </div>

    <!-- ---- Payment Info ---- -->
    <div class="wcmamtx_view_order-card">
        <p class="wcmamtx_view_order-card__title"><?php esc_html_e( 'Payment', 'customize-my-account-for-woocommerce' ); ?></p>
        <div class="wcmamtx_view_order-payment-row">
            <span class="wcmamtx_view_order-payment__method">
                &#128179; <?php echo esc_html( $order->get_payment_method_title() ); ?>
            </span>
            <?php if ( $order->is_paid() ) : ?>
                <span class="wcmamtx_view_order-badge wcmamtx_badge-completed">&#10003; <?php esc_html_e( 'Paid', 'customize-my-account-for-woocommerce' ); ?></span>
            <?php else : ?>
                <span class="wcmamtx_view_order-badge wcmamtx_badge-pending"><?php esc_html_e( 'Unpaid', 'customize-my-account-for-woocommerce' ); ?></span>
            <?php endif; ?>
        </div>
        <?php if ( $txn_id = $order->get_transaction_id() ) : ?>
            <p style="font-size:12px;color:#aaa;margin-top:8px;">
                <?php printf( esc_html__( 'Transaction ID: %s', 'customize-my-account-for-woocommerce' ), esc_html( $txn_id ) ); ?>
            </p>
        <?php endif; ?>
    </div>

    <!-- ---- Order Notes ---- -->
    <?php $notes = wc_get_order_notes( [ 'order_id' => $order_id, 'type' => 'customer' ] ); ?>
    <?php if ( $notes ) : ?>
    <div class="wcmamtx_view_order-card">
        <p class="wcmamtx_view_order-card__title"><?php esc_html_e( 'Order updates', 'customize-my-account-for-woocommerce' ); ?></p>
        <ul class="wcmamtx_view_order-notes">
            <?php foreach ( $notes as $note ) : ?>
            <li>
                <?php echo wp_kses_post( $note->comment_content ); ?>
                <span class="note-date">
                    <?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $note->comment_date ) ) ); ?>
                </span>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <!-- ---- Actions ---- -->
    <div class="wcmamtx_view_order-actions">
        

        <a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="wcmamtx_view_order-btn">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> <?php esc_html_e( 'Back to orders', 'customize-my-account-for-woocommerce' ); ?>
        </a>



        <?php
        $actions = wc_get_account_orders_actions( $order );

        if ( ! empty( $actions ) ) {
            unset($actions['order-again']);
            foreach ( $actions as $key => $action ) { 


                                    // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                if ( empty( $action['aria-label'] ) ) {
                                            // Generate the aria-label based on the action name.
                    /* translators: %1$s Action name, %2$s Order number. */
                    $action_aria_label = sprintf( __( '%1$s order number %2$s', 'customize-my-account-for-woocommerce' ), $action['name'], $order->get_order_number() );
                } else {
                    $action_aria_label = $action['aria-label'];
                }

                if ($key != "view") {

                    $icon_html = '';

                    switch($key) {

                        case "pay":
                        $icon_html = '<i class="fa fa-bag-shopping"></i>';
                        break;

                        case "cancel":
                        $icon_html = '<i class="fa fa-times"></i>';
                        break;

                        
                    }


                    echo '<a href="' . esc_url( $action['url'] ) . '" class="wcmamtx_view_order-btn woocommerce-button' . esc_attr( $wp_button_class ) . ' button ' . sanitize_html_class( $key ) . '" aria-label="' . esc_attr( $action_aria_label ) . '">' . $icon_html . '' . esc_html( $action['name'] ) . '</a>';
                }
                unset( $action_aria_label );
            }
        }
        ?>

        <?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
    </div>

    <?php do_action( 'woocommerce_after_view_order_page' ); ?>

</div><!-- .wcmamtx_view_order-wrap -->

<?php 
remove_action( 'woocommerce_view_order', 'woocommerce_order_details_table');
do_action( 'woocommerce_view_order', $order_id ); 
?>