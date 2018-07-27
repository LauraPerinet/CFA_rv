<h2>Nouvelles formations importées</h2>
<p>Merci de renseigner les noms des formations tels que décrits sur le site du CFA. Le nom entier est le nom qui sera visible par les candidats et les apprentis admis.</p>
<?php echo form_open('import/createFormations'); ?>
<?php for($i=0; $i<count($newFormation); $i++){ ?>
<h5><?php echo $newFormation[$i];?></h5>
<div class="bloc_formation_creation">
  <div>
    <label for="name<?php echo $i;?>">Nom entier : </label>
    <input name="formation<?php echo $i;?>"  class="long"  placeholder="Nom de la formation tel qu'il sera lu par les apprentis" id="name<?php echo $i;?>"/>
    <label for="url<?php echo $i;?>">URL de la formation sur le site du CFA : </label>
    <input name="url<?php echo $i;?>"  class="long"  placeholder="https://www.cfa-sciences.fr/physique-optique/licence-professionnelle-optique-optronique-instrumentation-liovis" id="url<?php echo $i;?>"/>
    <input name="ypareo[]" value="<?php echo $newFormation[$i];?>" type="hidden"  />
  </div>
  <div>
      <label>Référents CFA</label>
      <select name="<?php echo $i.'_';?>admin[]">
          <option value=""></option>
          <?php foreach($referents as $admin){
              if($admin->DefaultRef==0){
              ?>
              <option value="<?php echo $admin->id; ?>"><?php echo $admin->name.' '.$admin->firstname; ?></option>
          <?php }}?>
      </select>

      <select name="<?php echo  $i.'_';?>admin[]">
          <option value=""></option>
          <?php foreach($referents as $admin){
              if($admin->DefaultRef==0){
            ?>
              <option value="<?php echo $admin->id; ?>"><?php echo $admin->name.' '.$admin->firstname; ?></option>
          <?php }}?>
      </select>
  </div>
  <div>
      <label>Référents Universitaire</label>
      <select name="<?php echo  $i.'_';?>staffpart[]">
          <option value=""></option>
          <?php foreach($staffpart as $admin){ ?>
              <option value="<?php echo $admin->id; ?>"><?php echo $admin->name.' '.$admin->firstname; ?></option>
          <?php }?>
      </select>
      <select name="<?php echo  $i.'_';?>staffpart[]">
          <option value=""></option>
          <?php foreach($staffpart as $admin){ ?>
              <option value="<?php echo $admin->id; ?>"><?php echo $admin->name.' '.$admin->firstname; ?></option>
          <?php }?>
      </select>
  </div>
</div>
<?php }?>
<input name="type" value="<?php echo $type;?>" type="hidden" required />
<div><input type="submit" value="Enregistrer les formations dans la base de donnée" /></div>
</form>
