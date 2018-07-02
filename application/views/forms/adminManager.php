<h2>Créer un nouvel administrateur</h2>
<div class="form">
	<form action='ajoutAdmin' method="post" />
		<div>
			<label>Nom : </label><input name="name" required />
			<label>Prénom : </label><input name="firstname" required />
			
		</div>
		<div>
			<label>Email : </label><input name="email" type="email" required onchange="validityOff(this)"/>
			<label>Mot de passe : </label><input name="password" type="password" required />
			<label>Confirmez le mot de passe : </label><input type="password" required />
		</div>
		<div>
			<input type="submit"  value="Créer le compte" onclick="testPassword()"/>
		</div>
		</form>
</div>
<h2>Liste des administrateurs</h2>
<?php 
	
	foreach($referents as $admin){?>
		<form action='deleteAdmin/<?php echo $admin->id;?>' method="post" />
		<div>
			<label><?php echo $admin->name; ?> <?php echo $admin->firstname; ?></label>
			<input type="hidden" name="admin" value="<?php echo $admin->id; ?>" />
				<input type="submit" value="supprimer"/>
		</div>
		</form>
	<?php }
?>
<script>
	var pwd=document.querySelectorAll("input[type='password']");
	var email=document.querySelector("input[type='email']");
	
	function testPassword(){
		var domain=email.value.split("@")[1]
		if(domain!=="cfa-sciences.fr" && domain!=="cci-paris-idf.fr"){
			email.setCustomValidity("L'adresse courriel doit appartenir au domaine cfa-sciences.fr ou cci-paris-idf.fr.");
		}else{
			email.setCustomValidity("");
		}
		if(pwd[0].value!==pwd[1].value){
			pwd[1].setCustomValidity("Le mot de passe est différent.");
		}else{
			pwd[1].setCustomValidity("");
		}
	}
	
</script>

