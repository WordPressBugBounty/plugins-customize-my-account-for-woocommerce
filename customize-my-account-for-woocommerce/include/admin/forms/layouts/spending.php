<table>  


    <tr class="nav_header_widget_tr" style="<?php if (isset($wcmamtx_layout['nav_header_widget']) && ($wcmamtx_layout['nav_header_widget'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
        <td><label><?php echo esc_html__('Disable Customer Spending Boxes','customize-my-account-for-woocommerce'); ?></label> <br />
        </td>
        <td>
            <input type="checkbox" data-toggle="toggle"  data-on="<?php echo esc_html__('Yes','customize-my-account-for-woocommerce'); ?>" data-off="<?php echo esc_html__('No','customize-my-account-for-woocommerce'); ?>" class="wcmamtx_show_only_logged_in" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[navwidget_disable_spendboxes]" value="yes" <?php if (isset($wcmamtx_layout['navwidget_disable_spendboxes']) && ($wcmamtx_layout['navwidget_disable_spendboxes'] == "yes")) { echo 'checked'; } ?>>

        </td>
    </tr>

    <tr class="nav_header_widget_tr" style="<?php if (isset($wcmamtx_layout['nav_header_widget']) && ($wcmamtx_layout['nav_header_widget'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
        <td><label><?php echo esc_html__('Disable Customer Spending Chart','customize-my-account-for-woocommerce'); ?></label> <br />
        </td>
        <td>
            <input type="checkbox" data-toggle="toggle"  data-on="<?php echo esc_html__('Yes','customize-my-account-for-woocommerce'); ?>" data-off="<?php echo esc_html__('No','customize-my-account-for-woocommerce'); ?>" class="wcmamtx_show_only_logged_in" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[navwidget_disable_spendchart]" value="yes" <?php if (isset($wcmamtx_layout['navwidget_disable_spendchart']) && ($wcmamtx_layout['navwidget_disable_spendchart'] == "yes")) { echo 'checked'; } ?>>

        </td>
    </tr>
</table>