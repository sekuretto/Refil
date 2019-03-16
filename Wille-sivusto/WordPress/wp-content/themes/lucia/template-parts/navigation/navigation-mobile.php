<div class="lq-mobile-main-header">
  <div class="lq-logo">
    <?php get_template_part( 'template-parts/header/header', 'logo' ); ?>
  </div>
  <div class="lq-menu-toggle">
    <div class="lq-toggle-icon"><span class="lq-line"></span></div>
  </div>
</div>

<div class="lq-mobile-drawer-header" style="display: none;">

  <?php
	$custom_menu = apply_filters('lqt_custom_menu', '');

	$args = array(

			'theme_location' => 'top',

			'menu_id'        => 'top-menu',

			'menu_class' => 'lq-mobile-main-nav',

			'fallback_cb'    => false,

			'container' =>'',

			'link_before' => '<span>',

   			'link_after' => '</span>',

		);
	
	if( $custom_menu ){

		$args['menu'] = esc_attr($custom_menu);

		$args['theme_location'] = '';

		}

	wp_nav_menu( $args );
	?>
</div>