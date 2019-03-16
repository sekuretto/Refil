<?php 

$display_topbar = lucia_option('display_topbar');
$topbar_menu = lucia_option('topbar_menu');
$topbar_style = lucia_option('topbar_style');
$topbar_visibility = lucia_option('topbar_visibility');
$topbar_template_part = lucia_option('topbar_template_part');

if( $display_topbar == '1' ):

	$topbar_css_class = 'lq-top-bar';

	$topbar_css_class .= ' '.lucia_get_border_style($topbar_style);

	if( $topbar_visibility == '1' )

		$topbar_css_class .= ' mobile-hide';

	if( $topbar_visibility == '2' )

		$topbar_css_class .= ' tablet-hide';

?>
    <div class="<?php echo esc_attr($topbar_css_class);?>">

    <?php do_action('lqthemes_before_top_bar');?>
       <?php

			$topbar_left = lucia_option('topbar_left');
			
			if(is_array($topbar_left) && !empty($topbar_left)):
				$html = '<div class="lq-microwidgets">';
  				foreach($topbar_left as $item):
					$html .= '<div class="lq-microwidget lq-microwidget-info">';
					if($item['link']!=''){
						$html .= '<a href="'.esc_url($item['link']).'" target="'.esc_attr($item['target']).'">';
					}

					if($item['icon']!=''){
						$html .= '<i class="fa '.esc_attr($item['icon']).'"></i>&nbsp;&nbsp;';
					}

					$html .= esc_attr($item['text']);
					if($item['link']!=''){
						$html .= '</a>';
					}
					$html .= '</div>';
				endforeach;
				$html .= '</div>';
				echo $html;
			endif;
				?>

    <div class="lq-microwidgets">
   <?php

	if( $topbar_menu != '' ){
		echo '<div class="lq-microwidget lq-microwidget-micronav">';
		$args = array(

				'theme_location' => '',
				'menu' => esc_attr( $topbar_menu ),
				'menu_id'        => 'top-menu',
				'menu_class' => '',
				'fallback_cb'    => false,
				'container' =>'',
				'link_before' => '<span>',
				'link_after' => '</span>',
			);

		wp_nav_menu( $args );
		echo '</div>';
	}

	?>
       <?php

			$topbar_icons = lucia_option('topbar_icons');
			if(is_array($topbar_icons) && !empty($topbar_icons)):
				$html = '<div class="lq-microwidget lq-microwidget-social"> ';
  				foreach($topbar_icons as $item):
					if($item['icon']!=''){
						$html .= '<a href="'.esc_url($item['link']).'" target="'.esc_attr($item['target']).'"><i class="fa '.esc_attr($item['icon']).'"></i></a>';
					}

				endforeach;
				$html .= '</div>';
				echo $html;
				
			endif;

				?>
    </div>
     <?php do_action('lqthemes_after_top_bar');?>
  </div>
  <?php endif;?>