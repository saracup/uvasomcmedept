<?php
//ini_set('display_errors',1); 
//error_reporting(E_ALL);
/**
Template for single cme course lising. Requires Genesis Framework.
 */
/*********Make it sidebar content layout.**************/
add_filter('genesis_pre_get_option_site_layout', '__genesis_return_sidebar_content');

//Filter Post Title to Add Font Class
//////////////////////////////////////////////////////////////////////////////////
//********************CUSTOM TITLE************************************************
//////////////////////////////////////////////////////////////////////////////////
add_filter('genesis_post_title_output', 'uvasomcmedept_alter_post_title');
function uvasomcmedept_alter_post_title( $title ) {
    return sprintf( '<h1 class="entry-title">Course Details</h1>', apply_filters( 'genesis_post_title_text', get_the_title() ) );

}
remove_action( 'genesis_post_title','genesis_do_post_title' );
//remove_action('genesis_after_header', 'uvasom_do_post_title');
remove_action('genesis_after_header', 'uvasomcmedept_do_post_title');
function uvasomcmedept_do_post_title()
{
	echo '<div class="clearfix"></div>';
	echo '<div id="uvasom_page_title">';
	genesis_do_breadcrumbs();
	genesis_do_post_title();
	echo '</div>';
}
//////////////////////////////////////////////////////////////////////////////////
//********************CUSTOM CONTENT LAYOUT***************************************
//////////////////////////////////////////////////////////////////////////////////
//REMOVE POST INFO AND META DISPLAY
remove_action( 'genesis_before_post_content', 'genesis_post_info' );
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );
//REMOVE STANDARD POST CONTENT
remove_action( 'genesis_post_content', 'genesis_do_post_content' );
//REMOVE THE ARCHIVE LOOP
remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action( 'genesis_loop', 'uvasomcmecourses_do_loop' );
//ADD COURSE LISTING POST TYPE CONTENT
add_action( 'genesis_loop', 'uvasomcmedept_single_cmelisting' );
/** Add support for Genesis Grid Loop **/
function uvasomcmedept_single_cmelisting() {
		global $post;
		//$content = get_the_content();
		$post_id = get_the_ID();
		$content = $post->post_content;
		$date = get_post_meta(get_the_ID(),'uvacme_date');
		$start = get_post_meta(get_the_ID(),'uvacme_time');
		$starttime = preg_replace('/:00 /', ' ', $start);
		$end = get_post_meta(get_the_ID(),'uvacme_endtime');
		$endtime = preg_replace('/:00 /', ' ', $end);
		$facility = get_post_meta(get_the_ID(),'uvacme_facility');
		$city = get_post_meta(get_the_ID(),'uvacme_city');
		$state = get_post_meta(get_the_ID(),'uvacme_state');
		$progurl = get_post_meta(get_the_ID(),'uvacme_progurl');
		$courselisting .= ' <h2>'.get_the_title().'</h2>';
				if(!empty($progurl[0])){
		$courselisting .= '  <div class="courselinks"> <a class="cme_register" target="_blank" href="'.get_post_meta(get_the_ID(),'uvacme_progurl',true ) . '">Register</a>'."\n";
		$courselisting .= '<button id="'.$post_id.'" class="uvasomical"><span class="icon-calendar"></span>Add to my Calendar</button>'."\n";
		$courselisting .= '</div>'."\n";
		}
		$courselisting .= '<ul class="cme_course">'."\n";
		if(!empty($date[0])){
		$courselisting .= '  <li>Date: '.date('l, M d, Y',strtotime(get_post_meta(get_the_ID(),'uvacme_date',true ))) . '</li>'."\n";
		//$courselisting .= '  <li>Date: '.$date[0]. '</li>'."\n";
		}
		if(!empty($start[0])){
		$courselisting .= '  <li>Start Time: '.$starttime[0]. '</li>'."\n";
		}
		if(!empty($end[0])){
		$courselisting .= '  <li>End Time: '.$endtime[0]. '</li>'."\n";
		}
		if(!empty($facility[0])){
			$courselisting .= '  <li>Location: '.get_post_meta(get_the_ID(),'uvacme_facility',true );
			if(!is_empty($city)){ $courselisting .= ', '.get_post_meta(get_the_ID(),'uvacme_city',true );}
			if(!is_empty($state)){ $courselisting .= ', '.get_post_meta(get_the_ID(),'uvacme_state',true );} 
			$courselisting .=  '</li>'."\n";
		}
		$courselisting .=  '</ul>'."\n";
		//$content = the_content($post_id);
		if(!empty($content)){
			$courselisting .= '   <div class="cmecourse_content">'.$content.'</div>'."\n";
		}

	echo $courselisting;
	}

?>
