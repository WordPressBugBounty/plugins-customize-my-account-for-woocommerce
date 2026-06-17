<?php 


$wcmamtx_layout = (array) get_option( 'wcmamtx_layout' );


if (array_key_exists(0, $wcmamtx_layout)) {
    $wcmamtx_layout['formlogin_layout_override'] = "02";

    $wcmamtx_layout['navigationwidget_layout_override'] = wcmamtx_get_nav_widget_array_theme2();
    
    $wcmamtx_layout['nav_header_widget'] = wcmamtx_get_nav_widget_array_theme();
    
}



$nav_style = isset($wcmamtx_layout['nav_style']) ? $wcmamtx_layout['nav_style'] : wcmamtx_get_clean_design_theme_array();

$dash_style = isset($wcmamtx_layout['dash_style']) ? $wcmamtx_layout['dash_style'] : "01";
$sidebar_style = isset($wcmamtx_layout['sidebar_style']) ? $wcmamtx_layout['sidebar_style'] : "01";

$order_style = isset($wcmamtx_layout['order_style']) ? $wcmamtx_layout['order_style'] : "01";
$order_template_override = isset($wcmamtx_layout['order_template_override']) ? $wcmamtx_layout['order_template_override'] : "01";

$download_style = isset($wcmamtx_layout['download_style']) ? $wcmamtx_layout['download_style'] : "01";

$download_template_override = isset($wcmamtx_layout['download_template_override']) ? $wcmamtx_layout['download_template_override'] : "01";

$dashlink_layout_override = isset($wcmamtx_layout['dashlink_layout_override']) ? $wcmamtx_layout['dashlink_layout_override'] : "01";

$view_order_style = isset($wcmamtx_layout['view_order_style']) ? $wcmamtx_layout['view_order_style'] : "01";

$view_order_template_override = isset($wcmamtx_layout['view_order_template_override']) ? $wcmamtx_layout['view_order_template_override'] : "01";


$thankyou_style = isset($wcmamtx_layout['thankyou_style']) ? $wcmamtx_layout['thankyou_style'] : "01";

$thankyou_template_override = isset($wcmamtx_layout['thankyou_template_override']) ? $wcmamtx_layout['thankyou_template_override'] : "01";

$dashlink_box_override = isset($wcmamtx_layout['dashlink_box_override']) ? $wcmamtx_layout['dashlink_box_override'] : "01";

$linkbox_style = isset($wcmamtx_layout['linkbox_style']) ? $wcmamtx_layout['linkbox_style'] : "02";

$profilebox_override = isset($wcmamtx_layout['profilebox_override']) ? $wcmamtx_layout['profilebox_override'] : "01";

$profilebox_style = isset($wcmamtx_layout['profilebox_style']) ? $wcmamtx_layout['profilebox_style'] : "02";

$orderpay_style = isset($wcmamtx_layout['orderpay_style']) ? $wcmamtx_layout['orderpay_style'] : "01";

$orderpay_template_override = isset($wcmamtx_layout['orderpay_template_override']) ? $wcmamtx_layout['orderpay_template_override'] : "01";


$formlogin_layout_override = isset($wcmamtx_layout['formlogin_layout_override']) ? $wcmamtx_layout['formlogin_layout_override'] : "02";

$google_client_id = isset( $wcmamtx_layout['google_client_id'] ) ? $wcmamtx_layout['google_client_id'] : '';
$google_client_secret = isset( $wcmamtx_layout['google_client_secret'] ) ? $wcmamtx_layout['google_client_secret'] : '';

$frontend_url = get_permalink(get_option('woocommerce_myaccount_page_id'));
?>

<div class="wcmamtx_layout_tabs">

    <div class="tabs-sidebar">

        <button class="wcmamtx_tab_link active" data-tab="dashboard">
           <span class="dashicons dashicons-layout"></span>
            <?php echo esc_html__( 'Layout' ,'customize-my-account-for-woocommerce'); ?>
        </button>

        <button class="wcmamtx_tab_link" data-tab="orders">
            <span class="dashicons dashicons-art"></span>
            <?php echo esc_html__( 'Templates' ,'customize-my-account-for-woocommerce'); ?>
        </button>

        <button class="wcmamtx_tab_link" data-tab="downloads">
            <span class="dashicons dashicons-welcome-widgets-menus"></span>
            <?php echo esc_html__( 'Widgets' ,'customize-my-account-for-woocommerce'); ?>
        </button>

        <button class="wcmamtx_tab_link" data-tab="login">
            <span class="dashicons dashicons-admin-users"></span>
            <?php echo esc_html__( 'Login & Register' ,'customize-my-account-for-woocommerce'); ?>
        </button>

        <?php echo apply_filters('wcmamtx_add_new_layout_settings_tab',''); ?>

    </div>

    <div class="tabs-content">

        <div class="tab-pane active" id="dashboard">
            <?php include('widgets/sidebar_position_admin.php'); ?>
            <?php include('widgets/navigation_style_admin.php'); ?>
        </div>

        <div class="tab-pane" id="orders">
            <?php include('widgets/all_templates_admin.php'); ?>
        </div>

        <div class="tab-pane" id="downloads">
            <?php include('widgets/dashboard_links_admin.php'); ?>
             <?php include('widgets/customer_spending_admin.php'); ?>
             <?php include('widgets/navigation_widget_admin.php'); ?>
              <?php include('widgets/profile_completion_admin.php'); ?>
              <?php include('widgets/linkboxes_admin.php'); ?>
        </div>

        <div class="tab-pane" id="login">
            <?php include('widgets/loginbox_admin.php'); ?>

        </div>


        <?php echo apply_filters('wcmamtx_add_new_layout_settings_content',''); ?>
    </div>

</div>
<a type="button" target="_blank" href="<?php echo $frontend_url; ?>" id="wcmamtx_frontend_link" class="btn btn-sm btn-primary wcmamtx_frontend_link wcmamtx_frontend_link_top_sticky" >
	<span class="dashicons dashicons-welcome-view-site"></span>
	<?php echo esc_html__( 'Frontend' ,'customize-my-account-for-woocommerce'); ?>
</a>