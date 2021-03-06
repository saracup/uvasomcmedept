<?php

/**
 *
 * This file registers all of this plugin's 
 * specific Theme Settings, accessible from
 * Genesis > Site Contact Info.
 *
 * @package      WPS_Starter_Genesis_Child
 * @author       Travis Smith <travis@wpsmith.net>
 * @copyright    Copyright (c) 2012, Travis Smith
 * @license      <a href="http://opensource.org/licenses/gpl-2.0.php" onclick="javascript:_gaq.push(['_trackEvent','outbound-article','http://opensource.org']);" rel="nofollow">http://opensource.org/licenses/gpl-2.0.php</a> GNU Public License
 * @since        1.0
 * @alter        1.1.2012
 *
 */
 
 
/**
 * Registers a new admin page, providing content and corresponding menu item
 * for the Child Theme Settings page.
 *
 * @package      WPS_Starter_Genesis_Child
 * @subpackage   Admin
 *
 * @since 1.0.0
 */
class UVASOMCMEDEPT_Settings extends Genesis_Admin_Boxes {
	/**
	 * Create an admin menu item and settings page.
	 * 
	 * @since 1.0.0
	 */
	function __construct() {
		
		// Specify a unique page ID. 
		$page_id = 'uvasomcmedept';
		
		// Set it as a child to genesis, and define the menu and page titles
		$menu_ops = array(
			'submenu' => array(
				'parent_slug' => 'genesis',
				'page_title'  => 'CME Course Info',
				'menu_title'  => 'CME Course Info',
				'capability' => 'manage_options',
			)
		);
		
		// Set up page options. These are optional, so only uncomment if you want to change the defaults
		$page_ops = array(
		//	'screen_icon'       => array( 'custom' => WPS_ADMIN_IMAGES . '/staff_32x32.png' ),
			'screen_icon'       => 'options-general',
		//	'save_button_text'  => 'Save Settings',
		//	'reset_button_text' => 'Reset Settings',
		//	'save_notice_text'  => 'Settings saved.',
		//	'reset_notice_text' => 'Settings reset.',
		);		
		
		// Give it a unique settings field. 
		// You'll access them from genesis_get_option( 'option_name', CHILD_SETTINGS_FIELD );
		$settings_field = 'UVASOMCMEDEPT_SETTINGS_FIELD';
		
		// Set the default values
		$default_settings = array(
			'department' => ''		);
		
		// Create the Admin Page
		$this->create( $page_id, $menu_ops, $page_ops, $settings_field, $default_settings );

		// Initialize the Sanitization Filter
		add_action( 'genesis_settings_sanitizer_init', array( $this, 'sanitization_filters' ) );
	}
	/** 
	 * Set up Sanitization Filters
	 *
	 * See /lib/classes/sanitization.php for all available filters.
	 *
	 * @since 1.0.0
	 */	
	function sanitization_filters() {
		genesis_add_option_filter( 'no_html', $this->settings_field, array(
			'department',
		) );
	}
	
	/**
	 * Register metaboxes on Child Theme Settings page
	 *
	 * @since 1.0.0
	 *
	 * @see Child_Theme_Settings::contact_information() Callback for contact information
	 */
	function metaboxes() {
		
		add_meta_box('uvasomcmedept-settings', 'CME Course Type', array( $this, 'uvasomcmedept_meta_box' ), $this->pagehook, 'main', 'high');
		
	}
	
	/**
	 * Register contextual help on Child Theme Settings page
	 *
	 * @since 1.0.0
	 *
	 */
	function help( ) {	
		global $my_admin_page;
		$screen = get_current_screen();
		
		if ( $screen->id != $this->pagehook )
			return;
		
		$tab1_help = 
			'<h3>' . __( 'Department' , 'uvasomcmedept' ) . '</h3>' .
			'<p>' . __( 'Select the default department for courses searched by this website.' , 'uvasomcmedept' ) . '</p>';
				
		$screen->add_help_tab( 
			array(
				'id'	=> $this->pagehook . '-Department',
				'title'	=> __( 'Department' , 'uvasomcmedept' ),
				'content'	=> $tab1_help,
			) );
		
		// Add Genesis Sidebar
		$screen->set_help_sidebar(
                '<p><strong>' . __( 'For more information:', 'uvasomcmedept' ) . '</strong></p>'.
                '<p><a href="' . __( 'http://www.studiopress.com/support', 'uvasomcmedept' ) . '" target="_blank" title="' . __( 'Support Forums', 'uvasomcmedept' ) . '">' . __( 'Support Forums', 'uvasomcmedept' ) . '</a></p>'.
                '<p><a href="' . __( 'http://www.studiopress.com/tutorials', 'uvasomcmedept' ) . '" target="_blank" title="' . __( 'Genesis Tutorials', 'uvasomcmedept' ) . '">' . __( 'Genesis Tutorials', 'uvasomcmedept' ) . '</a></p>'.
                '<p><a href="' . __( 'http://dev.studiopress.com/', 'uvasomcmedept' ) . '" target="_blank" title="' . __( 'Genesis Developer Docs', 'uvasomcmedept' ) . '">' . __( 'Genesis Developer Docs', 'uvasomcmedept' ) . '</a></p>'
        );
	}
	
	/**
	 * Callback for Contact Information metabox
	 *
	 * @since 1.0.0
	 *
	 * @see Child_Theme_Settings::metaboxes()
	 */
	function uvasomcmedept_meta_box() {
		
//Display the form
//Default Course listing Selection
?>
	<p><strong>Courses listed in this site are categorized as:</strong><br />
    <?php uvasomcmedept_taxonomy_dropdown( 'department','Department' ); ?>
	</p>
<?php
	}
}
//function to search the main faculty listing site for the data to populate the pull-down menus
function uvasomcmedept_taxonomy_dropdown( $taxonomy, $title ) {
	//switch to the main faculty blog before running the query
	global $switched;
	switch_to_blog( 107 );
	$terms = get_terms( $taxonomy );
	if ( $terms ) {
		printf( '<select name="UVASOMCMEDEPT_SETTINGS_FIELD[%s]">', esc_attr( $taxonomy ) );
		$value = genesis_get_option( $taxonomy, 'UVASOMCMEDEPT_SETTINGS_FIELD');
		if ($value ===''){
		echo '<option value="" selected="selected">Select '.$title.'</option>';
		}
		if ($value >''){
		echo '<option value="">Select '.$title.'</option>';
		}
		foreach ( $terms as $term ) {
				if ($value=== ($term->slug )):$selected = ' selected="selected"'; 
				else:$selected = '';
				endif;
			printf( '<option value="%s"'.$selected.'>%s</option>', esc_attr( $term->slug ), esc_html( $term->name ) );
		}
		print( '</select>');
	//return to the current blog
	restore_current_blog();
	}
}
add_action( 'genesis_admin_menu', 'uvasomcmedept_settings_menu' );
/**
 * Instantiate the class to create the menu.
 *
 * @since 1.8.0
 */
function uvasomcmedept_settings_menu() {

	new UVASOMCMEDEPT_Settings;

}