<h2>Envoyer un email</h2>
<div class="form">
	<?php echo form_open('emailing/sendEmail',array('id'=>"formEmail",'method'=>'post')); ?>
		<div>
			<label for="typeEmail">Email :</label>
			<select name="typeEmail">
				<option value="prise-de-rendez-vous">Prise de rendez-vous</option>
			</select>
		</div>
		<input type="hidden" name="type" value="<?php echo $type; ?>" />
		<input type="hidden" name="id_formation" value="<?php echo $thisForm->id; ?>" />
		<input type="hidden" name="days" value="<?php foreach($calendar as $key=>$day){ echo $key.", "; }?>" />
		<input type="submit" value="Envoyer" />
</div>