<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Firma il registro di classe</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		var id_ore = new Array();
		<?php
		while(list($k, $v) = each($ids)){
		?>
		id_ore[<?php print $k ?>] = <?php print $v ?>;
		<?php } ?>
		var id_doc = new Array();
		var id_docc = new Array();
		<?php
		foreach ($firme as $x => $f){
			$doc = $docc = 0;
			if(isset($f['docente']) && $f['docente'] != ""){
				$doc = $f['docente'];
			}
			if(isset($f['doc_compresenza']) && $f['doc_compresenza'] != ""){
				$docc = $f['doc_compresenza'];
			}
		?>
		id_doc[<?php echo $x ?>] = <?php echo $doc ?>;
		id_docc[<?php echo $x ?>] = <?php echo $docc ?>;
		<?php } ?>

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('.sign').click(function(event){
				event.preventDefault();
				$('#ora').val($(this).attr("data-id"));
				offset = $(this).parent().offset();
				offset.top += $(this).parent().height();
				visualizza(event, offset);
			});
			$('.comp_sign').click(function(event){
				event.preventDefault();
				var strs = this.id.split("_");
				$('#ora').val(strs[1]);
				sign_compresence();
			});
			$('.subj').click(function(event){
				event.preventDefault();
				var strs = this.id.split("_");
				$('#mat').val(strs[1]);
				firma();
			});
			$('.del_sign').click(function(event){
				event.preventDefault();
				var strs = this.id.split("_");
				$('#ora').val(strs[2]);
				delete_sign();
			});
			$('.del_csign').click(function(event){
				event.preventDefault();
				var strs = this.id.split("_");
				$('#ora').val(strs[2]);
				delete_compresence_sign();
			});
			$('.argumentum').button();
			$('.argumentum').click(function(event){
				event.preventDefault();
				var strs = this.id.split("_");
				$('#ora').val(strs[1]);
				save_topic();
			});
			$('.support').click(function(event){
				event.preventDefault();
				var strs = this.id.split("_");
				$('#ora').val(strs[1]);
				support_sign(strs[2]);
			});
			$('.del_ssign').click(function(event){
				event.preventDefault();
				var strs = this.id.split("_");
				$('#ora').val(strs[2]);
				delete_support_sign(strs[3]);
			});
			$('#hid').mouseleave(function(event){
				event.preventDefault();
				$('#hid').hide();
			});
			$('#overlay').click(function(event) {
				if ($('#overlay').is(':visible')) {
					show_drawer(event);
				}
				$('#other_drawer').hide();
				$('#classeslist_drawer').hide();
			});
			$('.drawer_label span').click(function(event){
				var off = $(this).parent().offset();
				show_classlist(event, off);
			}).css({
				cursor: "pointer"
			});
		});

		var firma = function(){
		    $('#hid').hide();
		    var ora = $('#ora').val();
		    var mat = $('#mat').val();
		    var id_reg = $('#id_reg').val();
		    $('#action').val('sign');

		    var url = "firma.php";
		    $.ajax({
				type: "POST",
				url: url,
				data: {ora: ora, mat: mat, id_reg: id_reg, id_ora: id_ore[ora], action: 'sign'},
				dataType: 'json',
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
						j_alert("error", json.message);
						console.log(json.dbg_message);
					}
					else {
						$('#ora_'+ora).hide("fade", 300);
						setTimeout(function(){
							$('#ora_'+ora).text(json.subject);
							$('#ora_'+ora).show("fade", 400);
							$('#del_sign_'+ora).show("fade", 400);
						}, 300);

		                id_ore[ora] = json.id_ora;
		                id_doc[ora] = <?php echo $_SESSION['__user__']->getUid() ?>;
					}
				}
		    });
		};

		var sign_compresence = function(){
		    var ora = $('#ora').val();
		    var id_reg = $('#id_reg').val();
		    $('#action').val('sign_compresence');
			if(id_docc[ora] == <?php echo $_SESSION['__user__']->getUid() ?>){
				return false;
			}
			else if(id_docc[ora] != 0){
				alert("Ora firmata: deve essere cancellata dal docente che ha firmato");
				return false;
			}
			if (id_doc[ora] == <?php echo $_SESSION['__user__']->getUid() ?>){
				alert('Non puoi firmare anche per la compresenza');
				return false;
			}

		    var url = "firma.php";
		    $.ajax({
				type: "POST",
				url: url,
				data: {ora: ora, id_reg: id_reg, id_ora: id_ore[ora], action: 'sign_compresence'},
				dataType: 'json',
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
						j_alert("error", json.message);
						console.log(json.dbg_message);
					}
					else if (json.status == "ko"){
						j_alert("error", json.message);
					}
					else {
						$('#cfirma_'+ora).text("Compresenza: "+json.teacher);
						$('#del_csign_'+ora).show();
		                id_ore[ora] = json.id_ora;
		                id_docc[ora] = <?php echo $_SESSION['__user__']->getUid() ?>;
					}
				}
		    });
		};

		var support_sign = function(position){
		    var ora = $('#ora').val();
		    var id_reg = $('#id_reg').val();
		    $('#action').val('sign_support');

		    var url = "firma.php";
		    $.ajax({
				type: "POST",
				url: url,
				data: {ora: ora, id_reg: id_reg, id_ora: id_ore[ora], action: 'sign_support', day: '<?php echo $_REQUEST['data'] ?>'},
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
						j_alert("error", json.message);
						console.log(json.dbg_message);
					}
					else if (json.status == "ko"){
						j_alert("error", json.message);
					}
					else {
						$('#support_'+ora+'_'+position).text("Sostegno: "+json.teacher);
						$('#del_ssign_'+ora+'_'+position).show();
		                id_ore[ora] = json.id_ora;
		                alunni = json.alunni;
		                if (alunni.length == 1){
			                alink = document.createElement("A");
			                alink.setAttribute("href", "../sostegno/dettaglio_attivita.php?id="+alunni[0]['attivita']['id']+"&data="+json.day)+"&st="+alunni[0]['id_alunno'];
			                alink.setAttribute("style", "margin-left: 115px");
			                alink.appendChild(document.createTextNode("Attivita"));
			                $('#ct_'+ora+'_'+position).append(alink);
		                }
		                else {
		                    alink = document.createElement("A");
			                alink.setAttribute("href", "../sostegno/dettaglio_attivita.php?id="+alunni[0].attivita.id+"&data="+json.day+"&st="+alunni[0]['id_alunno']);
			                alink.setAttribute("style", "margin-left: 115px");
			                alink.setAttribute("id", "att"+alunni[0]['id_alunno']+"_"+id_ore[ora]+"_0");
			                $('#ct_'+ora+'_'+position).append(alink);
			                $("#att"+alunni[0]['id_alunno']+"_"+id_ore[ora]+"_0").html("Attivit&agrave; di "+alunni[0]['cognome']);

		                    }
			                for (var i = 1; i < alunni.length; i++) {
				                sp = document.createElement("span");
				                sp.setAttribute("style", "margin: 0 15px 0 15px");
				                sp.appendChild(document.createTextNode("|"));
				                sp.setAttribute("id", "sep"+alunni[i]['id_alunno']+"_"+id_ore[ora]+"_0");
				                $('#ct_'+ora+'_'+position).append(sp);
								otlink = document.createElement("A");
								otlink.setAttribute("href", "../sostegno/dettaglio_attivita.php?id="+alunni[i]['attivita']['id']+"&data="+json.day+"&st="+alunni[0]['id_alunno']);
								otlink.setAttribute("id", "att"+alunni[i]['id_alunno']+"_"+id_ore[ora]+"_"+i);

								$('#ct_'+ora+'_'+position).append(otlink);
				                last = $("#att"+alunni[i]['id_alunno']+"_"+id_ore[ora]+"_"+i);
				                $("#att"+alunni[i]['id_alunno']+"_"+id_ore[ora]+"_"+i).html("Attivit&agrave; di "+alunni[i]['cognome']);
			                }
		                }
					}

		    });
		};

		var delete_sign = function(){
			var ora = $('#ora').val();
		    var mat = $('#mat').val();
		    var id_reg = $('#id_reg').val();

		    var url = "firma.php";
		    $.ajax({
				type: "POST",
				url: url,
				data: {ora: ora, id_reg: id_reg, id_ora: id_ore[ora], action: 'unsign'},
				dataType: 'json',
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
						j_alert("error", json.message);
						console.log(json.dbg_message);
					}
					else {
						$('#ora_'+ora).hide("fade", 300);
						$('#arg'+ora).hide("fade", 300);
						$('#del_sign_'+ora).hide("fade", 300);
						setTimeout(function(){
							$('#ora_'+ora).text("Firma");
							$('#del_sign_'+ora).hide();
							$('#arg'+ora).val('');
							$('#ora_'+ora).show(400);
							$('#arg'+ora).show(400);
						}, 300);
					}
				}
		    });
		};

		var delete_compresence_sign = function(){
			var ora = $('#ora').val();
		    var id_reg = $('#id_reg').val();

		    var url = "firma.php";
		    $.ajax({
				type: "POST",
				url: url,
				data: {ora: ora, id_reg: id_reg, id_ora: id_ore[ora], action: 'unsign_compresence'},
				dataType: 'json',
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
						j_alert("error", json.message);
						console.log(json.dbg_message);
					}
					else {
						$('#cfirma_'+ora).text("Firma compresenza");
						$('#del_ssign_'+ora).hide();
					}
				}
		    });
		};

		var delete_support_sign = function(position){
			var ora = $('#ora').val();
		    var id_reg = $('#id_reg').val();

		    var url = "firma.php";
		    $.ajax({
				type: "POST",
				url: url,
				data: {ora: ora, id_reg: id_reg, id_ora: id_ore[ora], action: 'unsign_support'},
				dataType: 'json',
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
						j_alert("error", json.message);
						console.log(json.dbg_message);
					}
					else {
						$('#support_'+ora+'_'+position).text("Sostegno");
						$('#del_ssign_'+ora+'_'+position).hide();
						$('#ct_'+ora+'_'+position).hide();
					}
				}
		    });
		};

		var save_topic = function (){
		    var ora = $('#ora').val();
		    var mat = $('#mat').val();
		    var id_reg = $('#id_reg').val();
		    var topic = $('#arg'+ora).val();
		    var signature = $('#ora_'+ora).text();

		    if(id_doc[ora] != <?php echo $_SESSION['__user__']->getUid() ?> && id_doc[ora] != 0){
		        alert("Ora firmata da un altro docente");
				return false;
			}
			else if(id_doc[ora] == 0){
				alert("Ora non ancora firmata");
				return false;
			}

		    if (signature == "Firma"){
				alert('Non hai ancora firmato');
				return false;
		    }

		    $.ajax({
				type: "POST",
				url: "arg.php",
				data: {ora: ora, id_reg: id_reg, id_ora: id_ore[ora], action: 'save_topic', topic: topic},
				dataType: 'json',
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
						j_alert("error", json.message);
						console.log(json.dbg_message);
					}
					else {
						$('#check_'+ora).show(500);
						window.setTimeout("$('#check_"+ora+"').hide(1000)", 2000);
					}
				}
		    });
		};

		var visualizza = function(e, offset) {
			if ($('#hid').is(":visible")) {
				$('#hid').slideUp(300);
				return false;
			}
			if (id_doc[$('#ora').val()] != <?php echo $_SESSION['__user__']->getUid() ?> && id_doc[$('#ora').val()] != 0){
				alert('Ora firmata: deve essere prima cancellata dal docente che ha firmato');
				return false;
			}
			<?php
			if (count($materie) == 1){
				$m = $materie[0];
			?>
			document.forms[0].mat.value = <?php print $m['id_materia'] ?>;
			firma();
			<?php
			}
			else {
			?>
			tempY = offset.top;
			tempX = offset.left;
			$('#hid').css({top: parseInt(tempY)+"px"});
			$('#hid').css({left: parseInt(tempX)+"px"});
			$('#hid').slideDown(500);
		    <?php
			}
			?>
		    return true;
		};

		var show_classlist = function(e, off) {
			if ($('#classeslist_drawer').is(":visible")) {
				$('#classeslist_drawer').hide('slide', 300);
				return;
			}
			var offset = $('#drawer').offset();
			var top = off.top;

			var left = offset.left + $('#drawer').width() + 1;
			$('#classeslist_drawer').css({top: top+"px", left: left+"px", zIndex: 1000});
			$('#classeslist_drawer').show('slide', 300);
			return true;
		};

	</script>
<style>
	tr:hover {
		background: none;
	}
	.hasRowSpan {
		background-color: white;
	}
	.nohover:hover {
		background-color: white;
	}

</style>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<form>
	<div style="top: -20px; margin-left: 35px; margin-bottom: -10px" class="rb_button">
		<a href="registro_classe.php?data=<?php echo $_REQUEST['data']; ?>">
			<img src="../../../images/47bis.png" style="padding: 12px 0 0 12px" />
		</a>
	</div>
<table class="registro" style="position: relative; top: -27px">
<thead>
<tr class="title_tr">
	<td colspan="4" style="text-align: center; font-weight: bold; height: 20px">Firme docente</td>
</tr>
</thead>
<tbody>
<?php
reset ($firme);
$index = 0;
foreach ($firme as $x => $ora){
	/*
	 * display delete sign link
	 */
	$display = "none";
	/*
	 * display delete compresence sign link
	 */
	$cdisplay = "none";
	/*
	 * display delete support sign link
	*/
	$sdisplay = "none";
	
	$firma = "Firma";
	if (isset($ora['materia']) && $ora['materia'] != ""){
		$sel_mat = "SELECT materia FROM rb_materie WHERE id_materia = {$ora['materia']}";
		try{
			$firma = $db->executeCount($sel_mat);
		} catch (MySQLException $ex){
		
		}
		if (($ora['materia'] == 27 || $ora['materia'] == 41 || $ora['materia'] == 33) && ($ora['docente'] != $_SESSION['__user__']->getUid())){
			$sel_cdoc = "SELECT CONCAT_WS(' ', cognome, nome) FROM rb_utenti WHERE uid = {$ora['docente']}";
			try{
				$firma .= ": ".$db->executeCount($sel_cdoc);
			} catch (MySQLException $ex){
			
			}
		}
		if ($_SESSION['__user__']->getUid() == $ora['docente']){
			$display = "inline";
		}
	}
	$cfirma = "Firma compresenza";
	if (isset($ora['doc_compresenza']) && $ora['doc_compresenza'] != ""){
		$sel_cdoc = "SELECT CONCAT_WS(' ', cognome, nome) FROM rb_utenti WHERE uid = {$ora['doc_compresenza']}";
		try{
			$cfirma = "Compresenza: ".$db->executeCount($sel_cdoc);
		} catch (MySQLException $ex){
	
		}
		if ($_SESSION['__user__']->getUid() == $ora['doc_compresenza']){
			$cdisplay = "inline";
		}
	}
	$sos = array();
	for ($i = 1; $i <= $count_sos; $i++){
		$sos[$i] = "";
	}
	
	$sostegno = array();
	if (isset($ora['sostegno']) && count($ora['sostegno']) > 0){
		$index = 1;
		foreach ($ora['sostegno'] as $ds){
			$sos[$index] = $ds;
			if ($ds != "" && $ds != $_SESSION['__user__']->getUid()){
				$sel_user = "SELECT CONCAT_WS(' ', cognome, nome) FROM rb_utenti WHERE uid = {$ds}";
				$sostegno[$index] = $db->executeCount($sel_user);
			}
			else if ($ds == $_SESSION['__user__']->getUid()){
				$sostegno[$index] = $_SESSION['__user__']->getFullName();
			}
			else {
				$sostegno[$index] = "";
			}
			$index++;
		}
	}

?>
<tr>
	<td style="width: 5%; text-align: center; font-weight: bold" rowspan="<?php echo $rowspan ?>" class="hasRowSpan"><?php echo $x ?> ora</td>
	<td style="width: 35%; padding-left: 15px">
		<a id="ora_<?php echo $x ?>" data-id="<?php echo $x ?>" class="sign" style="color: black; font-weight: bold" href="#"><?php echo $firma ?></a>
		<a href="#" id="del_sign_<?php echo $x ?>" class="del_sign" style="display: <?php echo $display ?>; margin-left: 10px">(cancella)</a>
	</td>
	<td style="width: 10%; text-align: center">Argomento</td>
	<td style="width: 50%">
		<input type="text" style="width: 75%" id="arg<?php echo $x ?>" name="arg<?php echo $x ?>" value="<?php if (isset($ora['argomento'])) echo htmlentities($ora['argomento'], ENT_QUOTES) ?>" />
		<a href="#" id="reg_<?php echo $x ?>" class="argumentum material_link" style="margin-left: 8px">Registra</a>
		<img src="../../../images/checkminired.png" id="check_<?php echo $x ?>" style="display: none" />
	</td>
</tr>
<tr>
	<td style="padding-left: 15px; height: 18px" colspan="3">
		<a id="cfirma_<?php echo $x ?>" class="comp_sign" style="color: black; font-style: italic" href="#" onclick=""><?php echo $cfirma ?></a>
		<a href="#" id="del_csign_<?php echo $x ?>" class="del_csign" style="display: <?php echo $cdisplay ?>; margin-left: 5px">(cancella)</a>
	</td>
</tr>
<?php
	$c = 0;
	foreach ($sos as $ds){
		$act_link = "";
		$display_delete_link = "none";
		$string = "Sostegno";
		$c++;
		if (isset($sostegno[$c]) && $sostegno[$c] != ""){
			$string = "Sostegno: {$sostegno[$c]}";
			if ($sos[$c] == $_SESSION['__user__']->getUid()){
				$display_delete_link = "inline";
				/*
				 * gestione collegamento alle attivita
				 */
				if (count($alunni) == 1){
					$act_link = '<a href="../sostegno/dettaglio_attivita.php?id='.$alunni[0]['attivita']['id'].'&data='.$_REQUEST['data'].'&st='.$alunni[0]['id_alunno'].'" style="margin-left: 115px">Attivit&agrave;</a>';
				}
				else {
					$act_link = '<a href="../sostegno/dettaglio_attivita.php?id='.$alunni[0]['attivita']['id'].'&data='.$_REQUEST['data'].'&st='.$alunni[0]['id_alunno'].'" style="margin-left: 115px">Attivit&agrave; di '.$alunni[0]['cognome'].'</a>';
					if (count($alunni) > 1) {
						for ($z = 1; $z < count($alunni); $z++){
							$act_link .= '<span style="margin: 0 15px 0 15px">|</span><a href="../sostegno/dettaglio_attivita.php?id='.$alunni[$z]['attivita']['id'].'&data='.$_REQUEST['data'].'&st='.$alunni[$z]['id_alunno'].'" style="">Attivit&agrave; di '.$alunni[$z]['cognome'].'</a>';
						}
					}
				}
			}
		}
?>
<tr>
	<td colspan="3" style="padding-left: 15px; height: 18px">
		<a href="#" id="support_<?php echo $x ?>_<?php echo $c ?>" class="support"><?php echo $string ?></a>
		<a href="#" id="del_ssign_<?php echo $x ?>_<?php echo $c ?>" class="del_ssign" style="display: <?php echo $display_delete_link ?>; margin-left: 5px">(cancella)</a>
		<span id="ct_<?php echo $x."_".$c ?>"><?php echo $act_link ?></span>
	</td>
<tr>
<?php
	}
?>
<tr>
	<td style="height: 5px" colspan="4" class="nohover">
		&nbsp;
	</td>
<tr>
<?php
	//$index++;
}
?>
</tbody>
</table>
<p style="height: 0px">
<input type="hidden" name="ora" id="ora" value="" />
<input type="hidden" name="mat" id="mat" value="" />
<input type="hidden" name="action" id="action" value="" />
<input type="hidden" name="id_reg" id="id_reg" value="<?php print $_REQUEST['id_reg'] ?>" />
</p>
</form>
</div>
<!--
DIV nascosto che contiene le materie
-->
<div id="hid" style="display: none; width: 200px; height: 65px; position: absolute">
<?php
$k = 0;
foreach($materie as $m){
?>
    <a id="mat_<?php echo $m['id_materia'] ?>" style="font-weight: normal;" class="subj" href="#"><?php echo truncateString($m['materia'], 20) ?></a><br />
<?php
    $k++;
}
?>
</div>
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_label"><span>Classe <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></span></div>
		<div class="drawer_link submenu">
			<a href="registro_classe.php?data=<?php echo date("Y-m-d") ?>"><img src="../../../images/28.png" style="margin-right: 10px; position: relative; top: 5%"/>Registro di classe</a>
		</div>
		<div class="drawer_link submenu">
			<a href="stats.php"><img src="../../../images/18.png" style="margin-right: 10px; position: relative; top: 5%"/>Statistiche</a>
		</div>
		<div class="drawer_link separator submenu">
			<a href="notes.php"><img src="../../../images/26.png" style="margin-right: 10px; position: relative; top: 5%"/>Note</a>
		</div>
		<div class="drawer_link submenu"><a href="../registro_personale/index.php"><img src="../../../images/4.png" style="margin-right: 10px; position: relative; top: 5%" />Registro personale</a></div>
		<div class="drawer_link separator submenu"><a href="../gestione_classe/classe.php"><img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />Gestione classe</a></div>
		<div class="drawer_link"><a href="../index.php"><img src="../../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="../profile.php"><img src="../../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../../modules/documents/load_module.php?module=docs&area=teachers"><img src="../../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=teachers"><img src="<?php echo $_SESSION['__path_to_root__'] ?>images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../../shared/do_logout.php"><img src="../../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
<div id="classeslist_drawer" class="drawer" style="height: <?php echo (36 * (count($_SESSION['__user__']->getClasses()) - 1)) ?>px; display: none; position: absolute">
	<?php
	foreach ($_SESSION['__user__']->getClasses() as $cl) {
		if ($cl['id_classe'] != $_SESSION['__classe__']->get_ID()) {
			?>
			<div class="drawer_link ">
				<a href="<?php echo getFileName() ?>?reload=1&cls=<?php echo $cl['id_classe'] ?>&data=<?php echo $_REQUEST['data'] ?>">
					<img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%"/>
					Classe <?php echo $cl['classe'] ?>
				</a>
			</div>
		<?php
		}
	}
	?>
</div>
</body>
</html>
