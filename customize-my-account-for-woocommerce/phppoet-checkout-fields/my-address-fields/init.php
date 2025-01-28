<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class pcfme_my_address_fields_class {

    public function __construct() {

    	add_action( 'woocommerce_locate_template', array($this,'pcfme_override_edit_address_template'), 100, 3 );

    }


    
    public function pcfme_override_edit_address_template($template,$template_name,$template_path) {

        


        if ( strstr($template, 'form-edit-address.php')) {
            $template = untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/form-edit-address.php';
        }


        
        return $template;

    }

}

new pcfme_my_address_fields_class();

?>