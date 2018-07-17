<script>
	//script openMenu
	var buttons=document.getElementsByClassName("menuBtn");
	var forms=document.getElementsByClassName("form");

	for(var i=0; i<buttons.length;i++){
		console.log(buttons[i]);
		buttons[i].classList.remove("hidden");
		buttons[i].addEventListener("click", openForm);
	}
	for(var i=0; i<forms.length;i++){
		if(!forms[i].classList.contains("noHidden")) forms[i].classList.add("hidden");

	}
	function openForm(e){
		var 	target=e.target;
		var i=0;
		var goOn=true;
		while(target.getAttribute("data-form")===null && goOn){
			target=target.parentNode;
			if(i==10) goOn=false;
			i++;
		}
		var form=document.getElementById(target.getAttribute("data-form"));
		target.classList.toggle("open");
		form.classList.toggle("hidden");
	}
</script>
