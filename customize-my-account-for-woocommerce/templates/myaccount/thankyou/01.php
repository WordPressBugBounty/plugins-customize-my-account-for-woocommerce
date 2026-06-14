<?php
/**
 * WooCommerce Order Received (Thank You) Page
 *
 * Template: checkout/thankyou.php
 *
 * Drop this file into your theme at:
 *   yourtheme/woocommerce/checkout/thankyou.php
 *
 * @package WooCommerce\Templates
 * @version 8.1.0
 */

defined( 'ABSPATH' ) || exit;
?>

<style>
/* =============================================
   ORDER RECEIVED — RESET & BASE
   ============================================= */
.or-wrap *,
.or-wrap *::before,
.or-wrap *::after { box-sizing: border-box; margin: 0; padding: 0; }

.or-wrap {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    font-size: 14px;
    color: #1a1a1a;
    line-height: 1.6;
}

/* ---- Hero ---- */
.or-hero {
    text-align: center;
    padding: 2.5rem 1rem 2rem;
    margin-bottom: 1.5rem;
}

.or-checkmark {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: #f0fdf4;
    border: 1.5px solid #bbf7d0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.25rem;
    font-size: 32px;
    color: #15803d;
}

.or-hero h1 {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: .5rem;
    color: #1a1a1a;
}

.or-hero p {
    font-size: 14px;
    color: #666;
    line-height: 1.7;
}

/* ---- Notice banner ---- */
.or-notice {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 8px;
    padding: .9rem 1.1rem;
    font-size: 13px;
    color: #1d4ed8;
    margin-bottom: 1.25rem;
}

.or-notice svg { flex-shrink: 0; margin-top: 1px; }

/* ---- Summary metric cards ---- */
.or-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    margin-bottom: 1.25rem;
}

@media (max-width: 560px) {
    .or-summary-grid { grid-template-columns: repeat(2, 1fr); }
}

.or-summary-card {
    background: #f9fafb;
    border: 1px solid #e5e5e5;
    border-radius: 10px;
    padding: .9rem 1rem;
    text-align: center;
}

.or-summary-card__label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #888;
    margin-bottom: 5px;
}

.or-summary-card__value {
    font-size: 15px;
    font-weight: 600;
    color: #1a1a1a;
    word-break: break-word;
}

/* ---- Cards ---- */
.or-card {
    background: #fff;
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1rem;
}

.or-card__title {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: #888;
    margin-bottom: 1rem;
}

/* ---- Progress steps ---- */
.or-steps {
    display: flex;
    align-items: flex-start;
    position: relative;
}

.or-steps::before {
    content: '';
    position: absolute;
    top: 18px;
    left: calc(12.5%);
    width: 75%;
    height: 1px;
    background: #e5e5e5;
    z-index: 0;
}

.or-step {
    flex: 1;
    text-align: center;
    position: relative;
    z-index: 1;
}

.or-step__dot {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    font-size: 15px;
}

.or-step__dot--done   { background: #f0fdf4; border: 1.5px solid #bbf7d0; color: #15803d; }
.or-step__dot--active { background: #eff6ff; border: 1.5px solid #bfdbfe; color: #1d4ed8; }
.or-step__dot--pending{ background: #f9fafb; border: 1px solid #e5e5e5;   color: #aaa; }

.or-step__label strong {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 2px;
}

.or-step__label span {
    font-size: 11px;
    color: #888;
}

/* ---- Items table ---- */
.or-items-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1.25rem;
}

.or-items-table thead th {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #888;
    padding: 0 0 10px;
    border-bottom: 1px solid #e5e5e5;
    text-align: left;
}

.or-items-table thead th:last-child { text-align: right; }

.or-items-table tbody td {
    padding: 13px 0;
    border-bottom: 1px solid #f0f0f0;
    vertical-align: middle;
    font-size: 13px;
    color: #333;
}

.or-items-table tbody td:last-child { text-align: right; font-weight: 600; }
.or-items-table tbody tr:last-child td { border-bottom: none; }

.or-product-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.or-product-thumb {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    object-fit: cover;
    border: 1px solid #e5e5e5;
    flex-shrink: 0;
    background: #f5f5f5;
}

.or-product-thumb--placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #ccc;
}

.or-product-name {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 3px;
    color: #1a1a1a;
}

.or-product-meta {
    font-size: 12px;
    color: #888;
}

/* ---- Totals ---- */
.or-totals {
    width: 100%;
    border-collapse: collapse;
}

.or-totals td {
    padding: 5px 0;
    font-size: 13px;
    color: #666;
}

.or-totals td:last-child { text-align: right; color: #1a1a1a; }

.or-totals .or-grand td {
    font-size: 16px;
    font-weight: 600;
    color: #1a1a1a;
    border-top: 1px solid #e5e5e5;
    padding-top: 12px;
}

/* ---- Addresses ---- */
.or-addr-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

@media (max-width: 480px) {
    .or-addr-grid { grid-template-columns: 1fr; }
}

.or-addr-card {
    background: #fff;
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
}

.or-addr-card address {
    font-style: normal;
    font-size: 13px;
    color: #555;
    line-height: 1.8;
}

.or-addr-card address strong { color: #1a1a1a; font-weight: 600; }

/* ---- CTA buttons ---- */
.or-cta-row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: .5rem;
}

.or-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    border: 1px solid #d1d5db;
    background: #fff;
    color: #1a1a1a;
    cursor: pointer;
    text-decoration: none;
    transition: background .15s, border-color .15s;
}

.or-btn:hover { background: #f5f5f5; border-color: #aaa; color: #1a1a1a; text-decoration: none; }

.or-btn--primary {
    background: #1a1a1a;
    color: #fff;
    border-color: transparent;
}

.or-btn--primary:hover { background: #333; color: #fff; }
</style>

<!-- =============================================
     ORDER RECEIVED TEMPLATE
     ============================================= -->

<?php if ( ! $order ) : ?>

    <?php wc_print_notices(); ?>

    <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
        <?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'customize-my-account-for-woocommerce' ), null ); ?>
    </p>

<?php else : ?>

    <?php do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>

    <div class="or-wrap">

        <!-- ---- Hero ---- -->
        <div class="or-hero">
            <div class="or-checkmark">&#10003;</div>

            <?php if ( $order->has_status( 'failed' ) ) : ?>
                <h1><?php esc_html_e( 'Payment failed', 'customize-my-account-for-woocommerce' ); ?></h1>
                <p>
                    <?php esc_html_e( 'Unfortunately your order cannot be processed. Please attempt your purchase again.', 'customize-my-account-for-woocommerce' ); ?>
                </p>
            <?php else : ?>
                <h1>
                    <?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Order confirmed!', 'customize-my-account-for-woocommerce' ), $order ); ?>
                </h1>
                <p>
                    <?php
                    printf(
                        /* translators: %s: customer email */
                        esc_html__( 'Thank you for your purchase. A confirmation has been sent to %s', 'customize-my-account-for-woocommerce' ),
                        '<strong>' . esc_html( $order->get_billing_email() ) . '</strong>'
                    );
                    ?>
                </p>
            <?php endif; ?>
        </div>

        <?php wc_print_notices(); ?>

        <!-- ---- Email notice ---- -->
        <?php if ( ! $order->has_status( 'failed' ) ) : ?>
        <div class="or-notice">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
            <?php esc_html_e( "We've emailed your receipt and order details. Check your spam folder if you don't see it within a few minutes.", 'customize-my-account-for-woocommerce' ); ?>
        </div>
        <?php endif; ?>

        <!-- ---- Summary strip ---- -->
        <div class="or-summary-grid">
            <div class="or-summary-card">
                <div class="or-summary-card__label"><?php esc_html_e( 'Order', 'customize-my-account-for-woocommerce' ); ?></div>
                <div class="or-summary-card__value">#<?php echo esc_html( $order->get_order_number() ); ?></div>
            </div>
            <div class="or-summary-card">
                <div class="or-summary-card__label"><?php esc_html_e( 'Date', 'customize-my-account-for-woocommerce' ); ?></div>
                <div class="or-summary-card__value"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></div>
            </div>
            <div class="or-summary-card">
                <div class="or-summary-card__label"><?php esc_html_e( 'Payment', 'customize-my-account-for-woocommerce' ); ?></div>
                <div class="or-summary-card__value"><?php echo esc_html( $order->get_payment_method_title() ); ?></div>
            </div>
            <div class="or-summary-card">
                <div class="or-summary-card__label"><?php esc_html_e( 'Total', 'customize-my-account-for-woocommerce' ); ?></div>
                <div class="or-summary-card__value"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></div>
            </div>
        </div>

        <!-- ---- What happens next (steps) ---- -->
        <?php if ( ! $order->has_status( [ 'failed', 'cancelled' ] ) ) : ?>
        <div class="or-card">
            <p class="or-card__title"><?php esc_html_e( 'What happens next', 'customize-my-account-for-woocommerce' ); ?></p>
            <div class="or-steps">
                <div class="or-step">
                    <div class="or-step__dot or-step__dot--done">&#10003;</div>
                    <div class="or-step__label">
                        <strong><?php esc_html_e( 'Order placed', 'customize-my-account-for-woocommerce' ); ?></strong>
                        <span><?php esc_html_e( 'Just now', 'customize-my-account-for-woocommerce' ); ?></span>
                    </div>
                </div>
                <div class="or-step">
                    <div class="or-step__dot or-step__dot--active">&#9881;</div>
                    <div class="or-step__label">
                        <strong><?php esc_html_e( 'Processing', 'customize-my-account-for-woocommerce' ); ?></strong>
                        <span><?php esc_html_e( '1–2 hours', 'customize-my-account-for-woocommerce' ); ?></span>
                    </div>
                </div>
                <div class="or-step">
                    <div class="or-step__dot or-step__dot--pending">&#9993;</div>
                    <div class="or-step__label">
                        <strong><?php esc_html_e( 'Shipped', 'customize-my-account-for-woocommerce' ); ?></strong>
                        <span><?php esc_html_e( '1–2 days', 'customize-my-account-for-woocommerce' ); ?></span>
                    </div>
                </div>
                <div class="or-step">
                    <div class="or-step__dot or-step__dot--pending">&#8962;</div>
                    <div class="or-step__label">
                        <strong><?php esc_html_e( 'Delivered', 'customize-my-account-for-woocommerce' ); ?></strong>
                        <span><?php esc_html_e( '3–5 days', 'customize-my-account-for-woocommerce' ); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- ---- Order items & totals ---- -->
        <div class="or-card">
            <p class="or-card__title"><?php esc_html_e( 'Items in your order', 'customize-my-account-for-woocommerce' ); ?></p>

            <table class="or-items-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Product', 'customize-my-account-for-woocommerce' ); ?></th>
                        <th><?php esc_html_e( 'Qty', 'customize-my-account-for-woocommerce' ); ?></th>
                        <th><?php esc_html_e( 'Price', 'customize-my-account-for-woocommerce' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $order->get_items() as $item_id => $item ) :
                        $product   = $item->get_product();
                        $image_url = $product ? wp_get_attachment_image_url( $product->get_image_id(), 'thumbnail' ) : '';
                    ?>
                    <tr>
                        <td>
                            <div class="or-product-cell">
                                <?php if ( $image_url ) : ?>
                                    <img src="<?php echo esc_url( $image_url ); ?>"
                                         alt="<?php echo esc_attr( $item->get_name() ); ?>"
                                         class="or-product-thumb" />
                                <?php else : ?>
                                    <div class="or-product-thumb or-product-thumb--placeholder">&#128722;</div>
                                <?php endif; ?>

                                <div>
                                    <div class="or-product-name"><?php echo esc_html( $item->get_name() ); ?></div>
                                    <?php
                                    $meta_data = $item->get_formatted_meta_data( '' );
                                    if ( $meta_data ) :
                                        $parts = [];
                                        foreach ( $meta_data as $meta ) {
                                            $parts[] = wp_kses_post( $meta->display_key ) . ': ' . wp_kses_post( $meta->display_value );
                                        }
                                        echo '<div class="or-product-meta">' . implode( ' &middot; ', $parts ) . '</div>';
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
            <table class="or-totals">
                <tr>
                    <td><?php esc_html_e( 'Subtotal', 'customize-my-account-for-woocommerce' ); ?></td>
                    <td><?php echo wp_kses_post( $order->get_subtotal_to_display() ); ?></td>
                </tr>

                <?php foreach ( $order->get_shipping_methods() as $shipping ) : ?>
                <tr>
                    <td><?php printf( esc_html__( 'Shipping (%s)', 'customize-my-account-for-woocommerce' ), esc_html( $shipping->get_name() ) ); ?></td>
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

                <tr class="or-grand">
                    <td><?php esc_html_e( 'Order total', 'customize-my-account-for-woocommerce' ); ?></td>
                    <td><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></td>
                </tr>
            </table>
        </div>

        <!-- ---- Addresses ---- -->
        <div class="or-addr-grid">
            <div class="or-addr-card">
                <p class="or-card__title"><?php esc_html_e( 'Billing address', 'customize-my-account-for-woocommerce' ); ?></p>
                <address>
                    <?php echo wp_kses_post( $order->get_formatted_billing_address() ?: esc_html__( 'N/A', 'customize-my-account-for-woocommerce' ) ); ?>
                    <?php if ( $phone = $order->get_billing_phone() ) : ?>
                        <br><?php echo esc_html( $phone ); ?>
                    <?php endif; ?>
                </address>
            </div>

            <div class="or-addr-card">
                <p class="or-card__title"><?php esc_html_e( 'Shipping address', 'customize-my-account-for-woocommerce' ); ?></p>
                <address>
                    <?php echo wp_kses_post( $order->get_formatted_shipping_address() ?: esc_html__( 'Same as billing', 'customize-my-account-for-woocommerce' ) ); ?>
                </address>
            </div>
        </div>

        <!-- ---- CTA buttons ---- -->
        <div class="or-cta-row">
            <?php if ( $order->has_status( 'failed' ) ) : ?>
                <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="or-btn or-btn--primary">
                    &#128179; <?php esc_html_e( 'Pay for this order', 'customize-my-account-for-woocommerce' ); ?>
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url( wc_get_endpoint_url( 'view-order', $order->get_id(), wc_get_page_permalink( 'myaccount' ) ) ); ?>"
                   class="or-btn or-btn--primary">
                    &#128196; <?php esc_html_e( 'View order details', 'customize-my-account-for-woocommerce' ); ?>
                </a>
            <?php endif; ?>

            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="or-btn">
                &#128722; <?php esc_html_e( 'Continue shopping', 'customize-my-account-for-woocommerce' ); ?>
            </a>

            <?php if ( $order->is_download_permitted() ) : ?>
                <a href="<?php echo esc_url( wc_get_endpoint_url( 'downloads', '', wc_get_page_permalink( 'myaccount' ) ) ); ?>"
                   class="or-btn">
                    &#11015; <?php esc_html_e( 'Your downloads', 'customize-my-account-for-woocommerce' ); ?>
                </a>
            <?php endif; ?>

            <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
            <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
        </div>

    </div><!-- .or-wrap -->

<?php endif; ?>