<?php
/**
 * Template Name: Full Width
 */
	get_header();

?>
<div class="page-wrap">
 <?php do_action('lqt_before_page_wrap');?>  
  <div class="container-fullwidth">
          <article class="post-entry text-left">
            <?php do_action('lqt_before_page_content');?>
            <?php
				/* Start the Loop */
				while ( have_posts() ) : the_post();

					get_template_part( 'template-parts/page/content' );

					the_posts_pagination( array(
					'prev_text' => '<i class="fa fa-arrow-left"></i><span class="screen-reader-text">' . esc_html__( 'Previous page', 'lucia' ) . '</span>',
					'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next page', 'lucia' ) . '</span><i class="fa fa-arrow-right"></i>' ,
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'lucia' ) . ' </span>',
				) );

				endwhile; // End of the loop.
			?>
            <?php do_action('lqt_after_page_content');?>               
          </article>
           <?php if ( comments_open() || get_comments_number()) :?>
          <div class="post-attributes">
         <!--Comments Area-->
            <div class="comments-area text-left">
              <?php

						comments_template();
			  ?>
            </div>            
          </div>
          <?php endif;?>
        </section>
      </div>
      <?php do_action('lqt_after_page_wrap');?>
    </div>

<?php get_footer();