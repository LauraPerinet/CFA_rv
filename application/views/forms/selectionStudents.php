<h2>Recherche</h2>
<div class="form noHidden">
<?php echo form_open('student/view');  ?>
	<div>
		<label for="type">Type</label>
		<select name="type" id="type">
			<option value="candidate" <?php if(!isset($query['type']) || $query['type']!=="student") echo "selected"; ?>>candidats</option>
			<option value="student" <?php if(isset($query['type'])) echo $query['type']=="student" ? "selected":""; ?>>admis</option>
		</select>
		<label for="status">Status  </label>
		<select name="status" id="status">
			<option value="all">Tous</option>
			<?php foreach($status as $stat){ ?>
				<option value="<?php echo $stat->id; ?>" <?php if(isset($query['status'])) echo $query['status']===$stat->id ? "selected" : ""; ?> ><?php echo $stat->status; ?></option>
			<?php }?>
		</select>
		<input type="checkbox" name="relance" value="1" <?php if(isset($query["relance"]) && $query["relance"]==1) echo "checked"; ?>><label class="inline"> Relances </label>


	</div>
	<div>
		<label for="formation">Formation : </label>
		<select name="id_formation" id="formation">
			<option value="all">Toutes</option>
			<?php foreach($formations as $formation){?>
				<option value="<?php echo $formation->id; ?>" <?php if(isset($query['formation'])) echo $query['formation']===$formation->id ? "selected" : ""; ?>><?php echo $formation->ypareo; ?></option>
			<?php }?>
		</select>
		<label>Dernier changement de statut</label>
		<label class="inline">Entre </label>
		<input type="date" value="<?php echo isset($query["between"]["from"]) ? $query["between"]["from"] : $olderModif; ?>" name="from"/>
		<label class="inline"> et </label>
		<input type="date" value="<?php echo isset($query["between"]["to"]) ? $query["between"]["to"] : date('Y-m-d'); ?>" name="to"/>
	</div>
	<div>

		<p><input type="submit" value="Rechercher" /></p>
	</div>
</form>
</div>
