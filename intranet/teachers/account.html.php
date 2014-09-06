<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript">
function show_t(div){
	act = "hide";
	if($(div).style.display == "none"){
		act = "show";
	}
	for(var i = 1; i < 9; i++){
		if(act == "hide" && div.id == ("pfield"+i))
			continue;
		$('pfield'+i).hide();
	}
	if(act == "show")
		Effect.Appear(div, { duration: 1.0 });
	else
		Effect.Fade(div, { duration: 1.0 });
}

function _submit(form){
	// stringhe permessi
	<?php 
	$perms_string = strval(DOC_PERM);
	?>
	var perms = new Array(0, <?php print DOC_PERM ?>, <?php print DOC_PERM ?>, <?php print DOC_PERM ?>, <?php print DOC_PERM ?>, <?php print DOC_PERM ?>, <?php print DOC_PERM ?>, <?php print DOC_PERM ?>, <?php print DOC_PERM ?>);
	// permessi
	var parents = <?php print GEN_PERM ?>;
	var students = <?php print STD_PERM ?>;
	var ata = <?php print ATA_PERM ?>;
	for(i = 1; i < 9; i++){
		if($('par'+i).checked)
			perms[i] += parents;
		if($('stu'+i).checked)
			perms[i] += students;
		if($('ata'+i).checked)
			perms[i] += ata;
	}
	$('perms').value = perms.join(",");
	var req = new Ajax.Request('profile_manager.php',
			  {
			    	method:'post',
			    	parameters: $('profile_form').serialize(true),
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split("#");
			     		if(dati[0] == "ko"){
				     		alert("Impossibile completare l'operazione richiesta. Riprovare tra qualche secondo o segnalare l'errore al webmaster");
				     		return;
			     		}
			     		else{
			     			alert("Operazione completata con successo");
			     		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}
</script>
<style>
input {font-size: 0.9em }	
</style>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both">
<div id="right_col">
<?php include "profile_menu.php" ?>
</div>
<div id="left_col">
<div class="group_head">Gestisci i tuoi dati personali</div>
<form action="profile_manager.php" method="post" id="profile_form" style="position: relative; width: 80%; top: 15px; padding: 10px; margin: auto; border: 1px solid rgba(30, 67, 137, .8); border-radius: 10px;">
<table style="width: 95%; ">
<tr>
<td style="">
<div class="field" id="field1">
	<a href="#" onclick="show_t($('pfield1'))" style="font-weight: bolder; font-size: 1.1em;  background-color: rgba(30, 67, 137, .8); color: white; text-decoration: none">+</a>
	<span style="font-weight: bold; padding: 0 10px 0 0">Data di nascita</span>
	<input type="text" style="width: 205px; float: right" name="birthday" value="<?php if(isset($birthday)) print $birthday ?>" />
	<div style="display: none; padding-top: 5px" id="pfield1">Visibile per...
	<table style="width: 95%; margin-top: 5px; margin-left: auto; margin-right: auto; border-collapse: collapse">
	<tr>
		<td style="width: 50%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">Genitori</td>
		<td style="width: 50%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="par1" value="<?php print GEN_PERM ?>" <?php if(isset($birthday_perms)){ if($birthday_perms&GEN_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	<tr>
		<td style="width: 50%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">Studenti</td>
		<td style="width: 50%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="stu1" value="<?php print STD_PERM ?>" <?php if(isset($birthday_perms)){ if($birthday_perms&STD_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	<tr>
		<td style="width: 50%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">ATA</td>
		<td style="width: 50%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="ata1" value="<?php print ATA_PERM ?>" <?php if(isset($birthday_perms)){ if($birthday_perms&ATA_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	</table>
	</div>
</div>
</td>
</tr>
<tr>
<td style="">
<div class="field" id="field2">
	<a href="#" onclick="show_t($('pfield2'))" style="font-weight: bolder; font-size: 1.1em; background-color:  rgba(30, 67, 137, .8); color: white; text-decoration: none">+</a>
	<span style="font-weight: bold; padding-right: 10px">Indirizzo</span>
	<input type="text" style="width: 205px; float: right" name="address" value="<?php if(isset($address)) print $address ?>" />
	<div style="display: none; padding-top: 5px" id="pfield2">Visibile per...
	<table style="width: 95%; margin-top: 5px; margin-left: auto; margin-right: auto">
	<tr>
		<td style="width: 70%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">Genitori</td>
		<td style="width: 30%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="par2" value="<?php print GEN_PERM ?>" <?php if(isset($address_perms)){ if($address_perms&GEN_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	<tr>
		<td style="width: 70%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">Studenti</td>
		<td style="width: 30%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="stu2" value="<?php print STD_PERM ?>" <?php if(isset($address_perms)){ if($address_perms&STD_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	<tr>
		<td style="width: 70%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">ATA</td>
		<td style="width: 30%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="ata2" value="<?php print ATA_PERM ?>" <?php if(isset($address_perms)){ if($address_perms&ATA_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	</table>
	</div>
</div>
</td>
</tr>
<tr>
<td style="">
<div class="field" id="field3">
	<a href="#" onclick="show_t($('pfield3'))" style="font-weight: bolder; font-size: 1.1em; background-color:  rgba(30, 67, 137, .8); color: white; text-decoration: none">+</a>
	<span style="font-weight: bold; padding-right: 10px">Telefono fisso</span>
	<input type="text" style="border: 1px solid #AAAAAA; width: 205px; float: right" name="phone" value="<?php if(isset($phone)) print $phone ?>" />
	<div style="display: none; padding-top: 5px" id="pfield3">Visibile per...
	<table style="width: 95%; margin-top: 5px; margin-left: auto; margin-right: auto">
	<tr>
		<td style="width: 70%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">Genitori</td>
		<td style="width: 30%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="par3" value="<?php print GEN_PERM ?>" <?php if(isset($phone_perms)){ if($phone_perms&GEN_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	<tr>
		<td style="width: 70%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">Studenti</td>
		<td style="width: 30%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="stu3" value="<?php print STD_PERM ?>" <?php if(isset($phone_perms)){ if($phone_perms&STD_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	<tr>
		<td style="width: 70%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">ATA</td>
		<td style="width: 30%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="ata3" value="<?php print ATA_PERM ?>" <?php if(isset($phone_perms)){ if($phone_perms&ATA_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	</table>
	</div>
</div>
</td>
</tr>
<tr>
<td style="">
<div class="field" id="field4">
	<a href="#" onclick="show_t($('pfield4'))" style="font-weight: bolder; font-size: 1.1em; background-color:  rgba(30, 67, 137, .8); color: white; text-decoration: none">+</a>
	<span style="font-weight: bold; padding-right: 10px">Cellulare</span>
	<input type="text" style="width: 205px; float: right" name="cellphone" value="<?php if(isset($cellphone)) print $cellphone ?>" />
	<div style="display: none; padding-top: 5px" id="pfield4">Visibile per...
	<table style="width: 95%; margin-top: 5px; margin-left: auto; margin-right: auto">
	<tr>
		<td style="width: 70%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">Genitori</td>
		<td style="width: 30%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="par4" value="<?php print GEN_PERM ?>" <?php if(isset($cellphone_perms)){ if($cellphone_perms&GEN_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	<tr>
		<td style="width: 70%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">Studenti</td>
		<td style="width: 30%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="stu4" value="<?php print STD_PERM ?>" <?php if(isset($cellphone_perms)){ if($cellphone_perms&STD_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	<tr>
		<td style="width: 70%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">ATA</td>
		<td style="width: 30%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="ata4" value="<?php print ATA_PERM ?>" <?php if(isset($cellphone_perms)){ if($cellphone_perms&ATA_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	</table>
	</div>
</div>
</td>
</tr>
</table>
<table style="width: 95%;">
<tr>
<td style="">
<div class="field" id="field5">
	<a href="#" onclick="show_t($('pfield5'))" style="font-weight: bolder; font-size: 1.1em; background-color:  rgba(30, 67, 137, .8); color: white; text-decoration: none">+</a>
	<span style="font-weight: bold; padding-right: 10px">Email</span>
	<input type="text" style="border: 1px solid #AAAAAA; width: 205px; float: right" name="email"  value="<?php if(isset($email)) print $email ?>" />
	<div style="display: none; padding-top: 5px" id="pfield5">Visibile per...
	<table style="width: 95%; margin-top: 5px; margin-left: auto; margin-right: auto">
	<tr>
		<td style="width: 70%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">Genitori</td>
		<td style="width: 30%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="par5" value="<?php print GEN_PERM ?>" <?php if(isset($email_perms)){ if($email_perms&GEN_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	<tr>
		<td style="width: 70%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">Studenti</td>
		<td style="width: 30%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="stu5" value="<?php print STD_PERM ?>" <?php if(isset($email_perms)){ if($email_perms&STD_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	<tr>
		<td style="width: 70%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">ATA</td>
		<td style="width: 30%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="ata5" value="<?php print ATA_PERM ?>" <?php if(isset($email_perms)){ if($email_perms&ATA_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	</table>
	</div>
</div>
</td>
</tr>
<tr>
<td style="">
<div class="field" id="field6">
	<a href="#" onclick="show_t($('pfield6'))" style="font-weight: bolder; font-size: 1.1em; background-color:  rgba(30, 67, 137, .8); color: white; text-decoration: none">+</a>
	<span style="font-weight: bold; padding-right: 10px">Messenger</span>
	<input type="text" style="width: 205px; float: right" name="messenger"  value="<?php if(isset($messenger)) print $messenger ?>" />
	<div style="display: none; padding-top: 5px" id="pfield6">Visibile per...
	<table style="width: 95%; margin-top: 5px; margin-left: auto; margin-right: auto">
	<tr>
		<td style="width: 70%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">Genitori</td>
		<td style="width: 30%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="par6" value="<?php print GEN_PERM ?>" <?php if(isset($messenger_perms)){ if($messenger_perms&GEN_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	<tr>
		<td style="width: 70%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">Studenti</td>
		<td style="width: 30%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="stu6" value="<?php print STD_PERM ?>" <?php if(isset($messenger_perms)){ if($messenger_perms&STD_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	<tr>
		<td style="width: 70%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">ATA</td>
		<td style="width: 30%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="ata6" value="<?php print ATA_PERM ?>" <?php if(isset($messenger_perms)){ if($messenger_perms&ATA_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	</table>
	</div>
</div>
</td>
</tr>
<tr>
<td style="">
<div class="field" id="field7">
	<a href="#" onclick="show_t($('pfield7'))" style="font-weight: bolder; font-size: 1.1em; background-color:  rgba(30, 67, 137, .8); color: white; text-decoration: none">+</a>
	<span style="font-weight: bold; padding-right: 10px">Sito Web</span>
	<input type="text" style="width: 205px; float: right"  name="web"  value="<?php if(isset($website)) print $website ?>" />
	<div style="display: none; padding-top: 5px" id="pfield7">Visibile per...
	<table style="width: 95%; margin-top: 5px; margin-left: auto; margin-right: auto">
	<tr>
		<td style="width: 70%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">Genitori</td>
		<td style="width: 30%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="par7" value="<?php print GEN_PERM ?>" <?php if(isset($website_perms)){ if($website_perms&GEN_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	<tr>
		<td style="width: 70%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">Studenti</td>
		<td style="width: 30%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="stu7" value="<?php print STD_PERM ?>" <?php if(isset($website_perms)){ if($website_perms&STD_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	<tr>
		<td style="width: 70%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">ATA</td>
		<td style="width: 30%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="ata7" value="<?php print ATA_PERM ?>" <?php if(isset($website_perms)){ if($website_perms&ATA_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	</table>
	</div>
</div>
</td>
</tr>
<tr>
<td style="">
<div class="field" id="field8">
	<a href="#" onclick="show_t($('pfield8'))" style="font-weight: bolder; font-size: 1.1em; background-color:  rgba(30, 67, 137, .8); color: white; text-decoration: none">+</a>
	<span style="font-weight: bold; padding-right: 10px">Blog</span>
	<input type="text" style="width: 205px; float: right" name="blog"  value="<?php if(isset($blog)) print $blog ?>" />
	<div style="display: none; padding-top: 5px" id="pfield8">Visibile per...
	<table style="width: 95%; margin-top: 5px; margin-left: auto; margin-right: auto">
	<tr>
		<td style="width: 70%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">Genitori</td>
		<td style="width: 30%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="par8" value="<?php print GEN_PERM ?>" <?php if(isset($blog_perms)){ if($blog_perms&GEN_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	<tr>
		<td style="width: 70%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">Studenti</td>
		<td style="width: 30%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="stu8" value="<?php print STD_PERM ?>" <?php if(isset($blog_perms)){ if($blog_perms&STD_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	<tr>
		<td style="width: 70%; border-bottom: 1px dotted rgba(30, 67, 137, .4)">ATA</td>
		<td style="width: 30%; text-align: right; border-bottom: 1px dotted rgba(30, 67, 137, .4)"><input type="checkbox" id="ata8" value="<?php print ATA_PERM ?>" <?php if(isset($blog_perms)){ if($blog_perms&ATA_PERM) print "checked='checked'"; } ?> /></td>
	</tr>
	</table>
	</div>
</div>
</td>
</tr>
<tr>
<td colspan="2" style="text-align: right; padding-top: 10px; padding-bottom: 10px">
	<a href="#" onclick="_submit(document.forms[0])" style="margin-right: 25px; text-decoration: none; text-transform: uppercase">Registra</a>
	<input type="hidden" name="perms" id="perms" />
</td>
</tr>
</table>
</form>
<p style="clear: both"></p>
</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>
