<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var cls = 0;
		var std = 0;

		var show_menu = function(e, _all, _ff){
			tempY = offset.top;
			tempX = offset.left;
			$('#context_menu').css({top: parseInt(tempY)+"px"});
			$('#context_menu').css({left: parseInt(tempX)+"px"});
			$('#context_menu').slideDown(500);
			cls = idc;
			stud = subj;
			return false;
		};

		var downloadLog = function(){
			file = "registro-sostegno_<?php echo $_SESSION['__current_year__']->get_ID() ?>_<?php echo $_SESSION['__user__']->getUid() ?>_"+cls+"_"+std;
			document.location.href = "../../modules/documents/download_manager.php?doc=teacherbook&area=teachers&f="+file;
			$('#context_menu').hide();
		};

		var downloadAll = function(){
			file = "registro-sostegno_<?php echo $_SESSION['__current_year__']->get_ID() ?>_<?php echo $_SESSION['__user__']->getUid() ?>_"+cls+"_"+std;
			document.location.href = "../../modules/documents/download_manager.php?doc=teacherbookall&area=teachers&f="+file+"&support=1";
			$('#context_menu').hide();
		};

		var attach = function(){
			document.location.href = "allegati_registro.php?cls="+cls+"&std="+std;
		};

		var createLog = function(){
			loading("Attendere la creazione del registro...", 20);
			$.ajax({
				type: "POST",
				url: 'print_log.php',
				data: {cls: cls, std: std},
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
						$('#background_msg').text("Il registro è stato creato");
						timeout = 3;
					}
				}
			});
			$('#context_menu').hide();
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('a.dlog').click(function(event){
				//alert(this.id);
				event.preventDefault();
				idc = $(this).attr("data-idc");
				subj = $(this).attr("data-stud");
				offset = $(this).parent().offset();
				offset.top += $(this).parent().height();
				show_menu(event, idc, subj, offset);
			});
			$('#context_menu').mouseleave(function(event){
				event.preventDefault();
				$(this).hide();
			})
		});

		var loaded = false;
	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "profile_menu.php" ?>
	</div>
	<div id="left_col">
		<?php
		reset($classi);
		if ($ordine_scuola == 1){
			foreach ($classi as $k => $classe){
				?>
				<div id="welcome">
					<p id="w_head">CLASSE <?php echo $classe['classe'] ?></p>
					<table style="width: 100px">
						<?php
						foreach ($classe['studenti'] as $i => $s){
							?>
							<tr style="height: 25px">
								<td style="width: 100px"><a href="../../shared/no_js.php" class="dlog" data-idc="<?php echo $k ?>" data-stud="<?php echo $i ?>"><?php echo $s ?></a></td>
							</tr>
						<?php
						}
						?>
					</table>
				</div>
			<?php
			}
		}
		else if ($ordine_scuola == 2){
			foreach ($classi as $k => $classe){
				?>
				<div id="welcome">
					<p id="w_head">CLASSE <?php echo $classe['classe'] ?></p>
					<table style="width: 200px">
						<?php
						foreach ($classe['studenti'] as $i => $s){
							?>
							<tr style="height: 25px">
								<td style="width: 200px"><a href="../../shared/no_js.php" class="dlog" data-idc="<?php echo $k ?>" data-stud="<?php echo $i ?>"><?php echo $s ?></a></td>
							</tr>
						<?php
						}
						?>
					</table>
				</div>
			<?php
			}
		}
		?>
	</div>
	<p class="spacer"></p>
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
<!-- menu contestuale -->
<div id="context_menu" class="context_menu" style="position: absolute; width: 210px; height: 60px; display: none; ">
	<a style="font-weight: normal; text-decoration: none" href="#" onclick="attach()">Gestisci allegati</a><br />
	<a style="font-weight: normal; text-decoration: none" href="#" onclick="downloadLog()">Scarica solo il registro</a><br />
	<a style="font-weight: normal; text-decoration: none" href="#" onclick="downloadAll()">Scarica registro e allegati</a><br />
	<a style="font-weight: normal; text-decoration: none" href="#" onclick="createLog()">Crea o ricrea il registro</a><br />
</div>
<!-- fine menu contestuale -->
</body>
</html>
