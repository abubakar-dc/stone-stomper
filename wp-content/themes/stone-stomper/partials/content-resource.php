<?php
/**
 * Template part for displaying single resource
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Stone Stomper
 * @since 1.0.0
 */

list( $sts_var_post_id, $sts_fields, $sts_option_fields ) = StoneStomper::defaults();
// Post Tags & Categories.
$sts_var_post_tags       = get_the_tags( $sts_var_post_id );
$sts_var_post_categories = get_categories( $sts_var_post_id );


$sts_var_post_title = $sts_fields['sts_var_post_title'] ?? get_the_title();

?>

<div class="container-980">
	<div class="wrapper">
		<div class="post-image">
			<a href="<?php the_permalink(); ?>">
			<?php
			if ( ! has_post_thumbnail( $sts_var_post_id ) ) {
				echo '<img class="" src="' . esc_url( get_template_directory_uri() ) . '/assets/build/images/admin/defaults/default-image.webp" >';
			} else {
				echo get_the_post_thumbnail(
					$sts_var_post_id,
					'thumb_900',
				);
			}
			?>
		</a>
		</div><!-- .post-image -->
		<div class="post-meta d-flex justify-content-between align-items-center">
			<!-- /.post-tags -->
			<?php if ( $sts_var_post_categories ) { ?>
				<div class="post-cat">
					<?php foreach ( $sts_var_post_categories as $sts_var_category ) { ?>
						<a href="<?php echo esc_url( get_category_link( $sts_var_category ) ); ?>"><?php echo esc_html( $sts_var_category->name ); ?></a>
					<?php } ?>
				</div>
				<!-- /.post-cat -->
			<?php } ?>
			<div class="post-shares">
				<a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>&amp;t=<?php the_title(); ?>"
					rel="noopener" rel="noreferrer"
					onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><img
						src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/build/images/facebook-icon.svg" alt="Facebook"
						class="post-fb-share"></a>
				<a href="http://www.linkedin.com/shareArticle?mini=true&amp;title=<?php the_title(); ?>&amp;url=<?php the_permalink(); ?>"
					rel="noopener" rel="noreferrer"
					onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><img
						src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/build/images/linkedin-icon.svg" alt="Linked In"
						class="post-li-share"></a>
				<a href="http://twitter.com/intent/tweet?text=Currently reading <?php the_title(); ?>&amp;url=<?php the_permalink(); ?>"
					rel="noopener" rel="noreferrer"
					onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><img
						src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/build/images/twitter-icon.svg" alt="Twitter"
						class="post-tw-share"></a>
			</div>
			<!-- /.post-shares -->
		</div>


		<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-ctn' ); ?>>
				<?php get_template_part( 'partials/content' ); ?>
				<div class="post-details">
					<div class="post-pagination"> <?php the_posts_pagination(); ?> </div>
					<div class="post-comments">
					<?php
							// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
					?>
					</div>
				</div>
				<div class="ts-80"></div>
			</div>

			<?php
			wp_reset_postdata();

			$sts_var_rp_selection_criteria = isset( $sts_fields['sts_var_rp_selection_criteria'] ) ? $sts_fields['sts_var_rp_selection_criteria'] : null;
			if ( 'random' === $sts_var_rp_selection_criteria ) {

				$sts_args = array(
					'posts_per_page' => 3,
					'post__not_in'   => array( $post->ID ),
					'orderby'        => 'rand',
				);

				$sts_query = new WP_Query( $sts_args );

				// The Loop.
				if ( $sts_query->have_posts() ) {
					while ( $sts_query->have_posts() ) {
						$sts_query->the_post();
						// Include specific template for the content.
						get_template_part( 'partials/content', 'archive-post' );
					}
					?>
					<?php
				}
			} else {
				global $post;
				$sts_var_selected_posts = array();
				$sts_var_selected_posts = isset( $sts_fields['sts_var_rp_selected_posts'] ) ? $sts_fields['sts_var_rp_selected_posts'] : null;
				if ( $sts_var_selected_posts ) {

					?>
				<div class="related-posts ">
				<h3><?php esc_html_e( 'Related Posts', 'basetheme_td' ); ?></h3>
							<?php
							foreach ( $sts_var_selected_posts as $sts_var_post_id ) {

								$sts_post_fields = get_fields( $sts_var_post_id );
								if ( ! has_post_thumbnail( $sts_var_post_id ) ) {
									echo '<img class="" src="' . esc_url( get_template_directory_uri() ) . '/assets/build/images/admin/defaults/default-image.webp" >';
								} else {
									echo get_the_post_thumbnail(
										$sts_var_post_id,
										'thumb_900',
									);
								}

								get_template_part( 'partials/content', 'archive-post' );
							}
							?>
			</div>
					<?php
				}
				wp_reset_postdata();
			}
			?>
		</article>
	</div>
</div>
