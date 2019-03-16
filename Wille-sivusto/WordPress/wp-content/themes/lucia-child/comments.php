<?php

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php esc_html_e('This post is password protected. Enter the password to view comments.', 'lucia'); ?></p> 
	<?php
		return;
	}
?>


<?php if ( have_comments() ) : ?>
	<?php
	$current_user = wp_get_current_user();
	$user_id=$current_user->ID;
 
		  // etsitään oikea luokka käyttäjän id: perusteella
		  $args= array(
			  'post_type'=>'luokka',
			  'posts_per_page' => -1
			);
		  // haetaan luokka
		   $the_query = new WP_Query($args);
		   // loopataan löytyneet luokat läpi
		   if ( $the_query->have_posts() ) {
			   while ( $the_query->have_posts() ) {
					$the_query->the_post();
					// tallennetaan kaikki käyttäjät, jotka kuuluvat samaan luokkaan kuin sisään kirjautunut käyttäjä
					$users_array = get_field('kayttajat');
					//suoritetaan haku
					if(array_search($user_id, $users_array) !== FALSE ){
						$matched_array = $users_array;
					}
			   }
			   /* Restore original Post Data */
			   wp_reset_postdata();
		   }else {
				echo 'Vakava virhe ota yhteyttä ylläpitäjään.';
			}

	$args = array(
		'post_id' => get_the_ID(),
		'author__in' => $matched_array,
	);
	$comments = get_comments( $args );
	?>

<div class="comment-container">
	<?php
	foreach ( $comments as $comment ) {
		if($comment->comment_parent == 0){

			?>
			<div class="top-level-comment-custom">
			<div class="comment-author-image-custom">
				<?php echo get_wp_user_avatar($comment, 60); ?>
			</div>

					
					<h4 class="comment-author-custom">
						<?php echo $comment->comment_author; ?>
					</h4>
					<p class="comment-content-custom">
						<?php echo $comment->comment_content; ?>
					</p>
					<div class="comment-footer-custom">
						<div class="reply-custom"><?php 
							comment_reply_link( 
								array_merge( 
									array( 
										'add_below' => $add_below, 
										'depth'     => 1, 
										'max_depth' => 2
										) 
										) 
									); ?>
						</div>
						<p class="comment-details-custom">
								<strong><?php echo $comment->comment_date; ?></strong>
						</p>
					</div>
			</div>
			<?php
			$reply_args = array(
				'status' => 'approve', 
				'number' => '5',
				'parent' => $comment->comment_ID,
			);
			$replies = get_comments($reply_args);
			foreach($replies as $child_comment) {
				?>
				<div class="comment-reply-custom">
					<div class="comment-author-image-custom">
						<?php echo get_wp_user_avatar($child_comment, 60); ?>
					</div>
					<h3 class="comment-author-custom">
						<?php echo $child_comment->comment_author; ?>
					</h3>
					<p class="comment-content-custom" ><?php echo $child_comment->comment_content; ?></p>
						<div class="comment-footer-custom">
							<p class="reply-details-custom">
								<strong><?php echo $child_comment->comment_date; ?></strong>
							</p>
						</div>
        		</div>
			<?php
			}
		}
	}?>
</div>
<div class="compose-comment-container">
<!-- comments pagenavi Start. -->
	<?php
	if (get_option('page_comments')) {
		$comment_pages = paginate_comments_links('echo=0');
		if ($comment_pages) {
?>
		<div id="commentnavi">
			<span class="pages"><?php esc_html_e('Comment pages', 'lucia'); ?></span>
			<div id="commentpager">
				<?php echo $comment_pages;?>
				
			</div>
			<div class="fixed"></div>
		</div>
<?php
		}
	}
?>

<?php else :  ?>

	<?php if ( comments_open() ) : ?>

	<?php else : ?>
		<!-- If comments are closed. -->
		<p class="nocomments"></p>

	<?php endif; ?>
<?php endif; ?>

<?php if ( comments_open() ) : ?>

<div id="respond" class="respondbg compose-comment-container">

<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
<p><?php printf(
wp_kses_post(
/* translators: 1: wp_login_url. */
__('Sinun tulee olla <a href="%1$s">kirjautunut sisään</a> jättääksesi kommentin.', 'lucia')),
esc_url(wp_login_url( get_permalink() ))
); ?></p>
<?php else : ?>
<?php 
$commenter = wp_get_current_commenter();
$req = get_option( 'require_name_email' );
$aria_req = ( $req ? " aria-required='true'" : '' );
global $required_text;

$comments_args = array(
'class_submit' => 'submit',
         'comment_notes_before' => '<p class="comment-notes">' .
    esc_html__( 'Your email address will not be published.', 'lucia' ) . ( $req ? $required_text : '' ) .
    '</p>',
        'title_reply'=> esc_html__('Jätä kommentti', 'lucia'),
        'comment_notes_after' => '',
		'fields' => apply_filters( 'comment_form_default_fields', array(

    'author' =>
      '<section class="form-item"><input id="author" class="input-name form-control" name="author" placeholder="'.esc_attr__('Name', 'lucia').'"  type="text" value="' . esc_attr( $commenter['comment_author'] ) .
      '" size="30"' . $aria_req . ' /></section>',

    'email' =>
      '<section class="form-item"><input id="email" class="input-name form-control" name="email" placeholder="'.esc_attr__('Email', 'lucia').'"  type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
      '" size="30"' . $aria_req . ' /></section>',

    'url' =>
      '<section class="form-item"><input id="url" class="input-name form-control" placeholder="'.esc_attr__('Website', 'lucia').'" name="url"  type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
      '" size="30" /></section>'
    )
	
	),
        'comment_field' => '<section class="form-item"><textarea id="comment" name="comment" placeholder="'.esc_attr__('Kommentti', 'lucia').'" rows="8"  class="textarea-comment form-control" aria-required="true"></textarea></section>'
);
?>
<?php comment_form($comments_args);?>

<?php endif;  ?>
</div>
<?php endif;  ?>
</div>