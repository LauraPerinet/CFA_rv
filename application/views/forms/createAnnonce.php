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
			<input name="title" placeholder="Ex : Développeur web full stack" class="long" value="<?php if(isset($annonce)) echo $annonce->title;?>" >
		</p>
		<p>
				<label for="text" >Descriptif</label>
				<textarea name="text" placeholder="Descriptif de l'annonce" id="text" required><?php if(isset($annonce)) echo $annonce->text;?></textarea>
		</p>
	</div>
	<div>
		<p>
			<label for="date" class="inline">Date d'expiration : </label> <input type="date" name="date" placeholder="jj/mm/dddd" required id="date" />

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

			<button >Envoyer l'annonce</button>
		</p>
	</div>

</form>
</div>
