<div id="post-<?php the_ID(); ?>" <?php post_class('entry-box-wrap'); ?>>
  <article class="entry-box">
    <div class="entry-header no-img">
     <?php $display_category = lucia_option('excerpt_display_category');?>
     <?php if($display_category == '1' ):?>
          <div class="entry-category"><?php the_category(', '); ?></div>
          <?php endif; ?>
          
      <?php 
		  if ( is_single() ) {
				the_title( '<h2 class="entry-title">', '</h2>' );
			} else {
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			}
			
			if ( 'post' === get_post_type() ) :
				lucia_posted_on();
			endif;

		  ?>
    </div>
    <div class="entry-main">
      <div class="entry-summary">
        <?php 
			if ( is_single() ) {
			the_content( );
		} else {
			the_excerpt();
		}
			?>
        <?php
		  
	$args  = array(
		'before'           => '<p>' . esc_html__( 'Pages:', 'lucia' ),
		'after'            => '</p>',
		'link_before'      => '',
		'link_after'       => '',
		'next_or_number'   => 'number',
		'separator'        => ' ',
		'nextpagelink'     => esc_html__( 'Next page', 'lucia' ),
		'previouspagelink' => esc_html__( 'Previous page', 'lucia' ),
		'pagelink'         => '%',
		'echo'             => 1
	);
 
	wp_link_pages( $args  );
		
	?>
      </div>
    </div>
    <?php if ( !is_single() ) { ?>
    <div class="entry-footer clearfix">
      <div class="pull-left">
        <div class="entry-more"><a href="<?php the_permalink(); ?>">
          <?php esc_html_e('Continue Reading...', 'lucia');?>
          </a></div>
      </div>
      <div class="pull-right">
        <div class="entry-comments">
          <?php
		  if ( comments_open() ) :
			
			comments_popup_link( esc_html__( 'No comments yet', 'lucia' ), esc_html__( '1 comment', 'lucia' ), esc_html__( '% comments', 'lucia' ), 'comments-link', '');
			
		  endif;
		  ?>
        </div>
      </div>
    </div>
    <?php } ?>
  </article>
</div>

<!-- #post-## --> 
