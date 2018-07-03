<div class="form" id="export">
	<form method="post" action="<?php echo base_url("index.php/formation/export")?>">
	<div>
		<label>Type : </label>
		<select name="type">
			<option value="candidate">Entretien de selection</option>
			<option value="student">Soutenance </option>
		</select>
		<div><input type="submit" value="Exporter les tableaux"/>	</div>
	</div>

	<div>
	<?php
	$i=0;
	$j=count($formations)*0.5;
	foreach($formations as $formation){
			if($i==$j){ ;?>
				</div>
				<div>
			<?php }
		?>
			<li class="submenu">

				<input type="checkbox" name="id_formation[]" value="<?php echo $formation->id;?>"/>
				<?php echo $formation->ypareo; ?>
			</li>
	<?php
		$i++;
	}?>

	</div>

	</form>
</div>
<div id="pageExport">
<?php if(isset($meetings)){ ?>
	<div class="form">
		<?php echo form_open('formation/export/1'); ?>
			<button onclick="javascript:window.print()" type="button">Imprimer</button>
			<?php foreach($formationsSelected as $formation){?>
				<input type="hidden" value="<?php echo $formation;?>" name="id_formation[]" />
				<input type="hidden" value="<?php echo $type;?>" name="type" />
			<?php }?>
			<button type="submit">Exporter en csv</a>
		</form>
	</div>

<?php

foreach($meetings as $formation=>$days){ ?>
	<div class="formation">
		<h2><?php echo $formation;?></h2>
	<?php
		if(empty($days)){ ?>
			<p>Pas de calendrier enregistré</p>
		<?php }
		$meeting;
		foreach($days as $day=>$sessions){?>


		<table>
			<tr>
				<td ><img src="https://www.cfa-sciences.fr/sites/upmc/files/CFA%20des%20sciences%20simple_2.png"></td>
				<td colspan="5"><h2><?php echo $formation;?></h2></td>
				<td><img src="https://www.cfa-sciences.fr/sites/upmc/files/styles/logo_part/public/thumbnails/image/SORBONNE_FAC_SCIENCES_DEF_RVB_0.png?itok=7tgOZy4L"></td>
			</tr>

			<tr>
				<td colspan="7">
					<?php echo $day;?>
				</td>
			</tr>
			<tr>
				<td colspan="7">Jury :</td>
			</tr>
			<tr>
				<th>Salle</th>
				<th>Horaire</th>
				<th>Nom</th>
				<th>Prénom</th>
				<th>Admis(e)</th>
				<th>Refusé(e)</th>
				<th>En attente</th>
			</tr>
			<?php foreach($sessions as $meeting){ ?>
			<tr>
				<td><?php echo $meeting["distant"]!="" ? $meeting["distant"] : $meeting["location"];?></td>
				<td><?php echo $meeting['hour'];?></td>
				<td><?php if(isset($meeting['student'])){ ?>
					<p><?php echo $meeting['student']->name." ".$meeting['student']->firstname; ?></p>
					<p><?php echo $meeting['student']->email; ?> • <?php echo $meeting['student']->phone; ?></p>
				<?php }?></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>

			</tr>

			<?php }?>
			<tr>
				<td colspan="7">
					Signature des membres du jury
				</td>
			</tr>
			</table>

		<?php }?>
	</div>
<?php }}
?>

</div>
