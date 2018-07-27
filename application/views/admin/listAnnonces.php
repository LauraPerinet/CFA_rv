
<div id="annoncesListe">
	<?php if(count($annonces["valid"])>0 || count($annonces['expirate'])>0){?>
		<button type="button" class="deletepopup" data-id-annonce="all">Supprimer toutes les annonces</button>
<?php }
foreach($annonces as $type=>$annoncesR){

	if($type=="expirate") { ?>
		<h2>Annonces expirées</h2>
	<?php }else if($type=="finish") { ?>
		<h2>Offres pourvues</h2>
	<?php }else if($type=="autonomy"){ ?>
		<h2>Candidature autonome</h2>
	<?php }else{?>
		<h2>Annonces en cours</h2>
	<?php }
	if(count($annoncesR)==0) {?> <p>Aucune annonce enregistrée</p>
	<?php }
	foreach($annoncesR as $annonce){
		if($this->session->user->type=="admin"){
		?>

		<div class="menuBtn hidden">
			<button type="button" class="annonceTitle" data-form="annonce<?php echo $annonce->id;?>">
				<span><?php echo $annonce->title; ?></span>
				<?php
					if($type=="expirate"){
						if($annonce->cvSent){
							?>

							<span class="yellow">CVs envoyés</span>
						<?php }else{ ?>
							<span class="red">Envoyer les CVs</span>
						<?php }
					}else if($type=="finish"){ ?>
						<span class="green">Offre pourvue : <?php echo $annonce->student==="-1" ? "à l'exterieur" :
						"<a href=".site_url()."/student/casParticulier/student/".$annonce->student->id.">".$annonce->student->name." ".$annonce->student->firstname."</a>" ?>
						</span>
					<?php }else{
						$nbResponse=0;
						foreach($annonce->response as $response){
							if($response["interested"]!==null) $nbResponse++;
						}
						?>
						<?php if($annonce->expiration!==null){ ?><span>Expire le : <?php echo $annonce->expiration; ?>  </span>
					<?php }
					} ?>
					<span class="yellow"><?php echo $nbResponse; ?> réponse(s) </span>
					</span>
			</button>
		</div>
	<?php } ?>
		<div class="annonce hidden" id="annonce<?php echo $annonce->id;?>">
				<button type="button" class="deletepopup linkBtn deleteannonce" data-id-annonce="<?php echo $annonce->id; ?>">Supprimer l'annonce</button>
				<!--<a href="<?php echo site_url('formation/admin/'.$thisForm->id.'/student/modifAnnonce/'.$annonce->id.'#creationAnnonce'); ?>" class="modifAnnonce">Rééditer l'annonce</a>-->
				<?php
				if($type=="expirate" || $type="autonomy"){ ?>

						<form method="post" action="<?php echo site_url('annonce/update');?>" class="inline form noHidden">
							<input type="hidden" value="<?php echo $annonce->id; ?>" name="id"/>
							<input type="hidden" value="<?php echo $annonce->id_formation; ?>" name="id_formation"/>
							<?php if($annonce->cvSent==0 && $type=="expirate"){?>
									<button type="submit" name="cv">Envoyer les CVs</button>
							<?php }
							else if($annonce->student===null){ ?>
							<label>Apprentis placé : </label>
							<select name="student" >
									<option id="noBlackList" value="-1">Exterieur</option>
									<?php  foreach($annonce->response as $response){
										if($response["student"]->status=="Admis" || $response["student"]->id_status==10)?>
										<option value="<?php echo $response["student"]->id; ?>"><?php echo $response["student"]->name." ".$response["student"]->firstname; ?></option>
									<?php }?>
							</select>

							<button type="submit" name="studentPlace">Pourvoir l'offre</button>
						<?php } ?>
					</form>
					<?php
				}

				 ?>
				<div class="blocAnnonce">
				<div class="annonceTxt"><?php echo $annonce->text; ?></div>
				<?php if($this->session->user->type=="admin"){ ?>
				<div class="annonceAside">
					<div>
						<h3>Reponses</h3>
						<table>
							<tr>
									<th>Nom</th>
									<th>Interessé</th>
									<th>Pas interessé</th>

							</tr>
							<?php foreach($annonce->response as $response){ ?>
								<tr>
									<td><a href="<?php echo site_url()."/student/casParticulier/student/".$response["student"]->id; ?>"><?php echo $response["student"]->name." ".$response["student"]->firstname; ?></a></td>
									<td class="<?php if($response['interested']=="yes") echo "green";?>"><?php if($response['interested']=="yes") echo "X";?></td>
									<td class="<?php if($response['interested']=="no") echo "red";?>"><?php if($response['interested']=="non") echo "X";?></td>

								</tr>
							<?php } ?>
						</table>
					</div>
					<div>
						<h4>Blacklist</h4>
						<?php foreach($annonce->blacklist as $student){ ?>
								<button
									class="labelBlackList <?php if($type=='expirate') echo 'notActif'; ?>"
									type="button"
									title="Envoyer l'annonce"
									data-id-student="<?php echo $student->id; ?>"
									data-id-annonce="<?php echo $annonce->id; ?>">
									<?php echo $student->name." ".$student->firstname; if($type==='valid') echo "X"; ?> </button>
						<?php }?>
					</div>
			</div>
		<?php } ?>
		</div>
		</div>
	<?php }

	}?>
</div>
<div id="popupWhiteList" class="popup hidden">
		<form method="post" action="<?php echo site_url('annonce/toWhiteList'); ?>">
			<input type="hidden" name="id_student" id="input_id_student" />
			<input type="hidden" name="id_annonce" id="input_id_annonce"  />
			<input type="hidden" name="id_formation" value="<?php echo $thisForm->id; ?>" />
			<p>Souhaitez vous rendre l'annonce disponible à <span id="studentToWhiteList"></span> ?</p>
			<p>Un email lui sera envoyé.</p>
			<button type="submit" >Oui</button>
			<button type="button" class="closePopup">Non</button>
		</form>
</div>
<div id="popupDelete" class="popup hidden">
		<form method="post" action="<?php echo site_url('annonce/delete/'.$thisForm->id); ?>">
			<input type="hidden" name="id_annonce" id="input_id_annonce_delete"  />
			<p>Attention, la suppression est définitive </p>
			<button type="submit" >Oui, je sais !</button>
			<button type="button" class="closePopup">Je vais réfléchir</button>
		</form>
</div>
