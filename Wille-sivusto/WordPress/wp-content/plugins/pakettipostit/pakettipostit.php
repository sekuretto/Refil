<?php
/*
  Plugin Name: Matkapaketti post
  Description: Own custom posts
  Version: 1.0
  Author: Refil
 */


 //tehtävän luominen

 function paketti_form( $post_title, $hinta_hlö, $kuvaus, $picture ) {

    
    echo '
    <form action="' . $_SERVER['REQUEST_URI'] . '" method="post" enctype="multipart/form-data">
    <h2>Matkapaketin lisäys</h2>
    <p><strong>*</strong> -merkityt kohdat ovat pakollisia!</p>
    <p>Kuvan koko maksimissaan 2MB!</p>
    <div>
    <label for="post_title">Matkakohde <strong>*</strong></label>
    <input type="text" name="post_title" value="' . ( isset( $_POST['post_title']) ? $post_title : null ) . '">
    </div>


    <div>
    <label for="kuvaus">Kuvaus <strong></strong></label>
    <input type="text" name="kuvaus" value="' . ( isset( $_POST['kuvaus']) ? $kuvaus : null ) . '">
    </div>

    <div>
    <label for="hinta_hlö">Hinta/hlö € <strong>*</strong></label>
    <input type="number" name="hinta_hlö" value="' . ( isset( $_POST['hinta_hlö']) ? $hinta_hlö : null ) . '">
    </div>
    <div>
    <input type="hidden" name="profilepicture" value="'. $picture.  '">
    </div>
    <div>
    <label for="image">Kuva</label>

    <input type="file" name="profilepicture" size="25"/>    
    </div>
        
    <input type="submit" name="submit-paketti" value="Lisää matkapaketti"/>



    </form>
    ';
        
}
//    value"'. ( isset( $_POST['image']) ? $kuva : null ) . '" 



function paketti_validation( $post_title, $hinta_hlö )  {

    global $reg_errors_paketti;
    $reg_errors_paketti = new WP_Error;

    if ( empty( $hinta_hlö ) || empty($post_title)) {
        $reg_errors_paketti->add('field', 'Kenttä tyhjä');
    }


    if ( is_wp_error( $reg_errors_paketti ) ) {
 
        foreach ( $reg_errors_paketti->get_error_messages() as $error ) {
         
            echo '<div>';
            echo '<strong>Huom! </strong>:';
            echo $error . '<br/>';
            echo '</div>';
             
        }
     
    }
    
}


function complete_paketti($post_title, $kuvaus, $hinta_hlö, $picture) {

    

    $terms = get_terms( 'tyyppi' );


    // Globaali data talteen
    // Tarkistetaan errorit
       
        //jäsennetään käyttäjän data


        //luodaan käyttäjä kantaan
     //   $user = wp_get_current_user();

        //jäsennetään uuden luokkapostauksen data
        $new_post_args = [
            'post_title'    => $post_title,
            'post_type'     => 'matkapaketti',
            'post_content'  => $kuvaus,
            'post_status'   => 'publish',
            'post_author'   => $author_id
        ];


        //luodaan luokka-post
        $pakettiID = wp_insert_post( $new_post_args );


/*



        require_once(ABSPATH.'wp-admin/includes/file.php');
        $uploadedfile = $_FILES['image'];
        $movefile = wp_handle_upload($uploadedfile, array('test_form' => false));
        
        //On sauvegarde la photo dans le média library
        if ($movefile) {
        $wp_upload_dir = wp_upload_dir();
        $attachment = array(
        'guid' => $wp_upload_dir['url'].'/'.basename($movefile['file']),
        'post_mime_type' => $movefile['type'],
        'post_title' => preg_replace('/\.[^.]+$/','', basename($movefile['file'])),
        'post_content' => '',
        'post_status' => 'inherit'
        );
        $attach_id = wp_insert_attachment($attachment, $movefile['file']);
        
        update_field('image', $attach_id, 'user_'.$current_user->ID);
        }


echo var_dump($movefile);
echo $uploadedfile;

*/








require( dirname(__FILE__) . '/../../../wp-load.php' );
 
$wordpress_upload_dir = wp_upload_dir();
// $wordpress_upload_dir['path'] is the full server path to wp-content/uploads/2017/05, for multisite works good as well
// $wordpress_upload_dir['url'] the absolute URL to the same folder, actually we do not need it, just to show the link to file
$i = 1; // number of tries when the file with the same name is already exists
 
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
// looks like everything is OK
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










/*



 
        $filename_array = array(
            $kuva
         );






         $parent_post_id = $pakettiID;
         $wp_upload_dir = wp_upload_dir();

         foreach ($filename_array as $filename) {

            $filetype = wp_check_filetype( basename( $filename ), null );


            $attachment = array(
                'guid'           => $new_file_path, 
                'post_mime_type' => $new_file_mime,
                'post_title'     => $post_title,
                'post_content'   => '',
                'post_status'    => 'publish',
            );
            $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );

            // Make sure that this file is included, as   wp_generate_attachment_metadata() depends on it.
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
        	require_once( ABSPATH . 'wp-includes/l10n.php' ); // Required to gain access to the __() function
    	    require_once( ABSPATH . 'wp-includes/formatting.php' ); // Required to gain access to the untrailingslashit() function
        	require_once( ABSPATH . 'wp-includes/post.php' ); // Required to gain access to the wp_match_mime_types() function
            require_once( ABSPATH . 'wp-admin/includes/file.php' ); // Required to gain access to the wp_handle_upload() function
            require_once( ABSPATH . 'wp-admin/includes/media.php' );
            // Generate the metadata for the attachment, and update the database record.
            $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
            wp_update_attachment_metadata( $attach_id, $attach_data );
            set_post_thumbnail($parent_post_id, $attach_id);
        
        }





        echo $kuva;
        echo '<br>';
        echo $attach_id;
        echo '<br>';
        echo var_dump($attachment);
        echo '<br>';
        echo var_dump($attach_data);
        echo '<br>';
        echo $parent_post_id;
        echo '<br>';
        echo var_dump($wp_upload_dir);
        echo '<br>';
        echo $filename;
        echo '<br>';
        echo $pakettiID;





*/









        /*
/*
echo $post_title;

echo $wp_upload_dir['url'];






         /*
        $upload = wp_upload_bits( $_FILES['kuva']['name'], null, file_get_contents( $_FILES['kuva']['tmp_name'] ) );

    
        $wp_upload_dir = wp_upload_dir();
    

       
        $attachment = array(
            'guid' => $wp_upload_dir['baseurl'] . _wp_relative_upload_path( $upload['file'] ),
            'post_mime_type' => 'image/jpg',
            'post_title' => $post_title,
            'post_content' => 'my description',
            'post_status' => 'inherit'
        );
        
        $attach_id = wp_insert_attachment( $attachment, $upload['file'], $pakettiID );
    
        require_once( ABSPATH . 'wp-admin/includes/image.php' );

    
        $attach_data = wp_generate_attachment_metadata( $attach_id, $post_title );
        wp_update_attachment_metadata( $attach_id, $attach_data );
    
        update_post_meta( $pakettiID, '_thumbnail_id', $attach_id );
*/

        update_field('kuvaus', $kuvaus, $pakettiID);
        update_field('hinta_hlö', $hinta_hlö, $pakettiID);
        update_field('profilepicture', $picture, $pakettiID );
        wp_set_post_terms( $pakettiID, 'yksityinen', 'tyyppi' );



        //päivitetään luokan postia ACF-apin kautta

        //update_field('kayttajat', $user, $postID);

        echo '       
        <form action="http://'.$_SERVER['HTTP_HOST'].'/index.php/matkapaketit-testi/?valinta=yksityinen" method="post" id="myform">
        </form>
        <script type="text/javascript">
        document.getElementById("myform").submit();
         </script>
        ';


}

 function custom_pakettipostit_function() {
   if( isset($_POST['submit-paketti'] ) ){
        paketti_validation(
        $_POST['post_title'],
        $_POST['hinta_hlö']
        );

        global $reg_errors_paketti;

        if( 0 < count( $reg_errors_paketti->get_error_messages() ) ) {
            return paketti_form(
                $post_title, $kuvaus, $hinta_hlö, $picture
        );
    }

    global  $post_title, $kuvaus, $hinta_hlö, $picture;
    $post_title =   sanitize_text_field( $_POST['post_title'] );
    $kuvaus  =   sanitize_text_field( $_POST['kuvaus'] );
    $hinta_hlö =   sanitize_text_field( $_POST['hinta_hlö'] );
    $picture = sanitize_text_field($_POST['profilepicture']);

    complete_paketti(
        $post_title, $kuvaus, $hinta_hlö, $picture
        );
    }

    paketti_form( $post_title, $kuvaus, $hinta_hlö, $picture);

/*
    
    else{
     }
  */   
}









add_shortcode( 'cr_custom_pakettipostit', 'custom_pakettipostit_shortcode' );

function custom_pakettipostit_shortcode() {
    ob_start();
    custom_pakettipostit_function();
    return ob_get_clean();
}
