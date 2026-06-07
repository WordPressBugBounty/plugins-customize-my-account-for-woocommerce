<?php

defined( 'ABSPATH' ) || exit;

$wcmamtx_layout = (array) get_option( 'wcmamtx_layout' );

$download_style = isset($wcmamtx_layout['download_style']) ? $wcmamtx_layout['download_style'] : "01";




$download_template = "downloads/$download_style.php";

$download_template = apply_filters("wcmamtx_override_download_template",$download_template,$wcmamtx_layout);

$file_to_check = "wcmamtx_template/order/downloads/$download_style.php"; // Change to your relative file path

if ( file_exists( get_stylesheet_directory() . '/' . $file_to_check ) ) {
         // The file exists in the active child theme
	$download_template = ''.get_stylesheet_directory().'/wcmamtx_template/order/downloads/'.$download_style.'.php';
}

include($download_template);