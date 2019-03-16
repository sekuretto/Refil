<div id="page-<?php the_ID(); ?>">
  <article class="entry-box">
    <div class="entry-main">
      <div class="entry-summary">
	<?php 
	the_content( );
	wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'lucia' ),
				'after'  => '</div>',

			) );
	?>
      </div>
    </div>
  </article>
</div>

