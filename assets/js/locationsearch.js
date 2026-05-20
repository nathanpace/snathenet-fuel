$(document).ready(function () {
	$("form").submit(function (event) {
	
		var formData = {
		  location: $("#location").val(),
		};

		$.ajax({
		  type: "POST",
		  url: "/locationSearch",
		  data: formData,
		  dataType: "html",
		  encode: true,
		}).done(function (data) {
		  $('#search-results').html(data);
		});

		event.preventDefault();
	});
});
