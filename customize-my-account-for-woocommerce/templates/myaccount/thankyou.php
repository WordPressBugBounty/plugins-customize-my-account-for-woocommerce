<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_thankyou $thankyou
 */

defined( 'ABSPATH' ) || exit;

$wcmamtx_layout = (array) get_option( 'wcmamtx_layout' );

$thankyou_style = isset($wcmamtx_layout['thankyou_style']) ? $wcmamtx_layout['thankyou_style'] : "01";




$thankyou_template = "thankyou/$thankyou_style.php";

$thankyou_template = apply_filters("wcmamtx_override_thankyou_template",$thankyou_template,$wcmamtx_layout);

$file_to_check = "wcmamtx_template/thankyou/$thankyou_style.php"; // Change to your relative file path

if ( file_exists( get_stylesheet_directory() . '/' . $file_to_check ) ) {
         // The file exists in the active child theme
	$thankyou_template = ''.get_stylesheet_directory().'/wcmamtx_template/thankyou/'.$thankyou_style.'.php';
}

include($thankyou_template);
?>