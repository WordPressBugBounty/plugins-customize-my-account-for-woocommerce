<?php        
global $woocommerce;


$countries        = new WC_Countries();

$fields                 = $countries->get_address_fields( $countries->get_base_country(),'shipping_');
$shipping_settings      = (array) get_option('pcfme_shipping_settings');
$shipping_settings      = array_filter($shipping_settings);
$core_fields            = 'shipping_country,shipping_first_name,shipping_last_name,shipping_company,shipping_address_1,shipping_address_2,shipping_city,shipping_state,shipping_postcode';
$requiredshipping_slugs = '';
$country_fields         = 'shipping_country,shipping_state';	
$address2_field         = 'shipping_country,shipping_first_name,shipping_last_name,shipping_company,shipping_address_1,shipping_address_2,shipping_city,shipping_state,shipping_postcode';

$noticerowno2 = 1;

if (isset($shipping_settings) && (sizeof($shipping_settings) >= 1)) { 
	$conditional_fields_dropdown = $shipping_settings;
} else {
	$conditional_fields_dropdown = $fields;
}
?>


<center>
	<div class="panel-group pcfme-sortable-list" id="accordion" >
	<?php if (isset($shipping_settings) && (sizeof($shipping_settings) >= 1)) { 
		foreach ($shipping_settings as $key =>$field) { 
			$this->show_fields_form($conditional_fields_dropdown,$key,$field,$noticerowno2,$this->shipping_settings_key,$requiredshipping_slugs,$core_fields,$country_fields,$address2_field);
			$noticerowno2++;
		} 
	} else {

		foreach ($fields as $key =>$field) { 
			$this->show_fields_form($conditional_fields_dropdown,$key,$field,$noticerowno2,$this->shipping_settings_key,$requiredshipping_slugs,$core_fields,$country_fields,$address2_field);
			$noticerowno2++;
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
	<button type="button" href="#" data-etype="shipping" id="wcmamtx_add_field" class="btn btn-primary" >
			<span class="dashicons dashicons-insert"></span>
			<?php echo esc_html__('Add Shipping Field','customize-my-account-pro'); ?>
	</button>

	<a type="button" target="_blank" href="<?php echo $checkout_url; ?>" id="pcfme_frontend_link" class="btn btn-primary pcfme_frontend_link">
		<span class="dashicons dashicons-welcome-view-site"></span>
		<?php echo esc_html__('Frontend','customize-my-account-pro'); ?>
	</a>

	<button type="button" id="restore-shipping-fields" class="btn btn-danger">
		<?php echo esc_html__('Restore Shipping Fields','customize-my-account-pro'); ?>
	</button>

	<?php do_action( 'pcfme_add_author_links' ); ?>
</div>

</center> <?php

$this->show_new_form($conditional_fields_dropdown,$this->shipping_settings_key,$country_fields);
