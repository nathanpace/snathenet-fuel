		<div class="row">
			<div class="col-md-12">
				<br/>
				<h2>Geostuff</h2>
				<br/>
				It is currently <b><span id="current"></span> (<?=$todayGmtBst;?>)</b> here in the UK.
				<script>new showLocalTime('current','<?=$currentTime;?>', 0, 'long');</script>
				<hr>
				<div id="static-table">
					<table>
						<tr>
							<th rowspan="2">Location</th>
							<th rowspan="2">Latitude/Longitude</th>
                            <th rowspan="2">Current weather</th>
							<th colspan="2">Local time information</th>
							<th colspan="3">TODAY (<?=$todayDate;?>)</th>
							<th colspan="3">TOMORROW (<?=$tomorrowDate;?>)</th>
						</tr>
						<tr>
							<th>Difference from <?=$todayGmtBst;?></th>
							<th>Current local time</th>
							<th>Sunrise (<?=$todayGmtBst;?>)</th>
							<th>Length of day</th>
							<th>Sunset (<?=$todayGmtBst;?>)</th>
							<th>Sunrise (<?=$tomorrowGmtBst;?>)</th>
							<th>Length of day</th>
							<th>Sunset (<?=$tomorrowGmtBst;?>)</th>
						</tr>
						<?php foreach ($places as $placeId => $placeInfo) : ?>
						<tr class="<?=$placeInfo['rowclass'];?>">
							<?php if (!array_key_exists('error', $placeInfo)) : ?>
							<td class="variable"><?=$placeInfo['geocode']['name_1'];?><br>[<?=$placeInfo['geocode']['outcode'];?>]</td>
							<td><a href="<?=$placeInfo['geocode']['maplink'];?>" target="_blank"><?=$placeInfo['geocode']['latlong'];?></a></td>
							<td class="variable"><?=$placeInfo['weather_1'];?><br/><?=$placeInfo['weather_2'];?></td>
							<td><?=$placeInfo['offset']['diff'] . " " . $placeInfo['offset']['direction'];?></td>
							<td><span id="<?=$placeId;?>"></span></td>
							<td><?=$placeInfo['astro']['today']['sunrise'];?></td>
							<td><?=$placeInfo['astro']['today']['daylength'];?></td>
							<td><?=$placeInfo['astro']['today']['sunset'];?></td>
							<td><?=$placeInfo['astro']['tomorrow']['sunrise'];?><br/>(<?=$placeInfo['astro']['tomorrow']['sunriseDiff'];?>)</td>
							<td><?=$placeInfo['astro']['tomorrow']['daylength'];?><br/>(<?=$placeInfo['astro']['tomorrow']['daylengthDiff'];?>)</td>
							<td><?=$placeInfo['astro']['tomorrow']['sunset'];?><br/>(<?=$placeInfo['astro']['tomorrow']['sunsetDiff'];?>)</td>
							<?php else : ?>
							<td colspan="11" class="variable"><?=$placeInfo['error'];?></td>
							<?php endif; ?>
						</tr>
						<?php if (!array_key_exists('error', $placeInfo)) : ?>
						<script type="text/javascript">new showLocalTime("<?=$placeId;?>","<?=$currentTime;?>","<?=$placeInfo['offset']['total_mins'];?>","short");</script>
						<?php endif;?>
						<?php endforeach; ?>
					</table>
					<br>
				</div>
				<hr>
				<?php
					// Search form
					// Defined in presenter/geostuff/content.php
					echo Form::open();
					echo $searchForm->field('location')->build();
					echo $searchForm->field('submitsearch')->build();
					echo Form::close();
				?>
				<br>
				<div id="search-results">
					<!-- search results will populate in here via AJAX -->
				</div>
				<br>
				<div style="font-style: italic">
					All place data uses the <a href="https://postcodes.io/" target="_blank">Postcodes.io</a> API to determine geocode information. | Map links use <a href="https://openstreetmap.org" target="_blank">OpenStreetMap</a> data | Weather data provided by <a href="https://openweathermap.org/" target="_blank">OpenWeather</a>.
					<br>
					Sunrise/sunset times are in UK GMT/BST, not local time.
				</div>
			</div>
		</div>

