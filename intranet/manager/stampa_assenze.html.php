<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var target = {uid: 0, name: ""};
		var this_year = <?php echo $_SESSION['__current_year__']->get_ID() ?>;

		var search = function(uid){
			var url = "cerca_anni_registro.php";
			$.ajax({
				type: "POST",
				url: url,
				data: {uid: uid},
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
						$('#container').text("Nessun dato in archivio per i parametri richiesti");
					}
					else if(json.status == "nopg"){
						$('#container').text(json.message);
					}
					else{
						years = json.data;
						print_string = "";
						for (i in years) {
							t = years[i];
							if (t.id == this_year) {
								$('<a href="dettaglio_alunno.php?idc='+uid+'"><div class="nowcard"><div class="icon_card_accent"><span class="fa fa-desktop"></span></div><p class="text_card">A. S. '+ t.desc +'</p></div></a>').prependTo($('#container'));
								//$('<p><a href="dettaglio_alunno.php?idc='+uid+'">A. S. '+ t.desc +'</a></p>').prependTo($('#container'));
							}
							else {
								//$('<p><a href="report_assenze.php?y=' + t.id + '&s=' + uid + '">A. S. ' + t.desc + '</a></p>').prependTo($('#container'));
								$('<a href="report_assenze.php?y=' + t.id + '&s=' + uid + '"><div class="nowcard"><div class="icon_card"><span class="fa fa-print"></span></div><p class="text_card">A. S. ' + t.desc + '</p></div></a>').prependTo($('#container'));
							}
						}
					}
				}
			});
		};

		$(function(){
			load_jalert();
			setOverlayEvent();

			$("#cognome").autocomplete({
				source: "get_students.php?action=all",
				minLength: 2,
				select: function(event, ui){
					uid = ui.item.uid;
					nm = ui.item.value;
					search(uid);
				}
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
		<form class="reg_form" id="search_form" style="min-height: 150px; margin-top: 10px; overflow: hidden">
			<div style="width: 55%; margin: 10px 0 0px 20px; float: left">
				<table style="width: 95%">
					<tr id="tr_alunno">
						<td style="width: 40%">Cognome e nome</td>
						<td style="width: 60%">
							<input type="text" id="cognome" style="width: 95%" />
						</td>
					</tr>
				</table>
			</div>
			<div style="min-height: 145px;float: right; width: 35%; position: relative" id="container"></div>
		</form>
	</div>
	<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
		<div class="drawer_link"><a href="utility.php"><img src="../../images/59.png" style="margin-right: 10px; position: relative; top: 5%" />Utility</a></div>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
