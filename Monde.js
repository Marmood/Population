function changeContinent(){
	document.getElementById("reg").value=0;
	document.getElementById("formulaire").submit();
}

function changeRegion(){
	document.getElementById("formulaire").submit();
}

function init(){
if (document.getElementById("continents").value=="0" || document.getElementById("continents").value=="3") {
	document.getElementById("regions").style.display = "none";
}else{
	document.getElementById("regions").style.display = "flex";
}

}