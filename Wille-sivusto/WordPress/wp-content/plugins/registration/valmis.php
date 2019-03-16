<?php

/*
  Plugin Name: Custom Registration
  Description: Own registration
  Version: 1.0
  Author: Refil
 */

function myfnc()
        {
            echo '<ul class="progressbar">
            <li class="active">Oma tuli</li>
            <li class="active">Ryhmän tili</li>
            <li class="active">Kutsu muut</li>
            <li class="active">Valmis!</li>
            </ul>' ;

            echo '
            <h2>Rekisteröityminen on nyt valmis!</h2>
            <p> Kirjaudu vielä sisään tilillesi, niin pääset palveluun!</p>
            ';
        }


// Register a new shortcode: [cr_custom_registration]
add_shortcode( 'cr_custom_registration_finalize', 'custom_registration_finalize_shortcode' );
 
// The callback function that will replace [book]
function custom_registration_finalize_shortcode() {
    ob_start();
    myfnc();
    return ob_get_clean();
}

?>