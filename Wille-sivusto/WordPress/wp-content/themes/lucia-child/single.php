<?php /* Template Name: Tehtävien ja matkapakettien listaus */ ?>

<?php get_header();	?>
<?php echo wp_kses_post(apply_filters('lucia_page_title_bar','','page'));?>  
<?php lucia_container_before_page( 'page_sidebar_layout' ); ?>
<div class="col-main">
<section class="post-main" role="main" id="content">
<?php do_action('lqt_before_page_content');?>


	<!-- the loop -->
	<?php while ( have_posts() ) : the_post() ?>
        <?php $type = get_post_type();
        if($type == 'tehtava'){ ?>

            <div class="cpt-container">
            <h2 class="cpt-title"><?php the_title(); ?></h2>
			<img class="cpt-image" src="<?php echo get_field('profilepicture'); ?>">
			<p class="cpt-paragraph"><b>Osoite:</b> <?php echo get_field('kohde'); ?><br></p>
			<p class="cpt-paragraph"><b>Maksu:</b> <?php echo get_field('maksu'); ?> € </p>
			<p class="cpt-paragraph"><b>Max tekijät:</b> <?php echo get_field('tekijoiden_lukumaara'); ?></p>
			<p class="cpt-paragraph"><b>Kuvaus: </b><br><?php echo get_field('kuvaus'); ?></p>
			<div class="cpt-footer">
				<p class="cpt-date"><b><?php  echo get_the_date( $format, $post ); ?> </b></p>
				<p class="cpt-time"><b><?php  echo get_the_time( $format, $post ); ?> </b></p>
				<p class="cpt-author"><b>Julkaisija: <?php  echo get_the_author_meta( 'display_name',  $author_id ); ?></b></p>
			</div>
		    </div>
<?php
		}
        if ($type == 'matkapaketti'){ ?>
        
			<div class="cpt-container">
            <h2 class="cpt-title"><?php the_title(); ?></h2>
			<img class="cpt-image" src="<?php echo get_field('profilepicture'); ?>">
			<h3 class="cpt-subtitle">Kuvaus:</h3>
			<p class="cpt-paragraph"><?php echo get_field('kuvaus'); ?></p>
			<h3 class="cpt-subtitle">Hinta/hlö:</h3>
			<p class="cpt-paragraph"><?php echo get_field('hinta_hlö'); ?> €</p>
			<div class="cpt-footer">
				<p class="cpt-author"><b>Julkaisija: <?php  echo get_the_author_meta( 'display_name',  $author_id ); ?></b></p>
				<p class="cpt-time"><b><?php  echo get_the_time( $format, $post ); ?> </b></p>
				<p class="cpt-date"><b><?php  echo get_the_date( $format, $post ); ?> </b></p>
			</div>
		    </div>
        
<?php
		}
		if ($type == 'luokka'){ ?>
        
			<div class="cpt-container">
            <h2 class="cpt-title"><?php the_title(); ?></h2>
			<img class="cpt-image" src="http://192.168.9.200/wp-content/uploads/2019/01/cheerleading-2173914_1920.jpg" style="width:200px; height:110px; object-fit:cover; float:left; margin:0px 10px 0px 10px; border-radius:5px;">
			<p class="cpt-paragraph"><b>Katuosoite: </b><?php echo get_field('katuosoite'); ?></p>
			<p class="cpt-paragraph"><b>Paikkakunta:</b> <?php echo get_field('paikkakunta'); ?> </p>
			<p class="cpt-paragraph"><b>Ryhmäkoko:</b> <?php echo get_field('ryhmakoko'); ?> hlö</p>
			<p class="cpt-paragraph"><b>Valitut kohteet:</b> <?php echo get_field('valitut_kohteet'); ?> </p>
			<p class="cpt-paragraph"><b>Tulevat tehtävät:</b> <?php echo get_field('tulevat_tehtavat'); ?> </p>
			<p class="cpt-date"><b>Liittymispäivämäärä: </b><?php  echo get_the_date( $format, $post ); ?> </p>
			<p class="cpt-time"><?php  echo get_the_time( $format, $post ); ?> </p>
		    </div>
<?php
		}

        ?>

	<?php endwhile; ?>
	<!-- end of the loop -->
	<?php
		comments_template();
	?>

<?php do_action('lqt_after_page_content');?>    
</section>
</div>     
<?php lucia_container_after_page( 'blog_archives_sidebar_layout', 'archives' );?>
<?php get_footer();