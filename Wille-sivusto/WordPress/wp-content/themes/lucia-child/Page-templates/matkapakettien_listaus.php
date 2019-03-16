<?php /* Template Name: Matkapakettien listaus */ ?>
<?php get_header(); ?>
<?php echo wp_kses_post(apply_filters('lucia_page_title_bar','','category')); ?> 
<?php lucia_container_before_page( 'blog_archives_sidebar_layout' ); ?>

<?php 

// valinta katsotaa "valinta" postin mukaan jos tyhjä asetetaan oletukseksi yleinen 
$valinta = $_GET['valinta'];

if(!isset($_GET['valinta'])){
    $valinta = 'yksityinen';
}

// hakee hakusanan hakukentästä ja tallentaa sen hkentta-muuttujaan
$hkentta = $_GET['hakukentta'];
$hakusana = $hkentta;

?>

<div class="col-main">
    <section class="post-main" role="main" id="content">
        <?php do_action('lqt_before_page_content'); ?>

        <div class="<?php echo esc_attr(lucia_get_blog_style()); ?>">

            <h1 style="text-align: center;">Matkapaketit</h1>

            <!-- valinta kumpia valintoja tarkastellaan -->
            <form action="<?php $_SERVER['REQUEST_URI'] ?>" method="get">
            <input class="archive-btn btn-left" name="valinta" type="submit" value="yksityinen" text="Omat tehtävät"/>
            <input class="archive-btn btn-right" name="valinta" type="submit" value="yleinen" text="Yleiset tehtävät" />
            </form>
            <?php
            echo'
            <form  action="http://'.$_SERVER['HTTP_HOST'].'/index.php/matkapaketin-lisays/">
            <input type="submit" value="Lisää matkapaketti" />
            </form>
            '
            ?>
            <!-- hakulomake -->
            <form style="float: left; width: 40%;" action="<?php $_SERVER['REQUEST_URI'] ?>" method="get">
                <h2>Haku</h2>
                <input type="hidden" name="valinta" placeholder="hae matkapaketteja" autocomplete="off" value="<?php echo $valinta;  ?>"/>
                <input type="text" name="hakukentta" placeholder="hae matkapaketteja" autocomplete="off" value=""/>
                <input type="submit" name="submit"value="Hae"/>
            </form>

            <div style="float: left; width: 60%;">

                <div>
                    <div>
                    <form action="http://192.168.9.200/index.php/matkapaketit-testi/">
                    <input type="submit" name="submit" value="X"/>
                    </form>
                    </div>
                </div>
            </div>
            <h4 style="visibility: hidden;" >easteregg ;)</h4>
            <hr>

            
            <!-- matkapakettien haku -->
            <?php 

                // tarkastaa oliko hakukenttä tyhjä ja suorittaa hakufunktion sen perusteella
                if (empty($hkentta)) {    
                    //hakukenttä oli tyhjä; suorita matkapakettifunktio
                    matkapaketti($hkentta, $valinta);

                } else {
                    //hakukenttä ei ollut tyhjä; suorita matkapakettiHakufunktio
                    matkapakettiHaku($hkentta , $valinta);
                    
                    
                }

                // funktio matkapakettien näyttämiseen mikäli hakukenttä oli tyhjä
                function matkapaketti($hkentta , $valinta) {



                    if ($valinta == 'yksityinen'){


                        $current_user = wp_get_current_user();
                    
                                //echo get_current_user($user);
                               // var_dump($user);
                    
                               $user_id=$current_user->ID;
                               
                               $luokkaargs=[
                                'post_type'=>'luokka',
                                'posts_per_page' => -1
                             ];
                    
                             $the_query = new WP_Query($luokkaargs);
                    
                             // loopataan löytyneet luokat läpi
                    
                             if ( $the_query->have_posts() ) {
                    
                                 while ( $the_query->have_posts() ) {
                    
                                      $the_query->the_post();
                    
                                      // tallennetaan kaikki käyttäjät, jotka kuuluvat samaan luokkaan kuin sisään kirjautunut käyttäjä
                                      $post_id = get_the_ID();
                    
                                      $users_array = get_field('kayttajat',$post_id);
                                      if(array_search($user_id, $users_array) !== FALSE ){
                                        $haettavatkayttajat = $users_array;
                                      
                    
                                    }
                                 }
                    
                                 /* Restore original Post Data */
                    
                                 wp_reset_postdata();
                    
                             }else {
                                 
                    
                                  echo 'ei luokkia';
                    
                              }
                    
                            
                    
                    $args = array(
                        'post_type' => 'matkapaketti',
                        'tax_query' => array(
                            array(
                                'taxonomy' 	=> 'tyyppi',
                                'field' 	=> 'slug',
                                'terms' 	=> $valinta,
                    
                            )
                            
                            ),
                            'author__in' => $haettavatkayttajat,
                    );
                    
                    
                    
                    
                    
                    
                    
                    }
                    else {
                        $args = array(
                            'post_type' => 'matkapaketti',
                            'tax_query' => array(
                                array(
                                    'taxonomy' 	=> 'tyyppi',
                                    'field' 	=> 'slug',
                                    'terms' 	=> $valinta,
                                )
                            )
                        );
                    }
                    
                    // the query
                    $the_query = new WP_Query( $args );
                    if ( $the_query->have_posts() ) :
                    while ( $the_query->have_posts() ) : $the_query->the_post();
                    ?>
                    
                        <!-- tulostus ja sen muotoilu -->
                        <a class='m-paketti' href='<?php the_permalink(); ?>' >
                            <div class='matkapaketti-kortti' style='background-color:#9AD3D4; height:auto; border-radius:5px; margin-top: 30px; padding:10px; padding-bottom:50px;'>
                                <h2 class='matkapaketti-otsikko' style='text-align:center; padding-top:10px;'><?php the_title(); ?></h2>
                                <p style='color:#393D3F;'>
                                    <img src='<?php echo get_field('profilepicture') ;?>' style='width:200px; height:110px; object-fit:cover; float:left; margin:0px 10px 0px 10px; border-radius:5px;'>
                                    <?php echo get_field('kuvaus') ;?>
                                </p>
                                <p style='color:#393D3F;'><b>Hinta/hlö: <?php echo get_field('hinta_hlö'); ?> €</b></P>
                                <p style="color:#393D3F; margin-top: 10px; float:left;"><b><?php  echo get_the_date( $format, $post ); ?> </b></p>
                                <p style="color:#393D3F; margin: 10px; float:left;"><b><?php  echo get_the_time( $format, $post ); ?> </b></p>
                                <p style="color:#393D3F; margin: 10px; float:right;"><b>Julkaisija: <?php  echo get_the_author_meta( 'display_name',  $author_id ); ?></b></p>
                            </div>
                        </a>
                    
                    <?php
                    endwhile;
                    wp_reset_postdata();
                    else: 
                        echo "
                            <p><?php _e( 'Hakusanalla ei löytynyt matkapaketteja. :(' ); ?></p>
                            ";
                    endif;

                }

             ?> 
             <?php

            // funktio matkapakettien näyttämiseen mikäli hakukenttä sisälsi hakusanan
            function matkapakettiHaku($hkentta , $valinta) {


                if ($valinta == 'yksityinen'){


                    $current_user = wp_get_current_user();
                
                            //echo get_current_user($user);
                           // var_dump($user);
                
                           $user_id=$current_user->ID;
                           
                           $luokkaargs=[
                            'post_type'=>'luokka',
                            'posts_per_page' => -1
                         ];
                
                         $the_query = new WP_Query($luokkaargs);
                
                         // loopataan löytyneet luokat läpi
                
                         if ( $the_query->have_posts() ) {
                
                             while ( $the_query->have_posts() ) {
                
                                  $the_query->the_post();
                
                                  // tallennetaan kaikki käyttäjät, jotka kuuluvat samaan luokkaan kuin sisään kirjautunut käyttäjä
                                  $post_id = get_the_ID();
                
                                  $users_array = get_field('kayttajat',$post_id);
                                  if(array_search($user_id, $users_array) !== FALSE ){
                                    $haettavatkayttajat = $users_array;
                                  
                
                                }
                             }
                
                             /* Restore original Post Data */
                
                             wp_reset_postdata();
                
                         }else {
                             
                
                              echo 'ei luokkia';
                
                          }
                
                        
                
                $args = array(
                    'post_type' => 'matkapaketti',
                    'posts_per_page' => -1,
                     's' => $hkentta,
                     'tax_query' => array(
                        array(
                            'taxonomy' 	=> 'tyyppi',
                            'field' 	=> 'slug',
                            'terms' 	=> $valinta,
                
                        )
                        
                        ),
                        'author__in' => $haettavatkayttajat,
                );
                
                
                
                
                
                
                
                }else  {
                    $args = array(
                        'post_type' => 'matkapaketti',
                        'posts_per_page' => -1,
                        's' => $hkentta,
                        'tax_query' => array(
                            array(
                                'taxonomy' 	=> 'tyyppi',
                                'field' 	=> 'slug',
                                'terms' 	=> $valinta,
                    
                            )
                            
                            ),
                    );

                }


                // kysely ja silmukka

                $the_query = new WP_Query( $args );
                if ( $the_query->have_posts() ) :
                while ( $the_query->have_posts() ) : $the_query->the_post();
                ?>
                    <!-- tulostus ja sen muotoilu -->
                    <a class='m-paketti' href='<?php the_permalink(); ?>' >
                        <div class='matkapaketti-kortti' style='background-color:#9AD3D4; height:auto; border-radius:5px; margin-top: 30px; padding:10px; padding-bottom:50px;'>
                            <h2 class='matkapaketti-otsikko' style='text-align:center; padding-top:10px;'><?php the_title(); ?></h2>
                            <p style='color:#393D3F;'>
                                <img src='<?php echo get_field('profilepicture') ;?>' style='width:200px; height:110px; object-fit:cover; float:left; margin:0px 10px 0px 10px; border-radius:5px;'>
                                <?php echo get_field('kuvaus') ;?>
                            </p>
                            <p style='color:#393D3F;'><b>Hinta/hlö: <?php echo get_field('hinta_hlö'); ?></b></P>
                            <p style="color:#393D3F; margin-top: 10px; float:left;"><b><?php  echo get_the_date( $format, $post ); ?> </b></p>
                            <p style="color:#393D3F; margin: 10px; float:left;"><b><?php  echo get_the_time( $format, $post ); ?> </b></p>
                            <p style="color:#393D3F; margin: 10px; float:right;"><b>Julkaisija: <?php  echo get_the_author_meta( 'display_name', $author_id ); ?></b></p>
                        </div>
                    </a>
                
                <?php
                endwhile;
                wp_reset_postdata();
                else: 
                    echo "
                        <p><?php _e( 'Matkapaketteja ei vielä lisätty! :(' ); ?></p>
                        ";
                endif;
            }
            ?>
            
        </div>

        <?php lucia_get_post_attributes(); ?>
    </section>
</div>

<?php lucia_container_after_page( 'blog_archives_sidebar_layout', 'archives' );?>
<?php get_footer();