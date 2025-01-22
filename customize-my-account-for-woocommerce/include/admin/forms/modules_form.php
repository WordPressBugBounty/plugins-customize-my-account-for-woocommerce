<?php 


$module_settings = (array) get_option( 'wcmamtx_module_settings' );

?> 

<table class="widefat wcmamtx_options_table">

    


    <tr>
      <div class="alert alert-primary" role="alert">
        <?php echo esc_html__('You can enable/disable modules as per your requirement','customize-my-account-for-woocommerce'); ?>
        
      </div>
    </tr>
       
    <tr width="100%">

      <td width="100%">
        <?php 

        $el_widgets1 = array(
          'user-avatar'=>esc_html__('User Avatar (Included)','customize-my-account-for-woocommerce'),
          'elementor-templates'=>esc_html__('User Avatar (Included)','customize-my-account-for-woocommerce'),
          'my-account-fields'=>esc_html__('My Account Fields','customize-my-account-for-woocommerce'),
          'easy-checkout-fields'=>esc_html__('Easy Checkout Fields','customize-my-account-for-woocommerce'),
          'sample'=>esc_html__('sample','customize-my-account-for-woocommerce')
        );


        $el_widgets2 = array(
          'user-avatar'=>esc_html__('User Avatar (Included)','customize-my-account-for-woocommerce'),
          'elementor-templates'=>esc_html__('Elementor Templates (Included)','customize-my-account-for-woocommerce'),
          'my-account-fields'=>esc_html__('My Account Fields (Included)','customize-my-account-for-woocommerce'),
          'easy-checkout-fields'=>esc_html__('Easy Checkout Fields','customize-my-account-for-woocommerce'),
          'my-account-fields-pro'=>esc_html__('My Account Fields Pro (Pro Module)','customize-my-account-for-woocommerce'),
          'Order-actions'=>esc_html__('Order Actions (Pro Module)','customize-my-account-for-woocommerce'),
          'Order-columns'=>esc_html__('Order Columns (Pro Module)','customize-my-account-for-woocommerce'),
          'Download-columns'=>esc_html__('Download Columns (Pro Module)','customize-my-account-for-woocommerce'),
          'easy-checkout-fields-pro'=>esc_html__('Easy Checkout Fields Editor Pro (Pro Module)','customize-my-account-for-woocommerce'),
          'sample'=>esc_html__('sample','customize-my-account-for-woocommerce')
        );



                
        $el_widgets = isset($module_settings['el_widgets']) && !empty($module_settings['el_widgets']) ? $module_settings['el_widgets'] : $el_widgets1;


               
          ?>
          <ul class="wcmamtx_elwidget_ul2">
          <?php

        foreach ($el_widgets2 as $key=>$value) { ?>

          <li class="wcmamtx_module_<?php echo $key; ?>">
            <label><?php echo esc_attr($value); ?></label>
            <input type="checkbox" data-toggle="toggle" data-on="<?php echo esc_html__('Yes','customize-my-account-for-woocommerce'); ?>" data-off="<?php echo esc_html__('No','customize-my-account-for-woocommerce'); ?>" class="wcmamtx_widget_checkbox" name="wcmamtx_module_settings[el_widgets][<?php echo $key; ?>]" value="yes" <?php if (isset($el_widgets[$key])) { echo 'checked';}?> <?php if (isset($el_widgets2[$key]) && (!isset($el_widgets1[$key]))) { echo 'disabled';}?> >

             <?php

             switch($key) {
              case "user-avatar":
                echo '<p class="wcmamtx_module_info">User avatar modules allows users to upload custom avatar instead of gravtar, display username before navigation.</p>';
              break;

              case "elementor-templates":
                echo '<p class="wcmamtx_module_info">Elementor users can create custom my account elementor templates, plugin supports all widgets related to my account page, You can override login , register page and much more.</p>';
              break;

              case "my-account-fields":
                echo '<p class="wcmamtx_module_info">Manage user fields on my account page , create custom fields , create custom fields form inside my account endpoints and much more.</p>';
              break;

              case "easy-checkout-fields":
                echo '<p class="wcmamtx_module_info">Checkout field module that easily intergrates with my account fields module, collect required details from user on checkout page and display them on view order page.</p>';
              break;

              case "my-account-fields-pro":
                echo '<p class="wcmamtx_module_info">All the features of my account fields module plus show fields as per multiple dynamic rules plus show/hide fields conditionaly based on value of other fields. <a href="https://www.sysbasics.com/go/my-account-fields/" target="_blank">More Details</a></p>';
              break;

              case "Order-actions":
                echo '<p class="wcmamtx_module_info">Manage existing order actions as well as add new custom order actions. <a href="https://www.sysbasics.com/go/customize/" target="_blank">More Details</a></p>';
              break;

              case "Order-columns":
                echo '<p class="wcmamtx_module_info">Manage existing order Columns as well as add new custom order Columns where you can display user data collected from checkout fields module. <a href="https://www.sysbasics.com/go/customize/" target="_blank">More Details</a></p>';
              break;

              case "Download-columns":
                echo '<p class="wcmamtx_module_info">Manage existing Download Columns as well as add new custom Download Columns where you can display user data collected from checkout fields module. <a href="https://www.sysbasics.com/go/customize/" target="_blank">More Details</a></p>';
              break;

              case "easy-checkout-fields-pro":
                echo '<p class="wcmamtx_module_info">All the features of checkout module  plus show fields as per multiple dynamic rules plus show/hide fields conditionaly based on value of other fields. <a href="https://www.sysbasics.com/go/easy-checkout/" target="_blank">More Details</a></p>';
              break;
            }

             ?>
            
          </li>           

        <?php }

        ?>
          </ul>
      </td>
    </tr>
</table>