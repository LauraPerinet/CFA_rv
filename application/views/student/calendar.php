<div class="student">
<h2><?php echo $subtitle; ?></h2>
<?php
	if(empty($calendar)){?> <p>Aucun calendrier n'est actuellement enregistré.</p>
	<?php }else{ 
		if(!empty($problems)){?> 
			<p><?php echo $problems; ?></p>
	<?php }
	?>
		<p>Si vous êtes dans l'impossibilité de vous déplacer ou de vous rendre disponible aux dates proposées, <a href="<?php echo site_url("formation/contact/".$formation);?>">contactez nous.</a></p>
		<form method="post" action="<?php echo site_url('formation/checkInscription'); ?>">
		<div id="calendars" class="student">
			<?php
			$i=0;
			foreach($calendar as $day=>$meetingsByDay){?>
				<div class="dayBox">
					<div class="dayLign">
						<?php echo $day; ?>
					</div>
					<ul class="meetings">
						<?php 
						foreach($meetingsByDay as $meeting){
							if($meeting["id_student"]==="0"){
								$class="available";
							}else{
								$class=$meeting["id_student"]==$this->session->user->id ? "self" : "notAvailable" ;
							}
							?>
							<li class="meeting <?php echo $class; ?>">
							
							<?php echo "<span>".$meeting['hour']."</span>"; 
								if($meeting["id_student"]==="0"){
							?>

								<div class="infobloc">
									<p class="hiddeOnMob"><?php echo $day." • ".$meeting['hour']; ?></p>
									<p class="hiddeOnMob">Sorbonne Université, Faculté des Sciences</p>
									<p class="hiddeOnMob">4, place Jussieu, 75005 Paris</p>
									<p class="hiddeOnMob">Bâtiment Esclangon</p> 
									<p ><?php echo $meeting['location']=='' ? "<span class='hiddeOnMob'> La salle sera précisée ultérieurement.</span>": "Salle ".$meeting['location']; ?></p>
									<button type="submit" value="<?php echo $meeting["id"]; ?>" name="meeting">S'inscrire</button>
								</div>
								<?php }?>

						</li>
											
						<?php } ?>
					</ul>
				</div>
				<?php
				$i++;
				
				
			} ?>
		</div>
		<input type="hidden" name="formation" value="<?php echo $formation; ?>"/>
		</form>
	<?php }
?>
</div>

