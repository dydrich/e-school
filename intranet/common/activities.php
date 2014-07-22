<?php 

$sel_act = "SELECT rb_impegni.*, rb_materie.materia AS mat FROM rb_impegni, rb_materie WHERE rb_materie.id_materia = rb_impegni.materia AND classe = ".$_SESSION['__classe__']->get_ID()." AND anno = ".$_SESSION['__current_year__']->get_ID()." AND data_inizio >= NOW() AND rb_impegni.tipo = 1 ORDER BY data_inizio DESC";
$res_act = $db->execute($sel_act);

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
<link rel="stylesheet" href="../teachers/reg.css" type="text/css" media="screen,projection" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript">
function dett(id_impegno){
	var req = new Ajax.Request('../../shared/get_desc.php',
			  {
			    	method:'post',
			    	parameters: {id_impegno: id_impegno, tipo: '1'},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		if(dati[0] == "ko"){
				      		alert(dati[1]);
				      		return false;
			      		}
			      		var win = new Window({className: "mac_os_x",  width:300, height:null, zIndex: 100, resizable: true, title: "Dettaglio attivit&agrave;", showEffect:Effect.BlindDown, hideEffect: Effect.SwitchOff, draggable:true, wiredDrag: true});
		            	win.getContent().update("<div style='font-weight: bold; font-size: 11px; text-align: center; margin-top: 20px' class='Titolo'>"+dati[1]+"</div><div style='text-align: center; font-weight: normal; font-size: 11px; padding: 10px; margin-top: 20px; padding-bottom: 35px'>"+dati[2]+"</div>");     
		            	win.showCenter(true);
		            	//alert("Sono alla fine");
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}
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
	<div style="width: 90%; height: 30px; margin: 10px auto 0 auto; text-align: center; font-size: 1.1em; text-transform: uppercase">
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
		setlocale(LC_ALL, "it_IT");
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
</body>
</html>
