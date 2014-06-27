<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Iframe file</title>
<script type="text/javascript">
var attach_counter = <?php print $counter ?>;

function show_input(){
	//alert(parent.document.getElementById("aframe"));
	if(attach_counter > 2){
		alert("Limite raggiunto");
		return false;
	}
	attach_counter++;
	//alert(attach_counter);
	psize = 40 + (30*attach_counter);
	parent.document.getElementById("aframe").style.height = psize+"px";
	par = document.getElementById('attach'+attach_counter);
	inp = document.createElement("input");
	inp.setAttribute("type", "file");
	inp.setAttribute("size", 30);
	inp.setAttribute("name", "input"+attach_counter);
	inp.setAttribute("id", "input"+attach_counter);
	inp.setAttribute("onchange", "upload_file(this)");
	inp.setAttribute("style", "border: 1px solid; font-size: 11px");
	remove = document.createElement("input");
	remove.setAttribute("type", "button");
	remove.setAttribute("value", "Rimuovi");
	remove.setAttribute("style", "margin-left: 10px; padding-left: 5px; padding-right: 5px; border: 1px solid; font-size: 11px");
	remove.setAttribute("onclick", "hide_input()");
	par.appendChild(inp);
	par.appendChild(remove);
	par.style.display = "";
}

function hide_input(){
	document.getElementById('attach'+attach_counter).innerHTML = "";
	document.getElementById('attach'+attach_counter).style.display = "none";
	attach_counter--;
	psize = 40 + (30*attach_counter);
	parent.document.getElementById("aframe").style.height = psize+"px";
}

var show_files = function(){
	field = document.getElementById('input'+attach_counter);
	//alert(field.files[0].fileName);
}

function upload_file(input_file){
	document.forms[0].ct.value = attach_counter;
	document.forms[0].submit();
	attach_counter++;
}

</script>
<style>
p { height: 5px }
input, select, textarea {
	background-color: rgba(211, 222, 199, 0.6);
	border-radius: 2px;
	font-size: 11px
}
</style>
</head> 
<body style="background-color: #F3F3F6;"> 
<p style="text-align: left; height: 1px; padding: 0px; margin-bottom: 0"><a href="#" onclick="show_input()" style="font-weight: normal; color: #373946; font-size: 11px">Allega un file</a></p>
<form style="padding-top: 15px; width: 90%" method="post" enctype="multipart/form-data" action="attach_iframe.php?upl">
<p style="text-align: left; width: 95%; color: #467aa7; display: <?php if(!isset($_SESSION['files'][1])){ print ("none"); }?>" id="attach1"><?php if(isset($_SESSION['files'][1])){ print ("<input type='checkbox' value='1' checked='checked' name='cb[]' />&nbsp;".$_SESSION['files'][1]['name']." (<span style='font-type: italic'>".$_SESSION['files'][1]['mime']."</span>) ".$_SESSION['files'][1]['filesize']); }?></p>
<p style="text-align: left; width: 95%; color: #467aa7; display: <?php if(!isset($_SESSION['files'][2])){ print ("none"); }?>" id="attach2"><?php if(isset($_SESSION['files'][2])){ print ("<input type='checkbox' value='2' checked='checked' name='cb[]' />&nbsp;".$_SESSION['files'][2]['name']." (<span style='font-type: italic'>".$_SESSION['files'][2]['mime']."</span>) ".$_SESSION['files'][2]['filesize']); }?></p>
<p style="text-align: left; width: 95%; color: #467aa7; display: <?php if(!isset($_SESSION['files'][3])){ print ("none"); }?>" id="attach3"><?php if(isset($_SESSION['files'][3])){ print ("<input type='checkbox' value='3' checked='checked' name='cb[]' />&nbsp;".$_SESSION['files'][3]['name']." (<span style='font-type: italic'>".$_SESSION['files'][3]['mime']."</span>) ".$_SESSION['files'][3]['filesize']); }?></p>
<p style="display: inline"><input type="hidden" name="ct" value="<?php print $counter + 1 ?>" /></p>
</form>
</body> 
</html>