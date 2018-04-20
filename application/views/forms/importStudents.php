
<h2>Importer</h2>
<div class="form import">
<form action="<?php echo site_url('import/importStudent'); ?>" method="post" enctype="multipart/form-data" >
	<div>
		<label for="type">Type </label>
		<select name="type">
			<option value="candidate">candidats</option>
			<option value="student">étudiants</option>
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
