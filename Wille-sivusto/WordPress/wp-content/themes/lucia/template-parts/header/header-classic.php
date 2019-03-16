<?php

	$logo_position = esc_attr(lucia_option('classic_header_logo_position','left'));
	$menu_position = esc_attr(lucia_option('classic_header_menu_position','left'));
	
	$header_classes = 'lq-header lq-classic-header logo'.$logo_position;
	$header_classes .= ' '.$menu_position;
	
	$header_full_width = lucia_option('header_full_width');
	if( $header_full_width == '1' ){
		$header_classes .= ' fullwidth';
		}
	
	$header_classes = apply_filters( 'lqt_header_classes', $header_classes );
?>
<header id="masthead" class="<?php echo esc_attr($header_classes);?>">
  <?php get_template_part( 'template-parts/header/header', 'top-bar' ); ?>
  <div class="lq-main-header">
    <div class="lq-logo">
      <?php get_template_part( 'template-parts/header/header', 'logo' ); ?>
      <div class="lq-microwidgets">
        <div class="lq-microwidget lq-microwidget-banner">
          <?php 
				$classic_header_promo = lucia_option('classic_header_promo');
				echo do_shortcode(wp_kses_post($classic_header_promo));
			?>
        </div>
      </div>
    </div>
    <?php get_template_part( 'template-parts/navigation/navigation', 'top' ); ?>
  </div>
  <?php get_template_part( 'template-parts/navigation/navigation', 'mobile' ); ?>
</header>