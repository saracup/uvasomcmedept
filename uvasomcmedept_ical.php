<?php
function uvasomcme_ical() {
	global $post;
$description = explode("\n", $content);//truncated content for ical
//$ical .= '<div id="'.$post_id.'" class="uvasomical" style="display:none;">';
$ical = 'BEGIN:VCALENDAR'."\r\n";
$ical .= 'PRODID:-//Google Inc//Google Calendar 70.9054//EN'."\r\n";
$ical .= 'VERSION:2.0'."\r\n";
$ical .= 'BEGIN:VTIMEZONE'."\r\n";
$ical .= 'TZID:America/New_York'."\r\n";
$ical .= 'LAST-MODIFIED:19870101T000000Z'."\r\n";
$ical .= 'BEGIN:STANDARD'."\r\n";
$ical .= 'DTSTART:19971026T020000'."\r\n";
$ical .= 'TZOFFSETFROM:-0400'."\r\n";
$ical .= 'TZOFFSETTO:-0500'."\r\n";
$ical .= 'TZNAME:EST'."\r\n";
$ical .= 'END:STANDARD'."\r\n";
$ical .= 'BEGIN:DAYLIGHT'."\r\n";
$ical .= 'DTSTART:19971026T020000'."\r\n";
$ical .= 'TZOFFSETFROM:-0500'."\r\n";
$ical .= 'TZOFFSETTO:-0400'."\r\n";
$ical .= 'TZNAME:EDT'."\r\n";
$ical .= 'END:DAYLIGHT'."\r\n";
$ical .= 'END:VTIMEZONE'."\r\n";
$ical .= 'BEGIN:VEVENT'."\r\n";
$ical .= 'DTSTAMP:'.date ( 'Ymd\THis\Z', time())."\r\n";
$ical .= 'DTSTART:'.date ( 'Ymd', strtotime($cmedate[0])).'T'.date ('His\Z', strtotime($starttime[0]))."\r\n";
$ical .= 'DTEND:'.date( 'Ymd', strtotime($cmedate[0])).'T'.date ( 'His\Z', strtotime($endtime[0]))."\r\n";
$ical .= 'UID:'.$post_id.'-'.md5(mt_rand()).'-'.(($post_id + rand())*2).'@med.virginia.edu'."\r\n";
$ical .= 'DESCRIPTION:'.$description[0]."\r\n";
$ical .= 'URL;VALUE=URI:'.get_permalink($post_id)."\r\n";
$ical .= 'SUMMARY:'.get_the_title($post_id)."\r\n";
$ical .= 'END:VEVENT'."\r\n";
$ical .= 'END:VCALENDAR'."\r\n";
return $ical;
//endical
}
?>