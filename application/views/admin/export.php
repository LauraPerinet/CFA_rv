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

<?php if(isset($meetings)){ ?>
	<div class="form">
		<?php echo form_open('formation/export/1'); ?>
			<button onclick="javascript:window.print()" type="button">Imprimer</button>
			<?php foreach($formationsSelected as $formation){?>
				<input type="hidden" value="<?php echo $formation;?>" name="id_formation[]" />
				<input type="hidden" value="<?php echo $type;?>" name="type" />
			<?php }?>
			<button type="submit">Exporter en csv</button>
		</form>
	</div>
	
<?php  

foreach($meetings as $formation=>$days){ ?>
<div class="pageExport">
	<div class="formation"> 
		<h2><?php echo $formation;?></h2>
	<?php 
		if(empty($days)){ ?>
			<p>Pas de calendrier enregistré</p>
		<?php }
		foreach($days as $day=>$sessions){?> 
		
		
		<table>

			<tr>
				<th colspan="3">
					<?php echo $day;?>
				</th>
			</tr>
			<?php foreach($sessions as $meeting){ ?>
			<tr>
				<td><?php echo $meeting['hour'];?></td>
				<td><?php if(isset($meeting['student'])){ ?> 
					<p><?php echo $meeting['student']->name." ".$meeting['student']->firstname; ?></p>
					<p><?php echo $meeting['student']->email; ?> • <?php echo $meeting['student']->phone; ?></p>
				<?php }?></td>
				<td><?php echo $meeting["skype"]==1 ? "Skype" : $meeting["location"];?></td>
			</tr>
			<?php }?>
			</table>
			
		<?php }?>
	</div>
</div>
<div class="onlyPrint">
	<?php foreach($days as $day=>$sessions){?> 
	<div class="tab1"> 
		<table>
			<tr class="head">
				<td ><img src="https://www.cfa-sciences.fr/sites/upmc/files/CFA%20des%20sciences%20simple_2.png"></td>
				<td colspan="5"><h2><?php echo $formation;?></h2></td>
				<td><img src="https://www.cfa-sciences.fr/sites/upmc/files/styles/logo_part/public/thumbnails/image/SORBONNE_FAC_SCIENCES_DEF_RVB_0.png?itok=7tgOZy4L"></td>
			</tr>
			
			<tr class="head date">
				<td colspan="7">
					<?php echo $day;?>
				</td>
			</tr>
			<tr class="head jury">
				<td colspan="7">Jury :</td>
			</tr>
			<tr>
				<th class="medium">Salle</th>
				<th class="horaire small">Horaire</th>
				<th  class="large">Nom</th>
				<th  class="large">Prénom</th>
				<th class="small">Admis(e)</th>
				<th class="small">Refusé(e)</th>
				<th class="small" width="7%">En attente</th>
			</tr>
			<?php foreach($sessions as $meeting){ ?>
			<tr>
				<td class="medium"><?php echo $meeting["skype"]==1 ? "Skype" : $meeting["location"];?></td>
				<td class="horaire small"><?php echo $meeting['hour'];?></td>
				<td class="large"><?php if(isset($meeting['student'])){ ?> 
					<p><?php echo $meeting['student']->name; ?></p>
				<?php }?></td>
				<td class="large"><?php if(isset($meeting['student'])){ ?> 
					<p><?php echo $meeting['student']->firstname; ?></p>
				<?php }?></td>
				<td class="small"></td>
				<td class="small"></td>
				<td class="small" width="7%"></td>
				
			</tr>
			
			<?php }?>
			<tr>
				<td colspan="7">
					Signature des membres du jury
				</td>
			</tr>
			</table>
	</div>
	<div class="tab2">
		<table>
			<tr class="head">
				<td ><img src="https://www.cfa-sciences.fr/sites/upmc/files/CFA%20des%20sciences%20simple_2.png"></td>
				<td colspan="3"><h2><?php echo $formation;?></h2></td>
				<td><img src="https://www.cfa-sciences.fr/sites/upmc/files/styles/logo_part/public/thumbnails/image/SORBONNE_FAC_SCIENCES_DEF_RVB_0.png?itok=7tgOZy4L"></td>
			</tr>
			
			<tr class="head date">
				<td colspan="5">
					<?php echo $day;?>
				</td>
			</tr>
			<tr class="head jury">
				<td colspan="5">Jury :</td>
			</tr>
			<tr>
				<th>Salle</th>
				<th class="horaire small">Horaire</th>
				<th>Nom</th>
				<th>Prénom</th>
				<th >Emargement</th>

			</tr>
			<?php foreach($sessions as $meeting){ ?>
			<tr>
				<td><?php echo $meeting["skype"]==1 ? "Skype" : $meeting["location"];?></td>
				<td class="horaire small"><?php echo $meeting['hour'];?></td>
				<td><?php if(isset($meeting['student'])){ ?> 
					<p><?php echo $meeting['student']->name; ?></p>
				<?php }?></td>
				<td><?php if(isset($meeting['student'])){ ?> 
					<p><?php echo $meeting['student']->firstname; ?></p>
				<?php }?></td>
				<td></td>

				
			</tr>
			
			<?php }?>
			<tr>
				<td colspan="5">
					Signature des membres du jury
				</td>
			</tr>
			</table>
	</div>
	<?php } ?>
</div>
<?php }}
?>
