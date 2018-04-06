<?php
ini_set('display_errors',1); 
error_reporting(E_ALL);

define('WP_USE_THEMES', false);
require_once('../../../wp-load.php');
global $post;
/*$post->ID = $_GET['post_id'];
echo $post->ID;
the_content($post->ID);*/
?>
<?php
	global $switched;
	//switch to the blog with the course data in it -- specific to multinetwork install
	switch_to_blog( 107 );
$post_7 = get_post($post_id, ARRAY_A);
$title = $post_7['post_title'];
	echo $title;
?>
restore_current_blog();

?>