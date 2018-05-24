<div id="table<?php echo isset($type) ? $type:""; ?>" class="studentTable">
	<h2><?php echo $subtitle; ?></h2>

	<?php 
	if(empty($students)){ ?>
		<p>Aucun résultat ne correspond à votre requête.</p>
	<?php } else {
		
		if($title==='Gestion des apprentis'){
			echo form_open('student/deleteStudent'); 
	?>
			<button type="button" id="toHide" onclick="showPopUp('popupSup', 'students')">Supprimer</button>
			<div id="popupSup" class="popup hidden">
				<p>Attention !</p>
				<p>Vous vous apprêtez à supprimer <span id="numDelete"></span> <?php echo $subtitle; ?></p>
				<div>
					<input type="submit" value="Oui, je sais" class="realDelete"/>
					<button type="button" onclick="showPopUp(null, null)">Non, surtout pas !</button>
				</div>
			</div>
		<?php } ?>
			<table id="container">
			<tr>

				<th><input type="checkbox" id="selectAll<?php echo $title!=='Gestion des apprentis' ? $type :""; ?>" /></th>
				<th>Nom</th>
				<th>Contact</th>
				<th><?php if($title==='Gestion des apprentis'){ 
				?>Formation(s)<?php }else{ ?>
					Statut
				<?php } ?>
				</th>
			</tr>
		<?php foreach($students as $student){;
			if($title!=='Gestion des apprentis' || count($student->formations)>0 ){ ?>
			<tr class="status<?php echo isset($student->id_status) ? $student->id_status : ""; ?>">
		<td><input type="checkbox" name="student[]" <?php echo $title!=='Gestion des apprentis' ? 'class="'.$type.'" form="formEmail" ' : 'class="student"'; ?> value="<?php echo $student->id;?>"  /></td>
				<td ><a href="<?php echo site_url()."/student/casParticulier/".$type."/".$student->id; ?>"><?php echo $student->name." ".$student->firstname; ?></a></td>
				<td ><?php echo $student->email."<br/>".$student->phone; ?></td>
				<td ><?php if($title==='Gestion des apprentis'){ ?>
					<table>
					<?php foreach($student->formations as $formation){ 
						if( !isset($query["formation"]) || (isset($query["formation"]) && $query["formation"]==$formation->id_formation)){
					?>	
							<tr>
								<td><a href="<?php echo site_url("formation/admin/".$formation->id_formation); ?>"><?php echo $formation->ypareo; ?></a></td>
								<td class="status<?php echo $formation->id_status; ?>"><?php echo $formation->status; ?></td>
							</tr>
						
						<?php }
						} ?>
					</table>
					<?php }else{echo $student->status; } ?>
				</td>
			</tr>
			<?php }
		}?>
		</table>
		<input type="hidden" name="type" value="<?php echo isset($query['type']) ? $query['type'] : "candidate"; ?>" />
		<?php }  
		
		?>
		

</div>
<script>
	//admin student_table
	var selectAll=document.getElementById("selectAll<?php echo $title!=='Gestion des apprentis' ? $type :""; ?>");
	var allStudents = document.querySelectorAll("input[type=checkbox].<?php echo $title!=='Gestion des apprentis' ? $type :"student"; ?>");
	var checked=0;
	
	for(var i=0; i<allStudents.length;i++){
		allStudents[i].addEventListener("click", function(e){
			checked = e.target.checked ? checked +1: checked -1;
			hideButton();
		});
	}
	var toHide=document.getElementById("toHide");
	
	toHide.classList.add("hidden");
	selectAll.addEventListener("click", selection);

	function selection(e){
		for(var i=0; i<allStudents.length; i++){
			allStudents[i].checked=e.target.checked;
		}
		checked= e.target.checked ? allStudents.length : 0;
		hideButton();
	}
	
	function hideButton(){
		
		if(checked==0){
			toHide.classList.add("hidden");
			selectAll.checked=false;
		}else{
			toHide.classList.remove("hidden");
		}
		
	}

</script>