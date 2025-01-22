<?php
    $additional_fees    = (array) get_option('pcfme_additional_fees');
    $additional_fees    = array_filter($additional_fees);
    $country_fields     = '';

    $billing_settings   = (array) get_option('pcfme_billing_settings');

    $shipping_settings   = (array) get_option('pcfme_shipping_settings');

    $additional_settings   = (array) get_option('pcfme_additional_settings');

    $merged_fields  = array();

    foreach ($billing_settings as $skey=>$pvalue) { 
         $merged_fields[$skey] = $pvalue;
    }

    foreach ($shipping_settings as $skey=>$pvalue) { 
         $merged_fields[$skey] = $pvalue;
    }

    foreach ($additional_settings as $skey=>$pvalue) { 
         $merged_fields[$skey] = $pvalue;
    }

?>
<table class="widefat pcfme-fees-table">
    <?php

        $numindex = 1;

        foreach ($additional_fees as $key=>$value) { 
            $numindex++;
            $mnindex = $numindex;
            
            
            $rule_type = isset($value['rule_type']) ? $value['rule_type'] : "01";
            

            
        	?>

        	

        	<tr class="conditional-row conditional-row-<?php echo $mnindex; ?>">
                <input type="hidden" name="pcfme_additional_fees[<?php echo $mnindex; ?>][rule_type]" value="<?php echo $rule_type; ?>">

                <?php 

                Switch($rule_type) {
                    case 01:
                       pcfme_show_rule_type_01_td_values($value,$mnindex);
                    break;

                    case 02:
                       pcfme_show_rule_type_02_td_values($value,$mnindex);
                    break;

                    case 03:
                       pcfme_show_rule_type_03_td_values($value,$mnindex);
                    break;

                    default:
                        pcfme_show_rule_type_01_td_values($value,$mnindex);
                    break;
                }

                ?>
        		<td>
        			<span class="pcfmeformfield">
        				<strong><?php echo esc_html__( 'If Value of' ,'customize-my-account-pro'); ?></strong>
        			</span>
        		</td>
                
        		<td>
        			<select class="checkout_field_conditional_parentfield" rowno="<?php echo $mnindex; ?>" name="pcfme_additional_fees[<?php echo $mnindex; ?>][parentfield]">
        				
        					
        				<optgroup label="<?php echo esc_html__( 'Billing Fields' ,'customize-my-account-pro'); ?>">
        					<?php
        					foreach ($billing_settings as $optionkey=>$optionvalue) { 



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
                            ?> 

        							<option value="<?php echo $optionkey; ?>" <?php if (isset($value['parentfield']) && ($value['parentfield'] == $optionkey)) { echo 'selected';} ?>>
        								<?php echo $optionlabel; ?>
        									
        							</option>

        							<?php
        							
        						
        					} 
        					?>
        				</optgroup>

        				<optgroup label="<?php echo esc_html__( 'Shipping Fields' ,'customize-my-account-pro'); ?>">
        					<?php
        					foreach ($shipping_settings as $optionkey=>$optionvalue) { 

        						if ( (isset ($optionvalue['type']) && ($optionvalue['type'] == 'email')) || (preg_match('/\b'.$optionkey.'\b/', $country_fields ))) { 

        						} else { 

        							if (isset($optionvalue['label']))  { 

        								$optionlabel = $optionvalue['label']; 

        							} else { 

        								$optionlabel = $optionkey; 
        							}
        							?> 

        							<option value="<?php echo $optionkey; ?>" <?php if (isset($value['parentfield']) && ($value['parentfield'] == $optionkey)) { echo 'selected';} ?>>
        								<?php echo $optionlabel; ?>
        									
        							</option>

        							<?php
        							
        						} 
        					} 
        					?>
        				</optgroup>

                        <?php

                        $additional_settings  = (array) get_option('pcfme_additional_settings');
                        $additional_settings  = array_filter($additional_settings);

                        if (isset($additional_settings) && (sizeof($additional_settings) >= 1)) { 
                            $conditional_fields_dropdown = $additional_settings;
                        } else {
                            $conditional_fields_dropdown = array();
                        }




                        if (count($conditional_fields_dropdown) != 0) { ?>

                                <optgroup label="<?php echo esc_html__( 'Additional Fields' ,'customize-my-account-pro'); ?>">

                        <?php 

                        


        					foreach ($conditional_fields_dropdown as $optionkey=>$optionvalue) { 

        						if ( (isset ($optionvalue['type']) && ($optionvalue['type'] == 'email')) || (preg_match('/\b'.$optionkey.'\b/', $country_fields ))) { 

        						} else { 

        							if (isset($optionvalue['label']))  { 

        								$optionlabel = $optionvalue['label']; 

        							} else { 

        								$optionlabel = $optionkey; 
        							}
        							?> 

        							<option value="<?php echo $optionkey; ?>" <?php if (isset($value['parentfield']) && ($value['parentfield'] == $optionkey)) { echo 'selected';} ?>>
        								<?php echo $optionlabel; ?>
        									
        							</option>

        							<?php
        							
        						} 
        					} 
        				

        				
                         ?>

                                </optgroup>

                        <?php } ?>
        				

        			</select>
                    <?php if (!isset($value['action_type']) ) {  ?>
                        <input type="text" class="checkout_field_conditional_custom_label" placeholder="<?php echo esc_html__( 'Custom Label' ,'customize-my-account-pro'); ?>" name="pcfme_additional_fees[<?php echo $mnindex; ?>][custom_label]" value="<?php if (isset($value['custom_label']) && ($value['custom_label'] != '' ) ) { echo $value['custom_label']; } ?>">
                    <?php }  ?>
        		</td>



        		
        		



               <?php

               $hidden_type = isset($value['hidden_type']) ? $value['hidden_type'] : "text";

               

               if ($hidden_type == "checkbox") {

                ?>
                <td>

                    <span class="pcfmeformfield pcfmeformfield_equal_to">
                        <strong><?php echo esc_html__('is checked','customize-my-account-pro'); ?></strong>
                    </span>
                </td>

                <?php

              } else {

                ?>
                <td>
                    <span class="pcfmeformfield pcfmeformfield_equal_to">
                        <strong><?php echo esc_html__('is equal to','customize-my-account-pro'); ?></strong>
                    </span>
                </td>

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




            ?>
            <td>

            <input style="display:none;" class="conditional_select_hidden_type conditional_select_hidden_type_<?php echo $mnindex; ?>" name="pcfme_additional_fees[<?php echo $mnindex; ?>][hidden_type]" value="<?php echo $hidden_type; ?>">

           

            <input type="text" style="<?php echo $input_style; ?>" class="checkout_field_conditional_equalto" name="pcfme_additional_fees[<?php echo $mnindex; ?>][equalto]" value="<?php if (isset($value['equalto'])) { echo $value['equalto']; } ?>">



            <select style="<?php echo $select_style; ?>" class="checkout_field_conditional_select" name="pcfme_additional_fees[<?php echo $mnindex; ?>][equalto]">
                <?php

                $hidden_array = $merged_fields[''.$value['parentfield'].'']['options'];

                $hidden_array_new = $merged_fields[''.$value['parentfield'].'']['new_options'];



                $old_options = isset($hidden_array) ? $hidden_array : '';

                $old_options = explode(',', $old_options);

                $old_options_array = array();
                



                if (isset($old_options) && !empty($old_options) && (sizeof($old_options) > 0)) {
                    $old_options_array_index = 1;
                    foreach($old_options as $ovalue) {
                        $old_options_array[''.$old_options_array_index.''] = array('value'=>$ovalue,'text'=>$ovalue);
                        $old_options_array_index++;
                    }
                }



                $new_options_array = isset($hidden_array_new) ? $hidden_array_new : $old_options_array;


                if ($hidden_type != "text") {
                    foreach ($new_options_array as $idkey=>$ldval) {



                        ?>

                        <option value="<?php echo $ldval['value']; ?>" <?php if (isset($value['equalto']) && ($value['equalto'] == $ldval['value'])) {echo 'selected';} ?>>
                            <?php echo $ldval['text']; ?>

                        </option>

                        <?php                                           
                    }
                }

                ?>
            </select>

            </td>

            <td>
             <span class="glyphicon glyphicon-trash pcfme-remove-rule"></span>
            </td>

        	</tr>


        <?php 

                $mnindex++;
            }

    ?>
    
</table>

<button type="button"  href="#" data-toggle="modal" data-target="#pcfme_example_modal3" mnindex="<?php if (isset($mnindex)) { echo $mnindex; } else { echo 1;} ?>" class="btn button-primary button-add_rule">
	<span class="dashicons dashicons-insert add_rule_icon"></span>
	<?php echo esc_html__( 'Add Fees/Discounts Rule' ,'customize-my-account-pro'); ?>
</button>

<?php 

$enable_actions = apply_filters('pcfme_enable_action_rules','no'); 

if (isset($enable_actions) && ($enable_actions == "yes")) {

?>
<button type="button" mnindex="<?php if (isset($mnindex)) { echo $mnindex; } else { echo 1;} ?>" class="btn button-primary add-action-button">
    <span class="dashicons dashicons-insert add_rule_icon"></span>
    <?php echo esc_html__( 'Add Action Rule' ,'customize-my-account-pro'); ?>
</button>
<?php } ?>