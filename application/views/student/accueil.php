<h2>Bonjour <?php echo $student->firstname.' '.$student->name;?></h2>
<?php if(isset($this->session->user->message) && !empty($this->session->user->message)){ ?> <p><?php echo $this->session->user->message; ?></p><?php } ?>
<h3><?php echo $subtitle; ?></h3>
<?php foreach($formations as $form){ ?>
	<h4><?php echo $form->formation; ?></h4>
	<?php 
		if($form->id_status <=1){ ?>	<p> Le calendrier des entretiens n'a pas encore été enregistré. Vous recevrez sous peu un courriel vous proposant de vous positionner sur un créneau.</p>

		<?php }else if($form->id_status == 2){ ?><p><a href="<?php echo site_url('formation/inscription/'.$form->id); ?>"/>Choississez votre creneau.</a></p>

		<?php }elseif($form->id_status == 5) {?> <p>Le CFA a pris note de votre empêchement et vous rappelle rapidement.</p>
	<?php }else{ ?>
		<p>Vous êtes déjà positionné.</p>
	<?php }
	?>
<?php }?>

