<?php

/*
  Plugin Name: Varat
  Description: Varojen mittaus
  Version: 0.9
  Author: Refil
 */


function varat(){
    echo '<script type="text/javascript"> 
                function lisaa() { 
                    var v1=document.getElementById("progressbar").value;
                    var v2=document.getElementById("prosentti").textContent;
                    var number = parseFloat(v2);
                    document.getElementById("progressbar").value= v1 + 10;
                    document.getElementById("prosentti").innerHTML = number + 10 + " %";
                }
                function poista() { 
                    var v1=document.getElementById("progressbar").value;
                    var v2=document.getElementById("prosentti").textContent;
                    var number = parseFloat(v2);
                    document.getElementById("progressbar").value= v1 - 10;
                    document.getElementById("prosentti").innerHTML = number - 10 + " %";
                }
            </script> 
            
            <h3 style="text-align:center;">Varat</h3>
            <label style="font-weight:bold; color:#1a535c; float:left;">Puistoretki 20%</label>
            <!--<label style="width:75%; text-align:right;">Kaupunkikierros 65%</label>-->
            <label style="font-weight:bold; color:#1a535c; float:right;">Huvipuistomatka 100%</label>
            <progress id="progressbar" max="100" value="50"></progress>
            <p id="prosentti" style="width:100%; text-align:center; color:#1a535c; font-weight: bold;">50</p>
            <br>
            <input type="button" value="Lisää varoja" onClick="lisaa();">
            <input type="button" value="Poista varoja" onClick="poista();">';

}
 


// Register a new shortcode: [cr_custom_link_registration]
add_shortcode( 'cr_varat', 'varat_shortcode' );
 
// The callback function that will replace [book]
function varat_shortcode() {
    ob_start();
    varat();
    return ob_get_clean();
}


?>

