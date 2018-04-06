<?php

/**
 * Calls the class on the post edit screen.
 */
function call_uvasomcmedeptClass() {
    new uvasomcmedeptClass();
}

if ( is_admin() ) {
    add_action( 'load-post.php', 'call_uvasomcmedeptClass' );
    add_action( 'load-post-new.php', 'call_uvasomcmedeptClass' );
}

/** 
 * The Class.
 */
class uvasomcmedeptClass {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box( $post_type ) {
            $post_types = array('cmecourse');     //limit meta box to cme courses only
            if ( in_array( $post_type, $post_types )) {
		add_meta_box(
			'uvasomcmedept_details'
			,__( 'CME Course Details', 'uvasomcmedept_textdomain' )
			,array( $this, 'render_meta_box_content' )
			,$post_type
			,'advanced'
			,'high'
		);
            }
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {
	
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['uvasomcmedept_inner_custom_box_nonce'] ) )
			return $post_id;

		$nonce = $_POST['uvasomcmedept_inner_custom_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'uvasomcmedept_inner_custom_box' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;
	
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}

		/* OK, its safe for us to save the data now. */

		// Sanitize the user input.
		$id = sanitize_text_field( $_POST['uvacme_id'] );
		$credit = sanitize_text_field( $_POST['uvacme_credit'] );
		$date = sanitize_text_field( $_POST['uvacme_date'] );
		$start = sanitize_text_field( $_POST['uvacme_time'] );
		$end = sanitize_text_field( $_POST['uvacme_endtime'] );
		$status = sanitize_text_field( $_POST['uvacme_status'] );
		$sponsor = sanitize_text_field( $_POST['uvacme_sponsorship'] );
		$publish = sanitize_text_field( $_POST['uvacme_publish'] );
		$progurl = sanitize_text_field( $_POST['uvacme_progurl'] );
		$infourl = sanitize_text_field( $_POST['uvacme_url'] );
		$facility = sanitize_text_field( $_POST['uvacme_facility'] );
		$city = sanitize_text_field( $_POST['uvacme_city'] );
		$state = sanitize_text_field( $_POST['uvasomcmedept_state'] );
		$webpublish = sanitize_text_field( $_POST['uvacme_webpublish'] );
		
		
		
		// Update the meta field.
		update_post_meta( $post_id, 'uvacme_id', $id );
		update_post_meta( $post_id, 'uvacme_credit', $credit );
		update_post_meta( $post_id, 'uvacme_date', $date );
		update_post_meta( $post_id, 'uvacme_time', $start );
		update_post_meta( $post_id, 'uvacme_endtime', $end );
		update_post_meta( $post_id, 'uvacme_status', $status );
		update_post_meta( $post_id, 'uvacme_sponsorship', $sponsor );
		update_post_meta( $post_id, 'uvacme_publish', $publish );
		update_post_meta( $post_id, 'uvacme_progurl', $progurl );
		update_post_meta( $post_id, 'uvacme_url', $infourl );
		update_post_meta( $post_id, 'uvacme_facility', $facility );
		update_post_meta( $post_id, 'uvacme_city', $city );
		update_post_meta( $post_id, 'uvasomcmedept_state', $state );
		update_post_meta( $post_id, 'uvacme_webpublish', $webpublish );
	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {
	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'uvasomcmedept_inner_custom_box', 'uvasomcmedept_inner_custom_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$id = get_post_meta( $post->ID, 'uvacme_id', true );
		$credit = get_post_meta( $post->ID, 'uvacme_credit', true );
		$date = get_post_meta( $post->ID, 'uvacme_date', true );
		$start = get_post_meta( $post->ID, 'uvacme_time', true );
		$end = get_post_meta( $post->ID, 'uvacme_endtime', true );
		$status = get_post_meta( $post->ID, 'uvacme_status', true );
		$sponsor = get_post_meta( $post->ID, 'uvacme_sponsorship', true );
		$publish = get_post_meta( $post->ID, 'uvacme_publish', true );
		$progurl = get_post_meta( $post->ID, 'uvacme_progurl', true );
		$infourl = get_post_meta( $post->ID, 'uvacme_url', true );
		$facility = get_post_meta( $post->ID, 'uvacme_facility', true );
		$city = get_post_meta( $post->ID, 'uvacme_city', true );
		$state = get_post_meta( $post->ID, 'uvacme_state', true );
		$thumblink = get_post_meta( $post->ID, 'uvasomcme_thumblink', true );
		$webpublish = get_post_meta( $post->ID, 'uvacme_webpublish', true );

		// Display the form, using the current value.
		//ID
		echo '<p>'."\n";
		echo '<label for="uvacme_id">';
		_e( 'CME Event ID', 'uvasomcmedept_textdomain' );
		echo '</label> <br />';
		echo '<input type="text" id="uvacme_id" name="uvacme_id"';
                echo ' value="' . esc_attr( $id ) . '" size="25" />';
		echo '</p>'."\n";
		//CREDITS
		echo '<p>'."\n";
		echo '<label for="uvacme_credit">';
		_e( 'Credit Hours', 'uvasomcmedept_textdomain' );
		echo '</label><br /> ';
		echo '<input type="text" id="uvacme_credit" name="uvacme_credit"';
                echo ' value="' . esc_attr( $credit ) . '" size="25" />';
		echo '</p>'."\n";
		//DATE
		echo '<p>'."\n";
		echo '<label for="uvacme_date">';
		_e( 'Date', 'uvasomcmedept_textdomain' );
		echo '</label><br /> ';
		echo '<input type="text" id="uvacme_date" name="uvacme_date"';
                echo ' value="' . esc_attr( $date ) . '" size="25" />';
		echo '</p>'."\n";
		//START TIME
		echo '<p>'."\n";
		echo '<label for="uvacme_time">';
		_e( 'Start Time', 'uvasomcmedept_textdomain' );
		echo '</label><br /> ';
		echo '<input type="text" id="uvacme_time" name="uvacme_time"';
                echo ' value="' . esc_attr( $start ) . '" size="25" />';
		echo '</p>'."\n";
		//END TIME
		echo '<p>'."\n";
		echo '<label for="uvacme_endtime">';
		_e( 'End Time', 'uvasomcmedept_textdomain' );
		echo '</label><br /> ';
		echo '<input type="text" id="uvacme_endtime" name="uvacme_endtime"';
                echo ' value="' . esc_attr( $end ) . '" size="25" />';
		echo '</p>'."\n";
		//STATUS
		echo '<p>'."\n";
		echo '<label for="uvacme_status">';
		_e( 'Status', 'uvasomcmedept_textdomain' );
		echo '</label><br /> ';
		echo '<input type="text" id="uvacme_status" name="uvacme_status"';
                echo ' value="' . esc_attr( $status ) . '" size="25" />';
		echo '</p>'."\n";
		//SPONSORSHIP
		echo '<p>'."\n";
		echo '<label for="uvacme_sponsorship">';
		_e( 'Sponsorship', 'uvasomcmedept_textdomain' );
		echo '</label><br /> ';
		echo '<input type="text" id="uvacme_sponsorship" name="uvacme_sponsorship"';
                echo ' value="' . esc_attr( $sponsor ) . '" size="25" />';
		echo '</p>'."\n";
		//PROGRAM URL
		echo '<p>'."\n";
		echo '<label for="uvacme_progurl">';
		_e( 'Program URL', 'uvasomcmedept_textdomain' );
		echo '</label><br /> ';
		echo '<input type="text" id="uvacme_progurl" name="uvacme_progurl"';
                echo ' value="' . esc_attr( $progurl ) . '" size="25" />';
		echo '</p>'."\n";
		//INFORMATION URL
		echo '<p>'."\n";
		echo '<label for="uvacme_url">';
		_e( 'Informational URL', 'uvasomcmedept_textdomain' );
		echo '</label><br /> ';
		echo '<input type="text" id="uvacme_url" name="uvacme_url"';
                echo ' value="' . esc_attr( $infourl ) . '" size="25" />';
		echo '</p>'."\n";
		//FACILITY
		echo '<p>'."\n";
		echo '<label for="uvacme_facility">';
		_e( 'Facility', 'uvasomcmedept_textdomain' );
		echo '</label><br /> ';
		echo '<input type="text" id="uvacme_facility" name="uvacme_facility"';
                echo ' value="' . esc_attr( $facility ) . '" size="25" />';
		echo '</p>'."\n";
		//FACILITY CITY
		echo '<p>'."\n";
		echo '<label for="uvacme_city">';
		_e( 'Facility City', 'uvasomcmedept_textdomain' );
		echo '</label><br /> ';
		echo '<input type="text" id="uvacme_city" name="uvacme_city"';
                echo ' value="' . esc_attr( $city ) . '" size="25" />';
		echo '</p>'."\n";
		//FACILITY STATE
		echo '<p>'."\n";
		echo '<label for="uvacme_state">';
		_e( 'Facility State', 'uvasomcmedept_textdomain' );
		echo '</label><br /> ';
		echo '<input type="text" id="uvacme_state" name="uvacme_state"';
                echo ' value="' . esc_attr( $state ) . '" size="25" />';
		echo '</p>'."\n";
		//LINK TO BROCHURE
		echo '<p>'."\n";
		echo '<label for="uvasomcme_thumblink">';
		_e( 'brochurelink', 'uvasomcmedept_textdomain' );
		echo '</label><br /> ';
		echo '<input type="text" id="uvasomcme_thumblink" name="uvacme_state"';
                echo ' value="' . esc_attr( $thumblink ) . '" size="25" />';
		echo '</p>'."\n";
		//PUBLISH TO WEB
		echo '<p>Publish to Web? <span style="color:red;font-weight:bold;">'.esc_attr( $webpublish ).'</span></p>'."\n";
}
}