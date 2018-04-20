<h2>Enregistrez-vous pour poursuivre</h2>
<?php echo validation_errors(); ?>
<?php echo form_open('login/view'); 

?>
<label for="type">Vous êtes </label>
<select name="type">
	<option value="candidate">candidat à une ou plusieurs formation</option>
	<option value="student">apprentis au cfa</option>
</select>
<h5>Email</h5>
<input type="text" name="email" value="" size="50" />
<h5>Mot de passe</h5>
<input type="text" value="" name="password" size="50" />
<div><input type="submit" value="Signer" /></div>


</form>
