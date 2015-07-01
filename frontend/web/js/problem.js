window.onload=function(){
	var p_1 = document.getElementById("p_1");
	var img_1 = document.getElementById("img_1");
	var p_2 = document.getElementById("p_2");
	var img_2 = document.getElementById("img_2");
	var p_3 = document.getElementById("p_3");
	var img_3 = document.getElementById("img_3");
	var b1 = false;
	var b2 = false;
	var b3 = false;
	var b4 = false;
	p_1.onclick = p_1Fun;
	p_2.onclick = p_2Fun;
	p_3.onclick = p_3Fun;

	function p_1Fun(){
		if(!b1){
			img_1.src="images/icon-up.png";
			b1=true;
			return false;
		}
		if(b1){
			img_1.src="images/icon-down.png";
			b1=false;
			return false;
		}
	}
	function p_2Fun(){
		if(!b2){
			img_2.src="images/icon-up.png";
			b2=true;
			return false;
		}
		if(b2){
			img_2.src="images/icon-down.png";
			b2=false;
			return false;
		}
	}
	function p_3Fun(){
		if(!b3){
			img_3.src="images/icon-up.png";
			b3=true;
			return false;
		}
		if(b3){
			img_3.src="images/icon-down.png";
			b3=false;
			return false;
		}
	}

}