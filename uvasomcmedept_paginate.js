// JavaScript Document
jQuery(document).ready(function($) {
	$('.courselist_container').pajinate({
		items_per_page :10,
		nav_label_first : '<<',
		nav_label_last : '>>',
		nav_label_prev : '<',
		nav_label_next : '>',
		num_page_links_to_display : 10,
	});
});	
