<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>snathe.net - PHP developer in NW England for hire</title>
	<?=Asset::css(['bootstrap.css', 'snathe.css']); ?>
	<?=Asset::js(['https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js', 'homepage.js',]); ?>
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
				<h3>Hi there! I'm Nathan!</h3>
				<h4>I'm a <?=$age;?> year old PHP developer in Liverpool, NW England, UK, currently looking for work (ideally remote/remote first or hybrid).</h4>
				<p>
					<b>What do I do?</b>
					<br/>
					Take a look at <a href="https://www.linkedin.com/in/nathan-pace-php-developer-nw-eng/" target="_blank">my LinkedIn profile</a> or <a href="assets/downloads/cv.pdf" download>download my current CV</a>, but if you want a very brief summary:
					<ul>
						<li><b>PHP</b>, mainly. I've worked with the language for about 25 years now, from writing bespoke code through to working with frameworks such as <b>Laravel</b> or <b>FuelPHP</b> - and I'm just as happy doing either.</li>
						<li>I'm also happy to talk <b>SQL</b>, my preferences being either <b>PostgreSQL</b> or <b>MySQL</b>.</li>
						<li>My preference is for <b>back-end development work</b> using the languages above although I have been known to dabble in front end stuff when the need has arisen for me to do so (<b>HTML</b>, <b>JS</b> and <b>CSS</b>).</li>
						<li>Technology-wise, I'm happy working on <b>Mac</b>, <b>Linux</b> or <b>Windows</b>; basically, as long as the stuff I need to do my job is on there, I'll work on it!</li>
					</ul>
				</p>
				<p>
					<b>My most recent role (June 2018 - November 2025)</b> was as a senior developer for <a href="https://www.elevensoftware.com/" target="_blank">Eleven Software</a> (previously Airangel, which was taken over by Eleven in 2023), initially based in Warrington and transferring to a fully remote role in 2020.
					</br/>
					Here's some of the stuff I did during my time there:
					<br/>
					<ul>
						<li>Maintained in-house GPNS (Global Property Network Standards) reporting system for Marriott hotels, and was then involved in porting system from within existing monolith PHP codebase over to a new AWS platform for integration with another existing system which was acquired post-merger.</li>
						<li>Responsible for in-house development of “Dataloom” project (a fork of the above GPNS system) in Laravel and PostgreSQL; used by Marriott as a way of allowing hotel service providers to import performance metric data via CSV files which were subsequently processed to provide reporting/performance information to a separately maintained front end, via RESTful API calls.</li>
						<li>Wrote and maintained various MySQL queries to retrieve weekly/monthly site sign-up data for delivery as a CSV report.</li>
						<li>Implemented a "webhook" system in Laravel for transfer of hotel guest data from in-house system to third party hotel brand providers (Accor/Marriott), matching GHA (Global Hotel Alliance) standards.</li>
						<li>Maintained existing bespoke PHP/MySQL back-office system used in administering hotel guests connected to a hotel's WiFi.</li>
						<li>Made use of AWS deployment tools for code rollouts via in-house developed release system.</li>
						<li>Responsible for documentation of code and processes for users and developers, both in-code and using Confluence.</li>
					</ul>
				</p>
				<p>
					<b>What else have I done recently?</b><br/>
					Here's some of the stuff you can find on here that I've recently been keeping myself busy with.  All of this uses FuelPHP as the framework.
					<ul>
						<li><a href='/geostuff'>Geostuff</a> - uses API calls and the PHP native <tt>date_sun_info()</tt> functions to give information about sunrise/sunset times and current weather in any GB postcode or UK place name.</li>
						<li><a href='/phonecodes'>STD Code search</a> - searchable MySQL database of UK STD code and telephone exchange information.</li>
					</ul>

					There's also <a href="https://github.com/nathanpace" target="_blank">my Github page</a> for you to look at if you wish.
					
				</p>
				<p>
					<b>What other stuff do I like to do?</b>
					<ul>
						<li>
							<b>Quizzing</b>
							<ul>
								<li>I play in both the <a href="https://merseysidequizleagues.org.uk/" target="_blank">Merseyside Quiz League</a> and the <a href="https://quizcentral.net/qc/Online_Quiz_League_UK" target="_blank">Online Quiz League</a>.</li>
								<li>I run a weekly pub quiz at <a href="https://www.facebook.com/p/Heatons-Bridge-Inn-100057567694876/" target="_blank">The Heatons Bridge</a> in Scarisbrick.</li>
								<li>I've even been on a few TV and Radio quizzes in the UK, including <b>Popmaster</b>, <b>Fifteen-To-One</b>, <b>Countdown</b> and <b>The Chase</b>.</li>
							</ul>
						</li>
						<li>
							<b>Boardgaming</b>
							<ul>
								<li>I enjoy playing board and card games either in person or online via <a href="https://en.boardgamearena.com/" target="_blank">Board Game Arena</a>.<br/>Particular favourites include <b>Carcassonne</b>, <b>Takenoko</b>, <b>7 Wonders</b>, <b>Ticket To Ride</b>, <b>Braggart</b> and <b>Next Station: London</b>.</li>
							</ul>
						</li>
					</ul>
				</p>
				<p>
					<b>Want to say hi? Want to ask more about stuff I do or have done?</b><br/>
					Please do!  Email me - <a href="javascript:location='mailto:\u0068\u0065\u006c\u006c\u006f\u0040\u0073\u006e\u0061\u0074\u0068\u0065\u002e\u006e\u0065\u0074';void 0"><i>hello at snathe.net</i></a>
				</p>
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
