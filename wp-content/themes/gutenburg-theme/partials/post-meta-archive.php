<?php
/**
 * Template part for displaying content of about us page.
 *
 * @link https://developer.wordpress.org/themes/template-files-section/partial-and-miscellaneous-template-files/
 *
 * @package Base Theme Package
 * @since 1.0.0
 */

list($bst_var_author_avatar,$bst_var_author_name) = BaseTheme::get_author_data( get_the_ID() );

// Post Tags & Categories.
$bst_var_post_tag = get_the_tags( get_the_ID() );

?>


<div class="post-box-meta d-flex justify-content-between">
	<div class="post-date">
		<?php the_time( BASETHEME_PROJECT_DTFORMAT ); ?>
	</div>
	<?php if ( $bst_var_post_tag ) { ?>
		<div class="ac-post-cat">
		<?php foreach ( $bst_var_post_tag as $bst_var_category ) { ?>
			<a href="<?php echo esc_url( get_category_link( $bst_var_category ) ); ?>"><?php echo esc_html( $bst_var_category->name ); ?></a>
		<?php } ?>
		</div>
	<?php } ?>
</div>
