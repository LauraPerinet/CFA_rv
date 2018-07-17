<script>
//showPopUp
var hidden="true";
var deletePopUp=document.getElementById("popupSup");
var numToDelete=document.getElementById("numDelete");

function showPopUp( id, popUpTab){

		var tab;
		var problems=0;
		if(id){
			if(popUpTab=="meetings"){
				tab=document.querySelectorAll("input[type=checkbox][name='meeting[]']");
			}else if(popUpTab=="students"){
				tab=document.querySelectorAll("input[type=checkbox].<?php echo $title!=='Gestion des apprentis' ? $type :"student"; ?>");
			}
			var j=0;
			for(var i=0; i<tab.length; i++){
				j=tab[i].checked ? j+1 : j;
				if(tab[i].parentNode.classList.contains("notAvailable") && tab[i].checked) problems++;
			}
		}
			if(j>0 || !id) popup(popUpTab, j);
}
function popup( popUpTab, j){

		var bgpopup=document.getElementById("bgpopup");
		if(hidden=!hidden){
			deletePopUp.classList.remove("hidden");
			bgpopup.classList.remove("hidden");
			bgpopup.addEventListener("click", popup);
			if(popUpTab=="meetings"){
				if(problems>0){ numToDelete.classList.remove("hidden");
				}else{
					numToDelete.classList.add("hidden");
				}
			}else{
				numToDelete.textContent=j;
			}
		}else{
			deletePopUp.classList.add("hidden");
			bgpopup.classList.add("hidden");
			bgpopup.removeEventListener("click", popup);
		}

}
</script>
