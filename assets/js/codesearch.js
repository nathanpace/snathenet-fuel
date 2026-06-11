// Perform the search with the supplied data
function doSearch(formData) 
{
	$.ajax({
		type: "POST",
		url: "/codeSearch",
		data: formData,
		dataType: "html",
		encode: true,
	}).done(function (data) {
		$('#submitsearch').prop('disabled', false);
		$('#submitsearch').prop('value', ' Search ');
		$('#search-results').html(data);
		$("div[id*='exchanges-']").hide();
		$('table.display').dataTable({
			columnDefs: [
				{ 
					className: 'dt-left', 
					targets: '_all' 
				},
				{ 
					width: 60, 
					targets: [
						'#h-c-std-code', '#h-c-number-ranges',
						'#h-c-exchange-count',
						'#h-e-id','#h-e-postcode'
					]
				},
				{ 
					width: 70, 
					targets: [
						'#h-e-prev-codes'
					]
				},
				{
					width: 100,
					targets: [
						'#h-c-hist-ring-code'
					]
				},
				{
					width: 120,
					targets: [
						'#h-c-charge-group-id'
					]
				},
				{ 
					width: 130, 
					targets: [
						'#h-e-pre-afn-code','#h-e-post-afn-code',
						'#h-e-afn-route-sector'
					]
				},
				{
					width: 150,
					targets: [
						'#h-c-mapping'
					]
				},
				{ 
					width: 170, 
					targets: [
						'#h-c-charge-group-name',
						'#h-c-code-history',
						'#h-e-zone-district'
					]
				},
				{
					width: 275,
					targets: [
						'#h-c-area-name'
					]
				},
				{
					type: 'num',
					targets: '#h-e-prev-codes'
				},
				{	
					order: 'asc',
					targets: [
						'#h-e-prev-codes'
					]
				}
			],
		});
	}).fail(function() {
		alert("Unable to perform search, please try again later.");
		$('#submitsearch').prop('value', ' Search ');
		$('#submitsearch').prop('disabled', false);
	});
}

// Initialise page 
$(document).ready(function () {
	$('.formelement').css({'clear': 'inline-start'});
	$('#submitsearch').prop('disabled', true);
    $('#searchterm').keyup(function() {
        if($(this).val() != '') {
           $('#submitsearch').prop('disabled', false);
        }
    });

	// Form submission event
	$("form").submit(function (event) {
		event.preventDefault();
		$('#submitsearch').prop('disabled', true);
		$('#submitsearch').prop('value', ' Searching... ');
		var formData = {
			searchterm: $("#searchterm").val(),
			searchtype: $("#searchtype").val(),
		};
		
		doSearch(formData);
	});

});

// Action when an exchange count is clicked on the code list
$(document).on("click", "td[data-std-code-xchgs]", function(e) {

	// Get the id
	var id = $(this).attr('data-std-code-xchgs').trim(); 
	
	// Work out which set of exchange data to toggle
	var showHideThis = '#exchanges-'+id;

	$(showHideThis).toggle();

	// Toggle selected row highight
	if ($(showHideThis).is(':visible')) {
		$(this).closest("tr").addClass('selected');
	} else {
		$(this).closest("tr").removeClass('selected');
	}
});

// Action when a STD code is clicked on an exchange list
$(document).on("click", "td[id*='r-ex-']", function(e) {
	console.log($(this));
	event.preventDefault();
	$('#searching').html("Searching...");
	// Get text of clicked cell, this will be used as the search term
	var formData = {
		searchterm: $(this).text(),
		searchtype: 'code',
	};

	// Call the search function
	doSearch(formData);
});

// Action when a STD "moved" code is clicked
$(document).on("click", "span[data-moved^='0']", function(e) {

	event.preventDefault();
	search = $(this).attr('data-moved').trim().split(/\s+/).at(0); 

	// Get data value from element, this will be used as the search term
	var formData = {
		searchterm: search,
		searchtype: 'historical',
	};

	// Call the search function
	doSearch(formData);

});

// Action when a regular STD code is clicked
$(document).on("click", "span[data-std-code]", function(e) {

	event.preventDefault();
	search = $(this).attr('data-std-code').trim(); 

	// Get data value from element, this will be used as the search term
	var formData = {
		searchterm: search,
		searchtype: 'historical',
	};

	// Call the search function
	doSearch(formData);

});

// Action when help link is clicked
$(document).on("click", "#about", function(e) {
	$.ajax({
		type: "GET",
		url: "/phonecodes/about",
		dataType: "html",
		encode: true,
	}).done(function (data) {
		$('#search-results').html(data);
		$('#about-code-text').hide();
		$('#about-exchange-text').hide();
		$('#about-historical-text').hide();
	});
});

// Action when a heading is clicked on the about screen
$(document).on("click", ".h5[id*='about-']", function(e) {
	
	// Get ID of the clicked row
	var id = $(this).attr('id');
	
	// Work out which set of exchange data to toggle
	var showHideThis = '#'+id+'-text';

	$(showHideThis).toggle();

});