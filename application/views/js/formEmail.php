<script>
	//js formEmail
	
	
	var statusForm=document.querySelectorAll(".txtStatus");
	var idStatus;
	var select=document.querySelector("select[name='typeEmail']");
	var sendToSelection=document.querySelector('input[name="to"][value="selection"]');
	var sendToAll=document.querySelector('input[name="to"][value="all"]');
	
	select.addEventListener("click", changeStatus);
	sendToAll.addEventListener("click", selectStudents);
	changeStatus();
	
	function changeStatus(){
		
		var typeEmail=document.querySelector("#formEmail option:checked");
		
		switch(typeEmail.value){
			case "prise-de-rendez-vous":
				txtStatus="en attente d'email";
				idStatus=1;
				break;
			case "precision-salle":
				txtStatus="en attente d'information";
				idStatus=4;
				break;
			case "erratum-session-reportee":
				txtStatus="dont la session a été décalé";
				idStatus=6;
				break;
			case "rappel":
				txtStatus="positionnés";
				idStatus=3;
				break;
			case "relance":
				txtStatus="qui ont déjà reçu un email";
				idStatus=2;
				break;
		}
		if(sendToAll.checked) selectStudents();
		
		for(var i=0; i<statusForm.length; i++){
 
			 statusForm[i].textContent=txtStatus;
		}
	}
	
	function selectStudents(){
		if(allStudents){
			for(var i=0; i<allStudents.length; i++){
				allStudents[i].checked=allStudents[i].parentElement.parentElement.classList.contains("status"+idStatus);
				if(idStatus==3){
					if(allStudents[i].parentElement.parentElement.classList.contains("status4")) allStudents[i].checked=true;					
				}
			}
		};
		
	}

</script>