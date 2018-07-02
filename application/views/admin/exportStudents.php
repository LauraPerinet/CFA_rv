<div class="menuBtn"><button  type="button" data-form='formExportStudent'>
	Exporter ou imprimer le tableau
	</button> 
</div>
<div class="form" id="formExportStudent">
	<?php echo form_open(''); ?>
		<input type="hidden" name="exportCSV" value="1">
		<input type="hidden" name="type" value="<?php echo isset($query['type']) ? $query['type'] :'all'; ?>">
		<input type="hidden" name="year" value="<?php echo isset($query['year']) ? $query['year'] :'all'; ?>">
		<input type="hidden" name="id_formation" value="<?php echo isset($query['formation']) ? $query['formation'] :'all'; ?>">
		<input type="hidden" name="status" value="<?php echo isset($query['status']) ? $query['status'] :'all'; ?>">
		<input type="hidden" name="relance" value="<?php echo isset($query['relance']) ? $query['relance'] :'0'; ?>">
		<button onclick="javascript:window.print()" type="button">Imprimer</button>
		<button type="submit"  name="exportCSV" value="1">Exporter en csv</a>
	</form>
</div>