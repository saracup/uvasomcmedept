jQuery(document).ready(function($){
	var coursecount = $("#coursepage").attr("class")
	$(function() {
			$("#coursepage").paginate({
				count 		: coursecount,
				start 		: 1,
				display     : 10,
				border					: true,
				border_color			: '#fff',
				text_color  			: '#fff',
				background_color    	: 'black',	
				border_hover_color		: '#ccc',
				text_hover_color  		: '#000',
				background_hover_color	: '#fff', 
				images					: false,
				mouse					: 'press',
				onChange     			: function(page){
											$('._current','#div.courselist_container').removeClass('_current').hide();
											$('#div.courselist'+page).addClass('_current').show();
										  }
			});
});
});