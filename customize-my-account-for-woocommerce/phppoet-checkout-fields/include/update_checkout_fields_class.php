<?php 


class pcfme_update_checkout_fields {

     private $billing_settings_key       = 'pcfme_billing_settings';
	 private $shipping_settings_key      = 'pcfme_shipping_settings';
	 private $additional_settings_key    = 'pcfme_additional_settings';
	 private $extra_settings_key         = 'pcfme_extra_settings';
     
	 public function __construct() {
	    
	    
	      add_filter('woocommerce_checkout_fields', array( $this, 'update_billing_fields' ) );
	      add_filter('woocommerce_checkout_fields', array( $this, 'update_shipping_fields' ) );
	      add_filter('woocommerce_checkout_fields', array( $this, 'update_order_notes_field' ) );

	      add_filter('woocommerce_default_address_fields', array( $this, 'update_core_billing_fields' ) );      
	      add_action('woocommerce_after_order_notes', array( $this, 'add_additional_fields' ) );
		  add_action('woocommerce_checkout_process', array( $this, 'validate_additional_required_fields'));
          add_action('woocommerce_checkout_update_customer',array( $this, 'update_user_meta_additional_fields'), 10, 2 );

	    
	 }

public function update_core_billing_fields($fields) {

    $billing_fields = (array) get_option( $this->billing_settings_key );
    
    
    

    if (isset($billing_fields) && ($billing_fields != '')) {
			
		$keyorder = 1;
		 
	    foreach ($billing_fields as $key=>$value) {

	    	$key = substr($key, 8);

	    	

            if (isset($fields) && (sizeof($fields) > 1)) {

            	
		  
		        foreach ($fields as $key2=>$value2)  {
		   
		                

		                
		                if ($key == $key2) {
                            
                           
		            	    if (isset($value['label'])) { 

				                $fields[$key2]['label'] = $value['label']; 
				            }

				            if (isset($value['width'])) { 

				            	

				                $fields[$key2]['class'][] = $value['width']; 



				            }

				            $new_classes =  $fields[$key2]['class'];

				            $first_last_match_index = 0;

				            foreach ($new_classes as $classkey=>$classvalue) {
				            	if (($classvalue == "form-row-last") || ($classvalue == "form-row-first")) {
				            		$first_last_match_index++;
				            	}
				            }



				            if ($first_last_match_index > 0) {
				            	 $form_row_key_wide = array_search('form-row-wide', $new_classes); 



				            	 unset($new_classes[$form_row_key_wide]);

				            	 $fields[$key2]['class'] = $new_classes;
				            }


				            

				            if (isset($value['default_option'])) { 

				                $fields[$key2]['default'] = $value['default_option']; 
				            }

				            if (isset($keyorder)) {
				            	$fields[$key2]['priority'] = $keyorder * 10;
				            }


				            
                        }
                    
                }
            }

            $keyorder++;

	    }

	    
	}

	

	return $fields;

}
	 

public function validate_additional_required_fields() {
		 
	$additional_fields = (array) get_option( $this->additional_settings_key );
	$additional_fields =  array_filter($additional_fields);
    $requiredtext      =  __('is a required field','customize-my-account-pro');
         

    if (isset($additional_fields) && (sizeof($additional_fields) >= 1)) {
         
        foreach ($additional_fields as $key=>$value) {
			
			if (isset($value['visibility'])) {
				
				$visibilityarray = $value['visibility'];
				 
				if (isset($value['products'])) { 
				    $allowedproducts = $value['products'];
				} else {
					$allowedproducts = array(); 
				}
				 
				if (isset($value['category'])) {
					$allowedcats = $value['category'];
				} else {
					$allowedcats = array();
				}

				if (isset($value['role'])) {
					$allowedroles = $value['role'];
				} else {
					$allowedroles = array();
				}

				if (isset($value['total-quantity'])) {
					$total_quantity = $value['total-quantity'];
				} else {
					$total_quantity = 0;
				}

				if (isset($value['specific-product'])) {
					$prd = $value['specific-product'];
				} else {
					$prd = 0;
				}

				if (isset($value['specific-quantity'])) {
					$prd_qnty = $value['specific-quantity'];
				} else {
					$prd_qnty = 0;
				}


				if (isset($value['dynamic_rules'])) { 
					$dynamic_rules = $value['dynamic_rules'];
				} else {
					$dynamic_rules = array(); 
				}


				if (isset($value['dynamic_visibility_criteria'])) { 
					$dynamic_criteria = $value['dynamic_visibility_criteria'];
				} else {
					$dynamic_criteria = 'match_all'; 
				}



                if (isset($value['multiple_clone']) && ($value['multiple_clone'])) { 
		        	$multiple_clone = "yes";
		        } else {
		        	$multiple_clone = "no"; 
		        }

				 
				$is_field_hidden = pcfme_check_if_field_is_hidden($visibilityarray,$allowedproducts,$allowedcats,$allowedroles,$total_quantity,$prd,$prd_qnty,$dynamic_rules,$dynamic_criteria);
				 
				if (((!isset($is_field_hidden)) || ($is_field_hidden != 0))  && ($multiple_clone != "yes")) {

					if (isset($value['required']) && ( ! $_POST[$key] )) {
				        $noticetext='<strong>'.$value['label'].'</strong> '.$requiredtext.'';
                        wc_add_notice( __( $noticetext ), 'error' );
                    }
				}


				if ($multiple_clone == "yes") {

					$multiclone_condition = isset($value['multiclone_condition']) ? $value['multiclone_condition'] : "each_quantity";

                    $multiclone_product   = isset($value['multiclone_product']) ? $value['multiclone_product'] : "";
					
					pcfmne_process_multiple_clone_notice($multiclone_condition,$multiclone_product,$value);
				}
			}
	    }
    }
}


/**
 * Update core billing fields
 * Params $fields - default fields
 * Package@WooMatrix
 */

public function update_order_notes_field($fields) {
    
    $extra_fields = (array) get_option( $this->extra_settings_key );
    
    if (isset($extra_fields['order_comments'])) {
    	$value        = $extra_fields['order_comments'];
    }
    



    //sort classes

    if (isset($value['extraclass']) && ($value['extraclass'] != '')) {
		       
		$tempclasses = explode(',',$value['extraclass']);
		      
		      
		$extraclass = array();
                      
        foreach($tempclasses as $classval1) {
    
            $extraclass[$classval1]  = $classval1;
      
        }
			 
    }



	//modify field label

	if (isset($value['label'])) { 

		$fields['order']['order_comments']['label'] = $value['label']; 
	}

    //modify width
	if (isset($value['width'])) { 
					       
		if (isset( $fields['order']['order_comments']['class'])) {

			foreach ($fields['order']['order_comments']['class'] as $classkey=>$classvalue) {

			    if ($classvalue == 'form-row-wide' || $classvalue == "form-row-first"  || $classvalue == "form-row-last") {
                                 
                    unset($fields['order']['order_comments']['class'][$classkey]);

			    }
  
		    }
		}
					       
		$fields['order']['order_comments']['class'][]=$value['width'];
	}

	//modify required params

	if (isset($value['required']) && ($value['required'] == 1)) { 
				     	
		$fields['order']['order_comments']['required'] = $value['required']; 

	}

	//modify clearfix

	if (isset($value['clear']) && ($value['clear'] == 1)) { 
                    	    
        $fields['order']['order_comments']['clear'] = $value['clear']; 

    } else {
                        
        $fields['order']['order_comments']['clear'] = false;
                    
    } 

    //modify placeholder
    if (isset($value['placeholder'])) { 

		$fields['order']['order_comments']['placeholder'] = $value['placeholder']; 

	}



	//modify class

	if (isset($extraclass) && ($extraclass != '')) {
                     
		foreach ($extraclass as $classval) {
			$fields['order']['order_comments']['class'][] = $classval;
		}
	}





	//hide checkbox

	if (isset($value['hide']) && ($value['hide'] == 1)) {
        unset($fields['order']['order_comments']);
        add_filter('woocommerce_enable_order_notes_field', '__return_false');
    }


    if (isset($value['visibility'])) {
				 
		$visibilityarray = $value['visibility'];
				 
		if (isset($value['products'])) { 
            $allowedproducts = $value['products'];
	    } else {
			$allowedproducts = array(); 
		}
				 
		if (isset($value['category'])) {
			$allowedcats = $value['category'];
		} else {
			$allowedcats = array();
		}

		if (isset($value['role'])) {
			$allowedroles = $value['role'];
		} else {
			$allowedroles = array();
		}


		if (isset($value['total-quantity'])) {
			$total_quantity = $value['total-quantity'];
		} else {
			$total_quantity = 0;
		}


		if (isset($value['specific-product'])) {
			$prd = $value['specific-product'];
		} else {
			$prd = 0;
		}

		if (isset($value['specific-quantity'])) {
			$prd_qnty = $value['specific-quantity'];
		} else {
			$prd_qnty = 0;
		}

		if (isset($value['dynamic_rules'])) { 
			$dynamic_rules = $value['dynamic_rules'];
		} else {
			$dynamic_rules = array(); 
		}


		if (isset($value['dynamic_visibility_criteria'])) { 
			$dynamic_criteria = $value['dynamic_visibility_criteria'];
		} else {
			$dynamic_criteria = 'match_all'; 
		}


		$is_field_hidden = pcfme_check_if_field_is_hidden($visibilityarray,$allowedproducts,$allowedcats,$allowedroles,$total_quantity,$prd,$prd_qnty,$dynamic_rules,$dynamic_criteria);


				 
		if (isset($is_field_hidden) && ($is_field_hidden != 1)) {
			unset($fields['order']['order_comments']);
			add_filter('woocommerce_enable_order_notes_field', '__return_false');
		}
	}

    return $fields;
}

/**
 * Update core billing fields
 * Params $fields - default fields
 * Package@WooMatrix
 */
public function update_billing_fields($fields) {
    global $post;
    


    //get plugin generated array of billing fields
    $plugin_fields = (array) get_option( 'pcfme_billing_settings' );

   

    if (isset($plugin_fields) && (sizeof($plugin_fields) > 1)) { 
    	$plugin_fields = $plugin_fields;
    } else {
    	$plugin_fields = $fields;

    	return $plugin_fields;
    }

   

    $slug          = 'billing';	
	  
    $new_fields = pcfme_update_fields_combined($fields,$plugin_fields,$slug);

    return $new_fields;

}  
	
	


public function update_shipping_fields($fields) {
	global $post;

	$plugin_fields = (array) get_option( $this->shipping_settings_key );

	$slug          = 'shipping';


	if (isset($plugin_fields) && (sizeof($plugin_fields) > 1)) { 
    	$plugin_fields = $plugin_fields;
    } else {
    	$plugin_fields = $fields;

    	return $plugin_fields;
    }
	     
    $new_fields = pcfme_update_fields_combined($fields,$plugin_fields,$slug);

    return $new_fields;

}

/**
 * Saves additional field data as customer checkouts
 * 
 */

public function update_user_meta_additional_fields( $customer, $data ) {

	if ( ! is_user_logged_in() || is_admin() )
        return;

    $additional_fields = (array) get_option( $this->additional_settings_key );
	$additional_fields =  array_filter($additional_fields);
         

    if (isset($additional_fields) && (sizeof($additional_fields) >= 1)) { 

        $keyorder = 1; 

        foreach ($additional_fields as $additionalkey=>$additionalvalue) {

        	  // Update user meta data
        	if ( isset($_POST[$additionalkey]) )
        		update_user_meta( $customer->get_id(), $additionalkey, $_POST[$additionalkey] );

        }

    }
}



public function add_additional_fields() {

    $additional_fields = (array) get_option( $this->additional_settings_key );
	$additional_fields =  array_filter($additional_fields);
    

    


    if (isset($additional_fields) && (sizeof($additional_fields) >= 1)) { 

        $keyorder = 1; 

        foreach ($additional_fields as $additionalkey=>$additionalvalue) {


		  
          
            $extrafield= array();

            if (isset($additionalvalue['label'])) {
                
                $extrafield['label'] = $additionalvalue['label'];

            }

            if (isset($additionalvalue['type'])) {
                
                $extrafield['type'] = $additionalvalue['type'];

            }
          
            if (isset($additionalvalue['width'])) {
		        
		        $extrafield['class'][] =$additionalvalue['width'];

		    }
            
			//hider/opener class
            if (isset($additionalvalue['visibility']) && ($additionalvalue['visibility'] == 'field-specific')) {
			    
			    if (isset($additionalvalue['conditional'])) {

			    	$pcfme_conditional_class  = pcfme_get_conditional_class($additionalvalue['conditional']);

			    }
			    
			 
		    } else {

			    $pcfme_conditional_class  = '';
            
            }	


            //hider/opener class
            if (isset($additionalvalue['visibility']) && ($additionalvalue['visibility'] == 'shipping-specific')) {
			    
			    if (isset($additionalvalue['shipping'])) {

			    	$pcfme_shipping_class  = pcfme_get_conditional_shipping_class($additionalvalue['shipping']);

			    }
			    
			 
		    } else {

			    $pcfme_shipping_class  = '';
            
            }


            //hider/opener class
            if (isset($additionalvalue['visibility']) && ($additionalvalue['visibility'] == 'payment-specific')) {
			    
			    if (isset($additionalvalue['payment'])) {

			    	$pcfme_payment_class  = pcfme_get_conditional_payment_class($additionalvalue['payment']);

			    }
			    
			 
		    } else {

			    $pcfme_payment_class  = '';
            
            }
               
			
			$extrafield['class'][] = $pcfme_conditional_class;	
			$extrafield['class'][] = $pcfme_shipping_class;	
			$extrafield['class'][] = $pcfme_payment_class;	


            if (isset($additionalvalue['required'])) {

                $extrafield['required'] = $additionalvalue['required'];

            }

            if (isset($additionalvalue['placeholder'])) {
                
                $extrafield['placeholder'] = $additionalvalue['placeholder'];

            }
			
			if (isset($keyorder)) {
                
                $extrafield['priority'] = $keyorder;

            }

            if (isset($additionalvalue['validate'])) {
                
                $extrafield['validate'] = $additionalvalue['validate'];

            }


            if (isset($additionalvalue['enable_default_date'])) {
                
                $extrafield['enable_default_date'] = $additionalvalue['enable_default_date'];

            }

            if (isset($additionalvalue['default_date_add'])) {
                
                $extrafield['default_date_add'] = $additionalvalue['default_date_add'];

            }


            if (isset($additionalvalue['disable_specific_dates'])) {
                
                $extrafield['disable_specific_dates'] = $additionalvalue['disable_specific_dates'];

            }



            if (isset($additionalvalue['allowed_times'])) {
                
                $extrafield['allowed_times'] = $additionalvalue['allowed_times'];

            }

            if (isset($additionalvalue['enable_default_time'])) {
                
                $extrafield['enable_default_time'] = $additionalvalue['enable_default_time'];

            }

            if (isset($additionalvalue['default_time'])) {
                
                $extrafield['default_time'] = $additionalvalue['default_time'];

            }


           
            if (isset($additionalvalue['new_options']) || isset($additionalvalue['options'])) {

            	$old_options = isset($additionalvalue['options']) ? $additionalvalue['options'] : '';

            	$old_options = explode(',', $old_options);

            	$old_options_array = array();



            	if (isset($old_options) && !empty($old_options) && (sizeof($old_options) > 0)) {
            		$old_options_array_index = 1;
            		foreach($old_options as $ovalue) {
            			$old_options_array[''.$old_options_array_index.''] = array('value'=>$ovalue,'text'=>$ovalue);
            			$old_options_array_index++;
            		}
            	}

            	$new_options_array = isset($additionalvalue['new_options']) ? $additionalvalue['new_options'] : $old_options_array;



		        $options = array();
                      
                    foreach($new_options_array as $nkey=>$val){
    
                        $o_value = $val['value'];
    					$o_text = $val['text'];

    					$options[$o_value]  = $o_text;
      
                    }
             
                $extrafield['options'] = $options;

            }
			
			
		    //builds extraclass array
		    if (isset($additionalvalue['extraclass']) && ($additionalvalue['extraclass'] != '')) {
		      
		        $tempclasses = explode(',',$additionalvalue['extraclass']);
		      
		      
		        $extraclass = array();
                      
                foreach($tempclasses as $classval3){
    
                    $extraclass[$classval3]  = $classval3;
      
                }
			 
		    }
			
			
			//adds extra classes to billing fields
			if (isset($extraclass) && ($extraclass != '')) {
                     
			    foreach ($extraclass as $additionalclassval) {
				    $extrafield['class'][] = $additionalclassval;
			    }
				
			}


			$pcfme_conditional_class = pcfme_get_visibility_class_combined($additionalvalue);


            
			if (isset($pcfme_conditional_class) && ($pcfme_conditional_class != '')) {
				$extrafield['class'][] = $pcfme_conditional_class;
			}

			
		   
		    
		    if (isset($additionalvalue['disable_past'])) {
             
			    $extrafield['disable_past'] = $additionalvalue['disable_past'];
            }

            if (isset($additionalvalue['hidden_default'])) {
             
			    $extrafield['hidden_default'] = $additionalvalue['hidden_default'];
            }

            if (isset($additionalvalue['charlimit'])) {
             
			    $extrafield['charlimit'] = $additionalvalue['charlimit'];
            }


            if (isset($additionalvalue['checked_by_default'])) {
             
			    $extrafield['checked_by_default'] = $additionalvalue['checked_by_default'];
            }


            if (isset($additionalvalue['dynamic_rules'])) { 
		        $extrafield['dynamic_rules']  = $additionalvalue['dynamic_rules'];
		    } else {
		        $extrafield['dynamic_rules'] = array(); 
		    }


		    if (isset($additionalvalue['dynamic_visibility_criteria'])) { 
		        $extrafield['dynamic_visibility_criteria']  = $additionalvalue['dynamic_visibility_criteria'];
		    } else {
		        $extrafield['dynamic_visibility_criteria'] = 'match_all'; 
		    }

		    
			
			
			$field_post_meta = get_user_meta( get_current_user_id(), $additionalkey , true ); 
						  
		    if (isset($field_post_meta) && ($field_post_meta != '')) {

					$additional_field_value = $field_post_meta;

			} elseif (isset($additionalvalue['value'])) {

				    $additional_field_value = $additionalvalue['value'];

			} else {

				    $additional_field_value = '';
			}

        
		
			 
		    if (isset($additionalvalue['visibility'])) {

				$visibilityarray = $additionalvalue['visibility'];
				 
				if (isset($additionalvalue['products'])) { 

				    $allowedproducts = $additionalvalue['products'];

				} else {

					$allowedproducts = array(); 

				}
				 
				if (isset($additionalvalue['category'])) {
					 
					 $allowedcats = $additionalvalue['category'];

				} else {

					 $allowedcats = array();

				}


				if (isset($additionalvalue['role'])) {
					$allowedroles = $additionalvalue['role'];
				} else {
					$allowedroles = array();
				}

				if (isset($additionalvalue['total-quantity'])) {
					$total_quantity = $additionalvalue['total-quantity'];
				} else {
					$total_quantity = 0;
				}



				if (isset($additionalvalue['specific-product'])) {
			        $prd = $additionalvalue['specific-product'];
		        } else {
			        $prd = 0;
		        }

		        if (isset($additionalvalue['specific-quantity'])) {
		        	$prd_qnty = $additionalvalue['specific-quantity'];
		        } else {
		        	$prd_qnty = 0;
		        }



		        if (isset($additionalvalue['dynamic_rules'])) { 
		        	$dynamic_rules = $additionalvalue['dynamic_rules'];
		        } else {
		        	$dynamic_rules = array(); 
		        }


		        if (isset($additionalvalue['dynamic_visibility_criteria'])) { 
		        	$dynamic_criteria = $additionalvalue['dynamic_visibility_criteria'];
		        } else {
		        	$dynamic_criteria = 'match_all'; 
		        }




		        $is_field_hidden = pcfme_check_if_field_is_hidden($visibilityarray,$allowedproducts,$allowedcats,$allowedroles,$total_quantity,$prd,$prd_qnty,$dynamic_rules,$dynamic_criteria);


		        if (isset($additionalvalue['multiple_clone']) && ($additionalvalue['multiple_clone'])) { 
		        	$multiple_clone = "yes";
		        } else {
		        	$multiple_clone = "no"; 
		        }







		        if (((!isset($is_field_hidden)) || ($is_field_hidden != 0)) && ($multiple_clone != "yes")) {

		        	woocommerce_form_field( $additionalkey,  $extrafield ,! empty( $_POST[ $additionalkey ] ) ? wc_clean( $_POST[ $additionalkey ] ) : $additional_field_value);
		        }


		        if ($multiple_clone == "yes") {
                    
                    $multiclone_condition = isset($additionalvalue['multiclone_condition']) ? $additionalvalue['multiclone_condition'] : "each_quantity";

                    $multiclone_product   = isset($additionalvalue['multiclone_product']) ? $additionalvalue['multiclone_product'] : "";

		        	pcfmne_process_multiple_clone($multiclone_condition,$multiclone_product,$additionalvalue);
		        

		        }
		    }
		    $keyorder++;
        }
    }
}
	
	

	



	
	
	
}

new pcfme_update_checkout_fields();
?>