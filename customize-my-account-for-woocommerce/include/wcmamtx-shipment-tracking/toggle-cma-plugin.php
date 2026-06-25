<?php
/**
 * Plugin Name: Toggle CMA Free/Pro
 * Description: Adds a button in the WordPress admin bar to toggle between free and pro versions of Customize My Account for WooCommerce.
 * Version: 1.0.0
 * Author: Novamira
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'TCMA_FREE', 'customize-my-account-for-woocommerce/customize-my-account-for-woocommerce.php' );
define( 'TCMA_PRO',  'customize-my-account-for-woocommerce-pro/customize-my-account-for-woocommerce-pro.php' );

// -- Admin bar button --
add_action( 'admin_bar_menu', function( WP_Admin_Bar $bar ) {
    if ( ! current_user_can( 'activate_plugins' ) ) return;

    $free_active = is_plugin_active( TCMA_FREE );
    $pro_active  = is_plugin_active( TCMA_PRO );

    if ( $free_active ) {
        $label = '&#9889; Switch to CMA Pro';
        $color = '#2271b1';
    } elseif ( $pro_active ) {
        $label = '&#x1F504; Switch to CMA Free';
        $color = '#d63638';
    } else {
        $label = '&#9888; CMA: none active';
        $color = '#996800';
    }

    $bar->add_node( [
        'id'    => 'toggle-cma',
        'title' => '<span id="toggle-cma-btn" style="cursor:pointer;color:#fff;background:' . $color . ';padding:4px 10px;border-radius:3px;font-size:12px;line-height:26px;display:inline-block;">' . $label . '</span>',
        'href'  => '#',
        'meta'  => [ 'title' => 'Toggle CMA Free / Pro' ],
    ] );
}, 100 );

// -- Inline JS for AJAX call --
add_action( 'admin_footer', function() {
    if ( ! current_user_can( 'activate_plugins' ) ) return;
    ?>
    <script>
    (function(){
        var btn = document.getElementById('toggle-cma-btn');
        if ( ! btn ) return;
        var parentLink = btn.closest('a');
        if ( parentLink ) parentLink.href = '#';
        btn.addEventListener('click', function(e){
            e.preventDefault();
            e.stopPropagation();
            btn.textContent = '&#x23F3; Switching...';
            btn.style.opacity = '0.7';
            btn.style.pointerEvents = 'none';
            var data = new FormData();
            data.append('action', 'tcma_toggle');
            data.append('nonce',  '<?php echo wp_create_nonce("tcma_toggle"); ?>');
            fetch( '<?php echo admin_url("admin-ajax.php"); ?>', {
                method : 'POST',
                body   : data,
                credentials: 'same-origin'
            })
            .then( r => r.json() )
            .then( function(res) {
                if ( res.success ) {
                    btn.textContent = res.data.message;
                    btn.style.opacity = '1';
                    setTimeout( function(){ location.reload(); }, 1200 );
                } else {
                    btn.textContent = '&#x274C; ' + (res.data || 'Error');
                    btn.style.opacity = '1';
                    btn.style.pointerEvents = 'auto';
                }
            })
            .catch( function() {
                btn.textContent = '&#x274C; Request failed';
                btn.style.opacity = '1';
                btn.style.pointerEvents = 'auto';
            });
        });
    })();
    </script>
    <?php
} );

// -- AJAX handler --
add_action( 'wp_ajax_tcma_toggle', function() {
    check_ajax_referer( 'tcma_toggle', 'nonce' );

    if ( ! current_user_can( 'activate_plugins' ) ) {
        wp_send_json_error( 'Permission denied.' );
    }

    if ( ! function_exists( 'activate_plugin' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    $free_active = is_plugin_active( TCMA_FREE );
    $pro_active  = is_plugin_active( TCMA_PRO );

    if ( $free_active ) {
        deactivate_plugins( TCMA_FREE );
        $result = activate_plugin( TCMA_PRO );
        if ( is_wp_error( $result ) ) {
            activate_plugin( TCMA_FREE );
            wp_send_json_error( $result->get_error_message() );
        }
        wp_send_json_success( [ 'message' => 'Switched to CMA Pro!' ] );

    } elseif ( $pro_active ) {
        deactivate_plugins( TCMA_PRO );
        $result = activate_plugin( TCMA_FREE );
        if ( is_wp_error( $result ) ) {
            activate_plugin( TCMA_PRO );
            wp_send_json_error( $result->get_error_message() );
        }
        wp_send_json_success( [ 'message' => 'Switched to CMA Free!' ] );

    } else {
        wp_send_json_error( 'Neither plugin is currently active.' );
    }
} );
