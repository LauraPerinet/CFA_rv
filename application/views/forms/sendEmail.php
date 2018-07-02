<div >
<h2>Envoyer un email</h2>
<div class="form noHidden" >
	<form action="<?php echo site_url('emailing/sendEmail'); ?>" method="post" id="formEmail">
		<div>
			<label for="typeEmail">Email :</label>
			<select name="typeEmail">
				<option value="prise-de-rendez-vous" selected >Prise de rendez-vous</option>
				<option value="precision-salle">Précision salle</option>
				<option value="relance">Relance</option>
				<option value="rappel">Rappel</option>
				<option value="erratum-session-reportee">Erratum : Session annulée</option>
			</select>
		</div>
		<div>
			<div>
				<input type="radio" value="selection" name="to" />
				<label class="inline">Envoyer aux élèves selectionnés (<span class="txtStatus">concernés</span> uniquement)</label>
			</div>
			<div>
				<input type="radio" value="all" name="to" />
				<label class="inline" >Envoyer à tous les élèves <span class="txtStatus">concernés</span> </label>
			</div>
		</div>
		<div>
		<input type="hidden" name="type" value="<?php echo $type; ?>" />
		<input type="hidden" name="id_formation" value="<?php echo $thisForm->id; ?>" />
		<input type="hidden" name="days" value="<?php foreach($calendar as $key=>$day){ echo $key.", "; }?>" />
		<input type="submit" value="Envoyer" />
		</div>
	</form>
</div>
<?php //Script js formEmail inclus pour selection des emails selon status ?>
</div>