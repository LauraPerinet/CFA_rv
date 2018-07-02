
<div class="form naturalDisplay">
	<?php echo form_open('emailing/sendEmailProblem'); ?>
		<div class="left">
			<p class="left">Merci de nous expliquer la ou les raisons de votre impossibilité. Nous vous recontacterons afin de trouver un arrangement.</p>
		</div>
		<div >
			<input type="hidden" name="typeEmail" value="ne-peux-pas-venir" />
			<input type="hidden" name="id_formation" value="<?php echo $formation; ?>" />
			<textarea name="message" placeholder="Votre message" required></textarea>
		</div>

		<input type="submit" value="Envoyer" />
	</form>
</div>