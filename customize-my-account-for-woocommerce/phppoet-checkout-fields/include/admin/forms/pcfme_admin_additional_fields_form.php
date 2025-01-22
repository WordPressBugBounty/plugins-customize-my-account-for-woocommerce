<?php        
global $woocommerce;


$countries    = new WC_Countries();



$additional_settings  = (array) get_option('pcfme_additional_settings');
$additional_settings  = array_filter($additional_settings);
$core_fields          = '';
$country_fields       = '';
$address2_field       = '';

$requiredadditional_slugs = '';

if (isset($additional_settings) && (sizeof($additional_settings) >= 1)) { 
	$conditional_fields_dropdown = $additional_settings;
} else {
	$conditional_fields_dropdown = array();
}

$noticerowno3 = 1;
?>
<center>
	<div class="panel-group pcfme-sortable-list" id="accordion" >
		<?php if (isset($additional_settings) && (sizeof($additional_settings) >= 1)) { 
			foreach ($additional_settings as $key =>$field) { 
				$this->show_fields_form($conditional_fields_dropdown,$key,$field,$noticerowno3,$this->additional_settings_key,$requiredadditional_slugs,$core_fields,$country_fields,$address2_field);
				$noticerowno3++;
			} 
		} 
		?>

	</div>
	<div class="buttondiv">
		<?php 
		global $woocommerce;
		$checkout_url = '#';
		$checkout_url = wc_get_checkout_url();
		?>	  
		<button type="button" href="#" data-etype="additional" id="wcmamtx_add_field" class="btn btn-primary" >
			<span class="dashicons dashicons-insert"></span>
			<?php echo esc_html__('Add Additional Field','customize-my-account-pro'); ?>
		</button>

		<a type="button" target="_blank" href="<?php echo $checkout_url; ?>" id="pcfme_frontend_link" class="btn btn-primary pcfme_frontend_link">
			<span class="dashicons dashicons-welcome-view-site"></span>
			<?php echo esc_html__('Frontend','customize-my-account-pro'); ?>
		</a>

		<?php do_action( 'pcfme_add_author_links' ); ?>
		
	</div>
	</center> <?php
	
	$this->show_new_form($conditional_fields_dropdown,$this->additional_settings_key,$country_fields);