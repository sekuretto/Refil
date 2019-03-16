<?php /* Template Name: Keskustelu listaus */ ?>

<?php get_header();	?>
<?php echo wp_kses_post(apply_filters('lucia_page_title_bar','','page'));?>  
<?php lucia_container_before_page( 'page_sidebar_layout' ); ?>
<div class="col-main">
 <section class="post-main" role="main" id="content">
<?php do_action('lqt_before_page_content');?>
<?php 


// the query
$the_query = new WP_Query( array('post_type' => 'keskustelu') ); ?>

<?php if ( $the_query->have_posts() ) : ?>

	<!-- pagination here -->

	<!-- the loop -->
	<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
		<div class="keskustelu-kortti" style="background-color:aliceblue;">
		<a href="<?php the_permalink(); ?>" >
			<h2 class="keskustelu-otsikko"><?php the_title(); ?></h2>
			<p><?php echo get_field('aihe');?></p>
			<p><?php echo get_field('kuvaus');?></p>
			<p><?php echo get_field('kuva');?></p>
		</div>
		</a>
	<?php endwhile; ?>
	<!-- end of the loop -->

	<!-- pagination here -->

	<?php wp_reset_postdata(); ?>

<?php else : ?>
	<p><?php esc_html_e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; ?>

<?php do_action('lqt_after_page_content');?>    
</section>
</div>     
<?php lucia_container_after_page( 'page_sidebar_layout', 'page' );?>
<?php do_action('lqt_after_page_wrap');?>

<?php get_footer();