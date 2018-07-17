
<div class="menuBtn hidden"><button  type="button" data-form='formImportStudent'>
	Importer des apprentis
	</button>
</div>
<div class="form import" id="formImportStudent">
<form action="<?php echo site_url('import/importStudent'); ?>" method="post" enctype="multipart/form-data" >
	<div>
		<label for="type">Type </label>
		<select name="type">
			<option value="candidate">Candidats - recrutement</option>
			<option value="student">Admis - suivi des placements</option>
		</select>
	</div>
	<div>
		<label for="file">Choisir un fichier</label> <p><input type="file" name="fileRV" id="file" required /></p>
	</div>
	<div>
		<input type="submit" value="Importer"/>
	</div>
</form>
</div>
