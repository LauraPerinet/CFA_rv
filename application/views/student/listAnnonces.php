
<div id="annoncesListe">
	<?php
foreach($annonces as $type=>$annoncesR){

	if($type=="expirate") {  $expiration="A expiré le ";?>
		<h2>Annonces expirées</h2>
	<?php }else if($type=="finish") { $expiration="A expiré le ";?>
		<h2>Offres pourvues</h2>
	<?php }else { $expiration="Expire le ";?>
		<h2>Annonces en cours</h2>
	<?php }
	if(count($annoncesR)==0) {?> <p>Aucune annonce enregistrée</p>
	<?php }
	foreach($annoncesR as $annonce){ ?>
		<div class="menuBtn hidden">
			<button type="button" class="annonceTitle" data-form="annonce<?php echo $annonce->id;?>">
				<span><?php echo $annonce->title; ?></span>
				<?php if($annonce->response===null){
					if($type==="valid"){ ?>
						<span>Expire le <?php echo Utils::getFullDate($annonce->expiration); ?></span>
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
						<button type="submit" name="interested" value="yes" class="linkBtn green">Je suis interessé(e)</button>
						<button type="submit" name="interested" value="no" class="linkBtn red">Je ne suis pas interessé(e)</button>
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

	}?>
</div>
