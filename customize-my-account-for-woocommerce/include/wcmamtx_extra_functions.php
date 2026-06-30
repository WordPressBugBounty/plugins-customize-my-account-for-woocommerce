<?php

include('wcmamtx_countof_functions.php');

if ( ! function_exists( 'wcmamtx_login_page_inline_css' ) ) {
    function wcmamtx_login_page_inline_css() {
        if ( ! is_page( wc_get_page_id( 'myaccount' ) ) ) return;
        if ( ! function_exists( 'wcmamtx_get_layout' ) ) return;
        $layout = wcmamtx_get_layout();
        if ( empty( $layout['formlogin_layout_override'] ) || $layout['formlogin_layout_override'] !== '01' ) return;

        $grad_start  = sanitize_hex_color( $layout['login_page_gradient_start'] ?? '' ) ?: '#667eea';
        $grad_end    = sanitize_hex_color( $layout['login_page_gradient_end']   ?? '' ) ?: '#764ba2';
        $bg_image    = ! empty( $layout['login_page_bg_image'] ) ? $layout['login_page_bg_image'] : '';
        $bg_size_raw = ! empty( $layout['login_page_bg_size'] )  ? $layout['login_page_bg_size']  : 'cover';
        $bg_size     = ( $bg_size_raw === 'cover' || empty( $bg_size_raw ) ) ? 'cover' : ( absint( $bg_size_raw ) . '%' );
        $text_color  = sanitize_hex_color( $layout['login_page_text_color'] ?? '' ) ?: '';
        $badge_bg    = sanitize_hex_color( $layout['login_page_badge_bg']    ?? '' ) ?: '';

        $css = '';

        if ( $bg_image ) {
            $css .= '.wc-auth-left{background:url(' . esc_url( $bg_image ) . ') center/' . $bg_size . ' no-repeat ' . $grad_start . '!important;}';
        } else {
            $css .= '.wc-auth-left{background:linear-gradient(135deg,' . $grad_start . ' 0%,' . $grad_end . ' 100%)!important;}';
        }

        if ( $text_color ) {
            $css .= '.wc-auth-animated-headline{background:none!important;-webkit-text-fill-color:' . $text_color . '!important;color:' . $text_color . '!important;animation:none!important;}';
            $css .= '.wc-auth-left-subtitle{color:' . $text_color . '!important;}';
            $css .= '.wc-auth-cta-badge{color:' . $text_color . '!important;}';
        }

        if ( $badge_bg ) {
            $css .= '.wc-auth-cta-badge{background:' . $badge_bg . '!important;border-color:' . $badge_bg . '!important;}';
        }

        if ( $css ) {
            echo '<style id="wcmamtx-lp-css">' . wp_strip_all_tags( $css ) . '</style>';
        }
    }
    add_action( 'wp_head', 'wcmamtx_login_page_inline_css', 1 );
}

if ( ! function_exists( 'wcmamtx_ajax_login_handler' ) ) {
    function wcmamtx_ajax_login_handler() {
        if ( ! check_ajax_referer( 'woocommerce-login', 'woocommerce-login-nonce', false ) ) {
            wp_send_json_error( [ 'message' => __( 'Security check failed.', 'woocommerce' ) ] );
        }

        $username = isset( $_POST['username'] ) ? sanitize_user( wp_unslash( trim( $_POST['username'] ) ) ) : '';
        $password = isset( $_POST['password'] ) ? wp_unslash( $_POST['password'] ) : '';
        $remember = ! empty( $_POST['rememberme'] );
        $redirect = isset( $_POST['redirect'] ) ? wp_sanitize_redirect( wp_unslash( $_POST['redirect'] ) ) : wc_get_page_permalink( 'myaccount' );

        if ( empty( $username ) ) {
            wp_send_json_error( [ 'message' => __( 'Username or email is required.', 'woocommerce' ) ] );
        }
        if ( empty( $password ) ) {
            wp_send_json_error( [ 'message' => __( 'Password is required.', 'woocommerce' ) ] );
        }

        if ( is_email( $username ) ) {
            $user_by_email = get_user_by( 'email', $username );
            if ( $user_by_email ) {
                $username = $user_by_email->user_login;
            }
        }

        $user = wp_signon( [
            'user_login'    => $username,
            'user_password' => $password,
            'remember'      => $remember,
        ], is_ssl() );

        if ( is_wp_error( $user ) ) {
            wp_send_json_error( [ 'message' => wp_strip_all_tags( $user->get_error_message() ) ] );
        }

        $redirect = apply_filters( 'woocommerce_login_redirect', $redirect, $user );
        wp_send_json_success( [ 'redirect' => $redirect ] );
    }
    add_action( 'wp_ajax_nopriv_wcmamtx_ajax_login', 'wcmamtx_ajax_login_handler' );
}

if ( ! function_exists( 'wcmamtx_ajax_register_handler' ) ) {
    function wcmamtx_ajax_register_handler() {
        if ( ! check_ajax_referer( 'woocommerce-register', 'woocommerce-register-nonce', false ) ) {
            wp_send_json_error( [ 'message' => __( 'Security check failed.', 'woocommerce' ) ] );
        }

        $username = isset( $_POST['username'] ) ? sanitize_user( wp_unslash( trim( $_POST['username'] ) ) ) : '';
        $email    = isset( $_POST['email'] )    ? sanitize_email( wp_unslash( $_POST['email'] ) )          : '';
        $password = isset( $_POST['password'] ) ? wp_unslash( $_POST['password'] )                         : '';

        if ( empty( $email ) || ! is_email( $email ) ) {
            wp_send_json_error( [ 'message' => __( 'Please provide a valid email address.', 'woocommerce' ) ] );
        }

        $user_id = wc_create_new_customer( $email, $username, $password );

        if ( is_wp_error( $user_id ) ) {
            wp_send_json_error( [ 'message' => wp_strip_all_tags( $user_id->get_error_message() ) ] );
        }

        wp_set_current_user( $user_id );
        wp_set_auth_cookie( $user_id, true );

        $redirect = apply_filters( 'woocommerce_registration_redirect', wc_get_page_permalink( 'myaccount' ) );
        wp_send_json_success( [ 'redirect' => $redirect ] );
    }
    add_action( 'wp_ajax_nopriv_wcmamtx_ajax_register', 'wcmamtx_ajax_register_handler' );
}

if ( ! function_exists( 'wcmamtx_ajax_lost_password_handler' ) ) {
    function wcmamtx_ajax_lost_password_handler() {
        if ( ! check_ajax_referer( 'lost_password', 'woocommerce-lost-password-nonce', false ) ) {
            wp_send_json_error( [ 'message' => __( 'Security check failed.', 'woocommerce' ) ] );
        }

        $user_login = isset( $_POST['user_login'] ) ? sanitize_text_field( wp_unslash( $_POST['user_login'] ) ) : '';

        if ( empty( $user_login ) ) {
            wp_send_json_error( [ 'message' => __( 'Please enter your username or email address.', 'woocommerce' ) ] );
        }

        $result = retrieve_password( $user_login );

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( [ 'message' => wp_strip_all_tags( $result->get_error_message() ) ] );
        }

        wp_send_json_success( [ 'message' => __( 'Password reset email sent. Please check your inbox.', 'woocommerce' ) ] );
    }
    add_action( 'wp_ajax_nopriv_wcmamtx_ajax_lost_password', 'wcmamtx_ajax_lost_password_handler' );
}

if ( ! function_exists( 'wcmamtx_get_layout' ) ) {
    function wcmamtx_get_layout() {
        static $cache = null;
        if ( $cache === null ) {
            $cache = (array) get_option( 'wcmamtx_layout' );
        }
        return $cache;
    }
}

// Social login functions starts

/**
 * Clear the wcmamtx spending chart transient cache whenever an order
 * is created or its status changes, so the chart always shows fresh data.
 */

if (!function_exists('wcmamtx_bust_spending_cache')) {

    function wcmamtx_bust_spending_cache( $order_id ) {
        $order = wc_get_order( $order_id );
        if ( ! $order ) return;
        $user_id = $order->get_customer_id();
        if ( $user_id ) {
            delete_transient( 'wcmamtx_spending_' . $user_id );
        }
    }
}

if (!function_exists('wcmamtx_get_clean_design_theme_array')) {


    function wcmamtx_get_clean_design_theme_array() {

        //echo wp_get_theme();

         $supported_themes = array(
        'Astra',
        'Astra Child',
        'Divi',
        'Divi Child',
        'Woodmart',
        'Woodmart Child',
        'Kadence',
        'Kadence Child',
        'Avada',
        'Avada Child',
        'GeneratePress',
        'GeneratePress Child',
        'Blocksy',
        'Blocksy Child',
        'OceanWP',
        'OceanWP Child',
        'Extendable',
        'Extendable Child',
        'Customify',
        'Customify Child',
        'Xstore',
        'Xstore Child',
        'Shoptimizer',
        'Shoptimizer Child',
        'Porto',
        'Porto Child',
        'Pepper',
        'Pepper Child',
        'Electro',
        'Electro Child',
        'Martfury',
        'Martfury Child',
        'Motta',
        'Motta Child',
        'Betheme',
        'Betheme Child',
        'The7',
        'The7 Child',
        'Neve',
        'Neve Child',
        'Hello Elementor',
        'Hello Elementor Child',
        'Orchid Store',
        'Orchid Store Child',
        'Royal Elementor Kit',
        'Royal Elementor Kit Child',
        'Bluehost Blueprint',
        'Bluehost Blueprint Child',
        'Elessi Theme',
        'Elessi Theme Child'
    );

    return in_array((string) wp_get_theme(), $supported_themes, true)
        ? '02'
        : '01';
    }

}


if (!function_exists('wcmamtx_get_nav_widget_default_array')) {


    function wcmamtx_get_nav_widget_default_array() {

         $supported_themes = array(
        'Astra',
        'Astra Child',
        'GeneratePress',
        'GeneratePress Child',
        'Hello Elementor',
        'Hello Elementor Child',
        'Divi',
        'Divi Child',
        'Kadence',
        'Kadence Child',
        'Avada',
        'Avada Child',
        'My Listing',
        'My Listing Child',
        'Elessi Theme',
        'Elessi Theme Child',
        'Customify',
        'Customify Child',
        'Motta',
        'Motta Child',
        );

         return $supported_themes;

    }

}

if (!function_exists('wcmamtx_act_goahead_check_verified')) {


    function wcmamtx_act_goahead_check_verified() {

        $act_goahead = 'yes';

        $act_goahead_date = get_option("wcmamtx_act_date_free");

        $date_today       = '20260621';

        if ($act_goahead_date >= $date_today) {
          $act_goahead = 'no';
        }

        return $act_goahead;

  }

}


if (!function_exists('wcmamtx_get_account_menu_items')) {


    function wcmamtx_get_account_menu_items() {

       $items                 =  wc_get_account_menu_items();

       $wcmamtx_tabs          =  (array) get_option('wcmamtx_advanced_settings');

       if (count($wcmamtx_tabs) === 1 && empty(reset($wcmamtx_tabs))) {
            $wcmamtx_tabs = $items;
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

    if (!isset($wcmamtx_tabs) || (count($wcmamtx_tabs) == 1)) {

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

                  $act_goahead = "yes";

                  $act_goahead = wcmamtx_act_goahead_check_verified();


                    if ($act_goahead == "yes") {
                      if (($third_party_check == "no") && ($wcmamtx_type == "endpoint") && (strpos($gtkey, 'custom-endpoint-') === false))   {
                         unset($wcmamtx_tabs[$gtkey]);
                        }
                    } else if ($act_goahead == "no") {
                        if (($third_party_check == "no") && ($wcmamtx_type == "endpoint")) {
                            unset($wcmamtx_tabs[$gtkey]);
                        }
                   }


                  
            }

        }



    return $wcmamtx_tabs;

}

}



if (!function_exists('wcmamtx_get_nav_widget_array_theme')) {


    function wcmamtx_get_nav_widget_array_theme() {

        //echo wp_get_theme();

         $supported_themes = wcmamtx_get_nav_widget_default_array();

    return in_array((string) wp_get_theme(), $supported_themes, true)
        ? 'yes'
        : 'no';
    }

}


if (!function_exists('wcmamtx_get_nav_widget_array_theme2')) {


    function wcmamtx_get_nav_widget_array_theme2() {

        //echo wp_get_theme();

         $supported_themes = wcmamtx_get_nav_widget_default_array();

    return in_array((string) wp_get_theme(), $supported_themes, true)
        ? '01'
        : '02';
    }

}


if (!function_exists('wcmamtx_get_nav_widget_default_location_menu')) {


    function wcmamtx_get_nav_widget_default_location_menu() {

         $default_location = "primary";

         $menu_locations = get_nav_menu_locations();

         if (!empty($menu_locations)) {

           $default_location = array_key_first($menu_locations); 

         }

         return $default_location;

    }

}


if (!function_exists('wcmamtx_get_nav_widget_default_array_modal_login')) {


    function wcmamtx_get_nav_widget_default_array_modal_login() {

         $supported_themes = array(
        'Astra',
        'Astra Child',
        'GeneratePress',
        'GeneratePress Child',
        'Kadence',
        'Kadence Child',
        'Hello Elementor',
        'Hello Elementor Child',
        'Neve',
        'Neve Child',
        'Divi',
        'Divi Child',
        'Customify',
        'Customify Child',
        'Motta',
        'Motta Child'
        );

         return $supported_themes;

    }

}


if (!function_exists('wcmamtx_get_nav_widget_array_show_only_loggedin')) {


    function wcmamtx_get_nav_widget_array_show_only_loggedin() {

        $wcmamtx_layout = wcmamtx_get_layout();

        if (array_key_exists(0, $wcmamtx_layout)) {

            $supported_themes = wcmamtx_get_nav_widget_default_array_modal_login();

            return in_array((string) wp_get_theme(), $supported_themes, true)
            ? 'no'
            : 'yes';


        }

         return "no";

    }

}

if (!function_exists('wcmamtx_get_nav_logout_default_text')) {


    function wcmamtx_get_nav_logout_default_text() {

         if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) {
             return esc_html__('Log In / Register','customize-my-account-for-woocommerce');
         }

         return esc_html__('Log In','customize-my-account-for-woocommerce');

    }

}


if (!function_exists('wcmamtx_get_user_ip')) {


	function wcmamtx_get_user_ip() {
		return isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	}

}


/**
 * Get account menu item classes.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('sysbasics_menu_item_custom_output_premium')) {

    function sysbasics_menu_item_custom_output_premium( $item_output, $item, $depth, $args ) {

        $menu_item_classes = $item->classes;

        $frontend_url = get_permalink(get_option('woocommerce_myaccount_page_id'));

    //print_r($item);

        if ( is_array($menu_item_classes) && !in_array( 'customize-my-account-for-woocommerce-dropdown', $menu_item_classes )) {
            return $item_output;
        }


        if ( !is_user_logged_in() ) {

            $show_only_logged_in    = isset($wcmamtx_plugin_options['show_only_logged_in']) ? $wcmamtx_plugin_options['show_only_logged_in'] : "no";

            if ($show_only_logged_in == "yes") {
                return $items;
            }


            $nav_header_widget_text_logout = isset($wcmamtx_plugin_options['nav_header_widget_text_logout']) ? $wcmamtx_plugin_options['nav_header_widget_text_logout'] : esc_html__('Log In','customize-my-account-for-woocommerce');


            $Menu_link = '<li class="menu-item menu-item-type-post_type menu-item-object-page wcmamtx_menu wcmamtx_menu_logged_out"><a class="menu-link nav-top-link" aria-expanded="true" aria-haspopup="menu"  href="'.$frontend_url.'">'.$nav_header_widget_text_logout.'</a>';

            $items .= $Menu_link;

            return $items;
            
        } 

        ob_start(); 

        $frontend_url = get_permalink(get_option('woocommerce_myaccount_page_id'));


        $wcmamtx_plugin_options = (array) get_option('wcmamtx_plugin_options');

        $nav_header_widget_text = isset($wcmamtx_plugin_options['nav_header_widget_text']) ? $wcmamtx_plugin_options['nav_header_widget_text'] : esc_html__('My Account','customize-my-account-for-woocommerce');

        ?>
        <ul class="custom-sub-menu">
           <?php 

           



           $items = '';

           wcmamtx_get_menu_shortcode_content($items,$item); 

           

           ?>


       </li>
   </ul>
   <?php

   $custom_sub_menu_html = ob_get_clean();

    // Append after <a> element of the menu item targeted
   $item_output = $custom_sub_menu_html;

   return $item_output;
}
}


// Social login functions starts

if (!function_exists('wcmamtx_generate_state')) {


    function wcmamtx_generate_state() {

        $state = wp_generate_password( 32, false );

        set_transient(
            'wcmamtx_oauth_' . $state,
            true,
            15 * MINUTE_IN_SECONDS
        );

        return $state;
    }

}


if (!function_exists('wcmamtx_sb_get_customer_spending_data')) {


    function wcmamtx_sb_get_customer_spending_data( $user_id ) {
        $cache_key = 'wcmamtx_spending_' . $user_id;
        $cached = get_transient( $cache_key );
        if ( $cached !== false ) return $cached;

        $months = [];
        $totals = [];
        $monthly = [];

    // Single query: all orders in the last 12 months
        $start = date( 'Y-m-01', strtotime( '-11 months' ) );
    $end   = date( 'Y-m-t' ); // last day of current month

    $orders = wc_get_orders( [
        'customer_id'  => $user_id,
        'status'       => [ 'wc-completed', 'wc-processing' ],
        'limit'        => -1,
        'return'       => 'ids',
        'date_created' => $start . '...' . $end,
    ] );

    foreach ( $orders as $order_id ) {
        $order     = wc_get_order( $order_id );
        $month_key = $order->get_date_created()->format( 'Y-m' );
        $monthly[ $month_key ] = ( $monthly[ $month_key ] ?? 0 ) + floatval( $order->get_total() );
    }

    for ( $i = 11; $i >= 0; $i-- ) {
        $month_key = date( 'Y-m', strtotime( "-$i months" ) );
        $months[]  = date( 'M Y', strtotime( $month_key . '-01' ) );
        $totals[]  = round( $monthly[ $month_key ] ?? 0, 2 );
    }

    $result = [ 'labels' => $months, 'values' => $totals ];
    set_transient( $cache_key, $result, 3 * HOUR_IN_SECONDS );
    return $result;
}

}


/**
 * Get customer's average order value
 *
 * @param int $user_id
 * @return float
 */

if (!function_exists('wcmamtx_my_get_customer_average_order_value')) {

    function wcmamtx_my_get_customer_average_order_value( $user_id ) {

        if ( ! $user_id ) {
            return 0;
        }

        $total_spent = wc_get_customer_total_spent( $user_id );
        $order_count = wc_get_customer_order_count( $user_id );

        if ( $order_count <= 0 ) {
            return 0;
        }

        return $total_spent / $order_count;
    }
}


if (!function_exists('wcmamtx_get_google_login_url')) {


    function wcmamtx_get_google_login_url() {


        $wcmamtx_layout = wcmamtx_get_layout();

        $client_id = isset( $wcmamtx_layout['google_client_id'] ) ? $wcmamtx_layout['google_client_id'] : '';

        $state = wcmamtx_generate_state();

        $params = [
            'client_id'     => $client_id,
            'redirect_uri'  => home_url('/?wcmamtx-social=google'),
            'response_type' => 'code',
            'scope'         => 'openid email profile',
            'state'         => $state,
        ];

        return add_query_arg(
            $params,
            'https://accounts.google.com/o/oauth2/v2/auth'
        );
    }

}


if (!function_exists('wcmamtx_get_google_token')) {


    function wcmamtx_get_google_token($code) {

        $wcmamtx_layout = wcmamtx_get_layout();

        $client_id = isset( $wcmamtx_layout['google_client_id'] ) ? $wcmamtx_layout['google_client_id'] : '';
        $client_secret = isset( $wcmamtx_layout['google_client_secret'] ) ? $wcmamtx_layout['google_client_secret'] : '';

        $response = wp_remote_post(
            'https://oauth2.googleapis.com/token',
            [
                'body' => [
                    'client_id' => $client_id,
                    'client_secret' => $client_secret,
                    'code' => $code,
                    'grant_type' => 'authorization_code',
                    'redirect_uri' => home_url('/?wcmamtx-social=google'),
                ],
            ]
        );

        $body = json_decode(
            wp_remote_retrieve_body($response),
            true
        );

        return isset( $body['access_token'] ) ? $body['access_token'] : '';
    }

}


if (!function_exists('wcmamtx_get_google_user')) {


    function wcmamtx_get_google_user($token) {

        $response = wp_remote_get(
            'https://www.googleapis.com/oauth2/v2/userinfo',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]
        );

        return json_decode(
            wp_remote_retrieve_body($response),
            true
        );
    }

}


if (!function_exists('wcmamtx_login_or_create_user')) {


    function wcmamtx_login_or_create_user($social_user) {

        if ( empty( $social_user['email'] ) ) {
            wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
            exit;
        }

        $email = sanitize_email( $social_user['email'] );

        if ( ! is_email( $email ) ) {
            wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
            exit;
        }

        $user = get_user_by( 'email', $email );

        if ( ! $user ) {

            $password = wp_generate_password();

            $user_id = wc_create_new_customer(
                $email,
                sanitize_user(
                    current(
                        explode('@', $email)
                    )
                ),
                $password
            );

            if ( is_wp_error( $user_id ) ) {
                wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
                exit;
            }

            $user = get_user_by( 'id', $user_id );
        }

        wp_set_current_user( $user->ID );

        wp_set_auth_cookie( $user->ID, true );

        wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );

        exit;
    }
}

if (!function_exists('wcmamtx_get_facebook_login_url')) {

    function wcmamtx_get_facebook_login_url() {

        $wcmamtx_layout = wcmamtx_get_layout();

        $app_id = isset( $wcmamtx_layout['facebook_app_id'] ) ? $wcmamtx_layout['facebook_app_id'] : '';

        $state = wcmamtx_generate_state();

        $params = [
            'client_id'     => $app_id,
            'redirect_uri'  => home_url('/?wcmamtx-social=facebook'),
            'state'         => $state,
            'scope'         => 'email',
        ];

        return add_query_arg(
            $params,
            'https://www.facebook.com/v19.0/dialog/oauth'
        );
    }

}


if (!function_exists('wcmamtx_get_facebook_token')) {

    function wcmamtx_get_facebook_token($code) {

        $wcmamtx_layout = wcmamtx_get_layout();

        $app_id     = isset( $wcmamtx_layout['facebook_app_id'] )     ? $wcmamtx_layout['facebook_app_id']     : '';
        $app_secret = isset( $wcmamtx_layout['facebook_app_secret'] ) ? $wcmamtx_layout['facebook_app_secret'] : '';

        $url = add_query_arg( [
            'client_id'     => $app_id,
            'redirect_uri'  => home_url('/?wcmamtx-social=facebook'),
            'client_secret' => $app_secret,
            'code'          => $code,
        ], 'https://graph.facebook.com/v19.0/oauth/access_token' );

        $response = wp_remote_get( $url );

        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        return isset( $body['access_token'] ) ? $body['access_token'] : '';
    }

}


if (!function_exists('wcmamtx_get_facebook_user')) {

    function wcmamtx_get_facebook_user($token) {

        $url = add_query_arg( [
            'fields'       => 'id,name,email',
            'access_token' => $token,
        ], 'https://graph.facebook.com/me' );

        $response = wp_remote_get( $url );

        return json_decode( wp_remote_retrieve_body( $response ), true );
    }

}


// Social login functions ends


if (!function_exists('wcmamtx_deshlink_default_description')) {


    function wcmamtx_deshlink_default_description($key,$label) {
        $default_desc_text_links = array(
            'orders'             => esc_html__('View and track your orders','customize-my-account-for-woocommerce'),
            'downloads'          => esc_html__('Get your Downloads','customize-my-account-for-woocommerce'),
            'edit-address'       => esc_html__('Manage your addresses','customize-my-account-for-woocommerce'),
            'edit-account'       => esc_html__('Update your account info','customize-my-account-for-woocommerce'),
            'payment-methods'    => esc_html__('Manage your payment methods','customize-my-account-for-woocommerce'),
            'customer-logout'    => esc_html__('Logout from site','customize-my-account-for-woocommerce'),
        );

        

        $default_desc_text1 = ''.esc_html__('Manage','customize-my-account-for-woocommerce').' '.$label.'';

        $default_desc_text = isset($default_desc_text_links[$key]) ? $default_desc_text_links[$key] :  $default_desc_text1;

        return apply_filters('wcmamtx_deshlink_default_description_override',$default_desc_text);
    }

}


if (!function_exists('wcmamtx_get_avatar_default')) {


    function wcmamtx_get_avatar_default($profileuser,$avatar_size,$atts,$modal_popup = null,$nav_widget = null) {


        if ( ! is_user_logged_in() ) {
            return;
        }

        $avatar_settings = (array) get_option( 'wcmamtx_avatar_settings' );

        $default_source = isset($avatar_settings['disable_gravtar']) && ($avatar_settings['disable_gravtar'] == "yes") ? "local" : "gravtar";

        $args['extra_attr'] = '';


        

        if (($modal_popup == null) && ($nav_widget == null)) {
            $size = (int) $avatar_size;
            $args['extra_attr'] = 'style="width:'.$size.'px;height:'.$size.'px;border-radius:50%;object-fit:cover;"';
        }

        $modal_popup_class =  "";

        if ($modal_popup == true) {
            $args['class'] = "modal_popup";
            $modal_popup_class =  "modal_popup";
        }

        

        $default_value = '';
        $alt = '';

        if ($default_source == "gravtar") {
          

            $default_avatar = get_avatar($profileuser->ID ,$avatar_size,$default_value, $alt, $args);
        } else if ($default_source == "local")  {
            $custom_def_id = isset($avatar_settings['custom_default_avatar']) ? (int)$avatar_settings['custom_default_avatar'] : 0;
            $url = $custom_def_id > 0 ? (string) wp_get_attachment_url( $custom_def_id ) : '';
            if ( empty( $url ) ) {
                $url = ''.wcmamtx_PLUGIN_URL.'assets/images/default_avatar.jpg';
            }
            $args['alt'] = $url;
            $alt = $args['alt'];
            $default_avatar = sprintf(
                "<img src='%s' srcset='%s' height='%d' width='%d' %s class='avatar %s avatar-%d photo' />",
                esc_url( $url ),
                esc_url( $url ) . ' 2x',
                (int) $avatar_size,
                (int) $avatar_size,
                $args['extra_attr'],
                esc_attr( $modal_popup_class ),
                (int) $avatar_size
            );

        }

        if ( ! empty( $profileuser->sysbasics_user_avatar ) ) {
           $default_avatar = get_avatar( $profileuser->ID ,$avatar_size,$default_value, $alt, $args);
        }

        

        

        return $default_avatar;

    }

}





if (!function_exists('wcmamtx_parse_smart_tag_function')) {


    /**
     * Process the smart tags.
     *
     * @since 1.0.0
     */
    function wcmamtx_parse_smart_tag_function( $content, $endpoint = null ) {
        preg_match_all( '/\{(.*?)\}/', $content, $other_tags );

        if ( ! empty( $other_tags[1] ) ) {

            foreach ( $other_tags[1] as $key => $tag ) {
                $other_tag = explode( ' ', $tag )[0];
                switch ( $other_tag ) {

                    case 'endpoint_label':
                        
                        $content     = str_replace( '{' . $other_tag . '}', $endpoint, $content );
                        break;

                    case 'default_content':
                        $default_content = esc_html__( 'Hey {display_name}, Hope you are doing well.To modify this content, Click on Customize my account link above and visit {endpoint_label} tab.' ,'customize-my-account-for-woocommerce');

                        $default_content = wcmamtx_parse_smart_tag_function($default_content,$endpoint);
                        $content     = str_replace( '{' . $other_tag . '}', $default_content, $content );
                        break;


                    case 'admin_email':
                        $admin_email = sanitize_email( get_option( 'admin_email' ) );
                        $content     = str_replace( '{' . $other_tag . '}', $admin_email, $content );
                        break;

                    case 'site_name':
                        $site_name = get_option( 'blogname' );
                        $content   = str_replace( '{' . $other_tag . '}', $site_name, $content );
                        break;

                    case 'site_url':
                        $site_url = get_option( 'siteurl' );
                        $content  = str_replace( '{' . $other_tag . '}', $site_url, $content );
                        break;

                    

                    case 'user_ip_address':
                        $user_ip_add = wcmamtx_get_user_ip();
                        $content     = str_replace( '{' . $other_tag . '}', $user_ip_add, $content );
                        break;

                    case 'user_id':
                        $user_id = is_user_logged_in() ? get_current_user_id() : '';
                        $content = str_replace( '{' . $other_tag . '}', $user_id, $content );
                        break;

                    case 'user_email':
                        if ( is_user_logged_in() ) {
                            $user  = wp_get_current_user();
                            $email = sanitize_email( $user->user_email );
                        } else {
                            $email = '';
                        }
                        $content = str_replace( '{' . $other_tag . '}', $email, $content );
                        break;

                    case 'user_logout_link':
                        $logout_link_user = wc_logout_url();
                        $content = str_replace( '{' . $other_tag . '}', $logout_link_user, $content );
                    break;

                    case 'username':
                        if ( is_user_logged_in() ) {
                            $user = wp_get_current_user();
                            $name = sanitize_text_field( $user->user_login );
                        } else {
                            $name = '';
                        }
                        $content = str_replace( '{' . $other_tag . '}', $name, $content );
                        break;

                    case 'display_name':
                        if ( is_user_logged_in() ) {
                            $user = wp_get_current_user();
                            $name = sanitize_text_field( $user->display_name );
                        } else {
                            $name = '';
                        }
                        $content = str_replace( '{' . $other_tag . '}', $name, $content );
                        break;

                    case 'first_name':
                        if ( is_user_logged_in() ) {
                            $user = wp_get_current_user();
                            $name = sanitize_text_field( $user->user_firstname );
                        } else {
                            $name = '';
                        }
                        $content = str_replace( '{' . $other_tag . '}', $name, $content );
                        break;

                    case 'last_name':
                        if ( is_user_logged_in() ) {
                            $user = wp_get_current_user();
                            $name = sanitize_text_field( $user->user_lastname );
                        } else {
                            $name = '';
                        }
                        $content = str_replace( '{' . $other_tag . '}', $name, $content );
                        break;

                    case 'current_date':
                        $current_date = date_i18n( get_option( 'date_format' ) );
                        $content      = str_replace( '{' . $other_tag . '}', sanitize_text_field( $current_date ), $content );
                        break;
                    case 'current_time':
                        $current_time = date_i18n( get_option( 'time_format' ) );
                        $content      = str_replace( '{' . $other_tag . '}', sanitize_text_field( $current_time ), $content );
                        break;
                    case 'billing_address':
                    case 'shipping_address':
                        if ( is_user_logged_in() ) {
                            $meta_prefix = ( 'billing_address' === $other_tag ) ? 'billing_' : 'shipping_';
                            $user_id     = get_current_user_id();
                            $address     = array(
                                'first_name' => get_user_meta( $user_id, $meta_prefix . 'first_name', true ),
                                'last_name'  => get_user_meta( $user_id, $meta_prefix . 'last_name', true ),
                                'company'    => get_user_meta( $user_id, $meta_prefix . 'company', true ),
                                'address_1'  => get_user_meta( $user_id, $meta_prefix . 'address_1', true ),
                                'address_2'  => get_user_meta( $user_id, $meta_prefix . 'address_2', true ),
                                'city'       => get_user_meta( $user_id, $meta_prefix . 'city', true ),
                                'state'      => get_user_meta( $user_id, $meta_prefix . 'state', true ),
                                'postcode'   => get_user_meta( $user_id, $meta_prefix . 'postcode', true ),
                                'country'    => get_user_meta( $user_id, $meta_prefix . 'country', true ),
                            );

                            $address = array_filter( $address );
                            if ( ! empty( $address ) ) {
                                $formatted_address = $this->get_formatted_address( $address );
                                $content           = str_replace( '{' . $other_tag . '}', $formatted_address, $content );
                            } else {
                                $content = str_replace( '{' . $other_tag . '}', esc_html__( 'You have not set up this type of address yet.', 'customize-my-account-for-woocommerce' ), $content );
                            }
                        }
                        break;
                    case 'billing_company':
                    case 'shipping_company':
                        if ( is_user_logged_in() ) {
                            $meta_prefix  = ( 'billing_company' === $other_tag ) ? 'billing_' : 'shipping_';
                            $user_id      = get_current_user_id();
                            $company_name = get_user_meta( $user_id, $meta_prefix . 'company', true );

                            if ( empty( $company_name ) ) {
                                $company_name = '';
                            }

                            $content = str_replace( '{' . $other_tag . '}', $company_name, $content );
                        }
                        break;
                }
            }
        }
        return apply_filters('wcmamtx_modify_existing_content_variables',$content,$endpoint);
    }

}


/**
 * Check weather module is enabled or not.
 *
 * @since 2.12.0
 * @param string $key equals to module slug.
 * @return string
 */



if (!function_exists('wcmamtx_is_module_enabled')) {

    function wcmamtx_is_module_enabled($key) {

        $matchstick =  "yes";


        

        return $matchstick;
    }

}


if (!function_exists('wcmamtx_get_new_row_values')) {

    function wcmamtx_get_new_row_values($advancedsettings) {

    	$new_row_values    = array();

    	foreach ($advancedsettings as $key2=>$value2) {

        		$key2 = isset($value2['endpoint_key']) ? $value2['endpoint_key'] : $key2;


            
                $new_row_values[$key2]['endpoint_key']        = $key2;
                $new_row_values[$key2]['endpoint_name']       = $value2['endpoint_name'];
                $new_row_values[$key2]['wcmamtx_type']        = $value2['wcmamtx_type'];
                $new_row_values[$key2]['parent']              = $value2['parent'];
                
                $new_row_values[$key2]['class']               = isset($value2['class']) ? $value2['class'] : "";
                $new_row_values[$key2]['visibleto']           = isset($value2['visibleto']) ? $value2['visibleto'] : "all";
                $new_row_values[$key2]['roles']               = isset($value2['roles']) ? $value2['roles'] : array();

                if (isset($value2['wcmamtx_type']) && ($value2['wcmamtx_type'] == "heading")) {

                	$new_row_values[$key2]['icon_source']         = isset($value2['icon_source']) ? $value2['icon_source'] : "noicon";

                } else {

                	$new_row_values[$key2]['icon_source']         = isset($value2['icon_source']) ? $value2['icon_source'] : "default";

                }

                

                $new_row_values[$key2]['icon']                = isset($value2['icon']) ? $value2['icon'] : "";
                $new_row_values[$key2]['show']                = isset($value2['show']) ? $value2['show'] : "yes";
                $new_row_values[$key2]['upload_icon']         = isset($value2['upload_icon']) ? $value2['upload_icon'] : "";




                $default_color = wcmamtx_get_default_tab_color($key2);

                $default_color_font = '#334155';


                $default_desc_icon_colors = array(
                    'orders'          => esc_html__('#f97316','customize-my-account-for-woocommerce'),
                    'downloads'       => esc_html__('#22c55e','customize-my-account-for-woocommerce'),
                    'edit-address'    => esc_html__('#ef4444','customize-my-account-for-woocommerce'),
                    'edit-account'    => esc_html__('#8b5cf6','customize-my-account-for-woocommerce'),
                );


                $default_desc_color = isset($default_desc_icon_colors[$key2]) ? $default_desc_icon_colors[$key2] : "#3b82f6";


                $new_row_values[$key2]['dash_back_color']                = isset($value2['dash_back_color']) ? $value2['dash_back_color'] : $default_color;
                $new_row_values[$key2]['dash_font_color']                = isset($value2['dash_font_color']) ? $value2['dash_font_color'] : $default_color_font;

                $new_row_values[$key2]['icon_color_ds']                  = isset($value2['icon_color_ds']) ? $value2['icon_color_ds'] : $default_desc_color;

                $new_label = $value2['endpoint_name'];


                $default_desc_text_link = wcmamtx_deshlink_default_description($key2,$new_label);

                $default_desc_text      = isset($default_desc_text_link[$key2]) ? $default_desc_text_link[$key2] : "";

                $new_row_values[$key2]['default_desc_text']  = isset($value2['default_desc_text']) ? $value2['default_desc_text'] : $default_desc_text;


                $new_row_values[$key2]['count_of'] = isset($value2['count_of']) ? $value2['count_of'] : "none";
                
                $new_row_values[$key2]['count_bubble'] = isset($value2['count_bubble']) ? $value2['count_bubble'] : null;

                $new_row_values[$key2]['hide_empty'] = isset($value2['hide_empty']) ? $value2['hide_empty'] : null;

                $new_row_values[$key2]['hide_sidebar'] = isset($value2['hide_sidebar']) ? $value2['hide_sidebar'] : null;
                

                $new_row_values[$key2]['third_party']        = isset($value2['third_party']) ? $value2['third_party'] : null;
                

                if (isset($value2['wcmamtx_type']) && ($value2['wcmamtx_type'] == "link")) {
                	$new_row_values[$key2]['link_inputtarget']              = $value2['link_inputtarget'];
                	$new_row_values[$key2]['link_targetblank']              = $value2['link_targetblank'];
                }

                $value2['content'] = wp_kses(
                    $value2['content'],
                    wp_kses_allowed_html('post')
                );


                if (isset($value2['wcmamtx_type']) && ($value2['wcmamtx_type'] == "endpoint")) {
                    $new_row_values[$key2]['content']              = isset($value2['content']) ? $value2['content'] : "";
                }



                if (isset($value2['wcmamtx_type']) && ($value2['wcmamtx_type'] == "group")) {

                	$new_row_values[$key2]['group_open_default']   = isset($value2['group_open_default']) ? $value2['group_open_default'] : "no";

                }


                if ($key2 == "dashboard") {
                    $new_row_values[$key2]['hide_dashboard_hello']            = isset($value2['hide_dashboard_hello']) ? $value2['hide_dashboard_hello'] : 00;
                    $new_row_values[$key2]['hide_intro_hello']                = isset($value2['hide_intro_hello']) ? $value2['hide_intro_hello'] : 00;

                    $value2['content_dash'] = wp_kses(
                        $value2['content_dash'],
                        wp_kses_allowed_html('post')
                    );
                    $new_row_values[$key2]['content_dash']                    = isset($value2['content_dash']) ? $value2['content_dash'] : "";
                }
                
                
            

        }

        return $new_row_values;

    }

}


/**
 * Check weather module is enabled or not.
 *
 * @since 2.12.0
 * @param string $key equals to module slug.
 * @return string
 */



if (!function_exists('wcmamtx_get_default_endpoint_data')) {

	function wcmamtx_get_default_endpoint_data() {

		$advancedsettings  = (array) get_option('wcmamtx_advanced_settings');  





		return json_decode (json_encode ([$advancedsettings]), FALSE);

	}

}

/**
 * Check weather module is enabled or not.
 *
 * @since 2.12.0
 * @param string $key equals to module slug.
 * @return string
 */



if (!function_exists('wcmamtx_is_module_enabled_init')) {

    function wcmamtx_is_module_enabled_init($key) {

        $matchstick =  "yes";


        

        return $matchstick;
    }

}



/**
 * Get account menu item classes.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('load_wcmamtx_optional_class')) {

	function load_wcmamtx_optional_class($current_tab) {

		$default_class = '';


        if ($current_tab == "wcmamtx_advanced_settings") {

          $advancedsettings  = (array) get_option('wcmamtx_advanced_settings');  

          $tabs              = wc_get_account_menu_items();

          $core_fields       = 'dashboard,orders,downloads,edit-address,edit-account,customer-logout';





          $core_fields_array =  array(
             'dashboard'       => esc_html__('Dashboard','customize-my-account-for-woocommerce'),
             'orders'          => esc_html__('Orders','customize-my-account-for-woocommerce'),
             'downloads'       => esc_html__('Downloads','customize-my-account-for-woocommerce'),
             'edit-address'    => esc_html__('Addresses','customize-my-account-for-woocommerce'),
             'edit-account'    => esc_html__('Account Details','customize-my-account-for-woocommerce'),
             'customer-logout' => esc_html__('Log out','customize-my-account-for-woocommerce')
         );

          $tabs                = apply_filters( 'woocommerce_account_menu_items', $tabs, $core_fields_array );



          $frontend_menu_items = get_option('wcmamtx_frontend_items');




          if ((count($advancedsettings) != 1)) {

             foreach ($tabs as $ikey=>$ivalue) {

                $match = wcmtxka_find_string_match_pro($ikey,$advancedsettings);

                if (!array_key_exists($ikey, $advancedsettings) && !array_key_exists($ikey, $core_fields_array) && ($match == "notfound")) {



                   $advancedsettings[$ikey] = array(
                      'show' => 'yes',
                      'third_party' => 'yes',
                      'endpoint_key' => $ikey,
                      'wcmamtx_type' => 'endpoint',
                      'parent'       => 'none',
                      'endpoint_name'=> $ivalue,
                  );           

               }
           }






       }





        if (!isset($advancedsettings) || (count($advancedsettings) == 1)) {
         $default_class = "wcmamtx_one_time_save";

        } else {

         $default_class = "";
        }

    }

    if ($current_tab == "wcmamtx_layout") {

        $wcmamtx_layout = wcmamtx_get_layout();

        if (array_key_exists(0, $wcmamtx_layout)) {

            $default_class = "wcmamtx_one_time_save";

        }

    }

		return $default_class;

	}

}

/**
 * Get account menu item classes.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('wcmamtx_placeholder_img_src')) {

	function wcmamtx_placeholder_img_src() {
		return ''.wcmamtx_PLUGIN_URL.'assets/images/placeholder.png';
	}

}


/**
 * Get account menu item classes.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('wcmamtx_get_default_tab_color')) {

	function wcmamtx_get_default_tab_color($key) {
		$default_color = '#e9e9ef';

		
		return $default_color;
	}

}



/**
 * Get account menu item classes.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('wcmamtx_show_disabled_toggle_image')) {

	function wcmamtx_show_disabled_toggle_image() {
		echo '<a href="#" data-toggle="modal" data-target="#wcmamtx_upgrade_modal" class=""><img class="wcmamtx_disabled_image_popup" src="'.wcmamtx_PLUGIN_URL.'assets/images/disabled_pro_toggle.png"></a>';
	}

}



include('wcmamtx_thirdparty_functions.php');


/**
 * Get account menu item classes.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('wcmamtx_show_disabled_input')) {

	function wcmamtx_show_disabled_input() {
		echo '<a href="#" data-toggle="modal" data-target="#wcmamtx_upgrade_modal" class=""><img class="wcmamtx_disabled_image_popup" src="'.wcmamtx_PLUGIN_URL.'assets/images/disabled_pro_toggle.png"></a>';
	}

}





if (!function_exists('wcmamtx_load_pro_feature_preview')) {

	function wcmamtx_load_pro_feature_preview() { ?>

        <?php if  (wcmamtx_pro_price_show == "yes") { ?>


            <strong style="color:green;">
                <?php echo esc_html__( 'Lifetime license starts from ' ,'customize-my-account-for-woocommerce'); ?>
                <?php echo wcmamtx_pro_price_html; ?>
                <?php echo esc_html__( ' for single domain' ,'customize-my-account-for-woocommerce'); ?></strong>
                <br/><br/>

            <?php } ?>
      
		<strong><?php echo esc_html__( 'Pro Version Features' ,'customize-my-account-for-woocommerce'); ?></strong>
		<br/>
		
      
      	<table class="pro_preview_table">

      		<tr><td><i class="fa fa-check"></i></td><td><?php echo esc_html__( 'Unlimited Endpoints & links' ,'customize-my-account-for-woocommerce'); ?></td></tr>

      		<tr><td><i class="fa fa-check"></i></td><td><?php echo esc_html__( 'Unlimited Groups' ,'customize-my-account-for-woocommerce'); ?></td></tr>

      		<tr><td><i class="fa fa-check"></i></td><td><?php echo esc_html__( 'Custom Endpoint Key' ,'customize-my-account-for-woocommerce'); ?></td></tr>

      		<tr><td><i class="fa fa-check"></i></td><td><?php echo esc_html__( 'Change Default Dashboard Page' ,'customize-my-account-for-woocommerce'); ?></td></tr>

      		<tr><td><i class="fa fa-check"></i></td><td><?php echo esc_html__( 'Order Columns and Actions' ,'customize-my-account-for-woocommerce'); ?></td></tr>

      		<tr><td><i class="fa fa-check"></i></td><td><?php echo esc_html__( 'Download Columns' ,'customize-my-account-for-woocommerce'); ?></td></tr>
            
            <tr><td><i class="fa fa-check"></i></td><td><?php echo esc_html__( 'Custom My Account Elementor Templates' ,'customize-my-account-for-woocommerce'); ?></td></tr>

            <tr><td><i class="fa fa-check"></i></td><td><?php echo esc_html__( 'Ajax Navigation between Endpoints' ,'customize-my-account-for-woocommerce'); ?></td></tr>

            <tr><td><i class="fa fa-check"></i></td><td><?php echo esc_html__( 'And many more features' ,'customize-my-account-for-woocommerce'); ?></td></tr>

             <tr><td><i class="fa fa-check"></i></td><td><?php echo esc_html__( '6 Month of premium support' ,'customize-my-account-for-woocommerce'); ?></td></tr>

             <tr><td><i class="fa fa-check"></i></td><td><?php echo esc_html__( 'Lifetime dashboard updates' ,'customize-my-account-for-woocommerce'); ?></td></tr>

      	</table>
      
		
	<?php 
    }

}




/**
 * Get account menu item classes.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('wcmamtx_pro_added_endpoint')) {

	function wcmamtx_pro_added_endpoint($value) {
		if ((isset($value["content"]) && ($value["content"] != "")) && (!isset($value["third_party"]) || ($value["third_party"] != "yes"))) {
			$pro_added = "yes";
		} else {
			$pro_added = "yes";
		}

		return $pro_added;
	}

}




if (!function_exists('wcmamtx_get_menu_shortcode_content')) {


	function wcmamtx_get_menu_shortcode_content($items,$item) {

		$frontend_url = get_permalink(get_option('woocommerce_myaccount_page_id'));

		$wcmamtx_plugin_options = (array) get_option('wcmamtx_plugin_options');



		$nav_header_widget_text = isset($item->title) ? $item->title : esc_html__('My Account','customize-my-account-for-woocommerce');




		$widget_show_enabled    = isset($wcmamtx_plugin_options['nav_header_widget']) ? $wcmamtx_plugin_options['nav_header_widget'] : "no";




		if ( !is_user_logged_in() ) {

			$show_only_logged_in    = isset($wcmamtx_plugin_options['show_only_logged_in']) ? $wcmamtx_plugin_options['show_only_logged_in'] : "no";

			if ($show_only_logged_in == "yes") {
				return $items;
			}


			$nav_header_widget_text_logout = isset($wcmamtx_plugin_options['nav_header_widget_text_logout']) ? $wcmamtx_plugin_options['nav_header_widget_text_logout'] : esc_html__('Log In','customize-my-account-for-woocommerce');


			$Menu_link = '<li class="menu-item menu-item-type-post_type menu-item-object-page wcmamtx_menu wcmamtx_menu_logged_out"><a class="menu-link nav-top-link" aria-expanded="true" aria-haspopup="menu"  href="'.$frontend_url.'">'.$nav_header_widget_text_logout.'</a>';

			$items .= $Menu_link;

			echo  $items;

		} 





		$Menu_link  = '<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children"><a class="menu-link" href="'.$frontend_url.'">'.$nav_header_widget_text.'<i class="fa fa-chevron-down wcmamtx_nav_chevron"></i></a>';

		$Menu_link .= '<ul class="sub-menu nav-dropdown nav-dropdown-default" style="">';

		$Menu_link .= wcmamtx_get_my_account_menu_plain_li();

		$Menu_link .= '</ul></li>';



		$items .= $Menu_link;

		echo  $items;
	}

}


/**
 * Get account menu item classes.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('wcmamtx_get_account_menu_item_classes')) {

	function wcmamtx_get_account_menu_item_classes( $endpoint,$value ) {

		global $wp;

		$core_fields       = 'dashboard,orders,downloads,edit-address,edit-account,customer-logout';

		$icon_source       = "default";

		switch($icon_source) {

			case "default":
			   $extra_li_class = '';
			break;

			case "noicon":
			   $extra_li_class = 'wcmamtx_no_icon';
			break;

			case "custom":
			   $extra_li_class = 'wcmamtx_custom_icon';
			break;

			case "dashicon":
			   $extra_li_class = 'wcmamtx_custom_icon';
			break;

			default:
			   $extra_li_class = 'wcmamtx_custom_icon';
			break;

		}
        
        

        $classes = array(
        	'woocommerce-MyAccount-navigation-link',
        	'woocommerce-MyAccount-navigation-link--' . $endpoint,
        	''.$extra_li_class.''
        );
        
        
		

	    // Set current item class.
		$current = isset( $wp->query_vars[ $endpoint ] );
		if ( 'dashboard' === $endpoint && ( isset( $wp->query_vars['page'] ) || empty( $wp->query_vars ) ) ) {
		    $current = true; // Dashboard is not an endpoint, so needs a custom check.
	    } elseif ( 'orders' === $endpoint && isset( $wp->query_vars['view-order'] ) ) {
		    $current = true; // When looking at individual order, highlight Orders list item (to signify where in the menu the user currently is).
	    } elseif ( 'payment-methods' === $endpoint && isset( $wp->query_vars['add-payment-method'] ) ) {
		    $current = true;
	    }
 
	    if ( $current ) {
		    $classes[] = 'is-active';
	    }

	    $classes = apply_filters( 'woocommerce_account_menu_item_classes', $classes, $endpoint );

	    return implode( ' ', array_map( 'sanitize_html_class', $classes ) );
    }
}


// **********************************************************************//
// ! My account menu
// **********************************************************************//

if ( ! function_exists( 'wcmamtx_get_my_account_menu_plain_li' ) ) {
    function wcmamtx_get_my_account_menu_plain_li() {
        $user_info  = get_userdata( get_current_user_id() );
        $user_roles = $user_info->roles;

        $out = '';

        $wcmamtx_tabs   =  (array) get_option('wcmamtx_advanced_settings');

        $items = wc_get_account_menu_items();

        $core_fields    = 'dashboard,orders,downloads,edit-address,edit-account,customer-logout';

        $core_fields_array =  array(
            'dashboard'=>'dashboard',
            'orders'=>'orders',
            'downloads'=>'downloads',
            'edit-address'=>'edit-address',
            'edit-account'=>'edit-account',
            'customer-logout'=>'customer-logout',
            'payment-methods'=>'payment-methods'
        );


        foreach($items as $gtkey=>$gtvalue) {

            if (!array_key_exists($gtkey, $core_fields_array)) {
                  $third_party_check = wcmamtx_third_party_goahead_check($gtkey);

                  if ($third_party_check == "no") {
                     unset($items[$gtkey]);
                  }
            }

        }



        if (count($wcmamtx_tabs) === 1 && empty(reset($wcmamtx_tabs))) {
            $wcmamtx_tabs = $items;
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
                        'third_party' => 'yes',
                        'endpoint_key' => $ikey,
                        'wcmamtx_type' => 'endpoint',
                        'parent'       => 'none',
                        'endpoint_name'=> $ivalue,
                    );   
                }           

            }
        }





        $plugin_options = get_option('wcmamtx_plugin_options');

        $icon_position  = 'right';
        $icon_extra_class = '';

        if (!is_array($wcmamtx_tabs)) { 
            $wcmamtx_tabs = $items;
        }

        if (!isset($wcmamtx_tabs) || (count($wcmamtx_tabs) == 1)) {
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
            $icon_extra_class = " wcmamtx_custom_right";
            break;

            case "left":
            $icon_extra_class = " wcmamtx_custom_left";
            break;

            default:
            $icon_extra_class = " wcmamtx_custom_right";
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

                    $act_goahead = "yes";

                  $act_goahead = wcmamtx_act_goahead_check_verified();


                    if ($act_goahead == "yes") {
                      if (($third_party_check == "no") && ($wcmamtx_type == "endpoint") && (strpos($gtkey, 'custom-endpoint-') === false))   {
                         unset($wcmamtx_tabs[$gtkey]);
                        }
                    } else if ($act_goahead == "no") {
                        if (($third_party_check == "no") && ($wcmamtx_type == "endpoint")) {
                            unset($wcmamtx_tabs[$gtkey]);
                        }
                   }
            }

        }


        


        foreach ( $wcmamtx_tabs as $key => $value ) {
            
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

            $key = isset($value['endpoint_key']) ? $value['endpoint_key'] : $key;



            if (isset($value['parent']) && ($value['parent'] != '')) {
                $parent = $value['parent'];
            } else {
                $parent = 'none';
            }


           $wcmamtx_type = isset($value['wcmamtx_type']) ? $value['wcmamtx_type'] : "endpoint";


            if (($should_show == "yes") && ($is_visible == "yes")) {

                if (isset($wcmamtx_type) && ($wcmamtx_type != "group")) {


                    $out .= '<li class="menu-item menu-item-type-post_type menu-item-object-page"><a class="menu-link" href="'.wcmamtx_get_account_endpoint_url( $key ) .'"><span>' . esc_html( $name ) . '</span></a></li>';



                }

            }
        }

        $out .='';

        return $out;
    }
}




include("wcmamtx_load_icon_functions.php");


/**
 * Get account li html.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('wcmamtx_get_account_menu_li_html')) {

	function wcmamtx_get_account_menu_li_html( $name , $key , $value ,$icon_extra_class,$extraclass,$icon_source) { 

		$wsmt_li_fontsize = get_theme_mod('wsmt_li_fontsize');
         
        $font_size = isset($wsmt_li_fontsize) ? $wsmt_li_fontsize : "16px";

        $wsmt_li_padding = get_theme_mod('wsmt_li_padding');
         
        $padding_left = isset($wsmt_li_padding) ? $wsmt_li_padding : "0px";


        if (isset($value['wcmamtx_type']) && ($value['wcmamtx_type'] == "separater")) {

        	echo '<br/>';

        } else {


		
        $wcmamtx_type = isset($value['wcmamtx_type']) && is_array($value) ? $value['wcmamtx_type'] : "endpoint";

        ?>




		<li  class="<?php echo wcmamtx_get_account_menu_item_classes( $key , $value ); ?> <?php echo  esc_attr($wcmamtx_type); ?> <?php echo esc_attr($extraclass); ?> <?php if ($icon_source == "custom") { echo $icon_extra_class; } ?>">
			<a class="woocommerce-MyAccount-navigation-link_a"  href="<?php echo wcmamtx_get_account_endpoint_url( $key ); ?>" <?php if (isset($wcmamtx_type) && ($wcmamtx_type == "link") && (isset($value['link_targetblank'])) && ($value['link_targetblank'] == 01) ) { echo 'target="_blank"'; } ?>>
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
		</li>

	<?php }
    }
}






/**
 * License activation reminder.
 *
 * @since 1.0.0
 * @param string .
 * @return string
 */

if (!function_exists('wcmamtx_load_pro_reminder_div')) {

	function wcmamtx_load_pro_reminder_div() { 
		?>

		<div class="wcmamtx_notice_div">

			<div class="wcmamtx_notice_div_uppertext">
				<?php echo esc_html__( 'This feature is available in pro version only.','customize-my-account-for-woocommerce'); ?>

			</div>

			<?php wcmamtx_load_pro_feature_preview(); ?>

			<div class="wcmamtx_notice_div_lowerbutton">
				

				

				<a type="button" target="_blank" href="https://sysbasics.com/go/customize/"  class="btn btn-success wcmamtx_frontend_link" >
					<span class="dashicons dashicons-lock"></span>
					<?php echo esc_html__( 'Upgrade to Pro' ,'customize-my-account-for-woocommerce'); ?>
				</a>

				<br><br>

               
			</div>
		</div>

		<?php 
	}
}


/**
 * License activation reminder.
 *
 * @since 1.0.0
 * @param string .
 * @return string
 */

if (!function_exists('wcmamtx_show_limit_info')) {

	function wcmamtx_show_limit_info() { 
		?>

		<div class="wcmamtx_notice_div">

			<div class="wcmamtx_notice_div_uppertext">
				<?php echo esc_html__( 'Free version of plugin only supports 2 endpoint and 2 groups.Pro Version supports unlimited number of endpoints and groups.','customize-my-account-for-woocommerce'); ?>

			</div>

			<div class="wcmamtx_notice_div_lowerbutton">
				

				<a type="button" target="_blank" href="https://sysbasics.com/go/customize/"  class="btn btn-success wcmamtx_frontend_link" >
					<span class="dashicons dashicons-lock"></span>
					<?php echo esc_html__( 'Upgrade to pro' ,'customize-my-account-for-woocommerce'); ?>
				</a>

				<br><br>

                
			</div>
		</div>

		<?php 
	}
}


/**
 * Get account li html.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('wcmamtx_get_account_endpoint_url')) {

	function wcmamtx_get_account_endpoint_url($key) {



		$core_url =  esc_url( wc_get_account_endpoint_url( $key ) );


		if (!isset($core_url) || ($core_url == "")) {

			

			if ( 'dashboard' === $key ) {
				return wc_get_page_permalink( 'myaccount' );
			}

			if ( 'customer-logout' === $key ) {
				return wc_logout_url();
			}

			return ''.wc_get_page_permalink( 'myaccount' ).''.$key.'/';
		}


		return apply_filters('wcmamtx_override_endpoint_url',$core_url,$key);

	}
}


/**
 * Get account li html.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('wcmamtx_get_account_order_items')) {

	function wcmamtx_get_account_order_items() {

		$columns = array(
			'order-number'  => esc_html__( 'Order', 'customize-my-account-for-woocommerce' ),
			'order-date'    => esc_html__( 'Date', 'customize-my-account-for-woocommerce' ),
			'order-status'  => esc_html__( 'Status', 'customize-my-account-for-woocommerce' ),
			'order-total'   => esc_html__( 'Total', 'customize-my-account-for-woocommerce' ),
			'order-actions' => '&nbsp;',
		);

		return apply_filters('woocommerce_account_orders_columns',$columns);

	}
}


/**
 * Get account li html.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('wcmamtx_get_meta_values')) {

	function wcmamtx_get_meta_values( $post_type = 'order', $exclude_empty = false, $exclude_hidden = false)
	{

		$meta_keys = array();
        global $wpdb;
    $query = "
        SELECT DISTINCT($wpdb->postmeta.meta_key) 
        FROM $wpdb->posts 
        LEFT JOIN $wpdb->postmeta 
        ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
        WHERE $wpdb->posts.post_type = '%s'
    ";
    if($exclude_empty) 
        $query .= " AND $wpdb->postmeta.meta_key != ''";
    if($exclude_hidden) 
        $query .= " AND $wpdb->postmeta.meta_key NOT RegExp '(^[_0-9].+$)' ";

    $meta_keys = $wpdb->get_col($wpdb->prepare($query, $post_type));

    return $meta_keys;


	}
}


/**
 * Get account group html.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('wcmamtx_get_account_menu_group_html')) {

	function wcmamtx_get_account_menu_group_html( $name , $key , $value ,$icon_extra_class,$extraclass,$icon_source) { 

		


        if (isset($value['group_open_default']) && ($value['group_open_default'] == "01" )) { 
        	$openclose = 'open'; 

        } else {

        	
            
            $match_index = 0;
            

            $browser_url = $_SERVER['REQUEST_URI'];

            $parts = explode("/", $browser_url);

            $parsed  = isset($parts[1]) ? $parts[1] : "";           
            $parsed2 = isset($parts[2]) ? $parts[2] : "";
            $parsed3 = isset($parts[3]) ? $parts[3] : "";
            $parsed4 = isset($parts[3]) ? $parts[3] : "";
            


			$all_keys  = get_option('wcmamtx_advanced_settings'); 
			$plugin_options = get_option('wcmamtx_plugin_options'); 

			$matches   = wcmamtx_get_child_li($all_keys, $key); 
        	
			if (count($matches) > 0) { 
				foreach ($matches as $mkey=>$mvalue) {

					$mkey  = isset($mvalue['endpoint_key']) ? $mvalue['endpoint_key'] : $mkey;

					if (($parsed == $mkey) || ($parsed2 == $mkey) || ($parsed3 == $mkey) || ($parsed4 == $mkey)) {

                        $match_index++;
                        
					} 
				}
			}

			if ($match_index > 0) {
				$openclose = 'open';
			} else {
				$openclose = 'closed';
			}


        }

		?>

		<li class="wcmamtx_group2 <?php echo wcmamtx_get_account_menu_item_classes( $key , $value ); ?> <?php echo esc_attr($extraclass); ?> <?php if ($icon_source == "custom") { echo esc_attr($icon_extra_class); } ?> <?php echo esc_attr($openclose); ?> ">
			<a href="#" class="wcmamtx_group">
				<?php 
				if ($openclose == 'open') { ?>
					<i class="fa fa-chevron-up wcmamtx_group_fa" ></i>
				<?php } else { ?>
                    <i class="fa fa-chevron-down wcmamtx_group_fa"></i>
				<?php }
				?>
				<?php 
				if ($icon_source == "custom") {
					$icon       = isset($value['icon']) ? $value['icon'] : "";

					if ($icon != '') { ?>
						<i class="<?php echo esc_attr($icon); ?>"></i>
					<?php }
				} else if ($icon_source == "dashicon") {
					$icon       = isset($value['dashicon']) ? $value['dashicon'] : "";

					if ($icon != '') { ?>
						<span class="dashicons <?php echo esc_attr($icon); ?>"></span>
					<?php }

				}
				?>
				<span class="wcmamtx_sticky_icon_name"><?php echo esc_html( $name ); ?></span>
			</a>
			<?php


			$m_icon_position  = 'right';
            $m_icon_extra_class = '';

            if (isset($plugin_options['icon_position']) && ($plugin_options['icon_position'] != '')) {
            	$m_icon_position = $plugin_options['icon_position'];
            }



            switch($m_icon_position) {
            	case "right":
            	$m_icon_extra_class = "wcmamtx_custom_right";
            	break;

            	case "left":
            	$m_icon_extra_class = "wcmamtx_custom_left";
            	break;

            	default:
            	$m_icon_extra_class = "wcmamtx_custom_right";
            	break;
            }

            $all_keys  = get_option('wcmamtx_advanced_settings'); 
			$plugin_options = get_option('wcmamtx_plugin_options'); 

			$matches   = wcmamtx_get_child_li($all_keys, $key); 
            
            
			

			if (count($matches) > 0) { ?>
				<ul class="wcmamtx_sub_level" style="<?php if ($openclose == "open") { echo 'display:block;'; } else { echo 'display:none;'; } ?>">
					<?php
					foreach ($matches as $mkey=>$mvalue) {

						$mkey  = isset($mvalue['endpoint_key']) ? $mvalue['endpoint_key'] : $mkey;
						
						if (isset($mvalue['endpoint_name']) && ($mvalue['endpoint_name'] != '')) {
							$liname = $mvalue['endpoint_name'];
						} else {
							$liname = $mvalue;
						}

						$should_show = 'yes';



						if (isset($mvalue['show']) && ($mvalue['show'] == "no")) {

							$should_show = 'no';

						}

						if (isset($mvalue['visibleto']) && ($mvalue['visibleto'] != "all")) {

							$allowedroles  = isset($mvalue['roles']) ? $mvalue['roles'] : "";
							$allowedusers  = isset($mvalue['users']) ? $mvalue['users'] : array();

							$is_visible = wcmamtx_check_role_visibility($allowedroles,$mvalue['visibleto'],$allowedusers);

						} else {

							$is_visible = 'yes';
						}

						$icon_source_child       = isset($mvalue['icon_source']) ? $mvalue['icon_source'] : "default";

						if (isset($mvalue['class']) && ($mvalue['class'] != '')) {
							$mextraclass = str_replace(',',' ', $mvalue['class']);
						} else {
							$mextraclass = '';
						}


						if (($should_show == "yes") && ($is_visible == "yes")) {

							wcmamtx_get_account_menu_li_html( $liname, $mkey ,$mvalue ,$m_icon_extra_class,$mextraclass,$icon_source_child );
					    }
					}
					?>
				</ul>
			<?php } ?>
			
		</li>

	<?php }
}




// **********************************************************************//
// ! My account menu
// **********************************************************************//

if ( ! function_exists( 'wcmamtx_get_my_account_menu' ) ) {
	function wcmamtx_get_my_account_menu() {
		$user_info  = get_userdata( get_current_user_id() );
		$user_roles = $user_info->roles;

		$out = '<ul class="">';

		$wcmamtx_tabs   =  (array) get_option('wcmamtx_advanced_settings');

		$items = wc_get_account_menu_items();

		$core_fields    = 'dashboard,orders,downloads,edit-address,edit-account,customer-logout';

		$core_fields_array =  array(
			'dashboard'=>'dashboard',
			'orders'=>'orders',
			'downloads'=>'downloads',
			'edit-address'=>'edit-address',
			'edit-account'=>'edit-account',
			'customer-logout'=>'customer-logout'
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
						'third_party' => 'yes',
						'endpoint_key' => $ikey,
						'wcmamtx_type' => 'endpoint',
						'parent'       => 'none',
						'endpoint_name'=> $ivalue,
					);   
				}           

			}
		}





		$plugin_options = get_option('wcmamtx_plugin_options');

		$icon_position  = 'right';
		$icon_extra_class = '';

		if (!is_array($wcmamtx_tabs)) { 
			$wcmamtx_tabs = $items;
		}

		if (!isset($wcmamtx_tabs) || (count($wcmamtx_tabs) == 1)) {
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





		foreach ( $wcmamtx_tabs as $key => $value ) {
			
			if (isset($value['endpoint_name']) && ($value['endpoint_name'] != '')) {
				$name = $value['endpoint_name'];
			} else {
				$name = $value;
			}

			$should_show = 'yes';


			if (isset($value['visibleto']) && ($value['visibleto'] != "all")) {

				$allowedroles  = isset($value['roles']) ? $value['roles'] : "";
				$allowedusers  = isset($value['users']) ? $value['users'] : array();



				$is_visible = wcmamtx_check_role_visibility($allowedroles,$value['visibleto'],$allowedusers);

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



			$icon_source       = "default";

			$hide_myaccount_widget = isset($value['hide_myaccount_widget']) && ($value['hide_myaccount_widget'] == "01") ? "enabled" : "disabled";

            if (isset($hide_myaccount_widget) && ($hide_myaccount_widget == "enabled")) {
                
                 $should_show = 'no';
                
            }

			if (($should_show == "yes") && ($is_visible == "yes")) {

				if (isset($value['wcmamtx_type']) && ($value['wcmamtx_type'] == "group")) {


					$openclose = 'closed';

					$out .='<li class="wcmamtx_group2_sub '.wcmamtx_get_account_menu_item_classes( $key , $value ).' '.$extraclass.' closed"><a href="#" class="wcmamtx_group_sub">'.esc_html( $name ).'&emsp;<i class="fa fa-chevron-down wcmamtx_group_fa"></i></a>';




					$m_icon_position  = 'right';
					$m_icon_extra_class = '';

					if (isset($plugin_options['icon_position']) && ($plugin_options['icon_position'] != '')) {
						$m_icon_position = $plugin_options['icon_position'];
					}



					switch($m_icon_position) {
						case "right":
						$m_icon_extra_class = "wcmamtx_custom_right";
						break;

						case "left":
						$m_icon_extra_class = "wcmamtx_custom_left";
						break;

						default:
						$m_icon_extra_class = "wcmamtx_custom_right";
						break;
					}

					$all_keys  = get_option('wcmamtx_advanced_settings'); 
					$plugin_options = get_option('wcmamtx_plugin_options'); 

					$matches   = wcmamtx_get_child_li($all_keys, $key); 




					if (count($matches) > 0) { 
						$out .='<ul class="wcmamtx_sub_level" style="display:none;">';

						foreach ($matches as $mkey=>$mvalue) {

							$mkey  = isset($mvalue['endpoint_key']) ? $mvalue['endpoint_key'] : $mkey;

							if (isset($mvalue['endpoint_name']) && ($mvalue['endpoint_name'] != '')) {
								$liname = $mvalue['endpoint_name'];
							} else {
								$liname = $mvalue;
							}

							$should_show = 'yes';



							if (isset($mvalue['show']) && ($mvalue['show'] == "no")) {

								$should_show = 'no';

							}

							if (isset($mvalue['visibleto']) && ($mvalue['visibleto'] != "all")) {

								$allowedroles  = isset($mvalue['roles']) ? $mvalue['roles'] : "";
								$allowedusers  = isset($mvalue['users']) ? $mvalue['users'] : array();

								$is_visible = wcmamtx_check_role_visibility($allowedroles,$mvalue['visibleto'],$allowedusers);

							} else {

								$is_visible = 'yes';
							}

							$icon_source_child       = "default";

							if (isset($mvalue['class']) && ($mvalue['class'] != '')) {
								$mextraclass = str_replace(',',' ', $mvalue['class']);
							} else {
								$mextraclass = '';
							}


							if (($should_show == "yes") && ($is_visible == "yes")) {

								$out .= '<li class="' . wc_get_account_menu_item_classes( $mkey ) . '"><a href="' . wcmamtx_get_account_endpoint_url( $mkey ) . '"><span>' . esc_html( $liname ) . '</span></a></li>';
							}
						}
						$out .='</ul>';
					} 

					$out .='</li>';




				} else {

					if ($parent == "none") {
						$out .= '<li class="' . wc_get_account_menu_item_classes( $key ) . '"><a href="' . wcmamtx_get_account_endpoint_url( $key ) . '"><span>' . esc_html( $name ) . '</span></a></li>';
					}

				} 
			}
		}

		return $out . '</ul>';
	}
}


/**
 * Get parent li items.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('wcmamtx_get_child_li')) {


	function wcmamtx_get_child_li($array, $key) {

		$results = array();



		foreach ($array as $subkey=>$subvalue) {

			if (isset($subvalue['parent'])) {

				if ($subvalue['parent'] == $key) {
					$results[$subkey] = $subvalue;
				}
			}

		}

		return $results;
	}

}

/**
 * Get parent li items.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('wcmamtx_check_role_visibility')) {

    function wcmamtx_check_role_visibility($allowedroles,$visibile_to,$allowedusers) {

    	$role_status       = 'no';



        switch($visibile_to) {

        	case "specific_exclude":

        	if (isset($allowedroles) && is_array($allowedroles) && (!empty($allowedroles))) {
        		if ( ! is_user_logged_in() ) {
        			$role_status       = 'no';
        			return $role_status; 
        		}

        		$allowedauthors = '';

        		foreach ($allowedroles as $role) {
        			$allowedauthors.=''.$role.',';
        		}

        		$allowedauthors=substr_replace($allowedauthors, "", -1);

        		global $current_user;
        		$user_roles = $current_user->roles;
        		$user_role = array_shift($user_roles);



        		if (!preg_match('/\b'.$user_role.'\b/', $allowedauthors )) {
        			$role_status       = 'yes';
        			return $role_status;
        		}

        	}

        	if (empty($allowedroles) && ( ! is_user_logged_in() )) {
        		$role_status       = 'yes';
        		return $role_status;
        	}

        	break;

        	case "specific":

        	if (isset($allowedroles) && is_array($allowedroles) && (!empty($allowedroles))) {
        		if ( ! is_user_logged_in() ) {
        			$role_status       = 'no';
        			return $role_status; 
        		}

        		$allowedauthors = '';

        		foreach ($allowedroles as $role) {
        			$allowedauthors.=''.$role.',';
        		}

        		$allowedauthors=substr_replace($allowedauthors, "", -1);

        		global $current_user;
        		$user_roles = $current_user->roles;
        		$user_role = array_shift($user_roles);



        		if (preg_match('/\b'.$user_role.'\b/', $allowedauthors )) {
        			$role_status       = 'yes';
        			return $role_status;
        		}

        	}

        	if (empty($allowedroles) && ( ! is_user_logged_in() )) {
        		$role_status       = 'yes';
        		return $role_status;
        	}

        	break;

        	case "specific_exclude_user":



        	if (isset($allowedusers) && is_array($allowedusers) && (!empty($allowedusers))) {

        		if ( ! is_user_logged_in() ) {
        			$user_status       = 'no';
        			return $user_status; 
        		}
                
                $user_match_index = 0;

                $user_id = get_current_user_id();

                foreach ($allowedusers as $alloweduser) {

                	if ($user_id == $alloweduser) {
                		$user_match_index++;
                	}

                }

                if ($user_match_index > 0 ) {
                	$user_status       = 'no';
        			return $user_status; 
                } else {
                	$user_status       = 'yes';
        			return $user_status; 
                }


        	}

        	break;

        	case "specific_user":

        	if (isset($allowedusers) && is_array($allowedusers) && (!empty($allowedusers))) {

        		if ( ! is_user_logged_in() ) {
        			$user_status       = 'no';
        			return $user_status; 
        		}
                
                $user_match_index = 0;

                $user_id = get_current_user_id();

                foreach ($allowedusers as $alloweduser) {

                	if ($user_id == $alloweduser) {
                		$user_match_index++;
                	}

                }

                if ($user_match_index > 0 ) {
                	$user_status       = 'yes';
        			return $user_status; 
                } else {
                	$user_status       = 'no';
        			return $user_status; 
                }


        	}

        	break;

        }


        return $role_status; 
    }
}

/**
 * Show user avatar before natigation items.
 *
 * @since 1.0.0
 * @param string $endpoint Endpoint.
 * @return string
 */

if (!function_exists('wcmamtx_myaccount_customer_avatar')) {

    function wcmamtx_myaccount_customer_avatar() {
	    $current_user = wp_get_current_user();

	    $plugin_options = get_option('wcmamtx_plugin_options');

	    $show_avatar    = isset($plugin_options['show_avatar']) ? $plugin_options['show_avatar'] : "no";
	    $avatar_size    = isset($plugin_options['avatar_size']) ? $plugin_options['avatar_size'] : 200;

	    if (isset($show_avatar) && ($show_avatar == "yes")) {
	    	echo '<div class="wcmamtx_myaccount_avatar">' . get_avatar( $current_user->user_email, $avatar_size , '', $current_user->display_name ) . '</div>';
	    }
    }
}
 
add_action( 'wcmamtx_before_account_navigation', 'wcmamtx_myaccount_customer_avatar', 5 );


if (!function_exists('wcmtxka_find_string_match_pro')) {

	function wcmtxka_find_string_match_pro($string,$array) {

		foreach ($array as $key=>$value) {

			$endpoint_key = isset($value['endpoint_key']) ? $value['endpoint_key'] : $key;
			
            if ($endpoint_key == $string) { // Yoshi version

        	    return 'found';
            }
        }

        return 'notfound';
    }

}

?>