<?php 

	$excerpt_style = lucia_option('excerpt_style');
	$display_feature_image = lucia_option('excerpt_display_feature_image');
	
	if (is_single()){
		
		$display_feature_image = lucia_option('display_feature_image');
	}
	
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-entry' ); ?>>
   <?php 
    	if ( '' !== get_the_post_thumbnail() && $display_feature_image == '1' ) : 
    ?>
    <figure class="entry-image"> <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'lucia_featured_image' ); ?></a> </figure>
    <?php endif; ?>
    
    <div class="entry-main">
      <div class="entry-header">
       
        <a href="<?php the_permalink(); ?>">
        <h1 class="entry-title">
          <?php the_title(); ?>
        </h1>
        </a>
          <?php lucia_posted_on();?>
        
      </div>
      <div class="entry-summary">
       <?php 
            if ( is_single() || $excerpt_style == '1' ) {
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
		'nextpagelink'     => __( '<i class="fa fa-long-arrow-left"></i> Next page', 'lucia' ),
		'previouspagelink' => __( 'Previous page <i class="fa fa-long-arrow-right"></i>', 'lucia' ),
		'pagelink'         => '%',
		'echo'             => 1
	);
 
	wp_link_pages( $args  );
		
	?>
        
      </div>
      <?php if ( !is_single() ) : ?>
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
                
                comments_popup_link( esc_html__('No comments yet', 'lucia' ), esc_html__('1 comment', 'lucia' ), esc_html__('% comments', 'lucia' ), 'comments-link', '');
                
              endif;
              ?>
          </div>
        </div>
      </div>
      
      <?php endif; ?>
    </div>
     <?php if ( is_single() ) : ?>
    <?php lucia_get_post_footer();?>
    <?php endif; ?>
</article>
 <?php if ( is_single() ) : ?>
<div class="post-attributes">
                                                         
    <div class="comments-area text-left">
       <?php
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
			  ?>
         </div>
    </div>
 <?php endif; ?>