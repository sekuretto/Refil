<?
$user_id = wp_insert_user($userdata)


//ensin uusi luokka post
$new_post_args = [
    'post_title'    => false,
    'post_type'     => 'luokka',
    'post_status'   => 'publish',
    'post_author'   => $user_id
];

$postID = wp_insert_post( $new_post_args ); //tästä post id postauksen päivittämistä varten
/*
 ACF plugairn apin oma tyyli lisätä custom fieldien arvoja posteihin

-------------------- field_key => acf fieldin slug
------------------------------- value => mitä tallennetaan
--------------------------------------- post_id => mihin postiin tallenetaan 

------ update_field($field_key, $value, $post_id) 
*/

update_field('katuosoite', $_POST['katuosoite'], $postID);
update_field('paikkakunta', $_POST['paikkakunta'], $postID);
update_field('ryhmakoko', $_POST['ryhmakoko'], $postID);
update_field('salasana', $_POST['salasana'], $postID);
update_field('käyttäjät', $_POST['käyttäjät'], $postID);
?>