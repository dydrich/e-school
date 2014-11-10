<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var file = 0;
		var docID = "";

		var show_menu = function(e, _all, _ff, offset){
			tempY = offset.top;
			tempX = offset.left;
			$('#context_menu').css({top: parseInt(tempY)+"px"});
			$('#context_menu').css({left: parseInt(tempX)+"px"});
			$('#context_menu').slideDown(500);
		    docID = _all;
		    file = _ff;
		    return false;
		};

		var download_file = function(){
			<?php if (isset($_GET['sub'])): ?>
			document.location.href = "../../modules/documents/download_manager.php?doc=teacherbook_att&area=teachers&f="+ff+"&cls=<?php echo $_GET['cls'] ?>&sub=<?php echo $_GET['sub'] ?>";
			<?php else: ?>
			document.location.href = "../../modules/documents/download_manager.php?doc=teacherbook_att&area=teachers&f="+ff+"&cls=<?php echo $_GET['cls'] ?>&std=<?php echo $_REQUEST['std'] ?>";
			<?php endif; ?>
			$('#context_menu').hide();
		};

		var delete_file = function(){
			$('#context_menu').hide();
			$.ajax({
				type: "POST",
				url: '../../modules/documents/document_manager.php',
				data: {cls: <?php echo $_GET['cls'] ?>, sub: <?php echo $_GET['sub'] ?>, std: <?php echo $_GET['std'] ?>, doc_type: 'teacherbook_att', action: 2, id: all, f: ff},
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
						alert(json.message);
						console.log(json.dbg_message);
					}
					else if(json.status == "ko") {
						j_alert("error", "Impossibile completare l'operazione richiesta. Riprovare tra qualche secondo o segnalare l'errore al webmaster");
						return;
					}
					else {
						j_alert("alert", "File cancellato")
					}
					$("#att_"+all).hide();
				}
			});
		};

		var loaded = false;
		function loading(time){
			background_process("Attendere il caricamento del file...", time);
		}

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('a.showmenu').click(function(event){
				//alert(this.id);
				event.preventDefault();
				docID = $(this).attr("data-docID");
				file = $(this).attr("data-file");
				offset = $(this).parent().offset();
				offset.top += $(this).parent().height();
				show_menu(event, docID, file, offset);
			});
			$('#context_menu').mouseleave(function(event){
				event.preventDefault();
				$(this).hide();
			})
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
		<div id="welcome">
			<p id="w_head">Registro di <?php echo $desc ?>, classe <?php echo $desc_cls ?></p>
			<div id="att_container">
			<?php 
			if (count($allegati) < 1){
			?>
			Nessun allegato presente.
			<?php
			}
			else{
				foreach ($allegati as $all){
					$ff = preg_replace("/ /", "_", $all['file']);
					$filesize = filesize($_SESSION['__config__']['document_root']."/rclasse/download/registri/".$_SESSION['__current_year__']->get_descrizione()."/{$school_order_directory}/docenti/{$user_directory}/".$ff);
					if($filesize < 1024)
						$filesize .= "B";
					else{
						$filesize /= 1024;
						$filesize = round($filesize, 0);
						$filesize .= "K";
					}
			?>
				<p id="att_<?php echo $all['id'] ?>"><a href="#" style="text-decoration: none" class="showmenu" data-docID="<?php echo $all['id'] ?>" data-file="<?php echo $ff ?>"><?php echo $all['file'] ?> (<?php echo $filesize ?>)</a></p>
			<?php
				}
			}
			?>
			</div>
			<div style="width: 450px; margin-top: 40px">
				<iframe src="../../modules/documents/upload_manager.php?upl_type=teacherbook_att&tipo=null" style="border: none; width: 75%;  margin: 0px; height: 55px" id="aframe"></iframe>
			</div>
		</div>
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
<div id="context_menu" class="context_menu" style="position: absolute; width: 210px; height: 50px; display: none; ">
    <a style="font-weight: normal; text-decoration: none" href="#" onclick="download_file()">Scarica file</a><br />
    <a style="font-weight: normal; text-decoration: none" href="#" onclick="delete_file()">Elimina file</a><br />
</div>
<!-- fine menu contestuale -->
</body>
</html>
