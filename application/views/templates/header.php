<!DOCTYPE html>
<html>
<head>
	<meta name="robots" content="noindex, nofollow">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
	<title>
		Prise de rendez-vous | CFA des SCIENCES
	</title>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
	<link href="<?php echo base_url(); ?>styles/styles.css" type="text/css" rel="stylesheet"/>
	<link rel="shortcut icon" href="https://www.cfa-sciences.fr/sites/upmc/themes/bootstrap_upmc/favicon.ico" type="image/vnd.microsoft.icon" />
	<?php if(isset($imprime) && $imprime){ ?> <link href="<?php echo base_url(); ?>styles/stylesImpression.css" type="text/css" rel="stylesheet" media="print"/><?php } ?>
</head>
<body>
	<header>

		<nav>
			<ul>
					<li><a href="<?php echo site_url('pages/accueil'); ?>"><img id="logo_cfa" src="https://www.cfa-sciences.fr/sites/upmc/files/CFA%20des%20sciences%20simple_2.png" alt="Accueil" /></a></li>
				<?php  if(isset($this->session->user) && $this->session->user->type=="admin") { ?>
					<li><a href="<?php echo site_url('student/view'); ?>">APPRENTIS</a>
						<div class="submenu">
							<ul>
								<li class="submenu"><a href="<?php echo site_url("student/view/candidate"); ?>">Candidats</a></li>
								<li class="submenu"><a href="<?php echo site_url("student/view/student"); ?>">Admis</a></li>
						</div>
					</li>
					<li>Formations
						<div class="submenu">
							<ul>
								<?php if(count($formations)==0){?>
									<li>Pas de formation enregistrée</li>
								<?php }
								foreach($formations as $formation){?>
									<li class="submenu"><a href="<?php echo site_url('formation/admin/'.$formation->id); ?>"><?php echo $formation->ypareo; ?></a></li>

								<?php }?>
							</ul>
						</div>
					</li>
					<li>
						<a href="<?php echo site_url("formation/export"); ?>">Export</a>
					</li>
					<li>
						<a href="<?php echo site_url("admin/formations"); ?>">Admin</a>
						<div class="submenu">
							<ul>
								<li class="submenu"><a href="<?php echo site_url("admin/formations"); ?>">Formations</a></li>
								<li class="submenu"><a href="<?php echo site_url("admin/equipe"); ?>">Equipe</a></li>
								<!--<li class="submenu"><a href="<?php echo site_url("admin/contacts"); ?>">Contacts</a></li>-->
							</ul>
						</div>
					</li>
				<?php }else{
					?>
					<li><a href="<?php echo site_url('pages/accueil/contacts'); ?>" />Contacts</a></li>
					<?php
				}
				if(isset($this->session->user)){
				?>
				<li><a href="<?php echo site_url('login/disconnect'); ?>" />Deconnexion</a></li>
				<?php } ?>
			</ul>
		</nav>
	</header>
	<div class="global">
	<h1><?php if(isset($title)) echo $title; ?></h1>
