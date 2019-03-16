<?php
	$display_footer_widgets = lucia_option('display_footer_widgets');
	$footer_fullwidth = lucia_option('footer_fullwidth');
	$footer_columns = absint(lucia_option('footer_columns'));
	
	if( $footer_columns == 0 )
		$footer_columns = 4;

	if( $display_footer_widgets == '1' ):
		$css_class = 'footer-widget-area';
	
	if($footer_fullwidth == '1'){
		$css_class .= ' container-fullwidth';
		
	}
	
?>
<div class="<?php echo esc_attr($css_class); ?>">
<?php do_action( 'lqthemes_before_footer_widgets' );?>
     <ul class="lq-list-md-<?php echo esc_attr($footer_columns);?>">
      <?php for ($i = 1; $i <= 4; $i++) : ?>
      <?php if (is_active_sidebar("footer-".$i)) : ?>
		<li>
        <?php dynamic_sidebar("footer-".$i); ?>
        </li>
        <?php endif; ?>
        <?php endfor; ?>
      </ul>
      <?php do_action( 'lqthemes_after_footer_widgets' );?>
    </div>

<?php endif; ?>