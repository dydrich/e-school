<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>: gestione colloqui</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		_id = 0;
		$(function(){
			load_jalert();
			setOverlayEvent();

			$('#newweek').datepicker({
				dateFormat: 'yy-mm-dd',
				onClose: function(){
					add_interview();
				}
			});

			$('.delete_meeting').on('click', function(event) {
				_id = $(this).data('id');
				j_alert("confirm", "Eliminare la settimana di colloqui?");
			});

			$('#okbutton').on('click', function (event) {
				event.preventDefault();
				delete_interview(_id);
			});
		});

		var add_interview = function() {
			var url = "meetings_manager.php";
			$.ajax({
				type: "POST",
				url: url,
				data: {action: 'insert_school_meeting', date: $('#newweek').val()},
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
					else{
						location.reload(true);
					}
				}
			});
		};

		var delete_interview = function(id) {
			$('#confirm').fadeOut(10);
			var url = "meetings_manager.php";
			$.ajax({
				type: "POST",
				url: url,
				data: {action: 'delete_school_meeting', id: id},
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
					else{
						j_alert("alert", "Settimana eliminata");
						$("a[data-id='"+id+"']").hide(400);
					}
				}
			});
		};
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
		<div id="accordion" style="width: 90%; margin: auto">
			<?php
			foreach ($months as $k => $month) {
				if (isset($colloqui[$month['num']])) {
					?>
					<h3 class="accent_decoration"><?php echo $month['desc'] ?></h3>
					<div>
						<?php
						foreach ($colloqui[$month['num']] as $item) {
							$start_date = new DateTime($item['data']);
							$end_date = new DateTime($item['data']);
							$end_date->add(new DateInterval('P5D'));
							$start_month = searchMultidimensionalArrayForValue($months, $start_date->format("m"), 'num');
							$end_month = searchMultidimensionalArrayForValue($months, $end_date->format("m"), 'num');
							$class = 'material_link';
							$today = new DateTime();
							if ($end_date < $today) {
								$class = 'disabled_link';
							}
							?>
							<a href="#" data-id="<?php echo $item['id'] ?>" class="<?php echo $class ?> delete_meeting" style="display: flex; min-height: 44px">
								<p style="order: 1; flex-grow: 2">
									da lunedì <?php echo $start_date->format("d") . " " . strtolower($months[$start_month]['desc']) ?>
									a sabato <?php echo $end_date->format("d") . " " . strtolower($months[$end_month]['desc']) ?>
								</p>
								<i class="fa fa-trash" style="margin: 13px 50% 13px 0; order: 2; flex-grow: 1"></i>
							</a>
							<?php
						}
						?>
					</div>

					<?php
				}
			}
			?>
			<div style="clear:both; overflow:hidden;">
				<input type="text" name="newweek" id="newweek" value="Nuovo colloquio" style="width: 110px; height: 25px; text-align: center; font-size: 11px; font-family: Georgia; margin: auto"  />
			</div>
		</div>
	</div>
	<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 360px">
		<div class="drawer_label"><span>Classe <?php echo $_REQUEST['desc'] ?></span></div>
		<div class="drawer_link submenu"><a href="classe.php?id=<?php echo $_REQUEST['id'] ?>&show=cdc&desc=<?php echo $_REQUEST['desc'] ?>&tp=<?php echo $_REQUEST['tp'] ?>"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />Elenco docenti</a></div>
		<div class="drawer_link submenu"><a href="classe.php?id=<?php echo $_REQUEST['id'] ?>&show=alunni&desc=<?php echo $_REQUEST['desc'] ?>&tp=<?php echo $_REQUEST['tp'] ?>"><img src="../../images/35.png" style="margin-right: 10px; position: relative; top: 5%" />Elenco alunni</a></div>
		<div class="drawer_link submenu separator"><a href="classe.php?id=<?php echo $_REQUEST['id'] ?>&show=orario&desc=<?php echo $_REQUEST['desc'] ?>&tp=<?php echo $_REQUEST['tp'] ?>"><img src="../../images/70.png" style="margin-right: 10px; position: relative; top: 5%" />Orario delle lezioni</a></div>
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
