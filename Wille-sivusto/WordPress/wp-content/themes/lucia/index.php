<?php
	get_header();
?>
<?php echo wp_kses_post(apply_filters('lucia_page_title_bar','','category'));?> 
<?php lucia_container_before_page( 'blog_archives_sidebar_layout' ); ?>
	<div class="col-main">
      <section class="post-main" role="main" id="content">
          <div class="<?php echo esc_attr(lucia_get_blog_style()); ?>">
              
          <?php
              if ( have_posts() ) :
                  while ( have_posts() ) : the_post();
                      get_template_part( 'template-parts/post/content', get_post_format() );
                  endwhile;
              
              else :
                  get_template_part( 'template-parts/post/content', 'none' );
              
              endif;
          ?> 
              
          </div>
          
         <?php lucia_get_post_attributes(); ?> 
                           
      </section>
  </div>
<?php lucia_container_after_page( 'blog_archives_sidebar_layout', 'archives' );?>
<?php get_footer();