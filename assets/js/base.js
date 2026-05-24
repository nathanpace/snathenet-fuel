$(document).ready(function () {
	$('#menu-header').hover(function () {
		$('#menu-header').prop('title', 'Site Menu');
	},
	function () {
		$('#menu-header').prop('title', '');
	});

	$('#menu-header').click(function (event) {
		if ($("#menu-header-dropdown").is(":visible")) {
			$("#menu-header").removeClass("fa-times-circle");
			$("#menu-header").addClass("fa-bars");
			$("#menu-header-dropdown").hide();
		} else {
			$("#menu-header").removeClass("fa-bars");
			$("#menu-header").addClass("fa-times-circle");
			$("#menu-header-dropdown").show();
		}
	});
});
