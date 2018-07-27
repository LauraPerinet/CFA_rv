<h2>Recrutement <?php echo $thisForm->ypareo; ?></h2>
<div class="menuBtn hidden" id="creationAnnonce"><button type="button" data-form="formAddAnnonce">
	Créer une nouvelle annonce 	</button>
</div>
<div class="form <?php if(isset($annonce)) echo 'noHidden'; ?>" id="formAddAnnonce">
<h3>Nouvelle annonce</h3>
<form class="" action="<?php  echo site_url('annonce/create'); ?>" method="post" enctype="multipart/form-data" >

	<div  class="left">
		<p>
			<label for="title" class="inline">Intitulé de l'annonce</label>
			<input id="title" name="title" placeholder="Ex : Développeur web full stack" class="long" value="<?php if(isset($annonce)) echo $annonce->title;?>" >
		</p>
		<p>
				<label for="text" >Descriptif</label>
				<textarea name="text" placeholder="Descriptif de l'annonce" id="text" required><?php if(isset($annonce)) echo $annonce->text;?></textarea>
		</p>
	</div>
	<div class="left">
		<p>
			<input type="checkbox" name="autonomy"  id="autonomy" />
			<label for="autonomy" class="inline"> Contacter directement l'entreprise </label>
		</p>
		<p>
			<label for="date" class="inline">Date d'expiration : </label> <input type="date" name="date" placeholder="jj/mm/dddd" id="date" />
		</p>
		<p>
			<label for="studentWhiteList">Admis non concernés</label>

			<select name="blackList[]" id="studentWhiteList" multiple size="3">
					<option id="noBlackList" value="">Aucun</option>
					<?php foreach( $students as $student ){
						if($student->status=="Admis" || $student->id_status==10)?>
						<option value="<?php echo $student->id; ?>"><?php echo $student->name." ".$student->firstname; ?></option>
					<?php }?>
			</select>
			<div id="blackList">
			</div>
		</p>
		<p>Valider cette annonce enverra un email aux admis concernés</p>
		<p>


			<input type="hidden" name="id_formation" value="<?php echo $thisForm->id; ?>" />

			<button onclick="testValid()">Envoyer l'annonce</button>
		</p>
	</div>

</form>
</div>
<script>
// createAnnonce script
var autonomy=document.getElementById("autonomy");
var date=document.getElementById("date");

function testValid(){

	if(autonomy.checked==false && date.value==""){

		date.setCustomValidity('Vous devez indiquer une limite d\'envoi des CVs ou cocher " Contacter directement l\'entreprise"');
	}else{
		date.setCustomValidity('');
	}
}
</script>
