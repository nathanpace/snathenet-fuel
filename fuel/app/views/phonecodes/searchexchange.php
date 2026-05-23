<hr/>
<h4>Search results for "<?=$searchTerm;?>" in <?=$searchType;?>:</h4>
On any row, clicking the current STD code will perform a search on that code.
<p></p>
<table id="exchangelist" class="display">
	<thead>
		<tr>
			<th id="h-e-prev-codes">Previous<br/>STD&nbsp;code(s)</th>
			<th id="h-e-curret-codes">Current<br/>STD&nbsp;code</th>
			<th id="h-e-name">Exchange Name(s)</th>
			<th id="h-e-id">Exchange ID</th>
			<th id="h-e-zone-district">Network Zone<br/>Network District</th>
			<th id="h-e-postcode">Postcode</th>
			<th id="h-e-code-sector">Code<br/>Sector</th>
			<th id="h-e-pre-afn-code">Pre-AFN<br/>Exchange&nbsp;Code</th>
			<th id="h-e-post-afn-code">Post-AFN<br/>Exchange&nbsp;Code</th>
			<th id="h-e-afn-route-sector">AFN<br/>Sector</th>
			<th id="h-e-notes">Additional Notes</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($results as $i => $exchange) : ?>
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

