<h2>Recherche</h2>
<div class="form noHidden">
<?php echo form_open('student/view');  ?>
	<div>
		<label for="type">Type</label>
		<select name="type" id="type">
			<option value="candidate" <?php if(!isset($query['type']) || $query['type']!=="student") echo "selected"; ?>>candidats</option>
			<option value="student" <?php if(isset($query['type'])) echo $query['type']=="student" ? "selected":""; ?>>apprentis</option>
		</select>

		<label for="year">Année</label>
		<select name="year" id="year">
			<option value="all">Toutes</option>
		<?php for($i=$minYear; $i<=date('Y'); $i++){ ?>
			<option value="<?php echo $i; ?>" <?php if(isset($query['year'])) echo $query['year']===$i ? "selected":""; ?>><?php echo $i; ?></option>
		<?php }?>
		</select>
	</div>
	<div>
		<label for="formation">Formation : </label>
		<select name="formation" id="formation">
			<option value="all">Toutes</option>
			<?php foreach($formations as $formation){?>
				<option value="<?php echo $formation->id; ?>" <?php if(isset($query['formation'])) echo $query['formation']===$formation->id ? "selected" : ""; ?>><?php echo $formation->ypareo; ?></option>
			<?php }?>
		</select>
		<label for="status">Status : </label>
		<select name="status" id="status">
			<option value="all">Toutes</option>
			<?php foreach($status as $stat){ ?>
				<option value="<?php echo $stat->id; ?>" <?php if(isset($query['status'])) echo $query['status']===$stat->id ? "selected" : ""; ?> ><?php echo $stat->status; ?></option>
			<?php }?>
		</select>
	</div>
	<div>
		<p><input type="submit" value="Rechercher" /></p>
	</div>
</form>
</div>