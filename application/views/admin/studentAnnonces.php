<h2>Annonces envoyées à <?php echo $student->firstname.' '.$student->name; ?></h2>
<?php
if(count($annonces)==0){
  ?>
  <p>Aucune annonce n'a été envoyée</p>
<?php
}else{
  ?>
  <div class="flex">
    <span>Annonces envoyées : <?php echo $numbers['all']; ?></span>
    <span>Réponse "interessé" : <span class="green" style="padding:3px 5px;"><?php echo $numbers['yes']; ?></span></span>
    <span >Réponse "pas interessé" : <span class="red" style="padding:3px 5px;"> <?php echo $numbers['no']; ?></span></span>
  </div>
  <table class="admin">
      <tr>
        <th>titre de l'annonce</th>
        <th>Publication</th>
        <th>Expiration</th>
        <th>CV envoyé par</th>
        <th>Réponse</th>
      </tr>
      <?php foreach($annonces as $annonce){ ?>
        <tr>
          <td><?php echo $annonce->title; ?></td>
          <td><?php echo Utils::getFullDate($annonce->publication); ?></td>
          <td><?php echo Utils::getFullDate($annonce->expiration); ?></td>
          <td><?php echo $annonce->autonomy==1 ? "Etudiant" : "CFA"; ?></td>

          <td class="<?php if($annonce->interested=="yes") echo "green"; if($annonce->interested=="no") echo "red";?>"><?php if($annonce->interested=="yes" || $annonce->interested=="no") echo "X";?></td>
        </tr>
      <?php }?>
  </table>
<?php
}
?>
