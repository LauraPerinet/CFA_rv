<script>
	//script openMenu
	var buttons=document.getElementsByClassName("menuBtn");
	var forms=document.getElementsByClassName("form");
	
	for(var i=0; i<buttons.length;i++){
		buttons[i].classList.remove("hidden");
		buttons[i].addEventListener("click", openForm);
	}
	for(var i=0; i<forms.length;i++){
		if(!forms[i].classList.contains("noHidden")) forms[i].classList.add("hidden");

	}
	function openForm(e){
		var form=document.getElementById(e.target.dataset.form);
		e.target.classList.toggle("open");
		form.classList.toggle("hidden");
	}
</script>