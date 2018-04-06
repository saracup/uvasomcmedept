<?php
function uvasomcmedept_courses_printlist() {
		if( have_posts() ):
		while( have_posts() ): the_post(); 
		global $post;
		$post_id = get_the_ID();
		$content = get_the_content($post_id);
		$posted = get_the_date();
		//$day = get_post_meta(get_the_ID(),'uvacme_day');
		$cmedate = get_post_meta(get_the_ID(),'uvacme_date');
		$start = get_post_meta(get_the_ID(),'uvacme_time');
		$starttime = preg_replace('/:00 /', ' ', $start);
		$end = get_post_meta(get_the_ID(),'uvacme_endtime');
		$endtime = preg_replace('/:00 /', ' ', $end);
		$facility = get_post_meta(get_the_ID(),'uvacme_facility');
		$city = get_post_meta(get_the_ID(),'uvacme_city');
		$state = get_post_meta(get_the_ID(),'uvacme_state');
		$credits = get_post_meta(get_the_ID(),'uvacme_credit');
		$courselisting = '<div class="courselist content">'."\n";
		//$courselisting .= '<p>'.$posted.'</p>';
		$courselisting .= '<div class="courseinfo"><h2><a href="'.get_post_meta($post_id,'uvacme_progurl',true ).'">'.get_the_title().'</a></h2>'."\n";
		$courselisting .= '  <p class="coursedate">'.date('l, F j, Y',strtotime($cmedate[0]));
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
		$courselisting .= '  <div class="coursecontent">'.get_the_content($post_id).'</div>'; 
		$courselisting .= ' </div>'."\n";
		$courselisting .= ' <div class="courselinks">'."\n".'  <p><a class="cme_register" target="_blank" href="'.get_post_meta($post_id,'uvacme_progurl',true ) . '">Register</a></p>'."\n";
		if(!empty($cmethumb)) {
		$courselisting .= '<p class="cme_thumb" ><a class="cme_thumb" target="_blank" href="'.get_post_meta($post_id,'uvasomcme_thumblink',true ) . '"><img class="uvasomcmethumb" src="'.$post_thumbnail_url.'" /></a></p>'."\n";
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

		echo $courselisting;
		endwhile;
		genesis_posts_nav();
	endif;
	if( !have_posts() ):
		echo "<p>No current courses found.</p>"; 
	endif;

}
?>