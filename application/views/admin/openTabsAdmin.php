
<div id="tabs" class="">
	<h2 class="<?php echo $adminmenu=="formations" || $adminmenu=="" ? "selected" :""; ?>">
		<a href=" <?php echo site_url('admin/formations'); ?>">Formations</a>
	</h2>
	<h2 class="<?php echo $adminmenu=="staff" ? "selected" :""; ?>">
		<a href=" <?php echo site_url('admin/equipe'); ?>">Équipe</a>
	</h2>
	<!--
	<h2 class="<?php echo $adminmenu=="contact" ? "selected" :""; ?>">
		<a href=" <?php echo site_url('admin/contacts'); ?>">Contacts</a>
	</h2>-->
</div>
