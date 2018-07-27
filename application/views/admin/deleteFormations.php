<h2>Suppressions d'une formation</h2>
<p>La suppression peut causer des problèmes</p>
<?php if(!empty($student_formation)){ ?>
	<p>Etudiants admis pour cette formation : <?php echo $student_formation; ?></p>
<?php }?>
<?php if(!empty($student_calendar)){ ?>
	<p>Soutenances prévues pour cette formation : <?php echo $student_calendar; ?></p>
<?php }?>
<?php if(!empty($candidate_formation)){ ?>
	<p>Candidats à cette formation : <?php echo $candidate_formation; ?></p>
<?php }?>
<?php if(!empty($candidate_calendar)){ ?>
	<p>Entretiens de recrutement prévus pour cette formation : <?php echo $candidate_calendar; ?></p>
<?php }?>
<p>Ces entrées seront définitivement supprimées.</p>

<a class="btn" href="<?php echo site_url("admin/modificationFormation/".$formation);?>/1">Tout supprimer</a>
 <a class="btn" href="<?php echo site_url("admin/formations");?>">Annuler</a>
