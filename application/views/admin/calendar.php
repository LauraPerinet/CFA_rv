
<div id="calendar<?php echo $type;?>">
<?php echo form_open('Formation/deleteCalendar'); ?>
		<input type="hidden" name="type" value="<?php echo $type;?>" />
		<input type="hidden" name="id_formation" value="<?php echo $thisForm->id;?>" />
		<input type="submit" value="Supprimer les sessions selectionnées" />


<?php
	if(empty($calendar)){?> <p>Aucun calendrier n'est actuellement enregistré.</p>
	<?php }else{ 
	?>
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
								<li class="clear"><?php $location=$meeting["location"]; echo $location ?></li>
							<?php } ?>
							<li class="meeting <?php echo $meeting["id_student"]==="0" ? "available" : "notAvailable ".$meeting["id_student"]; ?>">
							
							<input type="checkbox" name="meeting[]" class="meeting<?php echo $i; ?>" value="<?php echo $meeting['id']; ?>" />
							<?php echo $meeting['hour']; ?>
							<?php if($meeting["id_student"]!=="0"){ ?>
								<div class="infobloc">
									<?php foreach($students as $student){
										if($student->id==$meeting["id_student"]){ ?>
											<p><a href="<?php echo site_url()."/student/cas-particulier/".$student->id; ?>" ?>"<?php echo $student->name;?> <?php echo $student->firstname;?></a></p>
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
	


</form>
</div>
<script>
	var selectAll=document.getElementsByClassName("selectAll");
	var meetings=document.getElementsByClassName("meeting");
	
	for(var i=0; i<selectAll.length; i++){
		selectAll[i].addEventListener("click", selection);
	}


	function selection(e){
		var allMeetings = document.querySelectorAll("input[type=checkbox]."+e.target.getAttribute("data-day"));
		for(var i=0; i<allMeetings.length; i++){
			allMeetings[i].checked=e.target.checked;
			var li= allMeetings[i].parentNode;
			if(e.target.checked){
				if(!li.classList.contains("selected")) li.classList.add("selected");
			}else{
				li.classList.remove("selected");
			}
		}
	}

</script>