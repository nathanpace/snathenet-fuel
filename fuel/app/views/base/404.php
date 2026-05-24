<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>snathe.net</title>
	<?=Asset::css(['bootstrap/bootstrap.css', 'snathe.css']); ?>
</head>
<body>
	<header>
		<div class="container resized">
			<a href="/" class="nostyle">Welcome to snathe.net</a>
		</div>
	</header>
	<div class="container resized fullwidth">
		<div class="row">
			<div class="col-md-12">
				<h1><?php echo $title; ?> <small>We can't find that!</small></h1>
				<hr>
			</div>
		</div>
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
