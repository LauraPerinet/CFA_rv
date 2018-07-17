<script>
//blacklist
  var options=document.querySelectorAll("#studentWhiteList option");
  var deleteAnnonceBtn=document.getElementsByClassName("deletepopup");
  var ul=document.getElementById("blackList");
  var labels=document.querySelectorAll(".labelBlackList:not(.notActif)");
  var popupWhiteList=document.getElementById("popupWhiteList");
  var popupDelete=document.getElementById("popupDelete");
  var closePopUp=document.getElementsByClassName("closePopup");


  for(var i=0; i<closePopUp.length;i++){
    closePopUp[i].addEventListener("click", close);
  }

  for(var i=0;i<labels.length;i++){
    labels[i].addEventListener("click", labelPopup);
  }
  for(var i=0;i<deleteAnnonceBtn.length;i++){
    deleteAnnonceBtn[i].addEventListener("click", deletePopup);

  }
  for(var i=0;i<options.length; i++){
    options[i].addEventListener("click", blackList);
  }

  function blackList(e){
    var li=document.querySelectorAll("#blackList button");

     for(var i=0; i<li.length;i++){
        ul.removeChild(li[i]);
     }

     for(var i=1; i<options.length;i++){
       if(options[i].selected){
         var newLi=document.createElement("button");
         newLi.setAttribute("type", "button");
         newLi.addEventListener("click", removeFromBlackList)
         newLi.classList.add("labelBlackList");
         newLi.dataset.idStudent=options[i].getAttribute("value");
         newLi.textContent=options[i].textContent+" X";
         ul.appendChild(newLi);
       }
     }
  }
  function removeFromBlackList(e){
    document.querySelector("option[value='"+e.target.getAttribute("data-id-student")+"']").selected=false;
    ul.removeChild(e.target);
  }

  function labelPopup(e){
    popup(e.target, popupWhiteList);
  }
  function deletePopup(e){
    popup(e.target, popupDelete);
  }
  function close(){
    if(!popupWhiteList.classList.contains("hidden")) popupWhiteList.classList.add("hidden");
    if(!popupDelete.classList.contains("hidden")) popupDelete.classList.add("hidden");
    if(!bgpopup.classList.contains("hidden")) bgpopup.classList.add("hidden");
    bgpopup.removeEventListener("click", popup);
  }
  function popup(target, popElmt){
    var bgpopup=document.getElementById("bgpopup");
    if(popElmt.classList.contains("hidden")){

        document.getElementById("studentToWhiteList").textContent=target.textContent.substring(0,target.textContent.length-1);

        document.getElementById("input_id_student").setAttribute("value", target.getAttribute("data-id-student"));
        document.getElementById("input_id_annonce").setAttribute("value", target.getAttribute("data-id-annonce"));

        document.getElementById("input_id_annonce_delete").setAttribute("value", target.getAttribute("data-id-annonce"));


      bgpopup.classList.remove("hidden");
      bgpopup.addEventListener("click", close);
      popElmt.classList.remove("hidden");

    }else{
      close();
    }
  }

  function modifAnnonce(e){
    document.querySelector("#formAddAnnonce input[type='title']").value=e.target.getAttribute("data-title")
  }
</script>
