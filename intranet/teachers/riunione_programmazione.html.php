<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti - Obiettivi didattici</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script>
		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#date').datepicker({
				dateFormat: "yy-mm-dd"
			});
			$('#start').timepicker({
				hour: 14,
				minute: 30
			});
			$('#end').timepicker({
				hour: 15,
				minute: 30
			});
			$('#regbutton').button().click(function(){
				submit_data();
			});
			$('.card_title').click(function(){
				$(this).siblings('div.card_longcontent').toggle(1000);
			});
			$('.card_title').css({
				cursor: "pointer"
			});
		});

		var action = '<?php if ($_REQUEST['rid'] == 0) echo "insert"; else echo "update" ?>';

		var submit_data = function() {
			if ($('#date').val() == "" || $('#start').val() == "" || $('#end').val() == "") {
				alert("Tutti i campi con l'asterisco sono obbligatori");
				return false;
			}
			else {
				$.ajax({
					type: "POST",
					url: 'planmeet_manager.php',
					data: {
						action: action,
						rid: <?php echo $_REQUEST['rid'] ?>,
						date: $('#date').val(),
						start: $('#start').val(),
						end: $('#end').val(),
						italiano: $('#italiano').val(),
						storia: $('#storia').val(),
						geografia: $('#geografia').val(),
						immagine: $('#immagine').val(),
						tecnologia: $('#tecnologia').val(),
						musica: $('#musica').val(),
						religione: $('#religione').val(),
						matematica: $('#matematica').val(),
						scienze: $('#scienze').val(),
						motoria: $('#motoria').val(),
						inglese: $('#inglese').val(),
						assenti: $('#assenti').val(),
						altro: $('#altro').val()
					},
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
						else if(json.status == "ko") {
							j_alert("error", "Impossibile completare l'operazione richiesta. Riprovare tra qualche secondo o segnalare l'errore al webmaster");
							return;
						}
						else {
							j_alert("alert", json.message)
							if (action == "insert") {
								//alert(json.rid);
								setTimeout(
									function() {
										document.location.href = "riunione_programmazione.php?rid="+json.rid;
									},
									2000
								);
							}
						}
					}
				});
			}
		};
	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu_moduli.php" ?>
	</div>
	<div id="left_col">
		<div class="card_container">
			<div class="card" style="padding-top: 10px">
				<div style="width: 13%; float: left; font-weight: bold; height: 30px">Data *</div>
				<div style="width: 20%; float: left">
					<input type="text" name="date" id="date" style="width: 80%" value="<?php if (isset($planMeet)) echo format_date($planMeet->getMeetingDate(), SQL_DATE_STYLE, IT_DATE_STYLE, "/"); else echo date("d/m/Y") ?>" />
				</div>
				<div style="width: 13%; float: left; font-weight: bold">Inizio *</div>
				<div style="width: 20%; float: left">
					<input type="text" name="start" id="start" style="width: 70%" value="<?php if (isset($planMeet)) echo substr($planMeet->getStartTime(), 0, 5); else echo "14:30" ?>"/>
				</div>
				<div style="width: 13%; float: left; font-weight: bold">Termine *</div>
				<div style="width: 20%; float: left">
					<input type="text" name="end" id="end" style="width: 70%" value="<?php if (isset($planMeet)) echo substr($planMeet->getEndTime(), 0, 5); else echo "15:30" ?>" />
				</div>
				<p style="clear: left"></p>
			</div>
			<div class="card">
				<div class="card_title">Assenti</div>
				<div class="card_longcontent" style="<?php if (!isset($planMeet)) echo "display: none"; else if (isset($planMeet) && $planMeet->getAbsents() == "") echo "display: none" ?>">
					<textarea id="assenti" name="assenti" style="width: 99%; height: 40px"><?php if (isset($planMeet)) echo $planMeet->getAbsents() ?></textarea>
				</div>
			</div>
			<div class="card">
				<div class="card_title">Italiano</div>
				<div class="card_longcontent" style="<?php if (!isset($planMeet)) echo "display: none"; else if (isset($planMeet) && $planMeet->getSubject("italiano") == "") echo "display: none" ?>">
					<textarea id="italiano" name="italiano" style="width: 99%; height: 40px"><?php if (isset($planMeet)) echo $planMeet->getSubject("italiano") ?></textarea>
				</div>
			</div>
			<div class="card">
				<div class="card_title">Matematica</div>
				<div class="card_longcontent" style="<?php if (!isset($planMeet)) echo "display: none"; else if (isset($planMeet) && $planMeet->getSubject("matematica") == "") echo "display: none" ?>">
					<textarea id="matematica" name="matematica" style="width: 99%; height: 40px"><?php if (isset($planMeet)) echo $planMeet->getSubject("matematica") ?></textarea>
				</div>
			</div>
			<div class="card">
				<div class="card_title">Religione</div>
				<div class="card_longcontent" style="<?php if (!isset($planMeet)) echo "display: none"; else if (isset($planMeet) && $planMeet->getSubject("religione") == "") echo "display: none" ?>">
					<textarea id="religione" name="religione" style="width: 99%; height: 40px"><?php if (isset($planMeet)) echo $planMeet->getSubject("religione") ?></textarea>
				</div>
			</div>
			<div class="card">
				<div class="card_title">Immagine</div>
				<div class="card_longcontent" style="<?php if (!isset($planMeet)) echo "display: none"; else if (isset($planMeet) && $planMeet->getSubject("immagine") == "") echo "display: none" ?>">
					<textarea id="immagine" name="immagine" style="width: 99%; height: 40px"><?php if (isset($planMeet)) echo $planMeet->getSubject("immagine") ?></textarea>
				</div>
			</div>
			<div class="card">
				<div class="card_title">Inglese</div>
				<div class="card_longcontent" style="<?php if (!isset($planMeet)) echo "display: none"; else if (isset($planMeet) && $planMeet->getSubject("inglese") == "") echo "display: none" ?>">
					<textarea id="inglese" name="inglese" style="width: 99%; height: 40px"><?php if (isset($planMeet)) echo $planMeet->getSubject("inglese") ?></textarea>
				</div>
			</div>
			<div class="card">
				<div class="card_title">Storia</div>
				<div class="card_longcontent" style="<?php if (!isset($planMeet)) echo "display: none"; else if (isset($planMeet) && $planMeet->getSubject("storia") == "") echo "display: none" ?>">
					<textarea id="storia" name="storia" style="width: 99%; height: 40px"><?php if (isset($planMeet)) echo $planMeet->getSubject("storia") ?></textarea>
				</div>
			</div>
			<div class="card">
				<div class="card_title">Geografia</div>
				<div class="card_longcontent" style="<?php if (!isset($planMeet)) echo "display: none"; else if (isset($planMeet) && $planMeet->getSubject("geografia") == "") echo "display: none" ?>">
					<textarea id="geografia" name="geografia" style="width: 99%; height: 40px"><?php if (isset($planMeet)) echo $planMeet->getSubject("geografia") ?></textarea>
				</div>
			</div>
			<div class="card">
				<div class="card_title">Motoria</div>
				<div class="card_longcontent" style="<?php if (!isset($planMeet)) echo "display: none"; else if (isset($planMeet) && $planMeet->getSubject("motoria") == "") echo "display: none" ?>">
					<textarea id="motoria" name="motoria" style="width: 99%; height: 40px"><?php if (isset($planMeet)) echo $planMeet->getSubject("motoria") ?></textarea>
				</div>
			</div>
			<div class="card">
				<div class="card_title">Scienze</div>
				<div class="card_longcontent" style="<?php if (!isset($planMeet)) echo "display: none"; else if (isset($planMeet) && $planMeet->getSubject("scienze") == "") echo "display: none" ?>">
					<textarea id="scienze" name="scienze" style="width: 99%; height: 40px"><?php if (isset($planMeet)) echo $planMeet->getSubject("scienze") ?></textarea>
				</div>
			</div>
			<div class="card">
				<div class="card_title">Musica</div>
				<div class="card_longcontent" style="<?php if (!isset($planMeet)) echo "display: none"; else if (isset($planMeet) && $planMeet->getSubject("musica") == "") echo "display: none" ?>">
					<textarea id="musica" name="musica" style="width: 99%; height: 40px"><?php if (isset($planMeet)) echo $planMeet->getSubject("musica") ?></textarea>
				</div>
			</div>
			<div class="card">
				<div class="card_title">Tecnologia</div>
				<div class="card_longcontent" style="<?php if (!isset($planMeet)) echo "display: none"; else if (isset($planMeet) && $planMeet->getSubject("tecnologia") == "") echo "display: none" ?>">
					<textarea id="tecnologia" name="tecnologia" style="width: 99%; height: 40px"><?php if (isset($planMeet)) echo $planMeet->getSubject("tecnologia") ?></textarea>
				</div>
			</div>
			<div class="card">
				<div class="card_title">Altro</div>
				<div class="card_longcontent" style="<?php if (!isset($planMeet)) echo "display: none"; else if (isset($planMeet) && $planMeet->getOther() == "") echo "display: none" ?>">
					<textarea id="altro" name="altro" style="width: 99%; height: 40px"><?php if (isset($planMeet)) echo $planMeet->getOther() ?></textarea>
				</div>
			</div>
		</div>
		<div style="width: 85%; height: 30px; margin-left: 5%; margin-bottom: 15px; text-align: right">
			<button id="regbutton">Registra</button>
		</div>
	</div>
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=teachers"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=teachers"><img src="../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
	<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
