//This toggles the individual courses open and closed to reveal more details, when available.
jQuery(document).ready(function($){
	//load closed class on page load
	$('.coursedetails').addClass('closed')
	//click function
	$('.coursedetails').click(function () {
	//show or hide next div with content in it
    $(this).next('.coursecontent').toggle("slow");
	//change class from open to close
	$(this ).toggleClass(function() {
	  if ( $( this ).is( ".closed" ) ) {
		return "open";
	  } else {
		return "closed";
	  }
});
});
});
