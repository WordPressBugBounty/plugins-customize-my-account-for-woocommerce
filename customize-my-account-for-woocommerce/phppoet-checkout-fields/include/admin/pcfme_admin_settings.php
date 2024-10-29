<?php

	
class pcfme_add_settings_page_class {
	
	
	
	private $billing_settings_key       = 'pcfme_billing_settings';
	private $shipping_settings_key      = 'pcfme_shipping_settings';
	private $additional_settings_key    = 'pcfme_additional_settings';
	private $additional_fees_key        = 'pcfme_additional_fees';
	private $extra_settings_key         = 'pcfme_extra_settings';
	private $pcfme_plugin_options       = 'pcfme_plugin_options';
	private $pcfme_license_settings     = 'pcfme_license_settings';
    private $pcfme_plugin_settings_tabs = array();	
	
	
	public function __construct() {
	    
		
		
	    
		add_action( 'admin_init', array( $this, 'register_billing_settings' ) );
		
		add_action( 'admin_menu', array( $this, 'add_admin_menus' ) ,100);
		add_action( 'admin_enqueue_scripts', array($this, 'pcfme_register_admin_scripts'));
		add_action( 'admin_enqueue_scripts', array($this, 'pcfme_load_admin_default_css'));
        add_action( 'wp_ajax_restore_billing_fields', array( $this, 'restore_billing_fields' ) );
		add_action( 'wp_ajax_restore_shipping_fields', array( $this, 'restore_shipping_fields' ) );
		add_action( 'wp_ajax_pdfmegetajaxproductslist', array( $this, 'pcfme_get_posts_ajax_callback' ) );
        
        add_action( 'wp_ajax_pcfme_get_child_field_options', array( $this, 'pcfme_get_child_field_options_function' ) );

        

        add_action( 'admin_post_add_field_form_response', array( $this, 'add_field_form_response' ));

		add_action( 'admin_post_add_fees_form_response', array( $this, 'add_fees_form_response' ));


	}




	public function add_fees_form_response() {

		if( isset( $_POST['pcfme_add_fees_nonce'] ) && wp_verify_nonce( $_POST['pcfme_add_fees_nonce'], 'pcfme_nonce_hidden_fees') ) {

		
		$rule_type = "01";
			

		if (isset($_POST['nds2']['rule_type'])) {
			$rule_type     = sanitize_text_field($_POST['nds2']['rule_type']);
		}
		


        $random_number  = mt_rand(100000, 999999);

       
        
        $existing_fields = (array) get_option('pcfme_additional_fees');

        $existing_fields    = array_filter($existing_fields);



        $new_row_values = array();


        $rownum = 02;
        	

        foreach ($existing_fields as $key2=>$value2) {

        		
             $new_row_values[$rownum] = $value2;

             $rownum++;
                
        }

        


        if (isset($rule_type) && ($rule_type != "")) {
            $new_row_values[$random_number]['rule_type']            = $rule_type;
            $new_row_values[$random_number]['amount']               = '';
            $new_row_values[$random_number]['type']                 = 'fixed';
            $new_row_values[$random_number]['parentfield']          = '';
            $new_row_values[$random_number]['custom_label']         = '';
            $new_row_values[$random_number]['equalto']              = '';
           
        }


        if (($new_row_values != $existing_fields) && !empty($new_row_values)) {


        	update_option('pcfme_additional_fees',$new_row_values);


        }

        $redirect_tab ='admin.php?page=pcfme_plugin_options&tab=pcfme_additional_fees';
 
		// add the admin notice
			$admin_notice = "success";

		// redirect the user to the appropriate page
			wp_redirect($redirect_tab);
			exit;
		} else {
			wp_die( __( 'Invalid nonce specified' ), __( 'Error' ), array(
				'response' 	=> 403,
				'back_link' => 'admin.php?page=pcfme_plugin_options',

			) );
		}
	}

	public function add_field_form_response() {

		if( isset( $_POST['pcfme_add_field_nonce'] ) && wp_verify_nonce( $_POST['pcfme_add_field_nonce'], 'pcfme_nonce_hidden') ) {

		
		
			

		if (isset($_POST['nds']['field_type'])) {
			$field_type     = sanitize_text_field($_POST['nds']['field_type']);
		}
		
        if (isset($_POST['nds']['label'])) {
            $new_name      = sanitize_text_field($_POST['nds']['label']);
        }

        if (isset($_POST['nds']['section'])) {
            $section      = sanitize_text_field($_POST['nds']['section']);
        }

        $random_number  = mt_rand(100000, 999999);

        $countries     = new WC_Countries();


        switch($section) {
        	case "billing":
        	    $existing_fields = (array) get_option('pcfme_billing_settings');
        	    $redirect_tab    = 'admin.php?page=pcfme_plugin_options&tab=pcfme_billing_settings';
        	    $new_key         = 'billing_field_'.$random_number.'';
        	    $core_fields     = $countries->get_address_fields( $countries->get_base_country(),'billing_');

        	break;

        	case "shipping":
        	    $existing_fields = (array) get_option('pcfme_shipping_settings');
        	    $redirect_tab    = 'admin.php?page=pcfme_plugin_options&tab=pcfme_shipping_settings';
        	    $new_key         = 'shipping_field_'.$random_number.'';
        	    $core_fields     = $countries->get_address_fields( $countries->get_base_country(),'shipping_');
        	break;

        	case "additional":
        	    $existing_fields = (array) get_option('pcfme_additional_settings');
        	    $redirect_tab    = 'admin.php?page=pcfme_plugin_options&tab=pcfme_additional_settings';
        	    $new_key         = 'additional_field_'.$random_number.'';
        	    $core_fields     = array();
        	break;
        }

        $new_row_values = array();


        if ((!isset($existing_fields) || (sizeof($existing_fields) <= 1 )) && ($section != "additional")) {
            $tabs  = $core_fields;

            foreach ($tabs as $key=>$value) {
            
                $new_row_values[$key] = $value;


            }

        } else {
        	

        	foreach ($existing_fields as $key2=>$value2) {

        		
                $new_row_values[$key2] = $value2;
                
               

            }

        }


        if (isset($new_name) && ($new_name != '')) {
            $new_row_values[$new_key]['field_key']           = $new_key;
            $new_row_values[$new_key]['type']                = $field_type;
            $new_row_values[$new_key]['label']               = $new_name;

        }

        if (($new_row_values != $existing_fields) && !empty($new_row_values)) {

        	switch($section) {
        		case "billing":
        		update_option('pcfme_billing_settings',$new_row_values);

        		break;

        		case "shipping":
        		update_option('pcfme_shipping_settings',$new_row_values);
        		break;

        		case "additional":
        		update_option('pcfme_additional_settings',$new_row_values);
        		break;
        	}

        }


 
		// add the admin notice
			$admin_notice = "success";

		// redirect the user to the appropriate page
			wp_redirect($redirect_tab);
			exit;
		} else {
			wp_die( __( 'Invalid nonce specified' ), __( 'Error' ), array(
				'response' 	=> 403,
				'back_link' => 'admin.php?page=pcfme_plugin_options',

			) );
		}
	}

	public function pcfme_get_child_field_options_function() {

		global $woocommerce;

		$this->countries     = new WC_Countries();

		if (isset($_POST['mtype'])) {
			$mtype     = sanitize_text_field($_POST['mtype']);
		}

		$fields  = (array) get_option('pcfme_'.$mtype.'_settings');

		
		$return['field_data'] = $fields;


		echo json_encode( $return );

		die;

	}
	
	public function pcfme_get_posts_ajax_callback(){
 
	
	  $return = array();
      $post_type_array = array('product', 'product_variation');
	  // you can use WP_Query, query_posts() or get_posts() here - it doesn't matter
	  $search_results = new WP_Query( array( 
		's'=> $_GET['q'], // the search query
		'post_status' => 'publish', // if you don't want drafts to be returned
		'ignore_sticky_posts' => 1,
		'post_type'           => $post_type_array,
		'posts_per_page' => 50 // how much to show at once
	  ) );
	  
	
	  if( $search_results->have_posts() ) :
		while( $search_results->have_posts() ) : $search_results->the_post();	
			// shorten the title a little
			$title = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;
			$finaltitle='#'. $search_results->post->ID.'- '.$title.'';
			$return[] = array( $search_results->post->ID, $finaltitle ); // array( Post ID, Post Title )
		endwhile;
	  endif;
	   echo json_encode( $return );
	  die;
    }
	
	
	public function restore_billing_fields() {
	    if( current_user_can('manage_woocommerce') ) {
           delete_option( $this->billing_settings_key );
	    }
	   
	   die();
	}
	
	public function restore_shipping_fields() {
	    if( current_user_can('manage_woocommerce') ) {
	        delete_option( $this->shipping_settings_key );
	    }
	   die();
	}

	

	public function pcfme_load_admin_default_css() {

	    wp_enqueue_style( 'woomatrix_admin_menu_css', ''.pcfme_PLUGIN_URL.'assets/css/admin_menu.css' );
	    wp_enqueue_script( 'woomatrix_admin_menu_js', ''.pcfme_PLUGIN_URL.'assets/js/admin_menu.js' );

	}
	
	

	
	/*
	 * registers admin scripts via admin enqueue scripts
	 */
	public function pcfme_register_admin_scripts($hook) {
	    global $billing_pcfmesettings_page;
			
		if ( $hook == $billing_pcfmesettings_page ) {
		     
 
		 
		 
		 
		    wp_enqueue_style( 'select2', ''.pcfme_PLUGIN_URL.'assets/css/select2.css' );
		    wp_enqueue_script( 'select2', ''.pcfme_PLUGIN_URL.'assets/js/select2.js' ,array('jquery') );
		 
		 
		    wp_enqueue_script( 'bootstrap-min', ''.pcfme_PLUGIN_URL.'assets/js/bootstrap-min.js' );

		    wp_enqueue_script( 'bootstrap-toggle.min.js', ''.pcfme_PLUGIN_URL.'assets/js/bootstrap-toggle.min.js' );

		    wp_enqueue_script( 'jquery-ui-sortable');
		 
		 
		    wp_enqueue_script( 'jquery.tag-editor', ''.pcfme_PLUGIN_URL.'assets/js/jquery.tag-editor.js' );
		    wp_enqueue_style( 'jquery.tag-editor', ''.pcfme_PLUGIN_URL.'assets/css/jquery.tag-editor.css' );
		    wp_enqueue_script( 'pcfmeadmin', ''.pcfme_PLUGIN_URL.'assets/js/pcfmeadmin.js' );
		 
         
		    wp_enqueue_style( 'pcfmeadmin', ''.pcfme_PLUGIN_URL.'assets/css/pcfmeadmin.css' );
		    wp_enqueue_style( 'pcfmeadmin-fa', ''.pcfme_PLUGIN_URL.'assets/css/fa.css' );
		    wp_enqueue_style ( 'bootstrap',''.pcfme_PLUGIN_URL.'assets/css/bootstrap.css');

		    wp_enqueue_style ( 'bootstrap-toggle.min.css',''.pcfme_PLUGIN_URL.'assets/css/bootstrap-toggle.min.css');
		 

		 
		    wp_enqueue_script( 'pcfme-frontend1', ''.pcfme_PLUGIN_URL.'assets/js/frontend1.js' );
		    wp_enqueue_style( 'jquery-ui', ''.pcfme_PLUGIN_URL.'assets/css/jquery-ui.css' );
		    wp_enqueue_style( 'pcfme-frontend', ''.pcfme_PLUGIN_URL.'assets/css/frontend.css' );
		 
            

            $rule_type_select1 = '<select mtype="" mntext="" mnkey="" class="checkout_field_dynamic_rule_type" name="">';

            $rule_type_select1 .= pcfme_get_dynamic_rule_types_select_optionhtml();

            $rule_type_select1 .= '</select>';

            $rule_type_select2 = '<select mtype="" mntext="" mnkey="" class="checkout_field_dynamic_rule_type_compare show_if_quantity_rule" name="">';

            $rule_type_select2 .= pcfme_get_dynamic_rule_types_compare_optionhtml();

            $rule_type_select2 .= '</select>';

             $rule_type_select3 = '<select style="display:none;" mtype="" mntext="" mnkey="" class="checkout_field_dynamic_rule_type_contains show_if_contains_rule" name="">';

            $rule_type_select3 .= pcfme_get_dynamic_rule_types_contains_optionhtml();

            $rule_type_select3 .= '</select>';

            $rule_type_number   = '<span style="" class="show_if_quantity_rule"><input type="number" class="checkout_field_dynamic_rule_number" val=""></span>';

            $rule_type_number2   = '<span style="display:none;" class="show_if_contains_rule">';


            $rule_type_number2   .= '<span  class="checkout_field_products_span" style="display:none;"><select class="checkout_field_products" data-placeholder="'.esc_html__('Choose Products','customize-my-account-for-woocommerce').'" name="" multiple>
            </select></span>';


            $rule_type_number2   .= '<span  class="checkout_field_category_span" style="display:none;"><select  class="checkout_field_category" data-placeholder="'. esc_html__('Choose Categories','customize-my-account-for-woocommerce').'" name=""  multiple>';

            $catargs = array(
            	'orderby'                  => 'name',
            	'taxonomy'                 => 'product_cat',
            	'hide_empty'               => 0
            );


            $categories           = get_categories( $catargs );  

            
            foreach ($categories as $category) { 

            	$rule_type_number2   .= '<option value="'.$category->term_id.'">#'.$category->term_id.'- '.$category->name.'</option>';
            	
            }

            $rule_type_number2   .= '</select></span>';


            global $wp_roles;

            if ( ! isset( $wp_roles ) ) { 
            	$wp_roles = new WP_Roles();  
            }

            $roles = $wp_roles->roles;

            $rule_type_number2   .= '<span  class="checkout_field_roles_span" style="display:none;"><select  class="checkout_field_role" data-placeholder="'. esc_html__('Choose Roles','customize-my-account-for-woocommerce').'" name=""  multiple>';


            foreach ($roles as $rkey=>$rvalue) { 

            	$rule_type_number2   .= '<option value="'.$rkey.'">'.$rvalue['name'].'</option>';
            	
            }


            $rule_type_number2   .= '</select></span>';
 

            $rule_type_number2   .= '</span>';

            $billing_settings = (array) get_option('pcfme_billing_settings');

            $shipping_settings = (array) get_option('pcfme_shipping_settings');

            $billing_select = '';
            $fees_select    = '';
            

            $country_fields = '';

		    $billing_select .= '<select mtype="" mntext="" mnkey="" class="checkout_field_conditional_parentfield" name=""><option></option>';
		    $fees_select    .= '<select class="checkout_field_conditional_parentfield" name="">';
		    $fees_select    .= '<optgroup label="'.esc_html__( 'Billing Fields' ,'customize-my-account-for-woocommerce').'">';

		    $action_select    = '';

		    $payment_select   = '';


            $payment_select   .= '<select class="checkout_field_rule_actionfield" name="">';
            $action_select    .= '<select class="checkout_field_rule_actionfield" name="">';


		    $action_select    .= '<optgroup label="'.esc_html__( 'Payment Gateway' ,'customize-my-account-for-woocommerce').'">';


		    

	        $payment_gateways = WC()->payment_gateways->get_available_payment_gateways();

	        foreach ($payment_gateways as $rkey=>$rvalue) { 

                $payment_select .='<option value="payment_method_'.$rkey.'">'.$rkey.'</option>';
				$action_select    .='<option value="payment_method_'.$rkey.'">'.$rkey.'</option>';

	        }


		    $action_select    .= '</optgroup>';


		    $shipping_methods = WC()->shipping->get_shipping_methods();


		    $action_select    .= '<optgroup label="'.esc_html__( 'Shipping Method' ,'customize-my-account-for-woocommerce').'">';


		    

	        

	        foreach ($shipping_methods as $skey=>$svalue) { 

                $payment_select .='<option value="shipping_method_'.$skey.'">'.$skey.'</option>';
				$action_select    .='<option value="shipping_method_'.$skey.'">'.$skey.'</option>';

	        }


		    $action_select    .= '</optgroup>';
				 
		    $payment_select .= '</select>';
		    
				     
	        foreach ($billing_settings as $optionkey=>$optionvalue) { 
				   
		        if ( (isset ($optionvalue['type']) && ($optionvalue['type'] == 'email')) || (preg_match('/\b'.$optionkey.'\b/', $country_fields ))) { 
					 
			    } else { 


			    	$field_type = isset($optionvalue['type']) ? $optionvalue['type'] : "text";


			    	

			    	switch($field_type) {
			    		case "pcfmeselect":
			    		$field_type_text ='select';
			    		break;

			    		default:
			    		$field_type_text =$field_type;
			    		break;
			    	}

			    	if ($field_type_text != "") {
			    		$field_type_text_html = '('.$field_type_text.')';
			    	}
				   
		        

				    if (isset($optionvalue['label']))  { 

					    $optionlabel = ''.$optionvalue['label'].' '.$field_type_text_html.''; 

				    } else { 

					    $optionlabel = ''.$optionkey.''; 
				    }   
					    	
				    $billing_select .='<option value="'.$optionkey.'">'.$optionlabel.'</option>';
				    $fees_select    .='<option value="'.$optionkey.'">'.$optionlabel.'</option>';
			    } 
		    } 

		    $fees_select    .= '</optgroup>';
				 
		    $billing_select .= '</select>';



		    $shipping_select  = '';

            $country_fields   = '';

		    $shipping_select .= '<select mtype="" mntext="" mnkey=""class="checkout_field_conditional_parentfield" name=""><option></option>';

		    $fees_select     .= '<optgroup label="'.esc_html__( 'Shipping Fields' ,'customize-my-account-for-woocommerce').'">';

				     
	        foreach ($shipping_settings as $optionkey=>$optionvalue) { 

                    
                    $field_type = isset($optionvalue['type']) ? $optionvalue['type'] : "text";

	        	    

			    	switch($field_type) {
			    		case "pcfmeselect":
			    		$field_type_text ='select';
			    		break;

			    		default:
			    		$field_type_text =$field_type;
			    		break;
			    	}

			    	if ($field_type_text != "") {
			    		$field_type_text_html = '('.$field_type_text.')';
			    	}
				   
		        

				    if (isset($optionvalue['label']))  { 

					    $optionlabel = ''.$optionvalue['label'].' '.$field_type_text_html.''; 

				    } else { 

					    $optionlabel = ''.$optionkey.''; 
				    }   
					    	
				    $shipping_select .='<option value="'.$optionkey.'">'.$optionlabel.'</option>';
				    $fees_select     .='<option value="'.$optionkey.'">'.$optionlabel.'</option>';
			    
		    }

		    $fees_select     .= '</optgroup>'; 
				 
		    $shipping_select .= '</select>';



		    $additional_select  = '';

            $country_fields     = '';

		    $additional_select .= '<select mtype="" mntext="" mnkey="" class="checkout_field_conditional_parentfield" name=""><option></option>';


		    $additional_settings  = (array) get_option('pcfme_additional_settings');
		    $additional_settings  = array_filter($additional_settings);


		    
		    if (isset($additional_settings) && (sizeof($additional_settings) >= 1)) { 
		    	$conditional_fields_dropdown = $additional_settings;
		    } else {
		    	$conditional_fields_dropdown = array();
		    }

		    
				     
		    
		    if (count($conditional_fields_dropdown) != 0) {

		    	$fees_select .= '<optgroup label="'.esc_html__( 'Additional Fields' ,'customize-my-account-for-woocommerce').'">';

		    }

		    

		    foreach ($conditional_fields_dropdown as $optionkey=>$optionvalue) { 

		    	if ( (isset ($optionvalue['type']) && ($optionvalue['type'] == 'email')) || (preg_match('/\b'.$optionkey.'\b/', $country_fields ))) { 

		    	} else { 

		    		$field_type = $optionvalue['type'];

		    		switch($field_type) {
		    			case "pcfmeselect":
		    			$field_type_text ='select';
		    			break;

		    			default:
		    			$field_type_text =$field_type;
		    			break;
		    		}

		    		if ($field_type_text != "") {
		    			$field_type_text_html = '('.$field_type_text.')';
		    		}
		    		
		    		

		    		if (isset($optionvalue['label']))  { 

		    			$optionlabel = ''.$optionvalue['label'].' '.$field_type_text_html.''; 

		    		} else { 

		    			$optionlabel = ''.$optionkey.' '; 
		    		}  

		    		$additional_select .='<option value="'.$optionkey.'">'.$optionlabel.'</option>';
		    		$fees_select       .='<option value="'.$optionkey.'">'.$optionlabel.'</option>';
		    	} 
		    }

		    

	        

            if (count($conditional_fields_dropdown) != 0) {

            	$fees_select       .= '</optgroup>';

            }
		    
				 
		    $additional_select .= '</select>';
		    $fees_select       .= '</select>';


		    if( current_user_can('manage_woocommerce') ) {

		        $restore_warning_text = esc_html__( 'Restoring Default fields will undo all your Changes. Are you sure you want to do this ?' ,'customize-my-account-for-woocommerce');

		    } else {

		    	$restore_warning_text = esc_html__( 'You can not restore fields in plugin demo.Of course this will work on your site.' ,'customize-my-account-for-woocommerce');

		    }


		    $child_array = array();





        
		
		 
		    $translation_array = array( 
		        'removealert'               => esc_html__( 'Are you sure you want to delete?' ,'customize-my-account-for-woocommerce'),
		        'checkoutfieldtext'         => esc_html__( 'billing_field_' ,'customize-my-account-for-woocommerce'),
		        'checkoutfieldtext2'        => esc_html__( 'shipping_field_' ,'customize-my-account-for-woocommerce'),
		        'checkoutfieldtext3'        => esc_html__( 'additional_field_' ,'customize-my-account-for-woocommerce'),
		        'checkoutfieldtext4'        => esc_html__( 'Billing field ' ,'customize-my-account-for-woocommerce'),
		        'checkoutfieldtext5'        => esc_html__( 'Shipping field ' ,'customize-my-account-for-woocommerce'),
		        'checkoutfieldtext6'        => esc_html__( 'Additional field ' ,'customize-my-account-for-woocommerce'),
		        'placeholder'               => esc_html__( 'Search and Select ' ,'customize-my-account-for-woocommerce'),
		        'restorealert'              => $restore_warning_text,
			    'optionplaceholder'         => esc_html__( 'Enter Option' ,'customize-my-account-for-woocommerce'),
			    'classplaceholder'          => esc_html__( 'Enter Class' ,'customize-my-account-for-woocommerce'),
			    'billing_select'            => $billing_select,
			    'shipping_select'           => $shipping_select,
			    'additional_select'         => $additional_select,
			    'fees_select'               => $fees_select,
			    'action_select'             => $action_select,
			    'amountplaceholder'         => esc_html__( 'Amount' ,'customize-my-account-for-woocommerce'),
			    'showtext'                  => esc_html__( 'Show' ,'customize-my-account-for-woocommerce'),
			    'addtext'                   => esc_html__( 'Add' ,'customize-my-account-for-woocommerce'),
			    'deducttext'                => esc_html__( 'Deduct' ,'customize-my-account-for-woocommerce'),
			    'hidetext'                  => esc_html__( 'Hide' ,'customize-my-account-for-woocommerce'),
			    'valuetext'                 => esc_html__( 'If value of' ,'customize-my-account-for-woocommerce'),
			    'equaltext'                 => esc_html__( 'is equal to' ,'customize-my-account-for-woocommerce'),
			    'fixedtext'                 => esc_html__( 'Fixed Amount' ,'customize-my-account-for-woocommerce'),
			    'percentagetext'            => esc_html__( 'Percentage' ,'customize-my-account-for-woocommerce'),
			    'is_checked'                => esc_html__( 'Is Checked' ,'customize-my-account-for-woocommerce'),
			    'copiedalert'               => esc_html__( 'Field key successfully copied to clipboard.' ,'customize-my-account-for-woocommerce'),
			    'input_label_text'          => esc_html__( 'Custom Label' ,'customize-my-account-for-woocommerce'),
			    'copy_text'                 => esc_html__( 'Copy' ,'customize-my-account-for-woocommerce'),
			    'rule_type_select1'         => $rule_type_select1,
			    'rule_type_select2'         => $rule_type_select2,
			    'rule_type_number'          => $rule_type_number,
			    'rule_type_select3'         => $rule_type_select3,
			    'rule_type_number2'         => $rule_type_number2
		    );
         
            wp_localize_script( 'pcfmeadmin', 'pcfmeadmin', $translation_array );
        }
	

	}


	
	
	public function register_billing_settings() {
		

		$this->pcfme_plugin_settings_tabs['pcfme_additional_settings'] = esc_html__( 'Edit Account Fields' ,'customize-my-account-for-woocommerce');

		

		$this->pcfme_plugin_settings_tabs['pcfme_extra_settings'] = esc_html__( 'Settings' ,'customize-my-account-for-woocommerce');






		


		register_setting( $this->additional_settings_key, $this->additional_settings_key );
		add_settings_section( 'pcfme_section_additional', '', '', $this->additional_settings_key );
		add_settings_field( 'pcfme_additional_option', '', array( $this, 'pcfme_field_additional_option' ), $this->additional_settings_key, 'pcfme_section_additional' );

		

		register_setting( $this->extra_settings_key, $this->extra_settings_key );
		add_settings_section( 'pcfme_section_extra', '', '', $this->extra_settings_key );
		add_settings_field( 'pcfme_extra_option', '', array( $this, 'pcfme_field_extra_option' ), $this->extra_settings_key, 'pcfme_section_extra' );

		
	}
	
	


	 public function pcfme_field_additional_option() { 


	 		include ('forms/pcfme_admin_additional_fields_form.php');


	 	


	 }





	public function pcfme_field_extra_option() { 
	     
       include ('forms/pcfme_admin_extra_fields_form.php');
		 
		 
	}


	 

	
	
	

	public function add_admin_menus() {
	   global $billing_pcfmesettings_page;

	    add_menu_page(
          esc_html__( 'sysbasics', 'plps' ),
         'SysBasics',
         'manage_woocommerce',
         'sysbasics',
         array($this,'plugin_options_page'),
         ''.pcfme_PLUGIN_URL.'assets/images/icon.png',
         70
        );
	    

        
	   
	    $billing_pcfmesettings_page = add_submenu_page( 'sysbasics', esc_html__('My Account Fields','customize-my-account-for-woocommerce'), esc_html__('My Account Fields','customize-my-account-for-woocommerce'), 'manage_woocommerce', $this->pcfme_plugin_options, array($this, 'plugin_options_page'));
	}
	
	
	public function plugin_options_page() {
	    global $woocommerce;



		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'pcfme_additional_settings';
		global $billing_fields;
		$billing_fields = '';
		?>
		<div class="wrap">
		    <?php $this->plugin_options_tabs(); ?>
		
			<form method="post" class="<?php echo $tab; ?>" action="options.php">
				<?php wp_nonce_field( 'update-options' ); ?>
				<?php settings_fields( $tab ); ?>
				
                
				<?php do_settings_sections( $tab ); ?>
				
				
				
				<center><input type="submit" name="submit" id="submit" class="btn btn-success" value="<?php echo esc_html__('Save Changes','customize-my-account-for-woocommerce'); ?>"></center>
				
				
				<?php 

				



				?> 
				<div class="modal fade" id="pcfme_example_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">

							<div class="modal-body">




										
								<div class="form-group">


									<?php pcfme_show_modal_popup("pcfme-field-type"); ?>
								</div>

								<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo esc_html__( 'Close' ,'customize-my-account-for-woocommerce'); ?></button>

								<a href="#" mnkey="" mselected="" id="pcfme_modal_popup_select_button" class="btn btn-primary pcfme_new_select"><?php echo esc_html__( 'Select' ,'customize-my-account-for-woocommerce'); ?>

							    </a>
							

						    </div>
						<div class="modal-footer">

						</div>
					    </div>
				    </div>
			    </div>


			</form>
			<div class="modal fade" id="pcfme_example_modal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">

						<div class="modal-body">

							<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="add_field_form_response" >			


								<input type="hidden" name="action" value="add_field_form_response">
								<input type="hidden" name="pcfme_add_field_nonce" value="<?php echo wp_create_nonce( 'pcfme_nonce_hidden' ); ?>" />	



								<input  required id="sdfsd-user_meta_key" type="text" name="<?php echo "nds"; ?>[label]" value="" placeholder="<?php echo esc_html__('Enter Label','customize-my-account-for-woocommerce'); ?>" />
								<input type="hidden" nonce="<?php echo wp_create_nonce( 'pcfme_nonce_hidden' ); ?>" name="<?php echo "nds"; ?>[section]" id="pcfme_hidden_field_section" value="">
								<input type="hidden" id="pcfme_hidden_field_type" nonce="<?php echo wp_create_nonce( 'pcfme_nonce_hidden' ); ?>" name="<?php echo "nds"; ?>[field_type]" id="pcfme_hidden_field_type" value="text">
								

								<div class="form-group">


									<?php pcfme_show_modal_popup("pcfme-field-type-new"); ?>
								</div>


								

								<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo esc_html__( 'Close' ,'customize-my-account-for-woocommerce'); ?></button>
								
								

								<button type="submit" id="pcfme_new_field_etype" etype="" name="submit"  class="btn btn-primary wcmamtx_new_end_point"><?php echo esc_html__( 'Add New Field' ,'customize-my-account-for-woocommerce'); ?>

                                </button>
                            </form>

                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="pcfme_example_modal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">

						<div class="modal-body">

							<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="add_fees_form_response" >			


								<input type="hidden" name="action" value="add_fees_form_response">
								<input type="hidden" name="pcfme_add_fees_nonce" value="<?php echo wp_create_nonce( 'pcfme_nonce_hidden_fees' ); ?>" />	
                                
                                <div class="form-group">
									<label><?php echo esc_html__( 'Rule Type' ,'customize-my-account-for-woocommerce'); ?></label>
									<select  nonce="<?php echo wp_create_nonce( 'pcfme_nonce_hidden_fees' ); ?>" required id="sdfsd-user_meta_key" type="text" name="<?php echo "nds2"; ?>[rule_type]" >
										
										<?php
                                        $rule_types= pcfme_easy_checkout_get_fees_type();

                                        foreach ($rule_types as $key=>$value) { ?>
                                               <option value="<?php echo $value['value']; ?>">
                                               	<?php echo $value['text']; ?>
                                               		
                                               	</option>
                                        <?php 
                                        }
										?>
		                            </select>
									         
								</div>
								
								

								<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo esc_html__( 'Close' ,'customize-my-account-for-woocommerce'); ?></button>
								


								<button type="submit" id="pcfme_new_field_etype" etype="" name="submit"  class="btn btn-primary wcmamtx_new_end_point"><?php echo esc_html__( 'Add New Rule' ,'customize-my-account-for-woocommerce'); ?>

                                </button>
                            </form>

                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>
			<br />
			
		</div>
		<div id="responsediv">
		</div>
		<?php
	}

    public function display_field_label($key,$field) {
		    if (isset($field['label'])) { 
			         $label = $field['label']; 
			    }  else {
					switch ($key) {
                        case "billing_address_1":
						case "shipping_address_1":
                          $label = esc_html__('Address','customize-my-account-for-woocommerce');
                        break;
                        case "billing_address_2":
						case "shipping_address_2":
                          $label = esc_html__('Address 2','customize-my-account-for-woocommerce');;
                        break;
                        
						case "billing_city":
						case "shipping_city":
                          $label = esc_html__('Town / City','customize-my-account-for-woocommerce');
                        break;
						
						case "billing_state":
						case "shipping_state":
                          $label = esc_html__('State / County','customize-my-account-for-woocommerce');
                        break;
						
						case "billing_postcode":
						case "shipping_postcode":
                          $label = esc_html__('Postcode / Zip','customize-my-account-for-woocommerce');
                        break;
						
						
						
                        default:
                          $label = $key;
                        }
				}
			
			return $label;
	}


	public function display_field_type($key,$field) {
		    if (isset($field['type']) && ($field['type'] == "pcfmeselect") ) { 
			    $type = 'select'; 
			} else {
				$type = isset( $field['type']) ?  $field['type'] : "text";

				
			} 
			
			return '<span class="pcfme_ucfirst_class">'.ucfirst($type).'</span>';
	}

	public function display_visual_preview($key,$field,$noticerowno) { 
        global $woocommerce;
     
		?>
	 
		<td width="30%">
			<a class="accordion-toggle pcfme_edit_icon_a" data-toggle="collapse" data-parent="#accordion" href="#pcfme<?php echo $noticerowno; ?>">
				<label class="pcfme_field_heading_lable pcfme_field_heading_lable_<?php echo $key; ?>">
					<?php echo $this->display_field_label($key,$field); ?>
				</label>
			</a>
		</td>
	    <td width="30%">
	  	  <?php echo $this->display_field_type($key,$field); ?>
	    </td>
	 
	<?php 
    
    }
	
	
	public function show_fields_form($fields,$key,$field,$noticerowno,$slug,$required_slugs,$core_fields,$country_fields,$address2_field) { ?>
	      <?php
		    
            if (isset($field['width'])) {
                 
                $fieldwidth= $field['width'];
               	 
            } elseif (isset($field['class'])) {
                  
                foreach($field['class'] as $class) {
               	  	if (isset($class)) {
                        switch($class) {
                    	    case "form-row-first":
                                $fieldwidth='form-row-first';
						    break;

                    	    case "form-row-last":
                                $fieldwidth='form-row-last';
						    break;

                    	    default:
                    	        $fieldwidth='form-row-wide';
                    	}
                    }
               	} 
            }
	    
	    global $wp_roles;

        if ( ! isset( $wp_roles ) ) { 
    	    $wp_roles = new WP_Roles();  
        }
	
	    $roles = $wp_roles->roles;
        $shipping_methods = WC()->shipping->get_shipping_methods();

	    $payment_gateways = WC()->payment_gateways->get_available_payment_gateways();

	
		$catargs = array(
	      'orderby'                  => 'name',
	      'taxonomy'                 => 'product_cat',
	      'hide_empty'               => 0
	    );
		 
	  
		$categories           = get_categories( $catargs );  

      
		if (!empty($field['category'])) {
		       $chosencategories = implode(',', $field['category']); 
		} else { 
			   $chosencategories=''; 
		}

		if (!empty($field['role'])) {
		       $chosenroles = implode(',', $field['role']); 
		} else { 
			   $chosenroles=''; 
		}
			 
        switch($slug) {
		
		  case "pcfme_billing_settings":
		    $headlingtext  =''.esc_html__('billing_field_','customize-my-account-for-woocommerce').''.$noticerowno.'';
		    $mntext        ='billing';
		   break;
	
          case "pcfme_shipping_settings":
		    $headlingtext =''.esc_html__('shipping_field_','customize-my-account-for-woocommerce').''.$noticerowno.'';
		    $mntext       ='shipping';
		   break;

		   case "pcfme_additional_settings":
		     $headlingtext =''.esc_html__('additional_field_','customize-my-account-for-woocommerce').''.$noticerowno.'';
		     $mntext       ='additional';
		   break;
		
		
	       } ?>   

       
	   <div class="panel-group panel panel-default pcfme_list_item" id="pcfme_list_items_<?php echo $noticerowno; ?>" style="display:block;">
           <div class="panel-heading"> 
		
	     <table class="heading-table <?php echo $key; ?>_panel <?php if (isset($field['hide']) && ($field['hide'] == 1)) { echo "pcfme_disabled";} ?>">
	     	<tr>
	     		<td>
	     			<?php if (preg_match('/\b'.$key.'\b/', $core_fields )) { ?>
	     				<input type="checkbox" class="pcfme_accordion_onoff" parentkey="<?php echo $key; ?>" <?php if (!isset($field['hide']) || ($field['hide'] == 0)) { echo "checked";} ?>>
	     				<input type="hidden" class="<?php echo $key; ?>_hidden_checkbox" name="<?php echo $slug; ?>[<?php echo $key; ?>][hide]" value="<?php if (isset($field['hide'])) { echo ($field['hide']); } else { echo 0; } ?>" checked>
	     			<?php } else { ?>
                        <span class="glyphicon glyphicon-trash pcfme_trash_icon"></span>
	     			<?php } ?>


	     			<a class="accordion-toggle pcfme_edit_icon_a" data-toggle="collapse" data-parent="#accordion" href="#pcfme<?php echo $noticerowno; ?>">
	     				<span class="glyphicon glyphicon-edit pcfme_edit_icon"></span>
	     			</a>

	     			<span title="<?php echo esc_html__('Clone this field','customize-my-account-for-woocommerce'); ?>" rowno="<?php echo $noticerowno; ?>" pkey="<?php echo $key; ?>" mslug="<?php echo $slug; ?>" class="dashicons dashicons-admin-page pcfme_duplicate_field"></span>
	     		</td>

	     		<?php $this->display_visual_preview($key,$field,$noticerowno); ?>

	     		
	     	</tr>
		  </table>
           </div>
           <div id="pcfme<?php echo $noticerowno; ?>" class="panel-collapse collapse">

		     <table class="table"> 
			 
			 

		     <tr class="pcfme_field_key_tr">
			    <td width="15%"><label for="<?php echo $key; ?>_type"><?php echo esc_html__('Field Key','customize-my-account-for-woocommerce'); ?></label></td>
			    <td width="85%" class="pcfme_field_key_tr">
			    	<?php 
                        if (isset($field['field_key']) && ($field['field_key'] != "")) { 
                         	$field_key = $field['field_key'];
                        } else { 
                        	$field_key = $key;
                        }
			    	?>
			    	<?php if (!preg_match('/\b'.$key.'\b/', $core_fields )) { ?>   
			    	    <input type="text" class="pcfme_change_key_input" clkey="<?php echo $key; ?>" name="<?php echo $slug; ?>[<?php echo $key; ?>][field_key]" value="<?php echo $field_key ?>">
			    	<?php } ?>

			   	    <span class="pcfme_field_key pcfme_field_key_<?php echo $key; ?>"><?php echo $field_key ?></span>
			   	    <span onclick="pcfme_copyToClipboard('.pcfme_copy_key_icon_<?php echo $key; ?>')" cpkey="<?php echo $field_key; ?>" title="<?php echo esc_html__('Copy to clipboard','customize-my-account-for-woocommerce'); ?>" class="glyphicon glyphicon-book pcfme_copy_key_icon pcfme_copy_key_icon_<?php echo $key; ?> "></span>

			   	</td>
		     </tr> 

			 <?php if (!preg_match('/\b'.$key.'\b/', $country_fields )) { ?>   
		       <tr>
	           <td width="15%"><label for="<?php echo $key; ?>_type"><?php echo esc_html__('Field Type','customize-my-account-for-woocommerce'); ?></label></td>
		       <td width="85%">
		          <select id="checkout_field_type_<?php echo $key; ?>" class="checkout_field_type" name="<?php echo $slug; ?>[<?php echo $key; ?>][type]" >

		          	<?php
                        $field_types = pcfme_easy_checkout_get_field_types();

                        foreach ($field_types as $field_key=>$field_value) { ?>
                            <option value="<?php echo $field_value['type']; ?>" <?php if (isset($field['type']) && ($field['type'] == $field_value['type'])) { echo "selected";} ?> ><?php echo $field_value['text']; ?></option>
                        <?php }
		          	?>
			        
					
			       </select>
			        <a href="#" mnkey="<?php echo $key; ?>" class="pcfme-btn btn btn-primary browse-fields">
						<i class="pcfme-icon-browse fa fa-bars"></i>
						<?php echo esc_html__('Browse Fields','customize-my-account-for-woocommerce'); ?>
					</a>
		       </td>
	           </tr>
	           <?php 
                } 

                do_action('pcfme_after_field_type_content',$key,$field); 
               
               if (!preg_match('/\b'.$key.'\b/', $address2_field )) { ?>
			   <tr>
                <td width="15%"><label for="<?php echo $key; ?>_label"><?php  echo esc_html__('Label','customize-my-account-for-woocommerce'); ?></label></td>
	            <td width="85%">
	            	<input type="text" clkey="<?php echo $key; ?>" class="pcfme_label_input" name="<?php echo $slug; ?>[<?php echo $key; ?>][label]" value="<?php 
	                if (isset($field['label']) && ($field['label'] != '')) { 
	            	    echo $field['label']; 

	            	    $cpm_lable = $field['label'];
	            	} elseif ($key == "order_comments") {
                        echo esc_html__('Order notes','customize-my-account-for-woocommerce');
	            	} else { 
	            		echo $headlingtext; 

	            		 $cpm_lable = $headlingtext;

	            	} ?>" size="100"></td>
               </tr>
			   <?php }  ?>
			
			   
			   


            <?php if (isset($field['type']) && ($field['type'] == "hidden_field")) {  ?>

				<tr>
					<td width="15%">
						<label for="<?php echo $key; ?>_charlimit"><?php echo esc_html__('Default Value','customize-my-account-for-woocommerce'); ?></label>
					</td>
					<td width="85%">
						<?php $hidden_default = isset($field['hidden_default']) ? $field['hidden_default'] : "Yes"; ?>
						<input type="text" name="<?php echo $slug; ?>[<?php echo $key; ?>][hidden_default]" value="<?php echo $hidden_default; ?>">
					</td>
				</tr>	

			<?php } ?>


			<?php if (isset($field['type']) && (($field['type'] == "datepicker") || ($field['type'] == "timepicker") || ($field['type'] == "datetimepicker") )) {  ?>

				<tr>
					<td width="15%">
						<label for="<?php echo $key; ?>_charlimit"><?php echo esc_html__('Default Date','customize-my-account-for-woocommerce'); ?></label>
					</td>
					<td width="85%">
						<input type="checkbox" class="" name="<?php echo $slug; ?>[<?php echo $key; ?>][enable_default_date]" value="1" <?php if (isset($field['enable_default_date']) && ($field['enable_default_date'] == 1)) { echo "checked";} ?>>
						<?php echo esc_html__('Today plus','customize-my-account-for-woocommerce'); ?>
						<?php $default_date_add = isset($field['default_date_add']) ? $field['default_date_add'] : 0; ?>
						&emsp;<input type="number" class="pcfme_default_date_input" name="<?php echo $slug; ?>[<?php echo $key; ?>][default_date_add]" value="<?php echo $default_date_add; ?>" size="20">
						<?php echo esc_html__('Days','customize-my-account-for-woocommerce'); ?>
					</td>
				</tr>

				<tr>
					<td width="15%">
						<label for="<?php echo $key; ?>_charlimit"><?php echo esc_html__('Default Time','customize-my-account-for-woocommerce'); ?></label>
					</td>
					<td width="85%">
						<input type="checkbox" class="" name="<?php echo $slug; ?>[<?php echo $key; ?>][enable_default_time]" value="1" <?php if (isset($field['enable_default_time']) && ($field['enable_default_time'] == 1)) { echo "checked";} ?>>
						
						<?php $default_time = isset($field['default_time']) ? $field['default_time'] : "08:00"; ?>
						&emsp;<input type="text" class="pcfme_default_date_input" name="<?php echo $slug; ?>[<?php echo $key; ?>][default_time]" value="<?php echo $default_time; ?>" size="20">
						<?php echo esc_html__('Format 24 hour','customize-my-account-for-woocommerce'); ?>
					</td>
				</tr>

				<tr>
					<td width="15%">
						<label for="<?php echo $key; ?>_charlimit"><?php echo esc_html__('Disable Specific Dates','customize-my-account-for-woocommerce'); ?></label>
					</td>
					<td width="85%">
						
						
						<?php $disable_specific_dates = isset($field['disable_specific_dates']) ? $field['disable_specific_dates'] : ""; ?>
						<input type="text" class="pcfme_disable_dates_input" name="<?php echo $slug; ?>[<?php echo $key; ?>][disable_specific_dates]" value="<?php echo $disable_specific_dates; ?>">

						<p><?php echo esc_html__('Enter comma separated list of dates in d.m.Y format only you want to disable like 01.01.2024,02.01.2024,03.01.2024,04.01.2024 ','customize-my-account-for-woocommerce'); ?></p>
						
					</td>
				</tr>


				<tr>
					<td width="15%">
						<label for="<?php echo $key; ?>_charlimit"><?php echo esc_html__('Allowed Times','customize-my-account-for-woocommerce'); ?></label>
					</td>
					<td width="85%">
						
						
						<?php 

						$pcfme_extra_settings   = (array) get_option('pcfme_extra_settings');

						$allowed_times_global   = isset($pcfme_extra_settings['allowed_times']) ? $pcfme_extra_settings['allowed_times'] : ""; 

						$allowed_times = isset($field['allowed_times']) ? $field['allowed_times'] : $allowed_times_global; 

						?>
						
						<input type="text" class="pcfme_allowed_times_input" name="<?php echo $slug; ?>[<?php echo $key; ?>][allowed_times]" value="<?php echo $allowed_times; ?>">

						<p><?php echo esc_html__('Enter values separated by comma(,) for example 11:00,11:30,12:00 Leave blank to show all.','customize-my-account-for-woocommerce'); ?></p>
						
					</td>
				</tr>			

			<?php } ?>

			<?php if (isset($field['type']) && ($field['type'] == "textarea")) {  ?>

				<tr>
					<td width="15%">
						<label for="<?php echo $key; ?>_charlimit"><?php echo esc_html__('Max Characters Allowed','customize-my-account-for-woocommerce'); ?></label>
					</td>
					<td width="85%">
						<?php $charlimit = isset($field['charlimit']) ? $field['charlimit'] : 200; ?>
						<input type="number" name="<?php echo $slug; ?>[<?php echo $key; ?>][charlimit]" value="<?php echo $charlimit; ?>">
					</td>
				</tr>	

			<?php } ?>

			    <?php if (isset($field['type']) && ($field['type'] == "checkbox")) {  ?>

				<tr>
					<td width="15%"><label for="<?php echo $key; ?>_required"><?php  echo esc_html__('Checked by default','customize-my-account-for-woocommerce'); ?></label></td>
					<td width="85%">
						<input type="checkbox" data-toggle="toggle" data-size="mini" data-on="<?php  echo esc_html__('Yes','customize-my-account-for-woocommerce'); ?>" data-off="<?php  echo esc_html__('No','customize-my-account-for-woocommerce'); ?>" name="<?php echo $slug; ?>[<?php echo $key; ?>][checked_by_default]" <?php if (isset($field['checked_by_default']) && ($field['checked_by_default'] == 1)) { echo "checked";} ?> value="1">
					</td>
				</tr>

				<?php } ?>
			   
			   <?php if (!preg_match('/\b'.$key.'\b/', $required_slugs )) { ?>
		       <tr>
                <td width="15%"><label for="<?php echo $key; ?>_required"><?php  echo esc_html__('Required','customize-my-account-for-woocommerce'); ?></label></td>
                <td width="85%">
                	<input type="checkbox" data-toggle="toggle" data-size="mini" data-on="<?php  echo esc_html__('Yes','customize-my-account-for-woocommerce'); ?>" data-off="<?php  echo esc_html__('No','customize-my-account-for-woocommerce'); ?>" name="<?php echo $slug; ?>[<?php echo $key; ?>][required]" <?php if (isset($field['required']) && ($field['required'] == 1)) { echo "checked";} ?> value="1"></td>
			   </tr>
			   <?php } ?>
			   
			   <tr>
                <td width="15%"><label for="<?php echo $key; ?>_required"><?php  echo esc_html__('Dashboard notice','customize-my-account-for-woocommerce'); ?></label></td>
                <td width="85%">
                	<input type="checkbox" data-toggle="toggle" class="wcmamtx_dash_notice_toggle" data-size="mini" data-on="<?php  echo esc_html__('Yes','customize-my-account-for-woocommerce'); ?>" data-off="<?php  echo esc_html__('No','customize-my-account-for-woocommerce'); ?>" name="<?php echo $slug; ?>[<?php echo $key; ?>][dashboard_notice]" <?php if (isset($field['dashboard_notice']) && ($field['dashboard_notice'] == 1)) { echo "checked";} ?> value="1">

                    <p><?php  echo esc_html__('if set yes customer will see notice on dashboard unless they enter it','customize-my-account-for-woocommerce'); ?></p>

                </td>
			   </tr>


			   <tr class="wcmamtx_dash_notice_tr" style="<?php if (isset($field['dashboard_notice']) && ($field['dashboard_notice'] == 1)) { echo "display:table-row;";} else { echo 'display:none;'; } ?>">
			   	<?php 

			   			$default_dash_notice = ''.__( 'Kindly Enter required details <a href="{edit_account_link}">'.$cpm_lable.'</a>', 'customize-my-account-for-woocommerce' ).'';

			   			if (isset($field['dash_notice_text']) && ($field['dash_notice_text'] != "")) { 
			   				$ds_text_default = $field['dash_notice_text']; 
			   			} else {
			   				$ds_text_default =  $default_dash_notice;
			   			}
			   			?>
			   	<td width="15%"><label for="<?php echo $key; ?>_required"><?php  echo esc_html__('Dashboard Notice Text','customize-my-account-for-woocommerce'); ?></label></td>
			   	<td width="85%">
			   		<textarea name="<?php echo $slug; ?>[<?php echo $key; ?>][dash_notice_text]" rows="4" cols="70"><?php echo $ds_text_default; ?></textarea>

			   		<p><?php  echo esc_html__('{edit_account_link} - your edit account page link','customize-my-account-for-woocommerce'); ?></p>

			   	</td>
			   </tr>
			   
			   
			   <tr>
                <td width="15%"><label for="<?php echo $key; ?>_label"><?php  echo esc_html__('Placeholder ','customize-my-account-for-woocommerce'); ?></label></td>
	            <td width="85%"><input type="text" name="<?php echo $slug; ?>[<?php echo $key; ?>][placeholder]" value="<?php if (isset($field['placeholder'])) { echo $field['placeholder']; } ?>" size="35"></td>
               </tr>
			   
                <tr class="add-field-extraclass" style="">
               	    <td width="15%">
               		    <label for="<?php echo $key; ?>_extraclass"><?php echo esc_html__('Extra Class','customize-my-account-for-woocommerce'); ?></label>
               	    </td>
               	    <td width="85%">
               		    <input type="text" class="pcfme_checkout_field_extraclass" name="<?php echo $slug; ?>[<?php echo $key; ?>][extraclass]" value="<?php if (isset($field['extraclass'])) { echo $field['extraclass']; } ?>" size="35">
               		    <?php echo esc_html__('Use space key or comma to separate class','customize-my-account-for-woocommerce'); ?>
               	    </td>
                </tr>

                <?php if ($key != 'order_comments') { ?>



               	<tr class="pcfme_field_options_tr" style="<?php if (isset($field['type']) && (($field['type'] == "pcfmeselect") || ($field['type'] == "multiselect") || ($field['type'] == "radio"))) { echo "display:table-row;";} else { echo 'display:none;'; } ?>">
               		<td width="15%">
               			<label for="<?php echo $key; ?>_options"><?php echo esc_html__('Options','customize-my-account-for-woocommerce'); ?></label>
               		</td>
               		<td width="85%">
               			

               			<?php
                             $old_options = isset($field['options']) ? $field['options'] : '';
                             
                             if (!is_array($old_options)) {
                             	$old_options = explode(',', $old_options);
                             }
                             

                             $old_options_array = array();

                             

                             if (isset($old_options) && !empty($old_options) && (sizeof($old_options) > 0)) {
                             	$old_options_array_index = 1;
                             	foreach($old_options as $ovalue) {
                             		$old_options_array[''.$old_options_array_index.''] = array('value'=>$ovalue,'text'=>$ovalue);
                                    $old_options_array_index++;
                             	}
                             }

                             $new_options_array = isset($field['new_options']) ? $field['new_options'] : $old_options_array;
                             ?>
                             <table class="table pcfme_sortable_table_heading" >
                             	<tr>
                             		<th ></td>
                             		<th ><?php echo esc_html__('Value','customize-my-account-for-woocommerce'); ?></td>
                             		<th ><?php echo esc_html__('Text','customize-my-account-for-woocommerce'); ?></td>
                             	</tr>
                             </table>
                             <table class="table pcfme_sortable_table_options pcfme_sortable_table_options_<?php echo $key; ?>" >
                             	<tbody>
                             <?php

                             $new_options_array_index = 1;
                             foreach ($new_options_array as $opkey=>$opvalue) { 
                                

                             	?>
                             	
                                 <tr class="pcfme_sortable_tr">
                                 	<td class="pcfme_sortable_td_first">
                                 		<span class="pcfme_sortable_tr_handle dashicons dashicons-menu"></span>
                                 	</td>
                             		<td>
                             			<input class="pcfme_text_input_<?php echo $key; ?>_<?php echo $new_options_array_index; ?>" type="text" name="<?php echo $slug; ?>[<?php echo $key; ?>][new_options][<?php echo $new_options_array_index; ?>][value]" value="<?php echo $opvalue['value']; ?>" >
                             		</td>
                             		<td>
                             			<input type="text" class="pcfme_value_input" keyno="<?php echo $key; ?>" mnindex="<?php echo $new_options_array_index; ?>" name="<?php echo $slug; ?>[<?php echo $key; ?>][new_options][<?php echo $new_options_array_index; ?>][text]" value="<?php echo $opvalue['text']; ?>" >

                             			<span class="pcfme_remove_icon dashicons dashicons-remove"></span>
                             		</td>
                                 </tr>
                                
                             <?php 
                                $new_options_array_index++;
                             }
                             ?>
                             </tbody>
                             </table>
                             <table class="table pcfme_sortable_table_buttons" >
                             <tr>
                             	<td></td>
                             	<td></td>
                             	<td>
                             		<button type="button" mnindex="<?php echo $new_options_array_index; ?>" mntype="<?php if (isset($mntext)) { echo $mntext; } ?>" keyno="<?php echo $key; ?>" class="btn button-primary add-option-button" >
                             			<span class="pcfme-dashicons dashicons dashicons-insert"></span><?php echo esc_html__('Add Option','customize-my-account-for-woocommerce'); ?>
                             		</button>
                             	</td>
                             	
                             </tr>
                             </table>
                             <?php
               			?>

               		</td>
               	</tr>
               <?php } ?>

               <?php if (isset($field['type']) && (($field['type'] == "pcfmeselect") || ($field['type'] == "radio"))) {  ?>

               	    <?php if (isset($field['options']) || isset($field['new_options'])) {  ?>

               	    	<tr>
               	    		<td width="15%">
               	    			<label for="<?php echo $key; ?>_default_option"><?php echo esc_html__('Default Option','customize-my-account-for-woocommerce'); ?></label>
               	    		</td>
               	    		<td width="85%">
               	    			<?php 
               	    			   

               	    			    ?>

               	    			    <select class="pcfme_default_option_select" name="<?php echo $slug; ?>[<?php echo $key; ?>][default_option]">
               	    			    	    <option  selected="true">
               	    			    	    	<?php echo esc_html__('Choose Default Option','customize-my-account-for-woocommerce'); ?>
               	    			    	    		
               	    			    	    </option>
                                            <?php

                                            $old_options = isset($field['options']) ? $field['options'] : '';

                                            $old_options = explode(',', $old_options);

                                            $old_options_array = array();



                                            if (isset($old_options) && !empty($old_options) && (sizeof($old_options) > 0)) {
                                            	$old_options_array_index = 1;
                                            	foreach($old_options as $ovalue) {
                                            		$old_options_array[''.$old_options_array_index.''] = array('value'=>$ovalue,'text'=>$ovalue);
                                            		$old_options_array_index++;
                                            	}
                                            }

                                            $new_options_array = isset($field['new_options']) ? $field['new_options'] : $old_options_array;

                                            foreach($new_options_array as $skey=>$svalue) { 
                                            	$skey  = $svalue['value'];
                                            	$stext  = $svalue['text'];
                                            	?>

                                            	<option value="<?php echo $skey; ?>"<?php if (isset($field['default_option']) &&  ($field['default_option'] == $skey)) { echo 'selected';} ?>>
                                            		<?php echo $stext; ?>

                                            	</option>

                                            <?php } ?>
                       
               	    			    </select>
               	    	        
               	           </td>
               	        </tr>

               	   <?php } ?>

               <?php  } ?>
			   
		
			   <?php 
			   $validatearray='';
			   
			    if (isset($field['validate'])) {
			        foreach ($field['validate'] as $z=>$value) {
			          $validatearray.=''.$value.',';
			        } 
			       
				   $validatearray=substr_replace($validatearray, "", -1);
			    }
			  
			   
			   ?>
			   <tr>
                <td width="15%"><label><?php  echo esc_html__('Visibility','customize-my-account-for-woocommerce'); ?></label></td>
	            <td width="85%">
		            <select class="checkout_field_visibility" name="<?php echo $slug; ?>[<?php echo $key; ?>][visibility]" >

		            	<?php
                        $visibility_types = pcfme_easy_checkout_get_visibility_types();

                        

                        foreach ($visibility_types as $visibility_key=>$visibility_value) { ?>
                            <option value="<?php echo $visibility_value['value']; ?>" <?php if (isset($field['visibility']) && ($field['visibility'] == $visibility_value['value'])) { echo "selected";} ?> ><?php echo $visibility_value['text']; ?></option>
                        <?php }
		          	    ?>
		                

			       </select>
			       <input type="checkbox" data-width="200" data-height="25" data-onstyle="success" data-offstyle="danger" class="pcfme_dynamically_visible_toggle" data-toggle="toggle" data-on="<?php  echo esc_html__('Dynamic Visibility On','customize-my-account-for-woocommerce'); ?>" data-off="<?php  echo esc_html__('Dynamic Visibility Off','customize-my-account-for-woocommerce'); ?>" <?php if (isset($field['visibility']) && ($field['visibility'] == "dynamically-visible")) { echo "checked";} ?> value="1">
		        </td>
	           </tr>



               <?php do_action('pcfme_after_visibility_content_tr',$slug,$key,$field); ?>


			    <tr class="checkout_field_shipping_tr" style="<?php if (isset($field['visibility']) && ($field['visibility'] == "shipping-specific" )) { echo "display:;"; } else { echo 'display:none;'; } ?>" >
			        <td width="15%">
                        <label><span class="pcfmeformfield"><?php echo esc_html__('Choose Shipping Method','customize-my-account-for-woocommerce'); ?></span></label>
	                </td>
			        <td width="85%">
			        	<select class="checkout_field_shipping_showhide" name="<?php echo $slug; ?>[<?php echo $key; ?>][shipping][showhide]" style="width:100px">
                            <option value="show" <?php if (isset($field['shipping']['showhide']) && ($field['shipping']['showhide'] != "hide")) { echo 'selected';}?>><?php echo esc_html__('show','customize-my-account-for-woocommerce'); ?></option>
				            <option value="hide" <?php if (isset($field['shipping']['showhide']) && ($field['shipping']['showhide'] == "hide")) { echo 'selected';}?>><?php echo esc_html__('hide','customize-my-account-for-woocommerce'); ?></option>
                        </select>&emsp;
                        <span><?php echo esc_html__('by','customize-my-account-for-woocommerce'); ?></span>&emsp;
			            <select class="checkout_field_shipping" data-placeholder="<?php echo esc_html__('Choose Shipping Method','customize-my-account-for-woocommerce'); ?>" name="<?php echo $slug; ?>[<?php echo $key; ?>][shipping][method]" style="width:600px">
                            <?php foreach ($shipping_methods as $rkey=>$rvalue) { ?>
				                <option value="<?php echo $rkey; ?>" <?php if (isset($field['shipping']['method']) && ($field['shipping']['method'] == $rkey)) { echo 'selected';}?>><?php echo $rkey; ?></option>
				            <?php } ?>
                        </select>
                    </td>
			    </tr>


			    <tr class="checkout_field_payment_tr" style="<?php if (isset($field['visibility']) && ($field['visibility'] == "payment-specific" )) { echo "display:;"; } else { echo 'display:none;'; } ?>" >
			        <td width="15%">
                        <label><span class="pcfmeformfield"><?php echo esc_html__('Choose Payment Gateway','customize-my-account-for-woocommerce'); ?></span></label>
	                </td>
			        <td width="85%">
			        	<select class="checkout_field_payment_showhide" name="<?php echo $slug; ?>[<?php echo $key; ?>][payment][showhide]" style="width:100px">
                            <option value="show" <?php if (isset($field['payment']['showhide']) && ($field['payment']['showhide'] != "hide")) { echo 'selected';}?>><?php echo esc_html__('show','customize-my-account-for-woocommerce'); ?></option>
				            <option value="hide" <?php if (isset($field['payment']['showhide']) && ($field['payment']['showhide'] == "hide")) { echo 'selected';}?>><?php echo esc_html__('hide','customize-my-account-for-woocommerce'); ?></option>
                        </select>&emsp;
                        <span><?php echo esc_html__('by','customize-my-account-for-woocommerce'); ?></span>&emsp;
			            <select class="checkout_field_payment" data-placeholder="<?php echo esc_html__('Choose Payment Gateway','customize-my-account-for-woocommerce'); ?>" name="<?php echo $slug; ?>[<?php echo $key; ?>][payment][gateway]" style="width:600px">
                            <?php foreach ($payment_gateways as $rkey=>$rvalue) { ?>
				                <option value="<?php echo $rkey; ?>" <?php if (isset($field['payment']['gateway']) && ($field['payment']['gateway'] == $rkey)) { echo 'selected';}?>><?php echo $rkey; ?></option>
				            <?php } ?>
                        </select>
                    </td>
			    </tr>



			    <tr class="checkout_field_dynamic_tr_new display_if_field_type_dynamic" style="<?php if (isset($field['visibility']) && ($field['visibility'] == "dynamically-visible" )) { echo "display:;"; } else { echo 'display:none;'; } ?>">
			        <td width="15%">
                        <label for="notice_category"><span class="pcfmeformfield">
                        	<?php echo esc_html__('Dynamic Visibility Rules','customize-my-account-for-woocommerce'); ?></span>
                        </label>
	                </td>
			        <td width="85%">
			        	<div class="dynamic_fields_div_wrapper dynamic_fields_div_wrapper_<?php echo $key; ?>">
			        		<?php $gtindex = 1; ?>

			        		<?php if (isset($field['dynamic_rules'])) { ?>
                            
                            <?php $gtindex = max(array_keys($field['dynamic_rules'])) ; ?>

                                <?php $gtindex++; ?>

                               
                               
			            	    <?php foreach ($field['dynamic_rules'] as $dynamickey=>$dynamicvalue) { ?>

			            	    	

			            	    		<?php pcfme_display_each_dynamic_row($dynamickey,$dynamicvalue,$key,$slug); ?>

			            	    	

			            	    	

			            	    <?php } ?>
			            	<?php } ?>
			        	</div>

			        	<select class="dynamic_visibility_criteria_select" name="<?php echo $slug; ?>[<?php echo $key; ?>][dynamic_visibility_criteria]">
			        		<option value="match_all" <?php if (isset($field['dynamic_visibility_criteria']) && ($field['dynamic_visibility_criteria']) == "match_all") { echo 'selected';} ?> ><?php echo esc_html__('Match All Rules','customize-my-account-for-woocommerce'); ?></span></option>
			        		<option value="match_any" <?php if (isset($field['dynamic_visibility_criteria']) && ($field['dynamic_visibility_criteria']) == "match_any") { echo 'selected';} ?>><?php echo esc_html__('Match any one rule','customize-my-account-for-woocommerce'); ?></span></option>
			        		
			        		<option value="disabled" <?php if (isset($field['dynamic_visibility_criteria']) && ($field['dynamic_visibility_criteria']) == "disabled") { echo 'selected';} ?>><?php echo esc_html__('Disable All Rules','customize-my-account-for-woocommerce'); ?></span></option>
			        	</select>
			        	<button type="button" gtindex="<?php echo $gtindex; ?>" mntype="<?php if (isset($mntext)) { echo $mntext; } ?>" keyno="<?php echo $key; ?>" class="btn button-primary add-rule-button" >
			            	<span class="pcfme-dashicons dashicons dashicons-insert"></span><?php echo esc_html__('Add Rule','customize-my-account-for-woocommerce'); ?>
			            </button>
			        </td>
			    </tr>

				<?php if (isset($field['conditional'])) $conditional_field = $field['conditional']; ?>

				<tr class="checkout_field_conditional_tr_new" style="">
			        <td width="15%">
                        <label for="notice_category"><span class="pcfmeformfield">
                        	<?php echo esc_html__('Field Conditional Rules','customize-my-account-for-woocommerce'); ?></span>
                        </label>
	                </td>
			        <td width="85%">
			            <div class="conditional_fields_div_wrapper conditional_fields_div_wrapper_<?php echo $key; ?>">
                            
                            <?php $mnindex = 1; ?>

                            <?php if (isset($field['conditional'])) { ?>
                            
                            <?php $mnindex = max(array_keys($field['conditional'])) ; ?>

                                <?php $mnindex++; ?>

			            	    <?php foreach ($field['conditional'] as $conditionalkey=>$conditionalvalue) { ?>

                                    

			            	        <div class="conditional-row conditional_row_<?php echo $conditionalkey; ?>_<?php echo $key; ?>">
			            		        <select class="checkout_field_conditional_showhide" name="pcfme_<?php echo $mntext; ?>_settings[<?php echo $key; ?>][conditional][<?php echo $conditionalkey; ?>][showhide]" >
			            			        <option value="open" <?php if (isset($conditionalvalue['showhide']) && ($conditionalvalue['showhide']) == "open") { echo 'selected';} ?> ><?php echo esc_html__('Show','customize-my-account-for-woocommerce'); ?></option>
			            			        <option value="hide" <?php if (isset($conditionalvalue['showhide']) && ($conditionalvalue['showhide']) == "hide") { echo 'selected';} ?>><?php echo esc_html__('Hide','customize-my-account-for-woocommerce'); ?></option>
			            		        </select>
			            		        <span class="pcfmeformfield">
			            		        	<strong>&emsp;<?php echo esc_html__('If value of','customize-my-account-for-woocommerce'); ?>&emsp;</strong>
			            		        </span>

			            		        <select mfield="<?php echo $key; ?>" mtype="<?php echo $mntext; ?>" mntext="<?php echo $conditionalkey; ?>" mnkey="<?php echo $key; ?>" class="checkout_field_conditional_parentfield" name="pcfme_<?php echo $mntext; ?>_settings[<?php echo $key; ?>][conditional][<?php echo $conditionalkey; ?>][parentfield]" >
                                            <?php foreach ($fields as $fkey=>$ffield) { ?>
                                            	<?php 
                                                    $nkey = isset($ffield['field_key']) ? $ffield['field_key'] : $fkey;
                                            	?>
                                            	<?php if ($key != $fkey) { ?>
                                                <option value="<?php echo $nkey; ?>" <?php if (isset($conditionalvalue['parentfield']) && ($conditionalvalue['parentfield']) == $fkey) { echo 'selected';} ?>>
                                        	        <?php if (isset($ffield['label'])) {
                                                        echo $ffield['label']; 
                                        	        } else { 
                                                        echo $nkey;
                                        	        }
                                        	    ?>
                                                <?php if (isset($ffield['type']) && (($ffield['type'] != ""))) {

                                                	    $field_type = $ffield['type'];

                                                	    switch($field_type) {
                                                	    	case "pcfmeselect":
                                                	    	   $field_type_text ='select';
                                                	    	break;

                                                	    	default:
                                                	    	   $field_type_text =$field_type;
                                                	    	break;
                                                	    }

                                                	    if ($field_type != "") {
                                                	    	echo '('.$field_type_text.')'; 
                                                	    }

                                                        
                                        	        }

                                        	    ?>
                                        	    

                                                </option>
                                            <?php } ?>
                                            <?php } ?>
			            			
			            		        </select>

			            		        <?php

			            		        $hidden_type = isset($conditionalvalue['hidden_type']) ? $conditionalvalue['hidden_type'] : "text";

			            		       

			            		        if ($hidden_type == "checkbox") {

			            		        	?>

			            		        	<span class="pcfmeformfield pcfmeformfield_equal_to">
			            		        		<strong><?php echo esc_html__('is checked','customize-my-account-for-woocommerce'); ?></strong>
			            		        	</span>

			            		        <?php

			            		        } else {

			            		        	?>

			            		        	<span class="pcfmeformfield pcfmeformfield_equal_to">
			            		        		<strong><?php echo esc_html__('is equal to','customize-my-account-for-woocommerce'); ?></strong>
			            		        	</span>

			            		        <?php }

			            		   

			            		        

			            		        $input_style= 'display:;';
			            		        $select_style= 'display:none;';

			            		        switch($hidden_type) {
			            		        	case "text":
			            		        	   $input_style= 'display:;';
			            		        	   $select_style= 'display:none;';
			            		        	break;

			            		        	case "select":
			            		        	   $input_style= 'display:none;';
			            		        	   $select_style= 'display:;';
			            		        	break;

			            		        	case "checkbox":
			            		        	   $input_style= 'display:none;';
			            		        	   $select_style= 'display:none;';
			            		        	break;

			            		        	default:
			            		        	   $input_style= 'display:;';
			            		        	   $select_style= 'display:none;';
			            		        	break;

			            		        	
			            		        }

			            		        if (isset($fields[''.$conditionalvalue['parentfield'].'']) && isset($fields[''.$conditionalvalue['parentfield'].'']['options'])) {
			            		        	$hidden_array = $fields[''.$conditionalvalue['parentfield'].'']['options'];

			            		        	$old_options = isset($hidden_array) ? $hidden_array : '';

			            		        	$old_options = explode(',', $old_options);

			            		        	$old_options_array = array();
			            		        }
                                        
                                        if (isset($fields[''.$conditionalvalue['parentfield'].'']['new_options'])) {
			            		        

			            		            $hidden_array_new = $fields[''.$conditionalvalue['parentfield'].'']['new_options'];

			            		        }

			            		        

			            		   

			            		        if (isset($old_options) && !empty($old_options) && (sizeof($old_options) > 0)) {
			            		        	$old_options_array_index = 1;
			            		        	foreach($old_options as $ovalue) {
			            		        		$old_options_array[''.$old_options_array_index.''] = array('value'=>$ovalue,'text'=>$ovalue);
			            		        		$old_options_array_index++;
			            		        	}
			            		        }

			            		        if (isset($hidden_array_new)) {
                                            $new_options_array = isset($hidden_array_new) ? $hidden_array_new : $old_options_array;
			            		        }

			            		        


			            		        ?>

			            		        <input style="display:none;" class="conditional_select_hidden_type" name="pcfme_<?php echo $mntext; ?>_settings[<?php echo $key; ?>][conditional][<?php echo $conditionalkey; ?>][hidden_type]" value="<?php echo $hidden_type; ?>">

			            		        

			            		        <input type="text" style="<?php echo $input_style; ?>" class="checkout_field_conditional_equalto" name="pcfme_<?php echo $mntext; ?>_settings[<?php echo $key; ?>][conditional][<?php echo $conditionalkey; ?>][equalto]" value="<?php if (isset($conditionalvalue['equalto'])) { echo $conditionalvalue['equalto']; } ?>">

			            		        <select style="<?php echo $select_style; ?>" class="checkout_field_conditional_select" name="pcfme_<?php echo $mntext; ?>_settings[<?php echo $key; ?>][conditional][<?php echo $conditionalkey; ?>][equalto]">
			            		        	<?php

			            		        	



			            		        	foreach ($new_options_array as $idkey=>$ldval) {

			            		        		

			            		        		?>

			            		        		<option value="<?php echo $ldval['value']; ?>" <?php if (isset($conditionalvalue['equalto']) && ($conditionalvalue['equalto'] == $ldval['value'])) {echo 'selected';} ?>>
			            		        			<?php echo $ldval['text']; ?>
			            		        				
			            		        		</option>

			            		        		<?php			            		        	
			            		        	}

			            		        	?>
			            		        </select>






			            		        <span class="dashicons dashicons-remove pcfme-remove-condition"></span>

			            	        </div>

			                    <?php  } ?>
			                
			                <?php } ?>
			            
			                </div>



			            <button type="button" mnindex="<?php echo $mnindex; ?>" mntype="<?php if (isset($mntext)) { echo $mntext; } ?>" keyno="<?php echo $key; ?>" class="btn button-primary add-condition-button" >
			            	<span class="pcfme-dashicons dashicons dashicons-insert"></span><?php echo esc_html__('Add Condition','customize-my-account-for-woocommerce'); ?>
			            </button>
                    </td>
			    </tr>

			 <?php if (($slug != 'pcfme_additional_settings') && ($key != 'order_comments')) { ?>
			   <tr>
                <td width="15%"><label for="<?php echo $key; ?>_label"><?php  echo esc_html__('Validate','customize-my-account-for-woocommerce'); ?></label></td>
	            <td width="85%">
		           <select name="<?php echo $slug; ?>[<?php echo $key; ?>][validate][]" class="row-validate-multiselect" multiple>
			         <option value="state" <?php if (preg_match('/\bstate\b/', $validatearray )) { echo 'selected'; } ?>><?php echo esc_html__('state','customize-my-account-for-woocommerce'); ?></option>
			         <option value="postcode" <?php if (preg_match('/\bpostcode\b/', $validatearray )) { echo 'selected'; } ?>><?php echo esc_html__('postcode','customize-my-account-for-woocommerce'); ?></option>
			         <option value="email" <?php if (preg_match('/\bemail\b/', $validatearray )) { echo 'selected'; } ?>><?php echo esc_html__('email','customize-my-account-for-woocommerce'); ?></option>
			         <option value="phone" <?php if (preg_match('/\bphone\b/', $validatearray )) { echo 'selected'; } ?>><?php echo esc_html__('phone','customize-my-account-for-woocommerce'); ?></option>
			       </select>
		        </td>
	           </tr>
			 <?php } ?>
			   

				 <?php do_action('pcfme_after_field_content_end',$key,$field); ?>
			   </table>

             </div>
			
          </div>
	<?php }
	
	
	

	

	public function plugin_options_tabs() {
		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->billing_settings_key;

		do_action('sysbasics_extra_button_admin');

		




        echo '<h2 class="nav-tab-wrapper">';
		foreach ( $this->pcfme_plugin_settings_tabs as $tab_key => $tab_caption ) {
			$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
			echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->pcfme_plugin_options . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';	
		}
		echo '</h2>';
	}
	
	public function show_new_form($fields,$slug,$country_fields) {
		
       
		

     	 
        $catargs = array(
	      'orderby'                  => 'name',
	      'taxonomy'                 => 'product_cat',
	      'hide_empty'               => 0
	     );
		 
	  
		$categories           = get_categories( $catargs ); 
		
	   
    }
	

}




new pcfme_add_settings_page_class();

?>