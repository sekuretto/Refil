<?php /* Template Name: lisää post */ ?>
<?php 

acf_form_head();

get_header();

?>
<div id="content">
	
	<?php
	
	acf_form(array(
		'post_id'		=> 'new_post',
		'new_post'		=> array(
			'post_type'		=> 'luokka',
			'post_status'	=> 'publish'
		)
	));
	
	?>
	
</div>

<?php get_footer(); ?>