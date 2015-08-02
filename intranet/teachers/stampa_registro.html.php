<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var cls = 0;
		var sub = 0;
		var $that = null;

		var show_menu = function(e, idc, subj, offset){
			if ($('#context_menu').is(":visible")) {
				//$('#context_menu').slideUp(400);
				$('#context_menu').trigger("mouseleave");
				return false;
			}
			tempY = offset.top;
			tempX = offset.left;
		    $('#context_menu').css({top: parseInt(tempY)+"px"});
		    $('#context_menu').css({left: parseInt(tempX)+"px"});
		    $('#context_menu').slideDown(500);
		    cls = idc;
		    sub = subj;
		    return false;
		};

		var downloadLog = function(){
			file = "registro_<?php echo $_SESSION['__current_year__']->get_ID() ?>_<?php echo $_SESSION['__user__']->getUid(true) ?>_"+cls+"_"+sub;
			document.location.href = "../../modules/documents/download_manager.php?doc=teacherbook&area=teachers&f="+file;
			$('#context_menu').hide();
		};

		var downloadAll = function(){
			file = "registro_<?php echo $_SESSION['__current_year__']->get_ID() ?>_<?php echo $_SESSION['__user__']->getUid(true) ?>_"+cls+"_"+sub;
			document.location.href = "../../modules/documents/download_manager.php?doc=teacherbookall&area=teachers&f="+file;
			$('#context_menu').hide();
		};

		var attach = function(){
			document.location.href = "allegati_registro.php?cls="+cls+"&sub="+sub;
		};

		var createLog = function(batch){
			if (batch) {
				loading("Creazione registri in corso", 20);
			}
			else {
				loading("Creazione registro in corso", 20);
			}
			$.ajax({
				type: "POST",
				url: 'print_log.php',
				data: {cls: cls, sub: sub, batch: batch},
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
						loaded(json.message);
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
				$(this).parent().css({
					/* stampa registro */
					backgroundColor: '#01579b',
				});
				$(this).css({
					color: '#FFFFFF',
					fontWeight: 'normal',
					fontSize: '1.1em',
					paddingLeft: '10px'
				});
				event.preventDefault();
				idc = $(this).attr("data-idc");
				subj = $(this).attr("data-subj");
				offset = $(this).parent().offset();
				offset.top += $(this).parent().height();
				$that = $(this);
				show_menu(event, idc, subj, offset);
			});
			$('#context_menu').mouseleave(function(event){
				event.preventDefault();
				$(this).slideUp(400);
				$that.parent().css({
					/* stampa registro */
					backgroundColor: ''
				});
				$that.css({
					color: '',
					fontWeight: 'normal',
					fontSize: '1em',
					paddingLeft: '0'
				});
			});
			$('#batch_create').click(function(event){
				event.preventDefault();
				createLog(1);
			});
		});
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
	if ($ordine_scuola == 1){
	?>
		<div style="position: absolute; top: 75px; margin-left: 625px; margin-bottom: 10px" class="rb_button">
			<a href="#" id="batch_create" title="Crea tutti i registri">
				<i class="fa fa-save" style="font-size: 26px; padding: 7px 0 0 10px; color: #222222"></i>
			</a>
		</div>
	<?php
		if ($_SESSION['__user__']->getSubject() == 12 || $_SESSION['__user__']->getSubject() == 9){
			foreach ($classi as $k => $classe){
				if (in_array($k, $ids)){
	?>
		<div class="welcome">
			<p id="w_head">CLASSE <?php echo $classe['name'] ?></p>
			<table style="width: 100px">
			<?php 
				foreach ($classe['subjects'] as $i => $s){
			?>
				<tr style="height: 25px">
					<td style="width: 100px"><a href="../../shared/no_js.php" class="dlog" data-idc="<?php echo $k ?>" data-subj="<?php echo $i ?>"><?php echo $s['mat'] ?></a></td>
				</tr>
			<?php 
				}
			?>
			</table>
		</div>
	<?php
				}
			}
		}	
		else {
	?>
		<div id="welcome">
			<p id="w_head"><?php echo strtoupper($materia) ?></p>
			<table style="width: 300px">
	<?php 
			foreach ($classi as $k => $classe){
				if (in_array($k, $ids)){
	?>
				<tr style="height: 25px">
					<td style="width: 100px"><a href="../../shared/no_js.php" class="dlog" data-idc="<?php echo $k ?>" data-subj="<?php echo $_SESSION['__user__']->getSubject() ?>">CLASSE <?php echo $classe['name'] ?></a></td>
				</tr>
			<?php
				}
			}
			?>
			</table>
		</div>
	<?php
		}
	}
	else if ($ordine_scuola == 2){
		foreach ($classi as $k => $classe){
			if (in_array($k, $ids)){
	?>
		<div id="welcome">
			<p id="w_head">CLASSE <?php echo $classe['name'] ?></p>
			<table style="width: 200px">
			<?php 
				foreach ($classe['subjects'] as $i => $s){
			?>
				<tr style="height: 25px">
					<td style="width: 200px"><a href="../../shared/no_js.php" class="dlog" data-idc="<?php echo $k ?>" data-subj="<?php echo $i ?>"><?php echo $s['mat'] ?></a></td>
				</tr>
			<?php
				}
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
<div class="context_menu" id="context_menu" style="position: absolute; width: 210px; height: 70px; display: none; ">
    <a style="font-weight: normal; text-decoration: none" href="#" onclick="attach()">Gestisci allegati</a><br />
    <a style="font-weight: normal; text-decoration: none" href="#" onclick="downloadLog()">Scarica solo il registro</a><br />
    <a style="font-weight: normal; text-decoration: none" href="#" onclick="downloadAll()">Scarica registro e allegati</a><br />
    <a style="font-weight: normal; text-decoration: none" href="#" onclick="createLog(0)">Crea o ricrea il registro</a><br />
</div>
<!-- fine menu contestuale -->
</body>
</html>
