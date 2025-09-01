<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Base Theme Package
 * @since 1.0.0
 */

/**
 *
 * If the user tries to load the comments page directly
 * not inside a page or single etc. /1/
 *
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments. /2/
 */

if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && 'comments.php' === basename( esc_url_raw( wp_unslash( $_SERVER['SCRIPT_FILENAME'] ) ) ) ) { /*1*/
		die( esc_html__( 'Please do not load this page directly. Thanks!', 'basetheme_td' ) );
}
if ( post_password_required() ) {
	esc_html__( 'This post is password protected. Enter the password to view comments.', 'basetheme_td' );
	return;
}

?>

<section id="respond">
	<div id="comments" class="comments-area">

		<?php
		// Only show  when comments are available.
		if ( have_comments() ) {
			?>
		<h3 class="section-title" id="comments">
			<?php
				$bst_var_comment_count = get_comments_number();
			if ( '1' === $bst_var_comment_count ) {
				printf(
					/* translators: 1: title. */
					esc_html__( 'One Response', 'basetheme_td' ),
					'<span>' . esc_html( get_the_title() ) . '</span>'
				);
			} else {
				printf(
					/* translators: 1: comment count number, 2: title. */
					esc_html( _nx( '%1$s Response', '%1$s Response', $theme_comment_count, 'comments title', 'basetheme_td' ) ),
					esc_html( number_format_i18n( $theme_comment_count ) ),
					'<span>' . esc_html( get_the_title() ) . '</span>'
				);
			}
			?>
		</h3>
		<div class="navigation">
			<?php the_comments_navigation(); ?>
		</div>
		<ol class="comment-list">

			<?php
				wp_list_comments(
					array(
						'style'       => 'ol',
						'short_ping'  => true,
						'avatar_size' => 60,
					)
				);
			?>

		</ol>
		<div class="navigation">
			<?php the_comments_navigation(); ?>
		</div>
		<?php } else { // this is displayed if there are no comments so far. ?>

			<?php
			// If comments are closed and there are comments, let's leave a little note, shall we?
			if ( ! comments_open() ) {
				?>
					<p class="no-comments"><?php esc_html__( 'Comments are closed.', 'basetheme_td' ); ?></p>
				<?php
			}
		}// Check for have_comments().
			comment_form();
		?>

	</div><!-- #comments -->
</section>
