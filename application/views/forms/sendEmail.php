<div id="toHide">
<h2>Envoyer un email</h2>
<div class="form noHidden">
	<form action="<?php echo site_url('emailing/sendEmail'); ?>" method="post" id="formEmail">
		<div>
			<label for="typeEmail">Email :</label>
			<select name="typeEmail">
				<option value="prise-de-rendez-vous">Prise de rendez-vous</option>
				<option value="precision-salle">Précision salle</option>
				<option value="erratum-session-reportee">Erratum : Session annulée</option>
			</select>
		</div>
		<input type="hidden" name="type" value="<?php echo $type; ?>" />
		<input type="hidden" name="id_formation" value="<?php echo $thisForm->id; ?>" />
		<input type="hidden" name="days" value="<?php foreach($calendar as $key=>$day){ echo $key.", "; }?>" />
		<input type="submit" value="Envoyer" />
	</form>
</div>
</div>