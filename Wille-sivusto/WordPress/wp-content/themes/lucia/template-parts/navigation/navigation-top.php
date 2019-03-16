<?php

	$header_style = lucia_option('header_style');
	$link_hover_effect = lucia_option('link_hover_effect');
	
	$display_shopping_cart_icon = lucia_option('display_shopping_cart_icon');
	$display_search_icon = lucia_option('display_search_icon');
	$display_random_icon = lucia_option('display_random_icon');
	
	$navigation_style = lucia_option('navigation_style');
	
	if($header_style=='')
		$header_style = 'default';
	$class = 'lq-navigation';
	
	if( $header_style == 'classic' || $header_style == 'center' )
	$class .= ' '.lucia_get_border_style($navigation_style);
	
	$header_menu_hover_style = ' lq-main-nav level-arrows-on';
	$header_menu_hover_style .= ' '.lucia_get_hover_style($link_hover_effect);
	
	$addClass = '';
	
?>

<?php
	$icons_by_menu = '<div class="lq-microwidgets">';

	if ($display_shopping_cart_icon == '1' && $header_style !== 'split')
        $icons_by_menu .= '					
					<div class="lq-microwidget lq-microwidget-shopping-cart">
                            <a href="#" class="lq-shopping-cart-label"></a>
                            <div class="lq-shopping-cart-wrap right-overflow" style="visibility: hidden; opacity: 0;">
                                <div class="lq-shopping-cart-inner">
                                    '.apply_filters( 'lucia_shopping_cart', '' ).'
                                </div>
                            </div>
                        </div>
						
						';
	  
	  if ($display_search_icon == '1' )
        $icons_by_menu .= '<div class="lq-microwidget lq-search" style="z-index:9999;">
                        <div class="lq-search-label"></div>
                        <div class="lq-search-wrap right-overflow" style="display:none;">
                            <form action="" class="search-form">
                                <div>
                                        <span class="screen-reader-text">'.esc_html__( 'Search for', 'lucia' ).':</span>
                                        <input type="text" class="search-field" placeholder="'.esc_html__( 'Search', 'lucia' ).' &hellip;" value="" name="s">
                                        <input type="submit" class="search-submit" value="'.esc_html__( 'Search', 'lucia' ).'">
                                </div>                                    
                            </form>
                        </div>
                    </div>';
					
		if ($display_random_icon == '1' ){
			$random_link = lucia_get_one_random_link();
			$icons_by_menu .= '<div class="lq-microwidget lq-microwidget-random">
								<a href="'.esc_url($random_link).'" class="lq-random-label"></a>
							</div>';
		}
					
      $icons_by_menu .= '</div>';



	  $addClass = ' lq-wp-menu';
	 
	 $class = $class.$addClass;
	  ?>
<nav class="<?php echo esc_attr($class);?>" role="navigation">
  <?php
	
	$custom_menu = apply_filters('lqt_custom_menu', '');
	$args = array(
			'theme_location' => 'top',
			'menu_id'        => 'top-menu',
			'menu_class' => $header_menu_hover_style,
			'fallback_cb'    => false,
			'container' =>'',
			'link_before' => '<span class="menu-item-text">',
   			'link_after' => '</span>',
		);
		
	if( $custom_menu ){
		$args['menu'] = esc_attr($custom_menu);
		$args['theme_location'] = '';
		}
	

	wp_nav_menu( $args );
	if( !has_nav_menu('top') && $custom_menu =='' )
		echo '<ul class="lq-main-nav"></ul>';
	
	?>
<?php
	echo wp_kses_post($icons_by_menu);
?>

</nav>