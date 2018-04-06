jQuery(document).ready(function($) {
    $("button.uvasomical").click(function(event) {
     	var linkId = $(this).attr("id");
		window.open( "data:text/calendar;charset=utf8," + escape ($("div#"+linkId+".uvasomical").text())); 
		});		
		
});
