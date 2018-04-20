<table>

<?php
	if(empty($calendar)){?> <tr><td>Aucun calendrier n'est actuellement enregistré.</td></tr>
	<?php }
	$i=0;
	foreach($calendar as $day=>$meetingsByDay){?>
		<tr>
			<th><?php echo $day; ?><br/></th>
		</tr>
		<?php 
		foreach($meetingsByDay as $meeting){?>
			<tr>
				<td class="<?php echo $meeting["id_student"]==="0" ? "available" : "notAvailable"; ?>">
				<?php echo $meeting['hour']; ?></a></td>
				<td>
					<form method="post" action="<?php echo site_url('formation/checkInscription'); ?>">
						<input type="hidden" name="meeting" value="<?php echo $meeting['id']; ?>" />
						<input type="hidden" name="formation" value="<?php echo $formation; ?>" />
						<input type="submit" value="S'inscrire" />
					</form>
				</td>
			</tr>
		<?php }
		$i++;
	}
?>
</table>