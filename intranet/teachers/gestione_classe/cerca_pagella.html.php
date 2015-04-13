<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		var y = <?php echo $year ?>;
		var q = <?php echo $q ?>;
		var search = function(){
			if(($('#cognome').val() == "")){
				alert("E' obbligatorio indicare il cognome");
				yellow_fade("tr_cognome");
				return false;
			}
			var url = "../../manager/report_manager.php";
			$.ajax({
				type: "POST",
				url: url,
				data: {y: y, cls: <?php echo $_SESSION['__classe__']->get_ID() ?>, lname: $('#cognome').val(), q: q, action: "search"},
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
						j_alert('error', json.message);
						console.log(json.dbg_message);
					}
					else if(json.status == "nostd"){
						$('#container').text("Nessun alunno in archivio per i parametri richiesti");
					}
					else if (json.status == "nopg") {
						$('#container').text(json.message);
					}
					else {
						var print_string = "";
						for(data in json){
							var t = json[data];
							//alert(t.del);
							if (t.del == 1){
								//print_string += "<p><a href='#' onclick='dwld_file(\"../../../lib/download_manager.php?dw_type=report&f="+t.file+"&sess=1&stid="+t.id+"&y=<?php echo $_SESSION['__current_year__']->get_ID() ?>&noread=1&delete=1\")' style=''>"+t.nome+" (1 quadrimestre)</a></p>";
								print_string += "<p><a href='#' onclick='dwld_file(\"../../../modules/documents/download_manager.php?doc=report&school_order=<?php echo $school ?>&area=teachers&f="+t.file+"&sess=1&stid="+t.id+"&y=<?php echo $_SESSION['__current_year__']->get_ID() ?>&noread=1&delete=1\")' style=''>"+t.nome+" (1 quadrimestre)</a></p>";
							}
							else {
								//print_string += "<p><a href='../../../lib/download_manager.php?dw_type=report&f="+t.file+"&sess=2&stid="+t.id+"&y=<?php echo $_SESSION['__current_year__']->get_ID() ?>&noread=1' style=''>"+t.nome+" (2 quadrimestre)</a></p>";
								print_string += "<p><a href='../../../modules/documents/download_manager.php?doc=report&school_order=<?php echo $school ?>&area=teachers&f="+t.file+"&sess=2&stid="+t.id+"&y=<?php echo $_SESSION['__current_year__']->get_ID() ?>&noread=1' style=''>"+t.nome+" (2 quadrimestre)</a></p>";
							}
						}
						$('#container').html(print_string);
					}
				}
			});
		};

		var dwld_file = function(href){
			document.location = href;
			$('#container').text('');
		};

		var _show = function(e, off) {
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

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#overlay').click(function(event) {
				if ($('#overlay').is(':visible')) {
					show_drawer(event);
				}
				$('#classeslist_drawer').hide();
			});
			$('#search_lnk').click(function(event){
				event.preventDefault();
				search();
			});
			$('.drawer_label span').click(function(event){
				var off = $(this).parent().offset();
				_show(event, off);
			}).css({
				cursor: "pointer"
			});
		});

	</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "class_working.php" ?>
</div>
<div id="left_col">
	<form class="reg_form" id="search_form" style="min-height: 150px;">
	<div style="width: 45%; margin-left: 20px; float: left">
		<table style="width: 95%">
			<tr id="tr_cognome">
				<td style="width: 40%">Cognome</td>
				<td style="width: 60%">
					<input type="text" id="cognome" style="width: 95%" autofocus />
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding-top: 20px"><a href="../../shared/no_js.php" id="search_lnk" class="material_link">Cerca la pagella</a></td>
			</tr>
		</table>
	</div>
	<div style="float: right; width: 45%" id="container"></div>
	</form>
</div>
<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_label"><span>Classe <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></span></div>
		<div class="drawer_link submenu"><a href="../registro_classe/registro_classe.php?data=<?php echo date("Y-m-d") ?>"><img src="../../../images/28.png" style="margin-right: 10px; position: relative; top: 5%" />Registro di classe</a></div>
		<div class="drawer_link submenu separator"><a href="../registro_personale/index.php"><img src="../../../images/4.png" style="margin-right: 10px; position: relative; top: 5%" />Registro personale</a></div>
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
		<a href="<?php echo $page ?>?reload=1&cls=<?php echo $cl['id_classe'] ?>">
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
