<?php
/**
 * Register widget area.
 *
 */

function lucia_widgets_init() {

	register_sidebar( array(

		'name'          => esc_html__( 'Sidebar', 'lucia' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'lucia' ),
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',

	) );

	register_sidebar( array(

		'name'          => esc_html__( 'Page Sidebar', 'lucia' ),
		'id'            => 'sidebar-page',
		'description'   => __( 'Add widgets here to appear in your pages sidebar.', 'lucia' ),
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',

	) );

	register_sidebar( array(

		'name'          => esc_html__( 'Blog Sidebar', 'lucia' ),
		'id'            => 'sidebar-blog',
		'description'   => esc_html__( 'Add widgets here to appear in your posts sidebar.', 'lucia' ),
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',

	) );

	register_sidebar( array(

		'name'          => esc_html__( 'Archives', 'lucia' ),
		'id'            => 'sidebar-archives',
		'description'   => esc_html__( 'Add widgets here to appear in your posts list sidebar.', 'lucia' ),
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',

	) );

	register_sidebar( array(

		'name'          => esc_html__( 'WooCommerce Single Product', 'lucia' ),
		'id'            => 'sidebar-woo-single',
		'description'   => esc_html__( 'Add widgets here to appear in your products sidebar.', 'lucia' ),
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',

	) );

	register_sidebar( array(

		'name'          => esc_html__( 'WooCommerce Archives', 'lucia' ),
		'id'            => 'sidebar-woo-archives',
		'description'   => esc_html__( 'Add widgets here to appear in your products list sidebar.', 'lucia' ),
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',

	) );

	register_sidebar( array(

		'name'          => esc_html__( 'Footer 1', 'lucia' ),
		'id'            => 'footer-1',
		'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'lucia' ),
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',

	) );

	register_sidebar( array(

		'name'          => esc_html__( 'Footer 2', 'lucia' ),
		'id'            => 'footer-2',
		'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'lucia' ),
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',

	) );

	register_sidebar( array(

		'name'          => esc_html__( 'Footer 3', 'lucia' ),
		'id'            => 'footer-3',
		'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'lucia' ),
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',

	) );

	register_sidebar( array(

		'name'          => esc_html__( 'Footer 4', 'lucia' ),
		'id'            => 'footer-4',
		'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'lucia' ),
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',

	) );

	register_sidebar( array(

		'name'          => esc_html__( 'Custom Sidebar 1', 'lucia' ),
		'id'            => 'custom-1',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',

	) );

	register_sidebar( array(

		'name'          => esc_html__( 'Custom Sidebar 2', 'lucia' ),
		'id'            => 'custom-2',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',

	) );

	register_sidebar( array(

		'name'          => esc_html__( 'Custom Sidebar 3', 'lucia' ),
		'id'            => 'custom-3',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',

	) );

	register_sidebar( array(

		'name'          => esc_html__( 'Custom Sidebar 4', 'lucia' ),
		'id'            => 'custom-4',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget-box %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',

	) );

}

add_action( 'widgets_init', 'lucia_widgets_init' );