<?php
/**
 * Template part for displaying posts with excerpts
 *
 * Used in Search Results and for Recent Posts in Front Page panels.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<h1 class="entry-title">
		<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
	</h1>

	<div class="before-content">

		<?php if ( 'post' === get_post_type() ) : ?>

				<?php if ( !is_single() && get_the_title() === '' ) : ?>

						<span class="clock-icon">
							<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
								<time datetime="<?php the_date( DATE_W3C ); ?>"><?php echo get_the_date(); ?></time>
							</a>
						</span><!-- .clock-icon -->
			
				<?php else : ?>

						<span class="clock-icon">
							<time datetime="<?php the_date( DATE_W3C ); ?>"><?php echo get_the_date(); ?></time>
						</span><!-- .clock-icon -->
					
				<?php endif; ?>

				<span class="author-icon">
					<?php the_author_posts_link(); ?>
				</span><!-- .author-icon -->

		<?php endif; ?>

		<?php if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>

					<span class="comments-icon">
						<?php comments_popup_link(__( 'No Comments', 'frealestate' ), __( '1 Comment', 'frealestate' ), __( '% Comments', 'frealestate' ), '', __( 'Comments are closed.', 'frealestate' )); ?>
					</span><!-- .comments-icon -->
		
		<?php endif; ?>

	</div><!-- .before-content -->

	<?php the_excerpt(); ?>

	<div class="after-content">

		<?php if ( 'post' === get_post_type() ) : ?>

				<?php if ( ! post_password_required() ) : ?>

							<?php if ( has_category() ) : ?>
									<p class="categories-wrapper">
										<span class="category-icon">
											<?php esc_html_e('Categories:', 'frealestate'); ?>
										</span><!-- .category-icon -->
										<?php the_category( ' ' ) ?>
									</p><!-- .categories-wrapper -->						
							<?php endif; ?>
						
							<?php if ( has_tag() ) : ?>
									<p class="tags-wrapper">
										<span class="tags-icon">
											<?php esc_html_e('Tags:', 'frealestate'); ?>
										</span><!-- .tags-icon -->
										<?php the_tags('', _x( ', ', 'Used between list items, there is a space after the comma.', 'frealestate' ), ''); ?>
									</p><!-- .tags-wrapper -->					
							<?php endif; ?>

				<?php endif; // ! post_password_required() ?>

		<?php endif; ?>

		<?php edit_post_link( __( 'Edit', 'frealestate' ), '<span class="edit-icon">', '</span>' ); ?>

	</div><!-- .after-content -->
	
	<div class="separator">
	</div>

</article><!-- #post-## -->
