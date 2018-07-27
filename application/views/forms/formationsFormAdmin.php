
<h2>Modifier et supprimer les formations</h2>
<?php
		if(count($formations)==0){ ?>
						<p>Aucune formation enregistrée</p>
				<?php }else{?>
			<table class="admin">
				<tr>

					<th>
						Informations
					</th>
					<th >
						Administrateurs
					</th>
					<th></th>
				</tr>
			<?php foreach($formations as $formation){ ?>

				<tr>
					<td>
						<table>
							<tr>
								<th>Code Ypareo</th>
								<td>
									<?php echo $formation->ypareo; ?>
								</td>
							</tr>
							<tr>
								<th>Nom entier</th>
								<td>
									<?php echo $formation->formation; ?>
								</td>
							</tr>
							<tr>
								<th>Url site CFA</th>
								<td>
									<?php echo $formation->url!== null ? '<a href='.$formation->url.' target="_blank">'.$formation->url.'</a>' : "non renseignée"; ?>
								</td>
							</tr>
						</table>
					</td>
					<td>
						<?php foreach($formation->staff as $key=>$staff){
							if($key==="admin"){ ?>
								<h4>Administrateurs CFA</h4>
							<?php }else{?>
								<h4>Partenaires universitaires</h4>
							<?php }
							if(count($staff)==0){?>
								<p>Aucun administrateur n'est enregistré</p>
							<?php }else{
							?>
							<ul>
								<?php foreach($staff as $admin){ ?>
									<li><?php echo $admin->name.' '.$admin->firstname; ?></li>
								<?php }?>
						 </ul>
					 <?php }}?>
					</td>
					<td>
						<form action='<?php  echo site_url('admin/modificationFormation/'.$formation->id); ?>' method="post" />
								<button class="linkBtn" type="submit" name="modification">Modifier</button>

									<button class="linkBtn deletebtn" type="submit" name="erase">Supprimer</button>

							</form>
					</td>

			<?php } ?>
		</tr>
	</table>
<?php }
?>
