<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

?>
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


if (count($wcmamtx_tabs) === 1 && empty(reset($wcmamtx_tabs))) {
    $wcmamtx_tabs = $items;
}

foreach ($items as $itkey=>$itvalue) {
    if (!array_key_exists($itkey, $wcmamtx_tabs)) {

        $new_array = array($itkey=>$itvalue);
        update_option('wcmamtx_tabs_to_add_third_party',$new_array);
    }
}

$core_fields    = 'dashboard,orders,downloads,edit-address,edit-account,customer-logout';

$core_fields_array =  array(
    'dashboard'       => esc_html__('Dashboard','customize-my-account-for-woocommerce'),
    'orders'          => esc_html__('Orders','customize-my-account-for-woocommerce'),
    'downloads'       => esc_html__('Downloads','customize-my-account-for-woocommerce'),
    'edit-address'    => esc_html__('Addresses','customize-my-account-for-woocommerce'),
    'edit-account'    => esc_html__('Account Details','customize-my-account-for-woocommerce'),
    'customer-logout' => esc_html__('Log out','customize-my-account-for-woocommerce')
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


                    $core_fields_array_filter =  array(
                        'dashboard'=>'dashboard',
                        'orders'=>'orders',
                        'downloads'=>'downloads',
                        'edit-address'=>'edit-address',
                        'edit-account'=>'edit-account',
                        'customer-logout'=>'customer-logout',
                        'payment-methods'=>'payment-methods'
                    );


        foreach($wcmamtx_tabs as $gtkey=>$gtvalue) {

            if (!array_key_exists($gtkey, $core_fields_array_filter)) {
                  $third_party_check = wcmamtx_third_party_goahead_check($gtkey);

                  $wcmamtx_type = isset($gtvalue['wcmamtx_type']) ? $gtvalue['wcmamtx_type'] : "endpoint";

                  if (($third_party_check == "no") && ($wcmamtx_type == "endpoint") && (strpos($gtkey, 'custom-') === false)) {
                     unset($wcmamtx_tabs[$gtkey]);
                  }
            }

        }



$wcmamtx_layout = (array) get_option( 'wcmamtx_layout' );


$dash_style = isset($wcmamtx_layout['dash_style']) ? $wcmamtx_layout['dash_style'] : "01";


if (($dash_style == "03") || ($dash_style == "04")) {
    $dash_style = "01";
}


$dash_template = "dashlinks/$dash_style.php";

$dash_template = apply_filters("wcmamtx_override_dashlinks_template",$dash_template,$wcmamtx_layout,$wcmamtx_tabs);

$file_to_check = "wcmamtx_template/dashlinks/$dash_style.php"; // Change to your relative file path

if ( file_exists( get_stylesheet_directory() . '/' . $file_to_check ) ) {
    // The file exists in the active child theme
    $dash_template = ''.get_stylesheet_directory().'/wcmamtx_template/dashlinks/'.$dash_style.'.php';
}

include($dash_template);

?>