<script>
	
	//scriptCalendar
	var selectAll=document.getElementsByClassName("selectAll");
	var meetings=document.getElementsByClassName("meeting");
	//var hidden="true";
	var checkedCalendar=0;
	var CalendartoHide=document.getElementById("CalendartoHide");
	//var deletePopUp=document.getElementById("popupSup");
	//var numToDelete=document.getElementById("numDelete");
	
	for(var i=0; i<selectAll.length; i++){
		selectAll[i].addEventListener("click", selectionCalendar);
	}

	CalendartoHide.classList.add("hidden");
	
	for(var i=0; i<meetings.length; i++){
		meetings[i].addEventListener("click", selectOne);
	}
	
	
	function selectionCalendar(e){
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
		checkedCalendar= e.target.checked ? allMeetings.length : 0;
		hideCalendarChange()
	}
	
	
	function selectOne(e){
		var liNodes=e.target.childNodes;
		var i=0;
		while(liNodes[i].nodeName==="#text"){ i++;}
		
		liNodes[i].checked=!liNodes[i].checked;
		if(liNodes[i].checked){
			e.target.classList.add("selected");
			checkedCalendar++;
		}else{
			e.target.classList.remove("selected");
			checkedCalendar--;
		}
		hideCalendarChange()
	}
	function hideCalendarChange(){
		if(checkedCalendar==0){
			CalendartoHide.classList.add("hidden");
		}else{
			CalendartoHide.classList.remove("hidden");
		}
	}
	
	document.querySelector("input[name='delete']").addEventListener("keypress", function(e){if(e.keyCode==13) e.target.preventDefault();});
	
</script>