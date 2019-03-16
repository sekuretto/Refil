<?php
/*
  Plugin Name: Custom post
  Description: Own custom posts
  Version: 1.0
  Author: Refil
 */

 //tehtävän luominen

 function tehtava_form( $tehtava_nimi, $kohde, $kuvaus, $maksu, $tekijoiden_lukumaara, $picture ) {

    
    echo '
    <form action="' . $_SERVER['REQUEST_URI'] . '" method="post" enctype="multipart/form-data">
    <h2>Tehtävän lisäys</h2>
    <p><strong>*</strong> -merkityt kohdat ovat pakollisia!</p>
    <p>Kuvan koko maksimissaan 2MB!</p>
    <div>
    <label for="tehtava_nimi">Tehtävän nimi <strong>*</strong></label>
    <input type="text" name="tehtava_nimi" value="' . ( isset( $_POST['tehtava_nimi']) ? $tehtava_nimi : null ) . '">
    </div>

    <div>
    <label for="kohde">Katuosoite <strong>*</strong></label>
    <input type="text" name="kohde" value="' . ( isset( $_POST['kohde']) ? $kohde : null ) . '">
    </div>

    <div>
    <label for="kuvaus">Kuvaus <strong></strong></label>
    <input type="text"  name="kuvaus" value="' . ( isset( $_POST['kuvaus']) ? $kuvaus : null ) . '">
    </div>

    <div>
    <label for="maksu">Maksu € <strong></strong></label>
    <input type="number" name="maksu" value="' . ( isset( $_POST['maksu']) ? $maksu : null ) . '">
    </div>
        
    <div>
    <label for="tekijoiden_lukumaara">Tekijöiden lukumäärä <strong></strong></label>
    <input type="number" name="tekijoiden_lukumaara" value="' . ( isset( $_POST['tekijoiden_lukumaara'] ) ? $tekijoiden_lukumaara : null ) . '">
    </div>
        
    <div>
    <input type="hidden" name="profilepicture" value="'. $picture.  '">
    </div>

    <div>
    <label for="image">Kuva</label>
    <input type="file" name="profilepicture" size="25"/>    
    </div>

        
    <input type="submit" name="submit-tehtava" value="Lisää tehtävä"/>



    </form>
    ';
        
}



function tehtava_validation( $tehtava_nimi, $kohde )  {

    global $reg_errors_tehtava;
    $reg_errors_tehtava = new WP_Error;

    if (empty( $kohde ) || empty($tehtava_nimi)) {
        $reg_errors_tehtava->add('field', 'Kenttä tyhjä');
    }


    if ( is_wp_error( $reg_errors_tehtava ) ) {
 
        foreach ( $reg_errors_tehtava->get_error_messages() as $error ) {
         
            echo '<div>';
            echo '<strong>Huom! </strong>:';
            echo $error . '<br/>';
            echo '</div>';
             
        }
     
    }
    
}


function complete_tehtava($tehtava_nimi, $kohde, $kuvaus, $maksu, $tekijoiden_lukumaara, $picture) {


    $terms = get_terms( 'tyyppi' );


    


    // Globaali data talteen
    // Tarkistetaan errorit
       
        //jäsennetään käyttäjän data


        //luodaan käyttäjä kantaan
     //   $user = wp_get_current_user();

        //jäsennetään uuden tehtävä postauksen data


        $new_post_args = [
            'post_title'    => $tehtava_nimi,
            'post_type'     => 'tehtava',
            'post_status'   => 'publish',
            'post_author'   => $author_id,

        ];


        //luodaan luokka-post
        $tehtavaID = wp_insert_post( $new_post_args );


        require( dirname(__FILE__) . '/../../../wp-load.php' );
 
$wordpress_upload_dir = wp_upload_dir();
$i = 1; 
 
$profilepicture = $_FILES['profilepicture'];
$new_file_path = $wordpress_upload_dir['path'] . '/' . $i. '_' . $profilepicture['name'];
$new_file_mime = mime_content_type( $profilepicture['tmp_name'] );
 
if( empty( $profilepicture ) )
	die( 'File is not selected.' );
 
if( $profilepicture['error'] )
	die( $profilepicture['error'] );
 
if( $profilepicture['size'] > wp_max_upload_size() )
	die( 'It is too large than expected.' );
 
if( !in_array( $new_file_mime, get_allowed_mime_types() ) )
	die( 'WordPress doesn\'t allow this type of uploads.' );
 
while( file_exists( $new_file_path ) ) {
	$i++;
	$new_file_path = $wordpress_upload_dir['path'] . '/' . $i . '_' . $profilepicture['name'];
}
 
$parent_post_id = $pakettiID;

if( move_uploaded_file( $profilepicture['tmp_name'], $new_file_path ) ) {
	$upload_id = wp_insert_attachment( array(
		'guid'           => $new_file_path, 
		'post_mime_type' => $new_file_mime,
		'post_title'     => preg_replace( '/\.[^.]+$/', '', $profilepicture['name'] ),
		'post_content'   => '',
		'post_status'    => 'inherit'
	), $new_file_path, $parent_post_id );
	// wp_generate_attachment_metadata() won't work if you do not include this file
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	// Generate and save the attachment metas into the database
	wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path ) );
	// Show the uploaded file in browser
}

$picture = $wordpress_upload_dir['url'].'/'.$i.'_'.$profilepicture['name'];





        //päivitetään luokan postia ACF-apin kautta
        update_field('kohde', $kohde, $tehtavaID);
        update_field('kuvaus', $kuvaus, $tehtavaID);
        update_field('maksu', $maksu, $tehtavaID);
        update_field('tekijoiden_lukumaara', $tekijoiden_lukumaara, $tehtavaID);
        update_field('profilepicture', $picture, $tehtavaID );
        wp_set_post_terms( $tehtavaID, 'yksityinen', 'tyyppi' );
        //update_field('kayttajat', $user, $postID);
    
        echo '       
        <form action="http://'.$_SERVER['HTTP_HOST'].'/index.php/tehtavat/" method="post" id="myform">
        </form>
        <script type="text/javascript">
        document.getElementById("myform").submit();
         </script>
        ';
}

 function custom_tehtavapostit_function() {
   if( isset($_POST['submit-tehtava'] ) ){
        tehtava_validation(
        $_POST['tehtava_nimi'],
        $_POST['kohde'],
        $_POST['maksu']
        );

        global $reg_errors_tehtava;

        if( 0 < count( $reg_errors_tehtava->get_error_messages() ) ) {
            return tehtava_form(
                $tehtava_nimi, $kohde, $kuvaus, $maksu, $tekijoiden_lukumaara
        );
    }

    global  $tehtava_nimi, $kohde, $kuvaus, $maksu, $tekijoiden_lukumaara, $picture;
    $tehtava_nimi =   sanitize_text_field( $_POST['tehtava_nimi'] );
    $kohde  =   sanitize_text_field( $_POST['kohde'] );
    $kuvaus     =   sanitize_text_field( $_POST['kuvaus'] );
    $maksu =   sanitize_text_field( $_POST['maksu'] );
    $tekijoiden_lukumaara  =   sanitize_text_field( $_POST['tekijoiden_lukumaara'] );
    $picture = sanitize_text_field($_POST['profilepicture']);

    complete_tehtava(
        $tehtava_nimi, $kohde, $kuvaus, $maksu, $tekijoiden_lukumaara, $picture);
      
    }else{
    tehtava_form(
        $tehtava_nimi, $kohde, $kuvaus, $maksu, $tekijoiden_lukumaara, $picture);
    }
/*
    
    else{
     }

     
  */ 

}



?>

<?php


add_shortcode( 'cr_custom_tehtavapostit', 'custom_tehtavapostit_shortcode' );

function custom_tehtavapostit_shortcode() {
    ob_start();
    custom_tehtavapostit_function();
    return ob_get_clean();
}
?>