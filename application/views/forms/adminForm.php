<h2>Gestion des équipes</h2>
<div class="menuBtn hidden" >
	<button type="button" data-form="formAddStaff">
	Entrer un nouveau membre	</button>
</div>

<div class="form" id="formAddStaff">
	<form action='ajoutAdmin' method="post" />
		<div>
			<label>Type :</label>
				<input type="radio" value="admin" name="type" checked onclick="admin(true)"> CFA |
				<input type="radio" value="staffpart" name="type" onclick="admin(false)"> Partenaire
			<label for="name">Nom : </label><input name="name" id="name" required />
			<label for="firstname">Prénom : </label><input name="firstname" id="firstname" required />

		</div>
		<div>
			<label for="email">Email : </label><input name="email" id="email" type="email" required />
			<label for="password">Mot de passe : </label><input name="password" id="password" type="password" required />
			<label for="password2">Confirmez le mot de passe : </label><input type="password" id="password2" required />
		</div>
		<div>
			<input type="submit"  value="Créer le compte" onclick="testPassword()"/>
		</div>
		</form>
</div>
<?php
	$types=["admin"=>$referents, "staffpart"=>$staffpart];

	foreach($types as $key=>$type){

			if($key==="admin"){
					$name="administrateurs CFA";
			}else{
				$name="partenaires universitaires";
			} ?>
				<h3>Liste des <?php echo $name; ?></h3>
				<?php if(count($type)==0){ ?>
						<p>Aucun <?php echo $name; ?> enregistré</p>
				<?php }else{?>
			<table class="admin">
				<tr>
					<th>
						Nom
					</th>
					<th>
						Email
					</th>
					<th >
						Formations
					</th>
					<th></th>
				</tr>
			<?php foreach($type as $admin){ ?>
				<tr>
					<td>
						<?php echo $admin->name; ?> <?php echo $admin->firstname; ?>
					</td>
					<td>
						<?php echo $admin->email; ?>
					</td>
					<td><ul>
						<?php

							foreach($admin->formations as $formation){ ?>
								<li><?php echo $formation->ypareo; ?></li>
							<?php }
						 ?>
					 </ul>
					</td>
					<td>
						<form action='changeAdmin/<?php echo $admin->id.'/'.$key; ?>' method="post" />
								<button class="linkBtn" type="submit" name="update">Modifier</button>
						<?php if(isset($admin->DefaultRef) && $admin->DefaultRef==1){ ?>

						<?php }else{ ?>
									<button class="linkBtn deletebtn" type="submit" name="delete">Supprimer</button>

						<?php } ?>
							</form>
					</td>

			<?php } ?>
		</tr>
	</table>
<?php }}
?>


<script>
	var pwd=document.querySelectorAll("input[type='password']");
	var email=document.querySelector("input[type='email']");
	var cfa=true;

	function testPassword(){
		if(cfa){
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
	}

	function admin(isCFA){

			if(isCFA){
				for(var i=0; i<pwd.length;i++){
					pwd[i].setAttribute("required", isCFA);
				}
				email.setAttribute("required", isCFA);
			}else{
				for(var i=0; i<pwd.length;i++){
					pwd[i].removeAttribute("required");
				}
				email.removeAttribute("required");
			}

		cfa=isCFA;
	}

</script>
