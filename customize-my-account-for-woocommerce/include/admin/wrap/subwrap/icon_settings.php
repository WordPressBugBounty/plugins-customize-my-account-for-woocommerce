<tr>
    <td>
        <label class="wcmamtx_accordion_label"><?php  echo esc_html__('Icon Settings','customize-my-account-for-woocommerce'); ?></label>
    </td>
    <td>
       <?php 
       if (isset($value['icon_source']) && ($value['icon_source'] != '')) {
          $icon_source = $value['icon_source'];
      } else {
          $icon_source = 'default';
      }
      ?>

      <div class="wcmamtx_icon_settings_div">
          <div class="form-check wcmamtx_icon_checkbox">
             <input class="form-check-input wcmamtx_icon_source_radio" type="radio" name="<?php  echo $this->wcmamtx_notices_settings_page; ?>[<?php echo $key; ?>][icon_source]"  value="default" <?php if ($icon_source == "default") { echo 'checked'; } ?>>
             <label class="form-check-label wcmamtx_icon_checkbox_label" >
                <?php  echo esc_html__('Default Icon','customize-my-account-for-woocommerce'); ?>
            </label>
        </div>
        <div class="form-check wcmamtx_icon_checkbox">
         <input class="form-check-input wcmamtx_icon_source_radio" type="radio" name="<?php  echo $this->wcmamtx_notices_settings_page; ?>[<?php echo $key; ?>][icon_source]"  value="noicon" <?php if ($icon_source == "noicon") { echo 'checked'; } ?> disabled>
         <label class="form-check-label wcmamtx_icon_checkbox_label">
            <?php  echo esc_html__('No Icon','customize-my-account-for-woocommerce'); ?>
            <a href="#" data-toggle="modal" data-target="#wcmamtx_upgrade_modal">
                <span class="wcmamtx_pro_upgrade_text"><?php  echo esc_html__('Upgrade to pro','customize-my-account-for-woocommerce'); ?></span>
                
            </a>
        </label>
    </div>
    <div class="form-check wcmamtx_icon_checkbox">
     <input class="form-check-input wcmamtx_icon_source_radio" type="radio" name="<?php  echo $this->wcmamtx_notices_settings_page; ?>[<?php echo $key; ?>][icon_source]"  value="custom" <?php if ($icon_source == "custom") { echo 'checked'; } ?> disabled>
     <label class="form-check-label wcmamtx_icon_checkbox_label">
        <?php  echo esc_html__('Font Awesome Icon','customize-my-account-for-woocommerce'); ?>
        <a href="#" data-toggle="modal" data-target="#wcmamtx_upgrade_modal">
            <span class="wcmamtx_pro_upgrade_text"><?php  echo esc_html__('Upgrade to pro','customize-my-account-for-woocommerce'); ?></span>

        </a>
        
    </label>
</div>

<div class="form-check wcmamtx_icon_checkbox">
 <input class="form-check-input wcmamtx_icon_source_radio" type="radio" name="<?php  echo $this->wcmamtx_notices_settings_page; ?>[<?php echo $key; ?>][icon_source]"  value="default" <?php if ($icon_source == "dashicon") { echo 'checked'; } ?> disabled>
 <label class="form-check-label wcmamtx_icon_checkbox_label">
    <?php  echo esc_html__('Dashicon','customize-my-account-for-woocommerce'); ?>
    <a href="#" data-toggle="modal" data-target="#wcmamtx_upgrade_modal">
        <span class="wcmamtx_pro_upgrade_text"><?php  echo esc_html__('Upgrade to pro','customize-my-account-for-woocommerce'); ?></span>

    </a>
</label>
</div>

<div class="form-check wcmamtx_icon_checkbox">
    <input class="form-check-input wcmamtx_icon_source_radio" type="radio" name="<?php  echo $this->wcmamtx_notices_settings_page; ?>[<?php echo $key; ?>][icon_source]"  value="default" <?php if ($icon_source == "upload") { echo 'checked'; } ?> disabled>
    <label class="form-check-label wcmamtx_icon_checkbox_label">
        <?php  echo esc_html__('Upload Icon','customize-my-account-for-woocommerce'); ?>
        <a href="#" data-toggle="modal" data-target="#wcmamtx_upgrade_modal">
            <span class="wcmamtx_pro_upgrade_text"><?php  echo esc_html__('Upgrade to pro','customize-my-account-for-woocommerce'); ?></span>

        </a>
    </label>
</div>
</div>
</td>

</tr>