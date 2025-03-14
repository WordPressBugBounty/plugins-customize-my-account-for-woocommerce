<?php

if (! function_exists('pcfme_get_datepicker_format_from_option')) {

	 /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */

	 function pcfme_get_datepicker_format_from_option($date_format) {

	 	$date_format_e = 'd/m/Y';

         switch($date_format) {

			 	case 01:
			 	   $date_format_e = 'd/m/Y';
			 	break;

			 	case 02:
			 	   $date_format_e = 'd/m/Y';
			 	break;

			 	case 03:
			 	   $date_format_e = 'd/m/Y';
			 	break;

			 	case 04:
			 	   $date_format_e = 'd/m/Y';
			 	break;

			 	case 05:
			 	   $date_format_e = 'd/m/Y';
			 	break;

			 	case 06:
			 	   $date_format_e = 'd/m/Y';
			 	break;



                default:
                    $date_format_e = 'd/m/Y';
                break;

	    }

	    return $date_format_e;
	}

}


/**
 * Get account menu item classes.
 *
 * @since 1.0.0
 */

if (!function_exists('pcfme_check_for_checkout_pages')) {

    function pcfme_check_for_checkout_pages() {

        $new_checkout_detected = 'no';

        $checkout_page_id   = get_option( 'woocommerce_checkout_page_id' );

        $checkout_content_post = get_post($checkout_page_id);
        $checkout_content = $checkout_content_post->post_content;

        

        if (!has_shortcode( $checkout_content, 'woocommerce_checkout')) {

            $new_checkout_detected = 'yes';

        }

    

        

        if ($new_checkout_detected == 'yes') {


           
            echo '<div class="updated" style="padding:15px; position:relative;"> ' . __( 'It looks like your site is using WooCommerce Block Checkout as Default checkout page.In order to make field modification Work create new page with [woocommerce_checkout] shortcode and asign it as default checkout page under <a href="admin.php?page=wc-settings&tab=advanced" target="_blank">woocommerce/setttings/advanced_settings tab</a>', 'customize-my-account-pro' ) . '</div>';
           

           

           
       }
    }
}


if (! function_exists('pcfme_easy_checkout_get_fees_type')) {

	 /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */

	 function pcfme_easy_checkout_get_fees_type() {
         
         $fees_types = array(
	 		"0"=>array(
	 		    'value'=>01,
	 		    'text'=> __('Add or Deduct Fixed Amount or Fixed Percentage','customize-my-account-pro')
	 		   

	 	    ),
	 	    "1"=>array(
	 		    'value'=>02,
	 		    'text'=> __('Give Discount equal to price of product','customize-my-account-pro')
	 		   

	 	    ),
	 	    "2"=>array(
	 		    'value'=>03,
	 		    'text'=> __('Add certain fee for each product','customize-my-account-pro')
	 		   

	 	    )

	 	   
	 	);
	 	return apply_filters('pcfme_override_fees_types',$fees_types);

	 }

}


if (! function_exists('pcfme_display_each_dynamic_row')) {

	 /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */

	 function pcfme_display_each_dynamic_row($dynamickey,$dynamicvalue,$key,$slug) {

	 	$selected = isset($dynamicvalue['rule_type']) ? $dynamicvalue['rule_type'] : "";

	 	$selected_compare = isset($dynamicvalue['rule_type_compare']) ? $dynamicvalue['rule_type_compare'] : "";

	 	$from_to_specific = isset($dynamicvalue['from_to_specific']) ? $dynamicvalue['from_to_specific'] : "";

	 	$from_to = isset($dynamicvalue['from_to']) ? $dynamicvalue['from_to'] : "";

	 	$selected_compare = isset($dynamicvalue['rule_type_compare']) ? $dynamicvalue['rule_type_compare'] : "";

	 	$selected_contains = isset($dynamicvalue['rule_type_contains']) ? $dynamicvalue['rule_type_contains'] : "";
        
        $checked_text = isset($dynamicvalue['enabled']) && ($dynamicvalue['enabled'] == "yes") ? 'checked' : "";

        $rule_type_number =  isset($dynamicvalue['rule_type_number']) ? $dynamicvalue['rule_type_number'] : "";

        $rule_type_date =  isset($dynamicvalue['rule_type_date']) ? $dynamicvalue['rule_type_date'] : "";

        $rule_type_time =  isset($dynamicvalue['rule_type_time']) ? $dynamicvalue['rule_type_time'] : "";

        $rule_type_date_time =  isset($dynamicvalue['rule_type_date_time']) ? $dynamicvalue['rule_type_date_time'] : "";

        $rule_type_products = isset($dynamicvalue['rule_type_products']) ? $dynamicvalue['rule_type_products'] : array();

        $rule_type_coupons = isset($dynamicvalue['rule_type_coupons']) ? $dynamicvalue['rule_type_coupons'] : array();
        
        $chosencategories   = isset($dynamicvalue['rule_type_categories']) ? $dynamicvalue['rule_type_categories'] : array();

        $chosenroles        = isset($dynamicvalue['rule_type_roles']) ? $dynamicvalue['rule_type_roles'] : array();

        $chosendays         = isset($dynamicvalue['rule_type_days']) ? $dynamicvalue['rule_type_days'] : array();

        $chosenmonthdays    = isset($dynamicvalue['rule_type_monthdays']) ? $dynamicvalue['rule_type_monthdays'] : array();

        $chosenmonths       = isset($dynamicvalue['rule_type_months']) ? $dynamicvalue['rule_type_months'] : array();

        global $wp_roles;

        if ( ! isset( $wp_roles ) ) { 
    	    $wp_roles = new WP_Roles();  
        }
	
	    $roles = $wp_roles->roles;

	    $days  = array(
	    	'mon' => __('Monday','customize-my-account-pro'),
	    	'tue' => __('Tuesday','customize-my-account-pro'),
	    	'wed' => __('Wednesday','customize-my-account-pro'),
	    	'thu' => __('Thursday','customize-my-account-pro'),
	    	'fri' => __('Friday','customize-my-account-pro'),
	    	'sat' => __('Saturday','customize-my-account-pro'),
	    	'sun' => __('Sunday','customize-my-account-pro')
	    	
	    );

	    $months  = array(
	    	'jan' => __('January','customize-my-account-pro'),
	    	'feb' => __('Tuesday','customize-my-account-pro'),
	    	'mar' => __('Wednesday','customize-my-account-pro'),
	    	'apr' => __('Thursday','customize-my-account-pro'),
	    	'may' => __('Friday','customize-my-account-pro'),
	    	'jun' => __('Saturday','customize-my-account-pro'),
	    	'jul' => __('Sunday','customize-my-account-pro'),
	    	'aug' => __('January','customize-my-account-pro'),
	    	'sep' => __('Tuesday','customize-my-account-pro'),
	    	'oct' => __('Wednesday','customize-my-account-pro'),
	    	'nov' => __('Thursday','customize-my-account-pro'),
	    	'dec' => __('Friday','customize-my-account-pro') 	
	    );

	    $monthdays  = array(
	    	'01' => '01',
	    	'02' => '02',
	    	'03' => '03',
	    	'04' => '04',
	    	'05' => '05',
	    	'06' => '06',
	    	'07' => '07',
	    	'08' => '08',
	    	'09' => '09',
	    	'10' => '10',
	    	'11' => '11',
	    	'12' => '12',
	    	'13' => '13',
	    	'14' => '14',
	    	'15' => '15',
	    	'16' => '16',
	    	'17' => '17',
	    	'18' => '18',
	    	'19' => '19',
	    	'20' => '20',
	    	'21' => '21',
	    	'22' => '22',
	    	'23' => '23',
	    	'24' => '24',
	    	'25' => '25',
	    	'26' => '26',
	    	'27' => '27',
	    	'28' => '28',
	    	'29' => '29',
	    	'30' => '30',
	    	'31' => '31',
	    	'last_day' => __('Last day of month','customize-my-account-pro')
	    );


        

        switch($selected) {
        	case "cart__quantity":
        	    $row_mode = 'quantity';
        	    $filter_mode = 'none';
        	break;

        	case "cart__count":
        	    $row_mode = 'quantity';
        	    $filter_mode = 'none';
        	break;

        	case "cart__weight":
        	    $row_mode = 'quantity';
        	    $filter_mode = 'none';
        	break;

        	case "cart_items__products":
        	    $row_mode = 'contains';
        	    $filter_mode  = 'products';
        	break;


        	case "cart_items__product_categories":
        	    $row_mode = 'contains';
        	    $filter_mode  = 'categories';
        	break;

        	case "user_role":
        	    $row_mode = 'contains';
        	    $filter_mode  = 'roles';
        	break;

        	case "user_is_logged_in":
        	    $row_mode = 'none';
        	    $filter_mode  = 'none';
        	break;

        	case "user_is_logged_out":
        	    $row_mode = 'none';
        	    $filter_mode  = 'none';
        	break;

        	case "customer_total_spent":
        	    $row_mode = 'quantity';
        	    $filter_mode  = 'none';
        	break;

        	case "customer_order_count":
        	    $row_mode = 'quantity';
        	    $filter_mode  = 'none';
        	break;

        	case "date_date":
        	    $row_mode = 'from_to_specific';
        	    $filter_mode  = 'date';
        	break;

        	case "date_time":
        	    $row_mode = 'from_to';
        	    $filter_mode  = 'time';
        	break;


        	case "date_date_time":
        	    $row_mode = 'from_to';
        	    $filter_mode  = 'date_time';
        	break;

        	case "date_week_day":
        	    $row_mode = 'contains';
        	    $filter_mode  = 'weekday';
        	break;

        	case "date_month_days":
        	    $row_mode = 'contains';
        	    $filter_mode  = 'monthdays';
        	break;

        	case "date_months":
        	    $row_mode = 'contains';
        	    $filter_mode  = 'months';
        	break;

        	case "coupon_applied":
        	    $row_mode = 'contains';
        	    $filter_mode  = 'coupons';
        	break;

        	default:
        	    $row_mode = 'quantity';
        	    $filter_mode  = 'products';
        	break;
        }





        $catargs = array(
	      'orderby'                  => 'name',
	      'taxonomy'                 => 'product_cat',
	      'hide_empty'               => 0
	    );
		 
	  
		$categories           = get_categories( $catargs );  

       

	 	?>
           
        <div class="conditional-row conditional_row_<?php echo $dynamickey; ?>_<?php echo $key; ?>">
        	<span class="pcfme_sortable_tr_handle_dynamic dashicons dashicons-menu"></span>&nbsp;
        	<input type="checkbox" class="rule_enabled_checkbox" name="<?php echo $slug; ?>[<?php echo $key; ?>][dynamic_rules][<?php echo $dynamickey; ?>][enabled]" value="yes" <?php echo $checked_text; ?>>
        	<select mtype="<?php echo $slug; ?>" mntext="<?php echo $dynamickey; ?>" mnkey="<?php echo $key; ?>" class="checkout_field_dynamic_rule_type checkout_field_dynamic_rule_type_<?php echo $dynamickey; ?>_<?php echo $key; ?>" name="<?php echo $slug; ?>[<?php echo $key; ?>][dynamic_rules][<?php echo $dynamickey; ?>][rule_type]">
        		

        		<?php echo pcfme_get_dynamic_rule_types_select_optionhtml($selected); ?>
        	</select>

        	<select style="<?php if ($row_mode == "quantity") { echo 'display: inline-grid;'; } else { echo 'display:none;'; } ?>" mtype="<?php echo $slug; ?>" mntext="<?php echo $dynamickey; ?>" mnkey="<?php echo $key; ?>" class="show_if_quantity_rule checkout_field_dynamic_rule_type_compare checkout_field_dynamic_rule_type_compare_<?php echo $dynamickey; ?>_<?php echo $key; ?>" name="<?php echo $slug; ?>[<?php echo $key; ?>][dynamic_rules][<?php echo $dynamickey; ?>][rule_type_compare]">
        		

        		<?php echo pcfme_get_dynamic_rule_types_compare_optionhtml($selected_compare); ?>
        	</select>


        	<select style="<?php if ($row_mode == "from_to_specific") { echo 'display: inline-grid;'; } else { echo 'display:none;'; } ?>" mtype="<?php echo $slug; ?>" mntext="<?php echo $dynamickey; ?>" mnkey="<?php echo $key; ?>" class="show_if_from_to_specific_rule checkout_field_dynamic_rule_type_from_to_specific checkout_field_dynamic_rule_type_from_to_specific_<?php echo $dynamickey; ?>_<?php echo $key; ?>" name="<?php echo $slug; ?>[<?php echo $key; ?>][dynamic_rules][<?php echo $dynamickey; ?>][from_to_specific]">
        		

        		<?php echo pcfme_get_dynamic_rule_types_fromtospecific_optionhtml($from_to_specific); ?>
        	</select>


        	<select style="<?php if ($row_mode == "from_to") { echo 'display: inline-grid;'; } else { echo 'display:none;'; } ?>" mtype="<?php echo $slug; ?>" mntext="<?php echo $dynamickey; ?>" mnkey="<?php echo $key; ?>" class="show_if_from_to_rule checkout_field_dynamic_rule_type_from_to checkout_field_dynamic_rule_type_from_to_<?php echo $dynamickey; ?>_<?php echo $key; ?>" name="<?php echo $slug; ?>[<?php echo $key; ?>][dynamic_rules][<?php echo $dynamickey; ?>][from_to]">
        		

        		<?php echo pcfme_get_dynamic_rule_types_fromto_optionhtml($from_to); ?>
        	</select>


            
			

        	<select style="<?php if (isset($filter_mode) && ($filter_mode  == 'roles') ) { echo 'display:none !important;'; } if ($row_mode == "contains") { echo 'display: inline-grid;'; } else { echo 'display:none;'; } ?>" mtype="<?php echo $slug; ?>" mntext="<?php echo $dynamickey; ?>" mnkey="<?php echo $key; ?>" class="show_if_contains_rule checkout_field_dynamic_rule_type_contains checkout_field_dynamic_rule_type_contains_<?php echo $dynamickey; ?>_<?php echo $key; ?>" name="<?php echo $slug; ?>[<?php echo $key; ?>][dynamic_rules][<?php echo $dynamickey; ?>][rule_type_contains]">
        		

        		    <?php echo pcfme_get_dynamic_rule_types_contains_optionhtml($selected_contains); ?>
        	</select>

			

        	&nbsp;

        	<span style="<?php if ($row_mode == "quantity") { echo 'display: inline-grid;'; } else { echo 'display:none;'; } ?>" class="show_if_quantity_rule">

        		 <input   mtype="<?php echo $slug; ?>" mntext="<?php echo $dynamickey; ?>" mnkey="<?php echo $key; ?>" type="number" class=" checkout_field_dynamic_rule_number checkout_field_dynamic_rule_number_<?php echo $dynamickey; ?>_<?php echo $key; ?>" name="<?php echo $slug; ?>[<?php echo $key; ?>][dynamic_rules][<?php echo $dynamickey; ?>][rule_type_number]" value="<?php echo $rule_type_number; ?>">

        	</span>


        	<span style="<?php if (($row_mode == "contains") || ($row_mode == "from_to") || ($row_mode == "from_to_specific")) { echo 'display:inline-grid;'; } else { echo 'display:none;'; } ?>" class="show_if_contains_rule_div">
                   

			    <span  class="checkout_field_products_span" style="<?php if ($filter_mode == "products") { echo 'display:inline-grid;'; } else { echo 'display:none;'; } ?>">
        		
			        <select  mtype="<?php echo $slug; ?>" mntext="<?php echo $dynamickey; ?>" mnkey="<?php echo $key; ?>" class="checkout_field_products" data-placeholder="<?php echo esc_html__('Choose Products','customize-my-account-pro'); ?>" name="<?php echo $slug; ?>[<?php echo $key; ?>][dynamic_rules][<?php echo $dynamickey; ?>][rule_type_products][]" multiple>
			   		<?php if (isset($rule_type_products) && (!empty($rule_type_products))) { ?>
			   			<?php foreach ($rule_type_products as $uniquekey => $unique_id) { ?>
			   				<option value="<?php echo $unique_id; ?>" selected>#<?php echo $unique_id; ?>- <?php echo get_the_title($unique_id); ?></option>
			   			<?php } ?>
			   		<?php  } ?>
			   	    </select>

				</span>

				<span  class="checkout_field_category_span" style="<?php if ($filter_mode == "categories") { echo 'display:inline-grid;'; } else { echo 'display:none;'; } ?>">
        		

			   	    <select  mtype="<?php echo $slug; ?>" mntext="<?php echo $dynamickey; ?>" mnkey="<?php echo $key; ?>"  class="checkout_field_category" data-placeholder="<?php echo esc_html__('Choose Categories','customize-my-account-pro'); ?>" name="<?php echo $slug; ?>[<?php echo $key; ?>][dynamic_rules][<?php echo $dynamickey; ?>][rule_type_categories][]"  multiple>
                            <?php foreach ($categories as $category) { ?>
				                <option value="<?php echo $category->term_id; ?>" <?php if (in_array($category->term_id, $chosencategories)) { echo "selected"; } ?>>#<?php echo $category->term_id; ?>- <?php echo $category->name; ?></option>
				            <?php } ?>
                    </select>

			    </span>


				<span  class="checkout_field_roles_span" style="<?php if ($filter_mode == "roles") { echo 'display:inline-grid;'; } else { echo 'display:none;'; } ?>">


			        <select mtype="<?php echo $slug; ?>" mntext="<?php echo $dynamickey; ?>" mnkey="<?php echo $key; ?>" class="checkout_field_role" data-placeholder="<?php echo esc_html__('Choose Roles','customize-my-account-pro'); ?>" name="<?php echo $slug; ?>[<?php echo $key; ?>][dynamic_rules][<?php echo $dynamickey; ?>][rule_type_roles][]"  multiple>
                            <?php foreach ($roles as $rkey=>$rvalue) { ?>
				                 <option value="<?php echo $rkey; ?>" <?php if (in_array($rkey, $chosenroles)) { echo "selected"; } ?>><?php echo $rvalue['name']; ?></option>
				            <?php } ?>
                    </select>
                
				</span>


				<span  class="checkout_field_coupons_span" style="<?php if ($filter_mode == "coupons") { echo 'display:inline-grid;'; } else { echo 'display:none;'; } ?>">
        		
			        <select  mtype="<?php echo $slug; ?>" mntext="<?php echo $dynamickey; ?>" mnkey="<?php echo $key; ?>" class="checkout_field_coupons" data-placeholder="<?php echo esc_html__('Choose Coupons','customize-my-account-pro'); ?>" name="<?php echo $slug; ?>[<?php echo $key; ?>][dynamic_rules][<?php echo $dynamickey; ?>][rule_type_coupons][]" multiple>
			   		<?php if (isset($rule_type_coupons) && (!empty($rule_type_coupons))) { ?>
			   			<?php foreach ($rule_type_coupons as $uniquekey => $unique_id) { ?>
			   				<option value="<?php echo $unique_id; ?>" selected>#<?php echo $unique_id; ?>- <?php echo get_the_title($unique_id); ?></option>
			   			<?php } ?>
			   		<?php  } ?>
			   	    </select>

				</span>


				<span  class="checkout_field_date_span" style="<?php if ($filter_mode == "date") { echo 'display:inline-grid;'; } else { echo 'display:none;'; } ?>">

                      <input   mtype="<?php echo $slug; ?>" mntext="<?php echo $dynamickey; ?>" mnkey="<?php echo $key; ?>" type="text" class=" checkout_field_dynamic_rule_date checkout_field_dynamic_rule_date_<?php echo $dynamickey; ?>_<?php echo $key; ?>" name="<?php echo $slug; ?>[<?php echo $key; ?>][dynamic_rules][<?php echo $dynamickey; ?>][rule_type_date]" value="<?php echo $rule_type_date; ?>">
			        
                
				</span>

				<span  class="checkout_field_time_span" style="<?php if ($filter_mode == "time") { echo 'display:inline-grid;'; } else { echo 'display:none;'; } ?>">

                      <input   mtype="<?php echo $slug; ?>" mntext="<?php echo $dynamickey; ?>" mnkey="<?php echo $key; ?>" type="text" class=" checkout_field_dynamic_rule_time checkout_field_dynamic_rule_time_<?php echo $dynamickey; ?>_<?php echo $key; ?>" name="<?php echo $slug; ?>[<?php echo $key; ?>][dynamic_rules][<?php echo $dynamickey; ?>][rule_type_time]" value="<?php echo $rule_type_time; ?>">
			        
                
				</span>

				<span  class="checkout_field_date_time_span" style="<?php if ($filter_mode == "date_time") { echo 'display:inline-grid;'; } else { echo 'display:none;'; } ?>">

                      <input   mtype="<?php echo $slug; ?>" mntext="<?php echo $dynamickey; ?>" mnkey="<?php echo $key; ?>" type="text" class=" checkout_field_dynamic_rule_date_time checkout_field_dynamic_rule_date_time_<?php echo $dynamickey; ?>_<?php echo $key; ?>" name="<?php echo $slug; ?>[<?php echo $key; ?>][dynamic_rules][<?php echo $dynamickey; ?>][rule_type_date_time]" value="<?php echo $rule_type_date_time; ?>">
			        
                
				</span>

				<span  class="checkout_field_weekday_span" style="<?php if ($filter_mode == "weekday") { echo 'display:inline-grid;'; } else { echo 'display:none;'; } ?>">


			        <select mtype="<?php echo $slug; ?>" mntext="<?php echo $dynamickey; ?>" mnkey="<?php echo $key; ?>" class="checkout_field_weekday" data-placeholder="<?php echo esc_html__('Choose Days','customize-my-account-pro'); ?>" name="<?php echo $slug; ?>[<?php echo $key; ?>][dynamic_rules][<?php echo $dynamickey; ?>][rule_type_days][]"  multiple>
                            <?php foreach ($days as $dkey=>$dvalue) { ?>
				                 <option value="<?php echo $dkey; ?>" <?php if (in_array($dkey, $chosendays)) { echo "selected"; } ?>><?php echo $dvalue; ?></option>
				            <?php } ?>
                    </select>
                
				</span>

				<span  class="checkout_field_monthdays_span" style="<?php if ($filter_mode == "monthdays") { echo 'display:inline-grid;'; } else { echo 'display:none;'; } ?>">


			        <select mtype="<?php echo $slug; ?>" mntext="<?php echo $dynamickey; ?>" mnkey="<?php echo $key; ?>" class="checkout_field_monthdays" data-placeholder="<?php echo esc_html__('Choose Month Days','customize-my-account-pro'); ?>" name="<?php echo $slug; ?>[<?php echo $key; ?>][dynamic_rules][<?php echo $dynamickey; ?>][rule_type_monthdays][]"  multiple>
                            <?php foreach ($monthdays as $mdkey=>$mdvalue) { ?>
				                 <option value="<?php echo $mdkey; ?>" <?php if (in_array($mdkey, $chosenmonthdays)) { echo "selected"; } ?>><?php echo $mdvalue; ?></option>
				            <?php } ?>
                    </select>
                
				</span>


				<span  class="checkout_field_months_span" style="<?php if ($filter_mode == "months") { echo 'display:inline-grid;'; } else { echo 'display:none;'; } ?>">


			        <select mtype="<?php echo $slug; ?>" mntext="<?php echo $dynamickey; ?>" mnkey="<?php echo $key; ?>" class="checkout_field_months" data-placeholder="<?php echo esc_html__('Choose Months','customize-my-account-pro'); ?>" name="<?php echo $slug; ?>[<?php echo $key; ?>][dynamic_rules][<?php echo $dynamickey; ?>][rule_type_months][]"  multiple>
                            <?php foreach ($months as $mkey=>$mvalue) { ?>
				                 <option value="<?php echo $mkey; ?>" <?php if (in_array($mkey, $chosenmonths)) { echo "selected"; } ?>><?php echo $mvalue; ?></option>
				            <?php } ?>
                    </select>
                
				</span>

				


        	</span>
            
           

        	<span class="dashicons dashicons-remove pcfme-remove-condition-dynamic"></span>
        </div>

        <?php

	 }

}





if (! function_exists('pcfme_get_dynamic_rule_types_contains_optionhtml')) {

	 /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */

	 function pcfme_get_dynamic_rule_types_contains_optionhtml($selected_compare = NULL) {

	 	 $equality_types = array(
	 		array(
	 		    'value'=>'contains_any',
	 		    'text'=> __('Contains Any','customize-my-account-pro')
	 		   

	 	    ),

	 	    array(
	 		    'value'=>'contains_all',
	 		    'text'=> __('Contains All','customize-my-account-pro')
	 		   

	 	    ),

	 	    array(
	 		    'value'=>'does_not_contain_any',
	 		    'text'=> __('Does not Contains Any','customize-my-account-pro')
	 		   

	 	    ),

	 	    array(
	 		    'value'=>'does_not_contain_all',
	 		    'text'=> __('Does not contains all','customize-my-account-pro')
	 		   

	 	    ),

	 	   

	 	   
	 	);

	 	$options_html ='';

	 	foreach ($equality_types as $okey=>$ovalue) {

	 		$selected_text = isset($selected_compare) && ($selected_compare == $ovalue['value']) ? "selected" : "";

	 		$options_html .='<option value="'.$ovalue['value'].'" '.$selected_text.'>'.$ovalue['text'].'</option>';

	 	}



	 	

	 	 return $options_html;

	 }

}

if (! function_exists('pcfme_get_dynamic_rule_types_compare_optionhtml')) {

	 /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */

	 function pcfme_get_dynamic_rule_types_compare_optionhtml($selected_compare = NULL) {

	 	 $equality_types = array(
	 		array(
	 		    'value'=>'less_than',
	 		    'text'=> __('Less than','customize-my-account-pro')
	 		   

	 	    ),

	 	    array(
	 		    'value'=>'greater_than',
	 		    'text'=> __('Greater Than','customize-my-account-pro')
	 		   

	 	    ),

	 	    array(
	 		    'value'=>'greater_than_equal_to',
	 		    'text'=> __('Greater than or equalto','customize-my-account-pro')
	 		   

	 	    ),

	 	   
	 	);

	 	$options_html ='';

	 	foreach ($equality_types as $okey=>$ovalue) {

	 		$selected_text = isset($selected_compare) && ($selected_compare == $ovalue['value']) ? "selected" : "";

	 		$options_html .='<option value="'.$ovalue['value'].'" '.$selected_text.'>'.$ovalue['text'].'</option>';

	 	}

	 	return $options_html;

	 }

}


if (! function_exists('pcfme_get_dynamic_rule_types_fromtospecific_optionhtml')) {

	 /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */

	 function pcfme_get_dynamic_rule_types_fromtospecific_optionhtml($selected_compare = NULL) {

	 	 $equality_types = array(
	 		array(
	 		    'value'=>'from',
	 		    'text'=> __('From','customize-my-account-pro')
	 		   

	 	    ),

	 	    array(
	 		    'value'=>'to',
	 		    'text'=> __('Till','customize-my-account-pro')
	 		   

	 	    ),

	 	    array(
	 		    'value'=>'specific',
	 		    'text'=> __('Specific','customize-my-account-pro')
	 		   

	 	    ),

	 	   
	 	);

	 	$options_html ='';

	 	foreach ($equality_types as $okey=>$ovalue) {

	 		$selected_text = isset($selected_compare) && ($selected_compare == $ovalue['value']) ? "selected" : "";

	 		$options_html .='<option value="'.$ovalue['value'].'" '.$selected_text.'>'.$ovalue['text'].'</option>';

	 	}

	 	return $options_html;

	 }

}


if (! function_exists('pcfme_get_dynamic_rule_types_fromto_optionhtml')) {

	 /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */

	 function pcfme_get_dynamic_rule_types_fromto_optionhtml($selected_compare = NULL) {

	 	 $equality_types = array(
	 		array(
	 		    'value'=>'from',
	 		    'text'=> __('From','customize-my-account-pro')
	 		   

	 	    ),

	 	    array(
	 		    'value'=>'to',
	 		    'text'=> __('Till','customize-my-account-pro')
	 		   

	 	    )

	 	   
	 	);

	 	$options_html ='';

	 	foreach ($equality_types as $okey=>$ovalue) {

	 		$selected_text = isset($selected_compare) && ($selected_compare == $ovalue['value']) ? "selected" : "";

	 		$options_html .='<option value="'.$ovalue['value'].'" '.$selected_text.'>'.$ovalue['text'].'</option>';

	 	}

	 	return $options_html;

	 }

}

if (! function_exists('pcfme_get_dynamic_rule_types_select_optionhtml')) {

	 /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */

	 function pcfme_get_dynamic_rule_types_select_optionhtml($selected = NULL) {

	 	 $cart_options          = pcfme_get_dynamic_rule_types_cart();
	 	 $cart_options_items    = pcfme_get_dynamic_rule_types_cart_items();
	 	 $cart_options_user     = pcfme_get_dynamic_rule_types_user();
	 	 $cart_options_customer = pcfme_get_dynamic_rule_types_customer();
	 	 

	 	 $options_html ='';

	 	 $options_html .='<optgroup label="'.__('Cart','customize-my-account-pro').'">';

	 	 foreach ($cart_options as $cvalue) {

	 	 	$selected_text = isset($selected) && ($selected == $cvalue['value']) ? "selected" : "";

	 	 	$options_html .='<option value="'.$cvalue['value'].'" '.$selected_text.'>'.$cvalue['text'].'</option>';

	 	 }

	 	 $options_html .='</optgroup>';

	 	 $options_html .='<optgroup label="'.__('Cart Items','customize-my-account-pro').'">';

	 	 foreach ($cart_options_items as $cvalue2) {

	 	 	$selected_text = isset($selected) && ($selected == $cvalue2['value']) ? "selected" : "";

	 	 	$options_html .='<option value="'.$cvalue2['value'].'" '.$selected_text.'>'.$cvalue2['text'].'</option>';
	 	 	
	 	 }

	 	 $options_html .='</optgroup>';

	 	 $options_html .='<optgroup label="'.__('User','customize-my-account-pro').'">';

	 	 

	 	 foreach ($cart_options_user as $cvalue3) {

	 	 	$selected_text = isset($selected) && ($selected == $cvalue3['value']) ? "selected" : "";

	 	 	$options_html .='<option value="'.$cvalue3['value'].'" '.$selected_text.'>'.$cvalue3['text'].'</option>';
	 	 	
	 	 }

	 	 $options_html .='</optgroup>';


	 	 $options_html .='<optgroup label="'.__('Customer','customize-my-account-pro').'">';

	 	 

	 	 foreach ($cart_options_customer as $cvalue4) {

	 	 	$selected_text = isset($selected) && ($selected == $cvalue4['value']) ? "selected" : "";

	 	 	$options_html .='<option value="'.$cvalue4['value'].'" '.$selected_text.'>'.$cvalue4['text'].'</option>';
	 	 	
	 	 }

	 	 $options_html .='</optgroup>';


	 	 $options_html .='<optgroup label="'.__('Date & Time','customize-my-account-pro').'">';



	 	 $cart_options_date     = pcfme_get_dynamic_rule_types_date();


	 	 foreach ($cart_options_date as $cvalue5) {

	 	 	$selected_text = isset($selected) && ($selected == $cvalue5['value']) ? "selected" : "";

	 	 	$options_html .='<option value="'.$cvalue5['value'].'" '.$selected_text.'>'.$cvalue5['text'].'</option>';
	 	 	
	 	 }

	 	 $options_html .='</optgroup>';


	 	 $options_html .='<optgroup label="'.__('Coupons','customize-my-account-pro').'">';


	 	 $cart_options_coupon     = pcfme_get_dynamic_rule_types_coupon();


	 	 foreach ($cart_options_coupon as $cvalue6) {

	 	 	$selected_text = isset($selected) && ($selected == $cvalue6['value']) ? "selected" : "";

	 	 	$options_html .='<option value="'.$cvalue6['value'].'" '.$selected_text.'>'.$cvalue6['text'].'</option>';
	 	 	
	 	 }

	 	 $options_html .='</optgroup>';
	 	 
         
	 	 return apply_filters('pcfme_override_dynamic_visibility_types',$options_html);;

	 }

}


if (! function_exists('pcfme_get_dynamic_rule_types_cart')) {

	 /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */

	 function pcfme_get_dynamic_rule_types_cart() {

	 	$visibility_types = array(
	 		array(
	 		    'value'=>'cart__quantity',
	 		    'text'=> __('Cart total quantity','customize-my-account-pro')
	 		   

	 	    ),

	 	    array(
	 		    'value'=>'cart__count',
	 		    'text'=> __('Cart item count','customize-my-account-pro')
	 		   

	 	    ),

	 	    array(
	 		    'value'=>'cart__weight',
	 		    'text'=> __('Cart total weight','customize-my-account-pro')
	 		   

	 	    ),

	 	   
	 	);
	 	return apply_filters('pcfme_override_rule_types_cart',$visibility_types);
	 }
}

if (! function_exists('pcfme_get_dynamic_rule_types_cart_items')) {

	 /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */

	 function pcfme_get_dynamic_rule_types_cart_items() {

	 	$visibility_types = array(
	 		array(
	 		    'value'=>'cart_items__products',
	 		    'text'=> __('Cart items - Products','customize-my-account-pro')
	 		   

	 	    ),


	 	    array(
	 		    'value'=>'cart_items__product_categories',
	 		    'text'=> __('Cart items - Categories','customize-my-account-pro')
	 		   

	 	    ),

	 	   
	 	);
	 	return apply_filters('pcfme_override_rule_types_cart_items',$visibility_types);
	 }
}


if (! function_exists('pcfme_get_dynamic_rule_types_user')) {

	 /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */

	 function pcfme_get_dynamic_rule_types_user() {

	 	$visibility_types = array(
	 		array(
	 		    'value'=>'user_role',
	 		    'text'=> __('User Role','customize-my-account-pro')
	 		   

	 	    ),
	 	    array(
	 		    'value'=>'user_is_logged_in',
	 		    'text'=> __('Is Logged In','customize-my-account-pro')
	 		   

	 	    ),
	 	    array(
	 		    'value'=>'user_is_logged_out',
	 		    'text'=> __('Is Logged out','customize-my-account-pro')
	 		   

	 	    )

	 	   
	 	);
	 	return apply_filters('pcfme_override_rule_types_cart_items',$visibility_types);
	 }
}


if (! function_exists('pcfme_get_dynamic_rule_types_customer')) {

	 /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */

	 function pcfme_get_dynamic_rule_types_customer() {

	 	$visibility_types = array(
	 		array(
	 		    'value'=>'customer_total_spent',
	 		    'text'=> __('Total Spent','customize-my-account-pro')
	 		   

	 	    ),

            array(
	 		    'value'=>'customer_order_count',
	 		    'text'=> __('Order Count','customize-my-account-pro')
	 		   

	 	    )
	 	   
	 	);
	 	return apply_filters('pcfme_override_rule_types_cart_items',$visibility_types);
	 }
}

if (! function_exists('pcfme_get_dynamic_rule_types_coupon')) {

	 /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */

	 function pcfme_get_dynamic_rule_types_coupon() {

	 	$visibility_types = array(
	 		array(
	 		    'value'=>'coupon_applied',
	 		    'text'=> __('Coupons Applied','customize-my-account-pro')
	 		   

	 	    )
	 	   
	 	);
	 	return apply_filters('pcfme_override_rule_types_coupon_items',$visibility_types);
	 }
}

if (! function_exists('pcfme_get_dynamic_rule_types_date')) {

	 /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */

	 function pcfme_get_dynamic_rule_types_date() {

	 	$visibility_types = array(
	 		array(
	 		    'value'=>'date_date',
	 		    'text'=> __('Date','customize-my-account-pro')
	 		   

	 	    ),

            array(
	 		    'value'=>'date_time',
	 		    'text'=> __('Time','customize-my-account-pro')
	 		   

	 	    ),
	 	    array(
	 		    'value'=>'date_date_time',
	 		    'text'=> __('Date & time','customize-my-account-pro')
	 		   

	 	    ),

            array(
	 		    'value'=>'date_week_day',
	 		    'text'=> __('Days of week','customize-my-account-pro')
	 		   

	 	    ),
	 	    array(
	 		    'value'=>'date_month_days',
	 		    'text'=> __('Days of Month','customize-my-account-pro')
	 		   

	 	    ),

            array(
	 		    'value'=>'date_months',
	 		    'text'=> __('Months','customize-my-account-pro')
	 		   

	 	    ),
	 	   
	 	);
	 	return apply_filters('pcfme_override_rule_types_cart_items',$visibility_types);
	 }
}

if (! function_exists('pcfme_get_dynamic_rule_types_cart_items_quantity')) {

	 /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */

	 function pcfme_get_dynamic_rule_types_cart_items_quantity() {

	 	$visibility_types = array(
	 		array(
	 		    'value'=>'cart_items__products',
	 		    'text'=> __('Cart item quantity - Products','customize-my-account-pro')
	 		   

	 	    ),

	 	    array(
	 		    'value'=>'cart_items__product_categories',
	 		    'text'=> __('Cart item quantity - Categories','customize-my-account-pro')
	 		   

	 	    ),

	 	   
	 	);
	 	return apply_filters('pcfme_override_rule_types_cart_items_quantity',$visibility_types);
	 }
}




if (! function_exists('pcfme_easy_checkout_get_visibility_types')) {

	 /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */

	 function pcfme_easy_checkout_get_visibility_types() {

	 	$visibility_types = array(
	 		array(
	 		    'value'=>'always-visible',
	 		    'text'=> __('Always Visibile','customize-my-account-pro')
	 		   

	 	    ),
	 	    array(
	 		    'value'=>'dynamically-visible',
	 		    'text'=> __('Dynamically Visibile','customize-my-account-pro')
	 		   

	 	    ),
	 	    array(
	 		    'value'=>'shipping-specific',
	 		    'text'=> __('Specific Shipping Method','customize-my-account-pro')
	 		    

	 	    ),
	 	    array(
	 		    'value'=>'payment-specific',
	 		    'text'=> __('Specific Payment Gateway','customize-my-account-pro')
	 		    

	 	    ),
	 	    array(
	 		    'value'=>'hide-downloadable',
	 		    'text'=> __('Hide If cart contains downloadable Products','customize-my-account-pro')
	 		    

	 	    ),
	 	    array(
	 		    'value'=>'hide-virtual',
	 		    'text'=> __('Hide If cart contains virtual Products','customize-my-account-pro')
	 		    

	 	    ),

	 	   
	 	);
	 	return apply_filters('pcfme_override_visibility_types',$visibility_types);
	 }
}

if (! function_exists('pcfme_easy_checkout_get_field_types')) {

	 /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */

	 function pcfme_easy_checkout_get_field_types() {

	 	$field_types = array(
	 		"0"=>array(
	 		    'type'=>'text',
	 		    'text'=> __('Text','customize-my-account-pro'),
	 		    'icon'=> 'fa fa-user'

	 	    ),
	 	    "1"=>array(
	 		    'type'=>'pcfmeselect',
	 		    'text'=> __('Select','customize-my-account-pro'),
	 		    'icon'=> 'fa fa-list'

	 	    ),
	 	    "2"=>array(
	 		    'type'=>'checkbox',
	 		    'text'=> __('Checkbox','customize-my-account-pro'),
	 		    'icon'=> 'fa fa-check'

	 	    ),
	 	    "3"=>array(
	 		    'type'=>'textarea',
	 		    'text'=> __('Textarea','customize-my-account-pro'),
	 		    'icon'=> 'fa fa-file'

	 	    ),
	 	    "4"=>array(
	 		    'type'=>'multiselect',
	 		    'text'=> __('MultiSelect','customize-my-account-pro'),
	 		    'icon'=> 'fa fa-list'

	 	    ),
	 	    "5"=>array(
	 		    'type'=>'radio',
	 		    'text'=> __('Radio','customize-my-account-pro'),
	 		    'icon'=> 'fa fa-bomb'

	 	    ),
	 	     "6"=>array(
	 		    'type'=>'heading',
	 		    'text'=> __('Heading','customize-my-account-pro'),
	 		    'icon'=> 'fa fa-minus'

	 	    ),
	 	     "7"=>array(
	 		    'type'=>'email',
	 		    'text'=> __('Email','customize-my-account-pro'),
	 		    'icon'=> 'fa fa-envelope'

	 	    ),
	 	     "8"=>array(
	 		    'type'=>'number',
	 		    'text'=> __('Number','customize-my-account-pro'),
	 		    'icon'=> 'fa fa-file'

	 	    ),
	 	     "9"=>array(
	 		    'type'=>'paragraph',
	 		    'text'=> __('Paragraph','customize-my-account-pro'),
	 		    'icon'=> 'fa fa-file'

	 	    ),

	 	     "10"=>array(
	 		    'type'=>'password',
	 		    'text'=> __('Password','customize-my-account-pro'),
	 		    'icon'=> 'fa fa-clipboard'

	 	    ),

	 	      "11"=>array(
	 		    'type'=>'datepicker',
	 		    'text'=> __('DatePicker','customize-my-account-pro'),
	 		    'icon'=> 'fa fa-calendar'

	 	    ),

	 	      "12"=>array(
	 		    'type'=>'timepicker',
	 		    'text'=> __('TimePicker','customize-my-account-pro'),
	 		    'icon'=> 'fa fa-list'

	 	    ),

	 	     "13"=>array(
	 		    'type'=>'datetimepicker',
	 		    'text'=> __('DateTime','customize-my-account-pro'),
	 		    'icon'=> 'fa fa-calendar'

	 	    ),

	 	     "14"=>array(
	 		    'type'=>'daterangepicker',
	 		    'text'=> __('DateRange','customize-my-account-pro'),
	 		    'icon'=> 'fa fa-calendar'

	 	    ),

	 	      "15"=>array(
	 		    'type'=>'datetimerangepicker',
	 		    'text'=> __('DateTimeRange','customize-my-account-pro'),
	 		    'icon'=> 'fa fa-file'

	 	    ),

	 	      "16"=>array(
	 		    'type'=>'hidden_field',
	 		    'text'=> __('Hidden','customize-my-account-pro'),
	 		    'icon'=> 'fa fa-file'

	 	    ),
	 	);
	 	return apply_filters('pcfme_override_field_types',$field_types);
	 }
}

if (! function_exists('pcfme_show_rule_type_01_td_values')) {

	 /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */

	 function pcfme_show_rule_type_01_td_values($value,$mnindex) {

	 	?>

	 	<td>
	 		<?php if (isset($value['action_type']) ) {  ?>
	 			<select class="checkout_field_rule_type" name="pcfme_additional_fees[<?php echo $mnindex; ?>][action_type]">
	 				<option value="show" <?php if (isset($value['action_type']) && ($value['action_type'] == "show")) { echo 'selected';} ?>>
	 					<?php echo esc_html__( 'Show' ,'customize-my-account-pro'); ?>        
	 				</option>
	 				<option value="hide" <?php if (isset($value['action_type']) && ($value['action_type'] == "hide")) { echo 'selected';} ?>>
	 					<?php echo esc_html__( 'Hide' ,'customize-my-account-pro'); ?>        
	 				</option>
	 			</select>
	 		<?php } else { ?>
	 			<strong><?php echo esc_html__( 'Add' ,'customize-my-account-pro'); ?></strong>
	 		<?php  } ?>
	 	</td>
	 	<td><?php if (isset($value['action_type']) ) {  

	 		$shipping_methods = WC()->shipping->get_shipping_methods();


	 		$payment_gateways = WC()->payment_gateways->get_available_payment_gateways();

	 		?>
	 		<select class="checkout_field_rule_actionfield" name="pcfme_additional_fees[<?php echo $mnindex; ?>][actionfield]">
	 			<optgroup label="<?php echo esc_html__( 'Payment Gateway' ,'customize-my-account-pro'); ?>">
	 				<?php foreach ($payment_gateways as $pkey=>$pvalue) { ?>

	 					<option value="payment_method_<?php echo $pkey; ?>"  <?php if (isset($value['actionfield']) && ($value['actionfield'] == 'payment_method_'.$pkey.'')) { echo 'selected';} ?>><?php echo $pkey; ?></option>

	 				<?php } ?>

	 			</optgroup>

	 			<optgroup label="<?php echo esc_html__( 'Shipping Method' ,'customize-my-account-pro'); ?>">
	 				<?php foreach ($shipping_methods as $skey=>$pvalue) { ?>

	 					<option value="shipping_method_<?php echo $skey; ?>"  <?php if (isset($value['actionfield']) && ($value['actionfield'] == 'shipping_method_'.$skey.'')) { echo 'selected';} ?>><?php echo $skey; ?></option>

	 				<?php } ?>

	 			</optgroup>

	 		</select>
	 	<?php } else { ?>

	 		<input type="number" step="0.01" name="pcfme_additional_fees[<?php echo $mnindex; ?>][amount]" value="<?php if (isset($value['amount']) ) { echo $value['amount']; } ?>" placeholder="<?php echo esc_html__( 'Amount' ,'customize-my-account-pro'); ?>">
	 	<?php  } ?>
	 </td>
	 <td>
	 	<?php if (!isset($value['action_type']) ) {  ?>
	 		<select class="checkout_field_rule_type" name="pcfme_additional_fees[<?php echo $mnindex; ?>][type]">
	 			<option value="fixed" <?php if (isset($value['type']) && ($value['type'] != "percentage")) { echo 'selected';} ?>>
	 				<?php echo esc_html__( 'Fixed Amount' ,'customize-my-account-pro'); ?>

	 			</option>
	 			<option value="percentage" <?php if (isset($value['type']) && ($value['type'] == "percentage")) { echo 'selected';} ?>>
	 				<?php echo esc_html__( 'Percentage' ,'customize-my-account-pro'); ?>

	 			</option>
	 		</select>
	 	<?php  } ?>
	 </td>
	 <?php


	}

}


if (! function_exists('pcfme_show_rule_type_02_td_values')) {

	 /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */

	function pcfme_show_rule_type_02_td_values($value,$mnindex) {

		$action_type = isset($value['add_deduct_type']) ? $value['add_deduct_type'] : "add";


	 	?>

	 	<td>
	 		
	 		<select class="checkout_field_rule_type" name="pcfme_additional_fees[<?php echo $mnindex; ?>][add_deduct_type]">
	 			<option value="add" <?php if (isset($value['add_deduct_type']) && ($value['add_deduct_type'] == "add")) { echo 'selected';} ?>>
	 				<?php echo esc_html__( 'Add' ,'customize-my-account-pro'); ?>        
	 			</option>
	 			<option value="deduct" <?php if (isset($value['add_deduct_type']) && ($value['add_deduct_type'] == "deduct")) { echo 'selected';} ?>>
	 				<?php echo esc_html__( 'Deduct' ,'customize-my-account-pro'); ?>        
	 			</option>
	 		</select>
	 		
	 	</td>
	 	<td>
	 		<?php echo esc_html__( 'Amount equal to price of ' ,'customize-my-account-pro'); ?>
	 	</td>
	 	<td>
	 		<select class="checkout_field_quantity_specific_product_fees" data-placeholder="<?php echo esc_html__('Choose Product','customize-my-account-pro'); ?>" name="pcfme_additional_fees[<?php echo $mnindex; ?>][specific-product]" style="width:600px">
	 			<?php if (isset($value['specific-product'])) { ?>
	 				<option value="<?php echo $value['specific-product']; ?>" selected>#<?php echo $value['specific-product'] ?>-<?php echo get_the_title($value['specific-product']); ?></option>
	 			<?php } ?>
	 		</select>
	 	</td>
	 	<?php


	}

}


if (! function_exists('pcfme_show_rule_type_03_td_values')) {

	 /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */

	 function pcfme_show_rule_type_03_td_values($value,$mnindex) {

	 	?>

	 	<td>

	 		<strong><?php echo esc_html__( 'Add' ,'customize-my-account-pro'); ?></strong>
	 		
	 	</td>
	 	<td>

	 		<input type="number" step="0.01" name="pcfme_additional_fees[<?php echo $mnindex; ?>][amount]" value="<?php if (isset($value['amount']) ) { echo $value['amount']; } ?>" placeholder="<?php echo esc_html__( 'Amount' ,'customize-my-account-pro'); ?>">

	 	</td>
	 	<td>
	 		<?php echo esc_html__( 'For each product in cart' ,'customize-my-account-pro'); ?>
	 	</td>
	 	<?php


	 }

}



if (! function_exists('pcfme_show_modal_popup')) {

	 /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */

	 function pcfme_show_modal_popup($each_class) {

	 	$dashicons_array = pcfme_easy_checkout_get_field_types();
	 	?>
	 	
	 	
	 	<div class="pcfme-field-types-tab" data-category="popular">

	 		<?php foreach ($dashicons_array as $key=>$value) { 

                $type = isset($value['type']) ? $value['type'] : "text";
                $icon = isset($value['icon']) ? $value['icon'] : "fa fa-pen-field";
                $text = isset($value['text']) ? $value['text'] : "Field";

	 			?>
	 			<a href="#" class="<?php echo $each_class; ?> <?php echo $type; ?>" data-field-type="<?php echo $type; ?>">
                    <input type="radio" class="pcfme_hidden_radio_input" value="<?php echo $type; ?>" name="pcfme_type">
	 				<i class="pcfme_fa_icon <?php echo $icon; ?>"></i>
	 				<span class="pcfme_field_type_label"><?php echo $text; ?></span>
	 			</a>

	 		<?php } ?>
			
			
		</div>
	 	<?php
	 }
}

if ( ! function_exists( 'pcfme_check_if_field_is_hidden' ) ) {

    /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */


    function pcfme_check_if_field_is_hidden($hiddenvalue,$allowedproduts ,$allowedcats,$allowedroles,$total_quantity,$prd,$prd_qnty,$dynamic_rules,$dynamic_criteria ) {
    	global $woocommerce;
    	if( ! $woocommerce->cart ) { return; }
    	$cart_items = $woocommerce->cart->get_cart();
    	$extra_options = (array) get_option( 'pcfme_extra_settings' );



    	switch($hiddenvalue) {
    		case "product-specific" :
    		$allowedproductindex =0;

    		if (( ! empty( $allowedproduts ) ) && (is_array($allowedproduts)))  {

    			foreach ($allowedproduts as $allowedproductkey=>$allowedproductid) {

    				foreach ($cart_items as $cartitem_key=>$cartitemvalue) {


    					if (isset($cartitemvalue['variation_id']) &&  ($cartitemvalue['variation_id'] != 0)) {
    						$product_id=$cartitemvalue['variation_id'];
    					} else {
    						$product_id=$cartitemvalue['product_id'];
    					}



    					if ($product_id == $allowedproductid) {
    						$allowedproductindex++;
    					}
    				}
    			}
    		}

    		if ($allowedproductindex == 0)  {

    			return 0;
    		} else {

    			return 1;
    		}

    		break;

    		case "category-specific" :
    		$categoryproductindex = 0;

    		if (( ! empty( $allowedcats ) ) && (is_array($allowedcats)))  {

    			foreach ($allowedcats as $allowedcatvalue) {

    				foreach ($cart_items as $cartitem_key=>$cartitemvalue) {

    					$product_id=$cartitemvalue['product_id'];

    					$catterms = get_the_terms( $product_id, 'product_cat' );

    					if (( ! empty( $catterms ) ) && (is_array($catterms)))  {

    						foreach ($catterms as $catterm) {
    							if ($catterm->term_id == $allowedcatvalue) {
    								$categoryproductindex++;
    							}
    						}
    					}


    				}
    			}
    		}

    		if ($categoryproductindex == 0)  {

    			return 0;

    		} else {

    			return 1;
    		}

    		break;

    		case "role-specific" :
    		$role_status       = 0;



    		if (isset($allowedroles) && is_array($allowedroles) && (!empty($allowedroles))) {
    			if ( ! is_user_logged_in() ) {
    				$role_status       = 0;
    				return $role_status; 
    			}

    			$allowedauthors = '';

    			foreach ($allowedroles as $role) {
    				$allowedauthors.=''.$role.',';
    			}

    			$allowedauthors=substr_replace($allowedauthors, "", -1);

    			global $current_user;
    			$user_roles = $current_user->roles;
    			$user_role = array_shift($user_roles);



    			if (preg_match('/\b'.$user_role.'\b/', $allowedauthors )) {
    				$role_status       = 1;
    				return $role_status;
    			}

    		}

    		if (empty($allowedroles) && ( ! is_user_logged_in() )) {
    			$role_status       = 1;
    			return $role_status;
    		}



    		return $role_status; 

    		break;

    		case "total-quantity" :
    		$quantity_index       = 0;

    		if (!isset($total_quantity) || ($total_quantity == 0)) {
    			return 0;
    		} 

    		$cart_count = $woocommerce->cart->cart_contents_count;

    		if ($cart_count == $total_quantity) {

    			return 1;

    		}

    		return $quantity_index;

    		break;



    		case "cart-quantity-specific" :

    		$product_quantity_index       = 0;

    		if (!isset($prd) || ($prd == 0)) {
    			return 0;
    		} 

    		if (!isset($prd_qnty) || ($prd_qnty == 0)) {
    			return 0;
    		} 


    		foreach ($cart_items as $cartitem_key=>$cartitemvalue) {


    			if (isset($cartitemvalue['variation_id']) &&  ($cartitemvalue['variation_id'] != 0)) {
    				$product_id=$cartitemvalue['variation_id'];
    			} else {
    				$product_id=$cartitemvalue['product_id'];
    			}





    			if (($product_id == $prd) && ($cartitemvalue['quantity'] == $prd_qnty)) {
    				$product_quantity_index++;

    			} 
    		}

    		if ($product_quantity_index > 1) {
    			return 1;
    		}

    		return $product_quantity_index;

    		break;

    		case "always-visible" :
    		return 1;
    		break;

    		case "hide-downloadable":

    		$downloadable_match_index       = 0;

            $cart_keys = count($cart_items);

    		foreach ($cart_items as $cartitem_key=>$cartitemvalue) {


    			if (isset($cartitemvalue['variation_id']) &&  ($cartitemvalue['variation_id'] != 0)) {
    				$dproduct_id=$cartitemvalue['variation_id'];
    			} else {
    				$dproduct_id=$cartitemvalue['product_id'];
    			}


                $_dproduct = wc_get_product($dproduct_id);


                if ($_dproduct->is_downloadable('yes')) {
                     $downloadable_match_index++;
                }


    		}

    		if ($downloadable_match_index == $cart_keys) {
    			return 0;
    		}

    		break;

    		case "hide-virtual":

    		$virtual_match_index       = 0;

            $cart_keys = count($cart_items);

    		foreach ($cart_items as $cartitem_key=>$cartitemvalue) {


    			if (isset($cartitemvalue['variation_id']) &&  ($cartitemvalue['variation_id'] != 0)) {
    				$dproduct_id=$cartitemvalue['variation_id'];
    			} else {
    				$dproduct_id=$cartitemvalue['product_id'];
    			}


                $_dproduct = wc_get_product($dproduct_id);


                if ($_dproduct->is_virtual('yes')) {
                     $virtual_match_index++;
                }


    		}

    		if ($virtual_match_index == $cart_keys) {
    			return 0;
    		}

    		break;

    		case "dynamically-visible":

    		    $dynamic_match_index = 0;



    		    $dynamic_match_index = pcfme_loop_through_dynamic_rules($dynamic_rules,$dynamic_criteria);

    		    return $dynamic_match_index;

    		break;

    		default:
    		return 1;
    	}
    }
}

/**
 * License activation reminder.
 *
 * @since 1.0.0
 * @param string .
 * @return string
 */

if (!function_exists('pcfme_count_item_in_cart')) {


	function pcfme_count_item_in_cart() {
		$count = 0;
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$count++;
		}
		return $count;
	}

}


/**
 * License activation reminder.
 *
 * @since 1.0.0
 * @param string .
 * @return string
 */

if (!function_exists('pcfme_does_rule_match_found')) {

	function pcfme_does_rule_match_found($rulevalue) { 

		$rule_matched = 'no';

        $rule_type = isset($rulevalue['rule_type']) ? $rulevalue['rule_type'] : "";

        switch($rule_type) {

        	case "date_date":

        	    $from_to_specific = isset($rulevalue['from_to_specific']) ? $rulevalue['from_to_specific'] : "from";
        	    $rule_type_date   = isset($rulevalue['rule_type_date']) ? $rulevalue['rule_type_date'] : "";

        	    $rule_type_date   = date("Ymd",strtotime($rule_type_date));

        	    $date_today      = date("Ymd");
                
              

        	    if (!isset($rule_type_date) || ($rule_type_date == "")) {
        	    	$rule_matched = 'no';

        	        return $rule_matched;
        	    }

        	    if (isset($from_to_specific) && isset($from_to_specific)) {

        	    	switch($from_to_specific) {

        	    		case "from":

        	    		   

        	    		    if ($date_today > $rule_type_date) {
        	    		    	$rule_matched = 'yes';

        	    		    	return $rule_matched;
        	    		    }

        	    		break;

        	    		case "to":

        	    		  
        	    		    if ($date_today < $rule_type_date) {
        	    		    	$rule_matched = 'yes';

        	    		    	return $rule_matched;
        	    		    }

        	    		break;

        	    		case "specific":

        	    		  

        	    		    if ($date_today == $rule_type_date) {
        	    		    	$rule_matched = 'yes';

        	    		    	return $rule_matched;
        	    		    }

        	    		break;

        	    	}

        	    }

        	break;

        	case "date_time":

        	    $from_to = isset($rulevalue['from_to']) ? $rulevalue['from_to'] : "from";
        	    $rule_type_time   = isset($rulevalue['rule_type_time']) ? $rulevalue['rule_type_time'] : "";

        	    $rule_type_time   = date("Hi",strtotime($rule_type_time));

        	    $time_now      = date("Hi");
                
                

        	    if (!isset($rule_type_time) || ($rule_type_time == "")) {
        	    	$rule_matched = 'no';

        	        return $rule_matched;
        	    }

        	    if (isset($from_to) && isset($from_to)) {

        	    	switch($from_to) {

        	    		case "from":

        	    		   

        	    		    if ($time_now > $rule_type_time) {
        	    		    	$rule_matched = 'yes';

        	    		    	return $rule_matched;
        	    		    }

        	    		break;

        	    		case "to":

        	    		  
        	    		    if ($time_now < $rule_type_time) {
        	    		    	$rule_matched = 'yes';

        	    		    	return $rule_matched;
        	    		    }

        	    		break;

        	    		

        	    	}

        	    }
        	break;

        	case "date_date_time":

        	 $from_to = isset($rulevalue['from_to']) ? $rulevalue['from_to'] : "from";
        	 $rule_type_date_time   = isset($rulevalue['rule_type_date_time']) ? $rulevalue['rule_type_date_time'] : "";

        	 $rule_type_date_time   = date("YmdHis",strtotime($rule_type_date_time));
        	 $current_date_time = current_datetime()->format('YmdHis');

        	 if (!isset($rule_type_date_time) || ($rule_type_date_time == "")) {
        	    	$rule_matched = 'no';

        	        return $rule_matched;
        	 }

        	    if (isset($from_to) && isset($from_to)) {

        	    	switch($from_to) {

        	    		case "from":

        	    		   

        	    		    if ($current_date_time > $rule_type_date_time) {
        	    		    	$rule_matched = 'yes';

        	    		    	return $rule_matched;
        	    		    }

        	    		break;

        	    		case "to":

        	    		  
        	    		    if ($current_date_time < $rule_type_date_time) {
        	    		    	$rule_matched = 'yes';

        	    		    	return $rule_matched;
        	    		    }

        	    		break;

        	    		

        	    	}

        	    }

        	break;

        	case "date_week_day":

        	   $contains_rule = isset($rulevalue['rule_type_contains']) ? $rulevalue['rule_type_contains'] : "contains_any";

        	   $weekdays      = isset($rulevalue['rule_type_days']) ? $rulevalue['rule_type_days'] : array();

        	   $currentday    = date("D");

        	   $currentday    = strtolower($currentday);


        	   switch($contains_rule) {

        	   	case "contains_any":



        	   	$allowedproductindex =0;


        	   	foreach ($weekdays as $rkey=>$rvalue) {
        	   		

        	   		if ($rvalue == $currentday) {
        	   			$allowedproductindex++;
        	   		}
        	   	}



        	   	if ($allowedproductindex > 0)  {

        	   		$rule_matched = 'yes';

        	   		return $rule_matched;
        	   	} else {

        	   		$rule_matched = 'no';

        	   		return $rule_matched;
        	   	}

        	   	break;

        	   	case "contains_all":

        	   	$allowedproductindex =0;

        	   	$allowed_match_found_index = 0;






        	   	foreach ($weekdays as $rkey=>$rvalue) {
        	   		

        	   		if ($rvalue == $currentday) {
        	   			$allowedproductindex++;
        	   		}

        	   		$allowed_match_found_index++;
        	   	}






        	   	if ($allowedproductindex == $allowed_match_found_index)  {

        	   		$rule_matched = 'yes';

        	   		return $rule_matched;
        	   	} else {

        	   		$rule_matched = 'no';

        	   		return $rule_matched;
        	   	}

        	   	break;

        	   	case "does_not_contain_any":

        	   	$allowedproductindex =0;



        	   	foreach ($weekdays as $rkey=>$rvalue) {
        	   		

        	   		if ($rvalue == $currentday) {
        	   			$allowedproductindex++;
        	   		}
        	   	}




        	   	if ($allowedproductindex > 0)  {

        	   		$rule_matched = 'no';

        	   		return $rule_matched;
        	   	} else {

        	   		$rule_matched = 'yes';

        	   		return $rule_matched;
        	   	}

        	   	break;

        	   	case "does_not_contain_all":

        	   	$allowedproductindex =0;

        	   	$allowed_match_found_index = 0;



        	   	foreach ($weekdays as $rkey=>$rvalue) {
        	   		

        	   		if ($rvalue == $currentday) {
        	   			$allowedproductindex++;
        	   		}

        	   		$allowed_match_found_index++;
        	   	}






        	   	if ($allowedproductindex == $allowed_match_found_index)  {

        	   		$rule_matched = 'no';

        	   		return $rule_matched;
        	   	} else {

        	   		$rule_matched = 'yes';

        	   		return $rule_matched;
        	   	}

        	   	break;


        	   }

        	break;

        	case "cart__quantity":

        	    $compare = isset($rulevalue['rule_type_compare']) ? $rulevalue['rule_type_compare'] : "less_than";

        	    if (isset($rulevalue['rule_type_number'])) {

        	    	 $number = $rulevalue['rule_type_number'];

        	    }


        	    if (isset($compare) && isset($number)) {

        	    	$cart_total_quantity = WC()->cart->get_cart_contents_count();

        	    	switch($compare) {

        	    		case "less_than":

        	    		    if ($cart_total_quantity < $number) {
        	    		   	    $rule_matched = 'yes';

        	    		   	    return $rule_matched;
        	    		    }

        	    		break;

        	    		case "greater_than":

        	    		    if ($cart_total_quantity > $number) {
        	    		   	    $rule_matched = 'yes';

        	    		   	    return $rule_matched;
        	    		    }

        	    		break;

        	    		case "greater_than_equal_to":

        	    		   if ($cart_total_quantity >= $number) {
        	    		   	    $rule_matched = 'yes';

        	    		   	    return $rule_matched;
        	    		   }

        	    		break;

        	    	}
        	    }
                
    	   
        	    
        	break;

        	case "cart__count":

        	   $compare = isset($rulevalue['rule_type_compare']) ? $rulevalue['rule_type_compare'] : "less_than";

        	    if (isset($rulevalue['rule_type_number'])) {

        	    	 $number = $rulevalue['rule_type_number'];

        	    }


        	    if (isset($compare) && isset($number)) {

        	    	$cart_total_quantity = pcfme_count_item_in_cart();



        	    	switch($compare) {

        	    		case "less_than":

        	    		    if ($cart_total_quantity < $number) {
        	    		   	    $rule_matched = 'yes';

        	    		   	    return $rule_matched;
        	    		    }

        	    		break;

        	    		case "greater_than":

        	    		    if ($cart_total_quantity > $number) {
        	    		   	    $rule_matched = 'yes';

        	    		   	    return $rule_matched;
        	    		    }

        	    		break;

        	    		case "greater_than_equal_to":

        	    		   if ($cart_total_quantity >= $number) {
        	    		   	    $rule_matched = 'yes';

        	    		   	    return $rule_matched;
        	    		   }

        	    		break;

        	    	}
        	    }
        	    
        	break;

        	case "cart__weight":


				$compare = isset($rulevalue['rule_type_compare']) ? $rulevalue['rule_type_compare'] : "less_than";

        	    if (isset($rulevalue['rule_type_number'])) {

        	    	 $number = $rulevalue['rule_type_number'];

        	    }


        	    if (isset($compare) && isset($number)) {

					global $woocommerce;

        	    	$cart_total_quantity = $woocommerce->cart->total;
                    



        	    	switch($compare) {

        	    		case "less_than":

        	    		    if ($cart_total_quantity < $number) {
        	    		   	    $rule_matched = 'yes';

        	    		   	    return $rule_matched;
        	    		    }

        	    		break;

        	    		case "greater_than":

        	    		    if ($cart_total_quantity > $number) {
        	    		   	    $rule_matched = 'yes';

        	    		   	    return $rule_matched;
        	    		    }

        	    		break;

        	    		case "greater_than_equal_to":

        	    		   if ($cart_total_quantity >= $number) {
        	    		   	    $rule_matched = 'yes';

        	    		   	    return $rule_matched;
        	    		   }

        	    		break;

        	    	}
        	    }

        	    
        	   
        	break;

        	case "user_is_logged_in":
        	$rule_matched = 'no';

        	if ( is_user_logged_in() ) {
        		$rule_matched = 'yes';
        		return $rule_matched;
        	} else {
        		$rule_matched = 'no';
        		return $rule_matched;
        	}
        	break;

        	case "user_is_logged_out":
        	   $rule_matched = 'no';

        	   if ( is_user_logged_in() ) {
        		$rule_matched = 'no';
        		return $rule_matched;
        	   } else {
        		$rule_matched = 'yes';
        		return $rule_matched;
        	   }
        	break;

        	case "coupon_applied":

        	$contains_rule = isset($rulevalue['rule_type_contains']) ? $rulevalue['rule_type_contains'] : "contains_any";

        	$rule_coupons =  isset($rulevalue['rule_type_coupons']) ? $rulevalue['rule_type_coupons'] : array();


        	global $woocommerce;
    	        
    	    if( ! $woocommerce->cart ) { return $rule_matched; }
    	        
    	    $cart_items = $woocommerce->cart->get_cart();

    	    $applied_coupons = $woocommerce->cart->get_applied_coupons();

    	    


    	    switch($contains_rule) {

        		case "contains_any":

        		

    	        $allowedproductindex =0;



    	        

                    
    	        foreach ($rule_coupons as $rkey=>$rvalue) {
    	        	$coupon_title = get_the_title($rvalue);

    	        	if (in_array($coupon_title, $applied_coupons)) {
    	        		$allowedproductindex++;
    	        	}
    	        }

                   
    	        

    	        

    	       


    	        if ($allowedproductindex > 0)  {

    	        	$rule_matched = 'yes';

    	        	return $rule_matched;
    	        } else {

    	        	$rule_matched = 'no';

    	        	return $rule_matched;
    	        }

                 break;

                 case "contains_all":

                 $allowedproductindex =0;

                 $allowed_match_found_index = 0;




                 

                 foreach ($rule_coupons as $rkey=>$rvalue) {
    	        	$coupon_title = get_the_title($rvalue);

    	        	if (in_array($coupon_title, $applied_coupons)) {
    	        		$allowedproductindex++;
    	        	}

    	        	$allowed_match_found_index++;
    	        }

    	        

    	       


        		if ($allowedproductindex == $allowed_match_found_index)  {

        			$rule_matched = 'yes';

        	        return $rule_matched;
        		} else {

        			$rule_matched = 'no';

        	        return $rule_matched;
        		}

                 break;

                 case "does_not_contain_any":

                 $allowedproductindex =0;



    	        foreach ($rule_coupons as $rkey=>$rvalue) {
    	        	$coupon_title = get_the_title($rvalue);

    	        	if (in_array($coupon_title, $applied_coupons)) {
    	        		$allowedproductindex++;
    	        	}
    	        }

    	       


    	        if ($allowedproductindex > 0)  {

    	        	$rule_matched = 'no';

    	        	return $rule_matched;
    	        } else {

    	        	$rule_matched = 'yes';

    	        	return $rule_matched;
    	        }

                 break;

                 case "does_not_contain_all":

                 $allowedproductindex =0;

                 $allowed_match_found_index = 0;

                 

                 foreach ($rule_coupons as $rkey=>$rvalue) {
    	        	$coupon_title = get_the_title($rvalue);

    	        	if (in_array($coupon_title, $applied_coupons)) {
    	        		$allowedproductindex++;
    	        	}

    	        	$allowed_match_found_index++;
    	        }

    	        

    	       


        		if ($allowedproductindex == $allowed_match_found_index)  {

        			$rule_matched = 'no';

        	        return $rule_matched;
        		} else {

        			$rule_matched = 'yes';

        	        return $rule_matched;
        		}

                 break;

        		
        	}

        	case "date_months":

        	    $contains_rule = isset($rulevalue['rule_type_contains']) ? $rulevalue['rule_type_contains'] : "contains_any";

        	    $months      = isset($rulevalue['rule_type_months']) ? $rulevalue['rule_type_months'] : array();

        	    $currentmonth    = date("M");

        	    $currentmonth    = strtolower($currentmonth);

        	    //echo $currentmonth;

        	    //print_r($months);

        	    switch($contains_rule) {

        	   	case "contains_any":



        	   	$allowedproductindex = 0;


        	   	foreach ($months as $rkey=>$rvalue) {
        	   		
        	   		

        	   		if ($rvalue == $currentmonth) {
        	   			$allowedproductindex++;
        	   		}
        	   	}
                



        	   	if ($allowedproductindex > 0)  {

        	   		$rule_matched = 'yes';

        	   		return $rule_matched;
        	   	} else {

        	   		$rule_matched = 'no';

        	   		return $rule_matched;
        	   	}

        	   	break;

        	   	case "contains_all":

        	   	$allowedproductindex =0;

        	   	$allowed_match_found_index = 0;






        	   	foreach ($months as $rkey=>$rvalue) {
        	   		

        	   		if ($rvalue == $currentmonth) {
        	   			$allowedproductindex++;
        	   		}

        	   		$allowed_match_found_index++;
        	   	}






        	   	if ($allowedproductindex == $allowed_match_found_index)  {

        	   		$rule_matched = 'yes';

        	   		return $rule_matched;
        	   	} else {

        	   		$rule_matched = 'no';

        	   		return $rule_matched;
        	   	}

        	   	break;

        	   	case "does_not_contain_any":

        	   	$allowedproductindex =0;



        	   	foreach ($months as $rkey=>$rvalue) {
        	   		

        	   		if ($rvalue == $currentmonth) {
        	   			$allowedproductindex++;
        	   		}
        	   	}




        	   	if ($allowedproductindex > 0)  {

        	   		$rule_matched = 'no';

        	   		return $rule_matched;
        	   	} else {

        	   		$rule_matched = 'yes';

        	   		return $rule_matched;
        	   	}

        	   	break;

        	   	case "does_not_contain_all":

        	   	$allowedproductindex =0;

        	   	$allowed_match_found_index = 0;



        	   	foreach ($months as $rkey=>$rvalue) {
        	   		

        	   		if ($rvalue == $currentmonth) {
        	   			$allowedproductindex++;
        	   		}

        	   		$allowed_match_found_index++;
        	   	}






        	   	if ($allowedproductindex == $allowed_match_found_index)  {

        	   		$rule_matched = 'no';

        	   		return $rule_matched;
        	   	} else {

        	   		$rule_matched = 'yes';

        	   		return $rule_matched;
        	   	}

        	   	break;


        	   }

        	break;

        	case "date_month_days":

        	   $contains_rule = isset($rulevalue['rule_type_contains']) ? $rulevalue['rule_type_contains'] : "contains_any";

        	   $monthdays      = isset($rulevalue['rule_type_monthdays']) ? $rulevalue['rule_type_monthdays'] : array();

        	   $currentdate    = date("d");

        	  
               $currentdate    = (int) $currentdate;

               

        	   switch($contains_rule) {

        	   	case "contains_any":



        	   	$allowedproductindex = 0;


        	   	foreach ($monthdays as $rkey=>$rvalue) {
        	   		
        	   		$rvalue = (int) $rvalue;

        	   		if ($rvalue == $currentdate) {
        	   			$allowedproductindex++;
        	   		}
        	   	}
                



        	   	if ($allowedproductindex > 0)  {

        	   		$rule_matched = 'yes';

        	   		return $rule_matched;
        	   	} else {

        	   		$rule_matched = 'no';

        	   		return $rule_matched;
        	   	}

        	   	break;

        	   	case "contains_all":

        	   	$allowedproductindex =0;

        	   	$allowed_match_found_index = 0;






        	   	foreach ($monthdays as $rkey=>$rvalue) {
        	   		

        	   		if ($rvalue == $currentdate) {
        	   			$allowedproductindex++;
        	   		}

        	   		$allowed_match_found_index++;
        	   	}






        	   	if ($allowedproductindex == $allowed_match_found_index)  {

        	   		$rule_matched = 'yes';

        	   		return $rule_matched;
        	   	} else {

        	   		$rule_matched = 'no';

        	   		return $rule_matched;
        	   	}

        	   	break;

        	   	case "does_not_contain_any":

        	   	$allowedproductindex =0;



        	   	foreach ($monthdays as $rkey=>$rvalue) {
        	   		

        	   		if ($rvalue == $currentdate) {
        	   			$allowedproductindex++;
        	   		}
        	   	}




        	   	if ($allowedproductindex > 0)  {

        	   		$rule_matched = 'no';

        	   		return $rule_matched;
        	   	} else {

        	   		$rule_matched = 'yes';

        	   		return $rule_matched;
        	   	}

        	   	break;

        	   	case "does_not_contain_all":

        	   	$allowedproductindex =0;

        	   	$allowed_match_found_index = 0;



        	   	foreach ($monthdays as $rkey=>$rvalue) {
        	   		

        	   		if ($rvalue == $currentdate) {
        	   			$allowedproductindex++;
        	   		}

        	   		$allowed_match_found_index++;
        	   	}






        	   	if ($allowedproductindex == $allowed_match_found_index)  {

        	   		$rule_matched = 'no';

        	   		return $rule_matched;
        	   	} else {

        	   		$rule_matched = 'yes';

        	   		return $rule_matched;
        	   	}

        	   	break;


        	   }
        	break;



        	break;

        	case "cart_items__products":

        	$contains_rule = isset($rulevalue['rule_type_contains']) ? $rulevalue['rule_type_contains'] : "contains_any";

        	$rule_products =  isset($rulevalue['rule_type_products']) ? $rulevalue['rule_type_products'] : array();

        	global $woocommerce;
    	        
    	    if( ! $woocommerce->cart ) { return $rule_matched; }
    	        
    	    $cart_items = $woocommerce->cart->get_cart();

        	switch($contains_rule) {

        		case "contains_any":

        		

    	        $allowedproductindex =0;



    	        foreach ($cart_items as $cartitem_key=>$cartitemvalue) {


    	        	if (isset($cartitemvalue['variation_id']) &&  ($cartitemvalue['variation_id'] != 0)) {
    	        		$product_id=$cartitemvalue['variation_id'];
    	        	} else {
    	        		$product_id=$cartitemvalue['product_id'];
    	        	}


    	        	



    	        	if (in_array($product_id, $rule_products)) {
    	        		$allowedproductindex++;
    	        	}
    	        }

    	       


    	        if ($allowedproductindex > 0)  {

    	        	$rule_matched = 'yes';

    	        	return $rule_matched;
    	        } else {

    	        	$rule_matched = 'no';

    	        	return $rule_matched;
    	        }

                 break;

                 case "contains_all":

                 $allowedproductindex =0;

                 $allowed_match_found_index = 0;

                 

                foreach($rule_products as $product_key=>$product_value) {

                	foreach ($cart_items as $cartitem_key=>$cartitemvalue) {


                		if (isset($cartitemvalue['variation_id']) &&  ($cartitemvalue['variation_id'] != 0)) {
                			$product_id=$cartitemvalue['variation_id'];
                		} else {
                			$product_id=$cartitemvalue['product_id'];
                		}






                		if ($product_id  == $product_value) {
                			$allowed_match_found_index++;
                		}

                		
                	}

                	$allowedproductindex++;

                }

    	        

    	       


        		if ($allowedproductindex == $allowed_match_found_index)  {

        			$rule_matched = 'yes';

        	        return $rule_matched;
        		} else {

        			$rule_matched = 'no';

        	        return $rule_matched;
        		}

                 break;

                 case "does_not_contain_any":

                 $allowedproductindex =0;



    	        foreach ($cart_items as $cartitem_key=>$cartitemvalue) {


    	        	if (isset($cartitemvalue['variation_id']) &&  ($cartitemvalue['variation_id'] != 0)) {
    	        		$product_id=$cartitemvalue['variation_id'];
    	        	} else {
    	        		$product_id=$cartitemvalue['product_id'];
    	        	}


    	        	



    	        	if (in_array($product_id, $rule_products)) {
    	        		$allowedproductindex++;
    	        	}
    	        }

    	       


    	        if ($allowedproductindex > 0)  {

    	        	$rule_matched = 'no';

    	        	return $rule_matched;
    	        } else {

    	        	$rule_matched = 'yes';

    	        	return $rule_matched;
    	        }

                 break;

                 case "does_not_contain_all":

                 $allowedproductindex =0;

                 $allowed_match_found_index = 0;

                 

                foreach($rule_products as $product_key=>$product_value) {

                	foreach ($cart_items as $cartitem_key=>$cartitemvalue) {


                		if (isset($cartitemvalue['variation_id']) &&  ($cartitemvalue['variation_id'] != 0)) {
                			$product_id=$cartitemvalue['variation_id'];
                		} else {
                			$product_id=$cartitemvalue['product_id'];
                		}






                		if ($product_id  == $product_value) {
                			$allowed_match_found_index++;
                		}

                		
                	}

                	$allowedproductindex++;

                }

    	        

    	       


        		if ($allowedproductindex == $allowed_match_found_index)  {

        			$rule_matched = 'no';

        	        return $rule_matched;
        		} else {

        			$rule_matched = 'yes';

        	        return $rule_matched;
        		}

                 break;

        		
        	}
        	    
        	break;


        	case "cart_items__product_categories":


        	$contains_rule = isset($rulevalue['rule_type_contains']) ? $rulevalue['rule_type_contains'] : "contains_any";

        	$alowed_cats =  isset($rulevalue['rule_type_categories']) ? $rulevalue['rule_type_categories'] : array();

        	global $woocommerce;
    	        
    	    if( ! $woocommerce->cart ) { return $rule_matched; }
    	        
    	    $cart_items = $woocommerce->cart->get_cart();

			switch($contains_rule) {

        		case "contains_any":

        		

    	        $allowedproductindex =0;



    	        foreach ($cart_items as $cartitem_key=>$cartitemvalue) {


    	        	


    	        	$product_id=$cartitemvalue['product_id'];

    				$catterms = get_the_terms( $product_id, 'product_cat' );

					

    				if (( ! empty( $catterms ) ) && (is_array($catterms)))  {

    					foreach ($catterms as $catterm) {

							
    						if (in_array($catterm->term_id, $alowed_cats)) {
    							$allowedproductindex++;
    						}
    					}
    				}



    	        	
    	        }

    	       


    	        if ($allowedproductindex > 0)  {

    	        	$rule_matched = 'yes';

    	        	return $rule_matched;
    	        } else {

    	        	$rule_matched = 'no';

    	        	return $rule_matched;
    	        }

                 break;

                 case "contains_all":

                 $allowedproductindex =0;

                 $allowed_match_found_index = 0;

                 
				 foreach ($cart_items as $cartitem_key=>$cartitemvalue) {
                

                	foreach($alowed_cats as $product_key=>$product_value) {


                		$product_id=$cartitemvalue['product_id'];

    				    $catterms = get_the_terms( $product_id, 'product_cat' );

    				    if (( ! empty( $catterms ) ) && (is_array($catterms)))  {

    					    foreach ($catterms as $catterm) {
    						    if (in_array($catterm->term_id, $alowed_cats)) {
    							    $allowed_match_found_index++;
    						    }
    					    }
    				    }



                		
                	}

                	$allowedproductindex++;

                }

    	        
                $cart_total_quantity = pcfme_count_item_in_cart();
    	       
                $allowedproductindex = $cart_total_quantity * $allowedproductindex;

        		if ($allowedproductindex == $allowed_match_found_index)  {

        			$rule_matched = 'yes';

        	        return $rule_matched;
        		} else {

        			$rule_matched = 'no';

        	        return $rule_matched;
        		}

                 break;

                 case "does_not_contain_any":

                 $allowedproductindex =0;



    	        foreach ($cart_items as $cartitem_key=>$cartitemvalue) {


    	        	$product_id=$cartitemvalue['product_id'];

    				$catterms = get_the_terms( $product_id, 'product_cat' );

    				if (( ! empty( $catterms ) ) && (is_array($catterms)))  {

    					foreach ($catterms as $catterm) {
    						if (in_array($catterm->term_id, $alowed_cats)) {
    							$allowedproductindex++;
    						}
    					}
    				}


    	        }

    	       


    	        if ($allowedproductindex > 0)  {

    	        	$rule_matched = 'no';

    	        	return $rule_matched;
    	        } else {

    	        	$rule_matched = 'yes';

    	        	return $rule_matched;
    	        }

                 break;

                case "does_not_contain_all":

                $allowedproductindex =0;

                $allowed_match_found_index = 0;

                 

                foreach ($cart_items as $cartitem_key=>$cartitemvalue) {
                

                	foreach($alowed_cats as $product_key=>$product_value) {


                		$product_id=$cartitemvalue['product_id'];

    				    $catterms = get_the_terms( $product_id, 'product_cat' );

    				    if (( ! empty( $catterms ) ) && (is_array($catterms)))  {

    					    foreach ($catterms as $catterm) {
    						    if (in_array($catterm->term_id, $alowed_cats)) {
    							    $allowed_match_found_index++;
    						    }
    					    }
    				    }

                		
                	}

                	$allowedproductindex++;

                }

    	        
                $cart_total_quantity = pcfme_count_item_in_cart();
    	       
                $allowedproductindex = $cart_total_quantity * $allowedproductindex;
    	       


        		if ($allowedproductindex == $allowed_match_found_index)  {

        			$rule_matched = 'no';

        	        return $rule_matched;
        		} else {

        			$rule_matched = 'yes';

        	        return $rule_matched;
        		}

                 break;

			}
        	    
        	break;

        	case "user_role":

				$role_status       = 'no';
                
				$contains_rule = isset($rulevalue['rule_type_contains']) ? $rulevalue['rule_type_contains'] : "contains_any";

        	    $allowedroles =  isset($rulevalue['rule_type_roles']) ? $rulevalue['rule_type_roles'] : array();

				


    		    if (isset($allowedroles) && is_array($allowedroles) && (!empty($allowedroles))) {
    			if ( ! is_user_logged_in() ) {
    				$role_status       = 'no';
    				return $role_status; 
    			}

    			$allowedauthors = '';

    			foreach ($allowedroles as $role) {
    				$allowedauthors.=''.$role.',';
    			}

    			$allowedauthors=substr_replace($allowedauthors, "", -1);

    			global $current_user;
    			$user_roles = $current_user->roles;
    			$user_role = array_shift($user_roles);



    			if (preg_match('/\b'.$user_role.'\b/', $allowedauthors )) {
    				$role_status       = "yes";
    				return $role_status;
    			}

    		    }

    		    if (empty($allowedroles) && ( ! is_user_logged_in() )) {
    			    $role_status       = "yes";
    			    return $role_status;
    		    }



    		    return $role_status; 
        	    
        	break;


        	case "customer_total_spent":
        	    

        	    if ( ! is_user_logged_in() ) {
    				$rule_matched       = 'no';
    				return $rule_matched; 
    			}

				$compare = isset($rulevalue['rule_type_compare']) ? $rulevalue['rule_type_compare'] : "less_than";

        	    if (isset($rulevalue['rule_type_number'])) {

        	    	 $number = $rulevalue['rule_type_number'];

        	    }


        	    if (isset($compare) && isset($number)) {

					global $woocommerce;

					$current_user = wp_get_current_user();

        	    	$customer_total_spent = wc_get_customer_total_spent( $current_user->ID );
                    



        	    	switch($compare) {

        	    		case "less_than":

        	    		    if ($customer_total_spent < $number) {
        	    		   	    $rule_matched = 'yes';

        	    		   	    return $rule_matched;
        	    		    }

        	    		break;

        	    		case "greater_than":

        	    		    if ($customer_total_spent > $number) {
        	    		   	    $rule_matched = 'yes';

        	    		   	    return $rule_matched;
        	    		    }

        	    		break;

        	    		case "greater_than_equal_to":

        	    		   if ($customer_total_spent >= $number) {
        	    		   	    $rule_matched = 'yes';

        	    		   	    return $rule_matched;
        	    		   }

        	    		break;

        	    	}
        	    }

        	    
        	break;


        	case "customer_order_count":

        	    if ( ! is_user_logged_in() ) {
    				$rule_matched       = 'no';
    				return $rule_matched; 
    			}

				$compare = isset($rulevalue['rule_type_compare']) ? $rulevalue['rule_type_compare'] : "less_than";

        	    if (isset($rulevalue['rule_type_number'])) {

        	    	 $number = $rulevalue['rule_type_number'];

        	    }


        	    if (isset($compare) && isset($number)) {

					global $woocommerce;

					$current_user = wp_get_current_user();

        	    	$customer_order_count = wc_get_customer_order_count( $current_user->ID );
                    



        	    	switch($compare) {

        	    		case "less_than":

        	    		    if ($customer_order_count < $number) {
        	    		   	    $rule_matched = 'yes';

        	    		   	    return $rule_matched;
        	    		    }

        	    		break;

        	    		case "greater_than":

        	    		    if ($customer_order_count > $number) {
        	    		   	    $rule_matched = 'yes';

        	    		   	    return $rule_matched;
        	    		    }

        	    		break;

        	    		case "greater_than_equal_to":

        	    		   if ($customer_order_count >= $number) {
        	    		   	    $rule_matched = 'yes';

        	    		   	    return $rule_matched;
        	    		   }

        	    		break;

        	    	}
        	    }

        	    
        	break;

        	
        }

		return $rule_matched;

	}

}


/**
 * License activation reminder.
 *
 * @since 1.0.0
 * @param string .
 * @return string
 */

if (!function_exists('pcfme_match_all_found_condtion')) {

	function pcfme_match_all_found_condtion($dynamic_rules) { 

		$match_all_found = 'no';

		$rule_match_all_index = 0;

		$match_found_index = 0;

        foreach ($dynamic_rules as $rule=>$rulevalue) {

        	if (isset($rulevalue['enabled']) && ($rulevalue['enabled'] == "yes")) {

        		$rule_matched = 'no';    		

        	    $rule_matched = pcfme_does_rule_match_found($rulevalue);

        	    if ($rule_matched == 'yes') {
        	    	$match_found_index++;
        	    }

        	    $rule_match_all_index++;
        	}
        }


        if ($rule_match_all_index == $match_found_index) {
        	return 'yes';
        }

		return $match_all_found;

	}

}


/**
 * License activation reminder.
 *
 * @since 1.0.0
 * @param string .
 * @return string
 */

if (!function_exists('pcfme_match_any_found_condtion')) {

	function pcfme_match_any_found_condtion($dynamic_rules) { 

		$match_any_found = 'no';


		$match_all_found = 'no';

		

		$match_found_index = 0;

        foreach ($dynamic_rules as $rule=>$rulevalue) {

        	if (isset($rulevalue['enabled']) && ($rulevalue['enabled'] == "yes")) {

        		$rule_matched = 'no';    		

        	    $rule_matched = pcfme_does_rule_match_found($rulevalue);

        	    if ($rule_matched == 'yes') {
        	    	$match_found_index++;
        	    }

        	   
        	}
        }


        if ($match_found_index > 0) {
        	return 'yes';
        }

		

		return $match_any_found;

	}

}


/**
 * License activation reminder.
 *
 * @since 1.0.0
 * @param string .
 * @return string
 */

if (!function_exists('pcfme_loop_through_dynamic_rules')) {

	function pcfme_loop_through_dynamic_rules($dynamic_rules,$dynamic_criteria) { 

		$dynamic_match_index = 0;

        switch($dynamic_criteria) {
        	case "match_all":
        	   $match_all_found = 'no';

        	   $match_all_found = pcfme_match_all_found_condtion($dynamic_rules);

        	   if ($match_all_found == "yes") {
        	   	   $dynamic_match_index = 1;

        	   	   return $dynamic_match_index;
        	   }
        	break;

        	case "match_any":

        	    $match_any_found = 'no';

        	    $match_any_found = pcfme_match_any_found_condtion($dynamic_rules);

        	    if ($match_any_found == "yes") {
        	   	   $dynamic_match_index = 1;

        	   	   return $dynamic_match_index;
        	    }
        	break;




        	case "disabled":
        	   $dynamic_match_index = 1;

        	   return $dynamic_match_index;
        	break;

        	default:

        	   $dynamic_match_index = 0;

        	   return $dynamic_match_index;
        	break;
        }

		return $dynamic_match_index;

          
	}

}


/**
 * License activation reminder.
 *
 * @since 1.0.0
 * @param string .
 * @return string
 */

if (!function_exists('pcfme_load_license_reminder_div')) {

	function pcfme_load_license_reminder_div() { 
		?>

		<div class="pcfme_notice_div">

			<div class="pcfme_notice_div_uppertext">
				<?php echo esc_html__( 'Its time to activate license to access backend.This does not affect frontend functionality.'); ?>

			</div>

			<div class="pcfme_notice_div_lowerbutton">
				<a type="button" href="admin.php?page=pcfme_plugin_options&tab=pcfme_license_settings"  class="btn btn-primary " >
					<span class="dashicons dashicons-lock"></span>
					<?php echo esc_html__( 'Activate License' ,'customize-my-account-pro'); ?>
				</a>

				
			</div>
		</div>

		<?php 
	}
}


if ( ! function_exists( 'pcfme_get_checkout_field_varsion_number' ) ) {

    /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */



    function pcfme_get_checkout_field_varsion_number() {
        // If get_plugins() isn't available, require it
	   
	   if ( ! function_exists( 'get_plugins' ) )
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	
        // Create the plugins folder and file variables
	   $plugin_folder = get_plugins( '/' . 'phppoet-checkout-fields' );
	   $plugin_file = 'phppoet-checkout-fields.php';
	
	   // If the plugin version number is set, return it 
	   if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
		 return $plugin_folder[$plugin_file]['Version'];

	   } else {
	// Otherwise return null
		return NULL;
	  }
   }
   
}



if ( ! function_exists( 'pcfme_get_woo_version_number' ) ) {

    /**
	 * Outputs a installed woocommerce version
	 *
	 * @access public
	 * @subpackage	Forms
	 */



    function pcfme_get_woo_version_number() {
        // If get_plugins() isn't available, require it
	   
	   if ( ! function_exists( 'get_plugins' ) )
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	
        // Create the plugins folder and file variables
	   $plugin_folder = get_plugins( '/' . 'woocommerce' );
	   $plugin_file = 'woocommerce.php';
	
	   // If the plugin version number is set, return it 
	   if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
		 return $plugin_folder[$plugin_file]['Version'];

	   } else {
	// Otherwise return null
		return NULL;
	  }
   }
   
}


if ( ! function_exists( 'pfcme_parent_visibility_check' ) ) {

    /**
	 * returns conditional classes
	 *
	 * @access public
	 * @subpackage	Forms
	 */



    function pfcme_parent_visibility_check($parentfield) {

        $default = 'visible';

        if (strpos($parentfield, 'billing') !== false) {
            
            $field_type = 'billing';

        } elseif (strpos($parentfield, 'shipping') !== false) {
        	
        	$field_type = 'shipping';
        
        } elseif (strpos($parentfield, 'shipping') !== false) {
            
            $field_type = 'additional';

        }


        if (isset($field_type)) {

        	switch($field_type) {
        		case "billing":
        		    $fields_data = get_option('pcfme_billing_settings');
        		break;

        		case "shipping":
        		    $fields_data = get_option('pcfme_shipping_settings');
        		break;

        		case "additional":
        		    $fields_data = get_option('pcfme_additional_settings');
        		break;

        	}
        }

        if (isset($fields_data) && isset($fields_data[$parentfield])) {
        	
        	$value = $fields_data[$parentfield];

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



				$is_field_hidden=pcfme_check_if_field_is_hidden($visibilityarray,$allowedproducts,$allowedcats,$allowedroles,$total_quantity,$prd,$prd_qnty,$dynamic_rules,$dynamic_criteria);

				if ((isset($is_field_hidden)) && ($is_field_hidden == 0)) {

					return 'hidden';

				}
            }

        }

        return $default;

    }

}


if ( ! function_exists( 'pcfme_get_fees_class' ) ) {

    /**
	 * returns conditional classes
	 *
	 * @access public
	 * @subpackage	Forms
	 */



    function pcfme_get_fees_class($key) {

    	$class              = '';

        $additional_fees    = get_option('pcfme_additional_fees');
        
        
        

        $matchindex         = 0;

        if (is_array($additional_fees)) {
        	$additional_fees    = array_filter($additional_fees);
        }


        if (isset($additional_fees) && is_array($additional_fees) && (sizeof($additional_fees) >= 1)) { 
        	$additional_fees = $additional_fees;
        } else {
        	$additional_fees = array();
        }


        foreach ($additional_fees as $fkey=>$fvalue) {
            //if (strstr($string, $url)) { // mine version
            if ((strpos($key, $fvalue['parentfield']) !== FALSE) && (isset($fvalue['amount'])) ) { // Yoshi version
    	        $matchindex++; 
    	        
            }
        }

        

        if ($matchindex > 0) {
        	
        	$class = 'pcfme-price-changer';


        }
    

		return $class;
    }
   
}


if ( ! function_exists( 'pcfme_get_action_class' ) ) {

    /**
	 * returns conditional classes
	 *
	 * @access public
	 * @subpackage	Forms
	 */



    function pcfme_get_action_class($key) {

    	$class              = '';

        $additional_fees    = get_option('pcfme_additional_fees');
        
        
        

        $matchindex         = 0;

        if (is_array($additional_fees)) {
        	$additional_fees    = array_filter($additional_fees);
        }


        if (isset($additional_fees) && is_array($additional_fees) && (sizeof($additional_fees) >= 1)) { 
        	$additional_fees = $additional_fees;
        } else {
        	$additional_fees = array();
        }


        foreach ($additional_fees as $fkey=>$fvalue) {
            //if (strstr($string, $url)) { // mine version
            if ((strpos($key, $fvalue['parentfield']) !== FALSE) && (isset($fvalue['actionfield'])) ) { // Yoshi version
    	        $matchindex++; 
    	        
            }
        }

        

        if ($matchindex > 0) {
        	
        	$class = 'pcfme-action-changer';


        }
    

		return $class;
    }
   
}



if ( ! function_exists( 'pcfme_get_field_data' ) ) {

    /**
	 * returns conditional classes
	 *
	 * @access public
	 * @subpackage	Forms
	 */



    function pcfme_get_field_data($key) {

    	$field_data = array();

        $billing_fields    = (array) get_option('pcfme_billing_settings');
        $shipping_fields   = (array) get_option('pcfme_shipping_settings');
        $additional_fields = (array) get_option('pcfme_additional_settings');

        

       

        foreach ($billing_fields as $bkey=>$bvalue) {
            //if (strstr($string, $url)) { // mine version
            if ($bkey == $key) { // Yoshi version
    	        $field_data['label'] = $bvalue['label']; 
    	        $field_data['type']  = $bvalue['type']; 
    	        
    	        return $field_data;
    	        
            }
        }

        foreach ($shipping_fields as $bkey=>$bvalue) {
            //if (strstr($string, $url)) { // mine version
            if ($bkey == $key) { // Yoshi version
    	        $field_data['label'] = $bvalue['label']; 
    	        $field_data['type']  = $bvalue['type']; 
    	        
    	        return $field_data;
    	        
            }
        }


        foreach ($additional_fields as $bkey=>$bvalue) {
            //if (strstr($string, $url)) { // mine version
            if ($bkey == $key) { // Yoshi version
    	        $field_data['label'] = $bvalue['label']; 
    	        $field_data['type']  = $bvalue['type']; 
    	        
    	        return $field_data;
    	        
            }
        }

        


        return $field_data;

        
    }
   
}

if ( ! function_exists( 'get_order_array' ) ) {

	function get_order_array($plugin_fields) {

		$order=array();

		foreach ($plugin_fields as $key=>$value) {
			array_push($order, $key);
		}


		return $order;
	}

}



if ( ! function_exists( 'pcfme_update_fields_combined' ) ) {

    /**
	 * returns conditional classes
	 *
	 * @access public
	 * @subpackage	Forms
	 */



    function pcfme_update_fields_combined($fields,$plugin_fields,$slug) {

    	if (isset($plugin_fields) && ($plugin_fields != '')) {

    		$keyorder = 1;


		    //loops through plugin generated array of billing fields  
    		foreach ($plugin_fields as $key2=>$value) {

    			if ((isset($value['new_options']) && ($value['new_options'] != '')) || (isset($value['options']) && ($value['options'] != '')))  {


    				$old_options = isset($value['options']) ? $value['options'] : '';

    				$old_options = explode(',', $old_options);

    				$old_options_array = array();



    				if (isset($old_options) && !empty($old_options) && (sizeof($old_options) > 0)) {
    					$old_options_array_index = 1;
    					foreach($old_options as $ovalue) {
    						$old_options_array[''.$old_options_array_index.''] = array('value'=>$ovalue,'text'=>$ovalue);
    						$old_options_array_index++;
    					}
    				}

    				$new_options_array = isset($value['new_options']) ? $value['new_options'] : $old_options_array;



    				$options = array();

    				foreach($new_options_array as $nkey=>$val){

    					$o_value = $val['value'];
    					$o_text = $val['text'];

    					$options[$o_value]  = $o_text;

    				}

    			}

                



    			if (isset($fields[$slug]) && (sizeof($fields[$slug]) >1)) {

		    	    //loops through default woocommerce fields array

    				foreach ($fields[$slug] as $key=>$billing)  {

		                //if key matches
    					if ($key == $key2) {


    						if (isset($value['type'])) { 

    							$fields[$slug][$key]['type'] = $value['type']; 

    						}





    						if (isset($value['label'])) { 

    							$fields[$slug][$key]['label'] = $value['label']; 
    						}


    						if (isset($value['width'])) { 

    							if (isset( $fields[$slug][$key]['class'])) {

    								foreach ($fields[$slug][$key]['class'] as $classkey=>$classvalue) {

    									if ($classvalue == 'form-row-wide' || $classvalue == "form-row-first"  || $classvalue == "form-row-last") {
    										unset($fields[$slug][$key]['class'][$classkey]);
    									}

    								}
    							}

    							$fields[$slug][$key]['class'][]=$value['width'];
    						}

    						if (isset($value['required']) && ($value['required'] == 1) && ($key != "billing_state")) { 

    							$fields[$slug][$key]['required'] = $value['required']; 

    						} else {

    							$fields[$slug][$key]['required'] = false;
    						} 


    						if (isset($value['clear']) && ($value['clear'] == 1)) { 

    							$fields[$slug][$key]['clear'] = $value['clear']; 

    						} else {

    							$fields[$slug][$key]['clear'] = false;

    						}	

    						if (isset($value['placeholder'])) { 

    							$fields[$slug][$key]['placeholder'] = $value['placeholder']; 

    						}

    						if (isset($keyorder)) { 


    							$fields[$slug][$key]['priority'] = $keyorder * 10; 

    						}


    						if (isset($value['new_options']) || isset($value['options'])) { 

    							if ((isset($value['new_options']) && ($value['new_options'] != '')) || (isset($value['options']) && ($value['options'] != ''))) {
    								$fields[$slug][$key]['options'] =$options;
    							}
    						}

                            $extraclass = array();
    						//builds extraclass array
		                    if (isset($value['extraclass']) && ($value['extraclass'] != '')) {
		      
		                        $tempclasses = explode(',',$value['extraclass']);
		      
		      
		                        
                      
                                foreach($tempclasses as $classval3){
    
                                    $extraclass[$classval3]  = $classval3;
      
                                }
			 
		                    }




    						$pcfme_conditional_class = '';


    						
    						$pcfme_conditional_class = pcfme_get_visibility_class_combined($value);
    						


                            

    						if (isset($pcfme_conditional_class) && ($pcfme_conditional_class != '')) {
    							$extraclass[] = $pcfme_conditional_class;
    						}


    						if (isset($extraclass) && ($extraclass != '')) {

    							foreach ($extraclass as $billingclassval) {
    								$fields[$slug][$key]['class'][] = $billingclassval;
    							}
    						}

    						


    						if (isset($value['validate'])) { 

    							$fields[$slug][$key]['validate'] =$value['validate'];

    						}

    						if (isset($value['disable_past'])) { 

    							$fields[$slug][$key]['disable_past'] =$value['disable_past'];

    						}
			            } //end of if key matches

			            //if key does not match

			            if (isset($plugin_fields[$key2]) && (!isset($fields[$slug][$key2]))) {



			        	    if (isset($plugin_fields[$key2])) {
			        		    $fields[$slug][$key2] = $value;
			        	    }

			        	    if (isset($value['width']) && ($value['width'] != '')) {
			        		    $fields[$slug][$key2]['class'][] =$value['width'];
			        	    }

                            $extraclass = array();
			        	    //builds extraclass array
		                    if (isset($value['extraclass']) && ($value['extraclass'] != '')) {
		      
		                        $tempclasses = explode(',',$value['extraclass']);
		      
		      
		                        
                      
                                foreach($tempclasses as $classval3){
    
                                    $extraclass[$classval3]  = $classval3;
      
                                }
			 
		                    }


			        	    $pcfme_conditional_class = '';


			        	    if (isset($value['visibility'])) {
			        		    $pcfme_conditional_class = pcfme_get_visibility_class_combined($value);
			        	    }




			        	    if (isset($pcfme_conditional_class) && ($pcfme_conditional_class != '')) {
			        		    $extraclass[] = $pcfme_conditional_class;
			        	    }


			        	    if (isset($extraclass) && ($extraclass != '')) {

			        		    foreach ($extraclass as $billingclassval2) {

			        			    $fields[$slug][$key2]['class'][] = $billingclassval2;

			        		    }

			        	    }

                            if ((isset($value['new_options']) && ($value['new_options'] != '')) || (isset($value['options']) && ($value['options'] != ''))) {

			        		    $fields[$slug][$key2]['options'] =$options;
			        	    }

			        	    if (isset($keyorder)) { 

			        	    	$new_keyorder = ($keyorder * 10) + 10;


			        		    $fields[$slug][$key2]['priority'] =  $new_keyorder;

			        	    }
			            }
			            //end of if key does not match
			        }
			    }



			$keyorder++;
		}
	}


	if ( is_checkout() ) {

		if (isset($plugin_fields) && (sizeof($plugin_fields) >1)) {



			$order = get_order_array($plugin_fields);

			foreach($order as $field) {
				$ordered_fields[$field] = $fields[$slug][$field];
			}

			$fields[$slug] = $ordered_fields;

		} 

	}




	if (isset($plugin_fields) && ($plugin_fields != '')) {

		foreach ($plugin_fields as $hidekey=>$hidevalue) {

			if (isset($hidevalue['hide']) && ($hidevalue['hide'] == 1)) {
				unset($fields[$slug][$hidekey]);
			}


			$visibilityarray = isset($hidevalue['visibility']) ? $hidevalue['visibility'] : array();

            if (isset($hidevalue['products'])) { 
            	$allowedproducts = $hidevalue['products'];
            } else {
            	$allowedproducts = array(); 
            }

            if (isset($hidevalue['category'])) {
            	$allowedcats = $hidevalue['category'];
            } else {
            	$allowedcats = array();
            }

            if (isset($hidevalue['role'])) {
            	$allowedroles = $hidevalue['role'];
            } else {
            	$allowedroles = array();
            }

            if (isset($hidevalue['total-quantity'])) {
            	$total_quantity = $hidevalue['total-quantity'];
            } else {
            	$total_quantity = 0;
            }

            if (isset($hidevalue['specific-product'])) {
            	$prd = $hidevalue['specific-product'];
            } else {
            	$prd = 0;
            }

            if (isset($hidevalue['specific-quantity'])) {
            	$prd_qnty = $hidevalue['specific-quantity'];
            } else {
            	$prd_qnty = 0;
            }


            if (isset($hidevalue['dynamic_rules'])) { 
            	$dynamic_rules = $hidevalue['dynamic_rules'];
            } else {
            	$dynamic_rules = array(); 
            }

            if (isset($hidevalue['dynamic_visibility_criteria'])) { 
            	$dynamic_criteria = $hidevalue['dynamic_visibility_criteria'];
            } else {
            	$dynamic_criteria = 'match_all'; 
            }



            $is_field_hidden=pcfme_check_if_field_is_hidden($visibilityarray,$allowedproducts,$allowedcats,$allowedroles,$total_quantity,$prd,$prd_qnty,$dynamic_rules,$dynamic_criteria);

            if ((isset($is_field_hidden)) && ($is_field_hidden == 0)) {

				unset($fields[$slug][$hidekey]);

			}






			if (isset($hidevalue['visibility'])) {

				$visibilityarray = $hidevalue['visibility'];

				if (isset($hidevalue['products'])) { 
					$allowedproducts = $hidevalue['products'];
				} else {
					$allowedproducts = array(); 
				}

				if (isset($hidevalue['category'])) {
					$allowedcats = $hidevalue['category'];
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
					unset($fields[$slug][$hidekey]);
				}


				
			}
		}
	}



	return $fields;


}

}



if ( ! function_exists( 'pcfmne_process_multiple_clone' ) ) {

    /**
	 * returns conditional classes
	 *
	 * @access public
	 * @subpackage	Forms
	 */



    function pcfmne_process_multiple_clone($multiclone_condition,$multiclone_product,$additionalvalue) {

    	global $woocommerce;
    	if( ! $woocommerce->cart ) { return; }
    	$cart_items = $woocommerce->cart->get_cart();

        
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




    	

    	foreach ($cart_items as $cartitem_key=>$cartitemvalue) {

    		


    		if (isset($cartitemvalue['variation_id']) &&  ($cartitemvalue['variation_id'] != 0)) {
    			$product_id=$cartitemvalue['variation_id'];
    		} else {
    			$product_id=$cartitemvalue['product_id'];
    		}


    		



    		if ($product_id == $multiclone_product) {

    			$quantity=$cartitemvalue['quantity'];

    			$quantity= $quantity+ 1;

    			$match_index = 1;

    			for ($i = 1; $i < $quantity; $i++) {

    				$product_title  = get_the_title($product_id);

    				

    				$extrafield['label'] = str_replace("{product_title}",$product_title,$extrafield['label']);

    				$extrafield['label'] = str_replace("{quantity_index}",$match_index,$extrafield['label']);




    				$additionalkey = ''.$additionalvalue['field_key'].'_'.$match_index.'';


    				$field_post_meta = get_user_meta( get_current_user_id(), $additionalkey , true ); 

    				if (isset($field_post_meta) && ($field_post_meta != '')) {

    					$additional_field_value = $field_post_meta;

    				} elseif (isset($additionalvalue['value'])) {

    					$additional_field_value = $additionalvalue['value'];

    				} else {

    					$additional_field_value = '';
    				}

    				

    				woocommerce_form_field( $additionalkey,  $extrafield ,! empty( $_POST[ $additionalkey ] ) ? wc_clean( $_POST[ $additionalkey ] ) : $additional_field_value);

    				$extrafield['label'] = str_replace($product_title,"{product_title}",$extrafield['label']);

    				$extrafield['label'] = str_replace($match_index,"{quantity_index}",$extrafield['label']);




    				$match_index++;

    			}
    		}
    	}
    }

}




if ( ! function_exists( 'pcfmne_process_multiple_clone_order_thanks' ) ) {

    /**
	 * returns conditional classes
	 *
	 * @access public
	 * @subpackage	Forms
	 */



    function pcfmne_process_multiple_clone_order_thanks($multiclone_condition,$multiclone_product,$additionalvalue,$order_id) {

    	global $woocommerce;
    	if( ! $woocommerce->cart ) { return; }
    	$cart_items = $woocommerce->cart->get_cart();

    	 $requiredtext      =  __('is a required field','customize-my-account-pro');

        
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


		    $field_post_meta = get_user_meta( get_current_user_id(), $additionalvalue['field_key'] , true ); 
						  
		    if (isset($field_post_meta) && ($field_post_meta != '')) {

					$additional_field_value = $field_post_meta;

			} elseif (isset($additionalvalue['value'])) {

				    $additional_field_value = $additionalvalue['value'];

			} else {

				    $additional_field_value = '';
			}

    	

    	foreach ($cart_items as $cartitem_key=>$cartitemvalue) {

    		


    		if (isset($cartitemvalue['variation_id']) &&  ($cartitemvalue['variation_id'] != 0)) {
    			$product_id=$cartitemvalue['variation_id'];
    		} else {
    			$product_id=$cartitemvalue['product_id'];
    		}


    		



    		if ($product_id == $multiclone_product) {

    			$quantity=$cartitemvalue['quantity'];

    			$quantity= $quantity+ 1;

    			$match_index = 1;

    			for ($i = 1; $i < $quantity; $i++) {

    				$product_title  = get_the_title($product_id);

    				

    				$extrafield['label'] = str_replace("{product_title}",$product_title,$extrafield['label']);
                    
                    $extrafield['label'] = str_replace("{quantity_index}",$match_index,$extrafield['label']);




    				$additionalkey = ''.$additionalvalue['field_key'].'_'.$match_index.'';
               
    				
                    $additionalkeyvalue = get_post_meta( $order_id, $additionalkey, true );
					$additionalkeyvalue = str_replace("_"," ",$additionalkeyvalue);
				    
					if ( ! empty( $additionalkeyvalue ) && ($additionalkeyvalue != 'empty') && ($additionalkeyvalue != 845675668) ) {
					   echo '<p><strong>'.__(''.$extrafield['label'].'').':</strong> ' . $additionalkeyvalue . '</p>';
					} else if (($additionalkeyvalue == 'empty') && ($additionalkeyvalue == 845675668)) {
		     			delete_post_meta( $order_id, $additionalkey);
		     		}
    				

    				$extrafield['label'] = str_replace($product_title,"{product_title}",$extrafield['label']);
                    
                    $extrafield['label'] = str_replace($match_index,"{quantity_index}",$extrafield['label']);


                    

    				$match_index++;

    			}
    		}
    	}
    }

}


if ( ! function_exists( 'pcfmne_process_multiple_clone_details_email' ) ) {

    /**
	 * returns conditional classes
	 *
	 * @access public
	 * @subpackage	Forms
	 */



    function pcfmne_process_multiple_clone_details_email($multiclone_condition,$multiclone_product,$additionalvalue,$order_id) {


       

    	$order = wc_get_order( $order_id );

    	$items = $order->get_items();

    	//print_r($items);

    	// Get and Loop Over Order Items
        foreach ( $items as $item_id => $item ) {
           $product_id = $item->get_product_id();
           $variation_id = $item->get_variation_id();


           $product_id = isset($variation_id) && ($variation_id != 0) ? $variation_id : $product_id;

    		
          


    		if (($product_id == $multiclone_product))  {

    			$quantity = $item->get_quantity();

    			
                $quantity = $quantity + 1;


    			$match_index = 1;

    			for ($i = 1; $i < $quantity; $i++) {

    				$product_title  = get_the_title($product_id);




    				$additionalkey = ''.$additionalvalue['field_key'].'_'.$match_index.'';

    				

    				
    				$additionalvalue['label'] = str_replace("{product_title}",$product_title,$additionalvalue['label']);

    				$additionalvalue['label'] = str_replace("{quantity_index}",$match_index,$additionalvalue['label']);




    				$additionalkey = ''.$additionalvalue['field_key'].'_'.$match_index.'';

    				
    				$additionalkeyvalue = get_post_meta( $order_id, $additionalkey, true );
    				$additionalkeyvalue = str_replace("_"," ",$additionalkeyvalue);



    				if ( ! empty( $additionalkeyvalue ) && ($additionalkeyvalue != 'empty') && ($additionalkeyvalue != 845675668)) { ?>

    					<tr>
    						<th scope="row" colspan="2" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:4px" align="left"><?php echo ucfirst($additionalvalue['label']); ?></th>
    						<td style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:4px" align="left"><?php echo $additionalkeyvalue; ?></td>
    					</tr>
    				<?php	}	else if ( ($additionalkeyvalue == 'empty') || ($additionalkeyvalue == 845675668) ) {
    					delete_post_meta( $order_id, $additionalkey);
    				}
    				

    				$additionalvalue['label'] = str_replace($product_title,"{product_title}",$additionalvalue['label']);

    				$additionalvalue['label'] = str_replace($match_index,"{quantity_index}",$additionalvalue['label']); 
    				


    				$match_index++;

    			}
    		}
    	}
    }

}


if ( ! function_exists( 'pcfmne_process_multiple_clone_details_edition' ) ) {

    /**
	 * returns conditional classes
	 *
	 * @access public
	 * @subpackage	Forms
	 */



    function pcfmne_process_multiple_clone_details_edition($multiclone_condition,$multiclone_product,$additionalvalue,$order_id) {


       

    	$order = wc_get_order( $order_id );

    	$items = $order->get_items();

    	//print_r($items);

    	// Get and Loop Over Order Items
        foreach ( $items as $item_id => $item ) {
           $product_id = $item->get_product_id();
           $variation_id = $item->get_variation_id();


           $product_id = isset($variation_id) && ($variation_id != 0) ? $variation_id : $product_id;

    		
          


    		if (($product_id == $multiclone_product))  {

    			$quantity = $item->get_quantity();

    			
                $quantity = $quantity + 1;


    			$match_index = 1;

    			for ($i = 1; $i < $quantity; $i++) {

    				$product_title  = get_the_title($product_id);




    				$additionalkey = ''.$additionalvalue['field_key'].'_'.$match_index.'';

    				
               
    				
                    $additionalvalue['label'] = str_replace("{product_title}",$product_title,$additionalvalue['label']);
                    
                    $additionalvalue['label'] = str_replace("{quantity_index}",$match_index,$additionalvalue['label']);




    				$additionalkey = ''.$additionalvalue['field_key'].'_'.$match_index.'';
               
    				
                    $additionalkeyvalue = get_post_meta( $order_id, $additionalkey, true );
			        $additionalkeyvalue = str_replace("_"," ",$additionalkeyvalue);
					
				    
				    
					if ( ! empty( $additionalkeyvalue ) && ($additionalkeyvalue != 'empty') && ($additionalkeyvalue != 845675668) ) {
					   echo '<p><strong>'.__(''.$additionalvalue['label'].'').':</strong> ' . $additionalkeyvalue . '</p>';
					} else if (($additionalkeyvalue == 'empty') && ($additionalkeyvalue == 845675668)) {
		     			delete_post_meta( $order_id, $additionalkey);
		     		}
    				

    				$additionalvalue['label'] = str_replace($product_title,"{product_title}",$additionalvalue['label']);
                    
                    $additionalvalue['label'] = str_replace($match_index,"{quantity_index}",$additionalvalue['label']); 
    				

    			
    				$match_index++;

    			}
    		}
    	}
    }

}


if ( ! function_exists( 'pcfmne_process_multiple_clone_pdfdetails' ) ) {

    /**
	 * returns conditional classes
	 *
	 * @access public
	 * @subpackage	Forms
	 */



    function pcfmne_process_multiple_clone_pdfdetails($multiclone_condition,$multiclone_product,$additionalvalue,$order_id) {


       

    	$order = wc_get_order( $order_id );

    	$items = $order->get_items();

    	//print_r($items);

    	// Get and Loop Over Order Items
        foreach ( $items as $item_id => $item ) {
           $product_id = $item->get_product_id();
           $variation_id = $item->get_variation_id();


           $product_id = isset($variation_id) && ($variation_id != 0) ? $variation_id : $product_id;

    		
          


    		if (($product_id == $multiclone_product))  {

    			$quantity = $item->get_quantity();

    			
                $quantity = $quantity + 1;


    			$match_index = 1;

    			for ($i = 1; $i < $quantity; $i++) {

    				$product_title  = get_the_title($product_id);




    				$additionalkey = ''.$additionalvalue['field_key'].'_'.$match_index.'';

    				
    				
    				
    				$additionalvalue['label'] = str_replace("{product_title}",$product_title,$additionalvalue['label']);
    				
    				$additionalvalue['label'] = str_replace("{quantity_index}",$match_index,$additionalvalue['label']);




    				$additionalkey = ''.$additionalvalue['field_key'].'_'.$match_index.'';
    				
    				
    				$additionalkeyvalue = get_post_meta( $order_id, $additionalkey, true );
    				$additionalkeyvalue = str_replace("_"," ",$additionalkeyvalue);
    				
    				if ( ! empty( $additionalkeyvalue ) && ($additionalkeyvalue != 'empty') && ($additionalkeyvalue != 845675668)) { ?>

    					<tr class="billing-nif">
    						<th><?php echo $additionalvalue['label']; ?></th>
    						<td><?php echo $additionalkeyvalue; ?></td>
    					</tr>
    					<?php	
    				} else if (($additionalkeyvalue == 'empty') || ($additionalkeyvalue == 845675668)) {
    					delete_post_meta( $order_id, $additionalkey);
    				}
    				

    				$additionalvalue['label'] = str_replace($product_title,"{product_title}",$additionalvalue['label']);
    				
    				$additionalvalue['label'] = str_replace($match_index,"{quantity_index}",$additionalvalue['label']); 
    				

    				
    				$match_index++;

    			}
    		}
    	}
    }

}



if ( ! function_exists( 'pcfmne_process_multiple_clone_details' ) ) {

    /**
	 * returns conditional classes
	 *
	 * @access public
	 * @subpackage	Forms
	 */



    function pcfmne_process_multiple_clone_details($multiclone_condition,$multiclone_product,$additionalvalue,$order_id) {


       

    	$order = wc_get_order( $order_id );

    	$items = $order->get_items();

    	//print_r($items);

    	// Get and Loop Over Order Items
        foreach ( $items as $item_id => $item ) {
           $product_id = $item->get_product_id();
           $variation_id = $item->get_variation_id();


           $product_id = isset($variation_id) && ($variation_id != 0) ? $variation_id : $product_id;

    		
          


    		if (($product_id == $multiclone_product))  {

    			$quantity = $item->get_quantity();

    			
                $quantity = $quantity + 1;


    			$match_index = 1;

    			for ($i = 1; $i < $quantity; $i++) {

    				$product_title  = get_the_title($product_id);




    				$additionalkey = ''.$additionalvalue['field_key'].'_'.$match_index.'';

    				
               
    				
                    $additionalvalue['label'] = str_replace("{product_title}",$product_title,$additionalvalue['label']);
                    
                    $additionalvalue['label'] = str_replace("{quantity_index}",$match_index,$additionalvalue['label']);




    				$additionalkey = ''.$additionalvalue['field_key'].'_'.$match_index.'';
               
    				
                    $additionalkeyvalue = get_post_meta( $order_id, $additionalkey, true );
			        $additionalkeyvalue = str_replace("_"," ",$additionalkeyvalue);
					
				    if ( ! empty( $additionalkeyvalue ) && ($additionalkeyvalue != 'empty') && ($additionalkeyvalue != 845675668)) { ?>
				          
						   <tr>
                             <th><?php echo $additionalvalue['label']; ?>:</th>
                             <td><?php echo $additionalkeyvalue; ?></td>
                           </tr>
					<?php 
					} else if (($additionalkeyvalue == 'empty') || ($additionalkeyvalue == 845675668)) {
		     			    delete_post_meta( $order_id, $additionalkey);
		     		}
    				

    				$additionalvalue['label'] = str_replace($product_title,"{product_title}",$additionalvalue['label']);
                    
                    $additionalvalue['label'] = str_replace($match_index,"{quantity_index}",$additionalvalue['label']); 
    				

    			
    				$match_index++;

    			}
    		}
    	}
    }

}

if ( ! function_exists( 'pcfmne_process_multiple_clone_order_save' ) ) {

    /**
	 * returns conditional classes
	 *
	 * @access public
	 * @subpackage	Forms
	 */



    function pcfmne_process_multiple_clone_order_save($multiclone_condition,$multiclone_product,$additionalvalue,$order_id) {

    	
    	

        $order = wc_get_order( $order_id );

    	

    	// Get and Loop Over Order Items
        foreach ( $order->get_items() as $item_id => $item ) {
           $product_id = $item->get_product_id();
           $variation_id = $item->get_variation_id();


           $product_id = isset($variation_id) && ($variation_id != 0) ? $variation_id : $product_id;

    		



    		if (($product_id == $multiclone_product))  {

    			$quantity = $item->get_quantity();

    			$quantity= $quantity+ 1;

    			$match_index = 1;

    			for ($i = 1; $i < $quantity; $i++) {

    				$product_title  = get_the_title($product_id);


    				$additionalkey = ''.$additionalvalue['field_key'].'_'.$match_index.'';
               
    				
                    if ( ! empty( $_POST[$additionalkey] ) ) {

			 			if (is_array($_POST[$additionalkey]))  {
			 				$additionalkeyvalue = implode(',', $_POST[$additionalkey]);
			 			} else {
			 				$additionalkeyvalue = $_POST[$additionalkey];
			 			}

			 			update_post_meta( $order_id, $additionalkey, sanitize_text_field( $additionalkeyvalue ) );
			 		} 
    				

    			
    				$match_index++;

    			}
    		}
    	}
    }

}



if ( ! function_exists( 'pcfmne_process_multiple_clone_notice' ) ) {

    /**
	 * returns conditional classes
	 *
	 * @access public
	 * @subpackage	Forms
	 */



    function pcfmne_process_multiple_clone_notice($multiclone_condition,$multiclone_product,$additionalvalue) {

    	global $woocommerce;
    	if( ! $woocommerce->cart ) { return; }
    	$cart_items = $woocommerce->cart->get_cart();

    	 $requiredtext      =  __('is a required field','customize-my-account-pro');

        
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


		    $field_post_meta = get_user_meta( get_current_user_id(), $additionalvalue['field_key'] , true ); 
						  
		    if (isset($field_post_meta) && ($field_post_meta != '')) {

					$additional_field_value = $field_post_meta;

			} elseif (isset($additionalvalue['value'])) {

				    $additional_field_value = $additionalvalue['value'];

			} else {

				    $additional_field_value = '';
			}

    	

    	foreach ($cart_items as $cartitem_key=>$cartitemvalue) {

    		


    		if (isset($cartitemvalue['variation_id']) &&  ($cartitemvalue['variation_id'] != 0)) {
    			$product_id=$cartitemvalue['variation_id'];
    		} else {
    			$product_id=$cartitemvalue['product_id'];
    		}


    		



    		if ($product_id == $multiclone_product) {

    			$quantity=$cartitemvalue['quantity'];

    			$quantity= $quantity+ 1;

    			$match_index = 1;

    			for ($i = 1; $i < $quantity; $i++) {

    				$product_title  = get_the_title($product_id);

    				

    				$extrafield['label'] = str_replace("{product_title}",$product_title,$extrafield['label']);
                    
                    $extrafield['label'] = str_replace("{quantity_index}",$match_index,$extrafield['label']);




    				$additionalkey = ''.$additionalvalue['field_key'].'_'.$match_index.'';
               
    				
                    if (isset($additionalvalue['required']) && ( ! $_POST[$additionalkey] )) {
				        $noticetext='<strong>'.$extrafield['label'].'</strong> '.$requiredtext.' '.$_POST[$additionalkey].' ';
                        wc_add_notice( __( $noticetext ), 'error' );
                    }
    				

    				$extrafield['label'] = str_replace($product_title,"{product_title}",$extrafield['label']);
                    
                    $extrafield['label'] = str_replace($match_index,"{quantity_index}",$extrafield['label']);


                    

    				$match_index++;

    			}
    		}
    	}
    }

}






if ( ! function_exists( 'pcfme_get_conditional_class' ) ) {

    /**
	 * returns conditional classes
	 *
	 * @access public
	 * @subpackage	Forms
	 */



    function pcfme_get_conditional_class($conditional) {



    	$class = '';

    	$parent_visibility_class = '';

        

    	foreach ($conditional as $key=>$value) {

            if (isset($value['showhide'])) {
            	$showhide                 = $value['showhide'];
            }

            if (isset($value['parentfield'])) {
            	$parentfield               = $value['parentfield'];
            }

            

            if (isset($showhide) && ($showhide == "open") && isset($parentfield)) {

            	$parent_visibility   = pfcme_parent_visibility_check($parentfield);

            	if (isset($parent_visibility) && ($parent_visibility == 'hidden')) {
            	    $parent_visibility_class = 'parent_hidden';
                } 
            }


            if (isset($value['equalto2']) && ($value['hidden_type'] == "select")) {

            	if (isset($value['equalto2'])) {
            		$equalto               = $value['equalto2'];
            		$equalto = str_replace(' ', '_', $equalto);
            	}

            } else {

            	if (isset($value['equalto'])) {
            		$equalto               = $value['equalto'];
            		$equalto = str_replace(' ', '_', $equalto);
            	}

            }




    		
	        
	        if ((isset($showhide)) && (isset($parentfield))) {

	        	if (isset($equalto) && ($equalto != '')) {
			        $class  .= 'dpnd_on_' . $parentfield . ' ' . $showhide . '_by_' . $parentfield . '_' . $equalto .' '.$parent_visibility_class.''; 
	            } else {
			        $class  .= '' . $showhide . '_by_' . $parentfield . ' '.$parent_visibility_class.''; 
		        }
	        }

	        

    	}
    

		return $class;
    }
   
}



if ( ! function_exists( 'pcfme_get_conditional_shipping_class' ) ) {

    /**
	 * returns conditional classes
	 *
	 * @access public
	 * @subpackage	Forms
	 */



    function pcfme_get_conditional_shipping_class($shipping) {

    	$shipping_method = $shipping['method'];
    	$showhide        = $shipping['showhide'];

    	switch ($showhide) {
    		case "show":
    		    $showhide_class2 ="hide_on_load";
    		break;

    		case "hide":
    		     $showhide_class2 ="show_on_load";
    		break;
    		
    	}


    	$class = ''.$showhide_class2.' '.$showhide.'_by_shipping_method_'. $shipping_method .'';


    	return $class;
    }

}



if ( ! function_exists( 'pcfme_get_conditional_payment_class' ) ) {

    /**
	 * returns conditional classes
	 *
	 * @access public
	 * @subpackage	Forms
	 */



    function pcfme_get_conditional_payment_class($payment) {

    	$payment_geteway = $payment['gateway'];
    	$showhide        = $payment['showhide'];

    	switch ($showhide) {
    		case "show":
    		    $showhide_class3 ="hide_on_load2";
    		break;

    		case "hide":
    		     $showhide_class3 ="show_on_load2";
    		break;
    		
    	}

    	$class = ''.$showhide_class3.' '.$showhide.'_by_payment_gateway_'.$payment_geteway.'';
    

		return $class;
    }
   
}


if ( ! function_exists( 'pcfme_get_siteurl' ) ) {

    /**
	 * returns conditional classes
	 *
	 * @access public
	 * @subpackage	Forms
	 */



    function pcfme_get_siteurl() {
    	$domain = get_option( 'siteurl' );
    	$domain = str_replace( 'http://', '', $domain );
    	$domain = str_replace( 'https://', '', $domain );
    	$domain = str_replace( 'www', '', $domain );
    	return urlencode( $domain );
    }

}



if ( ! function_exists( 'pcfme_get_visibility_class_combined' ) ) {

    /**
	 * returns conditional classes
	 *
	 * @access public
	 * @subpackage	Forms
	 */



    function pcfme_get_visibility_class_combined($value) {

    	$pcfme_conditional_class = '';

    	if (isset($value['conditional'])) {
    		$pcfme_conditional_class .= pcfme_get_conditional_class($value['conditional']);
    	}

    	
    	if (isset($value['visibility'])) {

    		switch($value['visibility']) {

    			

    			case "shipping-specific":
    			$pcfme_conditional_class = pcfme_get_conditional_shipping_class($value['shipping']);
    			break;

    			case "payment-specific":
    			$pcfme_conditional_class = pcfme_get_conditional_payment_class($value['payment']);
    			break;

    		}

    	}



    	return $pcfme_conditional_class;
    }

}







if ( ! function_exists( 'pcfmeinput_conditional_class' ) ) {
	
	function pcfmeinput_conditional_class($fieldkey) {

		$billing_settings_key      = 'pcfme_billing_settings';
	    $shipping_settings_key     = 'pcfme_shipping_settings';
	    $pcfme_additional_settings = 'pcfme_additional_settings';
		$pcfme_class_text          = '';
		 
		 
		$billing_fields                = (array) get_option( $billing_settings_key );
		$shipping_fields               = (array) get_option( $shipping_settings_key );
		$additional_fields             = (array) get_option( $pcfme_additional_settings );
		 
		$hiderlist  = array();
		$openerlist = array();
		 
		foreach ($billing_fields as $billingkey=>$billingvalue) {

			
			if (isset($billingvalue['conditional'])) {
				$conditional                = $billingvalue['conditional'];



				foreach ($conditional as $key1=>$value1) {

					if (isset($value1['parentfield'])) {
						$parentfield1               = $value1['parentfield'];
					}

					if (isset($value1['showhide'])) {
						$cxshowhide1               = $value1['showhide'];
					}




					if (isset($parentfield1) && ($parentfield1 != '')) {

						if (isset($cxshowhide1) && ($cxshowhide1 != '')) {
							switch ($cxshowhide1) {
								case "open":
								if (!in_array($parentfield1, $openerlist)) array_push($openerlist, $parentfield1);
								break;

								case "hide":
								if (!in_array($parentfield1, $hiderlist)) array_push($hiderlist, $parentfield1);
								break;
							}
						}
					}
				}

			}
               
		}
		 
		foreach ($shipping_fields as $shippingkey=>$shippingvalue) {

			    
			    if (isset($shippingvalue['conditional'])) {

			    				    $conditional2                = $shippingvalue['conditional'];

			    foreach ($conditional2 as $key2=>$value2) {

			 	    if (isset($value2['parentfield'])) {
                    	$parentfield2               = $value2['parentfield'];
                    }

                    if (isset($value2['showhide'])) {
                    	$cxshowhide2                = $value2['showhide'];
                    }

			        if (isset($parentfield2) && ($parentfield2 != '')) {
				
				        if (isset($cxshowhide2) && ($cxshowhide2 != '')) {
					        switch ($cxshowhide2) {
						        case "open":
						            if (!in_array($parentfield2, $openerlist)) array_push($openerlist, $parentfield2);
						        break;
						
						        case "hide":
						            if (!in_array($parentfield2, $hiderlist)) array_push($hiderlist, $parentfield2);
						        break;
						    }
				        }
			        }
			    }

			    }
			
			  
		}
		 
		 
        
        foreach ($additional_fields as $additionalkey=>$additionalvalue) {

	
			 
			    if (isset($additionalvalue['conditional'])) {
			    	$conditional3                = $additionalvalue['conditional'];
			    }
			    
                if (isset($conditional3)) {

			        foreach ($conditional3 as $key3=>$value3) {

			 	        if (isset($value3['parentfield'])) {
                    	    $parentfield3               = $value3['parentfield'];
                        }

                        if (isset($value3['showhide'])) {
                    	    $cxshowhide3                = $value3['showhide'];
                        }

			            if (isset($parentfield3) && ($parentfield3 != '')) {
				
				            if (isset($cxshowhide3) && ($cxshowhide3 != '')) {
					            switch ($cxshowhide3) {
						            case "open":
						                if (!in_array($parentfield3, $openerlist)) array_push($openerlist, $parentfield3);
						            break;
						
						            case "hide":
						                if (!in_array($parentfield3, $hiderlist)) array_push($hiderlist, $parentfield3);
						            break;
						        }
				            }
			            }
			        }
			    }
			  
		}
		 
		   
		if (in_array($fieldkey, $openerlist)) {

			$pcfmeopernertext                = 'pcfme-opener';

		} else {

			$pcfmeopernertext                = '';
		}
		   
		if (in_array($fieldkey, $hiderlist)) {

			$pcfmehidertext                 = 'pcfme-hider';

		} else {

			$pcfmehidertext                 = '';
		}
			
			
		$pcfme_class_text  = ''.$pcfmeopernertext.' '.$pcfmehidertext.'';
			
		    
			
	    return $pcfme_class_text;
	}
	        
}
?>