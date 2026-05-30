function toggle(showHideDiv) {
	var ele = document.getElementById(showHideDiv);
    var pr = document.getElementById("profile");
	if(pr.value == "FREE") {
    		ele.style.display = "none";
  	}
	else {
		ele.style.display = "block";
	}
}