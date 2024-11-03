<?php

class pcfme_manage_extrafield_class {

     public function __construct() {
		add_filter( 'woocommerce_form_field_text', array( $this, 'pcfmetext_form_field' ), 10, 4 );
		add_filter( 'woocommerce_form_field_hidden_field', array( $this, 'pcfmehidden_field_form_field' ), 10, 4 );
		add_filter( 'woocommerce_form_field_heading', array( $this, 'pcfmeheading_form_field' ), 10, 4 );
		add_filter( 'woocommerce_form_field_password', array( $this, 'pcfmetext_form_field' ), 10, 4 );
		add_filter( 'woocommerce_form_field_email', array( $this, 'pcfmetext_form_field' ), 10, 4 );
		add_filter( 'woocommerce_form_field_number', array( $this, 'pcfmetext_form_field' ), 10, 4 );
		add_filter( 'woocommerce_form_field_textarea', array( $this, 'pcfmetextarea_form_field' ), 10, 4 );
		add_filter( 'woocommerce_form_field_checkbox', array( $this, 'pcfmecheckbox_form_field' ), 10, 4 );
		add_filter( 'woocommerce_form_field_radio', array( $this, 'radio_form_field' ), 10, 4 );
     	add_filter( 'woocommerce_form_field_pcfmeselect', array( $this, 'pcfmeselect_form_field' ), 10, 4 );
	    add_filter( 'woocommerce_form_field_datepicker', array( $this, 'datepicker_form_field' ), 10, 4 );
	    add_filter( 'woocommerce_form_field_datetimepicker', array( $this, 'datetimepicker_form_field' ), 10, 4 );
	    add_filter( 'woocommerce_form_field_timepicker', array( $this, 'timepicker_form_field' ), 10, 4 );
	    add_filter( 'woocommerce_form_field_daterangepicker', array( $this, 'daterangepicker_form_field' ), 10, 4 );
	    add_filter( 'woocommerce_form_field_datetimerangepicker', array( $this, 'datetimerangepicker_form_field' ), 10, 4 );
		add_filter( 'woocommerce_form_field_multiselect', array( $this, 'multiselect_form_field' ), 10, 4 );
		add_filter( 'woocommerce_form_field_paragraph', array( $this, 'paragraph_form_field' ), 10, 4 );
		
		add_filter( 'wp_enqueue_scripts', array( $this, 'add_checkout_frountend_scripts' ));

		

        add_action( 'woocommerce_locate_template', array($this,'pcfme_core_override_default_navigation_template'), 100, 3 );

        add_action( 'woocommerce_save_account_details', array($this,'pcfme_save_profile_fields'), 12, 1 );

        add_filter('woocommerce_save_account_details_required_fields', array($this,'wc_save_account_details_required_fields') );

        add_action( 'woocommerce_account_navigation', array($this,'wcmamtx_display_dash_notice') );

        add_filter( 'manage_users_columns',  array($this,'wcmamtx_new_user_ui_columns') );
        
        add_filter( 'manage_users_custom_column', array($this,'wcmamtx_new_user_ui_columns_process_data'), 10, 3 );

        add_action('woocommerce_register_form',array($this,'wcmamtx_woocommerce_register_form_function'));

        add_action( 'woocommerce_created_customer', array($this,'pcfme_save_registration_fields') );

    }


    public function pcfme_save_registration_fields( $customer_id ) {
          
	      
           

		   $additional_fields              = (array) get_option('pcfme_additional_settings');
		   
            unset($all_fields[0]);
		   
		   foreach ($additional_fields as $additionalkey=>$additional_field) {
		   	    if ( isset( $_POST[$additionalkey] ) )
		   		update_user_meta( $customer_id, $additionalkey, sanitize_text_field( $_POST[$additionalkey] ) );
		   }
	      	
	      
    }



    public function wcmamtx_woocommerce_register_form_function() {

    	

    	$all_fields    = (array) get_option('pcfme_additional_settings');

    	unset($all_fields[0]);

    	if (isset($all_fields) && (sizeof($all_fields) >= 1)) { 


    		foreach ( $all_fields as $key => $field ) {

    			$enable_register = (isset($field['show_register']) && ($field['show_register'] == 1)) ? "yes" : "no";


    			if ($enable_register == "yes") {

    				$field_key = isset($field['field_key']) ? $field['field_key'] : $key;

    				$default_value = get_user_meta( get_current_user_id(), $field_key, true );


    				if (isset($field['type']) &&  ($field['type'] == "pcpcfmeselect")) {

    					$field_html = '';

    					woocommerce_form_field( $field_html, $key, $field, $default_value );

    				} else {

    					woocommerce_form_field( $key, $field, $default_value );

    				}

    			}

    		}
    	}


    }


    public function wcmamtx_new_user_ui_columns($column) {
    	$all_fields        = (array) get_option('pcfme_additional_settings');

	 	foreach ( $all_fields as $mkey => $mvalue ) {

	 		$show_admin_ui_column = isset($mvalue['show_adminui']) && ($mvalue['show_adminui'] == 1) ? "yes" : "no";
            

            if ($show_admin_ui_column == "yes") {
            	$column[$mkey] = $mvalue['label'];
            }
            
	 	}


    	return $column;
    }

    public function wcmamtx_new_user_ui_columns_process_data( $val, $column_name, $user_id ) {

    	$all_fields        = (array) get_option('pcfme_additional_settings');

    	foreach ( $all_fields as $mkey => $mvalue ) {

    		$show_admin_ui_column = isset($mvalue['show_adminui']) && ($mvalue['show_adminui'] == 1) ? "yes" : "no";


    		if ($show_admin_ui_column == "yes") {
    			switch ($column_name) {
    				case $mkey :

    				$field_key = isset($mvalue['field_key']) ? $mvalue['field_key'] : $mkey;
    				return get_the_author_meta( $field_key, $user_id );
    				default:
    			}
    		}

    	}


    	
    	return $val;
    }


	 /**
      * Function for `woocommerce_account_navigation` action-hook.
      * 
      * @return void
      */
	 public function wcmamtx_display_dash_notice() {

	 	$all_fields        = (array) get_option('pcfme_additional_settings');

	 	foreach ( $all_fields as $mkey => $mvalue ) {

	 		

	 		$show_dash_field = isset($mvalue['dashboard_notice']) && ($mvalue['dashboard_notice'] == 1) ? "yes" : "no";


	 		$field_key = isset($mvalue['field_key']) ? $mvalue['field_key'] : $mkey;

			$default_value = get_user_meta( get_current_user_id(), $field_key, true );

			if ((!isset($default_value) || ($default_value == "")) && ($show_dash_field == "yes")) {


				$edit_account_url = wc_customer_edit_account_url();

				$default_dash_notice = ''.__( 'Kindly Enter required details <a href="'.$edit_account_url.'">'.$mvalue['label'].'</a>', 'customize-my-account-for-woocommerce' ).'';

				if (isset($mvalue['dash_notice_text']) && ($mvalue['dash_notice_text'] != "")) { 
					$ds_text_default2 = $mvalue['dash_notice_text']; 
				} else {
					$ds_text_default2 =  $default_dash_notice;
				}

				$ds_text_default2 = str_replace("{edit_account_link}",$edit_account_url,$ds_text_default2);

				echo '<div class="woocommerce-info">'.$ds_text_default2.'</div>';

			}


	 		

	 	}

	    
	 }

	 public function wc_save_account_details_required_fields($required_fields) {

	 	$extra_settings    = (array) get_option('pcfme_extra_settings');


	 	$all_fields        = (array) get_option('pcfme_additional_settings');




	 	foreach ( $all_fields as $key => $field ) {


	 		$field_key = isset($field['field_key']) ? $field['field_key'] : $key;

	 		$is_required = isset($field['required']) && ($field['required'] == 1) ? "yes" : "no";
            
            if (($is_required == "yes") && (!isset($_POST[$key]) || ($_POST[$key] == ""))) {
            	/* translators: %s: Field name. */
				wc_add_notice( sprintf( __( '%s is a required field.', 'customize-my-account-for-woocommerce' ), $field['label'] ), 'error', array( 'id' => $key ) );
            }

	 	}


	 	$allow_display_name = isset($extra_settings['display_name']) && ($extra_settings['display_name'] == 1) ? "no" : "yes";

	 	if ($allow_display_name == "no") {

	 		unset( $required_fields['account_display_name'] );

	 	}


	 	$allow_email_field = isset($extra_settings['disable_email']) && ($extra_settings['disable_email'] == 1) ? "no" : "yes";

		if ($allow_email_field == "no") { 

		    unset( $required_fields['account_email'] ); 

		}

		$allow_first_name = isset($extra_settings['disable_firstname']) && ($extra_settings['disable_firstname'] == 1) ? "no" : "yes";

	    if ($allow_first_name == "no") {

	    	unset( $required_fields['account_first_name'] ); 

	    }

	    $allow_last_name = isset($extra_settings['disable_lastname']) && ($extra_settings['disable_lastname'] == 1) ? "no" : "yes";

	    if ($allow_last_name == "no") {

	    	unset( $required_fields['account_last_name'] ); 

	    }

	 	return $required_fields;

	 }


     public function pcfme_save_profile_fields( $user_id ) {
          
	      if ( !current_user_can( 'edit_user', $user_id ) )
		   return false;
           

		   $additional_fields              = (array) get_option('pcfme_additional_settings');
		   

		   
		   foreach ($additional_fields as $additionalkey=>$additional_field) {
		   	    if ( isset( $_POST[$additionalkey] ) )
		   		update_user_meta( $user_id, $additionalkey, sanitize_text_field( $_POST[$additionalkey] ) );
		   }
	      	
	      
    }


	public function pcfme_core_override_default_navigation_template($template,$template_name,$template_path) {




        if ( strstr($template, 'form-edit-account.php') ) {
            $template = wcmamtx_plugin_path() . '/phppoet-checkout-fields//include/templates/form-edit-account.php';
        }


        
        return $template;

    }






	 public function add_cart_fees($cart) {

	 	if ( ! $_POST || ( is_admin() && ! is_ajax() ) ) {
            return;
        }


        $additional_fees    = get_option('pcfme_additional_fees');
        

        if (is_array($additional_fees)) {
        	$additional_fees    = array_filter($additional_fees);
        }


        if (isset($additional_fees) && is_array($additional_fees) && (sizeof($additional_fees) >= 1)) { 
        	$additional_fees = $additional_fees;
        } else {
        	$additional_fees = array();
        }


        if ( isset( $_POST['post_data'] ) ) {
        	parse_str( $_POST['post_data'], $post_data );
        } else {
           $post_data = $_POST; // fallback for final checkout (non-ajax)
        }

        


        foreach ($additional_fees as $fkey=>$fvalue) {
            
            $fees_field = $fvalue['parentfield'];

            $field_data = pcfme_get_field_data($fees_field);
            
            $field_type  = isset($field_data['type']) ? $field_data['type'] : "text";
            $field_label = isset($field_data['label']) ? $field_data['label'] : "";

   

            $visibilityarray = isset($field_data['visibility']) ? $field_data['visibility'] : array();

            if (isset($field_data['products'])) { 
            	$allowedproducts = $field_data['products'];
            } else {
            	$allowedproducts = array(); 
            }

            if (isset($field_data['category'])) {
            	$allowedcats = $field_data['category'];
            } else {
            	$allowedcats = array();
            }

            if (isset($field_data['role'])) {
            	$allowedroles = $field_data['role'];
            } else {
            	$allowedroles = array();
            }

            if (isset($field_data['total-quantity'])) {
            	$total_quantity = $field_data['total-quantity'];
            } else {
            	$total_quantity = 0;
            }

            if (isset($field_data['specific-product'])) {
            	$prd = $field_data['specific-product'];
            } else {
            	$prd = 0;
            }

            if (isset($field_data['specific-quantity'])) {
            	$prd_qnty = $field_data['specific-quantity'];
            } else {
            	$prd_qnty = 0;
            }


            if (isset($field_data['dynamic_rules'])) { 
            	$dynamic_rules = $field_data['dynamic_rules'];
            } else {
            	$dynamic_rules = array(); 
            }

            if (isset($field_data['dynamic_visibility_criteria'])) { 
            	$dynamic_criteria = $field_data['dynamic_visibility_criteria'];
            } else {
            	$dynamic_criteria = 'match_all'; 
            }



            $is_field_hidden=pcfme_check_if_field_is_hidden($visibilityarray,$allowedproducts,$allowedcats,$allowedroles,$total_quantity,$prd,$prd_qnty,$dynamic_rules,$dynamic_criteria);

            if ((isset($is_field_hidden)) && ($is_field_hidden == 0)) {

				return;

			}

            if (isset($fvalue['custom_label']) && ($fvalue['custom_label'] != '')) {
            	$field_label = $fvalue['custom_label'];
            }


            $rule_type = isset($fvalue['rule_type']) ? $fvalue['rule_type'] : 01;
            

            switch($rule_type) {

            	case 01:
            	   $this->execute_rule_01_fees($fvalue,$field_type,$post_data,$fees_field,$field_label);
            	break;

            	case 02:
            	   $this->execute_rule_02_fees($fvalue,$field_type,$post_data,$fees_field,$field_label);
            	break;

            	case 03:
            	   $this->execute_rule_03_fees($fvalue,$field_type,$post_data,$fees_field,$field_label);
            	break;

            }


            


            
        
        }

	}

	public function execute_rule_02_fees($fvalue,$field_type,$post_data,$fees_field,$field_label) {

		$add_type    = isset($fvalue['add_deduct_type']) ? $fvalue['add_deduct_type'] : "add";

		$chosen_product = isset($fvalue['specific-product']) ? $fvalue['specific-product'] : "";

		if ($chosen_product == "") {
			return;
		}

		$_product = wc_get_product( $chosen_product );

		$fvalue['amount'] = $_product->get_price();

		if ($field_type == "checkbox" ) {

			if (isset($post_data[$fees_field]) && ($post_data[$fees_field] == "yes")) {
				switch($add_type) {
					case "add":
					$extracost = $fvalue['amount'];
					break;

					case "deduct":
					$extracost = 0 - $fvalue['amount'];
					break;


				}
				$this->apply_cart_fees_final($field_label, $extracost);
			}

		} else if ($field_type == "multiselect" ) {



			if (isset($post_data[$fees_field]) && (in_array($fvalue['equalto'], $post_data[$fees_field]))) {
				switch($add_type) {
					case "add":
					$extracost = $fvalue['amount'];
					break;

					case "deduct":
					$extracost = 0 - $fvalue['amount'];
					break;


				}
				$this->apply_cart_fees_final($field_label, $extracost);
			}

		} else  {

			if (isset($post_data[$fees_field]) && ($post_data[$fees_field] == $fvalue['equalto'])) {

				switch($add_type) {
					case "add":
					$extracost = $fvalue['amount'];
					break;

					
                    case "deduct":
					$extracost = 0 - $fvalue['amount'];
					break;

				}



				$this->apply_cart_fees_final($field_label, $extracost);
			}

		}

	}

	public function execute_rule_01_fees($fvalue,$field_type,$post_data,$fees_field,$field_label) {

		$add_type    = isset($fvalue['type']) ? $fvalue['type'] : "fixed";

		if ($field_type == "checkbox" ) {

			if (isset($post_data[$fees_field]) && ($post_data[$fees_field] == "yes")) {
				switch($add_type) {
					case "fixed":
					$extracost = $fvalue['amount'];
					break;

					case "percentage":
					$cart_subtotal = WC()->cart->get_subtotal();
					$extracost     = ($cart_subtotal * $fvalue['amount']) /100;
					break;


				}
				$this->apply_cart_fees_final($field_label, $extracost);
			}

		} else if ($field_type == "multiselect" ) {



			if (isset($post_data[$fees_field]) && (in_array($fvalue['equalto'], $post_data[$fees_field]))) {
				switch($add_type) {
					case "fixed":
					$extracost = $fvalue['amount'];
					break;

					case "percentage":
					$cart_subtotal = WC()->cart->get_subtotal();
					$extracost     = ($cart_subtotal * $fvalue['amount']) /100;
					break;


				}
				$this->apply_cart_fees_final($field_label, $extracost);
			}

		} else  {

			if (isset($post_data[$fees_field]) && ($post_data[$fees_field] == $fvalue['equalto'])) {

				switch($add_type) {
					case "fixed":
					$extracost = $fvalue['amount'];
					break;

					case "percentage":
					$cart_subtotal = WC()->cart->get_subtotal();
					$extracost     = ($cart_subtotal * $fvalue['amount']) /100;
					break;


				}



				$this->apply_cart_fees_final($field_label, $extracost);
			}

		}

	}

	public function execute_rule_03_fees($fvalue,$field_type,$post_data,$fees_field,$field_label) {

		$add_type    = isset($fvalue['type']) ? $fvalue['type'] : "fixed";

		$cart_count = WC()->cart->get_cart_contents_count();

		if ($field_type == "checkbox" ) {

			if (isset($post_data[$fees_field]) && ($post_data[$fees_field] == "yes")) {
				switch($add_type) {
					case "fixed":
					$extracost = $cart_count * $fvalue['amount'];
					break;

					
               

				}
				$this->apply_cart_fees_final($field_label, $extracost);
			}

		} else if ($field_type == "multiselect" ) {



			if (isset($post_data[$fees_field]) && (in_array($fvalue['equalto'], $post_data[$fees_field]))) {
				switch($add_type) {
					case "fixed":
					$extracost = $cart_count * $fvalue['amount'];
					break;

					


				}
				$this->apply_cart_fees_final($field_label, $extracost);
			}

		} else  {

			if (isset($post_data[$fees_field]) && ($post_data[$fees_field] == $fvalue['equalto'])) {

				switch($add_type) {
					case "fixed":
					$extracost = $cart_count * $fvalue['amount'];
					break;

					


				}



				$this->apply_cart_fees_final($field_label, $extracost);
			}

		}

	}

    /**
     * Since version 2.7.5
     * Adds cart fees 
     * $field_label - field label
     * $extra_cost  - fees to be added
     */
	public function apply_cart_fees_final($field_label, $extracost) {

		$pcfme_extra_settings = get_option('pcfme_extra_settings');

		$fees_taxable = isset($pcfme_extra_settings['fees_taxable']) ? $pcfme_extra_settings['fees_taxable'] : "yes";


		if (isset($fees_taxable) && ($fees_taxable == 'no')) {

			WC()->cart->add_fee( $field_label, $extracost );

		} else {

			WC()->cart->add_fee( $field_label, $extracost ,$taxable = true);

		}

	 	
	}



	 
	 public function add_checkout_frountend_scripts() {
	   global $post;

	    $pcfme_woo_version    = pcfme_get_woo_version_number();

	    $pcfme_checkout_version    = pcfme_get_checkout_field_varsion_number();
	    $pcfme_extra_settings = get_option('pcfme_extra_settings');

	    if (isset($pcfme_extra_settings['datepicker_format'])) {
	    	$datepicker_format = $pcfme_extra_settings['datepicker_format'];
	    } else {
	    	$datepicker_format = 01;
	    }


	    if (isset($pcfme_extra_settings['timepicker_interval']) && ($pcfme_extra_settings['timepicker_interval'] == 02)) {
	    	$timepicker_interval = 30;
	    } else {
	    	$timepicker_interval = 60;
	    }

	    if (isset($pcfme_extra_settings['timepicker_format'])) {
	    	$timepicker_format = $pcfme_extra_settings['timepicker_format'];
	    }

	    if (isset($pcfme_extra_settings['allowed_times']) && ($pcfme_extra_settings['allowed_times'] != '')) {
	    	$allowed_times = $pcfme_extra_settings['allowed_times'];
	 
	    } else {

	        $allowed_times = '';
	    }


	    if (!empty($pcfme_extra_settings['datepicker_disable_days'])) {
		    $days_to_exclude = implode(',', $pcfme_extra_settings['datepicker_disable_days']); 
	    } else { 
	        $days_to_exclude=''; 
	    }


	    $datetimepicker_lang = isset($pcfme_extra_settings['datetimepicker_lang']) ? $pcfme_extra_settings['datetimepicker_lang'] : "en";

	    $week_starts_on = isset($pcfme_extra_settings['week_starts_on']) ? $pcfme_extra_settings['week_starts_on'] : "sunday";

	    $dt_week_starts_on = isset($pcfme_extra_settings['dt_week_starts_on']) ? $pcfme_extra_settings['dt_week_starts_on'] : 0;

	    $separater_text = isset($pcfme_extra_settings['separater_text']) ? $pcfme_extra_settings['separater_text'] : esc_html__('to','customize-my-account-for-woocommerce');;
	    

	    if ( is_account_page() ) {

	     
		 
		 wp_enqueue_style( 'jquery-ui', ''.pcfme_PLUGIN_URL.'assets/css/jquery-ui.css' );

		 wp_enqueue_script( 'jquery.datetimepicker', ''.pcfme_PLUGIN_URL.'assets/js/jquery.datetimepicker.js',array('jquery') );
         
         wp_enqueue_script( 'moment', ''.pcfme_PLUGIN_URL.'assets/js/moment.js');
		 wp_enqueue_script( 'daterangepicker', ''.pcfme_PLUGIN_URL.'assets/js/daterangepicker.js',array('moment'));
		 

            wp_enqueue_script( 'pcfme-frontend2', ''.pcfme_PLUGIN_URL.'assets/js/frontend2.js',array(),$pcfme_checkout_version );
		 
         
        $pcfmefrontend_array = array( 
		    'datepicker_format'               => $datepicker_format,
		    'timepicker_interval'             => $timepicker_interval,
		    'allowed_times'                   => $allowed_times,
		    'days_to_exclude'                 => $days_to_exclude,
		    'datetimepicker_lang'             => $datetimepicker_lang,
		    'week_starts_on'                  => $week_starts_on,
		    'dt_week_starts_on'               => $dt_week_starts_on,
		    'chose_option_text'               => esc_html__( 'Choose Default Option', 'customize-my-account-for-woocommerce'  ),
		    'separater_text'                  => $separater_text
		);
         
         wp_localize_script( 'pcfme-frontend2', 'pcfmefrontend', $pcfmefrontend_array );




		 wp_enqueue_style( 'pcfme-frontend', ''.pcfme_PLUGIN_URL.'assets/css/frontend.css' );

		 wp_enqueue_style( 'jquery.datetimepicker', ''.pcfme_PLUGIN_URL.'assets/css/jquery.datetimepicker.css' );

		 wp_enqueue_style( 'daterangepicker', ''.pcfme_PLUGIN_URL.'assets/css/daterangepicker.css' );
		}
	 }
	 
	 
      
	 public function pcfmehidden_field_form_field( $field, $key, $args, $value ) {

	 	$key = isset($args['field_key']) ? $args['field_key'] : $key;




	 	$fees_class       = '';

	 	$fees_class       = pcfme_get_fees_class($key);


	 	$value = isset($args['hidden_default']) ? $args['hidden_default'] : "Yes";

	 	$after ='';


	 	$field = '
	 	<p class="form-row ' . implode( ' ', $args['class'] ) .' " id="' . $key . '_field"><input type="hidden" class="'.$fees_class.' input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'  '. pcfmeinput_conditional_class($key) .'" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '"   ' . $args['autocomplete'] . ' value="' . esc_attr( $value ) . '" />
	 	</p>' . $after;


	 	return $field;
	 }
      
	  public function pcfmetext_form_field( $field, $key, $args, $value ) {

	  	 $key = isset($args['field_key']) ? $args['field_key'] : $key;

         if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';
	  
	     if ( $args['required'] ) {
			  $args['class'][] = 'validate-required';
			  $required = ' <abbr class="required" title="' . esc_attr__( 'required', 'pcfme'  ) . '">*</abbr>';
		  } else {
			$required = '';
		  }
		     



		
		if ($value == "empty") {
			$value = "";
		}
        
        $after ='';
		

        $field = '<p class="form-row ' . implode( ' ', $args['class'] ) .' " id="' . $key . '_field">
            <label for="' . $key . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>
            <input type="' . esc_attr( $args['type'] ) . '" class=" input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'  '. pcfmeinput_conditional_class($key) .'" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" ' . $args['maxlength'] . ' ' . $args['autocomplete'] . ' value="' . esc_attr( $value ) . '" />
        </p>' . $after;
         

        return $field;
      }
	  
	  
	  public function pcfmeheading_form_field($field, $key, $args, $value) {

         if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';
	  
	     if ( $args['required'] ) {
			  $args['class'][] = 'validate-required';
			  $required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-for-woocommerce'  ) . '">*</abbr>';
		  } else {
			$required = '';
		  }
		 
		 
		 $after ='';
		 
	     

        $field = '<h3 class="form-row ' . implode( ' ', $args['class'] ) .' " id="' . $key . '_field">
		
            <span for="' . $key . '" class="pcfme_heading ' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</span>
			
        </h3>' . $after;
         

        return $field;
      }


    /**
     * Paragraph Field
     * params $field - 
     * params $key- unique key
     * $args- required,placeholder,label etc
     * $value- default value
     */


    public function paragraph_form_field( $field, $key, $args, $value) {

         if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';
	  
	     if ( $args['required'] ) {
			  $args['class'][] = 'validate-required';
			  $required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-for-woocommerce'  ) . '">*</abbr>';
		  } else {
			$required = '';
		  }
		 
		 
		if ($value == "empty") {
			$value = "";
		}
		
		$after ='';
	     

        $field = '<p class="form-row ' . implode( ' ', $args['class'] ) .' " id="' . $key . '_field">
		
            <span for="' . $key . '" class="pcfme_heading ' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</span>
			
        </p>' . $after;
         

        return $field;
    }
	  

	  
    public function pcfmetextarea_form_field($field,$key, $args, $value ) {

    	$key = isset($args['field_key']) ? $args['field_key'] : $key;

    	if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';
    	
    	if ( $args['required'] ) {
    		$args['class'][] = 'validate-required';
    		$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-for-woocommerce'  ) . '">*</abbr>';
    	} else {
    		$required = '';
    	}
    	
    	
        if ($value == "empty") {
			$value = "";
		}

    	
    	$charlimit        = isset($args['charlimit']) ? $args['charlimit'] : 200;
        
        $after ='';
    	
    	

    	$field = '<p class="form-row ' . implode( ' ', $args['class'] ) .' " id="' . $key . '_field">
    	<label for="' . $key . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>
    	<textarea maxlength="'.$charlimit.'" name="' . esc_attr( $key ) . '" class=" input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'  '. pcfmeinput_conditional_class($key) .'" id="' . $key . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" ' . $args['maxlength'] . ' ' . $args['autocomplete'] . ' ' . ( empty( $args['custom_attributes']['rows'] ) ? ' rows="2"' : '' ) . ( empty( $args['custom_attributes']['cols'] ) ? ' cols="5"' : '' ) . '>'. esc_textarea( $value  ) .'</textarea>
    	</p>' . $after;
    	

    	return $field;
    }
	  
	 public function pcfmecheckbox_form_field($field,$key, $args, $value) {




	 	 $key = isset($args['field_key']) ? $args['field_key'] : $key;

         if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';
	  
	     if ( $args['required'] ) {
			  $args['class'][] = 'validate-required';
			  $required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-for-woocommerce'  ) . '">*</abbr>';
		  } else {
			$required = '';
		  }

		 $pcfme_conditional_class = '';
		
		 


		

         $after ='';



	     
	     if (isset($value) && ($value == "yes") && ($value != "")) { 
		 	$checked_text = 'checked';
		 } else {
		 	$checked_text = '';
		 }
	     

         $field = '<p class="form-row ' . implode( ' ', $args['class'] ) .' " id="' . $key . '_field"><label class="checkbox ' . implode( ' ', $args['label_class'] ) .' ' . implode( ' ', $args['class'] ) .' ' . $pcfme_conditional_class .'" ><input type="' . esc_attr( $args['type'] ) . '" class=" input-checkbox ' . esc_attr( implode( ' ', $args['input_class'] ) ) .' ' . $pcfme_conditional_class .' '. pcfmeinput_conditional_class($key) .'" name="' . esc_attr( $key ) . '" id="' . $key . '" value="yes" '.$checked_text .' /> '
						 . $args['label'] . $required . '</label></p>' . $after;
         

        return $field;
      }
     
      public function radio_form_field($field, $key, $args, $value ) {
      
	    $key = isset($args['field_key']) ? $args['field_key'] : $key;
	  
        if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';
        
		if ( $args['required'] ) {
			$args['class'][] = 'validate-required';
			$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-for-woocommerce'  ) . '">*</abbr>';
		} else {
			$required = '';
		}


		


		$action_class       = '';

		$action_class       = pcfme_get_action_class($key);
		
        
		if ($value == "empty") {
			$value = "";
		}

		$after ='';
		

		 $options = '';

		if (! empty ($args['placeholder'])) {
		
		    $value    = $args['placeholder'];
	    }

	    if (! empty ($args['default_option'])) {
     		
     		$value    = $args['default_option'];
     	}

        if ( !empty( $args[ 'options' ] ) ) {
		  
	        foreach ( $args[ 'options' ] as $option_key => $option_text ) {
	       	
	       	  $option_key  = preg_replace('/\s+/', '_', $option_key);
	       	  $default_val = preg_replace('/\s+/', '_', $value);

	       	  if (isset($value) && ($default_val == $option_key)) {
	       	  	  $checked_text = 'checked';
	       	  } else {
	       	  	  $checked_text = $default_val;
	       	  }

			  $options .= '<input type="radio" name="' . $key . '" id="' . $key . '" value="' . $option_key . '" ' . checked( $value, $option_key, false ) . 'class=" '.$action_class.' select  '. pcfmeinput_conditional_class($key) .'" '.$checked_text.' '.$checked_text.'>  ' . $option_text . '<br>';
		    }
            
            
			$field = '<p class="pcfme-radio-select form-row ' . implode( ' ', $args[ 'class' ] ) . ' " id="' . $key . '_field">
			          <label for="' . $key . '" class="' . implode( ' ', $args[ 'label_class' ] ) . '">' . $args[ 'label' ] . $required . '</label>' . $options . '</p>' . $after;
        }



        return $field;
       
     }
      

public function pcfmeselect_form_field( $field, $key, $args, $value) {
        

  


     	$key = isset($args['field_key']) ? $args['field_key'] : $key;

     	if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';

     	if ( $args['required'] ) {
     		$args['class'][] = 'validate-required';
     		$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-for-woocommerce'  ) . '">*</abbr>';
     		$requiredtext = 'required';
     	} else {
     		$required = '';
     		$requiredtext = '';
     	}

     		  
     	$options = '';

     	$after ='';

     	
     	$options .= '<option value="">'.esc_html__('Choose an Option','customize-my-account-for-woocommerce').'</option>';

     	


     		

     	

     	
     	


     	if ( ! empty( $args['new_options'] ) ) {
     		foreach ( $args['new_options'] as $option_key => $option_text ) {

     			$option_key = preg_replace('/\s+/', '_', $option_text['value']);

     			$options .= '<option value="' . $option_text['value'] . '" '. selected( $value, $option_text['value'], false ) . '>' . $option_text['text'] .'</option>';
     		}

     		$field = '<p class="form-row ' . implode( ' ', $args['class'] ) .' " id="' . $key . '_field">
     		<label for="' . $key . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>
     		<select data-placeholder="'.$args['placeholder'].'" name="' . $key . '" id="' . $key . '" class="select pcfme-singleselect  '. pcfmeinput_conditional_class($key) .'" '.$requiredtext.'>
     		' . $options . '
     		</select>
     		</p>' . $after;
     	}

     	return $field;
     }


	 
	 public function multiselect_form_field( $field, $key, $args, $value) {
	 	$key = isset($args['field_key']) ? $args['field_key'] : $key;

	  if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';
	  
	    if ( $args['required'] ) {
			$args['class'][] = 'validate-required';
			$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-for-woocommerce'  ) . '">*</abbr>';
		} else {
			$required = '';
		}


        if ($value == "empty") {
			$value = "";
		}

		$after ='';
	
     
       
	    $optionsarray='';
	    
		if (isset($value) && is_array($value)) {
			   
			 foreach ($value as $optionvalue) {
			       $optionsarray.=''.$optionvalue.',';
			    } 
			  
			$optionsarray=substr_replace($optionsarray, "", -1);
			
	    }
		
		
	    

		
		
	    $options = '';

    if ( ! empty( $args['options'] ) ) {
        foreach ( $args['options'] as $option_key => $option_text ) {

        	$option_key = preg_replace('/\s+/', '_', $option_key);

			if (preg_match('/\b'.$option_key.'\b/', $optionsarray )) {
				$selectstatus = 'selected';
			} else {
				$selectstatus = '';
			}

            $options .= '<option value="' . $option_key . '" '. $selectstatus . '>' . $option_text .'</option>';
        }

        $field = '<p class="form-row ' . implode( ' ', $args['class'] ) .' " id="' . $key . '_field">
            <label for="' . $key . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>
            <select name="' . $key . '[]" id="' . $key . '" class="'.$fees_class.' select pcfme-multiselect  '. pcfmeinput_conditional_class($key) .'" multiple="multiple">
                ' . $options . '
            </select>
        </p>' . $after;
      }

       return $field;
	 }
	 
	 
	public function datepicker_form_field(  $field, $key, $args, $value) {
		$key = isset($args['field_key']) ? $args['field_key'] : $key;

	    if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';

		if ( $args['required'] ) {
			$args['class'][] = 'validate-required';
			$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-for-woocommerce'  ) . '">*</abbr>';
		} else {
			$required = '';
		}

		$after ='';
		
		 
		
		if (isset($args['disable_past'])) {
			$datepicker_class='pcfme-datepicker-disable-past';
		} else {
			$datepicker_class='pcfme-datepicker';
		}



		$defalt_val = '';

        $defalt_val = isset($value) ? $value : $defalt_val;

		$disable_specific_dates = '';


		if (isset($args['disable_specific_dates'])) {

            $disable_specific_dates          = isset($args['disable_specific_dates']) ? $args['disable_specific_dates'] : "";
		}




		

		$args['maxlength'] = ( $args['maxlength'] ) ? 'maxlength="' . absint( $args['maxlength'] ) . '"' : '';

		if ( ! empty( $args['validate'] ) )
			foreach( $args['validate'] as $validate )
				$args['class'][] = 'validate-' . $validate;

		$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .' " id="' . esc_attr( $key ) . '_field">';

		if ( $args['label'] )
			$field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label'] . $required . '</label>';

		$field .= '<input dates_to_disable="'.$disable_specific_dates.'" type="text" class=" '. $datepicker_class .' input-text  '. pcfmeinput_conditional_class($key) .'" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" placeholder="' . $args['placeholder'] . '" '.$args['maxlength'].' value="' . esc_attr( $defalt_val ) . '" />
			</p>' . $after;

		return $field;
	 }



	public function datetimepicker_form_field( $field, $key, $args, $value) {
		$key = isset($args['field_key']) ? $args['field_key'] : $key;

	    if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';

		if ( $args['required'] ) {
			$args['class'][] = 'validate-required';
			$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-for-woocommerce'  ) . '">*</abbr>';
		} else {
			$required = '';
		}


		$after ='';
		
		 
		
		if (isset($args['disable_past'])) {
			$datepicker_class='pcfme-datetimepicker-disable-past';
		} else {
			$datepicker_class='pcfme-datetimepicker';
		}

		if ($value == "empty") {
			$value = "";
		}


		$defalt_val = '';


		if (isset($args['enable_default_date'])) {

			$default_date         = isset($args['default_date_add']) ? $args['default_date_add'] : 0;

			$pcfme_extra_settings = (array) get_option('pcfme_extra_settings');

			$date_format          = isset($pcfme_extra_settings['datepicker_format']) ? $pcfme_extra_settings['datepicker_format'] : 01;

			$date_format          = pcfme_get_datepicker_format_from_option($date_format);

			$date = date("Y-m-d");

			$default_date = strtotime($date."+ $default_date days");

			$default_date = date($date_format,$default_date);

			$defalt_val .= ''.$default_date.''; 

		}

		if (isset($args['enable_default_time'])) {

			$defalt_time          = isset($args['default_time']) ? $args['default_time'] : "08:00";
      
            $defalt_val .= ' '.$defalt_time.''; 
		}


		if (isset($args['disable_specific_dates'])) {

            $disable_specific_dates          = isset($args['disable_specific_dates']) ? $args['disable_specific_dates'] : "";
		}


		if (isset($args['allowed_times'])) {

            $allowed_times          = isset($args['allowed_times']) ? $args['allowed_times'] : "";
		}





		


		$args['maxlength'] = ( $args['maxlength'] ) ? 'maxlength="' . absint( $args['maxlength'] ) . '"' : '';

		if ( ! empty( $args['validate'] ) )
			foreach( $args['validate'] as $validate )
				$args['class'][] = 'validate-' . $validate;

		$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .' " id="' . esc_attr( $key ) . '_field">';

		if ( $args['label'] )
			$field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label'] . $required . '</label>';

		$field .= '<input dates_to_disable="'.$disable_specific_dates.'" t_allowed="'.$allowed_times.'" type="text" class=" '. $datepicker_class .' input-text  '. pcfmeinput_conditional_class($key) .'" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" placeholder="' . $args['placeholder'] . '" '.$args['maxlength'].' value="'.$defalt_val.'" />
			</p>' . $after;

		return $field;
	 }


	public function daterangepicker_form_field(  $field, $key, $args, $value ) {

		$key = isset($args['field_key']) ? $args['field_key'] : $key;

	    if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';

		if ( $args['required'] ) {
			$args['class'][] = 'validate-required';
			$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-for-woocommerce'  ) . '">*</abbr>';
		} else {
			$required = '';
		}

		if ($value == "empty") {
			$value = "";
		}

		$after ='';
		
		 
		
		if (isset($args['disable_past'])) {
			$datepicker_class='pcfme-daterangepicker-disable-past';
		} else {
			$datepicker_class='pcfme-daterangepicker';
		}




		$args['maxlength'] = ( $args['maxlength'] ) ? 'maxlength="' . absint( $args['maxlength'] ) . '"' : '';

		if ( ! empty( $args['validate'] ) )
			foreach( $args['validate'] as $validate )
				$args['class'][] = 'validate-' . $validate;

		$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .' " id="' . esc_attr( $key ) . '_field">';

		if ( $args['label'] )
			$field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label'] . $required . '</label>';

		$field .= '<input type="text" class="'.$fees_class.' '. $datepicker_class .' input-text  '. pcfmeinput_conditional_class($key) .'" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" placeholder="' . $args['placeholder'] . '" '.$args['maxlength'].' value="" />
			</p>' . $after;

		return $field;
	}



	public function datetimerangepicker_form_field(  $field, $key, $args, $value ) {

		$key = isset($args['field_key']) ? $args['field_key'] : $key;

	    if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';

		if ( $args['required'] ) {
			$args['class'][] = 'validate-required';
			$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-for-woocommerce'  ) . '">*</abbr>';
		} else {
			$required = '';
		}

		if ($value == "empty") {
			$value = "";
		}

		$after ='';
		
		

		
		if (isset($args['disable_past'])) {
			$datepicker_class='pcfme-datetimerangepicker-disable-past';
		} else {
			$datepicker_class='pcfme-datetimerangepicker';
		}

		$args['maxlength'] = ( $args['maxlength'] ) ? 'maxlength="' . absint( $args['maxlength'] ) . '"' : '';

		if ( ! empty( $args['validate'] ) )
			foreach( $args['validate'] as $validate )
				$args['class'][] = 'validate-' . $validate;

		$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .' " id="' . esc_attr( $key ) . '_field">';

		if ( $args['label'] )
			$field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label'] . $required . '</label>';

		$field .= '<input type="text" class=" '. $datepicker_class .' input-text  '. pcfmeinput_conditional_class($key) .'" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" placeholder="' . $args['placeholder'] . '" '.$args['maxlength'].' value="" />
			</p>' . $after;

		return $field;
	}


	public function timepicker_form_field(  $field,$key, $args, $value) {
		$key = isset($args['field_key']) ? $args['field_key'] : $key;
		
	    if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';

		if ( $args['required'] ) {
			$args['class'][] = 'validate-required';
			$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-for-woocommerce'  ) . '">*</abbr>';
		} else {
			$required = '';
		}


		if ($value == "empty") {
			$value = "";
		}
		

		$after ='';
		 



		$defalt_val = '';

		if (isset($args['enable_default_time'])) {

			$defalt_val          = isset($args['default_time']) ? $args['default_time'] : "08:00";


		}


        if (isset($args['allowed_times'])) {

            $allowed_times          = isset($args['allowed_times']) ? $args['allowed_times'] : "";
		}
		
		
		$datepicker_class='pcfme-timepicker';
		

		$args['maxlength'] = ( $args['maxlength'] ) ? 'maxlength="' . absint( $args['maxlength'] ) . '"' : '';

		if ( ! empty( $args['validate'] ) )
			foreach( $args['validate'] as $validate )
				$args['class'][] = 'validate-' . $validate;

		$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .' " id="' . esc_attr( $key ) . '_field">';

		if ( $args['label'] )
			$field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label'] . $required . '</label>';

		$field .= '<input type="text" t_allowed="'.$allowed_times.'" class=" '. $datepicker_class .' input-text  '. pcfmeinput_conditional_class($key) .'" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" placeholder="' . $args['placeholder'] . '" '.$args['maxlength'].' value="'.$defalt_val.'" />
			</p>' . $after;

		return $field;
	}
}

new pcfme_manage_extrafield_class();
?>