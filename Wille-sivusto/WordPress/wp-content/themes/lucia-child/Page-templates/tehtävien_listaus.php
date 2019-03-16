<?php /* Template Name: Tehtävien listaus */ ?>

<?php get_header();	?>
<?php echo wp_kses_post(apply_filters('lucia_page_title_bar','','page'));?>  
<?php lucia_container_before_page( 'page_sidebar_layout' ); ?>
<div class="col-main">
 <section class="post-main" role="main" id="content">
<?php do_action('lqt_before_page_content'); ?>


        <div class="<?php echo esc_attr(lucia_get_blog_style()); ?>">

<h1 style="text-align: center;">Tehtävät</h1>
<form action="<?php $_SERVER['REQUEST_URI'] ?>" method="post">
<input class="archive-btn btn-left" name="valinta" type="submit" value="yksityinen" text="Omat tehtävät"/>
<input class="archive-btn btn-right" name="valinta" type="submit" value="yleinen" text="Yleiset tehtävät" />
</form>
<?php
echo'
	<form  action="http://'.$_SERVER['HTTP_HOST'].'/index.php/tehtava-postaus/">
    <input type="submit" value="Lisää tehtävä" />
	</form>'
	?>
<?php 
// the query arguments

$valinta = $_POST['valinta'];

if(!isset($_POST['valinta'])){
	$valinta = 'yksityinen';
}
if ($valinta == 'yksityinen'){
	$current_user = wp_get_current_user();
	$user_id=$current_user->ID;
	$luokkaargs=[
	'post_type'=>'luokka',
	'posts_per_page' => -1
	];
         $the_query = new WP_Query($luokkaargs);
         // loopataan löytyneet luokat läpi
         if ( $the_query->have_posts() ) {
             while ( $the_query->have_posts() ) {
                  $the_query->the_post();
                  // tallennetaan kaikki käyttäjät, jotka kuuluvat samaan luokkaan kuin sisään kirjautunut käyttäjä
                  $post_id = get_the_ID();
                  $users_array = get_field('kayttajat',$post_id);
				  if(array_search($user_id, $users_array) !== FALSE ){
					$haettavatkayttajat = $users_array;
				}
             }
             /* Restore original Post Data */
             wp_reset_postdata();
         }else {
              echo 'ei luokkia';
        	}

		$args = array(
			'post_type' => 'tehtava',
			'tax_query' => array(
				array(
					'taxonomy' 	=> 'tyyppi',
					'field' 	=> 'slug',
					'terms' 	=> $valinta,

				)
				
				),
				'author__in' => $haettavatkayttajat,
		);
}
else {
	$args = array(
		'post_type' => 'tehtava',
		'tax_query' => array(
			array(
				'taxonomy' 	=> 'tyyppi',
				'field' 	=> 'slug',
				'terms' 	=> $valinta,
			)
		)
	);
}

// the query
$the_query = new WP_Query( $args ); ?>

<?php if ( $the_query->have_posts() ) : ?>

	<!-- pagination here -->

	<!-- the loop -->
	<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
	
	


	<a class="archive-wrapper-link" href="<?php the_permalink(); ?>" >
		<div class="archive-content-container">
                    <h2 class="archive-title"><?php the_title(); ?></h2>

					<img class="archive-image" src="<?php echo get_field('profilepicture'); ?>">
			<p class="archive-paragraph"><b>Osoite:</b> <?php echo get_field('kohde'); ?><br></P>
			<p class="archive-paragraph"><b>Maksu:</b> <?php echo get_field('maksu'); ?> € </P>
			<p class="archive-paragraph"><b>Max tekijät:</b> <?php echo get_field('tekijoiden_lukumaara'); ?></p>
			<p class="archive-paragraph"><b>Julkaisija:</b> <?php  echo get_the_author_meta( 'display_name',  $author_id ); ?></p>
			<p class="archive-description"><b>Kuvaus: </b><br><?php echo get_field('kuvaus'); ?></p>
			<div class="archive-footer">
				<p class="archive-paragraph"><b><?php  echo get_the_date( $format, $post ); ?> </b></P>
				<p class="archive-paragraph"><b><?php  echo get_the_time( $format, $post ); ?> </b></P>
			</div>

		</div>
		</a>
	<?php endwhile; ?>
	<!-- end of the loop -->

	<!-- pagination here -->

	<?php wp_reset_postdata(); ?>

            <?php else:  ?>
            <h1><?php _e( 'Tehtäviä ei vielä lisätty!' ); ?></h1>
            <?php endif; ?>


<?php do_action('lqt_after_page_content');?>    
</section>
</div>     
<?php lucia_container_after_page( 'blog_archives_sidebar_layout', 'archives' );?>
<?php get_footer();