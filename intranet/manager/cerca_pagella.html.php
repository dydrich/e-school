<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript">
var y = <?php echo $year ?>;
var q = <?php echo $q ?>;
var search = function(){
	if($('#cognome').val() == ""){
		alert("E' obbligatorio indicare il cognome");
		yellow_fade("tr_cognome");
		return false;
	}
	var url = "report_manager.php";
	$.ajax({
		type: "POST",
		url: url,
		data: {y: y, cls: $('#classe').val(), lname: $('#cognome').val(), q: q, action: "search"},
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
			else if(json.status == "nostd"){
				$('#container').text("Nessun alunno in archivio per i parametri richiesti");
			}
			else if(json.status == "nopg"){
				$('#container').text(json.message);
			}
			else{
				//alert(response);
				print_string = "";
				for(data in json){
					var t = json[data];
					//alert(t.del);
					if (t.del == 1){
						print_string += "<p><a href='#' onclick='dwld_file(\"../../modules/documents/download_manager.php?doc=report&school_order=<?php echo $_SESSION['__school_order__'] ?>&area=manager&f="+t.file+"&sess=1&stid="+t.id+"&y=<?php echo $_SESSION['__current_year__']->get_ID() ?>&noread=1&delete=1\")' style=''>"+t.nome+" (1 quadrimestre)</a></p>";
					}
					else {
						print_string += "<p><a href='../../modules/documents/download_manager.php?doc=report&school_order=<?php echo $_SESSION['__school_order__'] ?>&area=manager&f="+t.file+"&sess=2&stid="+t.id+"&y="+y+"&noread=1' style=''>"+t.nome+" (2 quadrimestre)</a></p>";
					}
				}
				$('#container').html(print_string);
			}
		}
	});
};

var dwld_file = function(href){
	document.location = href;
	$('container').update('');
};

$(function(){
	if(y != <?php echo $_SESSION['__current_year__']->get_ID() ?>){
		$('#classe').attr("disabled", "disabled");
	}
	$('#anno').change(function(event){
		if($('#anno').val() == <?php echo $_SESSION['__current_year__']->get_ID() ?>){
			$('#classe').removeAttr("disabled");
		}
		else{
			$('#classe').attr("disabled", "disabled");
		}
		$('#container').text("");
	});
	
	$('#search_lnk').click(function(event){
		event.preventDefault();
		search();
	});
});	

</script>
</head>
<body>
<?php include "header.php" ?>
<?php include $_SESSION['__administration_group__']."/navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include $_SESSION['__administration_group__']."/menu.php" ?>
</div>
<div id="left_col">
	<div class="group_head">
		Pagelle online <?php echo $school ?>
	</div>
	<form class="reg_form" id="search_form" style="height: 150px; margin-top: 10px">
	<div style="width: 45%; margin: 10px 0 0px 20px; float: left">
		<table style="width: 95%">
			<tr id="tr_anno">
				<td style="width: 40%">Anno scolastico</td>
				<td style="width: 60%">
					<select id="anno" style="width: 95%" name="anno">
					<?php 
					while($y = $res_anni->fetch_assoc()){
					?>
						<option value="<?php echo $y['id_anno'] ?>" <?php if($year == $y['id_anno']) echo "selected" ?>><?php echo $y['descrizione'] ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			<!-- 
			<tr id="tr_ordine">
				<td style="width: 40%">Ordine di scuola</td>
				<td style="width: 60%">
					<select id="ordine" style="width: 95%" name="ordine">
						<option value="0">.</option>
					<?php 
					while($t = $res_tipi->fetch_assoc()){
					?>
						<option value="<?php echo $t['id_tipo'] ?>"><?php echo $t['tipo'] ?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			 -->
			<tr id="tr_classe">
				<td style="width: 40%">Classe</td>
				<td style="width: 60%">
					<select id="classe" style="width: 95%">
						<option value="0">.</option>
					<?php 
					while($c = $res_classi->fetch_assoc()){
					?>
						<option value="<?php echo $c['id_classe'] ?>"><?php echo $c['classe'] ?></option>
					<?php } ?>	
					</select>
				</td>
			</tr>
			<tr id="tr_cognome">
				<td style="width: 40%">Cognome</td>
				<td style="width: 60%">
					<input type="text" id="cognome" style="width: 95%" />
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding-top: 20px"><a href="../../shared/no_js.php" id="search_lnk" style="text-decoration: none; text-transform: uppercase">Cerca la pagella</a></td>
			</tr>
		</table>
	</div>
	<div style="float: right; width: 45%" id="container"></div>
	</form>
</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>
