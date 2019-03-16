<?php
/*
  Plugin Name: Kirjautunut käyttäjä
  Description: Kirjautuneen käyttäjän nimen näyttäminen
  Version: 1.0
  Author: Refil
 */


function sugned_in_user_function(){

    if ( is_user_logged_in() ) {
        $current_user = wp_get_current_user();
        echo '<p class="welcome">Tervetuloa ' . $current_user->display_name . "!</p>";
    } else {
        echo 'Welcome, visitor!';
    }
}

add_shortcode( 'signed_in_user', 'signed_in_user_shortcode' );
 
// The callback function that will replace [book]
function signed_in_user_shortcode() {
    ob_start();
    sugned_in_user_function();
    return ob_get_clean();
}

