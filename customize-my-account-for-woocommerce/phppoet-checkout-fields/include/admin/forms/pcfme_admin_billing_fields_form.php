<?php       
global $woocommerce;

$countries     = new WC_Countries();

$fields              = $countries->get_address_fields( $countries->get_base_country(),'billing_');

$billing_settings    = (array) get_option('pcfme_billing_settings');
$billing_settings    = array_filter($billing_settings);
$required_slugs      = '';
$core_fields         = 'billing_country,billing_first_name,billing_last_name,billing_company,billing_address_1,billing_address_2,billing_city,billing_state,billing_postcode,billing_email,billing_phone';
$country_fields      = 'billing_country,billing_state';
$address2_field      = 'billing_address_2';

$noticerowno = 1;

if (isset($billing_settings) && (sizeof($billing_settings) >= 1)) { 
	$conditional_fields_dropdown = $billing_settings;
} else {
	$conditional_fields_dropdown = $fields;
}


?>

<center>	   
	<div class="panel-group pcfme-sortable-list" id="accordion" >
		<?php 

		if (isset($billing_settings) && (sizeof($billing_settings) >= 1)) { 

			foreach ($billing_settings as $key =>$field) { 
				$this->show_fields_form($conditional_fields_dropdown,$key,$field,$noticerowno,$this->billing_settings_key,$required_slugs,$core_fields,$country_fields,$address2_field);
				$noticerowno++;
			} 
			
		} else {

			foreach ($fields as $key =>$field) { 
				$this->show_fields_form($conditional_fields_dropdown,$key,$field,$noticerowno,$this->billing_settings_key,$required_slugs,$core_fields,$country_fields,$address2_field);
				$noticerowno++;
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
		<button type="button" href="#" data-etype="billing" id="wcmamtx_add_field" class="btn btn-primary" >
			<span class="dashicons dashicons-insert"></span>
			<?php echo esc_html__('Add Billing Field','customize-my-account-pro'); ?>
		</button>

		
		

		<a type="button" target="_blank" href="<?php echo $checkout_url; ?>" id="pcfme_frontend_link" class="btn btn-primary pcfme_frontend_link">
			<span class="dashicons dashicons-welcome-view-site"></span>
		        <?php echo esc_html__('Frontend','customize-my-account-pro'); ?>
		</a>

		<button type="button" id="restore-billing-fields" class="btn btn-danger">
			<?php echo esc_html__('Restore Billing Fields','customize-my-account-pro'); ?>
		</button>

		<?php do_action( 'pcfme_add_author_links' ); ?>
			
	</div>

	</center> <?php
	
	$this->show_new_form($conditional_fields_dropdown,$this->billing_settings_key,$country_fields);
