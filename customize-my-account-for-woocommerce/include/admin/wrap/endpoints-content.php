<?php
global $wp_roles;

$extra_content_core_fields = 'downloads,edit-address,edit-account';
$exclude_content_core_fields       = 'dashboard,orders,customer-logout';

if (isset($value['wcmamtx_type'])) {

 $wcmamtx_type = $value['wcmamtx_type'];

} else {
 $wcmamtx_type = 'endpoint';

}


if (isset($value['parent']) && ($value['parent'] != "")) {

 $wcmamtx_parent = $value['parent'];

} else {

 $wcmamtx_parent = 'none';

}



if ( ! isset( $wp_roles ) ) { 
 $wp_roles = new WP_Roles();  

}

$roles    = $wp_roles->roles;

$third_party = isset($value['third_party']) ? $value['third_party'] : $third_party;

/**
 *  dashboard background color data starts
 */

$default_color = wcmamtx_get_default_tab_color($key);



$default_color_font = '#334155';



/**
 *  dashboard background color data ends
 */
?>

<h3>
  <div class="wcmamtx_accordion_handler">

    <input type="checkbox" class="wcmamtx_accordion_onoff" parentkey="<?php echo $key; ?>"  <?php if (isset($value['show']) && ($value['show'] != "no"))  { echo "checked"; } elseif (!isset($value['show'])) { echo 'checked';} ?>>
    <input type="hidden" class="<?php echo $key; ?>_hidden_checkbox" value='<?php if (isset($value['show']) && ($value['show'] == "no")) { echo "no"; } else { echo 'yes';} ?>' name='<?php  echo $this->wcmamtx_notices_settings_page; ?>[<?php echo $key; ?>][show]'>

</div>

<span class="dashicons dashicons-menu-alt "></span>

<?php if (preg_match('/\b'.$key.'\b/', $core_fields )) { ?>
<?php } else { 

  if (isset($third_party)) {
     $key = strtolower($key);
     $key = str_replace(' ', '_', $key);
 }

 ?>


 <span type="removeicon" third_party="<?php if (isset($third_party)) { echo $third_party; } else { echo 'no';} ?>" parentkey="<?php echo $key; ?>" class="dashicons dashicons-trash wcmamtx_accordion_remove"></span>



<?php } ?>
<?php if (isset($name)) { echo $name; } ?>

<span class="wcmamtx_type_label">
    <?php 

    if (isset($third_party)) {
        echo esc_html__('Third Party Endpoint','customize-my-account-for-woocommerce');
        
    } else {
        echo ucfirst($wcmamtx_type);
    }

    

    ?>
</span>
</h3>

<div class="<?php echo $wcmamtx_type; ?>_accordion_content">

 <table class="wcmamtx_table widefat">



     <?php if ((!preg_match('/\b'.$key.'\b/', $core_fields ) && ($wcmamtx_type == 'endpoint')) && (!isset($third_party))) { ?>   

        <tr>
            <td>
             <label class="wcmamtx_accordion_label"><?php  echo esc_html__('Custom Endpoint Key','customize-my-account-for-woocommerce'); ?></label>
         </td>
         <td>
            <?php wcmamtx_show_disabled_input(); ?>
        </td>

    </tr>
<?php } else { ?>

 <input type="hidden" class="wcmamtx_accordion_input" name="<?php  echo $this->wcmamtx_notices_settings_page; ?>[<?php echo $key; ?>][endpoint_key]" value="<?php if (isset($value['endpoint_key'])) { echo $value['endpoint_key']; } else { echo $key; } ?>">


<?php  } ?>


<input type="hidden" name="<?php  echo $this->wcmamtx_notices_settings_page; ?>[<?php echo $key; ?>][wcmamtx_type]" value="<?php echo $wcmamtx_type; ?>">

<input type="hidden" name="<?php  echo $this->wcmamtx_notices_settings_page; ?>[<?php echo $key; ?>][parent]" class="wcmamtx_parent_field" value="<?php echo $wcmamtx_parent; ?>">



<tr>
    <td>
        <label class="wcmamtx_accordion_label"><?php  echo esc_html__('Label','customize-my-account-for-woocommerce'); ?></label>
    </td>
    <td>

        <input type="text" class="wcmamtx_accordion_input" name="<?php  echo $this->wcmamtx_notices_settings_page; ?>[<?php echo $key; ?>][endpoint_name]" value="<?php if (isset($value['endpoint_name'])) { echo $value['endpoint_name']; } else { if (isset($name ) ) { echo $name; } } ?>">
    </td>

</tr>

<?php if (($key != "dashboard")) { 


    $custom_label = (isset($value['endpoint_name'])) ? $value['endpoint_name'] : $name;
    
    $default_desc_text = wcmamtx_deshlink_default_description($key,$custom_label);

    $default_desc_text = isset($value['default_desc_text']) ? $value['default_desc_text'] : $default_desc_text;


    ?>


<tr>
    <td>
        <label class="wcmamtx_accordion_label"><?php  echo esc_html__('Dashlink description','customize-my-account-for-woocommerce'); ?></label>
    </td>
    <td>

        <input type="text" class="wcmamtx_accordion_input" name="<?php  echo $this->wcmamtx_notices_settings_page; ?>[<?php echo $key; ?>][default_desc_text]" value="<?php if (isset($value['default_desc_text'])) { echo $value['default_desc_text']; } else {  if (isset($default_desc_text) && ($default_desc_text != "")) { echo $default_desc_text; } } ?>">
    </td>

</tr>

<?php } ?>

<?php if ($key != "dashboard") { ?>

    <tr>
        <td>
            <label class=" wcmamtx_accordion_label"><?php echo esc_html__('Hide in dashboard links','customize-my-account-for-woocommerce'); ?></label>
        </td>
        <td>
            <input type="checkbox" data-toggle="toggle" data-on="<?php  echo esc_html__('Yes','customize-my-account-for-woocommerce'); ?>" data-off="<?php  echo esc_html__('No','customize-my-account-for-woocommerce'); ?>" data-size="sm" class="wcmamtx_accordion_input wcmamtx_accordion_checkbox checkmark" ype="checkbox" name="wcmamtx_advanced_settings[<?php echo $key; ?>][hide_dashboard_links]" value="01" <?php if (isset($value['hide_dashboard_links']) && ($value['hide_dashboard_links'] == "01")) { echo 'checked'; } ?>>
            
        </td>
    </tr>

<?php } ?>

<?php 

$wcmamtx_layout = (array) get_option( 'wcmamtx_layout' );

$dash_style = isset($wcmamtx_layout['dash_style']) ? $wcmamtx_layout['dash_style'] : "01";

if ($dash_style == 02) {

    if ($key != "dashboard") { 

        ?>

        <tr>
            <td>
                <label class="wcmamtx_accordion_label"><?php  echo esc_html__('Dashboard links styling','customize-my-account-for-woocommerce'); ?></label>
                <p class="wcmamtx_accordion_label_small"><?php  echo esc_html__('Background Color and Font Color','customize-my-account-for-woocommerce'); ?></p>
            </td>
            <td>

                <input type="text" class="wcmamtx_accordion_input wcmamtx_color_input" name="<?php  echo $this->wcmamtx_notices_settings_page; ?>[<?php echo $key; ?>][dash_back_color]" value="<?php if (isset($value['dash_back_color'])) { echo $value['dash_back_color']; } else { echo $default_color; } ?>">
                <input type="text" class="wcmamtx_accordion_input wcmamtx_color_input_font" name="<?php  echo $this->wcmamtx_notices_settings_page; ?>[<?php echo $key; ?>][dash_font_color]" value="<?php if (isset($value['dash_font_color'])) { echo $value['dash_font_color']; } else { echo $default_color_font; } ?>">
                <a href="#" pkey="<?php echo $key; ?>" class="wcmamtx_restore_dash_color"><p class="wcmamtx_accordion_label_small"><?php  echo esc_html__('restore default','customize-my-account-for-woocommerce'); ?></p>
                </a>
            </td>
            
        </tr>
    <?php } ?>

<?php } else if  ($dash_style == 01)  {

    if ($key != "dashboard") { 

        $default_desc_icon_colors = array(
            'orders'          => esc_html__('#f97316','customize-my-account-for-woocommerce'),
            'downloads'       => esc_html__('#22c55e','customize-my-account-for-woocommerce'),
            'edit-address'    => esc_html__('#ef4444','customize-my-account-for-woocommerce'),
            'edit-account'    => esc_html__('#8b5cf6','customize-my-account-for-woocommerce'),
        );


        $default_desc_color = isset($default_desc_icon_colors[$key]) ? $default_desc_icon_colors[$key] : "#3b82f6";

        $default_desc_color = isset($value['icon_color_ds']) ? $value['icon_color_ds'] : $default_desc_color;

        ?>

        <tr>
            <td>
                <label class="wcmamtx_accordion_label"><?php  echo esc_html__('Dashboard links styling','customize-my-account-for-woocommerce'); ?></label>
                <p class="wcmamtx_accordion_label_small"><?php  echo esc_html__('Icon color','customize-my-account-for-woocommerce'); ?></p>
            </td>
            <td>

                <input type="text" class="wcmamtx_accordion_input wcmamtx_color_input_dsh" name="<?php  echo $this->wcmamtx_notices_settings_page; ?>[<?php echo $key; ?>][icon_color_ds]" value="<?php if (isset($value['icon_color_ds'])) { echo $value['icon_color_ds']; } else { echo  $default_desc_color; } ?>">
                
                
            </td>
            
        </tr>
    <?php } ?>

<?php } ?>

<?php include('subwrap/countof-settings.php'); ?>

<?php 

if (preg_match('/\b'.$key.'\b/', $core_fields ))  { 

    include('subwrap/icon_settings_revised.php'); 

} else {
    include('subwrap/icon_settings.php'); 
}

?>

<tr class="fa_icon_tr" style= "<?php if ($icon_source == "custom") { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
    <td>
        <label class="wcmamtx_accordion_label"><?php  echo esc_html__('Icon','customize-my-account-for-woocommerce'); ?></label>
    </td>
    <td>

        <input type="text" class="wcmamtx_iconpicker icon-class-input" name="<?php  echo esc_html__($this->wcmamtx_notices_settings_page); ?>[<?php echo $key; ?>][icon]" value="<?php if (isset($value['icon'])) { echo $value['icon']; } ?>">
        <button type="button" class="btn btn-primary picker-button"><?php  echo esc_html__('Chose Font Awesome Icon','customize-my-account-for-woocommerce'); ?></button>
        <p><a target="_blank" class="" href="https://fontawesome.com/v4/icons/"><?php  echo esc_html__('Full list of Supported Icons','customize-my-account-for-woocommerce'); ?>                        </a></p>
    </td>

</tr>

<tr class="show_dashicon_tr" style= "<?php if ($icon_source == "dashicon") { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
    <td>
        <label class="wcmamtx_accordion_label"><?php  echo esc_html__('Icon','customize-my-account-for-woocommerce'); ?></label>
    </td>
    <td>

        <input class="regular-text " id="dashicons_picker_example_<?php echo $key; ?>" type="text" name="<?php  echo esc_html__($this->wcmamtx_notices_settings_page); ?>[<?php echo $key; ?>][dashicon]" value="<?php if (isset($value['dashicon'])) { echo $value['dashicon']; } ?>" />
        <input class="button dashicons-picker" type="button" value="<?php  echo esc_html__('Chose Dashicon','customize-my-account-for-woocommerce'); ?>" data-target="#dashicons_picker_example_<?php echo $key; ?>" />

    </td>

</tr>

<tr class="show_upload_tr" style= "<?php if ($icon_source == "upload") { echo 'display:table-row;'; } else { echo 'display:none;'; } ?>">
    <td>
        <label class="wcmamtx_accordion_label"><?php  echo esc_html__('Upload Icon','customize-my-account-for-woocommerce'); ?></label>
    </td>
    <td>
        <?php

        $swatchimage = isset($value['upload_icon']) ? $value['upload_icon'] : "";

        if (isset($swatchimage)) {
            $swatchurl     = wp_get_attachment_thumb_url( $swatchimage );
        } 
        ?>
        <div class="facility_thumbnail" id="facility_thumbnail_<?php echo $key; ?>" style="float:left;">
            <img src="<?php if (isset($swatchurl) && ($swatchurl != '')) { echo $swatchurl; } else { echo wcmamtx_placeholder_img_src(); }  ?>" width="60px" height="60px" />
            <div  class="image-upload-div" idval="<?php echo $key; ?>" >
                <input type="hidden" class="facility_thumbnail_id_<?php echo $key; ?>" name="<?php  echo esc_html__($this->wcmamtx_notices_settings_page); ?>[<?php echo $key; ?>][upload_icon]" value="<?php if (isset($swatchimage)) { echo $swatchimage; } ?>"/>
                <button type="submit" class="upload_image_button_<?php echo $key; ?> button"><?php echo esc_html__( 'Upload/Add image', 'customize-my-account-for-woocommerce' ); ?></button>
                <button type="submit" class="remove_image_button_<?php echo $key; ?> button"><?php echo esc_html__( 'Remove image', 'customize-my-account-for-woocommerce' ); ?></button>
            </div>
        </div>


    </td>

</tr>

<?php if  ((wcmamtx_wpmlsticky_mode == "on") && ($wcmamtx_type != 'group')) { ?>

 <tr>
    <td>
        <label class="wcmamtx_accordion_label"><?php  echo esc_html__('WPML Sticky Links','customize-my-account-for-woocommerce'); ?></label>
    </td>
    <td>    
        <input data-toggle="toggle" data-size="small" class="wcmamtx_accordion_input wcmamtx_accordion_checkbox form-check-input" type="checkbox" name="wcmamtx_advanced_settings[<?php echo $key; ?>][exclude_wpml_sticky]" <?php if (isset($value['exclude_wpml_sticky']) && ($value['exclude_wpml_sticky'] == "01")) { echo 'checked'; } ?> value="01">

        <p class="wpml_sticky_para"><?php  echo esc_html__('Exclude from WPML Sticky Url to avoid transforming into PageID','customize-my-account-for-woocommerce'); ?></p>
    </td>
</tr>
<?php } ?>


<?php if ($wcmamtx_type == 'link') {     
    ?>


    <tr>
        <td>
            <label class="wcmamtx_accordion_label"><?php  echo esc_html__('Link url','customize-my-account-for-woocommerce'); ?></label>
        </td>
        <td>
         <input class="wcmamtx_accordion_input" type="text" name="wcmamtx_advanced_settings[<?php echo $key; ?>][link_inputtarget]" value="<?php if (isset($value['link_inputtarget']) && ($value['link_inputtarget'] != '')) { echo ($value['link_inputtarget']); } else { echo '#';} ?>" size="70">
     </td>

 </tr>

 <tr>
    <td>
     <label class="wcmamtx_accordion_label"><?php  echo esc_html__('Open in new tab','customize-my-account-for-woocommerce'); ?></label>
 </td>
 <td>    
    <input data-toggle="toggle" data-on="<?php  echo esc_html__('Yes','customize-my-account-for-woocommerce'); ?>" data-off="<?php  echo esc_html__('No','customize-my-account-for-woocommerce'); ?>" data-size="sm" class="wcmamtx_accordion_input wcmamtx_accordion_checkbox checkmark" type="checkbox" name="wcmamtx_advanced_settings[<?php echo $key; ?>][link_targetblank]" value="01" <?php if (isset($value['link_targetblank']) && ($value['link_targetblank'] == "01")) { echo 'checked'; } ?>>
</td>
</tr>

<?php } ?>


<tr>
    <td>
        <label class=" wcmamtx_accordion_label"><?php echo esc_html__('Hide in Sidebar Navigation','customize-my-account-for-woocommerce'); ?></label>
    </td>
    <td>
        <?php wcmamtx_show_disabled_toggle_image(); ?>

    </td>
</tr>



<tr>
    <td>
        <label class=" wcmamtx_accordion_label"><?php echo esc_html__('Hide in My Account Menu widget','customize-my-account-for-woocommerce'); ?></label>
    </td>
    <td>
        <?php wcmamtx_show_disabled_toggle_image(); ?>

    </td>
</tr>

<?php if ($key == "dashboard") { ?>





    <tr>
        <td>
            <label class="wcmamtx_accordion_label wcmamtx_custom_content_label"><?php  echo esc_html__('Custom Content before dashboard','customize-my-account-for-woocommerce'); ?></label>
        </td>
        <td>    

            <?php 
            $editor_content = isset($value['content_dash']) ? $value['content_dash'] : "";



            $editor_id      = 'wcmamtx_content_'.$key.'';
            $editor_name    = ''.$this->wcmamtx_notices_settings_page.'['.$key.'][content_dash]';

            wp_editor( $editor_content, $editor_id, $settings = array(
                                'textarea_name' => $editor_name,
                                'editor_height' => 180, // In pixels, takes precedence and has no default value
                                'textarea_rows' => 16,
                                'wpautop'       => true,
                            ) ); 
                            ?>
                            <a target="_blank" class="wcmamtx_accordion_label_small" href="https://www.sysbasics.com/knowledge-base/list-of-content-variables/" class=""><?php  echo esc_html__('Supported Variables','customize-my-account-for-woocommerce'); ?>
                            </a> <a target="_blank" class="wcmamtx_accordion_label_small float_right" href="https://www.sysbasics.com/knowledge-base/create-your-own-custom-content-variable/" class=""><?php  echo esc_html__('Create your own custom variable','customize-my-account-for-woocommerce'); ?>
                        </a>
                    </td>
                </tr>

            <?php } ?>


           <tr>
                    <td>
                        <label class="wcmamtxvisibleto wcmamtx_accordion_label"><?php echo esc_html__('Visible to','customize-my-account-for-woocommerce'); ?></label>
                    </td>
                    <td>
                        <select mkey="<?php echo $key; ?>" class="wcmamtxvisibleto" name="wcmamtx_advanced_settings[<?php echo $key; ?>][visibleto]">
                            <option value="all" <?php if ((isset($value['visibleto'])) && ($value['visibleto'] == "all")) { echo "selected"; } ?>><?php echo esc_html__('All roles','customize-my-account-for-woocommerce'); ?></option>
                            
                            <option value="specific_exclude" <?php if ((isset($value['visibleto'])) && ($value['visibleto'] == "specific_exclude")) { echo "selected"; } ?>><?php echo esc_html__('All roles except specified','customize-my-account-for-woocommerce'); ?></option>
                            <option value="specific" <?php if ((isset($value['visibleto'])) && ($value['visibleto'] == "specific")) { echo "selected"; } ?>><?php echo esc_html__('Only specified roles','customize-my-account-for-woocommerce'); ?></option>

                            
                        </select>
               
                    </td>
                </tr>

                <?php 

                if (!empty($value['roles'])) { 
                    $chosenrolls = implode(',', $value['roles']); 
                } else { 
                    $chosenrolls=''; 
                } 

                ?>
              
                <tr style="<?php if ((isset($value['visibleto'])) && (($value['visibleto'] == "specific") || ($value['visibleto'] == "specific_exclude"))) { echo "display:table-row;"; } else { echo "display:none;"; } ?>" class="wcmamtxroles_<?php echo $key; ?>">
                    <td>
                        <label class="wcmamtx_roles wcmamtx_accordion_label"><?php echo esc_html__('Select roles','customize-my-account-for-woocommerce'); ?></label>
                    </td>
                    <td>
                        <select data-placeholder="<?php echo esc_html__('Choose Roles','customize-my-account-for-woocommerce'); ?>" name="wcmamtx_advanced_settings[<?php echo $key; ?>][roles][]" class="wcmamtx_roleselect" multiple>
                            <?php foreach ($roles as $rkey => $role) { ?>
                                <option value="<?php echo $rkey; ?>" <?php if (preg_match('/\b'.$rkey.'\b/', $chosenrolls )) { echo 'selected';}?>><?php echo $role['name']; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>

                <?php 

                if (!empty($value['users'])) { 
                    $chosenusers = $value['users']; 
                } else { 
                    $chosenusers= array(); 
                } 

                

                ?>
              



            <?php if (($wcmamtx_type == 'endpoint') && (!preg_match('/\b'.$key.'\b/', $exclude_content_core_fields )) && (!isset($third_party))) { ?>

               <tr>
                <td>
                    <label class="wcmamtx_accordion_label wcmamtx_custom_content_label"><?php  echo esc_html__('Custom Content','customize-my-account-for-woocommerce'); ?></label>
                </td>
                <td>    

                    <?php 
                    $editor_content = isset($value['content']) ? $value['content'] : "";



                    $editor_id      = 'wcmamtx_content_'.$key.'';
                    $editor_name    = ''.$this->wcmamtx_notices_settings_page.'['.$key.'][content]';

                    wp_editor( $editor_content, $editor_id, $settings = array(
                     'textarea_name' => $editor_name,
                            	'editor_height' => 180, // In pixels, takes precedence and has no default value
                                'textarea_rows' => 16,
                                'wpautop'       => true,
                            ) ); 
                            ?>

                            <a target="_blank" class="wcmamtx_accordion_label_small" href="https://www.sysbasics.com/knowledge-base/list-of-content-variables/" class=""><?php  echo esc_html__('Supported Variables','customize-my-account-for-woocommerce'); ?>
                            </a> <a target="_blank" class="wcmamtx_accordion_label_small float_right" href="https://www.sysbasics.com/knowledge-base/create-your-own-custom-content-variable/" class=""><?php  echo esc_html__('Create your own custom variable','customize-my-account-for-woocommerce'); ?>
                        </a>
                    </td>
                </tr>

            <?php } ?>


            <?php if (($wcmamtx_type == 'endpoint') && (preg_match('/\b'.$key.'\b/', $extra_content_core_fields ))) { ?>

               <tr>
                  <td>
                     <label class="wcmamtx_accordion_label"><?php  echo esc_html__('Content Settings','customize-my-account-for-woocommerce'); ?></label>
                 </td>
                 <td>
                     <?php 
                     if (isset($value['content_settings']) && ($value['content_settings'] != '')) {
                        $content_settings = $value['content_settings'];
                    } else {
                        $content_settings = 'after';
                    }
                    ?>

                    <div class="wcmamtx_content_settings_div">
                        <div class="form-check wcmamtx_content_checkbox">
                           <input class="form-check-input wcmamtx_content_source_radio" type="radio" name="<?php  echo $this->wcmamtx_notices_settings_page; ?>[<?php echo $key; ?>][content_settings]"  value="after" <?php if ($content_settings == "after") { echo 'checked'; } ?>>
                           <label class="form-check-label wcmamtx_icon_checkbox_label" >
                              <?php  echo esc_html__('After Existing Content','customize-my-account-for-woocommerce'); ?>
                          </label>
                      </div>
                      <div class="form-check wcmamtx_content_checkbox">
                       <input class="form-check-input wcmamtx_content_source_radio" type="radio" name="<?php  echo $this->wcmamtx_notices_settings_page; ?>[<?php echo $key; ?>][content_settings]"  value="before" <?php if ($content_settings == "before") { echo 'checked'; } ?>>
                       <label class="form-check-label wcmamtx_icon_checkbox_label">
                          <?php  echo esc_html__('Before Existing Content','customize-my-account-for-woocommerce'); ?>
                      </label>
                  </div>
              </div>
          </td>

      </tr>

  <?php } ?>


  <?php if ($wcmamtx_type == 'group') { ?>

   <tr>
      <td>
         <label class="wcmamtx_accordion_label"><?php  echo esc_html__('Open by default','customize-my-account-for-woocommerce'); ?></label>
     </td>
     <td>    
         <input class="wcmamtx_accordion_input wcmamtx_accordion_checkbox form-check-input" type="checkbox" name="wcmamtx_advanced_settings[<?php echo $key; ?>][group_open_default]" <?php if (isset($value['group_open_default']) && ($value['group_open_default'] == "01")) { echo 'checked'; } ?> value="01">
     </td>
 </tr>

<?php } ?>

<tr>
    <td>
        <label class="wcmamtx_accordion_label"><?php  echo esc_html__('Extra Classes','customize-my-account-for-woocommerce'); ?></label>
    </td>
    <td>    
        <input type="text" class="wcmamtx_accordion_input wcmamtx_class_input" name="<?php  echo $this->wcmamtx_notices_settings_page; ?>[<?php echo $key; ?>][class]" value="<?php if (isset($value['class'])) { echo $value['class']; } ?>">
    </td>
</tr>




<?php if ($key == "edit-account") { ?>

    <tr>
        <td>
            <label class="wcmamtx_accordion_label"><?php  echo esc_html__('Edit Account fields','customize-my-account-for-woocommerce'); ?></label>
        </td>
        <td>    

            <a class="btn btn-success" style="color:white;" target="_blank" href="https://www.sysbasics.com/product/woocommerce-my-account-fields/">
                <?php  echo esc_html__('Manage Edit Account Fields','customize-my-account-for-woocommerce'); ?>
            </a>
        </td>
    </tr>

<?php } ?>

<?php if ($wcmamtx_type != 'group') { ?>

<?php } ?>

<?php if (isset($third_party)) { ?>

 <tr>
    <td>

    </td>
    <td>

       <input type="hidden" name="<?php  echo $this->wcmamtx_notices_settings_page; ?>[<?php echo $key; ?>][third_party]" value="yes">

   </td>

</tr>

<?php } ?>    
</table>

</div>

<?php if (($wcmamtx_type == 'group') && ($value['parent'] == "none")) {

 $this->get_group_content($name,$key,$value);

} ?>


<?php 