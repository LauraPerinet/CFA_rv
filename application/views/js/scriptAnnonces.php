<script>
	//script openMenu
	var divPopup=document.getElementById("popup");
	var p=document.querySelectorAll("#popup p");
	function popup(type){
		if(divPopup.classList.contains("hidden")){
			bgpopup.classList.remove("hidden");
			bgpopup.addEventListener("click", popup);
			for(var i=0; i<p.length;i++){
				p[i].classList.add("hidden");
			}
			document.querySelector("#popup p."+type).classList.remove("hidden");
			divPopup.classList.remove("hidden");
		}else{
			divPopup.classList.add("hidden");
			bgpopup.classList.add("hidden");
			bgpopup.removeEventListener("click", popup);
		}
	}
</script>
