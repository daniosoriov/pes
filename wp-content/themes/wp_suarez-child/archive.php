<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package cshero
 */
get_header(); ?>

<?php
/* After a small problem displaying the title of the archives page, 
 * I have to do this awful solution for the moment. Hopefully a better
 * solution will come in the future.
 * It is important to take off the page title for the archives page on the
 * Suarez theme.
 *
 * This solution has to be adapted to the general layout of the theme.
 * This solution is not scalable because if the theme changes, this title
 * will be outdated, so it only works as long as it doesn't change the general
 * layout.
 *
 * If the title layout does indeed change, there has to be a change here org
 * try to see if the changes are normally seen here by default without hardcoding.
 */
?>
<section class="cs-content-header">
    <div id="cs-page-title-wrapper" class="cs-page-title stripe-parallax-bg">
        <div class="container">
            <div id="title-animate" class="row" style="opacity: 1;">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="title_bar">
                        <h1 class="page-title"><? the_archive_title(); ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php global $smof_data, $breadcrumb;
$blog_layout = cshero_generetor_blog_layout(); ?>
<section id="primary" class="content-area<?php if ( $breadcrumb == '0' ) {
	echo ' no_breadcrumb';
}; ?><?php echo esc_attr( $blog_layout->class ); ?> archive-php">
    <div class="container">
        <div class="row">
			<?php if ( $blog_layout->left_col ): ?>
                <div class="left-wrap <?php echo esc_attr( $blog_layout->left_col ); ?>">
					<?php get_sidebar(); ?>
                </div>
			<?php endif; ?>
            <div class="content-wrap <?php echo esc_attr( $blog_layout->blog ); ?>">

                <main id="main" class="site-main" role="main">

					<?php if ( have_posts() ) : ?>
						<?php if ( $smof_data["archive_heading"] ): ?>
                            <header class="page-header">
                                <h1 class="page-title">
									<?php
									if ( is_category() ) :
										single_cat_title();

                                    elseif ( is_tag() ) :
										single_tag_title();

                                    elseif ( is_author() ) :
										printf( __( 'Author: %s', THEMENAME ), '<span class="vcard">' . get_the_author() . '</span>' );

                                    elseif ( is_day() ) :
										printf( __( 'Day: %s', THEMENAME ), '<span>' . get_the_date() . '</span>' );

                                    elseif ( is_month() ) :
										printf( __( 'Month: %s', THEMENAME ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', THEMENAME ) ) . '</span>' );

                                    elseif ( is_year() ) :
										printf( __( 'Year: %s', THEMENAME ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', THEMENAME ) ) . '</span>' );

                                    elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
										_e( 'Asides', THEMENAME );

                                    elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) :
										_e( 'Galleries', THEMENAME );

                                    elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
										_e( 'Images', THEMENAME );

                                    elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
										_e( 'Videos', THEMENAME );

                                    elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
										_e( 'Quotes', THEMENAME );

                                    elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
										_e( 'Links', THEMENAME );

                                    elseif ( is_tax( 'post_format', 'post-format-status' ) ) :
										_e( 'Statuses', THEMENAME );

                                    elseif ( is_tax( 'post_format', 'post-format-audio' ) ) :
										_e( 'Audios', THEMENAME );

                                    elseif ( is_tax( 'post_format', 'post-format-chat' ) ) :
										_e( 'Chats', THEMENAME );

									else :
										_e( 'Archives', THEMENAME );

									endif;
									?>
                                </h1>
								<?php
								// Show an optional term description.
								$term_description = term_description();
								if ( ! empty( $term_description ) ) :
									printf( '<div class="taxonomy-description">%s</div>', $term_description );
								endif;
								?>
                            </header><!-- .page-header -->
						<?php endif; ?>
						<?php /* Start the Loop */ ?>
						<?php if ( isset( $smof_data['archive_post'] ) && ( $smof_data['archive_post'] == 'blog-3-columns' || $smof_data['archive_post'] == 'blog-2-columns' ) ) : ?>
                            <div class="blog-columns-wrap clearfix">
						<?php endif; ?>

                        <div class="search-pagination top">
							<?php
							if ( function_exists( 'wp_pagenavi' ) ) {
								wp_pagenavi();
							}
							?>
                        </div>

						<?php while ( have_posts() ) : the_post(); ?>

							<?php
							/* Include the Post-Format-specific template for the content.
							 * If you want to override this in a child theme, then include a file
							 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
							 */

							// This is for the PES.
							// If the post type is a gallery, then change the template
							// to a custom template for the PES.
							$post_id = get_the_ID();
							$post    = get_post( $post_id );
							//echo 'EL FORMATO DEL POST: '. $post->post_type;
							//global $wp_query;
							//echo '<pre>QUERY '.print_r($wp_query,1).'</pre>';

							if ( $post->post_type == 'gallery' || $post->post_type == 'attachment' || $post->post_type == 'video' ):
								get_template_part( 'framework/templates/blog/pes', 'archive' );
							//get_template_part('framework/templates/blog/blog', 'pes'. $post->post_type);
							// Otherwise get the regular template from the framework.
							else:
								get_template_part( 'framework/templates/blog/blog', get_post_format() );

							endif; ?>

						<?php endwhile; ?>

						<?php if ( isset( $smof_data['archive_post'] ) && ( $smof_data['archive_post'] == 'blog-3-columns' || $smof_data['archive_post'] == 'blog-2-columns' ) ) : ?>
                            </div>
						<?php endif; ?>
						<?php //cshero_paging_nav(); ?>

                        <div class="search-pagination bottom">
							<?php
							if ( function_exists( 'wp_pagenavi' ) ) {
								wp_pagenavi();
							}
							?>
                        </div>

					<?php else : ?>

						<?php get_template_part( 'framework/templates/blog/blog', 'none' ); ?>

					<?php endif; ?>

                </main><!-- #main -->

            </div>
			<?php if ( $blog_layout->right_col ): ?>
                <div class="right-wrap <?php echo esc_attr( $blog_layout->right_col ); ?>">
					<?php get_sidebar(); ?>
                </div>
			<?php endif; ?>
        </div>
    </div>

</section><!-- #primary -->
<?php get_footer(); ?>
