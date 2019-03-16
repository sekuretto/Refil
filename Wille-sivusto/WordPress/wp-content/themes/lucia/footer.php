<?php

$hide_footer = apply_filters('lucia_hide_footer',0);

if( $hide_footer != '1' ):

?>
<footer class="site-footer">
    <?php

	get_template_part( 'template-parts/footer/site', 'widgets' );

	get_template_part( 'template-parts/footer/site', 'info' );

	?>
  </footer>
<?php endif;?>
</div>
<?php wp_footer(); ?>
</body>
</html>