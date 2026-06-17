<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to your theme your-theme/sysbasics-myaccount/dashboard.php , for better practice create your child theme and copy it to your-child-theme/sysbasics-myaccount/dashboard.php.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);

$advanced_settings = (array) get_option( 'wcmamtx_advanced_settings' );







if (isset($advanced_settings['dashboard'])) {


	$content_dash = isset($advanced_settings['dashboard']['content_dash']) ? $advanced_settings['dashboard']['content_dash'] : "";

	$allowed_html = wp_kses_allowed_html( 'post' );

    $content_dash = wp_kses( $content_dash, $allowed_html );


	echo apply_filters('the_content',$content_dash);

} else {

    

}



?>

<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
