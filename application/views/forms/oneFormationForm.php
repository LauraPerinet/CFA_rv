<?php if(isset($adminmenu)){
	$fromAdmin=true;
	?>
	<a class="linkBtn back" href="<?php echo site_url("admin/formations"); ?>">Retour aux formations</a>
	<h2><?php echo $thisForm->ypareo; ?></h2>
<?php }else{
	$fromAdmin=false;
?>
<h2>Référents de la formation</h2>
<div >
	<div>
		<h4>Référents CFA</h4>
		<ul>
			<?php
			foreach($admin as $adm){
				if($adm->isRef!==null){?>
					<li>•<?php echo $adm->firstname." ".$adm->name; ?><li>
			<?php }}?>
		</ul>
	</div>
	<div>
		<h4>Référents Universitaires</h4>
		<ul>
			<?php foreach($staffpart as $adm){
				if($adm->isRef!==null){?>
					<li>•<?php echo $adm->firstname." ".$adm->name; ?><li>
			<?php }}?>
		</ul>
	</div>
</div>

<div class="menuBtn hidden"><button  type="button" data-form='addReferent'>
	Modifier la formation
	</button>
</div>
<?php } ?>
<div class="form" id="addReferent">
	<form method="post" action="<?php echo site_url('formation/updateFormation/'.$thisForm->id.'/'.$fromAdmin); ?>">
		<div>
			<label for="name">Nom entier : </label>
			<input name="name" value="<?php echo $thisForm->formation;?>" class="long" id="name"/>
			<label for="url">URL associée sur le site du CFA : </label>
			<input name="url" value="<?php echo $thisForm->url!== null ? $thisForm->url : "";?>" class="long" id="url" placeholder="https://www.cfa-sciences.fr/physique-optique/licence-professionnelle-optique-optronique-instrumentation-liovis"/>
		</div>
		<div>
			<label >Référents CFA</label>
			<ul>
			<?php foreach($admin as $adm){
				if($adm->DefaultRef==0){ ?>
					<li>
							<input type="checkbox" name="admin[]" class="referend" value="<?php echo $adm->id; ?>" <?php if(!empty($adm->isRef)) echo "checked"; ?>/>
						<?php echo $adm->firstname." ".$adm->name; ?>

			</li>
			<?php }}?>
		</ul>
		</div>
		<div>
			<label >Référents Universitaires</label>
			<ul>
			<?php foreach($staffpart as $adm){ ?>
				<li>
					<input type="checkbox" name="staffpart[]" class="referend" value="<?php echo $adm->id; ?>" <?php if(!empty($adm->isRef)) echo "checked"; ?>/>
				<?php echo $adm->firstname." ".$adm->name; ?>

			</li>
		</ul>
			<?php }?>
		</div>
		<input type="hidden" name="id_formation" value="<?php echo $thisForm->id; ?>" />
		<input type="submit" value="Envoyer" />
	</form>
</div>
