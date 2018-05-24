<h2>Référent_s de la formation</h2>
<ul>
	<?php foreach($admin as $adm){
				if(!empty($adm->isRef)) echo '<li>• '.$adm->firstname." ".$adm->name.'<li>'; 
	}?> 
</ul>
<div class="menuBtn hidden"><button  type="button" data-form='addReferent'>
	Modifier les référents
	</button> 
</div>
<div class="form" id="addReferent">
	<?php echo form_open('formation/changeReferend'); ?>
		
		<div>
			<p id="refError" class="hidden error">Vous devez entrer au moins un référent.</p>
			<label for="email">Référents </label>
			<?php foreach($admin as $adm){
				echo $adm->firstname." ".$adm->name; ?> 
				<input type="checkbox" name="admin[]" class="referend" value="<?php echo $adm->id; ?>" <?php if(!empty($adm->isRef)) echo "checked"; ?>/>
			<?php }?>
		</div>
		<input type="hidden" name="id_formation" value="<?php echo $thisForm->id; ?>" />
		<input type="submit" value="Envoyer" onclick="testReferend();"/>
	</form>
</div>
<script>
	var ref=document.getElementsByClassName("referend");
	var pError=document.getElementById("refError");
	function testReferend(){
		var i=0;
		for(var j=0;j<ref.length; j++){
			if(ref[j].checked==true){
				i++;
				break;
			}
		}
		if(i==0){
			pError.classList.remove("hidden");
			document.getElementById("refError").setCustomValidity(" ");
		}else{
			document.getElementById("date").setCustomValidity("");
		}
	}
</script>