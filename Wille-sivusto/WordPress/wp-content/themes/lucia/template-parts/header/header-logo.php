<?php

$custom_logo = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
$logo_src = isset($custom_logo[0])? $custom_logo[0]:'';

$transparent_header_logo = lucia_option('transparent_header_logo');
$header_transparent = lucia_option('header_transparent');

$page_header_transparent = get_post_meta( get_the_ID(), 'lqthemes_header_transparent', true );

if( $page_header_transparent ){
	$header_transparent = $page_header_transparent;
	}

if( $header_transparent == '1' && $transparent_header_logo != '' ){
	
	$logo_src = $transparent_header_logo;
	
	}

$logo_src = apply_filters( 'lqt_logo', $logo_src );

if ( $logo_src ) {
		echo '<a href="'.esc_url( home_url( '/' ) ).'"><img src="' . esc_url( $logo_src ) . '"></a>';
	}else{

$header_text_color = get_header_textcolor();
 if ( 'blank' != $header_text_color ) :?>
                    <div class="lq-name-box">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <h2 class="site-name"><?php bloginfo( 'name' ); ?></h2>
                        </a>
                        <span class="site-tagline"><?php bloginfo( 'description' ); ?></span>
                    </div>
                    <?php endif;?>
        <?php }?>

