<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$wcmamtx_layout = (array) get_option( 'wcmamtx_layout' );

$sidebar_style = "01";

$extra_nav_sidebar_class = "";

if ($sidebar_style == "02") {
    $extra_nav_sidebar_class = "wcmamtx_float_sidebar_right";
}

?>
<nav class="wcmam-nav wcmam-style-1 <?php echo $extra_nav_sidebar_class; ?> <?php echo $menu_position_extra_class; ?>">

    <?php

    $show_avatar = 'yes';

    $avatar_settings = (array) get_option( 'wcmamtx_avatar_settings' );


    if (array_key_exists(0, $avatar_settings)) {


        $avatar_settings['intro_text_hello'] = "yes";
        $avatar_settings['disable_avatar'] = "yes";
        $avatar_settings['custom_avatar_content'] = "yes";

    }

    if (isset($avatar_settings['disable_avatar']) && ($avatar_settings['disable_avatar'] == "yes")) {

        $show_avatar = 'yes';
    } else {
        $show_avatar = 'no';
    }




    if ($show_avatar == 'yes') {
        echo (new wcmamtx_upload_avatar_tab())->wcmamtx_shortcode();
    }


    



    if (isset($avatar_settings['custom_avatar_content']) && ($avatar_settings['custom_avatar_content'] == "yes") && ($show_avatar == "yes")) {

        $editor_content_avatar = isset($avatar_settings['content_avatar']) ? $avatar_settings['content_avatar'] : '<p class="wcmamtx_default_text_below_avatar" style="text-align: center;">Hello <strong>{username}</strong> (not <strong>{username}</strong>? <a href="{user_logout_link}">Log out</a>)</p>';

        

        $editor_content_avatar = wcmamtx_parse_smart_tag_function($editor_content_avatar);

        echo $editor_content_avatar;

    }

    $wcmamtx_layout = (array) get_option( 'wcmamtx_layout' );

    $default_style = isset($wcmamtx_layout['style']) ? $wcmamtx_layout['style'] : "01";

    ?>

    <?php foreach ( $wcmamtx_tabs as $key => $value ) {

        if (isset($value['endpoint_name']) && ($value['endpoint_name'] != '')) {
                $name = $value['endpoint_name'];
            } else {
                $name = $value;
            }

            $should_show = 'yes';


            if (isset($value['visibleto']) && ($value['visibleto'] != "all")) {

                $allowedroles  = isset($value['roles']) ? $value['roles'] : "";

                $allowedusers  = isset($value['users']) ? $value['users'] : array();

                $is_visible    = wcmamtx_check_role_visibility($allowedroles,$value['visibleto'],$allowedusers);
                
            } else {

                $is_visible = 'yes';
            }



            if (isset($value['show']) && ($value['show'] == "no")) {
                
                 $should_show = 'no';
                
            }


            if (isset($value['class']) && ($value['class'] != '')) {
                $extraclass = str_replace(',',' ', $value['class']);
            } else {
                $extraclass = '';
            }

            if (isset($value['endpoint_key']) && ($value['endpoint_key'] != '')) {
                $key = $value['endpoint_key'];
            }

            if (isset($value['parent']) && ($value['parent'] != '')) {
                $parent = $value['parent'];
            } else {
                $parent = 'none';
            }


            
            $icon_source       = isset($value['icon_source']) ? $value['icon_source'] : "default";

            $hide_in_navigation = isset($value['hide_in_navigation']) && ($value['hide_in_navigation'] == "01") ? "enabled" : "disabled";

            if (isset($hide_in_navigation) && ($hide_in_navigation == "enabled")) {
                
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

            if (($should_show == "yes") && ($is_visible == "yes")) {
            
               

                   

                if (($parent == "none")) {


                    $wsmt_li_fontsize = get_theme_mod('wsmt_li_fontsize');

                    $font_size = isset($wsmt_li_fontsize) ? $wsmt_li_fontsize : "16px";

                    $wsmt_li_padding = get_theme_mod('wsmt_li_padding');

                    $padding_left = isset($wsmt_li_padding) ? $wsmt_li_padding : "0px";


                    if (isset($value['wcmamtx_type']) && ($value['wcmamtx_type'] == "separater")) {

                        echo '<br/>';

                    } else {



                        $wcmamtx_type = isset($value['wcmamtx_type']) && is_array($value) ? $value['wcmamtx_type'] : "endpoint";

                        ?>




                            <a class="woocommerce-MyAccount-navigation-link_a <?php echo wcmamtx_get_account_menu_item_classes( $key , $value ); ?> <?php echo  $wcmamtx_type; ?> <?php echo $extraclass; ?> <?php if ($icon_source == "custom") { echo $icon_extra_class; } ?>"  href="<?php echo wcmamtx_get_account_endpoint_url( $key ); ?>" <?php if (isset($wcmamtx_type) && ($wcmamtx_type == "link") && (isset($value['link_targetblank'])) && ($value['link_targetblank'] == 01) ) { echo 'target="_blank"'; } ?>>
                                <?php wcmamtx_get_account_menu_li_icon_html($icon_source,$value,$key); ?>
                                <span class="wcmamtx_sticky_icon_name">
                                    <?php echo esc_html( $name ); ?>

                                </span>
                                <?php 
                                $hide_sidebar = isset($value['hide_sidebar']) && ($value['hide_sidebar'] == "01") ? "yes" : "no";

                                if ($hide_sidebar == "no") {
                                    echo wcmamtx_counter_bubble($key,$value,"yes"); 
                                }

                                ?>
                            </a>
                        
                        <?php
                    }



                }

            }

    } ?>

    
</nav>