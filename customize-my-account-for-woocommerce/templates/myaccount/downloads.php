<?php
/**
 * Downloads
 *
 * Shows downloads on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/downloads.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$plugin_options = (array) get_option( 'wcmamtx_plugin_options' );

$default_value = isset($plugin_options['custom_templates']['downloads']) ? $plugin_options['custom_templates']['downloads'] : "default";

if ($default_value != "default") {

    $contentElementor = "";

    if (class_exists("\\Elementor\\Plugin")) {
        $post_ID = $plugin_options['custom_templates']['downloads'];
        $pluginElementor = \Elementor\Plugin::instance();
        $contentElementor = $pluginElementor->frontend->get_builder_content($post_ID);
    }

    echo $contentElementor;

} else {

$downloads     = WC()->customer->get_downloadable_products();
$has_downloads = (bool) $downloads;

do_action( 'woocommerce_before_account_downloads', $has_downloads ); ?>

<?php if ( $has_downloads ) : ?>

	<?php do_action( 'woocommerce_before_available_downloads' ); ?>

	<?php do_action( 'woocommerce_available_downloads', $downloads ); ?>

	<?php do_action( 'woocommerce_after_available_downloads' ); ?>

<?php else : ?>
	<div class="woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
			<?php esc_html_e( 'Browse products', 'customize-my-account-for-woocommerce' ); ?>
		</a>
		<?php esc_html_e( 'No downloads available yet.', 'customize-my-account-for-woocommerce' ); ?>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_downloads', $has_downloads ); ?>
<?php } ?>