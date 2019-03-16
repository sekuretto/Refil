<?php

/*
  Plugin Name: Custom Link Registration Ready
  Description: Rekisteröityminen linkin avulla valmis
  Version: 0.9
  Author: Refil
 */

function custom_link_registration_ready_function(){
    echo 'VALMIS';

}




// Register a new shortcode: [cr_custom_link_registration]
add_shortcode( 'cr_custom_link_registration_ready', 'custom_link_registration_ready_shortcode' );
 
// The callback function that will replace [book]
function custom_link_registration_ready_shortcode() {
    ob_start();
    custom_link_registration_ready_function();
    return ob_get_clean();
}


?>