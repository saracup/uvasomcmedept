<?php 
ini_set('display_errors',1); 
error_reporting(E_ALL);
/*****Template Name: Blank Content ********/
require_once('../../../wp-load.php');
?>
<html>
<head><?php wp_head(); ?></head>
<body>
<?php
global $post;
$post->ID=$_GET['coursepost_id'];
if ( have_posts() ) {
    while ( have_posts() ) {
        echo the_content();
    }
}
wp_footer();
?>
</body>
</html>