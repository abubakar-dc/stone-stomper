<?php
/**
 * Template part for displaying posts in an archive
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Stone Stomper
 * @since 1.0.0
 */

list( $sts_var_post_id, $sts_fields, $sts_option_fields ) = StoneStomper::defaults();

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-box column' ); ?>>
	<div class="post-box-img post-image">
		<a href="<?php echo esc_url( get_the_permalink() ); ?>">
			<?php StoneStomper::the_featured_image( $sts_var_post_id, 900 ); ?>
		</a>
	</div>
	<div class="post-content">
		<?php get_template_part( 'partials/post-meta-archive' ); ?>
		<div class="post-box-title post-title">
			<h4 id="post-<?php the_ID(); ?>" class="post-title-archive">
				<a href="<?php echo esc_url( get_the_permalink() ); ?>"> <?php echo esc_html( get_the_title() ); ?></a>
			</h4>
		</div>
		<div class="post-box-excerpt">
			<p><?php echo StoneStomper::excerpt_nomore( 130 ); ?> </p>
		</div>
		<a href="<?php echo esc_url( get_the_permalink() ); ?>" class="learn-more"><?php esc_html_e( 'Learn More', 'basetheme_td' ); ?></a>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
