<h2>Nouvelles formations importées</h2>
<?php echo form_open('import/createFormations'); ?>
<?php for($i=0; $i<count($newFormation); $i++){ ?>
<h5>Nom Ypareo : <?php echo $newFormation[$i];?></h5>
<label>Nom entier : </label>
<input name="formation<?php echo $i;?>" required class="long"/>
<input name="ypareo[]" value="<?php echo $newFormation[$i];?>" type="hidden" required />
<?php }?>
<input name="type" value="<?php echo $type;?>" type="hidden" required />
<div><input type="submit" value="Créer les formations" /></div>
</form>
