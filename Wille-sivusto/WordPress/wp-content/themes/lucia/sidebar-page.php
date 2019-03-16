<?php
$sidebar = apply_filters('lqthemes_page_sidebar','sidebar-page' );



if( is_active_sidebar( $sidebar ) ) {

		dynamic_sidebar( $sidebar );

	}elseif( is_active_sidebar( 'sidebar-1' ) ) {

		dynamic_sidebar('sidebar-1');

	}