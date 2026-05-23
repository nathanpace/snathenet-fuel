<?php if (count($data) === 0) : ?>
	<h5>Sorry, no matching location found.</h5>
<?php else : ?>

<table>	
	<tr>
		<th rowspan="2">Location</th>
		<th rowspan="2">Latitude/Longitude</th>
        <th rowspan="2">Current weather</th>
		<th colspan="2">Local time information</th>
		<th colspan="3">TODAY (<?=$page['todayDate'];?>)</th>
		<th colspan="3">TOMORROW (<?=$page['tomorrowDate'];?>)</th>
	</tr>
	<tr>
		<th>Difference from <?=$page['todayGmtBst'];?></th>
		<th>Current local time</th>
		<th>Sunrise (<?=$page['todayGmtBst'];?>)</th>
		<th>Length of day</th>
		<th>Sunset (<?=$page['todayGmtBst'];?>)</th>
		<th>Sunrise (<?=$page['tomorrowGmtBst'];?>)</th>
		<th>Length of day</th>
		<th>Sunset (<?=$page['tomorrowGmtBst'];?>)</th>
	</tr>
	<?php foreach ($data as $placeId => $placeInfo) : ?>
	<tr class="<?=$placeInfo['rowclass'];?>">
		<?php if (!array_key_exists('error', $placeInfo)) : ?>
		<td class="variable"><?=$placeInfo['geocode']['name_1'];?>
			<?php if ($placeInfo['geocode']['isPostcode'] === false) : ?><br>[<?=$placeInfo['geocode']['outcode'];?>] <?php endif; ?>
		</td>
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
		<td colspan="13" class="variable"><?=$placeInfo['error'];?></td>
		<?php endif; ?>
	</tr>
	<?php if (!array_key_exists('error', $placeInfo)) : ?>
	<script>new showLocalTime("<?=$placeId;?>",
							  "<?=$page['currentTime'];?>",
							  "<?=$placeInfo['offset']['total_mins'];?>",
							  "short");</script>
	<?php endif;?>
	<?php endforeach; ?>
</table>
<?php endif; ?>
