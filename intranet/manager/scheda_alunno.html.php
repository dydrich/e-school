<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro personale: scheda alunno</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#dis_grades').css({
				cursor: "pointer"
			});
			$('#dis_grades').click(function(event) {
				if ($('#dis_grades_body').is(":visible")) {
					$('#dis_grades_body').slideUp(400);
				}
				else {
					$('#dis_grades_body').slideDown(400);
				}
			});
			$('#dis_notes_did').css({
				cursor: "pointer"
			});
			$('#dis_notes').css({
				cursor: "pointer"
			});
			$('#dis_notes').click(function(event) {
				count = $(this).attr("data-notes-count");
				if (count > 0) {
					if ($('#dis_notes_body').is(":visible")) {
						$('#dis_notes_body').slideUp(400);
					}
					else {
						$('#dis_notes_body').slideDown(400);
					}
				}
			});
			$('#dis_notes_did').css({
				cursor: "pointer"
			});
			$('#dis_notes_did').click(function(event) {
				count = $(this).attr("data-notesdid-count");
				if (count > 0) {
					if ($('#dis_notesdid_body').is(":visible")) {
						$('#dis_notesdid_body').slideUp(400);
					}
					else {
						$('#dis_notesdid_body').slideDown(400);
					}
				}
			});
			$('#dis_absences').css({
				cursor: "pointer"
			});
			$('#dis_absences').click(function(event) {
				count = $(this).attr("data-abs-count");
				if (count > 0) {
					if ($('#dis_absences_body').is(":visible")) {
						$('#dis_absences_body').slideUp(400);
					}
					else {
						$('#dis_absences_body').slideDown(400);
					}
				}
			});
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
<?php include "header.php" ?>
<?php include $_SESSION['__administration_group__']."/navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include $_SESSION['__administration_group__']."/menu.php" ?>
	</div>
	<div id="left_col">
	<div class="card_container" style="margin-top: 20px">
		<div class="card">
			<div id="dis_notes" class="card_title" data-notes-count="<?php echo $res_note->num_rows ?>">Note disciplinari
				<div class="normal" style="float: right; width: 200px; margin-right: 30px">
					<?php echo $label_notes_dis ?>
				</div>
			</div>
			<div id="dis_notes_body" class="card_varcontent" style="display: none">
				<?php
				if ($res_note->num_rows > 0) {
					while ($row = $res_note->fetch_assoc()) {
						$data_nota = strftime("%A %d %B %Y", strtotime($row['data']));
						$teacher = "";
						if ($row['tipo'] == 1) {
							$teacher = " ".$row['cognome']." ".$row['nome'];
						}
				?>
				<div class="card_row" style="overflow: auto">
					<div><?php echo $data_nota."::".$row['tipo_nota'].$teacher ?></div>
					<div class="normal" style="width: 95%; border-top: 1px solid rgba(30, 67, 137, .1); padding: 10px">
						<?php echo $row['descrizione'] ?>
					</div>
				</div>
				<?php
					}
				}
				?>
			</div>
		</div>
		<div class="card">
			<div id="dis_notes_did" class="card_title" data-notesdid-count="<?php echo $note_didattiche['count'] ?>">Note didattiche
				<div class="normal" style="float: right; width: 200px; margin-right: 30px">
					<?php echo $label_notes_did ?>
				</div>
			</div>
			<div id="dis_notesdid_body" class="card_varcontent" style="display: none">
				<?php
				if ($note_didattiche['count'] > 0) {
					foreach ($note_didattiche['data'] as $k => $tipo_nota) {
				?>
				<div class="card_row">
					<?php echo $tipo_nota['tipo_nota'] ?>: <?php echo $tipo_nota['count'] ?> volte
				</div>
				<?php
					}
				}
				?>
			</div>
		</div>
		<div class="card" style="margin-top: 10px" >
			<div id="dis_absences" class="card_title" data-abs-count="<?php echo $res_note->num_rows ?>">Assenze
				<div class="normal" style="float: right; width: 200px; margin-right: 30px">
					<?php echo $perc_hour ?>% del monte ore totale
				</div>
			</div>
			<div id="dis_absences_body" class="card_longcontent" style="display: none">
				<div class="minicard">
					Giorni di assenza: <?php echo $studentData['absences']?> su <?php echo $totali['giorni'] ?>
				</div>
				<div class="minicard" style="margin-left: 50px">
					Ore di assenza: <?php echo $absences->toString(RBTime::$RBTIME_SHORT) ?> su <?php echo $totali['ore']->toString(RBTime::$RBTIME_SHORT) ?>
				</div>
				<div class="minicard">
				<?php
				if ($somma_ritardi['giorni_ritardo'] > 0) {
					?>
					Ritardi: <?php echo $somma_ritardi['giorni_ritardo'] ?> per un totale di <?php echo substr($somma_ritardi['ore_ritardo'], 0, 5) ?> ore
				<?php
				}
				else {
					?>
					Nessun ritardo
				<?php
				}
				?>
				</div>
				<div class="minicard" style="margin-left: 50px">
				<?php
				if ($somma_uscite['giorni_anticipo'] > 0) {
					?>
					Uscite anticipate: <?php echo $somma_uscite['giorni_anticipo'] ?> per un totale di <?php echo substr($somma_uscite['ore_perse'], 0, 5) ?> ore
				<?php
				}
				else {
					?>
					Nessuna uscita anticipata
				<?php
				}
				?>
				</div>
			</div>
		</div>
		<div class="card">
			<div id="dis_grades" class="card_title" data-grades-count="">Media voto
				<div class="normal" style="float: right; width: 200px; margin-right: 30px">
					<?php echo $media ?>
				</div>
			</div>
			<div id="dis_grades_body" class="card_varcontent" style="display: none; height: 200px">
				<?php
				$idx = 1;
				foreach ($materie as $s_id => $subject) {
					$span_class = "";
					if ($subject['media'] == 0) {
						$subject['media'] = "--";
					}
					else if ($subject['media'] < 6) {
						$span_class = "attention _bold";
					}
				?>
				<div class="minicard" style="<?php if(($idx%2) == 0) echo "margin-left: 50px" ?>">
					<?php echo $subject['materia'] ?>: <span class="<?php echo $span_class ?>"><?php echo $subject['media'] ?></span>
				</div>
				<?php
					$idx++;
				}
				?>
			</div>
		</div>
	</div>
</div>
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

