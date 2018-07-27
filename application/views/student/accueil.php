<div class="student">
<h2>Bonjour <?php echo $student->firstname.' '.$student->name;?></h2>
<?php if(isset($this->session->user->message) && !empty($this->session->user->message)){  ?> <div class="msgSent"><?php echo $this->session->user->message; ?></div><?php } ?>
<h3><?php echo $subtitle; ?></h3>
<?php foreach($formations as $form){ ?>
	<div class="liste_formation_student">
	<h4><?php echo $form->formation;
		if($form->url!==null){ ?>
			<a href="<?php echo $form->url;?>" target="_blank" class="linkFormation">Voir la fiche formation</a>
		<?php }?>
	</h4>
	<?php
		if($form->id_status <=1){ ?>
			<p> Le calendrier des entretiens n'a pas encore été enregistré. Vous recevrez sous peu un courriel vous proposant de vous positionner sur un créneau.</p>

		<?php }else if($form->id_status == 2){ ?>
			<p>Nous souhaitons vous rencontrer lors d'un entretien de motivation. Merci de vous positionner sur un des créneaux disponibles.</p>
			<p><a  href="<?php echo site_url('formation/inscription/'.$form->id); ?>"/>Choississez votre creneau</a></p>

		<?php }elseif($form->id_status == 5) {?>

			<p>Le CFA a pris note de votre empêchement et vous rappelle rapidement.</p>
			<p><a href="<?php echo site_url('formation/inscription/'.$form->id); ?>"/>Voir le calendrier</a></p>

	<?php }elseif($form->id_status == 3 || $form->id_status == 4){ ?>
			<p>Vous avez rendez-vous pour votre entretien
			<p class="center"><?php echo $form->meeting->dateRV; ?></p>
			<p class="center">Sorbonne Université • Faculté des Sciences</p>
			<p class="center">4, place Jussieu, 75005 Paris</p>
			<p class="center">Métro Jussieu (lignes 7 et 10) </p>
			<p class="center"><?php echo empty($form->meeting->location) ? "La salle vous sera précisée ultérieurement" : "Salle ".$form->meeting->location; ?></p>

			<?php if($form->meeting->canChange){?>
			<p>Vous pouvez <a href="<?php echo site_url('formation/inscription/'.$form->id); ?>"/>modifier cette date</a> jusqu'à deux jours ouverts pleins avant l'entretien.</p>
			<?php }
		}elseif($form->id_status == 7){ ?>
				<p>Votre entretien a été annulé à votre demande.</p>
				<p><a href="<?php echo site_url('formation/inscription/'.$form->id); ?>"/>Voir le calendrier</a></p>
			<?php }
	?>
	</div>
<?php }?>
</div>
