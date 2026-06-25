<?php

defined( 'ABSPATH' ) || exit;

$notes = $order->get_customer_order_notes();


$wcmamtx_layout = (array) get_option( 'wcmamtx_layout' );

$view_order_style = isset($wcmamtx_layout['view_order_style']) ? $wcmamtx_layout['view_order_style'] : "01";




$view_order_template = "view-order/$view_order_style.php";

$view_order_template = apply_filters("wcmamtx_override_view_order_template",$view_order_template,$wcmamtx_layout);

$file_to_check = "wcmamtx_template/view-order/$view_order_style.php"; // Change to your relative file path

if ( file_exists( get_stylesheet_directory() . '/' . $file_to_check ) ) {
         // The file exists in the active child theme
	$view_order_template = ''.get_stylesheet_directory().'/wcmamtx_template/view-order/'.$view_order_style.'.php';
}


include($view_order_template);