<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area genitori</title>
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
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

			$('.book').on('click', function(event) {
				_date = $(this).data('date');
				_teacher = $(this).data('teacher');
				_reserved = $(this).data('reserved');
				book_meeting(_date, _teacher, _reserved);
			});
		});

		var book_meeting = function(date, teacher, reserved){
			var url = "booking_manager.php";
			if (reserved == 1) {
				action = 'delete_booking';
			}
			else {
				action = 'book';
			}
			$.ajax({
				type: "POST",
				url: url,
				data: {action: action, date: date, teacher: teacher},
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
						window.setTimeout(function(){
							location.reload();
						}, 2000);
					}
				}
			});
		};

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
	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "class_working.php" ?>
</div>
<div id="left_col">
	<div class="card_container">
	<?php 
	foreach ($data as $k => $a){
		$meetings = $meetings_manager->getNextTeacherMeetings($k, 2);
		$label = "prenotazione non necessaria";
		$mandatory = 0;
		/*
		 * prenotazioni esistenti
		 */
		$res = $db->executeQuery("SELECT data FROM rb_prenotazioni_colloqui WHERE genitore = ".$_SESSION['__user__']->getUid()." AND docente = $k");
		$reservations = [];
		while($row = $res->fetch_assoc()) {
			$reservations[] = $row['data'];
		}

		if ($meetings['settings']['mandatory'] == 1) {
			$label = "prenotazione obbligatoria: clicca sulla data per prenotare";
			$mandatory = 1;
		}
	?>
	<div class="card">
		<div class="card_title">
			<?php echo $a['nome'] ?>
			<div style="float: right; width: 50%; margin-right: 10px" class="main_700">
				<?php echo implode(", ", $a['sec_f']) ?>
			</div>
		</div>
		<div class="card_varcontent">
			<span class="">Prossimi colloqui (<?php echo $label ?>)</span>
			<?php
			if ($meetings['meetings'] == null) {
				echo "<p>Non registrati</p>";
			}
			else {
				foreach ($meetings['meetings'] as $meeting) {
					/*
					 * dati orario classe
					 */
					$d = date("w", strtotime($meeting['data']));
					$day = $schedule_module->getDay($d);
					$starts = $day->getLessonsStartTime();
					$start = $starts[$meetings['settings']['hour']];
					if ($meetings['settings']['hour'] == 3) {
						$start->add(600);
					}

					/*
					 * verifica prenotazione effettuata
					 */
					$search_str = $meeting['data']." ".$start->toString(RBTime::$RBTIME_LONG);
					$reserved = 0;
					$reserved_label = '';
					if (is_array($reservations) && in_array($search_str, $reservations)) {
						$reserved = 1;
						$reserved_label = " (prenotazione effettuata: clicca per cancellare)";
					}

					$giorno_str = strftime("%A %d %B", strtotime($meeting['data']));
					if ($mandatory) {
					?>
						<p style="margin: 5px 0 0 0">
							<a href="#" class="book normal" data-reserved="<?php echo $reserved ?>" data-teacher="<?php echo $k ?>" data-date="<?php echo $meeting['data']." ".$start->toString(RBTime::$RBTIME_LONG) ?>" style="margin: 5px 0 0 0">
								<?php echo $giorno_str.", ore ".$start->toString().$reserved_label ?>
							</a>
						</p>
					<?php
					}
					else {
					?>
						<p style="margin: 5px 0 0 0"><?php echo $giorno_str ?></p>
					<?php
					}
				}
			}
			?>
		</div>
	</div>
	<?php 
	}
	?>
	</div>
</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<?php if (count($_SESSION['__sons__']) > 1): ?>
			<div class="drawer_link separator">
				<a href="#" id="showsub"><img src="../../images/69.png" style="margin-right: 10px; position: relative; top: 5%"/>Seleziona alunno</a>
			</div>
		<?php endif; ?>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=com&area=genitori"><img src="../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=genitori"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
<?php
if (count($_SESSION['__sons__']) > 1){
	$height = 36 * (count($_SESSION['__sons__']));
	?>
	<div id="other_drawer" class="drawer" style="height: <?php echo  $height ?>px; display: none; position: absolute">
		<?php
		$indice = 1;
		reset($_SESSION['__sons__']);
		while(list($key, $val) = each($_SESSION['__sons__'])){
			$cl = "";
			if ($key == $_SESSION['__current_son__']) {
				$cl = " _bold";
			}
			?>
			<div class="drawer_link">
				<a href="<?php print $page ?>?son=<?php print $key ?>" clas="<?php echo $cl ?>"><?php print $val[0] ?></a>
			</div>
		<?php
		}
		?>
	</div>
<?php
}
?>
</body>
</html>
