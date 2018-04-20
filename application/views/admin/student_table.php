<div id="table<?php echo isset($type) ? $type:""; ?>" class="studentTable">
	<h2><?php echo $subtitle; ?></h2>

	<?php 
	if(empty($students)){ ?>
		<p>Aucun résultat ne correspond à votre requête.</p>
	<?php } else {
		
		if($title==='Gestion des apprentis'){
			echo form_open('student/deleteStudent'); 
	?>
			<button type="button" onclick="showPopUp('popupSup')">Supprimer</button>
			<div id="popupSup" class="popup hidden">
				<p>Attention !</p>
				<p>Vous vous apprêté à supprimer <span id="numDelete"></span> <?php echo $subtitle; ?></p>
				<div>
					<input type="submit" value="Oui, je sais" class="realDelete"/>
					<button type="button" onclick="showPopUp()">Non, surtout pas !</button>
				</div>
			</div>
		<?php }
			
		}  
		
		?>
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
		<?php foreach($students as $student){ ?>
			<tr>
		<td><input type="checkbox" name="student[]" <?php echo $title!=='Gestion des apprentis' ? 'class="'.$type.'" form="formEmail" ' : 'class="student"'; ?> value="<?php echo $student->id;?>"  /></td>
				<td ><a href="<?php echo site_url()."/student/cas-particulier/".$student->id; ?>"><?php echo $student->name." ".$student->firstname; ?></a></td>
				<td ><?php echo $student->email."<br/>".$student->phone; ?></td>
				<td ><?php if($title==='Gestion des apprentis'){ 
					foreach($student->formations as $formation){
						echo $formation->ypareo.'<br/>';
					}
					}else{echo $student->status; } ?>
				</td>
			</tr>
		<?php }?>
		</table>
		<input type="hidden" name="type" value="<?php echo isset($query['type']) ? $query['type'] : "candidate"; ?>" />
	</form>
</div>
<script>
	var selectAll=document.getElementById("selectAll<?php echo $title!=='Gestion des apprentis' ? $type :""; ?>");
	selectAll.addEventListener("click", selection);
	var hidden="true";
	var deletePopUp=document.getElementById("popupSup");
	var numToDelete=document.getElementById("numDelete");
	
	function init(){
		
	}
	function selection(e){
		var allStudents = document.querySelectorAll("input[type=checkbox].<?php echo $title!=='Gestion des apprentis' ? $type :"student"; ?>");
		for(var i=0; i<allStudents.length; i++){
			allStudents[i].checked=e.target.checked;
		}
	}
	
	function showPopUp( id ){
		j=0;
		var allStudents = document.querySelectorAll("input[type=checkbox].<?php echo $title!=='Gestion des apprentis' ? $type :"student"; ?>");
		for(var i=0; i<allStudents.length; i++){
			j=allStudents[i].checked ? j+1 : j;
		}
		if(j>0){
			if(hidden=!hidden){
				document.getElementById("numDelete").textContent=j;
				deletePopUp.classList.remove("hidden");
			}else{
				deletePopUp.classList.add("hidden");
			}
		
		}
	}
</script>