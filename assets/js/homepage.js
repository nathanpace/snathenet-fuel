$(document).ready(function () {
	$("#download-cv").click(function (event) {
	
		event.preventDefault();

		$.ajax({
		  type: "GET",
		  url: "/cv",
		  encode: true,
		});

		
	});
});
