<?php 

add_action( 'wp_footer', function() {
    if ( ! is_account_page() ) {
        return;
    }

    $endpoint = 'dashboard';

    foreach ( WC()->query->get_query_vars() as $key => $value ) {
        if ( is_wc_endpoint_url( $key ) ) {
            $endpoint = sanitize_html_class( $key );
            break;
        }
    }
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const content = document.querySelector('.woocommerce-MyAccount-content');
        if (content) {
            content.classList.add('endpoint-<?php echo esc_js( $endpoint ); ?>');
        }
    });
    </script>
    <?php
});





add_action( 'woocommerce_new_order',            'wcmamtx_bust_spending_cache', 10, 1 );
add_action( 'woocommerce_order_status_changed', 'wcmamtx_bust_spending_cache', 10, 1 );


if (!class_exists('wcmamtx_add_frontend_class')) {

  class wcmamtx_add_frontend_class {

    private $endpoint_key;
    private $endpoint_content;

    private $column_val;
    private $column_key;
    private $column_key_custom;
    
    public function __construct() {


     add_action( 'wp_enqueue_scripts', array( $this, 'wcmamtx_load_assets' ) );
     add_action( 'woocommerce_account_menu_items', array($this, 'wcmamtx_rename_my_account_menu_items'), 100, 1);
     add_action( 'woocommerce_locate_template', array($this,'wcmamtx_override_default_navigation_template'), 100, 3 );
     




     add_filter( 'wpml_sl_blacklist_requests',  array($this,'wpml_sl_blacklist_requests'), 10, 2 );
     add_action( 'init', array($this,'wcmamtx_add_custom_endpoint_page') );



     add_filter( 'woocommerce_get_endpoint_url', array( $this, 'wcmamtx_link_url_redirect' ), 10, 4 );
    



     add_action('the_content', array( $this, 'wcmamtx_modify_post_content' ));




     add_shortcode('sysbasics_dashboard_menu', array( $this, 'sysbasics_dashboard_menu_function' ));

     add_action( 'admin_bar_menu', array( $this, 'register_custom_menu_link' ),999);

     add_action('init',array( $this, 'wcmamtx_google_callback' ));

     add_action( 'wp_nav_menu_items', array( $this, 'wcmamtx_add_menu_items' ), 10, 2 );

     add_filter('wp_nav_menu_items', array( $this, 'wcmamtx_optimize_avatar_into_logged_in_li' ), 20, 2);

     add_action( 'wp_enqueue_scripts', array( $this, 'wcmamtx_sb_enqueue_chart_assets' ) );



      add_action( 'woocommerce_account_dashboard', array($this,'wcmamtx_add_myaccount_links'), 10 );


    }



    public function wcmamtx_sb_enqueue_chart_assets() {

        if ( ! is_account_page() ) {
            return;
        }

        if ( ! is_user_logged_in() ) {
            return;
        }

        $wcmamtx_layout = (array) get_option('wcmamtx_layout');


        $spending_layout_override  = isset($wcmamtx_layout['spending_layout_override'])  ? $wcmamtx_layout['spending_layout_override']  : '02';
        $spendingchart_override    = isset($wcmamtx_layout['spendingchart_override'])    ? $wcmamtx_layout['spendingchart_override']    : '01';

        if ($spending_layout_override === '01' || $spendingchart_override === '01') {

            wp_enqueue_script(
                'wcmamtx_chart_js',
                wcmamtx_PLUGIN_URL . 'assets/js/chart.js',
                array(),
                '4.4.0',
                true
            );

            wp_enqueue_script(
                'wcmamtxchart',
                wcmamtx_PLUGIN_URL . 'assets/js/account-chart.js',
                array( 'wcmamtx_chart_js' ),
                '1.0',
                true
            );


            $wcmamtx_locals_chart = array(
                'amountspent_label'              => esc_html__('Amount Spent','customize-my-account-for-woocommerce'),
            );


            wp_localize_script( 'wcmamtxchart', 'wcmamtxchart', $wcmamtx_locals_chart );

            wp_enqueue_style(
                'wcmamtxchart-style',
                wcmamtx_PLUGIN_URL . 'assets/css/account-chart.css'
            );

        }


    }

    


    public function wcmamtx_add_menu_items( $items, $args ) {

        $frontend_url = get_permalink(get_option('woocommerce_myaccount_page_id'));

        $flush_cache = get_option('wcmamtx_flush_rewrite_cache_required',"no");

        if ($flush_cache == "yes") {
            add_action( 'wp_loaded', array($this,'wcmamtx_flush_rewrite_rules') );

            update_option('wcmamtx_flush_rewrite_cache_required',"no");
        }

        $wcmamtx_layout = (array) get_option('wcmamtx_layout');

        $nav_header_widget_text = isset($wcmamtx_layout['nav_header_widget_text']) ? $wcmamtx_layout['nav_header_widget_text'] : esc_html__('My Account','customize-my-account-for-woocommerce');


        

        $widget_show_enabled    = isset($wcmamtx_layout['nav_header_widget']) ? $wcmamtx_layout['nav_header_widget'] : wcmamtx_get_nav_widget_array_theme();

        if ($widget_show_enabled != "yes") {
            return $items;
        }

        $navigationwidget_layout_override = isset($wcmamtx_layout['navigationwidget_layout_override']) ? $wcmamtx_layout['navigationwidget_layout_override'] : wcmamtx_get_nav_widget_array_theme2();


        if (isset($navigationwidget_layout_override) && ($navigationwidget_layout_override == 02)) { 
            return $items;
        }

        $widget_show_location    = isset($wcmamtx_layout['widget_menu_location']) ? $wcmamtx_layout['widget_menu_location'] : wcmamtx_get_nav_widget_default_location_menu();

        if( $args->theme_location != $widget_show_location ) {
            return $items;
        }

       
        if ( !is_user_logged_in() ) {

            $show_only_logged_in    = isset($wcmamtx_layout['show_only_logged_in']) ? $wcmamtx_layout['show_only_logged_in'] :  wcmamtx_get_nav_widget_array_show_only_loggedin();

            if ($show_only_logged_in == "yes") {
                return $items;
            }


            $nav_header_widget_text_logout = isset($wcmamtx_layout['nav_header_widget_text_logout']) ? $wcmamtx_layout['nav_header_widget_text_logout'] : wcmamtx_get_nav_logout_default_text();


            $Menu_link  = '<li class="menu-item wcmamtx_menu wcmamtx_menu_logged_out2">';
            $Menu_link .= '<a href="' . esc_url( $frontend_url ) . '" class="wcmamtx-login-btn">';
            $Menu_link .= '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';
            $Menu_link .= '<span class="wcmamtxx_nav_header_widget_text_logout">' . esc_html( $nav_header_widget_text_logout ) . '</span>';
            $Menu_link .= '</a></li>';

            $items .= $Menu_link;

            return $items;
        
        }



        

        $Menu_link  = '<li style="" class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item current_page_item current-menu-ancestor current-menu-parent current_page_parent current_page_ancestor menu-item-has-children fusion-dropdown-menu menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children wcmamtx_menu wcmamtx_menu_logged_in" style=""><a class="menu-link" href="'.$frontend_url.'">'.$nav_header_widget_text.'<i class="fa fa-chevron-down wcmamtx_nav_chevron"></i></a>';

        $Menu_link .= '<ul class="sub-menu" style="">';

        $Menu_link .= wcmamtx_get_my_account_menu_plain_li();

        $Menu_link .= '</ul></li>';

        

        $items .= $Menu_link;

        return $items;
    }








  public function wcmamtx_optimize_avatar_into_logged_in_li($items, $args) {

    if (!is_user_logged_in()) {
        return $items;
    }

    // Only act if the logged-in menu item was actually added by the plugin
    if (strpos($items, 'wcmamtx_menu_logged_in') === false) {
        return $items;
    }

    $current_user      = wp_get_current_user();
    $display_name      = esc_html($current_user->display_name);
    $frontend_url      = esc_url(get_permalink(get_option('woocommerce_myaccount_page_id')));

    $avatar_size       = "38";

    $user_id     = get_current_user_id();
    $profileuser = get_userdata( $user_id );

    $nav_widget = true;

    $atts = array();

    $avatar_html       = wcmamtx_get_avatar_default($profileuser,$avatar_size,$atts,$nav_widget);

    $wcmamtx_layout        = (array) get_option('wcmamtx_layout');
    $nav_header_widget_text = isset($wcmamtx_layout['nav_header_widget_text'])
    ? esc_html($wcmamtx_layout['nav_header_widget_text'])
    : esc_html__('My Account', 'customize-my-account-for-woocommerce');

    $navwidget_disable_avatar = isset($wcmamtx_layout['navwidget_disable_avatar'])
    ? esc_html($wcmamtx_layout['navwidget_disable_avatar'])
    : "no";

    $navwidget_disable_username = isset($wcmamtx_layout['navwidget_disable_username'])
    ? esc_html($wcmamtx_layout['navwidget_disable_username'])
    : "no";

    $avatar_html_final = '';

    $display_name_final = '';

    if ($navwidget_disable_avatar != "yes") {
        $avatar_html_final = '<span class="wcmamtx-nav-avatar-wrap">' . $avatar_html . '</span>';
    }
    

    if ($navwidget_disable_username != "yes") {
        $display_name_final = '<span class="wcmamtx-nav-name">' . $display_name . '</span>';
    }
    

    

    // Build the new trigger anchor (replaces the plain <a class="menu-link" href="...">TEXT<i ...></i></a>)
    $new_trigger = '<a class="menu-link wcmamtx-nav-trigger" href="' . $frontend_url . '" aria-haspopup="true" aria-expanded="false">
    ' . $avatar_html_final . '
    <span class="wcmamtx-nav-user-info">
    <span class="wcmamtx-nav-label">' . $nav_header_widget_text . '</span>
    ' . $display_name_final . '
    </span>
    <span class="wcmamtx-nav-chevron" aria-hidden="true">
    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    </span>
    </a>';

    // Use a regex to replace the existing anchor inside the wcmamtx_menu_logged_in li
    // The plugin renders: <li ...wcmamtx_menu_logged_in...><a class="menu-link" href="...">TEXT<i ...></i></a><ul...>
    $items = preg_replace_callback(
        '/(<li[^>]*wcmamtx_menu_logged_in[^>]*>)(<a[^>]*class="menu-link"[^>]*>.*?<\/a>)/s',
        function($m) use ($new_trigger) {
            return $m[1] . $new_trigger;
        },
        $items
    );

    return $items;
}








public function wcmamtx_google_callback() {

        if (
            empty($_GET['wcmamtx-social']) ||
            $_GET['wcmamtx-social'] !== 'google'
        ) {
            return;
        }

        $code = sanitize_text_field($_GET['code']);

        $token = wcmamtx_get_google_token($code);

        $user = wcmamtx_get_google_user($token);

        wcmamtx_login_or_create_user($user);

        exit;
    }


    public function register_custom_menu_link($wp_admin_bar){

        if ( ! current_user_can( 'manage_options' )  || (!is_account_page()) ) {
            return;
        }

        if (is_account_page()) {

            $args = array(
                'id'    => 'wcmamtx_customize_myaccount', // Unique ID for your link
                'title' =>  esc_html__('My account live customizer','customize-my-account-for-woocommerce'), // The text that will appear in the admin bar
                'href'  => ''.admin_url().'admin.php?page=wcmamtx_frontend_customizer', // The URL the link will point to
                'meta'  => array(
                    'class'  => 'wcmamtx_customize_myaccount-class', // Custom CSS class
                ),
            );

        } 




        $wp_admin_bar->add_node($args);
    }





    public function sysbasics_dashboard_menu_function() {
        ob_start();

        $this->wcmamtx_add_myaccount_links();

        return ob_get_clean();
    }




    public function wcmamtx_add_myaccount_links() { 

        $wcmamtx_layout = (array) get_option( 'wcmamtx_layout' );

        if ( ! is_user_logged_in() ) {
            return;
        }

        if ( ! is_account_page() ) {
            return;
        }




        $spending_layout_override  = isset($wcmamtx_layout['spending_layout_override'])  ? $wcmamtx_layout['spending_layout_override']  : '02';
        $spendingchart_override    = isset($wcmamtx_layout['spendingchart_override'])    ? $wcmamtx_layout['spendingchart_override']    : '01';

        $navwidget_disable_spendboxes_showhide = '02';

        $navwidget_disable_spendchart_showhide  = '02';

        if ($spending_layout_override === '01' || $spendingchart_override === '01') { 



            

            $navwidget_disable_spendboxes = isset($wcmamtx_layout['navwidget_disable_spendboxes']) ? $wcmamtx_layout['navwidget_disable_spendboxes'] : "no";

            $navwidget_disable_spendchart = isset($wcmamtx_layout['navwidget_disable_spendchart']) ? $wcmamtx_layout['navwidget_disable_spendchart'] : "no";


            

            if ($navwidget_disable_spendboxes == "yes") {

               $navwidget_disable_spendboxes_showhide = "02";


            } else {
                $navwidget_disable_spendboxes_showhide = "01";

            }

            if ($navwidget_disable_spendchart == "yes") { 

                $navwidget_disable_spendchart_showhide  = "02";


            } else {

                $navwidget_disable_spendchart_showhide  = "01";

            }

        }

        // Respect top-level customizer toggles: if disabled in Dashboard Widgets, force hide
        if ($spending_layout_override !== '01') {
            $navwidget_disable_spendboxes_showhide = '02';
        }
        if ($spendingchart_override !== '01') {
            $navwidget_disable_spendchart_showhide = '02';
        }

        $dashlink_layout_override = isset($wcmamtx_layout['dashlink_layout_override']) ? $wcmamtx_layout['dashlink_layout_override'] : "01";

        
        $spendingbox_dashboard_priority = isset($wcmamtx_layout['spendingbox_dashboard_priority']) ? $wcmamtx_layout['spendingbox_dashboard_priority'] : 10;

        $spendingchart_dashboard_priority = isset($wcmamtx_layout['spendingchart_dashboard_priority']) ? $wcmamtx_layout['spendingchart_dashboard_priority'] : 20;

        $dashlinks_dashboard_priority = isset($wcmamtx_layout['dashlinks_dashboard_priority']) ? $wcmamtx_layout['dashlinks_dashboard_priority'] : 30;


        $sc1_override  = isset($wcmamtx_layout['shortcode1_override']) ? $wcmamtx_layout['shortcode1_override'] : '02';
        $sc2_override  = isset($wcmamtx_layout['shortcode2_override']) ? $wcmamtx_layout['shortcode2_override'] : '02';
        $sc1_value     = isset($wcmamtx_layout['shortcode1_value'])    ? $wcmamtx_layout['shortcode1_value']    : '';
        $sc2_value     = isset($wcmamtx_layout['shortcode2_value'])    ? $wcmamtx_layout['shortcode2_value']    : '';
        $sc1_priority  = isset($wcmamtx_layout['shortcode1_dashboard_priority']) ? (int)$wcmamtx_layout['shortcode1_dashboard_priority'] : 60;
        $sc2_priority  = isset($wcmamtx_layout['shortcode2_dashboard_priority']) ? (int)$wcmamtx_layout['shortcode2_dashboard_priority'] : 70;

        $dashboard_widget_array = array(
            'spendingbox' => array(
                'show'     => $navwidget_disable_spendboxes_showhide,
                'priority' => $spendingbox_dashboard_priority
            ),
            'spendingchart' => array(
                'show'      => $navwidget_disable_spendchart_showhide,
                'priority'  => $spendingchart_dashboard_priority
            ),
            'dashlinks' => array(
                'show'     => $dashlink_layout_override,
                'priority' => $dashlinks_dashboard_priority
            ),
            'shortcode1' => array(
                'show'      => $sc1_override,
                'priority'  => $sc1_priority,
                'shortcode' => $sc1_value,
            ),
            'shortcode2' => array(
                'show'      => $sc2_override,
                'priority'  => $sc2_priority,
                'shortcode' => $sc2_value,
            ),
        );

        
        uasort( $dashboard_widget_array, function( $a, $b ) {
            return (int) $a['priority'] <=> (int) $b['priority'];
        } );




        foreach ($dashboard_widget_array as $dkey=>$dvalue) {
            if ($dvalue['show'] == "01") {
                if ( $dkey === 'shortcode1' || $dkey === 'shortcode2' ) {
                    if ( ! empty( $dvalue['shortcode'] ) ) {
                        echo '<div class="wcmamtx-shortcode-widget" style="margin-bottom:16px;">';
                        echo do_shortcode( wp_kses_post( $dvalue['shortcode'] ) );
                        echo '</div>';
                    }
                } else {
                    include( plugin_dir_path( __FILE__ ) . $dkey . '.php' );
                }
            }
        }
        
    }




    public function wp_nav_menu_items_function($out) {


        $out = wcmamtx_get_my_account_menu();

        return $out;

    }





    



    public function wcmamtx_override_template_child_theme_or_direct_free($template_slug) {

        $template = wcmamtx_plugin_path() . '/templates/myaccount/'.$template_slug.'.php';

       

        if ( locate_template( [ 'sysbasics-myaccount/' ] ) ) {
            $overrides_exist = true;
        } else {
            $overrides_exist = false;
        }


        
        if ( $overrides_exist ) {
            // check the theme for specific file requested
            $file = locate_template( [ 'sysbasics-myaccount/'.$template_slug.'.php' ], false, false );
            if ( ! $file ) {

                
                return $template;
            } else {
                $file = apply_filters( 'sysbasics_myaccount_template_override', $file, $template );
                
               
                return $file;
            }
        }
        

        return $template;
    }


    public function wpml_sl_blacklist_requests( $blacklist, $sitepress ) {

        $advanced_settings = (array) get_option( 'wcmamtx_advanced_settings' );

        if (!isset($advanced_settings)) {
            return  $blacklist;
        } else {

            $myaccount_id = get_option( 'woocommerce_myaccount_page_id' );
            $myaccount_page = get_post($myaccount_id); 
            $accountslug = $myaccount_page->post_name;


            foreach ($advanced_settings as $key=>$value) {
                if (isset($value['exclude_wpml_sticky']) && ($value['exclude_wpml_sticky'] == "01")) {
                    $blacklist[] = ''.$accountslug.'/'.$key.'';
                }

            }
        }

        
        

        return $blacklist;
    }


    public function wcmamtx_modify_post_content($content) {
        global $post;

        global $wp;
        
        $current_url = home_url( $wp->request );

        if (strpos($current_url,'lost-password') !== false) {
            return $content;
        } 

        if ($post === null) {

            return $content;

        }



        $post_id = $post->ID;

        $myaccount_id = get_option( 'woocommerce_myaccount_page_id' );


        $plugin_options = (array) get_option( 'wcmamtx_plugin_options' );


        if (isset($plugin_options['custom_myaccount']) && ($plugin_options['custom_myaccount'] == "yes")) {

            if ($post_id != $myaccount_id) {

                return $content;

            } else {

                $advanced_settings = (array) get_option( 'wcmamtx_advanced_settings' );

                global $wp_query;

                if (!isset($advanced_settings) || (sizeof($advanced_settings) == 1)) {
                    $tabs = wc_get_account_menu_items();
                } else {
                    $tabs = $advanced_settings;
                }

                foreach ($tabs as $key=>$value) { 

                    if (isset( $wp_query->query_vars[$key] )) {
                      return $content;
                  }

              }



              $plugin_options = (array) get_option( 'wcmamtx_plugin_options' );

              if (isset($plugin_options['custom_my_account_template']) && ($plugin_options['custom_my_account_template'] != "default") && ($plugin_options['custom_my_account_template'] != "") ) {
                $contentElementor = "";

                if (class_exists("\\Elementor\\Plugin")) {
                    $post_ID = $plugin_options['custom_my_account_template'];
                    $pluginElementor = \Elementor\Plugin::instance();
                    $contentElementor = $pluginElementor->frontend->get_builder_content($post_ID);
                }

                $content = $contentElementor;
            }
            
        }

    }






        return $content;
    }

    /**
 * Parses a url to extract the query parameters from it as a assoc array
 * @param string $url
 * @param bool $decode (optional) apply url decode
 * @return array
 */
    function parseUrl($url, $decode = false)
    {
        $urlData = parse_url($url);

        if (empty($urlData['query'])) { return null; }

        $query = explode("&", $urlData['query']);
        $parameters = array();

        foreach($query as $parameter) {
            $param = explode("=", $parameter);

            if (!empty($param) && count($param) == 2)
                $parameters[$param[0]] = $decode == true ? urldecode($param[1]) : $param[1];
        }

        return $parameters;
    }












    /**
     * Get endpoint URL.
     *
     * Gets the URL for an endpoint, which varies depending on permalink settings.
     *
     * @param  string $endpoint  Endpoint slug.
     * @param  string $value     Query param value.
     * @param  string $permalink Permalink.
     *
     * @return string
     */
    public function wcmamtx_get_endpoint_url( $endpoint, $value = '', $permalink = '' ) {
        if ( ! $permalink ) {
            $permalink = get_permalink();
        }

        // Map endpoint to options.
        
        $query_vars = WC()->query->get_query_vars();
        $endpoint   = ! empty( $query_vars[ $endpoint ] ) ? $query_vars[ $endpoint ] : $endpoint;
        $value      = ( get_option( 'woocommerce_myaccount_edit_address_endpoint', 'edit-address' ) === $endpoint ) ? wc_edit_address_i18n( $value ) : $value;

        if ( get_option( 'permalink_structure' ) ) {
            if ( strstr( $permalink, '?' ) ) {
                $query_string = '?' . wp_parse_url( $permalink, PHP_URL_QUERY );
                $permalink    = current( explode( '?', $permalink ) );
            } else {
                $query_string = '';
            }
            $url = trailingslashit( $permalink );

            if ( $value ) {
                $url .= trailingslashit( $endpoint ) . user_trailingslashit( $value );
            } else {
                $url .= user_trailingslashit( $endpoint );
            }

            $url .= $query_string;
        } else {
            $url = add_query_arg( $endpoint, $value, $permalink );
        }

        return apply_filters( 'woocommerce_get_endpoint_url', $url, $endpoint, $value, $permalink );
    }




    public function wcmamtx_add_custom_endpoint_page() {
        $wcmamtx_tabs = get_option('wcmamtx_advanced_settings');
        add_option('wcmamtx_allowed_endpoint_trial', 02);
        add_option('wcmamtx_allowed_link_trial', 02);

        $core_fields       = 'dashboard,orders,downloads,edit-address,edit-account,customer-logout';

        if (!is_array($wcmamtx_tabs)) {

            return;
        }

        if (!isset($wcmamtx_tabs) || (sizeof($wcmamtx_tabs) == 1)) {
            return;
        } 


        foreach ($wcmamtx_tabs as $key=>$value) {

            if (!preg_match('/\b'.$key.'\b/', $core_fields )) {

                if (isset($value['endpoint_key']) && ($value['endpoint_key'] != '')) {
                    $new_key = $value['endpoint_key'];
                } else {
                    $new_key = $key;
                }

                if (isset($value['wcmamtx_type']) && ($value['wcmamtx_type'] == "endpoint") ) {
                    add_rewrite_endpoint( $new_key, EP_ROOT | EP_PAGES );

                    $flush_cache = get_option('wcmamtx_flush_rewrite_cache_required',"no");

                    if ($flush_cache == "yes") {
                        add_action( 'wp_loaded', array($this,'wcmamtx_flush_rewrite_rules') );

                        update_option('wcmamtx_flush_rewrite_cache_required',"no");
                    }

                    
                    
                    
                }
            }

        }

        $this->wcmamtx_core_endpoint_contents();

        
    }

    public function wcmamtx_flush_rewrite_rules() {
        

            flush_rewrite_rules();
    
    }


    public function wcmamtx_override_default_navigation_template( $template, $template_name, $template_path ) {

        $theme = wp_get_theme();
        $name  = $theme->{'Name'};
        $name  = str_replace(" ", "-", $name);

        $tname    = strtolower($name);



        $wcmamtx_layout = (array) get_option( 'wcmamtx_layout' );

       

        $plugin_options = (array) get_option( 'wcmamtx_plugin_options' );


        if (isset($plugin_options['disable_navigation']) && ($plugin_options['disable_navigation'] == "yes")) {
            return $template;
        }
         
        if ( strstr($template, 'navigation.php') ) {
            $template = $this->wcmamtx_override_template_child_theme_or_direct_free("navigation");
        } else if ( strstr($template, 'dashboard.php') ) {
            $template = $this->wcmamtx_override_template_child_theme_or_direct_free("dashboard");
        } 




        $order_template_override = isset($wcmamtx_layout['order_template_override']) ? $wcmamtx_layout['order_template_override'] : "01";


        if ( strstr($template, 'orders.php') && ($order_template_override != "02") && !is_checkout() && is_account_page() ) {
            $template = $this->wcmamtx_override_template_child_theme_or_direct_free('orders');
        }

        
        $download_template_override = isset($wcmamtx_layout['download_template_override']) ? $wcmamtx_layout['download_template_override'] : "01";
        


        if ( strstr($template, 'order-downloads.php') && ($download_template_override != "02") && !is_checkout() && is_account_page()) {
            $template = wcmamtx_plugin_path() . '/templates/myaccount/order/order-downloads.php';
        }


        $view_order_template_override = isset($wcmamtx_layout['view_order_template_override']) ? $wcmamtx_layout['view_order_template_override'] : "01";
        


        if ( strstr($template, 'view-order.php') && ($view_order_template_override != "02") && !is_checkout() && is_account_page()) {
            $template = wcmamtx_plugin_path() . '/templates/myaccount/view-order.php';
        }

        
        $formlogin_layout_override = isset($wcmamtx_layout['formlogin_layout_override']) ? $wcmamtx_layout['formlogin_layout_override'] : "02";
        


        if ( strstr($template, 'form-login.php') && ($formlogin_layout_override != "02") && is_account_page()) {
            $template = wcmamtx_plugin_path() . '/templates/myaccount/form-login.php';



             $file_to_check = "wcmamtx_template/form-login.php"; // Change to your relative file path

             if ( file_exists( get_stylesheet_directory() . '/' . $file_to_check ) ) {
         // The file exists in the active child theme
                $template = ''.get_stylesheet_directory().'/wcmamtx_template/form-login.php';
            }
        }
        
        return $template;
    }


    


	public function wcmamtx_load_assets() {

		$wcmamtx_locals = array('ajax_url'              => admin_url( 'admin-ajax.php' ));

        $wcmamtx_plugin_options                         = (array) get_option('wcmamtx_plugin_options');

        $wcmamtx_pro_settings  = (array) get_option('wcmamtx_pro_settings');  

        $wcmamtx_plugin_options = (array) get_option('wcmamtx_plugin_options');


        $avatar_settings = (array) get_option( 'wcmamtx_avatar_settings' );

        $default_source  = isset($avatar_settings['disable_gravtar']) && ($avatar_settings['disable_gravtar'] == "yes") ? "local" : "gravtar";


        $enable_cropping  = isset($avatar_settings['enable_cropping']) && ($avatar_settings['enable_cropping'] == "yes") ? "yes" : "no";

        $default_pic    = ''.wcmamtx_PLUGIN_URL.'assets/images/default_avatar.jpg';
        $user_id        = get_current_user_id();

        $user_gravatar_url = '';

        $local_avatars = get_user_meta( $user_id, 'sysbasics_user_avatar', true );

        //delete_user_meta($user_id,'wcmamtx_last_gravtar');


        $args = array();

        $user_info = get_userdata($user_id);
        if ($user_info) {
            $user_email        = $user_info->user_email;
            $user_gravatar_url = get_avatar_url( $user_email, array_merge( $args, array( 'size' => "200") ) );
        
            


            switch($default_source) {
                case "gravtar":
                $default_pic = $user_gravatar_url;

                

                $last_gravtar_url = get_user_meta($user_id,"wcmamtx_last_gravtar",true);

                if ( (empty($last_gravtar_url)) && (strpos($user_gravatar_url, "gravatar") !== false) ) {
                    

                    update_user_meta($user_id,"wcmamtx_last_gravtar",$user_gravatar_url);

                } else if (($last_gravtar_url != $user_gravatar_url) && ($user_gravatar_url != $default_pic) && (strpos($user_gravatar_url, "gravatar") !== false)) {



                    update_user_meta($user_id,"wcmamtx_last_gravtar",$user_gravatar_url);
                }

                $fresh_gravtar_url = get_user_meta($user_id,"wcmamtx_last_gravtar",true);


                if ($fresh_gravtar_url != "") {
                    $default_pic = $fresh_gravtar_url;
                }


                break;

                case "local":
                $default_pic = ''.wcmamtx_PLUGIN_URL.'assets/images/default_avatar.jpg';
                break;

                default:
                $default_pic = ''.wcmamtx_PLUGIN_URL.'assets/images/default_avatar.jpg';
                break;
            }

        }




        $wcmamtx_locals = array(
            'ajax_navigation'   => isset($wcmamtx_plugin_options['ajax_navigation']) ? $wcmamtx_plugin_options['ajax_navigation'] : "no",
            'contentSelector'      => apply_filters( 'wcmamtx_ajax_content_selector', '#content, div.woocommerce' ),
            'ajax_url'              => admin_url( 'admin-ajax.php' ),
            'nonce'                 => wp_create_nonce( 'wcmamtx_ajax_security_nonce' ),
            'default_pic'           => $default_pic,
            'mode'                  => $default_source,
            'uploading_text'        => esc_html__('Uploading... Please wait',"customize-my-account-for-woocommerce"),
            'restoring_text'        => esc_html__('Restoring... Please wait',"customize-my-account-for-woocommerce"),
            'error_text'            => esc_html__('An error occurred during transmission',"customize-my-account-for-woocommerce"),
            'file_text'             => esc_html__('Please select a file first',"customize-my-account-for-woocommerce"),
            'allow_cropper'         => $enable_cropping

        );


        global $post;




         if (is_account_page() || ((isset($post)) && ( false !== stripos( $post->post_content, '[sysbasics_user_avatar' ) ))) {

            if (is_user_logged_in()) {

                wp_enqueue_script( 'wcmamtx_cropper', ''.wcmamtx_PLUGIN_URL.'assets/js/cropper.min.js',array('jquery'), false, true);

                wp_enqueue_script( 'wcmamtxfrontend', ''.wcmamtx_PLUGIN_URL.'assets/js/frontend.js',array('jquery', 'wcmamtx_cropper'), false, true);


                wp_enqueue_style( 'wcmamtx-cropper', ''.wcmamtx_PLUGIN_URL.'assets/css/cropper.min.css' );

                wp_enqueue_style( 'wcmamtx-frontend', ''.wcmamtx_PLUGIN_URL.'assets/css/frontend.css' );

                wp_register_script( 'wcmamtx_webcame', ''.wcmamtx_PLUGIN_URL.'assets/js/webcam.min.js', array(), true );
                wp_enqueue_script('wcmamtx_webcame');

            }
            
        } 

        if (is_account_page() && (is_user_logged_in())) {

            
   
		    
            wp_enqueue_style( 'wcmamtx-font-awesome', ''.wcmamtx_PLUGIN_URL.'assets/css/all.min.css' );
            wp_localize_script( 'wcmamtxfrontend', 'wcmamtxfrontend', $wcmamtx_locals );

             if (isset($wcmamtx_pro_settings['sticky_sidebar_right']) && ($wcmamtx_pro_settings['sticky_sidebar_right'] == "yes")) {
                wp_enqueue_style( 'wcmamtx-sticky-right', ''.wcmamtx_PLUGIN_URL.'assets/css/sticky-right.css' );
             } else {
                wp_enqueue_style( 'wcmamtx-sticky', ''.wcmamtx_PLUGIN_URL.'assets/css/sticky.css' );

             }

            
        } 

        


        if (is_account_page() && is_user_logged_in()) {
            wp_enqueue_script( 'wcmamtxfrontend', ''.wcmamtx_PLUGIN_URL.'assets/js/frontend.js',array( 'jquery'), false, true);
            
        }

        

            $version = wcmamtx_get_woo_version_number_free();

            wp_enqueue_style( 'wcmamtx-frontend-unique', ''.wcmamtx_PLUGIN_URL.'assets/css/frontend-unique.css' );
            wp_enqueue_script( 'wcmamtx-frontend-unique', ''.wcmamtx_PLUGIN_URL.'assets/js/frontend-unique.js',array('jquery'),$version );

        
		
   
	}





    public function wcmamtx_rename_my_account_menu_items($items) {

		$wcmamtx_tabs = get_option('wcmamtx_advanced_settings');

        $core_fields_array =  array(
                         'dashboard'=>'dashboard',
                         'orders'=>'orders',
                         'downloads'=>'downloads',
                         'edit-address'=>'edit-address',
                         'edit-account'=>'edit-account',
                         'customer-logout'=>'customer-logout'
                      );
        

        if (!is_array($wcmamtx_tabs)) {
        	return $items;
        }

        if (!isset($wcmamtx_tabs) || (sizeof($wcmamtx_tabs) == 1)) {
            return $items;
        } else {
        	$new_ordered_array = $this->wcmamtx_reoder_array($wcmamtx_tabs,$items);

        }


        foreach ($items as $ikey=>$ivalue) {
            if (!array_key_exists($ikey, $new_ordered_array) && !array_key_exists($ikey, $core_fields_array)) {
                $new_ordered_array[$ikey] = $ivalue;           

            }
        }

        

		return $new_ordered_array;
    }

    public function wcmamtx_reoder_array($wcmamtx_tabs,$items) {
    	
    	$ordered = array();
        
        $core_fields       = 'dashboard,orders,downloads,edit-address,edit-account,customer-logout';

        $this->endpoint_content = '';



        foreach ($wcmamtx_tabs as $key=>$value) {
            
                if (!preg_match('/\b'.$key.'\b/', $core_fields ) && (isset($value['endpoint_key']))) {
                    $new_key = $value['endpoint_key'];

                } else {
                    $new_key = $key;
                }




                if (isset($value['endpoint_name']) && ($value['endpoint_name'] != '')) {
                    $new_value = $value['endpoint_name'];
                } else {
                    $new_value = $value;
                }


                if (isset($value['visibleto']) && ($value['visibleto'] != "all")) {
                    
                    $allowedroles  = isset($value['roles']) ? $value['roles'] : "";

                    $allowedusers  = isset($value['users']) ? $value['users'] : array();

                    $is_visible = wcmamtx_check_role_visibility($allowedroles,$value['visibleto'],$allowedusers);
                
                } else {

                    $is_visible = 'yes';
                }


                if (preg_match('/\b'.$key.'\b/', $core_fields )) {

                    if (isset($value['show'])) {

                        if ($value['show'] == "yes") {
                            
                            
                            if ($is_visible == 'yes') { 
                                
                                $ordered[$new_key] = $new_value;
                            }
                            
                        
                        }

                    } else {

                        

                        if ($is_visible == 'yes') {

                            $ordered[$new_key] = $new_value;
                        }
                    }

                } else {



                    if ($is_visible == 'yes') {
                        $ordered[$new_key] = $new_value;
                    }

                    if (isset($value['endpoint_key']) && ($value['endpoint_key'] != '')) {
                        $new_key = $value['endpoint_key'];
                    }

                    

                }
            	      
        }

        return $ordered;
    }


    public function wcmamtx_core_endpoint_contents() {

        

        $wcmamtx_tabs      = get_option('wcmamtx_advanced_settings');
        

        if (!is_array($wcmamtx_tabs)) {
            return;
        }

        if (!isset($wcmamtx_tabs) || (sizeof($wcmamtx_tabs) == 1)) {
            return;
        } else {
            
            $this->extra_content_foreach($wcmamtx_tabs);
        }


    }

    public function extra_content_foreach($wcmamtx_tabs) {
        $core_content_fields       = 'downloads,edit-address,edit-account';
        $core_fields       = 'dashboard,orders,downloads,edit-address,edit-account,customer-logout';

        $content  = '';
        $content_settings = 'after';

        foreach ($wcmamtx_tabs as $key=>$value) {

            
            if (isset($value['endpoint_name']) && ($value['endpoint_name'] != '')) {
                $endpoint_name = $value['endpoint_name'];
            } else {
                $endpoint_name = $value;
            } 

            if (preg_match('/\b'.$key.'\b/', $core_content_fields )) {

                $content           = isset($value['content']) ? $value['content'] : "";
                $content           = wcmamtx_parse_smart_tag_function($content,$endpoint_name);
                $content_settings  = isset($value['content_settings']) ? $value['content_settings'] : "after";

                switch($key) {
                    case "edit-address":
                        switch($content_settings) {
                            case "after":
                                add_action( 'woocommerce_after_edit_account_address_form', function() use ( $content ) {
                            
                                    echo apply_filters('the_content',$content);
                                });
                            break;

                            case "before":
                                add_action( 'woocommerce_before_edit_account_address_form', function() use ( $content ) {
                            
                                    echo apply_filters('the_content',$content);
                                });
                            break;
                        }
                    break;

                    case "downloads":
                        switch($content_settings) {
                            case "after":
                                add_action( 'woocommerce_after_account_downloads', function() use ( $content ) {
                            
                                    echo apply_filters('the_content',$content);
                                });
                            break;

                            case "before":
                                add_action( 'woocommerce_before_account_downloads', function() use ( $content ) {
                            
                                    echo apply_filters('the_content',$content);
                                });
                            break;
                        }
                    break;

                    case "edit-account":
                        switch($content_settings) {
                            case "after":
                                add_action( 'woocommerce_after_edit_account_form', function() use ( $content ) {
                            
                                    echo apply_filters('the_content',$content);
                                });
                            break;

                            case "before":
                                add_action( 'woocommerce_before_edit_account_form', function() use ( $content ) {
                            
                                    echo apply_filters('the_content',$content);
                                });
                            break;
                        }
                    break;
                }

            } elseif ((!preg_match('/\b'.$key.'\b/', $core_fields )) && (isset($value['wcmamtx_type']) && ($value['wcmamtx_type'] == "endpoint") )) {

                $content            = isset($value['content']) ? $value['content'] : "";
                $content           = wcmamtx_parse_smart_tag_function($content,$endpoint_name);
                

                $plugin_options = (array) get_option( 'wcmamtx_plugin_options' );

                if (isset($plugin_options['override_endpoints']) && ($plugin_options['override_endpoints'] == "yes") && isset($plugin_options['custom_templates'][$key]) && ($plugin_options['custom_templates'][$key] != "default") && ($plugin_options['custom_templates'][$key] != "") ) {
                     $contentElementor = "";

                     if (class_exists("\\Elementor\\Plugin")) {
                        $post_ID = $plugin_options['custom_templates'][$key];
                        $pluginElementor = \Elementor\Plugin::instance();
                        $contentElementor = $pluginElementor->frontend->get_builder_content($post_ID);
                    }

                    $content = $contentElementor;
                }


                global $end_key;
                $end_key = $key;

                add_filter( 'query_vars', array( $this, 'wcmamtx_do_query_vars' ), 0 );

                $endkey             = isset($value['endpoint_key']) ? $value['endpoint_key'] : $key;

                if ($content != '') {

                    add_action( 'woocommerce_account_'.$endkey.'_endpoint', function() use ( $content ) {

                     $allowed_html = wp_kses_allowed_html( 'post' );

                     $content = wp_kses( $content, $allowed_html );

                     echo apply_filters( 'the_content', $content );
                   });
                }

            }
        }
    }




    public function wcmamtx_do_query_vars( $vars ) {
        $vars[] = $this->endpoint_key;

        return $vars;
    }

    public function wcmamtx_link_url_redirect($url, $endpoint, $value, $permalink) {

        $wcmamtx_tabs = get_option('wcmamtx_advanced_settings');
        $core_fields       = 'dashboard,orders,downloads,edit-address,edit-account,customer-logout';


        if (!is_array($wcmamtx_tabs)) {

            return $url;
        }

        if (!isset($wcmamtx_tabs) || (sizeof($wcmamtx_tabs) == 1)) {
            return $url;
        }




        foreach ($wcmamtx_tabs as $key=>$value) {

            if (!preg_match('/\b'.$key.'\b/', $core_fields )) {

                if (isset($value['endpoint_key']) && ($value['endpoint_key'] != '')) {
                    $new_key = $value['endpoint_key'];
                } else {
                    $new_key = $key;
                }

                if (isset($value['wcmamtx_type']) && ($value['wcmamtx_type'] == "link") ) {

                    $endpoint_url  = isset($value['link_inputtarget']) ? $value['link_inputtarget'] : "#";

                    if ( $endpoint == $new_key ) {


                        $url = $endpoint_url;

                    }
                }
            }

        }

        


        return $url;
    }





    public function wcmamtx_render_shortcode_widgets() {
        $layout = (array) get_option( 'wcmamtx_layout', [] );
        $g = function( $k, $d ) use ( $layout ) { return isset( $layout[ $k ] ) ? $layout[ $k ] : $d; };

        $sc1_on       = $g( 'shortcode1_override', '02' ) === '01';
        $sc2_on       = $g( 'shortcode2_override', '02' ) === '01';
        $sc1_val      = trim( $g( 'shortcode1_value', '' ) );
        $sc2_val      = trim( $g( 'shortcode2_value', '' ) );
        $sc1_priority = (int) $g( 'shortcode1_dashboard_priority', 60 );
        $sc2_priority = (int) $g( 'shortcode2_dashboard_priority', 70 );

        $widgets = [];
        if ( $sc1_on && $sc1_val ) $widgets[ $sc1_priority ] = $sc1_val;
        if ( $sc2_on && $sc2_val ) $widgets[ $sc2_priority ] = $sc2_val;
        if ( empty( $widgets ) ) return;

        ksort( $widgets );
        foreach ( $widgets as $shortcode ) {
            echo '<div class="wcmamtx-shortcode-widget" style="margin-bottom:16px;">';
            echo do_shortcode( wp_kses_post( $shortcode ) );
            echo '</div>';
        }
    }
   }
}

new wcmamtx_add_frontend_class();

?>