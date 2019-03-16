<?php
	get_header();
?>

<?php echo wp_kses_post(apply_filters('lucia_page_title_bar','',''));?>  

<div class="page-wrap">

<?php do_action('lqt_before_page_wrap');?>  

  <div class="container">

    <div class="page-inner row no-aside">

      <div class="col-main">

        <section class="post-main" role="main" id="content">

          <article class="post-entry text-left">

            <?php do_action('lqt_before_page_content');?>
            <?php

				$page_404 = lucia_option('page_404');

				if( $page_404 > 0 ){

					$post   = get_post( $page_404 );

					echo '<div id="page-'.absint($post->ID).'">

							  <article class="entry-box">

								<div class="entry-main">

								  <div class="entry-summary">

								   <h1>'.wp_kses_post($post->post_title).'</h1>

								'.wp_kses_post($post->post_content).'

								  </div>

								</div>

							  </article>

							</div>';

					}else{

?>

           <h1><?php esc_html_e('404 Nothing Found', 'lucia');?></h1>

<p><?php esc_html_e('Sorry, the page could not be found.', 'lucia');?></p>

<a href="javascript:;" onClick="javascript :history.back(-1);"><span class="lucia-btn lucia-primary"><?php esc_html_e('Go Back', 'lucia');?></span></a>


            <?php }?>

           <?php do_action('lqt_after_page_content');?>         

          </article>
        </section>

      </div>

    </div>

  </div>

</div>
<?php get_footer();