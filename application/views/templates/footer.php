<footer>
	<!--
	<a href="<?php echo site_url('pages/accueil/contacts'); ?>">Contact</a>
-->
<em>&copy; CFA des SCIENCES 2018</em>
<div>
			<a href="https://www.sorbonne-universite.fr/" target="_blank"><img src="https://www.cfa-sciences.fr/sites/upmc/files/styles/logo_part/public/thumbnails/image/LOGO_SU_HORIZ_SEUL_RVB.png?itok=hJm1JGrS" alt="Sorbonne Université"/></a>

			<a href="http://www.cci-paris-idf.fr/" target="_blank"><img src="https://www.cfa-sciences.fr/sites/upmc/files/styles/logo_part/public/thumbnails/image/89.png?itok=GzgrgqMK" alt="CCI Paris Ile de France"/></a>
</div>

</footer>
<div id="bgpopup" class="hidden"></div>
<div id="bgloader" class="">
	<img src="https://www.cfa-sciences.fr/sites/upmc/themes/bootstrap_upmc/logo.png" alt="CFA des SCIENCES" />
	<p id="loaderMessage">Ce site nécessite l'activation de javascript dans votre navigateur</p>
</div>
</div>
<script>
	// hide bgloader

	var loader=document.getElementById("bgloader");
	console.log(loader);
	if(!loader.classList.contains("hidden")) loader.classList.add("hidden");
	document.getElementById("loaderMessage").textContent="Une petite seconde s'il vous plait..."
	function loaderOn(){

		if(loader.classList.contains("hidden")) loader.classList.remove("hidden");
		return true;
	}
</script>
</body>
</html>
