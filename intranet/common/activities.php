<?php

$ordine_scuola = $_SESSION['__classe__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$sel_act = "SELECT rb_impegni.*, rb_materie.materia AS mat FROM rb_impegni, rb_materie WHERE rb_materie.id_materia = rb_impegni.materia AND classe = ".$_SESSION['__classe__']->get_ID()." AND anno = ".$_SESSION['__current_year__']->get_ID()." AND data_inizio >= NOW() AND rb_impegni.tipo = 1 ORDER BY data_inizio DESC";
$res_act = $db->execute($sel_act);

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
<link rel="stylesheet" href="../../css/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/jquery/jquery-ui.min.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../modules/communication/theme/style.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
var dett = function(id_impegno){

	$.ajax({
		type: "POST",
		url: '../../shared/get_desc.php',
		data: {id_impegno: id_impegno, tipo: '1'},
		dataType: 'json',
		error: function() {
			show_error("Errore di trasmissione dei dati");
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
				alert(json.message);
				console.log(json.dbg_message);
			}
			else {
				$('#pop_t').text(json.descrizione);
				$('#pop_d').text(json.note);
				$('#dialog').dialog({
					autoOpen: true,
					show: {
						effect: "appear",
						duration: 500
					},
					hide: {
						effect: "slide",
						duration: 300
					},
					buttons: [{
						text: "Chiudi",
						click: function() {
							$( this ).dialog( "close" );
						}
					}],
					modal: true,
					width: 450,
					title: 'Dettaglio',
					open: function(event, ui){

					}
				});
			}
		}
	});

};
</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
<div id="right_col">
<?php if($area == "genitori") include "sons_menu.php" ?>
<?php include "class_working.php" ?>
</div>
<div id="left_col">
	<div class="group_head">
		Attivit&agrave;, classe <?php echo $_SESSION['__classe__']->get_anno(),$_SESSION['__classe__']->get_sezione() ?>
	</div>
<?php 
if($res_act->num_rows < 1){
?>
<div style="width: 100%; margin-left: auto; margin-right: auto; margin-top: 40px; font-size: 12px; font-weight: bold; text-align: center">
Nessuna attivit&agrave; prevista.
</div>
<?php 
}
else{
	$idx = 1;
	$bc = "";
	$data = "";
	while($row = $res_act->fetch_assoc()){
		$ct = 1;
		list($di, $oi) = explode(" ", $row['data_inizio']);
		setlocale(LC_TIME, "it_IT.utf8");
		$giorno_str = strftime("%A", strtotime($di));
		if($di != $data){
?>
	<div style="width: 90%; text-align: left; padding-left: 30px; margin: 30px 0 10px 0; text-transform: uppercase">
		<?php print strtoupper(substr($giorno_str, 0, 3))." ".format_date($di, SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>
	</div>
<?php 
		}
?>
	<div style="width: 90%; margin: auto; text-align: left; padding-left: 20px; border-bottom: 1px solid rgba(211, 222, 199, 0.6)">
		<a title="<?php print $row['note'] ?>" href="#" onclick="dett(<?php print $row['id_impegno'] ?>)" style="text-decoration: none; text-transform: uppercase"><?php print $row['mat'].":: ".$row['descrizione'] ?></a>
		<span style="float: right; margin-right: 50px">Ore <?php print substr($oi, 0, 5) ?></span>
	</div>

<?php 
		$ct++;
		$idx++;
		$data = $di;
	}
}
?>
	<form>
	<input type="hidden" name="id_impegno" id="impegno" />
	<input type="hidden" name="tipo" id="tipo" />
	</form>
	</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
<div id="dialog" style="display: none">
	<div id="pop_t" style='font-weight: bold; text-align: center; margin-top: 20px'></div>
	<div id="pop_d" style='text-align: center; padding: 10px; margin-top: 20px; padding-bottom: 35px'></div>
</div>
</body>
</html>
