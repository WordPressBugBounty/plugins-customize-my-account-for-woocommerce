<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );



$wcmamtx_tabs   =  (array) get_option('wcmamtx_advanced_settings');

$wcmamtx_pro_settings  = (array) get_option('wcmamtx_pro_settings'); 

$items          =  wc_get_account_menu_items();

$core_fields    = 'dashboard,orders,downloads,edit-address,edit-account,customer-logout';

$core_fields_array =  array(
    'orders'          => get_option( 'woocommerce_myaccount_orders_endpoint', 'orders' ),
    'downloads'       => get_option( 'woocommerce_myaccount_downloads_endpoint', 'downloads' ),
    'edit-address'    => get_option( 'woocommerce_myaccount_edit_address_endpoint', 'edit-address' ),
    'payment-methods' => get_option( 'woocommerce_myaccount_payment_methods_endpoint', 'payment-methods' ),
    'edit-account'    => get_option( 'woocommerce_myaccount_edit_account_endpoint', 'edit-account' ),
    'customer-logout' => get_option( 'woocommerce_logout_endpoint', 'customer-logout' ),
  );


$frontend_menu_items = get_option('wcmamtx_frontend_items');

if (!isset($frontend_menu_items) || ($frontend_menu_items == "")) {
    update_option('wcmamtx_frontend_items',$items);
}

$date_today = date("Ymd");

$frontend_menu_items_updated_time = get_option('frontend_menu_items_updated_time');

if ($date_today > $frontend_menu_items_updated_time) {
    update_option('frontend_menu_items_updated',$items);
    update_option('frontend_menu_items_updated_time',$date_today);
}




foreach ($items as $ikey=>$ivalue) {

    if (!array_key_exists($ikey, $wcmamtx_tabs) && !array_key_exists($ikey, $core_fields_array) ) {
        
        $match_index = 0;

        foreach ($wcmamtx_tabs as $tkey=>$tvalue) {
            if (isset($tvalue['endpoint_key']) && ($tvalue['endpoint_key'] == $ikey)) {
                $match_index++;
            }
        }

        if ($match_index == 0) {
            $wcmamtx_tabs[$ikey] = array(
              'show' => 'yes',
              'third_party'  => 'yes',
              'endpoint_key' => $ikey,
              'wcmamtx_type' => 'endpoint',
              'parent'       => 'none',
              'endpoint_name'=> $ivalue,
          );   
        }           

    }
}

$plugin_options = get_option('wcmamtx_plugin_options');


$menu_shape = 'vertical';



if (isset($plugin_options['horizontal_menu']) && ($plugin_options['horizontal_menu'] == "yes")) {

    $menu_shape = 'horizontal';

} else {

    $menu_shape = 'vertical';
}

$icon_position  = 'right';
$icon_extra_class = '';

if (!is_array($wcmamtx_tabs)) { 
    $wcmamtx_tabs = $items;
    
}

if (!isset($wcmamtx_tabs) || (sizeof($wcmamtx_tabs) === 1) || isset($wcmamtx_tabs[0])) {
    $wcmamtx_tabs = $items;
    
}

if (isset($plugin_options['icon_position']) && ($plugin_options['icon_position'] != '')) {
    $icon_position = $plugin_options['icon_position'];
}

if (isset($plugin_options['menu_position']) && ($plugin_options['menu_position'] != '')) {
    $menu_position = $plugin_options['menu_position'];
}



switch($icon_position) {
	case "right":
	   $icon_extra_class = "wcmamtx_custom_right";
	break;

	case "left":
	   $icon_extra_class = "wcmamtx_custom_left";
	break;

	default:
	   $icon_extra_class = "wcmamtx_custom_right";
	break;
}

$menu_position_extra_class = "";

if (isset($menu_position) && ($menu_position != '')) {
    switch($menu_position) {
        case "left":
        $menu_position_extra_class = "wcmamtx_menu_left";
        break;

        case "right":
        $menu_position_extra_class = "wcmamtx_menu_right";
        break;

        default:
        $menu_position_extra_class = "";
        break;
    }
}






if ($menu_shape == 'vertical') {

   
    include ("vertical_menu_shape.php");
   


  
 
} else {

include ("menu_default.php");

}

do_action( 'woocommerce_after_account_navigation' ); ?>