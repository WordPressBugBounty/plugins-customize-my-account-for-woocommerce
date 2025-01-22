jQuery(document).ready(function($) {
    
    $(".row-validate-multiselect").select2({
    	width:"300px"
    });
    

	$(".checkout_field_category").select2({

		width: "400px",

		containerCss: function (element) {
			var style = $(element)[0].style;
			return {
				display: style.display
			};
		}
	});
	$(".checkout_field_role").select2({
		width: "400px",
		containerCss: function (element) {
			var style = $(element)[0].style;
			return {
				display: style.display
			};
		}

	});


	$("div.pcfme-sortable-list").sortable({
		opacity : 0.7,
		handle: 'div.panel-heading'

	});

	$("table.pcfme_sortable_table_options").sortable({
		handle: 'td.pcfme_sortable_td_first',
		items: 'tr'

	});


    $("div.dynamic_fields_div_wrapper ").sortable({
		handle: '.pcfme_sortable_tr_handle_dynamic',
		items: 'div.conditional-row'

	});

    $('.pcfme_dynamically_visible_toggle').on('change', function(){
    	var isChecked = $(this).prop('checked');

    	if(isChecked){ 
    		$('.checkout_field_visibility').val("dynamically-visible").trigger("change");
    	} else{ 
    		
    		$('.checkout_field_visibility').val("always-visible").trigger("change");
    	}
    });




    $('.pcfme_show_if_multiple_clone_on').on("change",function() {

        var parentkey = $(this).attr("trkey");

        
        
        if ($(this).is(":checked")) {
            $("span.pcfme_show_if_multiple_clone_on_div."+ parentkey +"").show();
            $("span.pcfme_show_if_multiple_clone_on_prducts_span."+ parentkey +"").show();
           
 
        } else {
            
            $("span.pcfme_show_if_multiple_clone_on_div."+ parentkey +"").hide();
            $("span.pcfme_show_if_multiple_clone_on_prducts_span."+ parentkey +"").hide();
            
        }
    });
 


	$(function() {
		$('.checkout_field_type').on('change',function(){
			var typevalue1 = $(this).val();
			if ((typevalue1 == "datepicker") || (typevalue1 == "datetimepicker") || (typevalue1 == "daterangepicker") || (typevalue1 == "datetimerangepicker")) {
				$(this).parents('table:eq(0)').find('.disable_datepicker_tr').show();
				$(this).parents('table:eq(0)').find('.pcfme_field_options_tr').hide();
			} else if ((typevalue1 == "pcfmeselect") || (typevalue1 == "multiselect") || (typevalue1 == "radio") ) {
				$(this).parents('table:eq(0)').find('.pcfme_field_options_tr').show();
				$(this).parents('table:eq(0)').find('.disable_datepicker_tr').hide();
			} else {
				$(this).parents('table:eq(0)').find('.disable_datepicker_tr').hide();
				$(this).parents('table:eq(0)').find('.pcfme_field_options_tr').hide();
			}
		});
        
        $('.checkout_field_products_variations_conditions').select2({
        	 minimumResultsForSearch: -1
        });


		$('.checkout_field_conditional_parentfield').on('change',function(xevent){
			
			xevent.preventDefault();

			

			var this_val   = $(this).val();
			var row_number = $(this).attr("rowno");
			

			var this_val2 = this_val.split('_')[0];

			
            

			var mtype4     = this_val2;

			$.ajax({
				data: {
					action    : "pcfme_get_child_field_options",
					mtype     : mtype4


				},
				type: 'POST',
				url: ajaxurl,
				success: function( response ) { 

					

					var child_field_type = "text";



					var tresponse = JSON.parse(response);


					var field_data = tresponse.field_data;
					var child_field = field_data[this_val];


					var child_field_type = child_field['type'];

					


					var affected_field = $('tr.conditional-row-'+row_number+'');




					affected_field.find('.checkout_field_conditional_equalto').show();
					affected_field.find('.pcfmeformfield_equal_to').show();

					affected_field.find('.checkout_field_conditional_select').hide();
					affected_field.find('.pcfmeformfield_equal_to').text(pcfmeadmin.equaltext);

                    

					if ((child_field_type == "paragraph") || (child_field_type == "heading")  || (child_field_type == "checkbox")) {
						affected_field.find('.checkout_field_conditional_equalto').hide();

						if (child_field_type == "checkbox") {

							
							
							affected_field.find('.pcfmeformfield_equal_to').text(pcfmeadmin.is_checked);
							$('.conditional_select_hidden_type_'+row_number+'').val("checkbox");
						} else {
							affected_field.find('.pcfmeformfield_equal_to').hide();
						}

						

					} else if ((child_field_type == "pcfmeselect") || (child_field_type == "multiselect")  || (child_field_type == "radio")) {

						

						const options_array = child_field['new_options']; 

						affected_field.find('.conditional_select_hidden_type').val("select");
						

						
						
						affected_field.find('.checkout_field_conditional_equalto').val('');
						

						affected_field.find('.checkout_field_conditional_select').empty();

						affected_field.find('.checkout_field_conditional_select').show();

						



						$.each(options_array, function (i, item) {



							var newState = new Option(item['text'], item['value'], true, true);

							affected_field.find('.checkout_field_conditional_select').append(newState).trigger("change");     
						});
						


						affected_field.find('.checkout_field_conditional_equalto').hide();

						affected_field.find('.checkout_field_conditional_select').show();


					} else if (child_field_type == "text") {

						affected_field.find('.checkout_field_conditional_equalto').show();
						affected_field.find('.pcfmeformfield_equal_to').show();

						affected_field.find('.checkout_field_conditional_select').hide();
						affected_field.find('.conditional_select_hidden_type').val("text");

						affected_field.find('.pcfmeformfield_equal_to').text(pcfmeadmin.equaltext);


					} else {

						affected_field.find('.checkout_field_conditional_equalto').show();
						affected_field.find('.pcfmeformfield_equal_to').show();

						affected_field.find('.checkout_field_conditional_select').hide();
						affected_field.find('.conditional_select_hidden_type').val("text");
						affected_field.find('.pcfmeformfield_equal_to').text(pcfmeadmin.equaltext);


					}


				}
			});



			return false;

		});

		$('.checkout_field_conditional_parentfield').on('change',function(event){

			event.preventDefault();

			var this_val = $(this).val();
			var mtype    = $(this).attr("mtype");
			var mntext    = $(this).attr("mntext");
			var mfield    = $(this).attr("mfield");




			$.ajax({
				data: {
					action    : "pcfme_get_child_field_options",
					mtype     : mtype
					

				},
				type: 'POST',
				url: ajaxurl,
				success: function( response ) { 

					var child_field_type = "text";



					var tresponse = JSON.parse(response);

					

					var field_data = tresponse.field_data;
					var child_field = field_data[this_val];




					var child_field_type = child_field['type'];


					

					var affected_field = $('.conditional_row_'+mntext+'_'+mfield+'');


					affected_field.find('.checkout_field_conditional_equalto').hide();
					affected_field.find('.pcfmeformfield_equal_to').hide();

					affected_field.find('.checkout_field_conditional_select').hide();
					affected_field.find('.pcfmeformfield_equal_to').text(pcfmeadmin.equaltext);

					
					
					if ((child_field_type == "paragraph") || (child_field_type == "heading")  || (child_field_type == "checkbox")) {
						affected_field.find('.checkout_field_conditional_equalto').hide();

						if (child_field_type == "checkbox") {
							affected_field.find('.pcfmeformfield_equal_to').text(pcfmeadmin.is_checked);
							affected_field.find('.pcfmeformfield_equal_to').show();
							affected_field.find('.conditional_select_hidden_type').val("checkbox");
						} 



					} else if ((child_field_type == "pcfmeselect") || (child_field_type == "multiselect")  || (child_field_type == "radio")) {

						

						const options_array = child_field['new_options']; 



						affected_field.find('.checkout_field_conditional_select').empty();

						

						

						$.each(options_array, function (i, item) {

							

							var newState = new Option(item['text'], item['value'], true, true);
							
							affected_field.find('.checkout_field_conditional_select').append(newState).trigger("change");     
						});


						affected_field.find('.checkout_field_conditional_equalto').hide();

						affected_field.find('.pcfmeformfield_equal_to').text(pcfmeadmin.equaltext);
						affected_field.find('.pcfmeformfield_equal_to').show();
						affected_field.find('.conditional_select_hidden_type').val("select");

						

						affected_field.find('.checkout_field_conditional_select').show();

					} else if (child_field_type == "text") {

						affected_field.find('.checkout_field_conditional_equalto').show();
						affected_field.find('.pcfmeformfield_equal_to').show();

						affected_field.find('.checkout_field_conditional_select').hide();
						affected_field.find('.pcfmeformfield_equal_to').text(pcfmeadmin.equaltext);

						affected_field.find('.conditional_select_hidden_type').val("text");

						
					} else {

						affected_field.find('.checkout_field_conditional_equalto').show();
						affected_field.find('.pcfmeformfield_equal_to').show();

						affected_field.find('.checkout_field_conditional_select').hide();
						affected_field.find('.pcfmeformfield_equal_to').text(pcfmeadmin.equaltext);

						affected_field.find('.conditional_select_hidden_type').val("text");
					}


				}
			});

			

			return false;

		});

		$('.checkout_field_visibility').on('change',function(){
			var visibilityvalue2 = $(this).val();
			 if (visibilityvalue2 == "shipping-specific") {
				
				$(this).parents('table:eq(0)').find('.checkout_field_shipping_tr').show();
				$(this).parents('table:eq(0)').find('.checkout_field_payment_tr').hide();
				$(this).parents('table:eq(0)').find('.checkout_field_dynamic_tr_new').hide();
				
			} else if (visibilityvalue2 == "payment-specific") {
				
				$(this).parents('table:eq(0)').find('.checkout_field_shipping_tr').hide();
				$(this).parents('table:eq(0)').find('.checkout_field_payment_tr').show();
				$(this).parents('table:eq(0)').find('.checkout_field_dynamic_tr_new').hide();
				
			}  else if (visibilityvalue2 == "dynamically-visible") {

				
				$(this).parents('table:eq(0)').find('.checkout_field_shipping_tr').hide();
				$(this).parents('table:eq(0)').find('.checkout_field_payment_tr').hide();
				$(this).parents('table:eq(0)').find('.checkout_field_dynamic_tr_new').show();
			} else {
				
				$(this).parents('table:eq(0)').find('.checkout_field_shipping_tr').hide();
				$(this).parents('table:eq(0)').find('.checkout_field_payment_tr').hide();
				$(this).parents('table:eq(0)').find('.checkout_field_dynamic_tr_new').hide();
			}
		});






$('.pcfme_checkout_field_extraclass').tagEditor({
    delimiter: ', ', /* space and comma */
	placeholder: pcfmeadmin.classplaceholder
});

});

$( ".conditional-row" ).each(function() {
	$(this).find('.checkout_field_dynamic_rule_date').datetimepicker({		
		timepicker:false,
		format:'Y-m-d',
		defaultDate: new Date()
	}); 
});


	 

$('.checkout_field_dynamic_rule_time').datetimepicker({		
	datepicker:false,
	format:'H:i',	
});

$('.checkout_field_dynamic_rule_date_time').datetimepicker({		
		
});


$(document).on('click', '.pcfme_trash_icon', function () {

	var result = confirm(pcfmeadmin.removealert);
	if (result==true) {
		$(this).parents('.panel').get(0).remove();
	}
});


function pcfme_change_type_val_toggle(type_val,element) {



	switch(type_val) {

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

	if ($row_mode == "quantity") {
		element.parents('.conditional-row').find('.show_if_quantity_rule').show();
		element.parents('.conditional-row').find('.show_if_contains_rule').hide();
		element.parents('.conditional-row').find('.show_if_from_to_specific_rule').hide();
		element.parents('.conditional-row').find('.show_if_from_to_rule').hide();
	} else if ($row_mode == "contains")  {
		
		element.parents('.conditional-row').find('.show_if_quantity_rule').hide();
		element.parents('.conditional-row').find('.show_if_contains_rule').show();
		element.parents('.conditional-row').find('.show_if_from_to_specific_rule').hide();
		element.parents('.conditional-row').find('.show_if_from_to_rule').hide();
	} else if ($row_mode == "from_to_specific")  {
		element.parents('.conditional-row').find('.show_if_quantity_rule').hide();
		element.parents('.conditional-row').find('.show_if_contains_rule').hide();
		element.parents('.conditional-row').find('.show_if_from_to_rule').hide();
		element.parents('.conditional-row').find('.show_if_from_to_specific_rule').show();
	} else if ($row_mode == "from_to")  {
		element.parents('.conditional-row').find('.show_if_quantity_rule').hide();
		element.parents('.conditional-row').find('.show_if_contains_rule').hide();
		element.parents('.conditional-row').find('.show_if_from_to_specific_rule').hide();
		element.parents('.conditional-row').find('.show_if_from_to_rule').show();
	} else {
		element.parents('.conditional-row').find('.show_if_quantity_rule').hide();
		element.parents('.conditional-row').find('.show_if_contains_rule').hide();
		element.parents('.conditional-row').find('.show_if_from_to_specific_rule').hide();
		element.parents('.conditional-row').find('.show_if_from_to_rule').hide();
	}

	switch($filter_mode) {

	case "products":
		element.parents('.conditional-row').find('.checkout_field_coupons_span').hide();
		element.parents('.conditional-row').find('.checkout_field_months_span').hide();
		element.parents('.conditional-row').find('.checkout_field_monthdays_span').hide();
		element.parents('.conditional-row').find('.checkout_field_weekday_span').hide();
		element.parents('.conditional-row').find('.checkout_field_date_time_span').hide();
        element.parents('.conditional-row').find('.checkout_field_time_span').hide();
		element.parents('.conditional-row').find('.checkout_field_category_span').hide();
		element.parents('.conditional-row').find('.checkout_field_products_span').show();
		element.parents('.conditional-row').find('.checkout_field_roles_span').hide();
		element.parents('.conditional-row').find('.checkout_field_date_span').hide();
		element.parents('.conditional-row').find('.show_if_contains_rule_div').show(); 

		break;

	case "roles":
		element.parents('.conditional-row').find('.checkout_field_coupons_span').hide();
		element.parents('.conditional-row').find('.checkout_field_months_span').hide();
		element.parents('.conditional-row').find('.checkout_field_monthdays_span').hide();
		element.parents('.conditional-row').find('.checkout_field_weekday_span').hide();
		element.parents('.conditional-row').find('.checkout_field_date_time_span').hide();
        element.parents('.conditional-row').find('.checkout_field_time_span').hide();
		element.parents('.conditional-row').find('.checkout_field_roles_span').show();
		element.parents('.conditional-row').find('.checkout_field_category_span').hide();
		element.parents('.conditional-row').find('.checkout_field_products_span').hide();
		element.parents('.conditional-row').find('.checkout_field_date_span').hide();
		element.parents('.conditional-row').find('.show_if_contains_rule_div').show(); 
		break;

	case "categories":
		element.parents('.conditional-row').find('.checkout_field_coupons_span').hide();
		element.parents('.conditional-row').find('.checkout_field_months_span').hide();
		element.parents('.conditional-row').find('.checkout_field_monthdays_span').hide();
		element.parents('.conditional-row').find('.checkout_field_weekday_span').hide();
		element.parents('.conditional-row').find('.checkout_field_date_time_span').hide();
        element.parents('.conditional-row').find('.checkout_field_time_span').hide();
		element.parents('.conditional-row').find('.checkout_field_category_span').show();
		element.parents('.conditional-row').find('.checkout_field_roles_span').hide();
		element.parents('.conditional-row').find('.checkout_field_products_span').hide();  
		element.parents('.conditional-row').find('.checkout_field_date_span').hide();
		element.parents('.conditional-row').find('.show_if_contains_rule_div').show();   	 
	break;


     case "date":
     	element.parents('.conditional-row').find('.checkout_field_coupons_span').hide();
     	element.parents('.conditional-row').find('.checkout_field_months_span').hide();
     	element.parents('.conditional-row').find('.checkout_field_monthdays_span').hide();
     	element.parents('.conditional-row').find('.checkout_field_weekday_span').hide();
     	element.parents('.conditional-row').find('.checkout_field_date_time_span').hide();
     	element.parents('.conditional-row').find('.checkout_field_time_span').hide();
        element.parents('.conditional-row').find('.checkout_field_date_span').show();
		element.parents('.conditional-row').find('.checkout_field_category_span').hide();
		element.parents('.conditional-row').find('.checkout_field_roles_span').hide();
		element.parents('.conditional-row').find('.checkout_field_products_span').hide();  
		element.parents('.conditional-row').find('.show_if_contains_rule_div').show(); 
		element.parents('.conditional-row').find('.checkout_field_dynamic_rule_date').datetimepicker({		
	    			timepicker:false,
	    			format:'Y-m-d',
	    			defaultDate: new Date()		
	    }); 	 
	break;

    case "time":
    	element.parents('.conditional-row').find('.checkout_field_coupons_span').hide();
    	element.parents('.conditional-row').find('.checkout_field_months_span').hide();
    	element.parents('.conditional-row').find('.checkout_field_monthdays_span').hide();
    	element.parents('.conditional-row').find('.checkout_field_weekday_span').hide();
    	element.parents('.conditional-row').find('.checkout_field_date_time_span').hide();
    	element.parents('.conditional-row').find('.checkout_field_time_span').show();
        element.parents('.conditional-row').find('.checkout_field_date_span').hide();
		element.parents('.conditional-row').find('.checkout_field_category_span').hide();
		element.parents('.conditional-row').find('.checkout_field_roles_span').hide();
		element.parents('.conditional-row').find('.checkout_field_products_span').hide();  
		element.parents('.conditional-row').find('.show_if_contains_rule_div').show(); 
		element.parents('.conditional-row').find('.checkout_field_dynamic_rule_time').datetimepicker({		
	    			datepicker:false,
	    			format:'H:i',
	    				
	    }); 	 
	break;


    case "date_time":
    	element.parents('.conditional-row').find('.checkout_field_coupons_span').hide();
    	element.parents('.conditional-row').find('.checkout_field_months_span').hide();
    	element.parents('.conditional-row').find('.checkout_field_monthdays_span').hide();
    	element.parents('.conditional-row').find('.checkout_field_weekday_span').hide();
    	element.parents('.conditional-row').find('.checkout_field_date_time_span').show();
    	element.parents('.conditional-row').find('.checkout_field_time_span').hide();
        element.parents('.conditional-row').find('.checkout_field_date_span').hide();
		element.parents('.conditional-row').find('.checkout_field_category_span').hide();
		element.parents('.conditional-row').find('.checkout_field_roles_span').hide();
		element.parents('.conditional-row').find('.checkout_field_products_span').hide();  
		element.parents('.conditional-row').find('.show_if_contains_rule_div').show(); 
		element.parents('.conditional-row').find('.checkout_field_dynamic_rule_date_time').datetimepicker({		
	    			
	    }); 	 
	break;


    case "weekday":
    	element.parents('.conditional-row').find('.checkout_field_coupons_span').hide();
    	element.parents('.conditional-row').find('.checkout_field_months_span').hide();
    	element.parents('.conditional-row').find('.checkout_field_monthdays_span').hide();
    	element.parents('.conditional-row').find('.checkout_field_weekday_span').show();
    	element.parents('.conditional-row').find('.checkout_field_date_time_span').hide();
    	element.parents('.conditional-row').find('.checkout_field_time_span').hide();
        element.parents('.conditional-row').find('.checkout_field_date_span').hide();
		element.parents('.conditional-row').find('.checkout_field_category_span').hide();
		element.parents('.conditional-row').find('.checkout_field_roles_span').hide();
		element.parents('.conditional-row').find('.checkout_field_products_span').hide();  
		element.parents('.conditional-row').find('.show_if_contains_rule_div').show(); 
		element.parents('.conditional-row').find('.show_if_contains_rule_div').find('.checkout_field_weekday').select2({
	         width:"250px" 
        });
		 	 
	break;


	case "monthdays":
		element.parents('.conditional-row').find('.checkout_field_coupons_span').hide();
		element.parents('.conditional-row').find('.checkout_field_months_span').hide();
		element.parents('.conditional-row').find('.checkout_field_monthdays_span').show();
    	element.parents('.conditional-row').find('.checkout_field_weekday_span').hide();
    	element.parents('.conditional-row').find('.checkout_field_date_time_span').hide();
    	element.parents('.conditional-row').find('.checkout_field_time_span').hide();
        element.parents('.conditional-row').find('.checkout_field_date_span').hide();
		element.parents('.conditional-row').find('.checkout_field_category_span').hide();
		element.parents('.conditional-row').find('.checkout_field_roles_span').hide();
		element.parents('.conditional-row').find('.checkout_field_products_span').hide();  
		element.parents('.conditional-row').find('.show_if_contains_rule_div').show(); 
		element.parents('.conditional-row').find('.show_if_contains_rule_div').find('.checkout_field_monthdays').select2({
	         width:"250px" 
        });
		 	 
	break;


    case "months":
    	element.parents('.conditional-row').find('.checkout_field_coupons_span').hide();
    	element.parents('.conditional-row').find('.checkout_field_months_span').show();
		element.parents('.conditional-row').find('.checkout_field_monthdays_span').hide();
    	element.parents('.conditional-row').find('.checkout_field_weekday_span').hide();
    	element.parents('.conditional-row').find('.checkout_field_date_time_span').hide();
    	element.parents('.conditional-row').find('.checkout_field_time_span').hide();
        element.parents('.conditional-row').find('.checkout_field_date_span').hide();
		element.parents('.conditional-row').find('.checkout_field_category_span').hide();
		element.parents('.conditional-row').find('.checkout_field_roles_span').hide();
		element.parents('.conditional-row').find('.checkout_field_products_span').hide();  
		element.parents('.conditional-row').find('.show_if_contains_rule_div').show(); 
		element.parents('.conditional-row').find('.show_if_contains_rule_div').find('.checkout_field_months').select2({
	         width:"250px" 
        });
		 	 
	break;

	case "coupons":
    	element.parents('.conditional-row').find('.checkout_field_coupons_span').show();
    	element.parents('.conditional-row').find('.checkout_field_months_span').hide();
		element.parents('.conditional-row').find('.checkout_field_monthdays_span').hide();
    	element.parents('.conditional-row').find('.checkout_field_weekday_span').hide();
    	element.parents('.conditional-row').find('.checkout_field_date_time_span').hide();
    	element.parents('.conditional-row').find('.checkout_field_time_span').hide();
        element.parents('.conditional-row').find('.checkout_field_date_span').hide();
		element.parents('.conditional-row').find('.checkout_field_category_span').hide();
		element.parents('.conditional-row').find('.checkout_field_roles_span').hide();
		element.parents('.conditional-row').find('.checkout_field_products_span').hide();  
		element.parents('.conditional-row').find('.show_if_contains_rule_div').show(); 
		element.parents('.conditional-row').find('.show_if_contains_rule_div').find('.checkout_field_coupons').select2({

			ajax: {
    			url: ajaxurl, // AJAX URL is predefined in WordPress admin
    			dataType: 'json',
    			delay: 250, // delay in ms while typing when to perform a AJAX search
    			data: function (params) {
    				return {
        				q: params.term, // search query
        				action: 'pdfmegetajaxcouponslist' // AJAX action for admin-ajax.php
        			};
        		},
        		processResults: function( data ) {
        			var options = [];
        			if ( data ) {

					// data is the array of arrays, and each of them contains ID and the Label of the option
					$.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
						options.push( { id: text[0], text: text[1]  } );
					});

				}
				return {
					results: options
				};
			    },
			    cache: true
		    },
		    minimumInputLength: 3 ,
		    minimumResultsForSearch: -1,
	        width: "300px"// the minimum of symbols to input before perform a search
	    });
		 	 
	break;

	case "none":
		element.parents('.conditional-row').find('.checkout_field_coupons_span').hide();
		element.parents('.conditional-row').find('.checkout_field_months_span').hide();
		element.parents('.conditional-row').find('.checkout_field_monthdays_span').hide();
		element.parents('.conditional-row').find('.checkout_field_weekday_span').hide();
		element.parents('.conditional-row').find('.checkout_field_date_time_span').hide();
		element.parents('.conditional-row').find('.checkout_field_time_span').hide();
		element.parents('.conditional-row').find('.checkout_field_category_span').hide();
		element.parents('.conditional-row').find('.checkout_field_roles_span').hide();
		element.parents('.conditional-row').find('.checkout_field_date_span').hide();
		element.parents('.conditional-row').find('.checkout_field_products_span').hide();   
		element.parents('.conditional-row').find('.show_if_contains_rule_div').hide(); 	 
	break;


	}
}




$('.checkout_field_dynamic_rule_type').on("change",function(event){

	event.preventDefault();

	var type_val = $(this).val();
	var element = $(this);
    
    pcfme_change_type_val_toggle(type_val,element);


	return false;

});


$('.checkout_field_quantity_specific_product_fees,.checkout_field_products,.checkout_field_products_variations,.checkout_field_quantity_specific_product').select2({

	ajax: {
    			url: ajaxurl, // AJAX URL is predefined in WordPress admin
    			dataType: 'json',
    			delay: 250, // delay in ms while typing when to perform a AJAX search
    			data: function (params) {
    				return {
        				q: params.term, // search query
        				action: 'pdfmegetajaxproductslist' // AJAX action for admin-ajax.php
        			};
        		},
        		processResults: function( data ) {
        			var options = [];
        			if ( data ) {

					// data is the array of arrays, and each of them contains ID and the Label of the option
					$.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
						options.push( { id: text[0], text: text[1]  } );
					});

				}
				return {
					results: options
				};
			},
			cache: true
		},
		minimumInputLength: 3 ,
		minimumResultsForSearch: -1,
	    width: "300px"// the minimum of symbols to input before perform a search
});


$('.checkout_field_coupons').select2({

	ajax: {
    			url: ajaxurl, // AJAX URL is predefined in WordPress admin
    			dataType: 'json',
    			delay: 250, // delay in ms while typing when to perform a AJAX search
    			data: function (params) {
    				return {
        				q: params.term, // search query
        				action: 'pdfmegetajaxcouponslist' // AJAX action for admin-ajax.php
        			};
        		},
        		processResults: function( data ) {
        			var options = [];
        			if ( data ) {

					// data is the array of arrays, and each of them contains ID and the Label of the option
					$.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
						options.push( { id: text[0], text: text[1]  } );
					});

				}
				return {
					results: options
				};
			},
			cache: true
		},
		minimumInputLength: 3 ,
		minimumResultsForSearch: -1,
	    width: "300px"// the minimum of symbols to input before perform a search
});

$("#restore-billing-fields").on('click',function() {
	var result2 = confirm(pcfmeadmin.restorealert);
	if (result2 == true) {
		
		$.ajax({
			data: {action: "restore_billing_fields" },
			type: 'POST',
			url: ajaxurl,
			success: function( response ) { 
				window.location.reload();
			}
		});
	}
});

$("#restore-shipping-fields").on('click',function() {
	var result3 = confirm(pcfmeadmin.restorealert);
	if (result3 == true) {
		
		$.ajax({
			data: {action: "restore_shipping_fields" },
			type: 'POST',
			url: ajaxurl,
			success: function( response ) { 
				window.location.reload();
			}
		});
	}
});



$('.pcfme_accordion_onoff').click(function() {

	var parentkey = $(this).attr("parentkey");
	
	if ($(this).is(":checked")) {
		$(this).parents("table."+ parentkey +"_panel").removeClass('pcfme_disabled');
		$("."+ parentkey +"_hidden_checkbox").val(0);
		
	} else {
		
		$(this).parents("table."+ parentkey +"_panel").addClass('pcfme_disabled');
		$("."+ parentkey +"_hidden_checkbox").val(1);
		
	}
});




$('span.pcfme_remove_icon.dashicons.dashicons-remove').on('click',function(){

	$(this).parents('tr.pcfme_sortable_tr').remove();
});

$('.pcfme_change_key_input').keyup(function () {
	var clkey      = $(this).attr("clkey");
	var enteredval = $(this).val();

	$('.pcfme_field_key_'+ clkey +'').text(enteredval);
	$('.pcfme_copy_key_icon_'+ clkey +'').attr("cpkey",enteredval);
	
});

$('.pcfme_value_input').keyup(function(event){

	event.preventDefault();

	var changed_value        = $(this).val();

	event.mnindex            = $(this).attr("mnindex");

	var fieldkey             = $(this).attr("keyno");

	$('.pcfme_text_input_'+fieldkey+'_'+event.mnindex+'').val(changed_value);
	
	return false;
});

$('.add-option-button').on('click',function(event){

	event.preventDefault();

	event.mnindex       = $(this).attr("mnindex");

	var fieldkey        = $(this).attr("keyno");



	var option_html            = '';

	event.mntype        = $(this).attr("mntype");

	var input1          = '<input type="text" class="pcfme_text_input_'+fieldkey+'_'+event.mnindex+'"  class="pcfme_value_input" name="pcfme_'+event.mntype+'_settings['+fieldkey+'][new_options]['+event.mnindex+'][value]" value="">';
	var input2          = '<input type="text" class="pcfme_value_input" keyno="'+fieldkey+'" mnindex="'+event.mnindex+'" name="pcfme_'+event.mntype+'_settings['+fieldkey+'][new_options]['+event.mnindex+'][text]" value="">&nbsp;<span class="pcfme_remove_icon dashicons dashicons-remove"></span>';
	var sortable_span   = '<span class="pcfme_sortable_tr_handle dashicons dashicons-menu"></span>';

	option_html += '<tr class="pcfme_sortable_tr" style=""><td class="pcfme_sortable_td_first ui-sortable-handle">'+sortable_span+'</td><td>'+ input1 +'</td><td>'+ input2 +'</td></tr>';
	
	var new_row = $('.pcfme_sortable_table_options_'+fieldkey+' tr:last').after(option_html);
	
	$('.pcfme_value_input').keyup(function(event_child){

		event_child.preventDefault();

		var changed_value2             = $(this).val();

		var mnindex_child              = $(this).attr("mnindex");

		var fieldkey_child             = $(this).attr("keyno");

		$('.pcfme_text_input_'+fieldkey_child+'_'+mnindex_child+'').val(changed_value2);

		return false;
	});


	$('span.pcfme_remove_icon.dashicons.dashicons-remove').on('click',function(){

		$(this).parents('tr.pcfme_sortable_tr').remove();
	});

	event.mnindex++;

	$(this).attr("mnindex",event.mnindex);

	return false;

});





$('.add-rule-button').on('click',function(event){

	event.preventDefault();



	event.mnindex       = $(this).attr("gtindex");

	var fieldkey        = $(this).attr("keyno");

	var html            = '';

	event.mntype        = $(this).attr("mntype");





	

	var showtext   = pcfmeadmin.showtext;
	var hidetext   = pcfmeadmin.hidetext;
	var valuetext  = pcfmeadmin.valuetext
	var equaltext  = pcfmeadmin.equaltext;

	var select1  = pcfmeadmin.rule_type_select1;
	var ruleselect2  = pcfmeadmin.rule_type_select2;
	var ruleselect3  = pcfmeadmin.rule_type_select3;
	var ruleselect4  = pcfmeadmin.rule_type_select4;
	var ruleselect5  = pcfmeadmin.rule_type_select5;
	var rule_type_number = pcfmeadmin.rule_type_number;
    var rule_type_number2 = pcfmeadmin.rule_type_number2;
    var rule_type_number3 = pcfmeadmin.rule_type_number3;
    var rule_type_number4 = pcfmeadmin.rule_type_number4;
    var rule_type_number5 = pcfmeadmin.rule_type_number5;

	var deletei  = '&nbsp;<span class="dashicons dashicons-remove pcfme-remove-condition-dynamic"></span>';

	var sort_icon = '<span class="pcfme_sortable_tr_handle_dynamic dashicons dashicons-menu"></span>&nbsp;';

	var checkbox = '&nbsp;<input type="checkbox" class="rule_enabled_checkbox" value="yes" checked>&nbsp;';

	html        += '<div class="conditional-row conditional_row_'+event.mnindex+'_'+fieldkey+'">'+sort_icon+''+checkbox+''+select1+'&nbsp;'+ruleselect2+'&nbsp'+ruleselect3+'&nbsp'+ruleselect4+''+ruleselect5+'&nbsp;&nbsp;&nbsp;'+rule_type_number+''+rule_type_number2+''+rule_type_number3+''+rule_type_number4+''+rule_type_number5+''+deletei+'</div>';


   



	$(this).parents('.checkout_field_dynamic_tr_new').find('.dynamic_fields_div_wrapper').append(html);






	var conditional_row_   = '.conditional_row_'+event.mnindex+'_'+fieldkey+'';

	var rule_type_         = '.checkout_field_dynamic_rule_type';

	var checkout_tr_       = '.checkout_field_dynamic_tr_new';

	var dynamic_rule_type_add = 'checkout_field_dynamic_rule_type_'+event.mnindex+'_'+fieldkey+'';

	var dynamic_rule_type_ = '.checkout_field_dynamic_rule_type_'+event.mnindex+'_'+fieldkey+'';

	var dynamic_name_      = 'pcfme_'+event.mntype+'_settings['+fieldkey+'][dynamic_rules]['+event.mnindex+'][rule_type]';


	$(this).parents(checkout_tr_).find(conditional_row_).find('.rule_enabled_checkbox').attr('name','pcfme_'+event.mntype+'_settings['+fieldkey+'][dynamic_rules]['+event.mnindex+'][enabled]');


	
    var this_element = $(this);


    trigger_dynamic_rule_change_pfcme(this_element,checkout_tr_,conditional_row_,rule_type_,dynamic_rule_type_,dynamic_rule_type_add,event,fieldkey,dynamic_name_);

    var rule_type_compare         = '.checkout_field_dynamic_rule_type_compare';

    var dynamic_rule_type_compare_add = 'checkout_field_dynamic_rule_type_compare_'+event.mnindex+'_'+fieldkey+'';

    var dynamic_rule_type_compare = '.checkout_field_dynamic_rule_type_compare_'+event.mnindex+'_'+fieldkey+'';

    var dynamic_name_compare      = 'pcfme_'+event.mntype+'_settings['+fieldkey+'][dynamic_rules]['+event.mnindex+'][rule_type_compare]';

    trigger_dynamic_rule_change_pfcme(this_element,checkout_tr_,conditional_row_,rule_type_compare,dynamic_rule_type_compare,dynamic_rule_type_compare_add,event,fieldkey,dynamic_name_compare);

    
    


    var rule_type_contains         = '.checkout_field_dynamic_rule_type_contains';

    var dynamic_rule_type_contains_add = 'checkout_field_dynamic_rule_type_contains_'+event.mnindex+'_'+fieldkey+'';

    var dynamic_rule_type_contains = '.checkout_field_dynamic_rule_type_contains_'+event.mnindex+'_'+fieldkey+'';

    var dynamic_name_contains      = 'pcfme_'+event.mntype+'_settings['+fieldkey+'][dynamic_rules]['+event.mnindex+'][rule_type_contains]';

    trigger_dynamic_rule_change_pfcme(this_element,checkout_tr_,conditional_row_,rule_type_contains,dynamic_rule_type_contains,dynamic_rule_type_contains_add,event,fieldkey,dynamic_name_contains);


    var rule_type_from_to_specific         = '.checkout_field_dynamic_rule_type_from_to_specific';

    var dynamic_rule_type_from_to_specific_add = 'checkout_field_dynamic_rule_type_from_to_specific_'+event.mnindex+'_'+fieldkey+'';

    var dynamic_rule_type_from_to_specific = '.checkout_field_dynamic_rule_type_from_to_specific_'+event.mnindex+'_'+fieldkey+'';

    var dynamic_name_from_to_specific      = 'pcfme_'+event.mntype+'_settings['+fieldkey+'][dynamic_rules]['+event.mnindex+'][from_to_specific]';

    trigger_dynamic_rule_change_pfcme(this_element,checkout_tr_,conditional_row_,rule_type_from_to_specific,dynamic_rule_type_from_to_specific,dynamic_rule_type_from_to_specific_add,event,fieldkey,dynamic_name_from_to_specific);


    var rule_type_from_to         = '.checkout_field_dynamic_rule_type_from_to';

    var dynamic_rule_type_from_to_add = 'checkout_field_dynamic_rule_type_from_to_'+event.mnindex+'_'+fieldkey+'';

    var dynamic_rule_type_from_to = '.checkout_field_dynamic_rule_type_from_to_'+event.mnindex+'_'+fieldkey+'';

    var dynamic_name_from_to      = 'pcfme_'+event.mntype+'_settings['+fieldkey+'][dynamic_rules]['+event.mnindex+'][from_to]';

    trigger_dynamic_rule_change_pfcme(this_element,checkout_tr_,conditional_row_,rule_type_from_to,dynamic_rule_type_from_to,dynamic_rule_type_from_to_add,event,fieldkey,dynamic_name_from_to);


    var rule_type_product_multiple        = '.checkout_field_products';

    var dynamic_rule_type_product_add = 'rule_type_product_multiple_'+event.mnindex+'_'+fieldkey+'';

    var dynamic_rule_type_product = '.rule_type_product_multiple_'+event.mnindex+'_'+fieldkey+'';

    var dynamic_name_products      = 'pcfme_'+event.mntype+'_settings['+fieldkey+'][dynamic_rules]['+event.mnindex+'][rule_type_products][]';

    trigger_dynamic_rule_change_pfcme_multiselect(this_element,checkout_tr_,conditional_row_,rule_type_product_multiple,dynamic_rule_type_product,dynamic_rule_type_product_add,event,fieldkey,dynamic_name_products);
    

    //for weekday pickers
    var weekday_multiple  = '.checkout_field_weekday';

    var weekday_add       = 'rule_type_weekday_multiple_'+event.mnindex+'_'+fieldkey+'';

    var weekday           = '.rule_type_weekday_multiple_'+event.mnindex+'_'+fieldkey+'';

    var name_weekday      = 'pcfme_'+event.mntype+'_settings['+fieldkey+'][dynamic_rules]['+event.mnindex+'][rule_type_days][]';

    pfcme_multiselect_normal(this_element,checkout_tr_,conditional_row_,weekday_multiple,weekday,weekday_add,event,fieldkey,name_weekday);
    


    //for monthday pickers
    var monthdays_multiple  = '.checkout_field_monthdays';

    var monthdays_add       = 'rule_type_monthdays_multiple_'+event.mnindex+'_'+fieldkey+'';

    var monthdays           = '.rule_type_monthdays_multiple_'+event.mnindex+'_'+fieldkey+'';

    var name_monthdays      = 'pcfme_'+event.mntype+'_settings['+fieldkey+'][dynamic_rules]['+event.mnindex+'][rule_type_monthdays][]';

    pfcme_multiselect_normal(this_element,checkout_tr_,conditional_row_,monthdays_multiple,monthdays,monthdays_add,event,fieldkey,name_monthdays);
     
    

    //for months pickers
    var months_multiple     = '.checkout_field_months';

    var months_add       = 'rule_type_months_multiple_'+event.mnindex+'_'+fieldkey+'';

    var months           = '.rule_type_months_multiple_'+event.mnindex+'_'+fieldkey+'';

    var name_months      = 'pcfme_'+event.mntype+'_settings['+fieldkey+'][dynamic_rules]['+event.mnindex+'][rule_type_months][]';

    pfcme_multiselect_normal(this_element,checkout_tr_,conditional_row_,months_multiple,months,months_add,event,fieldkey,name_months);



    var rule_type_coupons_multiple        = '.checkout_field_coupons';

    var dynamic_rule_type_coupons_add = 'rule_type_coupons_multiple_'+event.mnindex+'_'+fieldkey+'';

    var dynamic_rule_type_coupons = '.rule_type_coupons_multiple_'+event.mnindex+'_'+fieldkey+'';

    var dynamic_name_coupons      = 'pcfme_'+event.mntype+'_settings['+fieldkey+'][dynamic_rules]['+event.mnindex+'][rule_type_coupons][]';

    trigger_dynamic_rule_change_pfcme_coupons(this_element,checkout_tr_,conditional_row_,rule_type_coupons_multiple,dynamic_rule_type_coupons,dynamic_rule_type_coupons_add,event,fieldkey,dynamic_name_coupons);
    

    var rule_type_category_multiple        = '.checkout_field_category';

    var dynamic_rule_type_category_add = 'rule_type_category_multiple_'+event.mnindex+'_'+fieldkey+'';

    var dynamic_rule_type_category = '.rule_type_category_multiple_'+event.mnindex+'_'+fieldkey+'';

    var dynamic_name_categories      = 'pcfme_'+event.mntype+'_settings['+fieldkey+'][dynamic_rules]['+event.mnindex+'][rule_type_categories][]';

    trigger_dynamic_rule_change_pfcme_category(this_element,checkout_tr_,conditional_row_,rule_type_category_multiple,dynamic_rule_type_category,dynamic_rule_type_category_add,event,fieldkey,dynamic_name_categories);
   
    

    var rule_type_role_multiple        = '.checkout_field_role';

    var dynamic_rule_type_role_add = 'rule_type_role_multiple_'+event.mnindex+'_'+fieldkey+'';

    var dynamic_rule_type_role = '.rule_type_role_multiple_'+event.mnindex+'_'+fieldkey+'';

    var dynamic_name_roles      = 'pcfme_'+event.mntype+'_settings['+fieldkey+'][dynamic_rules]['+event.mnindex+'][rule_type_roles][]';

    trigger_dynamic_rule_change_pfcme_category(this_element,checkout_tr_,conditional_row_,rule_type_role_multiple,dynamic_rule_type_role,dynamic_rule_type_role_add,event,fieldkey,dynamic_name_roles);


    var rule_type_number         = '.checkout_field_dynamic_rule_number';

    var dynamic_rule_type_number_add = 'checkout_field_dynamic_rule_number_'+event.mnindex+'_'+fieldkey+'';

    var dynamic_rule_type_number = '.checkout_field_dynamic_rule_number_'+event.mnindex+'_'+fieldkey+'';

    var dynamic_name_number      = 'pcfme_'+event.mntype+'_settings['+fieldkey+'][dynamic_rules]['+event.mnindex+'][rule_type_number]';

    trigger_dynamic_rule_change_pfcme(this_element,checkout_tr_,conditional_row_,rule_type_number,dynamic_rule_type_number,dynamic_rule_type_number_add,event,fieldkey,dynamic_name_number);
    

    var rule_type_date         = '.checkout_field_dynamic_rule_date';

    var dynamic_rule_type_date_add = 'checkout_field_dynamic_rule_date_'+event.mnindex+'_'+fieldkey+'';

    var dynamic_rule_type_date = '.checkout_field_dynamic_rule_date_'+event.mnindex+'_'+fieldkey+'';

    var dynamic_name_date      = 'pcfme_'+event.mntype+'_settings['+fieldkey+'][dynamic_rules]['+event.mnindex+'][rule_type_date]';

    trigger_dynamic_rule_change_pfcme(this_element,checkout_tr_,conditional_row_,rule_type_date,dynamic_rule_type_date,dynamic_rule_type_date_add,event,fieldkey,dynamic_name_date);
    

    var rule_type_time         = '.checkout_field_dynamic_rule_time';

    var dynamic_rule_type_time_add = 'checkout_field_dynamic_rule_time_'+event.mnindex+'_'+fieldkey+'';

    var dynamic_rule_type_time = '.checkout_field_dynamic_rule_time_'+event.mnindex+'_'+fieldkey+'';

    var dynamic_name_time      = 'pcfme_'+event.mntype+'_settings['+fieldkey+'][dynamic_rules]['+event.mnindex+'][rule_type_time]';

    trigger_dynamic_rule_change_pfcme(this_element,checkout_tr_,conditional_row_,rule_type_time,dynamic_rule_type_time,dynamic_rule_type_time_add,event,fieldkey,dynamic_name_time);
    
    
     var rule_type_date_time         = '.checkout_field_dynamic_rule_date_time';

     var dynamic_rule_type_date_time_add = 'checkout_field_dynamic_rule_date_tim_'+event.mnindex+'_'+fieldkey+'';

     var dynamic_rule_type_date_time = '.checkout_field_dynamic_rule_date_tim_'+event.mnindex+'_'+fieldkey+'';

     var dynamic_name_date_time      = 'pcfme_'+event.mntype+'_settings['+fieldkey+'][dynamic_rules]['+event.mnindex+'][rule_type_date_time]';

     trigger_dynamic_rule_change_pfcme(this_element,checkout_tr_,conditional_row_,rule_type_date_time,dynamic_rule_type_date_time,dynamic_rule_type_date_time_add,event,fieldkey,dynamic_name_date_time);
    
    $(this).parents(checkout_tr_).find(conditional_row_).find('.checkout_field_dynamic_rule_type').on("change",function(event){

    	event.preventDefault();

    	var type_val = $(this).val();

    	var element = $(this);
    
        pcfme_change_type_val_toggle(type_val,element);

    	return false;

    });



    $("div.dynamic_fields_div_wrapper ").sortable({
		handle: '.pcfme_sortable_tr_handle_dynamic',
		items: 'div.conditional-row'

	});
	

	event.mnindex++;

	$(this).attr("gtindex",event.mnindex);



	$('.pcfme-remove-condition-dynamic').on('click',function(){

		$(this).parents('.conditional-row').remove();


	});


});

$('.pcfme-remove-condition-dynamic').on('click',function(){

		$(this).parents('.conditional-row').remove();


});


$('.add-condition-button').on('click',function(event){

	event.preventDefault();

	event.mnindex       = $(this).attr("mnindex");

	var fieldkey        = $(this).attr("keyno");

	var html            = '';

	event.mntype        = $(this).attr("mntype");



	if (event.mntype) {

		switch(event.mntype) {

		case "billing":
			var selecthtml = pcfmeadmin.billing_select;
			break;

		case "shipping":
			var selecthtml = pcfmeadmin.shipping_select;
			break;

		case "additional":
			var selecthtml = pcfmeadmin.additional_select;
			break;
		}
	}

	var showtext   = pcfmeadmin.showtext;
	var hidetext   = pcfmeadmin.hidetext;
	
	var valuetext  = pcfmeadmin.valuetext
	var equaltext  = pcfmeadmin.equaltext;

	var select1  = '<select class="checkout_field_conditional_showhide" name="pcfme_'+event.mntype+'_settings['+fieldkey+'][conditional]['+event.mnindex+'][showhide]"><option value="open" selected="">'+showtext+'</option><option value="hide">'+hidetext+'</option></select>';
	var span1    = '<span class="pcfmeformfield_first pcfmeformfield_first_'+event.mnindex+'_'+fieldkey+'">'+valuetext+'</span>';
	var span2    = '<span class="pcfmeformfield pcfmeformfield_equal_to pcfmeformfield_'+event.mnindex+'_'+fieldkey+'">'+equaltext+'</span>';

	var input1   = '<input type="text" class="checkout_field_conditional_equalto" name="pcfme_'+event.mntype+'_settings['+fieldkey+'][conditional]['+event.mnindex+'][equalto]" value="">&nbsp;&nbsp;';
	var select2  = '<select style="display:none;" class="checkout_field_conditional_select" name="pcfme_'+event.mntype+'_settings['+fieldkey+'][conditional]['+event.mnindex+'][equalto2]"></select>';
	
	var s_hid_type  = '<input type="hidden" style="display:none;" class="conditional_select_hidden_type" name="pcfme_'+event.mntype+'_settings['+fieldkey+'][conditional]['+event.mnindex+'][hidden_type]" value="text">';
	var s_hid_type2  = '<input type="hidden" style="display:none;" class="conditional_select_hidden_array" name="pcfme_'+event.mntype+'_settings['+fieldkey+'][conditional]['+event.mnindex+'][hidden_array]" value="">';

	var deletei  = '<span class="dashicons dashicons-remove pcfme-remove-condition"></span>';

	html        += '<div class="conditional-row conditional_row_'+event.mnindex+'_'+fieldkey+'">'+select1+''+span1+''+selecthtml+''+span2+''+s_hid_type+''+s_hid_type2+''+input1+''+select2+''+deletei+'</div>';






	$(this).parents('.checkout_field_conditional_tr_new').find('.conditional_fields_div_wrapper').append(html);

	$(this).parents('.checkout_field_conditional_tr_new').find('.checkout_field_conditional_showhide').select2({
		width: "200px",
		minimumResultsForSearch: -1
	});


	$(this).parents('.checkout_field_conditional_tr_new').find('.checkout_field_conditional_showhide').on("change",function(){
		
	});



	$(this).parents('.checkout_field_conditional_tr_new').find('.checkout_field_conditional_parentfield option[value='+fieldkey+']').remove();

	$(this).parents('.checkout_field_conditional_tr_new').find('.checkout_field_conditional_parentfield').attr('name','pcfme_'+event.mntype+'_settings['+fieldkey+'][conditional]['+event.mnindex+'][parentfield]');



	$(this).parents('.checkout_field_conditional_tr_new').find('.checkout_field_conditional_parentfield').attr("mtype",event.mntype);
	$(this).parents('.checkout_field_conditional_tr_new').find('.checkout_field_conditional_parentfield').attr("mntext",event.mnindex);
	$(this).parents('.checkout_field_conditional_tr_new').find('.checkout_field_conditional_parentfield').attr("mnkey",fieldkey);

	$(this).parents('.checkout_field_conditional_tr_new').find('.checkout_field_conditional_parentfield').select2({width: "250px" });

	event.mnindex++;

	$(this).attr("mnindex",event.mnindex);


	$(this).parents('.checkout_field_conditional_tr_new').find('.checkout_field_conditional_parentfield').on('change',function(zevent){

		zevent.preventDefault();

		

		var this_val   = $(this).val();
		var mtype4     = $(this).attr("mtype");
		var mntext     = $(this).attr("mntext");
		var mfield     = $(this).attr("mnkey");

		
		

		$.ajax({
			data: {
				action    : "pcfme_get_child_field_options",
				mtype     : mtype4


			},
			type: 'POST',
			url: ajaxurl,
			success: function( response ) { 

				

				var child_field_type = "text";



				var tresponse = JSON.parse(response);

				
				var field_data = tresponse.field_data;
				var child_field = field_data[this_val];


				var child_field_type = child_field['type'];

				


				var affected_field = $('.conditional_row_'+mntext+'_'+mfield+'');


				affected_field.find('.checkout_field_conditional_equalto').show();
				affected_field.find('.pcfmeformfield_equal_to').show();

				affected_field.find('.checkout_field_conditional_select').hide();
				affected_field.find('.pcfmeformfield_equal_to').text(pcfmeadmin.equaltext);



				if ((child_field_type == "paragraph") || (child_field_type == "heading")  || (child_field_type == "checkbox")) {
					affected_field.find('.checkout_field_conditional_equalto').hide();

					if (child_field_type == "checkbox") {
						console.log(child_field_type);
						affected_field.find('.pcfmeformfield_equal_to').text(pcfmeadmin.is_checked);
						affected_field.find('.conditional_select_hidden_type').val("checkbox");
					} else {
						affected_field.find('.pcfmeformfield_equal_to').hide();
					}

				//console.log(1);

				} else if ((child_field_type == "pcfmeselect") || (child_field_type == "multiselect")  || (child_field_type == "radio")) {

					

					const options_array = child_field['new_options']; 

					affected_field.find('.conditional_select_hidden_type').val("select");
					

					affected_field.find('.checkout_field_conditional_select').empty();



					$.each(options_array, function (i, item) {



						var newState = new Option(item['text'], item['value'], true, true);

						affected_field.find('.checkout_field_conditional_select').append(newState).trigger("change");     
					});


					affected_field.find('.checkout_field_conditional_equalto').hide();

					affected_field.find('.checkout_field_conditional_select').show();

				//console.log(child_field_type);

				//console.log(2);

				} else if (child_field_type == "text") {

					affected_field.find('.checkout_field_conditional_equalto').show();
					affected_field.find('.pcfmeformfield_equal_to').show();

					affected_field.find('.checkout_field_conditional_select').hide();
					affected_field.find('.conditional_select_hidden_type').val("text");

					affected_field.find('.pcfmeformfield_equal_to').text(pcfmeadmin.equaltext);


				} else {

					affected_field.find('.checkout_field_conditional_equalto').show();
					affected_field.find('.pcfmeformfield_equal_to').show();

					affected_field.find('.checkout_field_conditional_select').hide();
					affected_field.find('.conditional_select_hidden_type').val("text");
					affected_field.find('.pcfmeformfield_equal_to').text(pcfmeadmin.equaltext);


				}


			}
		});



		return false;

	});


	$('.pcfme-remove-condition').on('click',function(){

		$(this).parents('.conditional-row').remove();


	});
});


$("a.browse-fields").on("click",function (event) {
	event.preventDefault();
	var mnkey = $(this).attr('mnkey');

	$('#pcfme_example_modal').modal('show');

	$("#pcfme_modal_popup_select_button").attr('mnkey',mnkey);


	return false;
});

$("#wcmamtx_add_field").on("click",function (event) {
	event.preventDefault();

	var mnkey = $(this).data('etype');

	$("#pcfme_hidden_field_section").val(mnkey);

	$('#pcfme_hidden_field_type').val("text");

	$('#pcfme_example_modal2').modal('show');

	return false;
});




$("#pcfme_modal_popup_select_button").on("click",function (event) {

	event.preventDefault();

	var mnkey = $(this).attr('mnkey');
	var selected_field = $(this).attr("mselected");

	

	$('#checkout_field_type_'+mnkey+'').val(""+selected_field+"").change();

	$('#pcfme_example_modal').modal('hide');

	$("#pcfme_modal_popup_select_button").attr('mnkey',"");


	return false;
});


$('.pcfme-field-type').on("click",function (event) {

	event.preventDefault();

	var field_type = $(this).data('field-type');
	
	

	$("#pcfme_modal_popup_select_button").attr('mselected',field_type);

	return false;
});


$('.pcfme-field-type-new').on("click",function (event) {

	event.preventDefault();

	var field_type = $(this).data('field-type');

	$('.pcfme-field-type-new').removeClass("pcfme_active");

	$(this).addClass('pcfme_active');
	
	$('#pcfme_hidden_field_type').val(field_type);
	
	return false;
});


$('.pcfme-remove-condition').on('click',function(){

	$(this).parents('.conditional-row').remove();
	

});




$('.thankyou_fields_location,.datepicker_disable_days,.datepicker_format').select2({ 
	minimumResultsForSearch: -1,
	width:"420px" 
});

$('.timepicker_interval,.week_starts_on_class,.dt_week_starts_on_class').select2({ 
	minimumResultsForSearch: -1,
	width:"250px" 
});

$('.datetimepicker_lang_class').select2({
	width:"250px" 
});


$('.checkout_field_weekday,.checkout_field_monthdays,.checkout_field_months').select2({
	width:"250px" 
});


$('.checkout_field_rule_showhide').select2({
	width:"100px" ,
	minimumResultsForSearch: -1
});

$('.checkout_field_conditional_parentfield').select2({
	width:"250px" 
});

$('.pcfme-remove-rule').on('click',function(){

	var result = confirm(pcfmeadmin.removealert);
	if (result==true) {

		$(this).parents('.conditional-row').remove();
		fieldkey--;
		$('.add-fees-button').attr("mnindex",fieldkey);

	}  
});


$(".checkout_field_conditional_equalto").on('change', function() {
	var value = $(this).val();
	$(this).val(value.replace(/ /g, '_'));
});


$('.pcfme_label_input').keyup(function(event_child){

	event_child.preventDefault();

	var clkey = $(this).attr("clkey");

	var changed_value = $(this).val();
	
	$('.pcfme_field_heading_lable_'+clkey+'').text(changed_value);

	return false;
});





$(".pcfme_duplicate_field").on('click', function() {
	var pkey = $(this).attr("pkey");

	var mslug = $(this).attr("mslug");

	var oldrowno = $(this).attr("rowno");

	var rowno = $(this).attr("rowno");

	rowno++;

	var field_to_clone=  $(this).parents('.panel').get(0);

	var clone_to_be_html = $(field_to_clone).clone();



	clone_to_be_html.find("span.select2").remove();

	clone_to_be_html.find("select").select2({
		width:"250px"
	});

	var cloned_field = clone_to_be_html.insertAfter(field_to_clone).fadeIn();
	
	cloned_field.find('.pcfme_duplicate_field').remove();
	
	cloned_field.find('.pcfme_edit_icon_a').attr("href",'#pcfme'+rowno+'');

	cloned_field.find('.panel-collapse').attr("id",'customize-my-account-pro'+rowno+'');

	
	
	var noticerownorand = Math.floor(Math.random()*1000);

	switch(mslug) {

	case "pcfme_billing_settings":
		var headlingtext  ='billing_field_'+noticerownorand+'';

		break;

	case "pcfme_shipping_settings":
		var headlingtext ='shipping_field_'+noticerownorand+'';
		
		break;

	case "pcfme_additional_settings":
		var headlingtext ='additional_field_'+noticerownorand+'';
		
		break;


	}

	

	cloned_field.find('input').each(function() {
		this.name= this.name.replace('['+pkey+']', '['+headlingtext+']');
	});

	

	cloned_field.find('input.pcfme_change_key_input').val(headlingtext);

	

	var old_label = cloned_field.find('input.pcfme_label_input').val();

	cloned_field.find('input.pcfme_label_input').val(''+old_label+' ('+pcfmeadmin.copy_text+')');

	cloned_field.find('.pcfme_field_heading_lable').text(''+old_label+' ('+pcfmeadmin.copy_text+')');

	cloned_field.find('.pcfme_label_input').keyup(function(event_child){

		event_child.preventDefault();
		
		var changed_value = $(this).val();
		
		
		cloned_field.find('.pcfme_field_heading_lable').text(changed_value);

		return false;
	});


	cloned_field.find('.add-option-button').hide();

	cloned_field.find('.add-condition-button').hide();

	cloned_field.find('.checkout_field_visibility').on('change',function(){
		var visibilityvalue2 = $(this).val();
		if (visibilityvalue2 == "product-specific") {
			$(this).parents('table:eq(0)').find('.checkout_field_category_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_conditional_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_role_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_total_quantity_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_products_tr').show();
			$(this).parents('table:eq(0)').find('.checkout_field_shipping_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_payment_tr').hide();

		} else if (visibilityvalue2 == "category-specific") {
			$(this).parents('table:eq(0)').find('.checkout_field_products_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_conditional_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_role_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_total_quantity_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_category_tr').show();
			$(this).parents('table:eq(0)').find('.checkout_field_shipping_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_payment_tr').hide();
		} else if (visibilityvalue2 == "field-specific") {
			$(this).parents('table:eq(0)').find('.checkout_field_products_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_category_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_role_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_total_quantity_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_cart_quantity_specific_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_conditional_tr').show();
			$(this).parents('table:eq(0)').find('.checkout_field_shipping_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_payment_tr').hide();
			
		} else if (visibilityvalue2 == "role-specific") {
			$(this).parents('table:eq(0)').find('.checkout_field_products_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_category_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_conditional_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_total_quantity_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_cart_quantity_specific_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_role_tr').show();
			$(this).parents('table:eq(0)').find('.checkout_field_shipping_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_payment_tr').hide();
			
		} else if (visibilityvalue2 == "shipping-specific") {
			$(this).parents('table:eq(0)').find('.checkout_field_products_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_category_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_conditional_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_total_quantity_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_cart_quantity_specific_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_role_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_shipping_tr').show();
			$(this).parents('table:eq(0)').find('.checkout_field_payment_tr').hide();
			
		} else if (visibilityvalue2 == "payment-specific") {
			$(this).parents('table:eq(0)').find('.checkout_field_products_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_category_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_conditional_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_total_quantity_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_cart_quantity_specific_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_role_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_shipping_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_payment_tr').show();
			
		} else if (visibilityvalue2 == "total-quantity") {
			$(this).parents('table:eq(0)').find('.checkout_field_category_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_products_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_conditional_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_role_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_cart_quantity_specific_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_total_quantity_tr').show();
			$(this).parents('table:eq(0)').find('.checkout_field_shipping_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_payment_tr').hide();
			
		} else if (visibilityvalue2 == "cart-quantity-specific") {
			$(this).parents('table:eq(0)').find('.checkout_field_category_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_products_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_conditional_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_role_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_total_quantity_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_cart_quantity_specific_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_cart_quantity_specific_tr').show();
			$(this).parents('table:eq(0)').find('.checkout_field_shipping_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_payment_tr').hide();
		} else {
			$(this).parents('table:eq(0)').find('.checkout_field_category_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_products_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_conditional_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_role_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_total_quantity_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_cart_quantity_specific_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_shipping_tr').hide();
			$(this).parents('table:eq(0)').find('.checkout_field_payment_tr').hide();
		}
	});






$(this).attr("rowno",rowno);

});

});  

function pcfme_copyToClipboard(element) {
	var $temp = jQuery("<input>");
	jQuery("body").append($temp);
	var clipboard_text = jQuery(element).attr("cpkey");

	$temp.val(clipboard_text).select();
	document.execCommand("copy");
	$temp.remove();

	alert(pcfmeadmin.copiedalert);
}

function trigger_dynamic_rule_change_pfcme(this_element,checkout_tr_,conditional_row_,rule_type_,dynamic_rule_type_,dynamic_rule_type_add,event,fieldkey,dynamic_name_) {


	this_element.parents(checkout_tr_).find(conditional_row_).find(rule_type_).addClass(dynamic_rule_type_add);

   

	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr('name',dynamic_name_);



	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr("mtype",event.mntype);
	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr("mntext",event.mnindex);
	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr("mnkey",fieldkey);



}

function trigger_dynamic_rule_change_pfcme_from_to(this_element,checkout_tr_,conditional_row_,rule_type_,dynamic_rule_type_,dynamic_rule_type_add,event,fieldkey,dynamic_name_) {


	this_element.parents(checkout_tr_).find(conditional_row_).find(rule_type_).addClass(dynamic_rule_type_add);

   

	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr('name',dynamic_name_);



	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr("mtype",event.mntype);
	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr("mntext",event.mnindex);
	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr("mnkey",fieldkey);



}


function trigger_dynamic_rule_change_pfcme_coupons(this_element,checkout_tr_,conditional_row_,rule_type_,dynamic_rule_type_,dynamic_rule_type_add,event,fieldkey,dynamic_name_) {


	this_element.parents(checkout_tr_).find(conditional_row_).find(rule_type_).addClass(dynamic_rule_type_add);

   

	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr('name',dynamic_name_);
    

    this_element.parents(checkout_tr_).find(dynamic_rule_type_).select2({

	ajax: {
    			url: ajaxurl, // AJAX URL is predefined in WordPress admin
    			dataType: 'json',
    			delay: 250, // delay in ms while typing when to perform a AJAX search
    			data: function (params) {
    				return {
        				q: params.term, // search query
        				action: 'pdfmegetajaxcouponslist' // AJAX action for admin-ajax.php
        			};
        		},
        		processResults: function( data ) {

        			var options = [];
        			if ( data ) {

					// data is the array of arrays, and each of them contains ID and the Label of the option
					jQuery.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
						options.push( { id: text[0], text: text[1]  } );
					});

				}
				return {
					results: options
				};
			},
			cache: true
		},
		minimumInputLength: 3 ,
		minimumResultsForSearch: -1,
	    width: "300px"// the minimum of symbols to input before perform a search
    });


    this_element.parents(checkout_tr_).find(dynamic_rule_type_).next('span.select2-container').css("display","inline-grid");

	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr("mtype",event.mntype);
	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr("mntext",event.mnindex);
	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr("mnkey",fieldkey);

}


function pfcme_multiselect_normal(this_element,checkout_tr_,conditional_row_,rule_type_,dynamic_rule_type_,dynamic_rule_type_add,event,fieldkey,dynamic_name_) {


	this_element.parents(checkout_tr_).find(conditional_row_).find(rule_type_).addClass(dynamic_rule_type_add);

   

	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr('name',dynamic_name_);
    

    this_element.parents(checkout_tr_).find(dynamic_rule_type_).select2({
		minimumResultsForSearch: -1,
	    width: "300px"// the minimum of symbols to input before perform a search
    });


    this_element.parents(checkout_tr_).find(dynamic_rule_type_).next('span.select2-container').css("display","inline-grid");

	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr("mtype",event.mntype);
	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr("mntext",event.mnindex);
	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr("mnkey",fieldkey);

}

function trigger_dynamic_rule_change_pfcme_multiselect(this_element,checkout_tr_,conditional_row_,rule_type_,dynamic_rule_type_,dynamic_rule_type_add,event,fieldkey,dynamic_name_) {


	this_element.parents(checkout_tr_).find(conditional_row_).find(rule_type_).addClass(dynamic_rule_type_add);

   

	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr('name',dynamic_name_);
    

    this_element.parents(checkout_tr_).find(dynamic_rule_type_).select2({

	ajax: {
    			url: ajaxurl, // AJAX URL is predefined in WordPress admin
    			dataType: 'json',
    			delay: 250, // delay in ms while typing when to perform a AJAX search
    			data: function (params) {
    				return {
        				q: params.term, // search query
        				action: 'pdfmegetajaxproductslist' // AJAX action for admin-ajax.php
        			};
        		},
        		processResults: function( data ) {

        			var options = [];
        			if ( data ) {

					// data is the array of arrays, and each of them contains ID and the Label of the option
					jQuery.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
						options.push( { id: text[0], text: text[1]  } );
					});

				}
				return {
					results: options
				};
			},
			cache: true
		},
		minimumInputLength: 3 ,
		minimumResultsForSearch: -1,
	    width: "300px"// the minimum of symbols to input before perform a search
    });


    this_element.parents(checkout_tr_).find(dynamic_rule_type_).next('span.select2-container').css("display","inline-grid");

	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr("mtype",event.mntype);
	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr("mntext",event.mnindex);
	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr("mnkey",fieldkey);

}

function trigger_dynamic_rule_change_pfcme_category(this_element,checkout_tr_,conditional_row_,rule_type_,dynamic_rule_type_,dynamic_rule_type_add,event,fieldkey,dynamic_name_) {


	this_element.parents(checkout_tr_).find(conditional_row_).find(rule_type_).addClass(dynamic_rule_type_add);

   

	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr('name',dynamic_name_);
    

    this_element.parents(checkout_tr_).find(dynamic_rule_type_).select2({

	    width: "300px"// the minimum of symbols to input before perform a search
    });

    var select2_id = this_element.parents(checkout_tr_).find(dynamic_rule_type_).data("select2-id");


    this_element.parents(checkout_tr_).find(dynamic_rule_type_).next('span.select2-container').css("display","inline-grid");

	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr("mtype",event.mntype);
	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr("mntext",event.mnindex);
	this_element.parents(checkout_tr_).find(dynamic_rule_type_).attr("mnkey",fieldkey);

}