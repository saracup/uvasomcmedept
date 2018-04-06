<?php
/*
Plugin Name: UVA Health/School of Medicine CME Tracker for Departments
Plugin URI: http://technology.med.virginia.edu/digitalcommunications
Description: Allows listing of CME tracker courses imported into WordPress.
Version: 0.1
Author: Cathy Finn-Derecki
Author URI: http://transparentuniversity.com
Copyright 2012  Cathy Finn-Derecki  (email : cad3r@virginia.edu)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
//include widget
require_once(dirname( __FILE__ ). '/uvasomcmedept_search_widget.php');
require_once( trailingslashit( get_template_directory() ) . 'lib/classes/class-genesis-admin.php');
require_once(dirname( __FILE__ ). '/uvasomcmedept_rss.php');
require_once(dirname( __FILE__ ). '/uvasomcmedept_settings_page.php');
//Register Styles
//require_once(dirname( __FILE__ ). '/uvasomcmedept_admin.php');
// Register style sheet.
add_action( 'wp_enqueue_scripts', 'register_uvasomcmedept_styles' );

/**
 * Register style sheet.
 */
function register_uvasomcmedept_styles() {
	wp_register_style( 'uvasomcmedept_style', plugins_url( 'uvasomcmedept/uvasomcmedept.css' ) );
	wp_enqueue_style( 'uvasomcmedept_style' );
	wp_enqueue_script( 'uvasomcmedept_details',plugins_url().'/uvasomcmedept/uvasomcmedept.js',array('jquery'),'',true );
	wp_enqueue_script( 'uvasomcmedept_ical',plugins_url().'/uvasomcmedept/js/uvasomcmedept_ical.js',array('jquery'),'',false );
}
/*********Add the body class styling**************/
function uvasomcmedept_add_classes( $classes ) {
	$classes[] = 'uvasomcmedept';
	return $classes;
}
add_filter( 'body_class', 'uvasomcmedept_add_classes' );

/**************************************************************************************************/
//REGISTER THE FACULTY LISTING CONTENT TYPE FOR RETRIEVAL FROM FACULTY DIRECTORY//////////////////////////////
/**************************************************************************************************/
// Create the CME Course post type
function create_cmecourse() {

	register_post_type( 'cmecourse',
	// CPT Options
		array(
			'labels' => array(
				'name' => __( 'CME Courses' ),
				'singular_name' => __( 'CME Course' ),
			'supports' => array('title,editor,thumbnail,comments,uvacme_id,uvacme_credit,uvacme_date,uvacme_time,uvacme_endtime,uvacme_status,uvacme_webpublish,uvacme_sponsorship,uvacme_progurl,uvacme_url,uvacme_facility,uvacme_city,uvasomcme_state,uvasomcme_thumb,uvasomcme_thumblink'),
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'cmecourse'),
		)
	);
}
// Hooking up our function to theme setup
add_action( 'init', 'create_cmecourse' );

// hook into the init action and call uvasomcmedept_listings when it fires
add_action( 'init', 'tax_cmecourse', 0 );

// create taxonomies for the post type "faculty-listing"
function tax_cmecourse() {
// Coordinator
	$labels = array(
		'name'              => _x( 'Coordinators', 'taxonomy general name' ),
		'singular_name'     => _x( 'Coordinator', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Coordinators' ),
		'all_items'         => __( 'All Coordinators' )
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => false,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'coordinator' ),
	);

register_taxonomy( 'coordinators', array( 'cmecourse' ), $args );
//Course Directors
$labels = array(
		'name'              => _x( 'Course Directors', 'taxonomy general name' ),
		'singular_name'     => _x( 'Course Director', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Course Directors' ),
		'all_items'         => __( 'All Course Directors' )
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => false,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'course-director' ),
	);
register_taxonomy( 'course-director', array( 'cmecourse' ), $args );
//Departments
$labels = array(
		'name'              => _x( 'Department', 'taxonomy general name' ),
		'singular_name'     => _x( 'Departments', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Departments' ),
		'all_items'         => __( 'All Departments' )
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'department' ),
	);

register_taxonomy( 'department', array( 'cmecourse' ), $args );
//Divisions
$labels = array(
		'name'              => _x( 'Division', 'taxonomy general name' ),
		'singular_name'     => _x( 'Divisions', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Divisions' ),
		'all_items'         => __( 'All Divisions' )
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => false,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'division' ),
	);

register_taxonomy( 'division', array( 'cmecourse' ), $args );
//Course Types -- Editable in WordPress
$labels = array(
		'name'              => _x( 'Course Type', 'taxonomy general name' ),
		'singular_name'     => _x( 'Course Types', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Course Types' ),
		'all_items'         => __( 'All Course Types' )
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'course-type' ),
	);

register_taxonomy( 'course-type', array( 'cmecourse' ), $args );

}
/*********add css for plugin **************/
function uvasomcmedept_styles() {
	wp_enqueue_style( 'uvasomcmedept', plugins_url(). '/uvasomcmedept/uvasomcmedept.css');
}
add_action('wp_enqueue_scripts', 'uvasomcmedept_styles');
/*********shortcodes **********/
/***********automatic Course listings based on department***********/
function uvasomcmecourses_do_loop( $atts ) {
	extract( shortcode_atts( array(
		'listing' => 'department',
		'name' => '',
		'type' => ''
	), $atts ) );
	global $post;
	if (((esc_attr($listing) == '')||(esc_attr($listing) == 'department')||(esc_attr($listing) == 'course-type'))){
	// wp query arguments
$paged = 1;
if ( get_query_var( 'paged' ) ) { $paged = get_query_var( 'paged' ); }
if ( get_query_var( 'page' ) ) { $paged = get_query_var( 'page' ); }
	$args = array(
		'post_type'      => 'cmecourse',
		'posts_per_page' => 100,
		'tax_query' => array(
				array(
				'taxonomy' => esc_attr($listing),
				'field' => 'slug',
				'terms' => esc_attr($name),
				)
		),
		'meta_query' => array(
        array(
            'key' => 'uvacme_date',
            'value' => date("Ymd",time()),
            'compare' => '>=',
			'type' => 'NUMERIC',
        )
    ),
		'post_status'    => 'publish',
		'meta_key'	=> 'uvacme_date',
		'orderby' => 'meta_value',
		'order' => 'ASC',
		'paged' => get_query_var( 'paged' )
	);
	// Run the query
	global $wp_query;
	$query = new WP_Query( $args );

	$courselisting = '<div class="courselist_container">'."\n";
	while ( $query->have_posts() ) : $query->the_post();
	global $post;
		$post_id = get_the_ID();
		$content = get_the_content($post_id);
		$cmedate = get_post_meta(get_the_ID(),'uvacme_date');
		$start = get_post_meta(get_the_ID(),'uvacme_time');
		$starttime = preg_replace('/:00 /', ' ', $start);
		$end = get_post_meta(get_the_ID(),'uvacme_endtime');
		$endtime = preg_replace('/:00 /', ' ', $end);
		$facility = get_post_meta(get_the_ID(),'uvacme_facility');
		$city = get_post_meta(get_the_ID(),'uvacme_city');
		$state = get_post_meta(get_the_ID(),'uvacme_state');
		$credits = get_post_meta(get_the_ID(),'uvacme_credit');
		$courselisting .= '<div class="courselist content">'."\n";
		$courselisting .= '<div class="courseinfo"><a href="'.get_post_meta($post_id,'uvacme_progurl',true ) . '"><h2>'.get_the_title().'</a></h2>'."\n";
		//$courselisting .= '  <p>'.date('l, M d, Y',$date[0]);
		$courselisting .= '  <p class="coursedate">'.date('l, F j, Y',strtotime($cmedate[0]));
		$cmethumb = get_the_post_thumbnail( $post_id );
		if(!empty($start[0])){$courselisting .=  ', '.$starttime[0];
		if(!empty($end[0])){$courselisting .= ' &mdash; '.$endtime[0];}
		if(!empty($facility[0])){
			$courselisting .= '  <br />'.get_post_meta(get_the_ID(),'uvacme_facility',true );
			if(!empty($city)){ $courselisting .= ', '.get_post_meta(get_the_ID(),'uvacme_city',true );}
			if(!empty($state)){ $courselisting .= ', '.get_post_meta(get_the_ID(),'uvacme_state',true );}
		}
		if(!empty($credits[0])){$courselisting .=  '<br />CME Credits: '.$credits[0];}

		}
		$courselisting .= '  </p>'."\n";
		if(!empty($content)){
		$courselisting .= '<div class="coursedetails"></div>'."\n";
		}
		$courselisting .= '  <div class="coursecontent">'.$content.'</div>';
		$courselisting .= ' </div>'."\n";
		$courselisting .= ' <div class="courselinks">'."\n".'  <p><a class="cme_register" target="_blank" href="'.get_post_meta($post_id,'uvacme_progurl',true ) . '">Register</a></p>'."\n";
		if(!empty($cmethumb)) {
		$courselisting .= '<p class="cme_thumb" ><a class="cme_thumb" target="_blank" href="'.get_post_meta($post_id,'uvasomcme_thumblink',true ) . '"><img class="uvasomcmethumb" src="'.$cmethumb.'" /></a></p>'."\n";
		}
		$courselisting .= '<button id="'.$post_id.'" class="uvasomical"><span class="icon-calendar"></span>Add to my Calendar</button>'."\n";
		$courselisting .= ' </div>'."\n";
		$courselisting .= '</div>'."\n";
//start ical
		$description = explode("\n", $content);//truncated content for ical
		$spring = date('z', strtotime('third Sunday of March '.date('Y',$cmedate)));//start of EDT
		$fall = date('z', strtotime('first Sunday of November '.date('Y',$cmedate)));//start of EST
		$eventdayofyear = date('z', strtotime($cmedate[0]));//event day of year
		if (($eventdayofyear >=$spring)&& ($eventdayofyear < $fall)):$offset = 4; else: $offset= 5;endif; //est or edt
		$courselisting .= '<div id="'.$post_id.'" class="uvasomical">';
		$courselisting .= 'BEGIN:VCALENDAR'."\r\n";
		$courselisting .= 'PRODID:-//Google Inc//Google Calendar 70.9054//EN'."\r\n";
		$courselisting .= 'VERSION:2.0'."\r\n";
		$courselisting .= 'BEGIN:VEVENT'."\r\n";
		$courselisting .= 'DTSTAMP:'.date ( 'Ymd\THis', time())."\r\n";
		$courselisting .= 'DTSTART:'.date ( 'Ymd', strtotime($cmedate[0])).'T'.date ('His', strtotime($starttime[0].' + '.$offset.' hours')).'Z'."\r\n";
		$courselisting .= 'DTEND:'.date( 'Ymd', strtotime($cmedate[0])).'T'.date ( 'His', strtotime($endtime[0].' + '.$offset.' hours')).'Z'."\r\n";
		$courselisting .= 'UID:'.$post_id.'-'.md5(mt_rand()).'-'.(($post_id + rand())*2).'@med.virginia.edu'."\r\n";
		$courselisting .= 'DESCRIPTION:'.trim ($description[0])."\r\n";
		$courselisting .= 'URL;VALUE=URI:'.get_permalink($post_id)."\r\n";
		$courselisting .= 'SUMMARY:'.get_the_title($post_id)."\r\n";
		if (!empty($facility[0])): $facility=$facility[0];else:$facility='TBD';endif;
		if (!empty($city[0])): $city=', '.$city[0];else: $city='';endif;
		if (!empty($state[0])): $state=', '.$state[0];else: $state='';endif;
		$courselisting .= 'LOCATION: '.$facility.$city.$state."\r\n";
		$courselisting .= 'END:VEVENT'."\r\n";
		$courselisting .= 'END:VCALENDAR'."\r\n";
		$courselisting .= '</div><!--end ical div-->'."\n";
//endical
	endwhile;
	wp_reset_query();
	return $courselisting. "\n".'</div>'."\n";
	}

}
add_action( 'genesis_loop', 'uvasomcmecourses_do_loop' );
remove_action( 'genesis_loop', 'genesis_do_loop' );
//Register and Activate Shortcodes
function uvasomcme_register_shortcodes(){
  add_shortcode( 'uvasomcmecourselist', 'uvasomcmecourses_do_loop' );
  add_shortcode('uvasomcmecourse','uvasomcmecourse_single');
}
add_action( 'init', 'uvasomcme_register_shortcodes');
//********Function to redirect to faculty search results template***********//
add_action("template_redirect", 'uvasomcmedept_redirect');
function uvasomcmedept_redirect() {
	global $post;
	$plugindir = dirname( __FILE__ );
	$archivetemplate = 'uvasomcmedept_archive.php';
	$singletemplate = 'uvasomcmedept_listing_single.php';
	if (strpos($_SERVER["REQUEST_URI"], '?post_type=cmecourse&s=')||(is_post_type_archive('cmecourse')))
		{
			include($plugindir . '/' . $archivetemplate);
		}
	if(is_singular( 'cmecourse') ) {
		{
			include($plugindir . '/' . $singletemplate);
		}
	}
}


?>
