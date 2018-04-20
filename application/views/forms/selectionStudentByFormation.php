<div id="selection<?php echo $type; ?>">
<h2>Recherche</h2>
	<div class="form">
		<form action="" method="post" />
			<div>
				<label for="status">Statut : </label>
				<select name="<?php echo $type; ?>_status" id="status">
					<option value="null">Tous</option>
					<?php foreach($status as $stat){?>
						<option value="<?php echo $stat->id; ?>" <?php if(isset($query[$type.'_status'])) echo $query[$type.'_status']===$stat->id ? "selected" : ""; ?>><?php echo $stat->status; ?></option>
					<?php }?>
				</select>
			</div>
			<div><input type="submit" value="Rechercher" /></div>
		</form>
	</div>
</div>