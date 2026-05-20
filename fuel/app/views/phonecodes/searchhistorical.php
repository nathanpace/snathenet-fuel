<hr/>
<h4>Search results for "<?=$searchTerm;?>" in historical info:</h4>

<p><i><b>NB</b> The code(s) returned here may not initially match the code searched, this usually means the code searched for was the result of a move from an earlier code and the code searched for will be shown in the "Code Moved To" column.</i>
</p> 
Code Count: <?=$resultCount['codes'];?><br/>
<p></p>
<table border="1" id="histcodelist" class="display">
	<thead>
		<tr>
			<th id="h-hc-std-code">STD<br/>Code</th>
			<th id="h-hc-area-name">STD Area Name<br/>(also called Exchange Group Name)</th>
			<th id="h-hc-group-type">Group Type</th>
			<th id="h-hc-mapping">Code Mapping</th>
			<th id="h-hc-map-reason">Reason For<br/>Code Mapping</th>
			<th id="h-hc-routing">Routing</th>
			<th id="h-hc-prev-codes">Code Moved From</th>
			<th id="h-hc-orig-code">Code Moved To</th>
			<th id="h-hc-other-notes">Other<br/>Notes</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($results['Codes'] as $i => $area) : ?>
		<tr class="<?=$i%2==0?'even':'odd'?>" id="<?=$area['STDCode'];?>-<?=$area['NameClean'];?>">
			<td class="variable font12"><b><?=$area['STDCode'];?></b></td>
			<td class="variable font12"><b><?=$area['Name'];?></b></td>
			<td class="variable font12"><?=$area['GroupType'];?></td>
			<td class="variable font12"><?=$area['Mapping'];?></td>
			<td class="variable font12"><?=$area['MappingReason'];?></td>
			<td class="variable font12"><?=$area['Routing'];?></td>
			<td class="variable font12"><?=$area['MovedFrom'];?>
			<td class="variable font12"><?=$area['MovedTo'];?></td>
			<td class="variable font12"><?=$area['OtherNotes'];?></td>
		</tr>
<?php endforeach;?>
	</tbody>
</table>

<?php if ($resultCount['exchanges'] > 0) : ?>
Exchange Count: <?=$resultCount['exchanges'];?><br/>
<p></p>
<table border="1" id="histexchangelist" class="display">
	<thead>
		<tr>
			<th id="h-he-prev-codes">Previous<br/>STD&nbsp;code(s)</th>
			<th id="h-he-curret-codes">Current<br/>STD&nbsp;code</th>
			<th id="h-he-name">Exchange Name(s)</th>
			<th id="h-he-id">Exchange ID</th>
			<th id="h-he-zone-district">Network Zone<br/>Network District</th>
			<th id="h-he-postcode">Postcode</th>
			<th id="h-he-code-sector">Code<br/>Sector</th>
			<th id="h-he-pre-afn-code">Pre-AFN<br/>Exchange&nbsp;Code</th>
			<th id="h-he-post-afn-code">Post-AFN<br/>Exchange&nbsp;Code</th>
			<th id="h-he-afn-route-sector">AFN<br/>Sector</th>
			<th id="h-he-notes">Additional Notes</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($results['Exchanges'] as $i => $exchange) : ?>
		<tr class="<?=$i%2==0?'even':'odd'?>" id="<?=$exchange['ID'];?>">
			<td class="variable font12"><?=str_replace("/", ", ", $exchange['OriginalCode']);?></td>
			<td class="variable font12" id="r-ex-<?=$exchange['STDCode'];?>"><?=$exchange['STDCode'];?></td>
			<td class="variable font12"><?=$exchange['Name'];?>
			<?=array_key_exists('AltName', $exchange)?"<br/>(" . $exchange['AltName'] . ")":"";?>
			</td>
			<td class="variable font12"><?=$exchange['ID'];?>
			<td class="variable font12"><?=$exchange['NetworkInfo']['Zone'] . "<br/>" .$exchange['NetworkInfo']['District'];?></td>
			<td class="variable font12"><?=$exchange['Postcode'];?></td>
			<?php if(array_key_exists("Sector", $exchange)) : ?>
			<td class="variable font12"><?=$exchange['Sector'];?></td>
			<?php else : ?>
			<td class="variable font12">N/A</td>
			<?php	 
				endif;
				if (array_key_exists("AdditionalInfo", $exchange)) : 
			?>
			<td class="variable font12"><?=$exchange['AdditionalInfo']['preAFNCode'];?></td>
			<td class="variable font12"><?=$exchange['AdditionalInfo']['postAFNCode'];?></td>
			<td class="variable font12"><?=empty($exchange['AdditionalInfo']['afnRoutingSector'])?$exchange['Sector']:$exchange['AdditionalInfo']['afnRoutingSector'];?></td>
			<td class="variable font12"><?=$exchange['AdditionalInfo']['notes'];?></td>
			<?php else : ?>
			<td class="variable font12">N/A</td>
			<td class="variable font12">N/A</td>
			<td class="variable font12">N/A</td>
			<td class="variable font12">N/A</td>				
			<?php endif; ?>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>
<?php endif; ?>
