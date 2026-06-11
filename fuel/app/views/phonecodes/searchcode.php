<hr/>
<div class="h4">Search results for "<?=$searchTerm;?>" in <?=$searchType;?>:</div>

<i>Click on the exchange count to view or hide the list of exchanges in that area.<br>
If an associated code (i.e a "ring" code) is shown, click on that to perform a historical search on that code</i>
<p></p>
<table id="codelist" class="display">
	<thead>
		<tr>
			<th id="h-c-std-code">STD<br/>Code</th>
			<th id="h-c-area-name">STD Area Name<br/>(also called Exchange Group Name)</th>
			<th id="h-c-number-ranges">Number Ranges</th>
			<th id="h-c-exchange-count">Exchange Count</th>
			<th id="h-c-charge-group-name">Charge Group<br/>Name</th>
			<th id="h-c-charge-group-id">Charge Group<br>ID</th>
			<th id="h-c-mapping">Original Code<br>Mapping</th>
			<th id="h-c-code-history">Code Change<br>History</th>
			<th id="h-c-hist-ring-code">Historical<br>Ring Code</th>
			<th id="h-c-notes">Notes</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($results as $i => $area) : ?>
		<tr class="<?=$i%2==0?'even':'odd'?>" id="<?=$area['STDCode'];?>-<?=$area['NameClean'];?>">
			<td class="font12"><b><?=$area['STDCode'];?></b></td>
			<td class="variable font12"><b><?=$area['Name'];?></b></td>
			<td class="font12"><?=$area['NumberRange'] ?? 'ALL';?></td>
			<td class="font12" data-std-code-xchgs="<?=$area['STDCode'];?>-<?=$area['NameClean'];?>"><?=$area['Exchanges']['Count'];?></td>
			<td class="variable font12"><?=$area['ChargeGroup']['Name'];?></td>
			<td class="variable font12"><?=$area['ChargeGroup']['ID'];?></td>
			<td class="font12 variable"><?=$area['Mapping'] === "-" ? "N/A" : $area['Mapping']  . "<br>(" . $area['MappingReason'] . ")" ;?></td>
			<td class="font12"><?=$area['CodeHistory'];?></td>
			<td class="font12"><?=$area['RingCode'];?></td>
			<td class="variable font12"><?=$area['OtherMappingNotes'];?></td>
		</tr>
<?php endforeach;?>
	</tbody>
</table>
<?php 
	foreach ($results as $area) : 
?>
<div id="exchanges-<?=$area['STDCode'];?>-<?=$area['NameClean'];?>">
	<?php if (count($area['Exchanges']['List']) === 0) : ?>
	<div class="h4">Exchange information for the <?=$area['STDCode'] . " " .$area['Name'];?> area is currently unavailable.</div>
	<?php else : ?>
	<hr/>
	<div class="h4">The <?=$area['Exchanges']['Count'];?> exchange<?=$area['Exchanges']['Count'] > 1?"s":"";?> in the <?=$area['STDCode'] . " " .$area['Name'];?> area:</div>
	<table class="display">
		<thead>
			<tr>
				<th id="h-e-prev-codes">Previous<br/>STD&nbsp;code(s)</th>
				<th id="h-e-name">Exchange Name(s)</th>
				<th id="h-e-id">Exchange ID</th>
				<th id="h-e-zone-district">Network Zone<br/>Network District</th>
				<th id="h-e-postcode">Postcode</th>
				<?php if (in_array("Sector", $area['Exchanges']['Fields'])) : ?>
				<th id="h-e-code-sector">Code<br/>Sector</th>
				<?php 
					endif;
					if (in_array("AdditionalInfo", $area['Exchanges']['Fields'])) : 
				?>
				<th id="h-e-pre-afn-code">Pre-AFN<br/>Exchange&nbsp;Code</th>
				<th id="h-e-post-afn-code">Post-AFN<br/>Exchange&nbsp;Code</th>
				<th id="h-e-afn-route-sector">AFN<br/>Sector</th>
				<th id="h-e-notes">Additional Notes</th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($area['Exchanges']['List'] as $i => $exchange) : ?>
			<tr class="<?=$i%2==0?'even':'odd'?>" id="<?=$exchange['ID'];?>">
				<td class="font12"><?=str_replace("/", ", ", $exchange['OriginalCode']);?></td>
				<td class="variable font12"><?=$exchange['Name'];?>
				<?=array_key_exists('AltName', $exchange)?"<br/>(" . $exchange['AltName'] . ")":"";?>
				</td>
				<td class="variable font12"><?=$exchange['ID'];?>
				<td class="variable font12"><?=$exchange['NetworkInfo']['Zone'] . "<br/>" .$exchange['NetworkInfo']['District'];?></td>
				<td class="font12"><?=$exchange['MapLink'];?></td>
				<?php if (in_array("Sector", $area['Exchanges']['Fields'])) : ?>
				<td class="font12"><?=$exchange['Sector'];?></td>
				<?php 
					endif;
					if (in_array("AdditionalInfo", $area['Exchanges']['Fields'])) : 
				?>
				<td class="font12"><?=$exchange['AdditionalInfo']['preAFNCode'];?></td>
				<td class="font12"><?=$exchange['AdditionalInfo']['postAFNCode'];?></td>
				<td class="font12"><?=empty($exchange['AdditionalInfo']['afnRoutingSector'])?$exchange['Sector']:$exchange['AdditionalInfo']['afnRoutingSector'];?></td>
				<td class="variable font12"><?=$exchange['AdditionalInfo']['notes'];?></td>
				<?php endif; ?>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
	<?php endif; ?>
</div>

<?php endforeach; ?>
