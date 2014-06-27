<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area studenti</title>
<link rel="stylesheet" href="../teachers/reg.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
var registra = function(){
	if(trim(document.forms[0].email.value) == ""){
		alert("Email assente. Inserire un indirizzo email valido per proseguire");
		$('email_label').style.color = "red";
		$('email_row').style.border = "1px solid #FF0000";
		return false;
	}

	req = new Ajax.Request('save_profile.php',
		  {
		    	method:'post',
		    	parameters: $('my_form').serialize(true),
		    	onSuccess: function(transport){
		      		var response = transport.responseText || "no response text";
		      		//alert(response);
		      		var dati = response.split("|");
		      		if(dati[0] == "ko"){
			      		alert(dati[1]);
			      		return false;
		      		}
		      		alert("Profilo modificato correttamente");
		    	},
		    	onFailure: function(){ alert("Si e' verificato un errore..."); }
		  });
};
</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "profile_working.php" ?>
</div>
<div id="left_col">
	<div style="width: 95%; height: 30px; margin: 10px auto 0 auto; text-align: center; font-size: 1.1em; text-transform: uppercase">
		E-profile
	</div>
	<form id="my_form" method="post" action="dati.php" style="border: 1px solid #666666; border-radius: 10px; margin-top: 20px; text-align: left; width: 460px; margin-left: auto; margin-right: auto">
	<table style="width: 400px; margin-left: auto; margin-right: auto; margin-top: 30px; margin-bottom: 30px">
		<tr id="email_row">
			<td style="width: 60%" id="email_label">Email</td>
			<td style="width: 40%"><input type="text" name="email" style="width: 250px; font-size: 11px; border: 1px solid" value="<?php if(isset($profile)) print $profile['email']; ?>" /></td> 
		</tr>
		<tr id="mess_row">
			<td style="width: 60%" id="mess_label">Messenger</td>
			<td style="width: 40%"><input type="text" name="mess" style="width: 250px; font-size: 11px; border: 1px solid" value="<?php if(isset($profile)) print $profile['messenger']; ?>" /></td> 
		</tr>
		<tr id="blog_row">
			<td style="width: 60%" id="blog_label">Blog</td>
			<td style="width: 40%"><input type="text" name="blog" style="width: 250px; font-size: 11px; border: 1px solid" value="<?php if(isset($profile)) print $profile['blog']; ?>" /></td> 
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td> 
		</tr>
		<tr>
			<td colspan="2" style="text-align: right; margin-right: 50px">
				<a href="#" onclick="registra()" style="text-decoration: none; text-transform: uppercase">Registra</a>
				<input type="hidden" name="action" id="action" value="profile" />
			</td> 
		</tr>
	</table>
	</form>
</div>
<p class="spacer"></p>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>