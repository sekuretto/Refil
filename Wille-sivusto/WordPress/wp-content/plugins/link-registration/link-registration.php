<?php

/*
  Plugin Name: Custom Link Registration
  Description: Rekisteröityminen linkin avulla
  Version: 0.9
  Author: Refil
 */


//vanhemman rekisteröitymislomake
function link_registration_form( $password, $email, $first_name, $last_name, $user ) {
   
    echo '

    <div>
    <ul class="progressbar">
          <li class="active">Tilin luominen</li>
          <li>Valmis!</li>
    </ul>
    </div>

    <div>
    <h2>Tervetuloa Willeen!</h2>
    <p>Joku ryhmästänne onkin jo luonut tilin ryhmälle. Sinun tarvitsee vain luoda itsellesi käyttäjä, jotta pääset mukaan seuraamaan keräystä.</p>
    </div>

    <div>
    <p><strong>*</strong> -merkityt kohdat ovat pakollisia</p>
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
        
    <input type="submit" name="submit" value="Rekisteröidy"/>
    </form>
    ';
    
        
}


// vanhemman rekisteröinti validaatio
function link_registration_validation( $password, $email, $first_name, $last_name )  {

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
            echo '<strong>Huom! </strong>';
            echo $error . '<br/>';
            echo '</div>';
             
        }
     
    }

}


//Luodaan käyttäjä ja luokka-post
function complete_link_registration($password, $email, $first_name, $last_name, $postID) {
   

    // Globaali data talteen
    global $reg_errors, $password, $email, $first_name, $last_name, $postID;

    // Tarkistetaan errorit
   // if ( 1 > count( $reg_errors->get_error_messages() ) ) {

        //jäsennetään käyttäjän data
        $userdata = array(
        'user_login'    =>   $email,
        'user_email'    =>   $email,
        'user_pass'     =>   $password,
        'first_name'    =>   $first_name,
        'last_name'     =>   $last_name,
        );

        //luodaan käyttäjä kantaan
        

        
        
        //$old_users = get_field('kayttajat', $postID);

       /* for($i=0;$i<$old_users.length();$i++){
            $users.push($old_users[$i]);
        }*/

        $old_users = get_field('kayttajat',$postID);
        $user = wp_insert_user( $userdata );
        array_push($old_users, $user);

        //var_dump(get_field('kayttajat',$postID));
        if(! is_wp_error($user) && $postID) {

            $meta=[
                'kayttajat'=>$old_users,
            ];

            $update_post_args=[

                'ID' => $postID,
                'post_type' => 'luokka',
                'meta_input' => $meta

            ];

            $updated_post = wp_update_post($update_post_args);

            

        }

        echo '
  
        <form action="http://'.$_SERVER['HTTP_HOST'].'/index.php/linkki-rekisteroityminen-valmis/" method="post" id="linkkiform">
        </form>
        <script type="text/javascript">
        document.getElementById("linkkiform").submit();
         </script>
        ';
                
       // $user = wp_insert_user( $userdata );
        //päivitetään luokan postia ACF-apin kautta
       // update_field('kayttajat', $user, $postID);
           
}

//ohjelman pääfunktio
function custom_link_registration_function() {


    if(!$_GET['post_id']){
        echo 'Moi mee <a href="http://192.168.9.200/index.php/testi-rekisteri/">tänne.</a>';
        return null;
    }

    
    //käyttäjän omien tietojen validaatioon vieminen
    if( isset($_POST['submit'] ) ) {
        link_registration_validation(
        $_POST['password'],
        $_POST['email'],
        $_POST['fname'],
        $_POST['lname']
        );

        global $reg_errors;

        
        
        // (käyttäjä) käytetään globaaleja muuttujia, puhdistetaan input ennen kantaan lähettämistä
        global $password, $email, $first_name, $last_name, $postID;
        $password   =   esc_attr( $_POST['password'] );
        $email      =   sanitize_email( $_POST['email'] );
        $first_name =   sanitize_text_field( $_POST['fname'] );
        $last_name  =   sanitize_text_field( $_POST['lname'] );
        $postID = sanitize_text_field( $_GET['post_id'] );

        if( 0 < count( $reg_errors->get_error_messages() ) ) {
            return link_registration_form(
                $password,
                $email,
                $first_name,
                $last_name,
                $user,
                $postID
                );
        }
        
        complete_link_registration(
            $password,
            $email,
            $first_name,
            $last_name,
            $postID
            );
           
        
    }
    
    else{
        link_registration_form(
            $password,
            $email,
            $first_name,
            $last_name,
            $user,
            $postID
            );
    }
    
}

// Register a new shortcode: [cr_custom_link_registration]
add_shortcode( 'cr_custom_link_registration', 'custom_link_registration_shortcode' );
 
// The callback function that will replace [book]
function custom_link_registration_shortcode() {
    ob_start();
    custom_link_registration_function();
    return ob_get_clean();
}
