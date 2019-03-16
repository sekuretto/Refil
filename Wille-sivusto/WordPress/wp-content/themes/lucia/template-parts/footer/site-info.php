<?php

	$footer_icons = lucia_option('footer_icons');
	$display_footer_icons = lucia_option('display_footer_icons');
	$copyright = lucia_option('copyright');
	$footer_fullwidth = lucia_option('footer_fullwidth');
	$footer_layout = lucia_option('footer_bottom_layout');
	$footer_style = lucia_option('footer_bottom_style');
	$footer_menu = lucia_option('footer_menu');
	
	$wrap = 'footer-info-area';
	if( $footer_layout != ''){
		$wrap .= ' '.esc_attr($footer_layout);
	}
	
	if($footer_fullwidth == '1'){
		$wrap .= ' container-fullwidth';
	}
	
	$wrap .= ' '.lucia_get_footer_border_style($footer_style);
?>
<div class="<?php echo esc_attr($wrap); ?>">

<?php do_action( 'lqthemes_before_footer_bottom' );?>
    <div class="lq-microwidgets">
     <div class="lq-microwidget site-info">
     <span class="copyright_selective"><?php echo do_shortcode(wp_kses_post($copyright));?></span>
     </div>      
       </div>
      
      <?php 
	if (  $display_footer_icons == '1' || is_customize_preview()):
		$css_class = 'footer-sns lucia-footer-sns footer_icons_selective';
		if( $display_footer_icons !=1 && is_customize_preview() )
			$css_class  .= ' hide';
	
	?>
    <div class="lq-microwidgets">
      <ul class="<?php echo esc_attr($css_class); ?>">
      <?php 
	  if($footer_icons){
	  foreach ($footer_icons as $item ){
		  $item['icon'] = str_replace('fa-','',$item['icon']);
		  $item['icon'] = str_replace('fa ','',$item['icon']);
	  ?>
      <li><a href="<?php echo esc_url($item['link']);?>" title="<?php echo esc_attr($item['title']);?>" target="_blank"><i class="fa fa-<?php echo esc_attr($item['icon']);?>"></i></a></li>
      <?php 
	  }
	  }
	  ?>
      </ul>
      <?php endif;	?>
      
      <?php
	  if( $footer_menu != '' ){
		  echo '<div class="lq-microwidget lq-microwidget-micronav">';
	  $args = array(
			'theme_location' => '',
			'menu_id'        => 'top-menu',
			'menu' => $footer_menu,
			'menu_class' => 'nav navbar-nav',
			'fallback_cb'    => false,
			'container' =>'',
			'link_before' => '<span>',
   			'link_after' => '</span>',
		);

	wp_nav_menu( $args );
	echo ' </div>';
	  }
	  ?>
      </div>
         <?php do_action( 'lqthemes_after_footer_bottom' );?>
</div>
          
          