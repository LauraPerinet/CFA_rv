<h2>Formations</h2>
<?php foreach($student->formations as $formation){ ?>
	<h3><a href="<?php echo site_url("formation/admin/".$formation->id_formation);?>"><?php echo $formation->ypareo;?></a></h3>
	<h4>Status : <?php echo $formation->status; ?></h4>
<?php 
	$calendar=$calendars[$formation->ypareo];
	if(empty($calendar)) { ?>
		<p>Le calendrier n'a pas encore été créé.</p>
	<?php }
?>
	<div class="menuBtn hidden"><button type="button" data-form="formAddSession<?php echo $formation->id_formation; ?>">
	Créer une session exceptionnelle
	</button> 
</div>
	<div class="form" id="formAddSession<?php echo $formation->id_formation; ?>">
		<form action="<?php echo site_url('formation/createCalendar'); ?>" method="post" enctype="multipart/form-data" >

			<div  class="left">
				<p>
					<label for="date" class="inline">Date : </label> <input type="date" name="date" placeholder="jj/mm/dddd" required />
			
					<label for="hourStart" class="inline">De </label><input type="time" name="hourStart" value="09:00" required /> <label for="hourStop" class="inline"> à </label> <input type="time" name="hourStop" value="12:00"/>
				</p>
				<p>
					<label for="location" class="inline">Salle</label>
					<input name="location" placeholder="Ex : Bâtiment Esclangon - salle 203" class="long"></p>
				<p><input type="checkbox" name="skype" value="1" /> Skype </p>
				
			</div>
			<div class="left">
				<p>
					
				
					<input type="hidden" name="id_formation" value="<?php echo $formation->id_formation; ?>" />
					<input type="hidden" name="student" value="<?php echo $student->id; ?>" />
					<input type="hidden" name="particular" value="1" />
					<input type="hidden" name="type" value="<?php echo $type;?>" />
					<input type="hidden" name="step" value="-5" />
					<button value="Ajouter les sessions" >Créer le rendez-vous</button> 
				</p>
			</div>

		</form>
	</div>
	<form method="post" action="<?php echo site_url('formation/adminInscription'); ?>">
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
								$class=$meeting["id_student"]==$student->id ? "self" : "notAvailable" ;
							}
							?>
							<li class="meeting <?php echo $class; echo $meeting["particular"]=="1" ? " particular" :"";?>">
							
							<?php echo $meeting['hour']; 
								if($meeting["id_student"]==="0" || $meeting["id_student"]===$student->id){
							?>

								<div class="infobloc">
									<p><?php echo $day." • ".$meeting['hour']; ?></p>
									<p><?php echo $meeting['location']=='' ? "La salle vous sera précisée ultérieurement.": "Salle ".$meeting['location']; ?></p>
									<p><input type="checkbox" name="skype" value="1" <?php if($meeting['skype']==1) echo "checked"; ?> /> Skype </p>
									<button type="submit" value="<?php echo $meeting["id"]; ?>" name="meeting">Inscrire</button>
									<?php if($meeting["id_student"]===$student->id){?>
										<button type="submit" value="<?php echo $meeting["id"]; ?>" name="cancelMeeting">Desinscrire</button>
									<?php } ?>
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
		<input type="hidden" name="id_formation" value="<?php echo $formation->id_formation; ?>"/>
		<input type="hidden" name="student" value="<?php echo $student->id; ?>"/>
		<input type="hidden" name="type" value="<?php echo $type; ?>"/>
		</form>
<?php }?>