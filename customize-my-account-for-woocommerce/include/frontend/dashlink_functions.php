<?php
$plugin_options = (array) get_option( 'wcmamtx_pro_settings' );

if ( (isset($plugin_options['disable_dashboard_links'])) && ($plugin_options['disable_dashboard_links'] == "yes")) {
    return;
}

$wcmamtx_tabs          =  (array) get_option('wcmamtx_advanced_settings');

$wcmamtx_layout = (array) get_option( 'wcmamtx_layout' );

$default_column = isset($wcmamtx_layout['columns']) ? $wcmamtx_layout['columns'] : "03";

$dash_style = isset($wcmamtx_layout['dash_style']) ? $wcmamtx_layout['dash_style'] : "01";

$items                 =  wc_get_account_menu_items();

foreach ($items as $itkey=>$itvalue) {
    if (!array_key_exists($itkey, $wcmamtx_tabs)) {

        $new_array = array($itkey=>$itvalue);
        update_option('wcmamtx_tabs_to_add_third_party',$new_array);
    }
}

$core_fields    = 'dashboard,orders,downloads,edit-address,edit-account,customer-logout';

$core_fields_array =  array(
    'dashboard'       => esc_html__('Dashboard','woocommerce'),
    'orders'          => esc_html__('Orders','woocommerce'),
    'downloads'       => esc_html__('Downloads','woocommerce'),
    'edit-address'    => esc_html__('Addresses','woocommerce'),
    'edit-account'    => esc_html__('Account Details','woocommerce'),
    'customer-logout' => esc_html__('Log out','woocommerce')
);






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



if (!isset($wcmamtx_tabs) || (sizeof($wcmamtx_tabs) == 1)) {

    $wcmamtx_tabs = $items;

}


$wcmamtx_tabs   = apply_filters('wcmamtx_override_dashlinks',$wcmamtx_tabs);


include("dashlinks/default.php");
?>