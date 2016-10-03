<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro personale: voti alunno</title>
	<link rel="stylesheet" href="../../../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		var _show = function(e, off) {
			if ($('#other_drawer').is(":visible")) {
				$('#other_drawer').hide('slide', 300);
				return;
			}
			var offset = $('#drawer').offset();
			var top = off.top;

			var left = offset.left + $('#drawer').width() + 1;
			$('#other_drawer').css({top: top+"px", left: left+"px", zIndex: 1000});
			$('#other_drawer').show('slide', 300);
			return true;
		};

		var change_subject = function(id){
			document.location.href="voti_alunno.php?subj="+id+"&q=<?php echo $q ?>";
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#overlay').click(function(event) {
				if ($('#overlay').is(':visible')) {
					show_drawer(event);
				}
				$('#other_drawer').hide();
			});
			$('#showsub').click(function(event){
				var off = $(this).parent().offset();
				_show(event, off);
			});
		});
	</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
	<div class="mdtabs">
		<?php
		foreach ($materie as $mat) {
			if (isset($_REQUEST['subj']) && $_REQUEST['subj'] == $mat['id']) {
				$label_subject = "::".$mat['mat'];
			}
			if ($mat['mat'] == "Materia alternativa") {
				$mat['mat'] = "Mat. alt.";
			}
			if ($mat['mat'] == "Arte e immagine") {
				$mat['mat'] = "Arte";
			}
			if ($mat['mat'] == "Educazione fisica") {
				$mat['mat'] = "Ed. fis.";
			}

			?>
			<div class="mdtab<?php if (isset($_REQUEST['subj']) && $_REQUEST['subj'] == $mat['id']) echo " mdselected_tab" ?>" style="width: 75px">
				<a href="#" onclick="change_subject(<?php echo $mat['id'] ?>)"><span><?php echo $mat['mat'] ?></span></a>
			</div>
		<?php
		}
		?>
	</div>
	<div style="top: -7px; margin-left: 35px" class="rb_button">
		<a href="medie_voto.php">
			<img src="../../../images/47bis.png" style="padding: 12px 0 0 12px" />
		</a>
	</div>
	<table class="registro" style="margin-top: -25px">
		<thead>
		<tr class="head_tr_no_bg">
			<td style="text-align: center; " colspan="3"><span id="ingresso" style=""><?php print $_SESSION['__classe__']->to_string() ?></span></td>
			<td colspan="1" style="text-align: center">Elenco voti dell'alunno <?php echo $alunno['nome']." ".$alunno['cognome'] ?>::<?php echo $materie[$subj]['mat'] ?></td>
		</tr>
		<tr class="title_tr _center">
			<td style="width: 8%">Voto</td>
			<td style="width: 10%">Data</td>
			<td style="width: 27%">Descrizione</td>
			<td style="width: 55%">Argomento</td>
		</tr>
		</thead>
		<tbody>
		<?php
		while ($voto = $res_voti->fetch_assoc()){
			if ($subj == 26 || $subj == 30) {
				$voti_rel = RBUtilities::getReligionGrades();
				$voto['voto'] = $voti_rel[RBUtilities::convertReligionGrade($voto['voto'])];
			}
			?>
			<tr class="title_tr">
				<td style="width: 8%" class="_center _bold <?php if($voto['voto'] < 6 && $voto['voto'] > 0) echo "attention" ?>"><?php echo $voto['voto'] ?></td>
				<td style="width: 10%" class="_center"><?php echo format_date($voto['data_voto'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></td>
				<td style="width: 27%; padding-left: 5px" class="_center"><?php echo $voto['descrizione'] ?></td>
				<td style="width: 55%; padding-left: 5px"><?php echo $voto['argomento'] ?></td>
			</tr>
		<?php
		}

		?>
		</tbody>
		<tfoot>
		<tr>
			<td colspan="4" style="height: 15px"></td>
		</tr>
		<tr class="nav_tr">
			<td colspan="5" style="text-align: center; height: 40px">
				<a href="voti_alunno.php?q=1&subj=<?php echo $subj ?>" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
					<img style="margin-right: 5px; position: relative; top: 2px" src="../../../images/24.png" />1 Quadrimestre
				</a>
				<a href="voti_alunno.php?q=2&subj=<?php echo $subj ?>" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
					<img style="margin-right: 5px; position: relative; top: 2px" src="../../../images/24.png" />2 Quadrimestre
				</a>
				<a href="voti_alunno.php?q=0&subj=<?php echo $subj ?>" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
					<img style="margin-right: 5px; position: relative; top: 2px" src="../../../images/24.png" />Totale
				</a>
				<!-- <a href="dettaglio_medie.php?q=1">1 Quadrimestre</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="dettaglio_medie.php?q=2">2 Quadrimestre</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="dettaglio_medie.php?q=0">Totale</a> -->
			</td>
		</tr>
		</tfoot>
	</table>
</div>
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_label"><span>Classe <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></span></div>
		<div class="drawer_link submenu"><a href="medie_voto.php"><img src="../../../images/4.png" style="margin-right: 10px; position: relative; top: 5%" />Medie voto</a></div>
		<div class="drawer_link submenu"><a href="../registro_classe/registro_classe.php?data=<?php echo date("Y-m-d") ?>"><img src="../../../images/28.png" style="margin-right: 10px; position: relative; top: 5%" />Registro di classe</a></div>
		<div class="drawer_link submenu"><a href="scrutini.php?q=1"><img src="../../../images/74.png" style="margin-right: 10px; position: relative; top: 5%" />Scrutini</a></div>
		<div class="drawer_link submenu separator"><a href="../gestione_classe/classe.php"><img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />Gestione classe</a></div>
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
</body>
</html>
