<?php

	$class = 'lq-navigation';
	$display_shopping_cart_icon = lucia_option('display_shopping_cart_icon');
	$header_menu_hover_style = ' lq-main-nav lq-nav-left';
	$addClass = '';
	$icons_by_menu = '<div class="lq-microwidgets">';

	if ($display_shopping_cart_icon == '1')

    	$icons_by_menu .= '<div class="lq-microwidget lq-shopping-cart" style="z-index:9999;">

						<a href="#" class="lq-shopping-cart-label"></a>

                        <div class="lq-shopping-cart-wrap left-overflow">

                            <div class="lq-shopping-cart-inner">

                                <ul class="cart_list product_list_widget empty">

                                    <li>'.apply_filters('lucia_shopping_cart',esc_html__( 'No products in the cart.', 'lucia' )).'</li>

                                </ul>

                            </div>

                        </div>

                    </div>';

      $icons_by_menu .= '</div>';
	  $addClass = ' lq-wp-menu';

	  ?>

<nav class="<?php echo esc_attr($class.$addClass);?>" role="navigation">

  <?php
		   wp_nav_menu( array(

			'theme_location' => 'top-left',

			'menu_id'        => 'top-menu-left',

			'menu_class' => $header_menu_hover_style,

			'fallback_cb'    => false,

			'container' =>'',

			'link_before' => '<span>',

   			'link_after' => '</span>',

		) );

echo wp_kses_post($icons_by_menu);

?>
</nav>