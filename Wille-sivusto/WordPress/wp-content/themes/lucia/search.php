<?php
	get_header();
?> 

<section class="page-title-bar title-left">
    <div class="container">
      <?php lucia_breadcrumbs();?>
        <div class="clearfix"></div>            
    </div>
</section>

<div class="page-wrap">

<?php do_action('lqt_after_archive_wrap');?>

<?php lucia_container_before_page( 'blog_archives_sidebar_layout' ); ?>

<div class="blog-list-wrap">

<?php

	if ( have_posts() ) :

		while ( have_posts() ) : the_post();

		get_template_part( 'template-parts/post/content', 'search' );

	  endwhile;

	else :

	  get_template_part( 'template-parts/post/content', 'none' );

	endif;

?> 

</div>

<?php lucia_get_post_attributes(); ?>                        

<?php lucia_container_after_page( 'blog_archives_sidebar_layout', 'archives' );?>

<?php do_action('lqt_after_archive_wrap');?>

</div>

<?php get_footer();