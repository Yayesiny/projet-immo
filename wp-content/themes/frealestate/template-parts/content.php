<?php
/**
 * The default template for displaying content
 *
 * Used for single, index, archive, and search contents.
 *
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( is_single() ) : ?>

			<h1 class="entry-title">
				<?php the_title(); ?>
			</h1>

	<?php else : ?>
	
			<h1 class="entry-title">
				<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
			</h1>
	
	<?php endif; ?>

	<div class="before-content">
		<span class="author-icon">
			<?php the_author_posts_link(); ?>
		</span><!-- .author-icon -->
		
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
		
		<?php if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>

					<span class="comments-icon">
						<?php comments_popup_link(__( 'No Comments', 'frealestate' ), __( '1 Comment', 'frealestate' ), __( '% Comments', 'frealestate' ), '', __( 'Comments are closed.', 'frealestate' )); ?>
					</span><!-- .comments-icon -->
		
		<?php endif; ?>
	</div><!-- .before-content -->

	<?php if ( is_single() ) : ?>

				<div class="content">
					<?php
						if ( has_post_thumbnail() ) :

							the_post_thumbnail();

						endif;
						
						the_content( __( 'Read More...', 'frealestate') );
					?>
				</div><!-- .content -->

	<?php else : ?>

				<div class="content">
					<?php if ( has_post_thumbnail() ) : ?>
								
								<a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php the_title_attribute(); ?>">
									<?php the_post_thumbnail(); ?>
								</a>
								
					<?php endif;

						  the_content( __( 'Read More', 'frealestate') );
					?>
				</div><!-- .content -->

	<?php endif; ?>

	<div class="after-content">	

		<?php if ( ! post_password_required() ) : ?>

					<?php if ( has_category() ) : ?>
							<span class="category-icon">
								<?php the_category( _x( ', ', 'Used between list items, there is a space after the comma.', 'frealestate' ) ) ?>
							</span><!-- .category-icon -->						
					<?php endif; ?>
				
					<?php if ( has_tag() ) : ?>
							<span class="tags-icon">
								<?php the_tags( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'frealestate' ), '' ); ?>
							</span><!-- .tags-icon -->						
					<?php endif; ?>

		<?php endif; // ! post_password_required() ?>
		
		<?php edit_post_link( __( 'Edit', 'frealestate' ), '<span class="edit-icon">', '</span>' ); ?>

	</div><!-- .after-content -->
	
</article><!-- #post-## -->
