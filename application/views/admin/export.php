<div id="pageExport">
<?php  
foreach($meetings as $formation=>$days){ ?>
	
	<?php foreach($days as $day=>$sessions){?> 
		<div class="page">
		<h2><?php echo $formation;?></h2>
		<table>

			<tr>
				<th colspan="3">
					<?php echo $day;?>
				</th>
			</tr>
			<?php foreach($sessions as $meeting){?>
			<tr>
				<td><?php echo $meeting['hour'];?></td>
				<td><?php if(isset($meeting['student'])){ ?> 
					<p><?php echo $meeting['student']->name; ?></p>
					<p><?php echo $meeting['student']->email; ?> • <?php echo $meeting['student']->phone; ?></p>
				<?php }?></td>
				<td></td>
			</tr>
			<?php }?>
			</table>
			</div>
		<?php }?>
	
<?php }
?>

</div>