<?php /* Template Name: Luokan sivu */ ?>

<?php get_header(); ?>
<?php echo wp_kses_post(apply_filters('lucia_page_title_bar','','category')); ?> 
<?php lucia_container_before_page( 'blog_archives_sidebar_layout' ); ?>

<div class="col-main">
    <section class="post-main" role="main" id="content">
        <?php do_action('lqt_before_page_content'); ?>

        <div class="<?php echo esc_attr(lucia_get_blog_style()); ?>">

            <!-- estää lomakkeen uudelleen lähettämisen -->
            <script>
                if ( window.history.replaceState ) {
                    window.history.replaceState( null, null, window.location.href );
                }
            </script>

            <h1 style="text-align: center;">Luokan tiedot</h1>

            <?php

                $current_user = wp_get_current_user();
                $kayttaja_nimi = $current_user->display_name;
   
            // TULOT JA MENOT LOMAKEFUNKTIO
            function tulot_menot_lomake($viesti, $maara) {
                echo '
                <form action="' . $_SERVER['REQUEST_URI'] . '" method="post" autocomplete="off">
                    <h3>Tulojen/menojen lisäys</h3>
                    <h4>Viesti</h4>
                    <input type="text" name="viesti"  value="' . ( isset( $_POST['viesti']) ? $viesti : null ) . '" required>
                    <h4>Tulot/Menot, €</h4>
                    <input type="number" name="maara"  step="any" value="' . ( isset( $_POST['maara']) ? $maara : null ) . '" required>
                    <input type="submit" name="submit" value="Lisää" >
                </form>
                <hr>
                ';
            }

            // MATKATAVOITELOMAKE FUNKTIO
            function tavoite_lomake($tavoite, $matkakohde) {
                echo '
                    <form action="' . $_SERVER['REQUEST_URI'] . '" method="post" autocomplete="off">
                        <h3>Aseta tavoite</h3>
                        <h4>Matkakohde</h4>
                        <input type="text" name="matkakohde" value="' . ( isset( $_POST['matkakohde']) ? $matkakohde : null ) . '" required>
                        <h4>Kokonaishinta, €</h4>
                        <input type="number" name="tavoite"  step="any" min="0" value="' . ( isset( $_POST['tavoite']) ? $tavoite : null ) . '" required>
                        <input type="submit" name="submit2" value="Aseta" >
                    </form>
                    <hr>
                    ';
            }



            // KATSOO ONKO KÄYTTÄJÄ KIRJAUTUNUT SISÄÄN:
            if (is_user_logged_in()) {
                // ON, TEHDÄÄN TÄMÄ
                tulot_menot_lomake($viesti, $maara);
                tavoite_lomake($tavoite, $matkakohde);
                $logged_in = 1;
                
                if(isset( $_POST['submit'])){
                    // Ottaa tilitapahtuman lisäysajankohdan ylös
                    date_default_timezone_set('Europe/Helsinki');
                    $date_submitted = date('Y-m-d H:i:s');

                    // Hakee arvot lomakkeelta
                    $form_values = array($kayttaja_nimi, $_POST['viesti'], $_POST['maara'], $date_submitted);

                    // Lisää tiedot sivulle ilman sivun uudelleen päivittämistä
                    echo "<meta http-equiv='refresh' content='0'>";

                }

                if (isset( $_POST['submit2'] )) {

                    // Tavoitelomakkeen arvot
                    $tavoite_arvot = array($_POST['matkakohde'], $_POST['tavoite']);

                    // Lisää tiedot sivulle ilman sivun uudelleen päivittämistä
                    echo "<meta http-equiv='refresh' content='0'>";
                    
                }

            } else {
                // EI
                echo "Olet kirjautunut ulos. Kirjaudu sisään lisätäksesi tilitapahtumia.";
                $logged_in = 0;
            }

                // KYSELY JOLLA HAETAAN KAIKKI LUOKAT MYÖHEMPÄÄ SEULONTAA VARTEN, EI TULOSTETA VIELÄ MITÄÄN
                $args1 = array(
                    'post_type' => 'luokka',
                    'posts_per_page' => -1, 
                );
                
                $the_query1 = new WP_Query( $args1 );
                if ( $the_query1->have_posts() ) :
                while ( $the_query1->have_posts() ) : $the_query1->the_post();
                
                // OTTAA KIRJAUTUNEEN KÄYTTÄJÄN ID:N
                $kayttajan_id = get_current_user_id();
                //echo 'id: '. $kayttajan_id .'<br>';

                // HAKEE KÄYTTÄJÄT LUOKASTA
                $kayttajat_taulukko = get_field('kayttajat');
                //echo $kayttajat_taulukko .'<br>';

                // VALITSEE LUOKAN JOSTA LÖYTYY KIRJAUTUNEEN KÄYTTÄJÄN ID
                if(in_array($kayttajan_id, $kayttajat_taulukko)) {
                    $posti_id = get_the_ID();
                    //echo 'löytyi!';
                } else {
                    //echo 'ei löytynyt!';
                    
                }

                endwhile;
                wp_reset_postdata();
                else: 
                    echo "Virhe :(";
                endif;

                // THE KYSELY, JOLLA HAETAAN SIVULLE NÄYTETTÄVÄT VIESTIT
                $args = array(
                    'post_type' => 'luokka',
                    'p' => $posti_id,
                    //'posts_per_page' => 1, 
                );
                
                $the_query = new WP_Query( $args );
                if ( $the_query->have_posts() ) :
                while ( $the_query->have_posts() ) : $the_query->the_post();

                // HAKEE ENTISET TILITAPAHTUMAT JÄRJESTELMÄSTÄ JA LAITTAA NE MUUTTUJAAN
                $edelliset_tilitapahtumat = get_field('tilitapahtumat');
                
                // HAKEE ENTISET TAVOITTEET
                $edelliset_tavoitteet = get_field('valitut_kohteet');

                // PILKKOO TILITAPAHTUMAT
                $tili_col = explode('#', $edelliset_tilitapahtumat);
                
                foreach ($tili_col as $value) {
                    $tili_row[] = explode('-/-', $value);
                }

                // PILKKOO TAVOITTEET
                $tavoite_col = explode('#', $edelliset_tavoitteet);
                
                foreach ($tavoite_col as $arvo) {
                    $tavoite_row[] = explode('-/-', $arvo);
                }
                    
                ?>

                <div>
                
                <?php 

                    // TULOSTAA TILITAPAHTUMAT, JOS KÄYTTÄJÄ ON KIRJAUTUNUT SISÄÄN
                    if ($logged_in == 1) {

                        ?>
                            <h4>Tilitapahtumat:</h4>
                            <table style="width:100%; border-radius:5px;">
                            <tr>
                                <th>Nimi</th>
                                <th>Viesti</th> 
                                <th>Määrä, €</th>
                                <th>Aika</th>
                            </tr>
                        <?php

                        $length = count($tili_row);
                        for ($i = 0; $i < $length-1; $i++) {
                            
                            $kok_summa += $tili_row[$i][2];

                            ?>
                                <tr>
                                    <td><?php  echo $tili_row[$i][0]; ?></td>
                                    <td><?php  echo $tili_row[$i][1]; ?></td> 
                                    <td class="maara"><?php echo $tili_row[$i][2]; ?></td>
                                    <td><?php  echo $tili_row[$i][3]; ?></td>
                                </tr>
                            <?php
                            
                        }

                        ?>
                        
                            </table>
                            <h4>Yhteensä: <?php echo $kok_summa; ?> €</h4>
                            <hr>

                            <?php

                            // MATKATAVOITTEIDEN NIMEN JA HINNAN TALLENNUS MUUTTUJIIN
                            $pituus = count($tavoite_row);
                            for ($numero = 0; $numero < $pituus-1; $numero++) {
    
                                $matkan_nimi = $tavoite_row[$numero][0]; 
                                $tavoite_summa = $tavoite_row[$numero][1];  
                                                                    
                            }

                            // LASKEE MONTAKO PROSENTTIA TAVOITESUMMASTA ON KERÄTTY
                            if ($kok_summa != null) {
                                $prosenttiosuus = $kok_summa / $tavoite_summa * 100;
                                // pyöristää yhden desimaalin tarkkuuteen
                                $prosenttiosuus2 = round( $prosenttiosuus, 1, PHP_ROUND_HALF_ODD);
                            }

                            ?>   

                            <!-- VAROJEN NÄYTTÄMINEN -->
                            <label style="font-weight:bold; color:#1a535c; float:right;"><?php echo $matkan_nimi . ', ' . $tavoite_summa . ' €'; ?></label>
                            <progress id="progressbar" max="<?php echo $tavoite_summa; ?>" value="<?php echo $kok_summa; ?>"></progress>
                            <p id="prosentti" style="width:100%; text-align:center; color:#1a535c; font-weight: bold;"><?php echo $prosenttiosuus2 . ' %'; ?></p>
                            <br>

                        <?php
                        
                    } else {
                        // Eipä mithään
                    }
                    
                ?>
                
                </div>

                <div style='background-color:#9AD3D4; height:auto; border-radius:5px; margin-top: 30px; padding:10px; padding-bottom:50px;'>
                    <h2 style='text-align:center; padding-top:10px;'><?php the_title(); ?></h2>
                    <img src='http://192.168.9.200/wp-content/uploads/2019/01/cheerleading-2173914_1920.jpg' style='width:200px; height:110px; object-fit:cover; float:left; margin:0px 10px 0px 10px; border-radius:5px;'>
                    <p style='color:#393D3F;'><b>Katuosoite: </b><?php echo get_field('katuosoite', $posti_id); ?></P>
                    <p style='color:#393D3F;'><b>Paikkakunta: </b><?php echo get_field('paikkakunta', $posti_id); ?></P>
                    <p style='color:#393D3F;'><b>Ryhmäkoko: </b><?php echo get_field('ryhmakoko', $posti_id); ?></P>
                    <p style='color:#393D3F;'><b>Valitut matkakohteet: </b><?php echo $matkan_nimi; ?></P>
                    <p style='color:#393D3F;'><b>Matkan hinta: </b><?php echo $tavoite_summa . ' €'; ?></P>
                    <p style="color:#393D3F; margin-top: 10px; float:left;"><b>Liittymispäivämäärä: </b><?php echo get_the_date($format, $post, $posti_id); ?></p>
                    <p style="color:#393D3F; margin: 10px; float:left;"><?php echo get_the_time($format, $post, $posti_id); ?></p>
                </div>
                
                <?php

                // LUOKAN ID
                $ID = $posti_id;

                // JOS TILITAPAHTUMIA EI LISÄTTY
                if ($form_values == null) {

                    //älä tee mitään

                // LISÄKSI TARKISTETAAN ONKO TIETOKANNASSA ENNESTÄÄN TILITAPAHTUMIA 
                } else if ($edelliset_tilitapahtumat == null) {
                    
                    // JOS EI, LISÄTÄÄN UUDET MERKINNÄT SUORAAN
                    $string_to_save = $form_values[0].'-/-'.$form_values[1].'-/-'.$form_values[2].'-/-'.$form_values[3].'#';
                    update_field( 'tilitapahtumat', $string_to_save, $ID );
                    //echo "ifin kautta";
                    
                // JOS ON, LISÄTÄÄN UUDET MERKINNÄT VANHOJEN PERÄÄN
                } else {
                    //echo $edelliset_tilitapahtumat;
                    $string_to_save = $edelliset_tilitapahtumat.$form_values[0].'-/-'.$form_values[1].'-/-'.$form_values[2].'-/-'.$form_values[3].'#';
                    update_field( 'tilitapahtumat', $string_to_save, $ID );
                    //echo "elsen kautta <br>";
                    
                }

                // MATKATAVOITTEIDEN TALLENNUS
                if ($tavoite_arvot == null) {
                    // älä tee mitään
                } else {
                    // JOS EI, LISÄTÄÄN UUDET MERKINNÄT SUORAAN
                    $tallennettavat_arvot = $tavoite_arvot[0].'-/-'.$tavoite_arvot[1].'#';
                    update_field( 'valitut_kohteet', $tallennettavat_arvot, $ID );
                }

                endwhile;
                wp_reset_postdata();
                else: 
                    echo "Virhe :(";
                endif;

             ?>
            
            <script>

                // Etsii maara-luokasta kaikki - alkuiset kentät
                var elements2 = jQuery(".maara").filter(function() {
                    return jQuery(this).text().match("-");
                });

                // Vaihtaa värin punaiseksi
                for(var i=0; i<elements2.length; i++) { 
                     elements2[i].style.color = "red";
                }


                var intRegex = /^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/;

                var elements3 = jQuery(".maara").filter(function() {
                    return jQuery(this).text().match(intRegex);
                });

                // Vaihtaa värin vihreäksi
                for(var i=0; i<elements3.length; i++) { 
                     elements3[i].style.color = "green";
                }

            </script>

        </div>

        <?php lucia_get_post_attributes(); ?>
    </section>
</div>

<?php lucia_container_after_page( 'blog_archives_sidebar_layout', 'archives' );?>
<?php get_footer();