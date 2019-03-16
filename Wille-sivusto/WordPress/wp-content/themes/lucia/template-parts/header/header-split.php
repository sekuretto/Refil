<?php
	$menu_position = esc_attr(lucia_option('split_header_menu_position'));
	$header_classes = 'lq-header lq-split-header';
	$header_full_width = lucia_option('header_full_width');
	$header_classes .= ' '.$menu_position;
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
    </div>
     <?php get_template_part( 'template-parts/navigation/navigation', 'top-left' ); ?>
     <?php get_template_part( 'template-parts/navigation/navigation', 'top' ); ?>
  </div>
  
  <?php get_template_part( 'template-parts/navigation/navigation', 'mobile' ); ?>
</header>
