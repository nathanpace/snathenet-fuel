<hr/>
<h4>Search results for "<?=$searchTerm;?>" in <?=$searchType;?>:</h4>

Click on a row to view or hide the list of exchanges in that area.
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
			<th id="h-c-prev-codes">Previous Codes</th>
			<th id="h-c-orig-code">Original Code</th>
			<th id="h-c-mapping">Code Mapping</th>
			<th id="h-c-map-reason">Reason For<br/>Code Mapping</th>
			<th id="h-c-other-notes">Other<br/>Notes</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($results as $i => $area) : ?>
		<tr class="<?=$i%2==0?'even':'odd'?>" id="<?=$area['STDCode'];?>-<?=$area['NameClean'];?>">
			<td class="variable font12"><b><?=$area['STDCode'];?></b></td>
			<td class="variable font12"><b><?=$area['Name'];?></b></td>
			<td class="variable font12">
			<?php if (array_key_exists('NumberRange', $area)) : ?>
				<?=$area['NumberRange'];?>
			<?php else : ?>
				ALL
			<?php endif; ?>
			</td>
			<td class="variable font12"><?=$area['Exchanges']['Count'];?></td>
			<td class="variable font12"><?=$area['ChargeGroup']['Name'];?></td>
			<td class="variable font12"><?=$area['ChargeGroup']['ID'];?></td>
			<td class="variable font12"><?=$area['PreviousCodes'];?>
			<td class="variable font12"><?=$area['OriginalCode'];?></td>
			<td class="variable font12"><?=$area['Mapping'];?></td>
			<td class="variable font12"><?=$area['MappingReason'];?></td>
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
	<h4>Exchange information for the <?=$area['STDCode'] . " " .$area['Name'];?> area is currently unavailable.</h4>
	<?php else : ?>
	<hr/>
	<h4>The <?=$area['Exchanges']['Count'];?> exchange<?=$area['Exchanges']['Count'] > 1?"s":"";?> in the <?=$area['STDCode'] . " " .$area['Name'];?> area:</h4>
	<table border="1" class="display">
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
				<td class="variable font12"><?=str_replace("/", ", ", $exchange['OriginalCode']);?></td>
				<td class="variable font12"><?=$exchange['Name'];?>
				<?=array_key_exists('AltName', $exchange)?"<br/>(" . $exchange['AltName'] . ")":"";?>
				</td>
				<td class="variable font12"><?=$exchange['ID'];?>
				<td class="variable font12"><?=$exchange['NetworkInfo']['Zone'] . "<br/>" .$exchange['NetworkInfo']['District'];?></td>
				<td class="variable font12"><?=$exchange['Postcode'];?></td>
				<?php if (in_array("Sector", $area['Exchanges']['Fields'])) : ?>
				<td class="variable font12"><?=$exchange['Sector'];?></td>
				<?php 
					endif;
					if (in_array("AdditionalInfo", $area['Exchanges']['Fields'])) : 
				?>
				<td class="variable font12"><?=$exchange['AdditionalInfo']['preAFNCode'];?></td>
				<td class="variable font12"><?=$exchange['AdditionalInfo']['postAFNCode'];?></td>
				<td class="variable font12"><?=empty($exchange['AdditionalInfo']['afnRoutingSector'])?$exchange['Sector']:$exchange['AdditionalInfo']['afnRoutingSector'];?></td>
				<td class="variable font12"><?=$exchange['AdditionalInfo']['notes'];?></td>
				<?php endif; ?>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
	<?php endif; ?>
</div>

<?php endforeach; ?>
