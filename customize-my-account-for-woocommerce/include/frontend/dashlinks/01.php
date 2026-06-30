<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

?>
<div class="wcmtx_def1-dashboard">
    <?php unset($wcmamtx_tabs['dashboard']); ?>

    <?php 



    foreach ( $wcmamtx_tabs as $key => $value ) {

        if (isset($value['endpoint_name']) && ($value['endpoint_name'] != '')) {
            $name = $value['endpoint_name'];
        } else {
            $name = $value;
        }  

        $default_desc_text_link = wcmamtx_deshlink_default_description($key,$name);

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


        $default_color = wcmamtx_get_default_tab_color($key);



        $default_color = isset($value['dash_back_color']) ? $value['dash_back_color'] : $default_color;



        $default_color_font = '#334155';

        $default_color_font = isset($value['dash_font_color']) ? $value['dash_font_color'] : $default_color_font;




        if (($wcmamtx_type != "group") && ($should_show == 'yes') && ( $is_visible == "yes")) {

                $key = isset($value['endpoint_key']) ? $value['endpoint_key'] : $key;

                $wcmamtx_plugin_options = (array) get_option('wcmamtx_plugin_options');

                $ajax_class = isset($wcmamtx_plugin_options['ajax_navigation']) && ($wcmamtx_plugin_options['ajax_navigation'] == "yes") ? "wcmamtx_ajax_enabled" : "";


                $default_desc_text = isset($value['default_desc_text']) ? $value['default_desc_text'] : $default_desc_text_link;

                $default_desc_icon_colors = array(
                    'orders'          => esc_html__('#f97316','customize-my-account-for-woocommerce'),
                    'downloads'       => esc_html__('#22c55e','customize-my-account-for-woocommerce'),
                    'edit-address'    => esc_html__('#ef4444','customize-my-account-for-woocommerce'),
                    'edit-account'    => esc_html__('#8b5cf6','customize-my-account-for-woocommerce'),
                );


               $default_desc_color = isset($default_desc_icon_colors[$key]) ? $default_desc_icon_colors[$key] : "#3b82f6";

               $default_desc_color = isset($value['icon_color_ds']) ? $value['icon_color_ds'] : $default_desc_color;



               ?>

               <?php if ( isset( $guest_modal_popup ) && $guest_modal_popup === 'yes' ) : ?>
               <a href="#" class="wcmtx_def1-card <?php echo esc_attr( $key ); ?> wcmamtx-guest-popup-trigger" data-redirect-url="<?php echo esc_url( wcmamtx_get_account_endpoint_url( $key ) ); ?>" style="color:<?php echo $default_color_font; ?>;">
               <?php else : ?>
               <a href="<?php echo wcmamtx_get_account_endpoint_url( $key ); ?>" class="wcmtx_def1-card <?php echo esc_attr( $key ); ?>" style="color:<?php echo $default_color_font; ?>;">
               <?php endif; ?>
                <?php 

                    $style_dsh = 'style="color:'.$default_desc_color.' !important;"';

                    wcmamtx_get_account_menu_li_icon_html($icon_source,$value,$key,$style_dsh); 

                ?>

                
                <span class="wcmatx_tab_title_new_de">
                 <?php echo esc_html( $name ); ?> <?php wcmamtx_counter_bubble($key,$value); ?>
                </span>
                <?php if (isset($default_desc_text) && ($default_desc_text != "")) { ?>
                    <span class="wcmatx_tab_desc_below_dashlink"><?php echo esc_html( $default_desc_text ); ?></span>
                <?php } ?>
                </a>

        <?php } ?>

    <?php } ?>

</div>