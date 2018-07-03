
<div id="calendar<?php echo $type;?>">
<?php echo form_open('formation/changeCalendar'); ?>


<?php
	if(empty($calendar)){?> <p>Aucun calendrier n'est actuellement enregistré.</p>
	<?php }else{
	?>
		<button type="button" id="roomsBtn">Cacher les salles</button>
		<div id="calendars">

			<?php
			$i=0;
			foreach($calendar as $day=>$meetingsByDay){?>
				<div class="dayBox">
					<div class="dayLign">
						<input type="checkbox" class="selectAll" data-day="meeting<?php echo $i; ?>"/>
						<?php echo $day; ?>
					</div>
					<ul class="meetings">
						<?php
						$location="";
						foreach($meetingsByDay as $meeting){
							if($meeting["location"]=="") $meeting["location"]="SALLE NON PRECISEE";
							if($location!==$meeting["location"]){ ?>
								<li class="loc clear"><?php $location=$meeting["location"]; echo $location ?></li>
							<?php } ?>
							<li class="meeting <?php echo $meeting["id_student"]==="0" ? "available" : "notAvailable "; echo $meeting["particular"]=="1" ? "particular" :"";?>">

							<input type="checkbox" name="meeting[]" class="meeting<?php echo $i; ?>" value="<?php echo $meeting['id']; ?>" />
							<?php echo $meeting['hour']; ?>
							<?php if($meeting["id_student"]!=="0"){ ;?>
								<div class="infobloc">

									<?php foreach($students as $student){
										if($student->id==$meeting["id_student"]){ ?>
											<p><a href="<?php echo site_url()."/student/casParticulier/".$type.'/'.$student->id; ?>" ?>"<?php echo $student->name;?> <?php echo $student->firstname;?></a></p>
											<p><?php echo $student->email;?></p>
											<p><?php echo $student->phone;?></p>
										<?php }
									}?>
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
	<?php }
?>




	<div id="CalendartoHide">
		<button type="button" onclick="showPopUp('popupSup', 'meetings')">Supprimer</button>
					<div id="popupSup" class="popup hidden">
						<p>Attention !</p>
						<p>Êtes vous certain de vouloir supprimer ces sessions ?</p>
						<p><span id="numDelete" class="hidden">Des étudiants sont déjà positionnés !</span></p>
						<div>
							<input type="hidden" name="type" value="<?php echo $type;?>" />
							<input type="hidden" name="id_formation" value="<?php echo $thisForm->id;?>" />
							<input type="submit" name="delete" value="Tout à fait certain" />
							<button type="button" onclick="showPopUp()">Non, surtout pas !</button>
						</div>
					</div>
		<div class="menuBtn hidden"><button  type="button" data-form='formChangeLocation'>
			Modifier la salle
			</button>
		</div>
		<div class="form " id="formChangeLocation">
				<label class="inline">Salle : </label><input name="location" placeholder="Ex : Bâtiment Esclangon - salle 203" class="long">
				<input type="submit" name="changeLocation" value="Changer la salle" />

		</div>
	</div>
	</form>
</div>
