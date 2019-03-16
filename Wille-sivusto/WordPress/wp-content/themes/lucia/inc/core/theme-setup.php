<?php

function lucia_setup() {

	load_theme_textdomain( 'lucia', get_template_directory() . '/languages' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'lucia_featured_image', 960, 720, true );
	add_image_size( 'lucia_widget_post_image', 480, 360, true );

	// Set the default content width.

	$GLOBALS['content_width'] = 1170;
	register_nav_menus( array(

		'top-bar'    => esc_html__( 'Top Bar Menu', 'lucia' ),
		'top'    => esc_html__( 'Top Menu', 'lucia' ),
		'top-left' => esc_html__( 'Top Left Menu (Split Navigation Bar)', 'lucia' ),

	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */

	add_theme_support( 'html5', array(

		'comment-form',
		'comment-list',
		'gallery',
		'caption',

	) );

	// Add theme support for Custom Logo.

	add_theme_support( 'custom-logo', array(

		'height'      => 100,
		'flex-width'  => true,
		'flex-height' => true,

	) );

	// Setup the WordPress core custom header feature.
	add_theme_support( 'custom-header', array(

		'default-image'          => '',
		'random-default'         => false,
		'width'                  => '1920',
		'height'                 => '70',
		'flex-height'            => true,
		'flex-width'             => true,
		'default-text-color'     => '#333333',
		'header-text'            => true,
		'uploads'                => true,
		'wp-head-callback'       => '',
		'admin-head-callback'    => '',
		'admin-preview-callback' => ''

	));

	// Setup the WordPress core custom background feature.

	add_theme_support( 'custom-background',  array(

		'default-color' => 'ffffff',

		'default-image' => '',

	) );

	// Add theme support for selective refresh for widgets.

	add_theme_support( 'customize-selective-refresh-widgets' );

	// Woocommerce Support

	add_theme_support( 'woocommerce' );

	add_theme_support( 'wc-product-gallery-zoom' );

	add_theme_support( 'wc-product-gallery-lightbox' );

	add_theme_support( 'wc-product-gallery-slider' );

	/*

	 * This theme styles the visual editor to resemble the theme style,

	 * specifically font, colors, and column width.

 	 */

	add_editor_style( array( 'assets/css/editor-style.css' ) );

}

add_action( 'after_setup_theme', 'lucia_setup' );