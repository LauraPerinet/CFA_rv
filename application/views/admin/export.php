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

				<input type="checkbox" name="formation[]" value="<?php echo $formation->id;?>"/> 
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
				<input type="hidden" value="<?php echo $formation;?>" name="formation[]" />
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
<?php }}
?>

</div>