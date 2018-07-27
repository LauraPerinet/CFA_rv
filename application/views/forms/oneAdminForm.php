
<h2>Modifier <?php
if($type=="admin"){?>
  l'administrateur CFA
<?php }else{ ?>
  le partenaire
<?php }
echo $admin->firstname." ". $admin->name; ?></h2>
<form action="<?php echo site_url("admin/updateStaff/".$admin->id."/".$type); ?>" method="post">

<table class="admin">
  <tr>
    <td><label for="name">Nom : </label></td>
    <td><input name="name" value="<?php echo $admin->name; ?>" id="name" required/></td>
  </tr>
  <tr>
    <td><label for="firstname">Prénom : </label></td>
    <td><input name="firstname" value="<?php echo $admin->firstname; ?>" id="firstname" required/></td>
  </tr>
  <tr>
    <td><label for="email">Email : </label></td>
    <td><input name="email" type="email" class="long" value="<?php echo $admin->email; ?>" id="email" <?php if($type=="admin") echo "required"; ?>/></td>


  </tr>
  <tr><td colspan="2"><input type="submit" name="adminInfos" value="Modifier les informations" onclick="testEmail()" /></td></tr>
</table>

<?php if(isset($admin->DefaultRef) && $admin->DefaultRef==1){ ?>
  <h3>Adresse par défaut</h3>
  <p>Si aucun référent CFA n'est inscrit pour une formation, tous les emails concernants cette formation partiront à cette adresse. C'est de plus l'adresse visible sur la page contact</p>
<?php }else{?>
<h3>Référent pour les formations </h3>
<?php
if(count($formations)==0){ ?>
  <p>Aucune formation n'est enregistrée.</p>
<?php }else{ ?>
<table class="admin">
  <?php

  foreach($formations as $formation){ ?>
    <?php
      $checked=false;
    for($i=0; $i<count($admin->formations); $i++){
        if($admin->formations[$i]->id_formation===$formation->id){
          $checked=true;
          break;
        }
     }?>
    <tr>
      <td class="<?php if($checked)echo "blue" ?>">
        <?php echo $formation->ypareo; ?>
      </td>
      <td>


        <input type="checkbox" name="formations[]" value="<?php echo $formation->id; ?>" <?php if($checked) echo "checked";?> />
      </td>
    </tr>
  <?php }

  ?>
  <tr>
    <td colspan="2"><input type="submit" name="adminFormation" value="Modifier les formations" /></td>
  </tr>
</table>
<?php }
}?>
</form>

<script>
var email=document.querySelector("input[type='email']");
function testEmail(){
  var domain=email.value.split("@")[1]
  if(domain!=="cfa-sciences.fr" && domain!=="cci-paris-idf.fr"){
    email.setCustomValidity("L'adresse courriel doit appartenir au domaine cfa-sciences.fr ou cci-paris-idf.fr.");
  }else{
    email.setCustomValidity("");
  }
}

</script>
