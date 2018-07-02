<div id="tabs" class="">
	<h2 class="<?php echo $type=="candidate" ? "selected" :""; ?>">
		<a href=" <?php echo site_url('formation/admin/'.$thisForm->id.'/candidate'); ?>">Candidats</a>
	</h2>
	<h2 class="<?php echo $type=="student" ? "selected" :""; ?>">
		<a href=" <?php echo site_url('formation/admin/'.$thisForm->id.'/student'); ?>">Admis</a>
	</h2>
</div>
