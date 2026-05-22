<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>snathe.net - STD code search tool</title>
	<?=Asset::css(['https://cdn.datatables.net/2.3.7/css/dataTables.dataTables.css','bootstrap.css', 'snathe.css']); ?>
	<?=Asset::js(['https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js', 'https://cdn.datatables.net/2.3.7/js/dataTables.js', 'codesearch.js']); ?>
</head>
<body>
	<header>
		<div class="container resized">
			<a href="/" class="nostyle">Welcome to snathe.net</a>
		</div>
	</header>
	<div class="container resized fullwidth">
		<div class="row">
			<div class="col-lg-12">
				<br/>
				<h2>STD code search tool</h2>
				<a id="about">About this tool</a>
				<hr>
				<p>
					Enter search term below and choose search type:<br/>
					<ul>
						<li><b>Code:</b> A current STD code (e.g. <b>01234</b>, <b>0118</b> or <b>023</b>), or a word that is in a STD area name (e.g. <b>South</b>).</li>
						<li><b>Charge Group:</b> A charge group name (e.g. <b>Swanley</b>).</li>
						<li><b>Exchange:</b> An exchange name (e.g. <b>Bootle</b>) or exchange MDF ID (e.g. <b>LVBOO</b>)</li>
						<li><b>Historical Info:</b> Either a "historical" STD code i.e. pre-PhONEday or Big Number Change code (eg <b>07048</b> or <b>021</b>), or an exchange group name (eg <b>Bedford</b>)</li>
					</ul>
				</p>
				<?php

					// Dropdown options for search types
					$searchTypes = [
						'code' => 'Code',
						'group' => 'Charge Group',
						'exchange' => 'Exchange',
						'historical' => 'Historical Info',
					];

					// Form 
					echo Form::open();
					echo Form::label('Search term:&nbsp;', 'search');
					echo Form::input('search', '', ['id' => 'searchterm']);
					echo("<br/>");
					echo Form::label('Search type:&nbsp;', 'searchtype');
					echo Form::select('searchtype', 
									  'code', 
									  $searchTypes,
									  array('id' => 'searchtype'));
					echo("<br/>");
					echo Form::button('submitsearch', ' Search ', array('id' => 'submitsearch'));
					echo Form::close();
				?>

				<div id="search-results">
					<!-- search results will populate in here via AJAX -->
				</div>
			</div>
		</div>
		<hr>
		<footer>
			<p class="pull-right">Page rendered in {exec_time}s using {mem_usage}mb of memory.</p>
			<p>
				Built with <a href="https://fuelphp.com/">FuelPHP</a>, released under the MIT license.<br>
				<small>Version: <?=Fuel::VERSION;?></small>
			</p>
		</footer>
	</div>
</body>
</html>
