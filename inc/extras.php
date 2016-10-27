<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * @package Maker
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function maker_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class if there are no widgets in a sidebar.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	// Adds a no-excerpt class for portfolio pages without manual excerpts.
	if ( ! has_excerpt() || ! get_theme_mod( 'maker_display_project_excerpt', 1 ) ) {
		$classes[] = 'no-excerpt';
	}

	return $classes;
}
add_filter( 'body_class', 'maker_body_classes' );

/**
 * Defines portfolio grid class depending on the number of columns.
 */
function maker_portfolio_grid_class() {
	if ( in_array( get_theme_mod( 'maker_portfolio_columns', 3 ), array( 2, 3, 4 ) ) ) {
		return 'portfolio-grid-col-' . get_theme_mod( 'maker_portfolio_columns', 3 );
	} else {
		return 'portfolio-grid-col-3';
	}
}

/**
 * Custom excerpt.
 *
 * @param string $more current excerpt.
 */
function maker_excerpt( $more ) {
	global $post;
	return ' ...';
}
add_filter( 'excerpt_more', 'maker_excerpt' );

/**
 * Filters the_category() to output HTML5 valid rel tag.
 *
 * @param string $text markup containing list of categories.
 */
function maker_category_rel( $text ) {
	$search  = array( 'rel="category"', 'rel="category tag"' );
	$replace = 'rel="tag"';
	$text    = str_replace( $search, $replace, $text );
	return $text;
}
add_filter( 'the_category', 'maker_category_rel' );
/**
 * Update maximum srcset image width.
 *
 * @param int $max_width Maximum allowed image width.
 */
function maker_remove_max_srcset_image_width( $max_width ) {
	return 1992;
}
add_filter( 'max_srcset_image_width', 'maker_remove_max_srcset_image_width' );

/**
 * Displays an update notice.
 */
function maker_update_notice() {
	global $current_user ;
	$user_id = $current_user->ID;
	$nag_allowed_html = array(
		'a' => array(
			'href' => array(),
			'target' => array(),
		),
		'strong' => array(),
	);

	/* Check if the user has already clicked to ignore the nag */
	if ( current_user_can( 'edit_theme_options' ) && ! get_user_meta( $user_id, 'maker_update_0_3_0_ignore' ) ) {
		echo '<div class="notice notice-warning is-dismissible"><p>';
		printf( wp_kses( __( 'Upgrading from version prior to <strong>Maker 0.3.0</strong>? Read the <a href="%1$s" target="_blank">release notes</a>, this update requires a bit of action on your side. <a href="%2$s" target="_blank">Got it, don\'t show again.</a>', 'maker' ), $nag_allowed_html ),
			esc_url( 'https://docs.themepatio.com/maker-upgrade-0-3-0/' ),
			'?maker_update_0_3_0_ignore=0" target="_blank"'
			);
		echo '</p></div>';
	}
}
add_action( 'admin_notices', 'maker_update_notice' );

/**
 * Checks if the user has clicked to ignore the notice.
 */
function maker_update_notice_ignore() {
	global $current_user;
	$user_id = $current_user->ID;

	/* If user clicks to ignore the notice, add that to their user meta */
	if ( isset( $_GET['maker_update_0_3_0_ignore'] ) && 0 == $_GET['maker_update_0_3_0_ignore'] ) {
		 add_user_meta( $user_id, 'maker_update_0_3_0_ignore', 1, true );
	}
}
add_action( 'admin_init', 'maker_update_notice_ignore' );
