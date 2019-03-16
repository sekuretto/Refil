<?php
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function lucia_posted_on() {
	
	$display_category = lucia_option('excerpt_display_category');
	$display_author = lucia_option('excerpt_display_author');
	$display_date = lucia_option('excerpt_display_date');
	
	if (is_single()){
		$display_category = lucia_option('display_category');
		$display_author = lucia_option('display_author');
		$display_date = lucia_option('display_date');
	}

	?>
<div class="entry-meta">
           <?php if($display_date == '1' ):?>
              <span class="entry-date updated"><i class="fa fa-calendar-o" aria-hidden="true"></i> <a href="<?php echo esc_url(get_month_link(get_the_time('Y'), get_the_time('m')));?>"><?php echo esc_attr(get_the_date());?></a></span> 
              <?php endif; ?>
              <?php if($display_author == '1' ):?>
              <span class="entry-author author vcard" rel="author"><i class="fa fa-user-o" aria-hidden="true"></i> <span class="fn"> <?php the_author_posts_link();?></span></span>
              <?php endif; ?>
         <?php if($display_category == '1' ):?>
        <span class="entry-category"> <i class="fa fa-folder-o" aria-hidden="true"></i> 
          <?php the_category(', '); ?>
        </span>
        <?php endif; ?>
        </div>
              
             
    <?php
}

/**
 * Returns an accessibility-friendly link to edit a post or page.
 */
function lucia_edit_link() {

	$link = edit_post_link(
		sprintf(
		/* translators: 1: title. */
			__( 'Edit<span class="screen-reader-text"> "%1$s"</span>', 'lucia' ),
			get_the_title()
		),
		'<span class="edit-link">',
		'</span>'
	);

	return $link;
}

/**
 *  Custom comments list
 */	
function lucia_comment($comment, $args, $depth) {

?>

<li <?php comment_class("comment media-comment"); ?> id="comment-<?php comment_ID() ;?>">
  <article class="comment-body">
      <footer class="comment-meta">
          <div class="comment-author vcard">
             <?php echo get_avatar($comment,'100','' ); ?>
              <b class="fn"><?php echo get_comment_author_link();?></b>
              <span class="says"><?php esc_html_e('says','lucia') ;?>:</span>
          </div>
          <div class="comment-metadata">
                  <time datetime="<?php echo esc_attr(get_the_modified_date( DATE_W3C ));?>"><?php comment_date(); ?></time> <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ;?>
          </div>
      </footer>
      <div class="comment-content">
      <?php if ($comment->comment_approved == '0') : ?>
                   <em><?php esc_html_e('Your comment is awaiting moderation.','lucia') ;?></em>
                   <br />
                <?php endif; ?>
         <?php comment_text() ;?>
      </div>
  </article>
</li>
                            
<?php
	}
	
/**
 * Returns breadcrumbs.
 */
function lucia_breadcrumbs() {
	$delimiter = '/'; 
	$before = '<span class="current">';
	$after = '</span>';
	if ( !is_home() && !is_front_page() || is_paged() ) {
		echo '<div itemscope itemtype="http://schema.org/WebPage" id="crumbs"><i class="fa fa-home"></i>';
		global $post;
		$homeLink = esc_url(home_url());
		echo ' <a itemprop="breadcrumb" href="' . $homeLink . '">' . esc_html__( 'Home' , 'lucia' ) . '</a> ' . $delimiter . ' ';
		if ( is_category() ) {
			global $wp_query;
			$cat_obj = $wp_query->get_queried_object();
			$thisCat = $cat_obj->term_id;
			$thisCat = get_category($thisCat);
			$parentCat = get_category($thisCat->parent);
			if ($thisCat->parent != 0){
				$cat_code = get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' ');
				echo $cat_code = str_replace ('<a','<a itemprop="breadcrumb"', $cat_code );
			}
			echo $before . '' . single_cat_title('', false) . '' . $after;
		} elseif ( is_day() ) {
			echo '<a itemprop="breadcrumb" href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . esc_attr(get_the_time('Y')) . '</a> ' . $delimiter . ' ';
			echo '<a itemprop="breadcrumb"  href="' . esc_url(get_month_link(get_the_time('Y'),get_the_time('m'))) . '">' . esc_attr(get_the_time('F')) . '</a> ' . $delimiter . ' ';
			echo $before . esc_attr(get_the_time('d')) . $after;
		} elseif ( is_month() ) {
			echo '<a itemprop="breadcrumb" href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . esc_attr(get_the_time('Y')) . '</a> ' . $delimiter . ' ';
			echo $before . esc_attr(get_the_time('F')) . $after;
		} elseif ( is_year() ) {
			echo $before . esc_attr(get_the_time('Y')) . $after;
		} elseif ( is_single() && !is_attachment() ) {
			if ( get_post_type() != 'post' ) {
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite;
				echo '<a itemprop="breadcrumb" href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
				echo $before . esc_attr(get_the_title()) . $after;
			} else {
				$cat = get_the_category(); $cat = $cat[0];
				$cat_code = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
				echo $cat_code = str_replace ('<a','<a itemprop="breadcrumb"', $cat_code );
				echo $before . esc_attr(get_the_title()) . $after;
			}
		} elseif ( !is_single() && !is_page() && get_post_type() != 'post' ) {
			$post_type = get_post_type_object(get_post_type());
			if ($post_type)
			echo $before . $post_type->labels->singular_name . $after;
		} elseif ( is_attachment() ) {
			$parent = get_post($post->post_parent);
			$cat = get_the_category($parent->ID); $cat = isset($cat[0])?$cat[0]:'';
			echo '<a itemprop="breadcrumb" href="' . esc_url(get_permalink($parent)) . '">' . esc_attr($parent->post_title) . '</a> ' . $delimiter . ' ';
			echo $before . esc_attr(get_the_title()) . $after;
		} elseif ( is_page() && !$post->post_parent ) {
			echo $before . esc_attr(get_the_title()) . $after;
		} elseif ( is_page() && $post->post_parent ) {
			$parent_id  = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page = get_page($parent_id);
				$breadcrumbs[] = '<a itemprop="breadcrumb" href="' .esc_url( get_permalink($page->ID)) . '">' . esc_attr(get_the_title($page->ID)) . '</a>';
				$parent_id  = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
			echo $before . esc_attr(get_the_title()) . $after;
		} elseif ( is_search() ) {
			echo $before ;
			printf( 
			/* translators: 1: search. */
			esc_html__( 'Search Results for: %1$s', 'lucia' ),  get_search_query() );
			echo  $after;
		} elseif ( is_tag() ) {
			echo $before ;
			printf(
			/* translators: 1: title. */
			 esc_html__( 'Tag Archives: %1$s', 'lucia' ), single_tag_title( '', false ) );
			echo  $after;
		} elseif ( is_author() ) {
			global $author;
			$userdata = get_userdata($author);
			echo $before ;
			printf( 
			/* translators: 1: display_name. */
			esc_html__( 'Author Archives: %1$s', 'lucia' ),  $userdata->display_name );
			echo  $after;
		} elseif ( is_404() ) {
			echo $before;
			esc_html_e( 'Not Found', 'lucia' );
			echo  $after;
		}
		if ( get_query_var('paged') ) {
			if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() )
				echo sprintf( 
				/* translators: 1: page. */
				 esc_html__( '( Page %1$s )', 'lucia' ),
				 get_query_var('paged')
				 );
		}
		echo '</div>';
	}
}

/**
 * Get sidebar
 */
function lucia_get_sidebar($layout,$type){
	if($layout=='' || $layout == 'none' || $layout == 'no' )
		return '';
	?>
	<div class="col-aside-<?php echo $layout; ?>">
    <?php do_action('lqt_before_sidebar');?>
      <aside class="blog-side left text-left">
          <div class="widget-area">
             <?php get_sidebar($type);?>
          </div>
        </aside>
        <?php do_action('lqt_after_sidebar');?>
      </div>
<?php
	}
	
/**
 * Add script to the footer
 *
 */
function lucia_footer_script(){
	
	$display_scroll_to_top = lucia_option('display_scroll_to_top');
	$scroll_btn_position = lucia_option('scroll_btn_position');
	
	if($display_scroll_to_top=='1' ){
		$css_class = 'back-to-top';
		$css_class .= ' '.$scroll_btn_position;
		
		echo '<div class="'.$css_class.'"></div>';
		}

 } 
add_action('wp_footer','lucia_footer_script');

/**
 * Add title bar
 *
 */
function lucia_page_title_bar( $content, $type='page' ){
	
	$display_titlebar_default   = lucia_option('display_titlebar');
	$display_breadcrumb_default = lucia_option('display_breadcrumb');
	
	
	
	$display_titlebar = apply_filters( 'lqt_display_titlebar', $display_titlebar_default );
	$display_breadcrumb = apply_filters( 'lqt_display_breadcrumb', $display_breadcrumb_default );
	
	if( $display_titlebar == 'default' )
		$display_titlebar   = $display_titlebar_default;
	
	if( $display_breadcrumb == 'default' )
		$display_breadcrumb   = $display_breadcrumb_default;
	
	if( $display_titlebar != '1' )
		return '';
	
    $title_bar_layout_default = lucia_option('title_bar_layout');
	$title_bar_layout = apply_filters('lqt_title_bar_layout',$title_bar_layout_default);
	
	if( $title_bar_layout == 'default' )
		$title_bar_layout   = $title_bar_layout_default;
	
	$title_bar_css = apply_filters('lqt_title_bar_css', '' );
	
		
	$class = 'page-title-bar '.$title_bar_layout;
	$html = '<section class="'.$class.'" style="'.$title_bar_css.'">';
	$html .= '<div class="lq-container">';
	$html .= '<div class="page-title-bar-inner">';
	 
   	$html .= ' <hgroup class="page-title">';
	if ( class_exists( 'WooCommerce' ) && function_exists('is_shop') && is_shop()){
		$html .= '<h1 class="woocommerce-products-header__title page-title">'. woocommerce_page_title(false).'</h1>';
	}
    elseif ( class_exists( 'WooCommerce' ) && (is_product_category() || is_product_tag()) ){
        $html .= '<h1 class="woocommerce-products-header__title page-title">'.single_term_title('',false).'</h1>';
	}elseif( is_home() ){
		 $header_custom_text = lucia_option('header_custom_text');
		$html .= '<h2>'.wp_kses_post( $header_custom_text ).'</h2>';
	}elseif(  is_single() ){
		$html .= '<h2>'.esc_attr(get_the_title()).'</h2>';
	}elseif(is_singular()){
   		$html .= '<h1>'.esc_attr(get_the_title()).'</h1>';
	}elseif(is_category()){
   		$html .= '<h1>'.esc_attr(single_cat_title('', false)).'</h1>';
	}elseif(is_archive()){
   		$html .= '<h1>'.esc_attr(get_the_archive_title()).'</h1>';
	}
	
    $html .= '</hgroup>';

	 
	 if( $display_breadcrumb == '1' ){
		$html .= '<div class="breadcrumb-nav">';
		ob_start();
		lucia_breadcrumbs();
		$html .= ob_get_contents();
		ob_end_clean();
		$html .= '</div>';
	 }
	
	$html .= '</div>';
	$html .= '</div>';
	$html .= '</section>';

	return $html;
	
	}

add_filter( 'lucia_page_title_bar', 'lucia_page_title_bar', 10, 2 );


/**
 * Add menu shoping cart
 *
 */
function lucia_add_cart_single_ajax() {
	
	$html = '';
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
		ob_start();
		the_widget( 'WC_Widget_Cart' );
		$html = ob_get_clean();
	}
	return $html;
}
add_filter('lucia_shopping_cart','lucia_add_cart_single_ajax', 10, 2);

/**
 * Container before page content
 *
 */
 
function lucia_container_before_page( $layout ){
  
	$sidebar_layout = apply_filters('lqthemes_page_sidebar_layout', lucia_option( $layout ));
	
	switch($sidebar_layout){
		case 'left':
			$aside_class = 'left-aside';
		break;
		case 'right':
			$aside_class = 'right-aside';
		break;
		default:
			$aside_class = 'no-aside';
		break;
		
		};
		
	$html = '<main id="main" class="page-wrap '.$aside_class.'">
            <div class="lq-container">
                <div class="lq-row">';

		echo $html;
	
	}
/**
 * Container after page content
 *
 */
 function lucia_container_after_page( $layout, $type = 'page' ){
	 
	$sidebar_layout = apply_filters('lqthemes_page_sidebar_layout',lucia_option( $layout ));

	lucia_get_sidebar($sidebar_layout,$type);
                        
       echo '</div>
                </div>
            </div>  
        </main>';
	}

/**
 * Footer of single page content
 *
 */
function lucia_get_post_footer(){

	echo '<div class="entry-footer clearfix">';
    echo '<div class="pull-left"> ';

	if(get_the_tag_list()) {
		echo get_the_tag_list( esc_html__( 'Tags: ', 'lucia' ),', ');
	}

    echo '</div>';
    echo '</div>';

	}

/**
 * Get post attributes
 *
 */
function lucia_get_post_attributes(){
	?>
   <nav class="navigation pagination" role="navigation">
                                <div class="nav-links">
<?php the_posts_pagination( array(
					'prev_text' => '<i class="fa fa-arrow-left"></i><span class="screen-reader-text">' . esc_html__( 'Previous page', 'lucia' ) . '</span>',
					'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next page', 'lucia' ) . '</span><i class="fa fa-arrow-right"></i>' ,
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'lucia' ) . ' </span>',
				) );?>
</div>
</nav>
    <?php
}

/**
 * Get blog list style css class
 *
 */
function lucia_get_blog_style(){
	
	$blog_style = absint(lucia_option( 'blog_list_style'));
	$wrap_class = '';
	switch($blog_style){
		case '1':
			$wrap_class = 'blog-list-wrap';
		break;
		case '2':
			$wrap_class = 'blog-list-wrap blog-aside-image';
		break;
		case '3':
			$wrap_class = 'blog-list-wrap blog-grid';
		break;
		default:
			$wrap_class = 'blog-list-wrap';
		break;
		
		};
	return $wrap_class;
	}
	
add_filter( 'comment_form_fields', 'lucia_move_comment_field' );
function lucia_move_comment_field( $fields ) {
    $comment_field = $fields['comment'];
    unset( $fields['comment'] );
    $fields['comment'] = $comment_field;
    return $fields;
}

/*
 * Add admin about page
 */
function lucia_admin_menu(){
	
	add_theme_page( esc_html__( 'About Lucia', 'lucia' ), esc_html__( 'About Lucia', 'lucia' ), 'manage_options', 'about-lucia','lucia_about_lucia');
	
	}
add_action( 'admin_menu', 'lucia_admin_menu' );

function lucia_about_lucia(){
	
	?>

    <div class="lucia-info-wrap">
  <h1><?php  esc_html_e( 'Welcome to Lucia WordPress Theme', 'lucia' ) ?></h1>
  <p>
  <?php  esc_html_e( 'Lucia is the perfect theme which could be used to build one page sites for design agency, corporate, restaurant, personal, showcase, magazine, portfolio, ecommerce, etc. The theme is compatible with Elementor, the most popular drag & drop page builder, which you could use to create elegant sites with no code knowledge. We have designed various specific elements and elegant frontpage template for the plugin which can help you create a site like the demo with just several steps. Lucia also offers various options for header, footer, pages & posts, etc. And it is compatible with WooCommerce, Polylang, WPML, Contact Form 7, etc.', 'lucia' ) ?>
  </p>
  <div class="lucia-column-left">
    <div class="lucia-message">
      <h2><?php esc_html_e( 'Import demo sites', 'lucia' ); ?></h2>
      
      <?php
	   if ( function_exists( 'is_plugin_active' ) && is_plugin_active('lqthemes-companion/lqthemes-companion.php') ) {
    			
			?>
      <p><?php  printf(
	  wp_kses_post(
	  /* translators: 1: lqthemes-sites page. */
	  __( 'Lucia offers a free library of <a href="%1$s">demo sites</a>. Import your favorite one by just one click.', 'lucia' )),esc_url(admin_url('themes.php?page=lqthemes-sites'))
	  
	  ); ?></p>
      <?php }else{?>
      		 <p><?php esc_html_e( 'Lucia offers a free library of demo sites. Import your favorite one by just one click.', 'lucia' ); ?></p>
      <?php }?>
      <?php
	   if ( function_exists( 'is_plugin_active' ) && !is_plugin_active('lqthemes-companion/lqthemes-companion.php') ) {
    			
			?>
      <p><a href="<?php echo esc_url(admin_url('themes.php?page=tgmpa-install-plugins&plugin_status=install'));?>" class="button"><?php esc_html_e( 'Install the plugins', 'lucia' ); ?></a></p>
      <?php }?>
    </div>
    <div class="lucia-message">
  <h2><?php esc_html_e( 'Start to customize your site', 'lucia' ); ?></h2>
  <ul class="lucia-customize-list">
    
<li>
      <div class="lucia-customize-box">
        <h4><?php esc_html_e( 'Upload Your Logo', 'lucia' ); ?></h4>
        <p class="lucia-customize-desc"><?php esc_html_e( 'Add your own logo for the header.', 'lucia' ); ?></p>
        <p class="lucia-customize-link"><a target="_blank" href="<?php echo esc_url(admin_url('customize.php?autofocus%5Bcontrol%5D=custom_logo'));?>"><?php esc_html_e( 'Navigate to the option', 'lucia' ); ?></a></p>
      </div>
    </li>
<li>
      <div class="lucia-customize-box">
        <h4><?php esc_html_e( 'Upload Favicon', 'lucia' ); ?></h4>
        <p class="lucia-customize-desc"><?php esc_html_e( 'Set the icon that would display as browser and app icon.', 'lucia' ); ?></p>
        <p class="lucia-customize-link"><a target="_blank" href="<?php echo esc_url(admin_url('customize.php?autofocus%5Bcontrol%5D=site_icon'));?>"><?php esc_html_e( 'Navigate to the option', 'lucia' ); ?></a></p>
      </div>
    </li>
<li>
      <div class="lucia-customize-box">
        <h4><?php esc_html_e( 'Sidebar Settings', 'lucia' ); ?></h4>
        <p class="lucia-customize-desc"><?php esc_html_e( 'Set sidebar for pages & posts.', 'lucia' ); ?></p>
        <p class="lucia-customize-link"><a target="_blank" href="<?php echo esc_url(admin_url('customize.php?autofocus%5Bcontrol%5D=lucia[page_sidebar_layout]'));?>"><?php esc_html_e( 'Navigate to the option', 'lucia' ); ?></a></p>
      </div>
    </li>
<li>
      <div class="lucia-customize-box">
        <h4><?php esc_html_e( 'Blog Settings', 'lucia' ); ?></h4>
        <p class="lucia-customize-desc"><?php esc_html_e( 'Set contents display in archive pages & posts.', 'lucia' ); ?></p>
        <p class="lucia-customize-link"><a target="_blank" href="<?php echo esc_url(admin_url('customize.php?autofocus%5Bcontrol%5D=lucia[display_feature_image]'));?>"><?php esc_html_e( 'Navigate to the option', 'lucia' ); ?></a></p>
      </div>
    </li><li>
      <div class="lucia-customize-box">
        <h4><?php esc_html_e( 'Typography Settings', 'lucia' ); ?></h4>
        <p class="lucia-customize-desc"><?php esc_html_e( 'Choose your own typography for any parts of your website.', 'lucia' ); ?></p>
        <p class="lucia-customize-link"><a target="_blank" href="<?php echo esc_url(admin_url('customize.php'));?>"><?php esc_html_e( 'Navigate to the option', 'lucia' ); ?></a></p>
      </div>
    </li>
    
    <li>
      <div class="lucia-customize-box">
        <h4><?php esc_html_e( 'Top Bar Options', 'lucia' ); ?></h4>
        <p class="lucia-customize-desc"><?php esc_html_e( 'Set info for the top bar above header.', 'lucia' ); ?></p>
        <p class="lucia-customize-link"><a target="_blank" href="<?php echo esc_url(admin_url('customize.php?autofocus%5Bcontrol%5D=lucia[display_topbar]'));?>"><?php esc_html_e( 'Navigate to the option', 'lucia' ); ?></a></p>
      </div>
    </li>
    
    <li>
      <div class="lucia-customize-box">
        <h4><?php esc_html_e( 'Header Options', 'lucia' ); ?></h4>
        <p class="lucia-customize-desc"><?php esc_html_e( 'Set layout for the default header.', 'lucia' ); ?></p>
        <p class="lucia-customize-link"><a target="_blank" href="<?php echo esc_url(admin_url('customize.php?autofocus%5Bcontrol%5D=lucia[header_style]'));?>"><?php esc_html_e( 'Navigate to the option', 'lucia' ); ?></a></p>
      </div>
    </li>
    
    <li>
      <div class="lucia-customize-box">
        <h4><?php esc_html_e( 'Footer Widgets Options', 'lucia' ); ?></h4>
        <p class="lucia-customize-desc"><?php esc_html_e( 'Choose to display & customize the widget areas in the footer.', 'lucia' ); ?></p>
        <p class="lucia-customize-link"><a target="_blank" href="<?php echo esc_url(admin_url('customize.php?autofocus%5Bcontrol%5D=lucia[display_footer_widgets]'));?>"><?php esc_html_e( 'Navigate to the option', 'lucia' ); ?></a></p>
      </div>
    </li>
    
     <li>
      <div class="lucia-customize-box">
        <h4><?php esc_html_e( 'Footer Info Options', 'lucia' ); ?></h4>
        <p class="lucia-customize-desc"><?php esc_html_e( 'Insert copyright info and social icons in the footer.', 'lucia' ); ?></p>
        <p class="lucia-customize-link"><a target="_blank" href="<?php echo esc_url(admin_url('customize.php?autofocus%5Bcontrol%5D=lucia[display_footer_icons]'));?>"><?php esc_html_e( 'Navigate to the option', 'lucia' ); ?></a></p>
      </div>
    </li>

    
  </ul>
</div>
  </div>
  <div class="lucia-column-right">
    <div class="lucia-message"><h4><?php esc_html_e( 'Review Lucia on WordPress', 'lucia' ); ?></h4><p><?php esc_html_e( 'We are grateful that you have chose our theme. If you like Lucia, please take 1 minitue to post your review on Wordpress. Few words of ppreciation also motivates the development team.', 'lucia' ); ?></p><p><a class="button" target="_blank" href="https://wordpress.org/support/theme/lucia/reviews/#new-post"> <?php esc_html_e( 'Post Your Review', 'lucia' ); ?> </a></p></div>
    <div class="lucia-message"><p><?php esc_html_e( 'More info could be found at the manual.', 'lucia' ); ?></p><p><a class="button" target="_blank" href="https://lqthemes.com/lucia-documentation/"><?php esc_html_e( 'Step-by-step Manual', 'lucia' ); ?></a></p></div>
    <div class="lucia-message"><p><?php esc_html_e( 'If you have checked the documentation and still having an issue, please post in the support thread.', 'lucia' ); ?></p><p><a class="button" target="_blank" href="https://wordpress.org/support/theme/lucia"><?php esc_html_e( 'Support Thread', 'lucia' ); ?></a></p></div>
    <div class="lucia-message">
      <h4><?php esc_html_e( 'FAQ', 'lucia' ); ?></h4>
      <p><a class="" target="_blank" href="https://lqthemes.com/faq/#1"><?php esc_html_e( 'How to Create Child Theme?', 'lucia' ); ?></a></p>
      <p><a class="" target="_blank" href="https://lqthemes.com/faq/#2"><?php esc_html_e( 'How to Add Custom CSS to Your Website?', 'lucia' ); ?></a></p>
      <p><a class="" target="_blank" href="https://lqthemes.com/faq/#3"><?php esc_html_e( 'How to Translate the Theme?', 'lucia' ); ?></a></p>
      <p><a class="" target="_blank" href="https://lqthemes.com/faq/#4"><?php esc_html_e( 'How to Make Your Site Multilingual?', 'lucia' ); ?></a></p>
      <p><a class="" target="_blank" href="https://lqthemes.com/faq/#5"><?php esc_html_e( 'How to Make Your Site Multilingual?', 'lucia' ); ?>x</a></p>
    </div>
  </div>
</div>
    <?php	
}