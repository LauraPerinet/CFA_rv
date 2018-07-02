<h2>Bonjour </h2>
<?php if(isset($students)){
	foreach($students as $type=>$infos){ ?>
			<h3><?php echo $type;?></h3>
			<div class="infographie">
		<?php
			foreach($infos as $label=>$value){
				if($value["nb"]>0){?>

					<a href="<?php echo $value["href"]; ?>">
					<div class="nb">
						<p class="<?php echo $value["class"];?>"><?php echo $value["nb"]; ?></p>
						<p><?php echo $label; ?></p>
					</div>
					</a>
				<?php }else{
					if($label=="candidats" || $label=="admis"){ ?>
						<p>Pas de <?php echo $label; ?> enregistrés pour le moment</p>
					<?php }
				}
			}?>
			</div>
	<?php }
}?>
