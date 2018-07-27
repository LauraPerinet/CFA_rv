<script>
	//script openMenu
	var divAnnonce=document.getElementById("popupAnnonce");
	var p=document.querySelectorAll("#popupAnnonce p");
	function popupAnnonce(type){
		if(divAnnonce.classList.contains("hidden")){
			bgpopup.classList.remove("hidden");
			bgpopup.addEventListener("click", popup);
			for(var i=0; i<p.length;i++){
				p[i].classList.add("hidden");
			}
			document.querySelector("#popup p."+type).classList.remove("hidden");
			divAnnonce.classList.remove("hidden");
		}else{
			divAnnonce.classList.add("hidden");
			bgpopup.classList.add("hidden");
			bgpopup.removeEventListener("click", popup);
			return true;
		}
	}
</script>
