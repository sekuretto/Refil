<?php
/**
 * Enqueue scripts and styles.
 */
function lucia_scripts() {
	
	global $lucia_options;
	
	$lucia_options = get_option( LUCIA_TEXTDOMAIN );
	
	wp_enqueue_style( 'font-awesome',  get_template_directory_uri() .'/assets/vendor/font-awesome/css/font-awesome.min.css', false, '', false );
	wp_enqueue_style( 'owl-carousel',  get_template_directory_uri() .'/assets/vendor/owl-carousel/css/owl.carousel.min.css', false, '', false );
	
	// Theme stylesheet.	
	wp_enqueue_style( 'lucia-style', get_stylesheet_uri() , false, LUCIA_VERSION, false );

	wp_enqueue_script( 'jquery-owl-carousel', get_template_directory_uri() . '/assets/vendor/owl-carousel/js/owl.carousel.min.js' , array( 'jquery' ), null, true);
	
	wp_enqueue_script( 'respond', get_template_directory_uri() . '/assets/vendor/respond.min.js' , array( 'jquery' ), null, true);
		

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	$preloader_background = esc_attr(lucia_option('preloader_background'));
	$preloader_opacity = esc_attr(lucia_option('preloader_opacity'));
	$preloader_image = esc_attr(lucia_option('preloader_image'));
	
	if (is_numeric($preloader_image)) {
		$image_attributes = wp_get_attachment_image_src($preloader_image, 'full');
		$preloader_image   = $image_attributes[0];
	}
	
	$preloader_bg = "";
	if( $preloader_background != "" ){
		$rgb = lucia_hex2rgb( $preloader_background );
		$preloader_bg = "rgba(".$rgb[0].",".$rgb[1].",".$rgb[2].",".$preloader_opacity.")";
	}
	
	wp_enqueue_script( 'lucia-main', get_template_directory_uri() . '/assets/js/main.js' , array( 'jquery' ), LUCIA_VERSION, true);
	wp_localize_script( 'lucia-main', 'lucia_params', array(
		'ajaxurl'  => esc_url(admin_url('admin-ajax.php')),
		'themeurl' => get_template_directory_uri(),
	)  );
	
	$custom_css = '';
	$header_text_color = get_header_textcolor();

	if ( 'blank' != $header_text_color ) :
		$custom_css .= ".site-name, .site-tagline { color: ".sanitize_hex_color( $header_text_color )." ; }.site-tagline { display: none; }";
	else:
		$custom_css .= ".site-name,.site-tagline {display: none;}";
	endif;
	
	
	$primary_color = lucia_option('primary_color');
	if( $primary_color != '' ){
	
	 	$primary_color = sanitize_hex_color( $primary_color );
	
		$custom_css .= "a:hover,a:active {color: ".$primary_color.";}header a:hover {color: ".$primary_color.";}.site-nav  > div > ul > li.current > a {color: ".$primary_color.";}.entry-meta a:hover {color: ".$primary_color.";}.form-control:focus,select:focus,input:focus,textarea:focus,input[type=\"text\"]:focus,input[type=\"password\"]:focus,input[type=\"datetime\"]:focus,input[type=\"datetime-local\"]:focus,input[type=\"date\"]:focus,input[type=\"month\"]:focus,input[type=\"time\"]:focus,input[type=\"week\"]:focus,input[type=\"number\"]:focus,input[type=\"email\"]:focus,input[type=\"url\"]:focus,input[type=\"search\"]:focus,input[type=\"tel\"]:focus,input[type=\"color\"]:focus,.uneditable-input:focus {border-color: ".$primary_color.";}input[type=\"submit\"] {background-color: ".$primary_color.";}.entry-box.grid .img-box-caption .entry-category {background-color: ".$primary_color.";}.widget-title:before {background-color: ".$primary_color.";}.btn-normal,button,.lucia-btn-normal,.woocommerce #respond input#submit.alt,.woocommerce a.button.alt,.woocommerce button.button.alt,.woocommerce input.button.alt {background-color: ".$primary_color.";}.woocommerce #respond input#submit.alt:hover,.woocommerce a.button.alt:hover,.woocommerce button.button.alt:hover,.woocommerce input.button.alt:hover {background-color: ".$primary_color.";}.woocommerce nav.woocommerce-pagination ul li a:focus,.woocommerce nav.woocommerce-pagination ul li a:hover {color: ".$primary_color.";}.lucia-header .lucia-main-nav > li > a:hover,.lucia-header .lucia-main-nav > li.active > a {color: ".$primary_color.";}.lucia-header .lucia-main-nav > li > a:hover, .lucia-header .lucia-main-nav > li.active > a {color:".$primary_color.";}";
	}
	
	// Form styles
	$form_border_style = lucia_option('form_border_style');
	$form_border_width = lucia_option('form_border_width');
	$form_border_color = lucia_option('form_border_color');
	$form_background_color = lucia_option('form_background_color');
	$form_broder_radius = lucia_option('form_broder_radius');
	$custom_css .=  '.form-control, select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"], .uneditable-input{	border-style:'.esc_attr($form_border_style).';	border-width:'.absint($form_border_width).'px;border-color:'.sanitize_hex_color($form_border_color).';	background-color:'.sanitize_hex_color($form_background_color).';border-radius: '.esc_attr($form_broder_radius).'px;}';
	
	// Button styles
	$button_font_size = lucia_option('button_font_size');
	$button_color = lucia_option('button_color');
	$button_text_transform = lucia_option('button_text_transform');
	$button_broder_radius = lucia_option('button_broder_radius');
	$button_border_color = lucia_option('button_border_color');
	$button_background_color = lucia_option('button_background_color');
	$button_border_style = lucia_option('button_border_style');
	$button_border_width = lucia_option('button_border_width');
	$button_border_style = lucia_option('button_border_style');
	
	$custom_css .=  'button,input[type="submit"],.lucia-btn,btn-normal{'.((is_numeric($button_font_size) && $button_font_size > 0 )?'font-size: '.absint($button_font_size).'px;':'').'color: '.sanitize_hex_color($button_color).';text-transform: '.esc_attr($button_text_transform).';border-radius: '.esc_attr($button_broder_radius).'px;border-color:'.sanitize_hex_color($button_border_color).';background-color:'.sanitize_hex_color($button_background_color).';border-style:'.esc_attr($button_border_style).';border-width:'.absint($button_border_width).'px;}';
	
	
	$lqthemes_page_header_background_image = apply_filters('lqthemes_page_header_background_image', '');
	$lqthemes_post_title_background_color = apply_filters('lqthemes_post_title_background_color', '');
	$lqthemes_post_title_bg_image_position = apply_filters( 'lqthemes_post_title_bg_image_position', '' );
	$lqthemes_post_title_bg_image_repeat = apply_filters( 'lqthemes_post_title_bg_image_attachment', '' );
	$lqthemes_post_title_bg_image_size = apply_filters( 'lqthemes_post_title_bg_image_size', '' );
	$lqthemes_post_title_font_color = apply_filters('lqthemes_post_title_font_color', '');
	
	
	if( $lqthemes_post_title_font_color != '' )
		$custom_css .= 'body .page-title-bar,body .page-title-bar .page-title h1,body .page-title-bar .page-title h2,body .page-title-bar a{color:'.sanitize_hex_color($lqthemes_post_title_font_color).';}';
		
	$custom_css .= 'body .page-title-bar{';
		
	if( $lqthemes_page_header_background_image != '' ){
		if (is_numeric($lqthemes_page_header_background_image)) {
			$image_attributes = wp_get_attachment_image_src($lqthemes_page_header_background_image, 'full');
			$lqthemes_page_header_background_image   = $image_attributes[0];
		  }
		$custom_css .= 'background-image:url('.esc_url($lqthemes_page_header_background_image).');';
	}
	if( $lqthemes_post_title_background_color != '' )
		$custom_css .= 'background-color:'.sanitize_hex_color($lqthemes_post_title_background_color).';';
	if( $lqthemes_post_title_bg_image_position != '' )
		$custom_css .= 'background-position:'.esc_attr($lqthemes_post_title_bg_image_position).';';
	if( $lqthemes_post_title_bg_image_repeat != '' )
		$custom_css .= 'background-repeat:'.esc_attr($lqthemes_post_title_bg_image_repeat).';';
	if( $lqthemes_post_title_bg_image_size != '' )
		$custom_css .= 'background-size:'.esc_attr($lqthemes_post_title_bg_image_size).';';
	
	$custom_css .= '}';
	
	$body_layout = lucia_option('body_layout');
	if( $body_layout == 'boxed' ){
		$box_width = lucia_option('box_width');
		$custom_css .=  '.wrapper{width:'.absint($box_width).'px;}';
		}
	
	$sidebar_width = lucia_option('sidebar_width');

	if($sidebar_width){
	$custom_css .= '@media (min-width: 992px){
		.left-aside .col-aside-left, .right-aside .col-aside-right {
			width: '.absint($sidebar_width).'%;
		}}
		@media (min-width: 992px){
		.left-aside .col-main, .right-aside .col-main {
			width: '.absint(100-$sidebar_width).'%;
		}
		}';

	}
	
	$sticky_header = lucia_option('sticky_header');
	
	if($sticky_header != '1'){
	$custom_css .= '.lq-fixed-header-wrap{
			display: none !important;
		}';

	}
	
	$sticky_header_mobile = lucia_option('sticky_header_mobile');
	
	if($sticky_header_mobile != '1'){
	$custom_css .= '@media (max-width: 768px){
		.lq-fixed-header-wrap{
			display: none !important;
		}}';

	}else{
		$custom_css .= '@media (max-width: 768px){
		.lq-fixed-header-wrap{
			display: block !important;
		}}';
	}
	
	
	$custom_css = apply_filters( 'lucia_additional_css', $custom_css );

	wp_add_inline_style( 'lucia-style', str_replace('&gt;', '>', stripslashes(wp_filter_nohtml_kses( $custom_css ) ) ) );

}
add_action( 'wp_enqueue_scripts', 'lucia_scripts' );

function lucia_admin_scripts(){
	
	if( isset($_GET['page']) && $_GET['page'] == 'about-lucia' )
		wp_enqueue_style( 'lucia-admin', get_template_directory_uri() . '/assets/css/admin.css', '', '', false );
	
		
	}
add_action( 'admin_enqueue_scripts', 'lucia_admin_scripts' );


function lucia_customizer_scripts(){

	wp_enqueue_style( 'lucia-customizer', get_template_directory_uri() . '/assets/css/customizer-control.css', '', '', false );
	
		
	}
add_action( 'customize_controls_print_styles', 'lucia_customizer_scripts' );
