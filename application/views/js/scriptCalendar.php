<script>
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