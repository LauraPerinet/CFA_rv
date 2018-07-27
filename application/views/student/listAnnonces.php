
<div id="annoncesListe">
	<?php
	$types=['valid', 'autonomy', 'expirate', 'finish'];
	$goOn=0;
foreach($types as $type){

	if(count($annonces[$type])>0){
		if($type=="expirate") {
			$goOn++;
			$expiration="A expiré le ";?>
			<h2>Offres expirées</h2>
		<?php }else if($type=="finish") {
			$goOn++;
			$expiration="A expiré le ";?>
			<h2>Offres pourvues</h2>
		<?php }else if($type=="autonomy"){
			$goOn++; ?>
			<h2>Contactez l'entreprise directement</h2>
		<?php }else{
			$goOn++;
			$expiration="Expire le ";?>
			<h2>Le CFA envoie les CVs</h2>
		<?php }
 }

	foreach($annonces[$type] as $annonce){ ?>
		<div class="menuBtn hidden">
			<button type="button" class="annonceTitle" data-form="annonce<?php echo $annonce->id;?>">
				<span><?php echo $annonce->title; ?></span>
				<?php if($annonce->response===null){
					if($type==="valid" || $type==="autonomy"){
						if($type==="valid"){?><span class="expiration">Expire le <?php echo Utils::getFullDate($annonce->expiration); ?></span><?php } ?>
						<span class="yellow">Nouveau</span>
					<?php }else{ ?>
						<span class="">Ignorée</span>
					<?php }

				 }else if($annonce->response==="yes"){ ?>
					<span class="green">Postulé</span>
			<?php	}else if($annonce->response==="no"){ ?>
					<span class="red">Pas interessé(e)</span>
			<?php	} ?>

			</button>
		</div>
		<div class="annonce hidden" id="annonce<?php echo $annonce->id;?>">
				<?php if($annonce->response===null){
					if($type=="expirate" || $type=="finish"){ ?>
						<p>Vous n'avez pas postulé à cette annonce</p>
					<?php }else{
					?>
					<form method="post" action="<?php echo site_url('annonce/interested');?>" class="studentInterest"/>
						<input type="hidden" name="id" value="<?php echo $annonce->id; ?>"/>
						<button type="submit" name="interested" value="yes" class="green" onsubmit="return popupAnnonce('<?php echo $type; ?>')">
							<?php if($type=="valid"){ ?>
								Je suis interessé(e)
							<?php }else{ ?>
								J'ai candidaté
							<?php }?>

						</button>
						<button type="submit" name="interested" value="no" class="red">Je ne suis pas interessé(e)</button>
					</form>
				<?php }
				}else{
					if($annonce->response==="yes"){ ?>
						<p>Vous avez postulé à cette annonce.</p>
					<?php }else{ ?>
						<p>Vous avez indiqué n'être pas interessé par cette annonce.</p>
					<?php }
				}?>

				<p><?php

				echo $annonce->text; ?></p>
		</div>
	<?php
		}

	}
	if($goOn==0){ ?>
	 <p>Aucune annonce n'est enregistrée pour le moment.</p>
	<?php }
	?>
</div>
<div id="popup" class="popup hidden">
	<p class="valid">Le CFA enverra prochainement votre CV à l'entreprise.</p>
	<p class="autonomy">Le CFA n'envoie pas de CV pour cette annonce. <br/>Envoyez votre CV et lettre de motivation au contact indiqué</p>
	<div>
		<button type="button" onclick="popup(null)">ça marche</button>
	</div>
</div>
