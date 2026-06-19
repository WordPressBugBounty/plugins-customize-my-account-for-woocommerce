<?php
/**
 * My Account Orders
 *
 * Shows orders in table view.
 *
 * This template can be overridden by copying it to your theme your-theme/sysbasics-myaccount/orders.php , for better practice create your child theme and copy it to your-child-theme/sysbasics-myaccount/orders.php.
 *
 */

defined( 'ABSPATH' ) || exit;

$wcmamtx_layout = (array) get_option( 'wcmamtx_layout' );

$order_style = isset($wcmamtx_layout['order_style']) ? $wcmamtx_layout['order_style'] : "01";




$order_template = "order/$order_style.php";

$order_template = apply_filters("wcmamtx_override_order_template",$order_template,$wcmamtx_layout);

$file_to_check = "wcmamtx_template/order/$order_style.php"; // Change to your relative file path

if ( file_exists( get_stylesheet_directory() . '/' . $file_to_check ) ) {
         // The file exists in the active child theme
	$order_template = ''.get_stylesheet_directory().'/wcmamtx_template/order/'.$order_style.'.php';
}

include($order_template);