<div class="errormessage">
<?php if (empty($searchTerm)) : ?>
Please enter a search term
<?php else : ?>
No matching <?=$searchType;?> found for "<?=$searchTerm;?>"
</div>
<?php endif; ?>