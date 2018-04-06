<?php
//ini_set('display_errors',1); 
//error_reporting(E_ALL);

class uvasomcmedept_search_widget extends WP_Widget {

	// constructor
	function uvasomcmedept_search_widget() {
		parent::WP_Widget(false, $name = __('UVA SOM CME Course Search Widget', 'uvasomcmedept_search_widget') );
	}
	//get the dropdowns by taxonomy

	// widget form creation
function form($instance) {

// Check values
if( $instance) {
     $title = esc_attr($instance['title']);
} else {
     $title = 'Search';
}
?>

<p>
<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'uvasomcmedept_search_widget'); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
</p>
<p>
<input class="checkbox" type="checkbox" <?php checked($instance['department'], 'on'); ?> id="<?php echo $this->get_field_id('department'); ?>" name="<?php echo $this->get_field_name('department'); ?>" /> 
<label for="<?php echo $this->get_field_id('department'); ?>">Include Sponsoring Department</label></p>
<p>
<input class="checkbox" type="checkbox" <?php checked($instance['primary'], 'on'); ?> id="<?php echo $this->get_field_id('primary'); ?>" name="<?php echo $this->get_field_name('division'); ?>" /> 
<label for="<?php echo $this->get_field_id('division'); ?>">Include Sponsoring Division</label></p>
<?php
	}
	// update widget
	function update($new_instance, $old_instance) {
      $instance = $old_instance;
      // Fields
      $instance['title'] = strip_tags($new_instance['title']);
	  $instance['department'] = $new_instance['department'];
	  $instance['division'] = $new_instance['division'];
     return $instance;
}

	// display widget
	function widget($args, $instance) {
	   extract( $args );
	   // these are the widget options
	   $title = apply_filters('widget_title', $instance['title']);
	   $rd = $instance['department'] ? 'true' : 'false';
	   $primary = $instance['division'] ? 'true' : 'false';
	   $text = $instance['text'];
	   echo $before_widget;
	   // Display the widget
	   echo '<div class="widget-text uvasomcmedept_search_widget_box">';
	
	   // Check if title is set
	   if ( $title ) {
		  echo $before_title . $title . $after_title;
	   }
	   
	   //output the search form
	   ?>
	   <form action="<?php bloginfo('url'); ?>" method="get">
		<?php
		/*(if('on' == $instance['department'] ) {
		custom_taxonomy_dropdown( 'department','Sponsoring Department' );
		}
		if('on' == $instance['division'] ) {
		custom_taxonomy_dropdown( 'division','Sponsoring Division' );
		}*/
		//custom_taxonomy_dropdown( 'training-grant','Training Grant' );
		?>
        <input type="hidden" name="post_type" id="post_type" value="cmecourse" />
        <input type="hidden" name="meta_key" value="uvacme_date" />
        <input type="hidden" name="orderby" value="meta_value" />
        <input type="hidden" name="order" value="DESC" />
		<input type="text" value="" name="s" />
        <input type="submit" name="submit" value="Search" />
        </form>
<?php
	   echo '</div>';
	   echo $after_widget;
	}
}
function custom_taxonomy_dropdown( $taxonomy, $title ) {
	$terms = get_terms( $taxonomy );
	if ( $terms ) {
	if ($taxonomy == 'department') {$title = 'Sponsoring Department';}
	if ($taxonomy == 'division') {$title = 'Sponsoring Division';}
		printf( '<select name="%s" class="postform">', esc_attr( $taxonomy ) );
		echo '<option value="" selected="selected">By '.$title.'</option>';
		foreach ( $terms as $term ) {
			printf( '<option value="%s">%s</option>', esc_attr( $term->slug ), esc_html( $term->name ) );
		}
		print( '</select>' );
	}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("uvasomcmedept_search_widget");'));
?>
