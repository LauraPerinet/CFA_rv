<script>
	
	//scriptCalendar
	var selectAll=document.getElementsByClassName("selectAll");
	var meetings=document.getElementsByClassName("meeting");
	var hidden="true";
	var deletePopUp=document.getElementById("popupSup");
	var numToDelete=document.getElementById("numDelete");
	
	for(var i=0; i<selectAll.length; i++){
		selectAll[i].addEventListener("click", selection);
	}


	function selection(e){
		var allMeetings = document.querySelectorAll("input[type=checkbox]."+e.target.getAttribute("data-day"));
		for(var i=0; i<allMeetings.length; i++){
			allMeetings[i].checked=e.target.checked;
			var li= allMeetings[i].parentNode;
			if(e.target.checked){
				if(!li.classList.contains("selected")) li.classList.add("selected");
			}else{
				li.classList.remove("selected");
			}
		}
	}
	
	for(var i=0; i<meetings.length; i++){
		meetings[i].addEventListener("click", selectOne);
	}
	function selectOne(e){
		var liNodes=e.target.childNodes;
		var i=0;
		while(liNodes[i].nodeName==="#text"){ i++;}
		e.target.classList.contains("selected") ? e.target.classList.remove("selected"): e.target.classList.add("selected");
		liNodes[i].checked=!liNodes[i].checked;
	}
	
</script>