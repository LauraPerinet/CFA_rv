<div id="selection<?php echo $type; ?>" >
<?php if(isset($query["candidate_status"])){ ?>
<h2>Recherche</h2>
	<div class="form noHidden">
		<?php echo form_open('formation/admin/'.$thisForm->id,array('id'=>"selectionForm",'method'=>'post')); ?>
			<div>
				<label for="status">Statut : </label>
				<select name="<?php echo $type; ?>_status" id="status">
				
					<option value="all">Tous</option>
					<?php foreach($status as $stat){ ?>
						<option value="<?php echo $stat->id; ?>" <?php if(isset($query[$type.'_status'])) echo $query[$type.'_status']===$stat->id ? "selected" : ""; ?>><?php echo $stat->status; ?></option>
					<?php }?>
				</select>
				<input type="checkbox" name="relance" value="1" <?php if(isset($query["relance"]) && $query["relance"]==1) echo "checked"; ?>><label class="inline"> Relances </label>
				<label>Dernier changement de statut</label>
				<label class="inline">Entre </label>
				<input type="date" value="<?php echo isset($query["between"]["from"]) ? $query["between"]["from"] : $olderModif; ?>" name="from"/> 
				<label class="inline"> et </label>
				<input type="date" value="<?php echo isset($query["between"]["to"]) ? $query["between"]["to"] : date('Y-m-d'); ?>" name="to"/>
			</div>
			<div>
			
			<input type="submit" value="Rechercher" form="selectionForm"/></div>
		</form>
	</div>
<?php } ?>
</div>