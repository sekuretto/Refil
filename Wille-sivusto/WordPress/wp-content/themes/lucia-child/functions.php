<?php
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
 
    $parent_style = 'parent-style';
 
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}

add_action('wp_logout','auto_redirect_after_logout');
function auto_redirect_after_logout(){
  wp_redirect( home_url() );
  exit();
}

add_action('link_registration_redirect','link_registration_auto_redirect');
function link_registration_auto_redirect(){
  wp_redirect( home_url() );
  exit();
}

//add_action( 'user_register', 'auto_login_new_user' );

/*function auto_login_new_user( $user_id ) {
   // wp_set_current_user($user_id);
   // wp_set_auth_cookie($user_id);
   // $request = $_SERVER["HTTP_REFERER"];
   // wp_redirect($request);
  //  exit;
}*/
