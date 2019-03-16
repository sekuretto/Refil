<?php
/*
  Plugin Name: Custom Registration
  Description: Own registration
  Version: 1.0
  Author: Refil
 */

//vanhemman rekisteröitymislomake

function registration_form( $password, $email, $first_name, $last_name, $user ) {

    
    echo '

    <div>
    <ul class="progressbar">
          <li class="active">Oma tili</li>
          <li>Ryhmän tili</li>
          <li>Kutsu muut</li>
          <li>Valmis!</li>
    </ul>
    </div>
    <div>
    <h2>Rekisteröityminen</h2>
    <p>Rekisteröityäksesti sinun täytyy syöttää pyydetyt tiedot. Nyt luo ensin käyttäjä itsellesi, jonka jälkeen pääset syöttämään luokan tiedot ja kutsumaan muut vanhemmat mukaan.</p>
    <p><strong>*</strong> -merkityt kohdat ovat pakollisia!</p>
    </div>
    <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">


    <div>
    <label for="firstname">Etunimi</label>
    <input type="text" name="fname" value="' . ( isset( $_POST['fname']) ? $first_name : null ) . '">
    </div>

    <div>
    <label for="website">Sukunimi</label>
    <input type="text" name="lname" value="' . ( isset( $_POST['lname']) ? $last_name : null ) . '">
    </div>

    <div>
    <label for="email">Sähköposti <strong>*</strong></label>
    <input type="text" name="email" value="' . ( isset( $_POST['email']) ? $email : null ) . '">
    </div>
        
    <div>
    <label for="password">Salasana <strong>*</strong></label>
    <label class="passwd">Vähintään 5 merkkiä</label>
    <input type="password" name="password" value="' . ( isset( $_POST['password'] ) ? $password : null ) . '">
    </div>
    
    <div>
    <label for="password_confirm">Toista salasana <strong>*</strong></label>
    <input type="password" name="password_confirm" value="">
    </div> 
        
    <input type="submit" name="submit" value="Rekisteröidy" class="submit-button"/>
    </form>
    ';
        
}

//luokan "postin" luominen
function registration_form_class ( $luokan_nimi, $katuosoite, $paikkakunta, $ryhmakoko, $salasana, $password,
$email,
$first_name,
$last_name ) {
    
    echo '
    <div>
    <ul class="progressbar">
    <li class="active">Oma tili</li>
    <li class="active">Ryhmän tili</li>
    <li>Kutsu muut</li>
    <li>Valmis!</li>
    </ul>

    </div>


        <div>
        <h2>Rekisteröityminen</h2>
        <p>Oma tilisi on nyt luotu. Syötä ryhmän tiedot ja pääset kutsumaan muut mukaan.</p>
        <p><strong>*</strong> -merkityt kohdat ovat pakollisia</p>
        </div>

        <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
        
        <div>
        <label for="luokan_nimi">Ryhmän/luokan nimi <strong>*</strong> </label>
        <input type="text" name="luokan_nimi" value="' . ( isset( $_POST['luokan_nimi']) ? $luokan_nimi : null ) . '">
        </div>

        <div>
        <label for="firstname">Katuosoite <strong>*</strong></label>
        <input type="text" name="katuosoite" value="' . ( isset( $_POST['katuosoite']) ? $katuosoite : null ) . '">
        </div>

        <div>
        <label for="paikkakunta">Paikkakunta <strong>*</strong></label>
        <input type="text" name="paikkakunta" value="' . ( isset( $_POST['paikkakunta']) ? $paikkakunta : null ) . '">
        </div>

        <div>
        <label for="ryhmakoko">Ryhmän koko <strong>*</strong></label>
        <input type="number" name="ryhmakoko" value="' . ( isset( $_POST['ryhmakoko']) ? $ryhmakoko : null ) . '">
        </div>
            
        <div>
        <label for="password">Luokan salasana <strong>*</strong></label>
        <label class="passwd">Anna luokalle salasana, tämä salasana on kaikille yhteinen, joten ethän käytä samaa salasanaa kuin aiemmin.</label>
        <label class="passwd">Vähintään 5 merkkiä</label>
        <input type="password" name="salasana" value="' . ( isset( $_POST['salasana'] ) ? $salasana : null ) . '">
        </div>
        
        <div>
        <label for="password_confirm2">Toista salasana <strong>*</strong></label>
        <input type="password" name="password_confirm2" value="">
        </div> 


        <div class="hidden">
        <label for="firstname">Etunimi</label>
        <input type="text" name="fname" value="' . $first_name . '">
        </div>
    
        <div class="hidden">
        <label for="website">Sukunimi</label>
        <input type="text" name="lname" value="' . $last_name . '">
        </div>
    
        <div class="hidden">
        <label for="email">Sähköposti <strong>*</strong></label>
        <input type="text" name="email" value="' . $email . '">
        </div>
            
        <div class="hidden">
        <label for="password">Salasana <strong>*</strong></label>
        <label class="passwd">Vähintään 5 merkkiä</label>
        <input type="password" name="password" value="' . $password . '">
        </div>
        
            
        <input type="submit" name="submit-class" value="Rekisteröi ryhmä"/>



        </form>
        ';


}


// vanhemman rekisteröinti validaatio
function registration_validation( $password, $email, $first_name, $last_name )  {

    global $reg_errors;
    $reg_errors = new WP_Error;

    if ( empty( $password ) || empty( $email ) ) {
        $reg_errors->add('field', 'Kenttä tyhjä');
    }

    if ( 5 > strlen( $password ) ) {
        $reg_errors->add( 'password', 'Salasanan täytyy olla yli 5 merkkiä' );
    }

    if ( isset($_POST['password']) && $_POST['password'] != $_POST['password_confirm'] ) {
        $reg_errors->add( 'password_confirm', 'Salasanat eivät täsmää' );
    }

    if ( !is_email( $email ) ) {
        $reg_errors->add( 'email_invalid', 'Sähköposti ei kelpaa' );
    }

    if ( email_exists( $email ) ) {
        $reg_errors->add( 'email', 'Sähköposti on jo käytössä' );
    }


    if ( is_wp_error( $reg_errors ) ) {
 
        foreach ( $reg_errors->get_error_messages() as $error ) {
         
            echo '<div class="huom">';
            echo '<strong>Huom! </strong>:';
            echo $error . '<br/>';
            echo '</div>';
             
        }
     
    }

}

// luokan validaatio
function registration_validation_class( $luokan_nimi, $katuosoite, $paikkakunta, $ryhmakoko, $salasana )  {

    global $reg_errors_class;
    $reg_errors_class = new WP_Error;

    if ( empty( $luokan_nimi ) || empty( $katuosoite ) || empty($paikkakunta)|| empty($ryhmakoko)|| empty($salasana) ) {
        $reg_errors_class->add('field', 'Kenttä tyhjä');
    }
    if ( 5 > strlen( $_POST['salasana'] ) ) {
        $reg_errors_class->add( 'salasana', 'Salasanan täytyy olla yli 5 merkkiä' );
    }

    if ( isset($_POST['salasana']) && $_POST['salasana'] != $_POST['password_confirm2'] ) {
        $reg_errors_class->add( 'password_confirm2', 'Salasanat eivät täsmää' );
    }

    if ( is_wp_error( $reg_errors_class ) ) {
 
        foreach ( $reg_errors_class->get_error_messages() as $error ) {
         
            echo '<div class="huom">';
            echo '<strong>Huom! </strong>';
            echo $error . '<br/>';
            echo '</div>';
             
        }
     
    }
    
}

//Luodaan käyttäjä ja luokka-post
function complete_registration($password,$email,$first_name,$last_name,$luokan_nimi, $katuosoite, $paikkakunta, $ryhmakoko, $salasana) {

    // Globaali data talteen
    // Tarkistetaan errorit
       
        //jäsennetään käyttäjän data
        $userdata = array(
        'user_login'    =>   $email,
        'user_email'    =>   $email,
        'user_pass'     =>   $password,
        'first_name'    =>   $first_name,
        'last_name'     =>   $last_name,
        );

        //luodaan käyttäjä kantaan
        $user = wp_insert_user( $userdata );

        //jäsennetään uuden luokkapostauksen data
        $new_post_args = [
            'post_title'    => $luokan_nimi,
            'post_type'     => 'luokka',
            'post_status'   => 'publish',
            'post_author'   => $user
        ];

        //luodaan luokka-post
        $postID = wp_insert_post( $new_post_args );

        $linkki = 'http://'.$_SERVER['HTTP_HOST'].'/index.php/linkki-rekisteroityminen/?post_id='.$postID;

        //päivitetään luokan postia ACF-apin kautta
        update_field('katuosoite', $katuosoite, $postID);
        update_field('paikkakunta', $paikkakunta, $postID);
        update_field('ryhmakoko', $ryhmakoko, $postID);
        update_field('salasana', $salasana, $postID);
        update_field('kayttajat', $user, $postID);
        update_field('kutsumislinkki', $linkki, $postID);

        
        echo '

        <ul class="progressbar">
        <li class="active">Oma tuli</li>
        <li class="active">Ryhmän tili</li>
        <li class="active">Kutsu muut</li>
        <li>Valmis!</li>
        </ul>

        <div>
        <h2>Rekisteröityminen</h2>
        <p>Oma tilisi ja ryhmä tili on nyt luotu! Alla olevaa linkkiä jakamalla pääsevät muut vanhemmat liittymään palveluun. Huomioi, että muiden vanhempien ei enää tarvitse luoda ryhmä tiliä, joten pyydäthän heitä rekisteröitymään vain kyseisen linkin avulla. </p>
        <p>Jos et kerkiä jakamaan linkkiä nyt, saat sen myös Profiili-sivultasi.</p>
        </div>
        
        
        ';

        echo 'Kopioi tämä!<h2>'.$linkki.'</h2>';

        echo '
        <form action="http://'.$_SERVER['HTTP_HOST'].'/index.php/rekisteroityminen-valmis/" method="post">
        <input type="submit" name="complete" value="Valmis!"/>
        </form>
        ';
    
}




//ohjelman pääfunktio
function custom_registration_function() {

    //(jos painetaan luokan rekisteröitymistä) luokan rekisteröitymistä painaessa viedään tiedot validaatioon
    if( isset($_POST['submit-class'] ) ){
        registration_validation_class(
            $_POST['luokan_nimi'],
            $_POST['katuosoite'],
            $_POST['paikkakunta'],
            $_POST['ryhmakoko'],
            $_POST['salasana'],
            $_POST['password'],
            $_POST['email'],
            $_POST['fname'],
            $_POST['lname']
        );
        // jos luokan tiliä luodessa erroreita, ei jatketa, vaan palataan luokan formiin ja näytetään error messaget
        global $reg_errors_class;
        // (luokka) käytetään globaaleja muuttujia, puhdistetaan input ennen kantaan lähettämistä
        global  $luokan_nimi, $katuosoite, $paikkakunta, $ryhmakoko, $salasana, $password, $email, $first_name, $last_name;
        $luokan_nimi =   sanitize_text_field( $_POST['luokan_nimi'] );
        $salasana  =   esc_attr( $_POST['salasana'] );
        $katuosoite     =   sanitize_text_field( $_POST['katuosoite'] );
        $paikkakunta =   sanitize_text_field( $_POST['paikkakunta'] );
        $ryhmakoko  =   sanitize_text_field( $_POST['ryhmakoko'] );
        $password   =   esc_attr( $_POST['password'] );
        $email      =   sanitize_email( $_POST['email'] );
        $first_name =   sanitize_text_field( $_POST['fname'] );
        $last_name  =   sanitize_text_field( $_POST['lname'] );

        if( 0 < count( $reg_errors_class->get_error_messages() ) ) {
            return registration_form_class(
                $luokan_nimi, 
                $katuosoite, 
                $paikkakunta,  
                $ryhmakoko, 
                $salasana,
                $password,
                $email,
                $first_name,
                $last_name
            );
        }
        
        //viedään rekisteröityminen loppuun
        complete_registration(
            $password,
            $email,
            $first_name,
            $last_name,
            $luokan_nimi, 
            $katuosoite, 
            $paikkakunta, 
            $ryhmakoko, 
            $salasana
        );
    }

    //käyttäjän omien tietojen validaatioon vieminen
    elseif( isset($_POST['submit'] ) ) {
        registration_validation(
            $_POST['password'],
            $_POST['email'],
            $_POST['fname'],
            $_POST['lname']
        );
        // haetaan GLOBAALIT muuttujat
        global $reg_errors;
        global $password, $email, $first_name, $last_name;
        // (käyttäjä) käytetään globaaleja muuttujia, puhdistetaan input ennen kantaan lähettämistä
        $password   =   esc_attr( $_POST['password'] );
        $email      =   sanitize_email( $_POST['email'] );
        $first_name =   sanitize_text_field( $_POST['fname'] );
        $last_name  =   sanitize_text_field( $_POST['lname'] );
    
        // jos löytyy errori 
        if( 0 < count( $reg_errors->get_error_messages() ) ) {
            return registration_form(
                $password,
                $email,
                $first_name,
                $last_name,
                $user
            );
        }
        // näytetään luokan formi
        registration_form_class ( 
            $luokan_nimi, 
            $katuosoite, 
            $paikkakunta, 
            $ryhmakoko, 
            $salasana,
            $password, 
            $email, 
            $first_name, 
            $last_name
         );
        
    }
    // alkutilanne (käyttäjän luominen)
    else {
    registration_form(
        $password,
        $email,
        $first_name,
        $last_name,
        $user
        );
    }
}

// Register a new shortcode: [cr_custom_registration]
add_shortcode( 'cr_custom_registration', 'custom_registration_shortcode' );
 
// The callback function that will replace [book]
function custom_registration_shortcode() {
    ob_start();
    custom_registration_function();
    return ob_get_clean();
}