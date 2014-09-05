<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
<link rel="stylesheet" href="../../../css/site_themes/blue_red/reg.css" type="text/css" media="screen,projection" />
<link href="../../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../../js/prototype.js"></script>
<script type="text/javascript" src="../../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript" src="../../../js/window.js"></script>
<script type="text/javascript" src="../../../js/window_effects.js"></script>
<script type="text/javascript">
var do_submit = function(){
	att = new Array();
	iframe = $('aframe').contentDocument;
	for(var i = 0; i < iframe.forms[0].elements.length; i++){
		//alert(a.name+"==>"+a.value);
		if(iframe.forms[0].elements[i].checked){
			//alert(iframe.forms[0].elements[i].name+="==>"+iframe.forms[0].elements[i].value);
			att.push(iframe.forms[0].elements[i].value);
		}
	}
	document.forms[0].insert.value = att.join(",");
	//alert(document.forms[0].insert.value);
	document.forms[0].submit();
}
</script>
<style>
td { height: 20px }
</style>
</head>
<body <?php if($sent){ print ("onload='alert(\"Mail inviata\")'"); } ?>> 
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "class_working.php" ?>
</div>
<div id="left_col">
<h2 style="text-align: center">Invio email</h2>
<form style="padding: 20px; border: 1px solid rgb(211, 222, 199); width: 90%; margin: auto; border-radius: 8px" method="post" action="send_mail.php?action=send">
<table style="width: 95%; margin: auto">
<tr>
	<td style="font-weight: bold; width: 10%">From</td>
	<td style="width: 90%"><input type="text" style="width: 95%" id="from" name="from" /></td>
</tr>
<tr>
	<td style="font-weight: bold; width: 10%"><a href="#">To</a></td>
	<td style="width: 90%"><input type="text" style="width: 95%" id="to" name="to" /></td>
</tr>
<tr>
	<td style="font-weight: bold; width: 10%">Oggetto</td>
	<td style="width: 90%"><input type="text" style="width: 95%" id="subject" name="subject" /></td>
</tr>
<tr>
	<td style="font-weight: bold; width: 10%"></td>
	<td style="font-weight: bold;"><div style="width: 100%; margin: 0px; "><iframe src="<?php echo $_SESSION['__path_to_root__'] ?>shared/attach_iframe.php" style="border: 0; width: 100%; height: 40px; margin: 0px" id="aframe"></iframe></div></td>
</tr>
<tr>
	<td style="font-weight: bold;" colspan="2"><textarea style="width: 96%; height: 150px" name="txt" id="txt"></textarea></td>
</tr>
<tr>
	<td style="font-weight: normal; text-align: right" colspan="2">
		<a href="#" onclick="do_submit()" style="margin: 10px 23px 0 0; text-transform: uppercase; text-decoration: none">Invia</a>
		<input type="hidden" name="insert" id="insert" />	
	</td>
</tr>
</table>
</form>
</div> 
<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
</body>
</html>
