<h2>Calendrier <?php echo $thisForm->ypareo; ?></h2>
<div class="form">
<h3>Importer des dates <?php echo $type=="candidate" ? "d'entretien de selection": "de soutenance"; ?></h3>
<form action="<?php echo site_url('formation/createCalendar'); ?>" method="post" enctype="multipart/form-data" >

	<div  class="left">
		<p>
			<label for="date" class="inline">Date : </label> <input type="date" name="date" placeholder="jj/mm/dddd" required id="date"/>
	
			<label for="hourStart" class="inline">De </label><input type="time" name="hourStart" value="09:00" required /> <label for="hourStop" class="inline"> à </label> <input type="time" name="hourStop" value="12:00"/>
			<label for="step" class="inline">Toutes les </label><input type="number" name="step" max="300" min="10" required /> minutes
		</p>
		<p>
			<label for="location" class="inline">Salle</label>
			<input name="location">
		</p>
	</div>
	<div class="left">
		<p>
			
		
			<input type="hidden" name="id_formation" value="<?php echo $thisForm->id; ?>" />
			<input type="hidden" name="type" value="<?php echo $type;?>" />
			<button value="Ajouter les sessions" >Ajouter les sessions</button> 
		</p>
	</div>

</form>
</div>
