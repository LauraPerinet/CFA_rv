
<?php
	foreach($formations as $formation){?>
		<form action='modificationFormation/<?php echo $formation->id;?>' method="post" />
		<div>
			<label><?php echo $formation->ypareo; ?></label>
			<div><input name="formation" value="<?php echo $formation->formation; ?>" required class="long"/>
				<input type="submit" name="modification" value="Modifier"/>
				<input type="submit" name="erase" value="supprimer"/>
			</div>
		</div>
		</form>
	<?php }
?>
