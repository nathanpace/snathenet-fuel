		<div class="row">
			<div class="col-lg-12">
				<div>
					<div class="h2">STD code search tool</div>
					<a id="about">About this tool</a>
				</div>
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
				<div class="formcontainer">
				<?php
					echo Form::open();
					echo $searchForm->field('searchterm')->build();
				?>
				<br/>
				<?php
					echo $searchForm->field('searchtype')->build();
				?>
				<br/>
				<?php
					echo $searchForm->field('submitsearch')->build();
					echo Form::close();
				?>
				</div>
				<br>
				<div id="search-results">
					<!-- search results will populate in here via AJAX -->
				</div>
			</div>
		</div>