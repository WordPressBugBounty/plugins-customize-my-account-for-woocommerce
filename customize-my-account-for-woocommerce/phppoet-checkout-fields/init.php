<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


 if( !defined( 'pcfme_PLUGIN_URL' ) )
define( 'pcfme_PLUGIN_URL', plugin_dir_url( __FILE__ ) );





include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if (is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
	
  include dirname( __FILE__ ) . '/include/pcmfe_core_functions.php';
  include dirname( __FILE__ ) . '/include/update_checkout_fields_class.php';
  include dirname( __FILE__ ) . '/include/add_order_meta_fields_class.php';
  include dirname( __FILE__ ) . '/include/manage_extrafield_class.php';
  include dirname( __FILE__ ) . '/include/admin/pcfme_admin_settings.php';
  include dirname( __FILE__ ) . '/easy-checkout-file-upload/easy-checkout-file-upload.php';
  include dirname( __FILE__ ) . '/my-address-fields/init.php';
}






$plugin_data = get_plugin_data( __FILE__,false ,false );
$plugin_version = $plugin_data['Version'];



?>