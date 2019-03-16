<?php

/*
  Plugin Name: Linkki
  Description: Kutsumis linkki
  Version: 1.0
  Author: Refil
 */


function invitation_link()
        {

           $current_user = wp_get_current_user();
            echo '
            <h3>Linkki, jolla voit kutsua muut vanhemmat</h3>
            ';
            //echo get_current_user($user);
           // var_dump($user);

           $user_id=$current_user->ID;

           // etsitään oikea luokka käyttäjän id: perusteella

         $args=[
            'post_type'=>'luokka',
            'posts_per_page' => -1
         ];

        // haetaan luokka

         $the_query = new WP_Query($args);

         // loopataan löytyneet luokat läpi

         if ( $the_query->have_posts() ) {

             while ( $the_query->have_posts() ) {

                  $the_query->the_post();

                  // tallennetaan kaikki käyttäjät, jotka kuuluvat samaan luokkaan kuin sisään kirjautunut käyttäjä
                  $post_id = get_the_ID();

                  $users_array = get_field('kayttajat',$post_id);


                      //suoritetaan haku
                      if(array_search($user_id, $users_array) !== FALSE ){
                          echo '<p><strong>' .get_field('kutsumislinkki',$post_id).'</strong></p>';
                        

                      }
                      //echo $users_array;
                      
             }

             /* Restore original Post Data */

             wp_reset_postdata();

         }else {
             

              echo 'ei luokkia';

          }

        }
    

// Register a new shortcode
add_shortcode( 'invitation_link', 'invitation_link_shortcode' );
 
// The callback function that will replace [book]
function invitation_link_shortcode() {
    ob_start();
    invitation_link();
    return ob_get_clean();
}


?>