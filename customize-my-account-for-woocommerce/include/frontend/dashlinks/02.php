<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

?>
<div class="wcmtx-my-account-links wcmtx-grid dash-<?php echo $dash_style; ?>">
    <?php unset($wcmamtx_tabs['customer-logout']); ?>
    <?php unset($wcmamtx_tabs['dashboard']); ?>
    <?php foreach ( $wcmamtx_tabs as $key => $value ) : 


        $should_show = 'yes';




        if (isset($value['show']) && ($value['show'] == "no")) {
            
           $should_show = 'no';
           
       }

       if (isset($value['visibleto']) && ($value['visibleto'] != "all")) {

        $allowedroles  = isset($value['roles']) ? $value['roles'] : "";

        $allowedusers  = isset($value['users']) ? $value['users'] : array();

        $is_visible    = wcmamtx_check_role_visibility($allowedroles,$value['visibleto'],$allowedusers);

    } else {

        $is_visible = 'yes';
    }


     if (isset($value['wcmamtx_type']) && (($value['wcmamtx_type'] == "separater") || ($value['wcmamtx_type'] == "heading"))) {

         $should_show = 'no';
     }




    
    if (isset($value['endpoint_name']) && ($value['endpoint_name'] != '')) {
        $name = $value['endpoint_name'];
    } else {
        $name = $value;
    }  

    

    $wcmamtx_type = isset($value['wcmamtx_type']) ? $value['wcmamtx_type'] : "default";

    $icon_source = isset($value['icon_source']) ? $value['icon_source'] : "default";

    $hide_in_link_toggle = isset($value['hide_dashboard_links']) && ($value['hide_dashboard_links'] == "01") ? "enabled" : "disabled";

    if (isset($hide_in_link_toggle) && ($hide_in_link_toggle == "enabled")) {
        
       $should_show = 'no';
       
   }

   $third_party = isset($value['third_party']) ? $value['third_party'] : null; 

   $third_party_go_ahead = 'yes';

   if (isset($third_party)) {
     
       $third_party_go_ahead = wcmamtx_third_party_goahead_check($key);

       if ($third_party_go_ahead == "no") {
        $should_show = 'no';
    }
}




            /**
             *  dashboard background color data starts
             */

            $default_color = wcmamtx_get_default_tab_color($key);



            $default_color = isset($value['dash_back_color']) ? $value['dash_back_color'] : $default_color;



            $default_color_font = '#334155';

            $default_color_font = isset($value['dash_font_color']) ? $value['dash_font_color'] : $default_color_font;


            /**
             *  dashboard background color data ends
             */


            if (($wcmamtx_type != "group") && ($should_show == 'yes') && ( $is_visible == "yes")) {
             $key = isset($value['endpoint_key']) ? $value['endpoint_key'] : $key;

             $wcmamtx_plugin_options = (array) get_option('wcmamtx_plugin_options');

             $ajax_class = isset($wcmamtx_plugin_options['ajax_navigation']) && ($wcmamtx_plugin_options['ajax_navigation'] == "yes") ? "wcmamtx_ajax_enabled" : "";
             ?>
             <div class="wcmamtx_dashboard_link <?php echo esc_attr( $key ); ?>-link <?php echo $ajax_class; ?>" style="background-color: <?php echo $default_color; ?>; color:<?php echo $default_color_font; ?>;">
                
                <a href="<?php echo wcmamtx_get_account_endpoint_url( $key ); ?>" style="color:<?php echo $default_color_font; ?>;">

                    <?php wcmamtx_counter_bubble($key,$value); ?>
                    
                    <p class="wcmtx_icon_src" style="color:<?php echo $default_color_font; ?>;">
                       <?php wcmamtx_get_account_menu_li_icon_html($icon_source,$value,$key); ?>
                    </p>

                   <?php echo esc_html( $name ); ?>
                   
               </a>
           </div>
       <?php } ?>
   <?php endforeach; ?>
</div>