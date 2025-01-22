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
     	add_filter( 'woocommerce_form_field_country', array( $this, 'pcfmecountry_form_field' ), 10, 4 );
	    add_filter( 'woocommerce_form_field_datepicker', array( $this, 'datepicker_form_field' ), 10, 4 );
	    add_filter( 'woocommerce_form_field_datetimepicker', array( $this, 'datetimepicker_form_field' ), 10, 4 );
	    add_filter( 'woocommerce_form_field_timepicker', array( $this, 'timepicker_form_field' ), 10, 4 );
	    add_filter( 'woocommerce_form_field_daterangepicker', array( $this, 'daterangepicker_form_field' ), 10, 4 );
	    add_filter( 'woocommerce_form_field_datetimerangepicker', array( $this, 'datetimerangepicker_form_field' ), 10, 4 );
		add_filter( 'woocommerce_form_field_multiselect', array( $this, 'multiselect_form_field' ), 10, 4 );
		add_filter( 'woocommerce_form_field_paragraph', array( $this, 'paragraph_form_field' ), 10, 4 );
		
		add_filter( 'wp_enqueue_scripts', array( $this, 'add_checkout_frountend_scripts' ));

		add_action( 'woocommerce_cart_calculate_fees', array( $this, 'add_cart_fees' ) );
		add_action( 'wp_ajax_nopriv_pcfme_get_action_data', array( $this, 'pcfme_get_action_data_function' ) );
        add_action( 'wp_ajax_pcfme_get_action_data', array( $this, 'pcfme_get_action_data_function' ) );
        add_action( 'admin_notices', array( $this, 'pcfme_add_admin_notices2' ) );

	 }

	
    /**
      * Add admin notices function
      *
      * @return void
      */
    public function pcfme_add_admin_notices2() {

    	$noticetext = get_option("pcfme_display_notice_text");

    	if (isset($noticetext) && ($noticetext != "")) {
    		?>
            <div class="notice notice-info">
                <p>
                    <?php echo $noticetext; ?>
                </p>
            </div>
            <?php
    	}
            
    }

	public function pcfme_get_action_data_function() {

	    if ( ! $_POST || ( is_admin() && ! is_ajax() ) ) {
            return;
        }

        $additional_fees    = get_option('pcfme_additional_fees');

        $response  = array();
        

        if (is_array($additional_fees)) {
        	$additional_fees    = array_filter($additional_fees);
        }


        if (isset($additional_fees) && is_array($additional_fees) && (sizeof($additional_fees) >= 1)) { 
        	$additional_fees = $additional_fees;
        } else {
        	$additional_fees = array();
        }


        $field_key = isset($_POST['key']) ? $_POST['key'] : "";
        $thisval   = isset($_POST['thisval']) ? $_POST['thisval'] : "";

        foreach ($additional_fees as $akey=>$avalue) {

        	if (isset($avalue['parentfield']) && ($avalue['parentfield'] != "") && ($avalue['parentfield'] == $field_key) && ($avalue['equalto'] == $thisval)) {
        		$response[$akey] = array(
        			'action'=> $avalue['action_type'],
        	        'target'=> $avalue['actionfield']
        	    );

        	}
        }


        wp_send_json_success( $response );
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

	    $separater_text = isset($pcfme_extra_settings['separater_text']) ? $pcfme_extra_settings['separater_text'] : esc_html__('to','customize-my-account-pro');;
	    

	    if ( is_checkout() || is_account_page() ) {

	     
		 
		 wp_enqueue_style( 'jquery-ui', ''.pcfme_PLUGIN_URL.'assets/css/jquery-ui.css' );

		 wp_enqueue_script( 'jquery.datetimepicker', ''.pcfme_PLUGIN_URL.'assets/js/jquery.datetimepicker.js',array('jquery') );
         
         wp_enqueue_script( 'moment', ''.pcfme_PLUGIN_URL.'assets/js/moment.js');
		 wp_enqueue_script( 'daterangepicker', ''.pcfme_PLUGIN_URL.'assets/js/daterangepicker.js',array('moment'));
		 
		 if ($pcfme_woo_version < 2.3) {
		 	wp_enqueue_script( 'pcfme-frontend1', ''.pcfme_PLUGIN_URL.'assets/js/frontend1.js' );
		 } else {
            wp_enqueue_script( 'pcfme-frontend2', ''.pcfme_PLUGIN_URL.'assets/js/frontend2.js',array(),$pcfme_checkout_version );
		 }
         
        $pcfmefrontend_array = array( 
		    'datepicker_format'               => $datepicker_format,
		    'timepicker_interval'             => $timepicker_interval,
		    'allowed_times'                   => $allowed_times,
		    'days_to_exclude'                 => $days_to_exclude,
		    'datetimepicker_lang'             => $datetimepicker_lang,
		    'week_starts_on'                  => $week_starts_on,
		    'dt_week_starts_on'               => $dt_week_starts_on,
		    'chose_option_text'               => esc_html__( 'Choose Default Option', 'customize-my-account-pro'  ),
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
			  $required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-pro'  ) . '">*</abbr>';
		  } else {
			$required = '';
		  }
		     


		$fees_class       = '';

		$fees_class       = pcfme_get_fees_class($key);

		
		if ($value == "empty") {
			$value = "";
		}
        
        $after ='';
		

        $field = '<p class="form-row ' . implode( ' ', $args['class'] ) .' " id="' . $key . '_field">
            <label for="' . $key . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>
            <input type="' . esc_attr( $args['type'] ) . '" class="'.$fees_class.' input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'  '. pcfmeinput_conditional_class($key) .'" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" ' . $args['maxlength'] . ' ' . $args['autocomplete'] . ' value="' . esc_attr( $value ) . '" />
        </p>' . $after;
         

        return $field;
      }
	  
	  
	  public function pcfmeheading_form_field($field, $key, $args, $value) {

         if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';
	  
	     if ( $args['required'] ) {
			  $args['class'][] = 'validate-required';
			  $required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-pro'  ) . '">*</abbr>';
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
			  $required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-pro'  ) . '">*</abbr>';
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
    		$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-pro'  ) . '">*</abbr>';
    	} else {
    		$required = '';
    	}
    	
    	
        if ($value == "empty") {
			$value = "";
		}

    	$fees_class       = '';

    	$fees_class       = pcfme_get_fees_class($key);
    	$charlimit        = isset($args['charlimit']) ? $args['charlimit'] : 200;
        
        $after ='';
    	
    	

    	$field = '<p class="form-row ' . implode( ' ', $args['class'] ) .' " id="' . $key . '_field">
    	<label for="' . $key . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>
    	<textarea maxlength="'.$charlimit.'" name="' . esc_attr( $key ) . '" class="'.$fees_class.' input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'  '. pcfmeinput_conditional_class($key) .'" id="' . $key . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" ' . $args['maxlength'] . ' ' . $args['autocomplete'] . ' ' . ( empty( $args['custom_attributes']['rows'] ) ? ' rows="2"' : '' ) . ( empty( $args['custom_attributes']['cols'] ) ? ' cols="5"' : '' ) . '>'. esc_textarea( $value  ) .'</textarea>
    	</p>' . $after;
    	

    	return $field;
    }
	  
	 public function pcfmecheckbox_form_field($field,$key, $args, $value) {




	 	 $key = isset($args['field_key']) ? $args['field_key'] : $key;

         if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';
	  
	     if ( $args['required'] ) {
			  $args['class'][] = 'validate-required';
			  $required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-pro'  ) . '">*</abbr>';
		  } else {
			$required = '';
		  }

		 $pcfme_conditional_class = '';
		
		 
         if ($value == "empty") {
			$value = "";
		 }

		 $fees_class       = '';

		 $fees_class       = pcfme_get_fees_class($key);

         $after ='';

		 if (isset($args['checked_by_default']) && ($args['checked_by_default'] == 1)) { 
		 	$checked_text = 'checked';
		 } else {
		 	$checked_text = '';
		 }

	
	   

         $field = '<p class="form-row ' . implode( ' ', $args['class'] ) .' " id="' . $key . '_field"><label class="checkbox ' . implode( ' ', $args['label_class'] ) .' ' . implode( ' ', $args['class'] ) .' ' . $pcfme_conditional_class .'" ><input type="' . esc_attr( $args['type'] ) . '" class="'.$fees_class.' input-checkbox ' . esc_attr( implode( ' ', $args['input_class'] ) ) .' ' . $pcfme_conditional_class .' '. pcfmeinput_conditional_class($key) .'" name="' . esc_attr( $key ) . '" id="' . $key . '" value="yes" '.$checked_text .' /> '
						 . $args['label'] . $required . '</label></p>' . $after;
         

        return $field;
      }
     
      public function radio_form_field($field, $key, $args, $value ) {
      
	    $key = isset($args['field_key']) ? $args['field_key'] : $key;
	  
        if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';
        
		if ( $args['required'] ) {
			$args['class'][] = 'validate-required';
			$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-pro'  ) . '">*</abbr>';
		} else {
			$required = '';
		}


		$fees_class       = '';

		$fees_class         = pcfme_get_fees_class($key);


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

			  $options .= '<input type="radio" name="' . $key . '" id="' . $key . '" value="' . $option_key . '" ' . checked( $value, $option_key, false ) . 'class="'.$fees_class.' '.$action_class.' select  '. pcfmeinput_conditional_class($key) .'" '.$checked_text.' '.$checked_text.'>  ' . $option_text . '<br>';
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
     		$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-pro'  ) . '">*</abbr>';
     		$requiredtext = 'required';
     	} else {
     		$required = '';
     		$requiredtext = '';
     	}

     		  
     	$options = '';

     	$after ='';

     	
     	$options .= '<option value="">'.esc_html__('Choose an Option','customize-my-account-pro').'</option>';
     		

     	if (! empty ($args['default_option'])) {
     		
     		$value    = $args['default_option'];
     	}

     	if ($value == "empty") {
			$value = "";
		}




     	$fees_class       = '';

		$fees_class       = pcfme_get_fees_class($key);


     	if ( ! empty( $args['options'] ) ) {
     		foreach ( $args['options'] as $option_key => $option_text ) {

     			$option_key = preg_replace('/\s+/', '_', $option_key);

     			$options .= '<option value="' . $option_key . '" '. selected( $value, $option_key, false ) . '>' . $option_text .'</option>';
     		}

     		$field = '<p class="form-row ' . implode( ' ', $args['class'] ) .' " id="' . $key . '_field">
     		<label for="' . $key . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>
     		<select data-placeholder="'.$args['placeholder'].'" name="' . $key . '" id="' . $key . '" class="select '.$fees_class.' pcfme-singleselect  '. pcfmeinput_conditional_class($key) .'" '.$requiredtext.'>
     		' . $options . '
     		</select>
     		</p>' . $after;
     	}

     	return $field;
     }


    public function pcfmecountry_form_field( $field, $key, $args, $value) {

     	$key = isset($args['field_key']) ? $args['field_key'] : $key;

     	if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';

     	if ( $args['required'] ) {
     		$args['class'][] = 'validate-required';
     		$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-pro'  ) . '">*</abbr>';
     		$requiredtext = 'required';
     	} else {
     		$required = '';
     		$requiredtext = '';
     	}

     		  
     	$options = '';

     	$after ='';

     	
     	$options .= '<option value="">'.esc_html__('Choose an Option','customize-my-account-pro').'</option>';
     		

     	if (! empty ($args['default_option'])) {
     		
     		$value    = $args['default_option'];
     	}

     	if ($value == "empty") {
			$value = "";
		}




     	$fees_class       = '';

		$fees_class       = pcfme_get_fees_class($key);

				// Custom attribute handling.
		$custom_attributes         = array();
		$args['custom_attributes'] = array_filter( (array) $args['custom_attributes'], 'strlen' );

		if ( $args['maxlength'] ) {
			$args['custom_attributes']['maxlength'] = absint( $args['maxlength'] );
		}

		if ( $args['minlength'] ) {
			$args['custom_attributes']['minlength'] = absint( $args['minlength'] );
		}

		if ( ! empty( $args['autocomplete'] ) ) {
			$args['custom_attributes']['autocomplete'] = $args['autocomplete'];
		}

		if ( true === $args['autofocus'] ) {
			$args['custom_attributes']['autofocus'] = 'autofocus';
		}

		if ( $args['description'] ) {
			$args['custom_attributes']['aria-describedby'] = $args['id'] . '-description';
		}

		if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
			foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
			}
		}

		$countries = 'shipping_country' === $key ? WC()->countries->get_shipping_countries() : WC()->countries->get_allowed_countries();

				if ( 1 === count( $countries ) ) {

					$field_html .= '<strong>' . current( array_values( $countries ) ) . '</strong>';

					$field_html .= '<input type="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="' . current( array_keys( $countries ) ) . '" ' . implode( ' ', $custom_attributes ) . ' class="country_to_state '.$fees_class.' '. pcfmeinput_conditional_class($key) .'" readonly="readonly" />';

				} else {
					$data_label = ! empty( $args['label'] ) ? 'data-label="' . esc_attr( $args['label'] ) . '"' : '';

					$field_html = '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="country_to_state country_select '.$fees_class.' '. pcfmeinput_conditional_class($key) .' pcfme-singleselect ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ? $args['placeholder'] : esc_attr__( 'Select a country / region&hellip;', 'woocommerce' ) ) . '" ' . $data_label . '><option value="">' . esc_html__( 'Select a country / region&hellip;', 'woocommerce' ) . '</option>';

					foreach ( $countries as $ckey => $cvalue ) {
						$field_html .= '<option value="' . esc_attr( $ckey ) . '" ' . selected( $value, $ckey, false ) . '>' . esc_html( $cvalue ) . '</option>';
					}

					$field_html .= '</select>';

					$field_html .= '<noscript><button type="submit" name="woocommerce_checkout_update_totals" value="' . esc_attr__( 'Update country / region', 'woocommerce' ) . '">' . esc_html__( 'Update country / region', 'woocommerce' ) . '</button></noscript>';

				}


     	if ( ! empty( $args['options'] ) ) {
     		foreach ( $args['options'] as $option_key => $option_text ) {

     			$option_key = preg_replace('/\s+/', '_', $option_key);

     			$options .= '<option value="' . $option_key . '" '. selected( $value, $option_key, false ) . '>' . $option_text .'</option>';
     		}

     		$field = '<p class="form-row ' . implode( ' ', $args['class'] ) .' " id="' . $key . '_field">
     		<label for="' . $key . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>
     		'.$field_html.'
     		</p>' . $after;
     	}

     	return $field;
     }


	 
	 public function multiselect_form_field( $field, $key, $args, $value) {
	 	$key = isset($args['field_key']) ? $args['field_key'] : $key;

	  if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';
	  
	    if ( $args['required'] ) {
			$args['class'][] = 'validate-required';
			$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-pro'  ) . '">*</abbr>';
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
		
		
	    

		$fees_class       = '';

		$fees_class       = pcfme_get_fees_class($key);
		
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
			$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-pro'  ) . '">*</abbr>';
		} else {
			$required = '';
		}

		$after ='';
		
		 
		
		if (isset($args['disable_past'])) {
			$datepicker_class='pcfme-datepicker-disable-past';
		} else {
			$datepicker_class='pcfme-datepicker';
		}

		if ($value == "empty") {
			$value = "";
		}

		$defalt_val = '';

		if (isset($args['enable_default_date'])) {

			$defalt_val          = isset($args['default_date_add']) ? $args['default_date_add'] : 0;

			$pcfme_extra_settings = (array) get_option('pcfme_extra_settings');

			$date_format          = isset($pcfme_extra_settings['datepicker_format']) ? $pcfme_extra_settings['datepicker_format'] : 01;

			$date_format          = pcfme_get_datepicker_format_from_option($date_format);

			$date = date("Y-m-d");

			$mod_date = strtotime($date."+ $defalt_val days");

			$defalt_val = date($date_format,$mod_date);

		}

		$disable_specific_dates = '';


		if (isset($args['disable_specific_dates'])) {

            $disable_specific_dates          = isset($args['disable_specific_dates']) ? $args['disable_specific_dates'] : "";
		}




		$fees_class       = '';

		$fees_class       = pcfme_get_fees_class($key);

		$args['maxlength'] = ( $args['maxlength'] ) ? 'maxlength="' . absint( $args['maxlength'] ) . '"' : '';

		if ( ! empty( $args['validate'] ) )
			foreach( $args['validate'] as $validate )
				$args['class'][] = 'validate-' . $validate;

		$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .' " id="' . esc_attr( $key ) . '_field">';

		if ( $args['label'] )
			$field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label'] . $required . '</label>';

		$field .= '<input dates_to_disable="'.$disable_specific_dates.'" type="text" class="'.$fees_class.' '. $datepicker_class .' input-text  '. pcfmeinput_conditional_class($key) .'" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" placeholder="' . $args['placeholder'] . '" '.$args['maxlength'].' value="' . esc_attr( $defalt_val ) . '" />
			</p>' . $after;

		return $field;
	 }



	public function datetimepicker_form_field( $field, $key, $args, $value) {
		$key = isset($args['field_key']) ? $args['field_key'] : $key;

	    if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';

		if ( $args['required'] ) {
			$args['class'][] = 'validate-required';
			$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-pro'  ) . '">*</abbr>';
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





		$fees_class       = '';

		$fees_class       = pcfme_get_fees_class($key);

		$args['maxlength'] = ( $args['maxlength'] ) ? 'maxlength="' . absint( $args['maxlength'] ) . '"' : '';

		if ( ! empty( $args['validate'] ) )
			foreach( $args['validate'] as $validate )
				$args['class'][] = 'validate-' . $validate;

		$field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .' " id="' . esc_attr( $key ) . '_field">';

		if ( $args['label'] )
			$field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label'] . $required . '</label>';

		$field .= '<input dates_to_disable="'.$disable_specific_dates.'" t_allowed="'.$allowed_times.'" type="text" class="'.$fees_class.' '. $datepicker_class .' input-text  '. pcfmeinput_conditional_class($key) .'" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" placeholder="' . $args['placeholder'] . '" '.$args['maxlength'].' value="'.$defalt_val.'" />
			</p>' . $after;

		return $field;
	 }


	public function daterangepicker_form_field(  $field, $key, $args, $value ) {

		$key = isset($args['field_key']) ? $args['field_key'] : $key;

	    if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';

		if ( $args['required'] ) {
			$args['class'][] = 'validate-required';
			$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-pro'  ) . '">*</abbr>';
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


		$fees_class       = '';

		$fees_class       = pcfme_get_fees_class($key);

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
			$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-pro'  ) . '">*</abbr>';
		} else {
			$required = '';
		}

		if ($value == "empty") {
			$value = "";
		}

		$after ='';
		
		

		$fees_class       = '';

		$fees_class       = pcfme_get_fees_class($key);
		
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

		$field .= '<input type="text" class="'.$fees_class.' '. $datepicker_class .' input-text  '. pcfmeinput_conditional_class($key) .'" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" placeholder="' . $args['placeholder'] . '" '.$args['maxlength'].' value="" />
			</p>' . $after;

		return $field;
	}


	public function timepicker_form_field(  $field,$key, $args, $value) {
		$key = isset($args['field_key']) ? $args['field_key'] : $key;
		
	    if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';

		if ( $args['required'] ) {
			$args['class'][] = 'validate-required';
			$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'customize-my-account-pro'  ) . '">*</abbr>';
		} else {
			$required = '';
		}


		if ($value == "empty") {
			$value = "";
		}
		

		$after ='';
		 

		$fees_class       = '';

		$fees_class       = pcfme_get_fees_class($key);

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

		$field .= '<input type="text" t_allowed="'.$allowed_times.'" class="'. $fees_class.' '. $datepicker_class .' input-text  '. pcfmeinput_conditional_class($key) .'" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" placeholder="' . $args['placeholder'] . '" '.$args['maxlength'].' value="'.$defalt_val.'" />
			</p>' . $after;

		return $field;
	}
}

new pcfme_manage_extrafield_class();
?>