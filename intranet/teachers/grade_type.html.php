<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
var save_data = function(){
	var url = "../../shared/save_user_config.php";
	$('#field').val("tipologia_prove");
	$.ajax({
		type: "POST",
		url: url,
		data: $('#st_form').serialize(true),
		error: function() {
			j_alert("error", "Errore di trasmissione dei dati");
		},
		succes: function() {

		},
		complete: function(data){
			r = data.responseText;
			if(r == "null"){
				return false;
			}
			var json = $.parseJSON(r);
			if (json.status == "kosql"){
				sqlalert();
				console.log(json.dbg_message);
				return;
			}
			else if(json.status == "ko") {
				j_alert("error", "Impossibile completare l'operazione richiesta. Riprovare tra qualche secondo o segnalare l'errore al webmaster");
				return;
			}
			else {
				j_alert("alert", json.message);
			}
		}
	});
};

$(function(){
	load_jalert();
	$('#save_btn').click(function(event){
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
		<div class="group_head">
		Configurazione registro 
		</div>
		<form method="post" name="st_form" id="st_form" class="no_border">
		<div style="width: 45%; margin: 15px auto; border: 1px solid rgba(30, 67, 137, .5);; padding: 20px">
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
