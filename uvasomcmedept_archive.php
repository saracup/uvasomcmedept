<?php
//ini_set('display_errors',1); 
//error_reporting(E_ALL);
/**
 * This file handles the faculty search results page.
*/
require_once(dirname( __FILE__ ). '/uvasomcmedept_print_list.php');

/*********Make it sidebar content layout.**************/
add_filter('genesis_pre_get_option_site_layout', '__genesis_return_sidebar_content');

/*********Don't display the post meta after each post.**************/
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );
/*********Add the search class to the page body for optional theme styling**************/
function uvasomcmedeptsearch_add_classes( $classes ) {
	unset($classes['home']);
	$classes[] = 'search';
	return $classes;
}
add_filter( 'body_class', 'uvasomcmedeptsearch_add_classes' );
/**************************************************************************************************/
//THESE LAYOUT ADJUSTMENTS ARE  SPECIFIC TO THE UVASOM BIMS THEME ONLY//////////////////////////////
/**************************************************************************************************/
/*********Move the page title from its default location, per the BIMS Theme**************/
if (get_stylesheet() =='uvasom_bims') {
add_action( 'genesis_post_title','genesis_do_post_title' );
add_action( 'genesis_after_header', 'uvasomcmedept_do_search_title' );
}
if (get_stylesheet() =='uvasom_news') {
add_action( 'genesis_post_title','genesis_do_post_title' );
add_action( 'genesis_before_loop', 'uvasomcmedept_do_search_title' );
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
}
/****declare variables needed for layout******/
	$taxonomy = 'department'; //change me
    $term = get_query_var( 'term' );
    $term_obj = get_term_by( 'slug' , $term , $taxonomy );
/*********Get rid of the home page layout stuff if this is the UVASOM News Theme**************/
/**************************************************************************************************/
function uvasomcmedept_do_search_title() {
	$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
	if (is_tax( 'department')) {$preterm='Courses Offered By ';
	//if (strpos($_SERVER["REQUEST_URI"], '?undergraduates')){$preterm='Faculty Accepting Undergraduates';$term->name='';}
$title = sprintf( '<div class="clearfix"></div><div id="uvasom_page_title">'.genesis_do_breadcrumbs().'<h1 class="archive-title">%s %s</h1>', apply_filters( 'genesis_search_title_text', __( $preterm, 'genesis' ) ), $term->name).'</div>';
	echo apply_filters( 'genesis_search_title_output', $title ) . "\n";
	}
}
/*********Remove the default archive listing **************/
remove_action( 'genesis_loop', 'genesis_do_loop' );
/*********include the custom faculty listing archive listing **************/
if (is_tax( 'department')||is_tax( 'division')||is_post_type_archive('cmecourse')){
add_action('genesis_loop','uvasomcmedept_do_loop');
}
/*********function defining display custom course listing  **************/
function uvasomcmedept_do_loop() {
	global $post;
	uvasomcmedept_courses_printlist();
	wp_reset_query();
}
?>