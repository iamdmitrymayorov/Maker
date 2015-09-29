<?php
/**
 * The template for displaying portfolio by Portfolio Toolkit.
 *
 * Template Name: Portfolio – Portfolio Toolkit
 *
 * @package Maker
 */

get_header(); ?>

<div id="main" class="site-main" role="main">
	<div id="content" class="site-content">
		<div id="primary" class="content-area">

			<?php if ( get_theme_mod( 'maker_display_portfolio_text' ) ) : ?>
			
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'template-parts/content', 'page' ); ?>

					<?php
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					?>

				<?php endwhile;?>

			<?php endif ?>

			<?php
				// Check if we have pagination.
				if ( get_query_var( 'paged' ) ) :
					$paged = get_query_var( 'paged' );
				elseif ( get_query_var( 'page' ) ) :
					$paged = get_query_var( 'page' );
				else :
					$paged = 1;
				endif;

				// Default posts per page option.
				$posts_per_page = get_option( 'posts_per_page', 9 );

				// Check if Portfolio Toolkit is activated.
				if ( post_type_exists( 'portfolio' ) ) :

					$args = array(
						'post_type'      => 'portfolio',
						'order'          => 'DESC',
						'orderby'        => 'date',
						'paged'          => $paged,
						'posts_per_page' => $posts_per_page,
					);

					$projects = new WP_Query( $args );

					if ( $projects -> have_posts() ) :

						printf(
							'<div class="portfolio-grid %s">',
							sanitize_html_class( maker_portfolio_grid_class() )
						);

							while ( $projects -> have_posts() ) : $projects -> the_post();

								get_template_part( 'template-parts/content', 'portfolio-toolkit' );

							endwhile;

						echo '</div>';

						maker_paging_nav( $projects->max_num_pages );

						wp_reset_postdata();

					endif;

				endif;
			?>

		</div>
	</div><!-- #content -->
</div><!-- #main -->

<?php get_footer(); ?>
