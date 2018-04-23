
<script>
	//closeTabsFormation
	document.getElementById("tabs").classList.remove('hidden');
	var candidates={
		"selected":true,
		"btn":document.querySelector("#tabs h2.selected"),
		"container":document.getElementById("containercandidate")
	};
	var students = {
		"selected":false,
		"btn":document.querySelector("#tabs h2:not(.selected)"),
		"container":document.getElementById("containerstudent")
	}
	var tabs=[candidates, students];

	students.btn.addEventListener("click", select);
	students.container.classList.add("hidden");

	function select(e){
		
		for(var i=0; i<tabs.length; i++){
			tabs[i].selected=!tabs[i].selected;
			if(tabs[i].selected){
				console.log(tabs[i]);
				tabs[i].btn.removeEventListener("click", select);
				tabs[i].btn.classList.add("selected");
				tabs[i].container.classList.remove("hidden");
			}else{
				tabs[i].btn.addEventListener("click", select);
				tabs[i].btn.classList.remove("selected");
				tabs[i].container.classList.add("hidden");
			}
		}
	}
	
	
</script>