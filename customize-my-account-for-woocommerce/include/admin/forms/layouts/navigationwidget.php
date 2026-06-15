<table>  
    <tr>
    	<td><label><?php echo esc_html__('Show My Account widget on navigation menu','customize-my-account-for-woocommerce'); ?></label> <br />
    	</td>
    	<td>
    		<input type="checkbox" data-toggle="toggle"  data-on="<?php echo esc_html__('Yes','customize-my-account-for-woocommerce'); ?>" data-off="<?php echo esc_html__('No','customize-my-account-for-woocommerce'); ?>" class="wcmamtx_show_nav_header_widget" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[nav_header_widget]" value="yes" <?php if (isset($wcmamtx_layout['nav_header_widget']) && ($wcmamtx_layout['nav_header_widget'] == "yes")) { echo 'checked'; } ?>>
    		

    	</td>
    </tr>


    <tr class="nav_header_widget_tr" style="<?php if (isset($wcmamtx_layout['nav_header_widget']) && ($wcmamtx_layout['nav_header_widget'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
    	<td><label><?php echo esc_html__('Header Menu Location','customize-my-account-for-woocommerce'); ?></label> <br />
    	</td>
    	<td>
    		<?php 

    		$menu_locations = get_nav_menu_locations();

            //$firstKey = array_key_first($menu_locations); 



    		?>
    		<select class="wcmamtx_default_tab_select" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[widget_menu_location]">
    			<?php foreach ($menu_locations as $key=>$value) { ?>
    				<option value="<?php echo $key;?>" <?php if (isset($wcmamtx_layout['widget_menu_location']) && ($wcmamtx_layout['widget_menu_location'] == $key)) {echo 'selected';} ?>><?php echo $key;?></option>
    			<?php } ?>
    		</select>

    		<span class="alert alert-success wcmamtx_apearance_menu_text">

    		<?php echo esc_html__('If this dropdown is empty then either you do not have menus added or your theme do not support menus. Visit ','customize-my-account-for-woocommerce'); ?> <a target="_blank" href="nav-menus.php"><?php echo esc_html__('Appearance/Menu','customize-my-account-for-woocommerce'); ?></a>
    	     </span>

    	</td>
    </tr>

    <tr class="nav_header_widget_tr" style="<?php if (isset($wcmamtx_layout['nav_header_widget']) && ($wcmamtx_layout['nav_header_widget'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
    	<td><label><?php echo esc_html__('Widget text (logged in)','customize-my-account-for-woocommerce'); ?></label> <br />
    	</td>
    	<td>
    		<?php             
    		$nav_header_widget_text = isset($wcmamtx_layout['nav_header_widget_text']) ? $wcmamtx_layout['nav_header_widget_text'] : esc_html__('My Account','customize-my-account-for-woocommerce');
    		?>
    		<input type="text" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[nav_header_widget_text]" value="<?php echo $nav_header_widget_text; ?>">

    	</td>
    </tr>


    <tr class="nav_header_widget_tr" style="<?php if (isset($wcmamtx_layout['nav_header_widget']) && ($wcmamtx_layout['nav_header_widget'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
    	<td><label><?php echo esc_html__('Widget text (logged out)','customize-my-account-for-woocommerce'); ?></label> <br />
    	</td>
    	<td>
    		<?php             
    		$nav_header_widget_text_logout = isset($wcmamtx_layout['nav_header_widget_text_logout']) ? $wcmamtx_layout['nav_header_widget_text_logout'] : esc_html__('Log In','customize-my-account-for-woocommerce');
    		?>
    		<input type="text" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[nav_header_widget_text_logout]" value="<?php echo $nav_header_widget_text_logout; ?>">

    	</td>
    </tr>

    <tr class="nav_header_widget_tr" style="<?php if (isset($wcmamtx_layout['nav_header_widget']) && ($wcmamtx_layout['nav_header_widget'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
    	<td><label><?php echo esc_html__('Show widget only for logged in','customize-my-account-for-woocommerce'); ?></label> <br />
    	</td>
    	<td>
    		<input type="checkbox" data-toggle="toggle"  data-on="<?php echo esc_html__('Yes','customize-my-account-for-woocommerce'); ?>" data-off="<?php echo esc_html__('No','customize-my-account-for-woocommerce'); ?>" class="wcmamtx_show_only_logged_in" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[show_only_logged_in]" value="yes" <?php if (isset($wcmamtx_layout['show_only_logged_in']) && ($wcmamtx_layout['show_only_logged_in'] == "yes")) { echo 'checked'; } ?>>

    	</td>
    </tr>

    <tr class="nav_header_widget_tr" style="<?php if (isset($wcmamtx_layout['nav_header_widget']) && ($wcmamtx_layout['nav_header_widget'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
        <td><label><?php echo esc_html__('Disable Avatar','customize-my-account-for-woocommerce'); ?></label> <br />
        </td>
        <td>
            <input type="checkbox" data-toggle="toggle"  data-on="<?php echo esc_html__('Yes','customize-my-account-for-woocommerce'); ?>" data-off="<?php echo esc_html__('No','customize-my-account-for-woocommerce'); ?>" class="wcmamtx_show_only_logged_in" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[navwidget_disable_avatar]" value="yes" <?php if (isset($wcmamtx_layout['navwidget_disable_avatar']) && ($wcmamtx_layout['navwidget_disable_avatar'] == "yes")) { echo 'checked'; } ?>>

        </td>
    </tr>

    <tr class="nav_header_widget_tr" style="<?php if (isset($wcmamtx_layout['nav_header_widget']) && ($wcmamtx_layout['nav_header_widget'] == "yes")) { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
        <td><label><?php echo esc_html__('Disable Username','customize-my-account-for-woocommerce'); ?></label> <br />
        </td>
        <td>
            <input type="checkbox" data-toggle="toggle"  data-on="<?php echo esc_html__('Yes','customize-my-account-for-woocommerce'); ?>" data-off="<?php echo esc_html__('No','customize-my-account-for-woocommerce'); ?>" class="wcmamtx_show_only_logged_in" name="<?php  echo esc_html__($this->wcmamtx_layout_page); ?>[navwidget_disable_username]" value="yes" <?php if (isset($wcmamtx_layout['navwidget_disable_username']) && ($wcmamtx_layout['navwidget_disable_username'] == "yes")) { echo 'checked'; } ?>>

        </td>
    </tr>
</table>