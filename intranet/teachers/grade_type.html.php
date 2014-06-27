<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
<link rel="stylesheet" href="reg.css" type="text/css" media="screen,projection" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript">
var save_data = function(){
	var url = "../../shared/save_user_config.php";
	$('field').value = "tipologia_prove";
	req = new Ajax.Request(url,
		  {
		    	method:'post',
		    	parameters: $('st_form').serialize(true),
		    	onSuccess: function(transport){
			    	var response = transport.responseText || "no response text";
			    	dati = response.split("#");
		    		if(dati[0] == "kosql"){
			    		sqlalert();
		    			console.log('Modificata non riuscita: '+dati[1], 2);
		    			return;
		    		}
		    		else{
		    			_alert('Configurazione completata', 2);
		    		}
		    	},
		    	onFailure: function(){ alert("Si e' verificato un errore..."); }
		  });
};

document.observe("dom:loaded", function(){
	$('save_btn').observe("click", function(event){
		event.preventDefault();
		save_data();
	});
});
</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
	<?php include "profile_menu.php" ?>
	</div>
	<div id="left_col">
		<div style="width: 90%; height: 30px; margin: 30px auto 0 auto; text-align: center; font-size: 1.1em; text-transform: uppercase">
		Configurazione registro 
		</div>
		<form method="post" name="st_form" id="st_form">
		<div style="width: 45%; margin: auto; border: 1px solid #CCCCCC; padding: 20px">
			<span>Quali tipologie di prove vuoi utilizzare?</span>
			<ul>
		<?php 
		while ($row = $res_prove->fetch_assoc()){
			$checked = false;
			if(count($selected) > 0){
				if(in_array($row['id'], $selected)){
					$checked = true;
				}
			}
			else {
				if($row['default'] == 1){
					$checked = true;
				}
			}
		?>
		<li><span style="padding-left: 20px"><?php echo $row['tipologia'] ?></span><input type="checkbox" name="tests[]" <?php if($checked) echo "checked" ?> value="<?php echo $row['id'] ?>" /></li>
		<?php } ?>
		</ul>
		<div style="text-align: right; width: 100%; height: 20px; margin-right: 30px; margin-top: 20px"><a href="../../shared/no_js.php" id="save_btn" style="text-transform: uppercase; text-decoration: none">Salva</a></div>
		</div>
		<input type="hidden" name="field" id="field" value="" />
		<input type="hidden" name="id_param" id="id_param" value="1" />
		</form>
	</div>
	<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>
