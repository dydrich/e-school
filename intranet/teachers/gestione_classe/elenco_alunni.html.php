<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Elenco alunni</title>
	<link rel="stylesheet" href="../../../font-awesome/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
			$('.show_ctx').click(function(event){
				event.preventDefault();
				id_alunno = $(this).attr("data-id");
				<?php //if((!$_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())) && (!$_SESSION['__user__']->isAdministrator()) ): ?>
				//return false;
				<?php //else: ?>
				var offset = $(this).parent().offset();
				offset.top += $(this).parent().height();
				show_menu(event, id_alunno, offset);
				<?php //endif; ?>
			});
			$('#std_list_ctx').mouseleave(function(event){
				$('#std_list_ctx').hide(300);
			});
			$('.profile_link').click(function(event){
				event.preventDefault();
				show_profile();
			});
			$('.profile_print').click(function(event){
				event.preventDefault();
				print_profile();
			});
			$('.phone_link').click(function(event){
				event.preventDefault();
				show_phones();
			});
			$('#overlay').click(function(event) {
				if ($('#overlay').is(':visible')) {
					show_drawer(event);
				}
				$('#classeslist_drawer').hide();
			});
			$('.drawer_label span').click(function(event){
				var off = $(this).parent().offset();
				_show(event, off);
			}).css({
				cursor: "pointer"
			});
			$('#pdf').click(function(event){
				event.preventDefault();
				filter();
			});
		});

		var show_menu = function(e, _stid, offset){
			$('#std_list_ctx').css({top: offset.top+"px", left: offset.left+"px"});
		    $('#std_list_ctx').slideDown(500);
		    stid = _stid;
		    return false;
		};

		var show_profile = function(){
			document.location.href = "dettaglio_alunno.php?stid="+stid;
		};

		var show_phones = function(){
			document.location.href = "telefoni_alunno.php?stid="+stid;
		};

		var print_profile = function(){
			document.location.href = "pdf_profilo_alunno.php?stid="+stid;
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

		var filter = function(){
			$('#drawer').hide();
			$('#listfilter').dialog({
				autoOpen: true,
				show: {
					effect: "appear",
					duration: 200
				},
				hide: {
					effect: "slide",
					duration: 200
				},
				modal: true,
				width: 450,
				height: 300,
				title: 'Filtra elenco',
				open: function(event, ui){

				},
				close: function(event) {
					$('#overlay').hide();
				}
			});
		};

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
	<div style="position: absolute; top: 75px; margin-left: 675px; margin-bottom: 10px; " class="rb_button">
		<a href="#" id="pdf">
			<img src="../../../images/pdf-32.png" style="padding: 4px 0 0 7px" />
		</a>
	</div>
	<div class="card_container" style="margin-top: 10px">
	<?php 
	$background = "";
	$idx = 1;
	while($alunno = $res_alunni->fetch_assoc()){
		$ripetente = "NO";
		if($alunno['ripetente'] == 1)
			$ripetente = "SI";
			
		// estraggo l'indirizzo e il telefono
		$address = "Non presente";
		$phone = "Non presente";
		$sel_add = "SELECT * FROM rb_indirizzi_alunni WHERE id_alunno = ".$alunno['id_alunno'];
		$res_add = $db->execute($sel_add);
		if($res_add->num_rows > 0){
			$add = $res_add->fetch_assoc();
			if($add['indirizzo'] != ""){
				$address = $add['indirizzo'];
			}
		}

		$tel = array();
		$sel_ph = "SELECT telefono FROM rb_telefoni_alunni WHERE id_alunno = ".$alunno['id_alunno'];
		$res_ph = $db->execute($sel_ph);
		if($res_ph->num_rows > 0){
			while ($ph = $res_ph->fetch_assoc()) {
				$tel[] = $ph['telefono'];
			}
			$phone = implode(", ", $tel);
		}
		
		$data_nascita = "--";
		if ($alunno['data_nascita'] != ""){
			$data_nascita = format_date($alunno['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/");
		}
		
	?>
	<div class="card">
		<div class="card_title">
			<a class="show_ctx" data-id="<?php print $alunno['id_alunno'] ?>"><?php print ($alunno['cognome']." ".$alunno['nome']) ?></a>
			<div style="float: right; margin-right: 20px" class="normal">
				<?php echo $alunno['luogo_nascita'].", ". $data_nascita ?>
			</div>
		</div>
		<div class="card_content">
			<span id="add<?php print $alunno['id_alunno'] ?>">Indirizzo: <?php print $address ?></span><br />
			<span id="phn<?php print $alunno['id_alunno'] ?>">Telefono: <?php echo $phone ?></span>
		</div>
	</div>
	<?php 
		$idx++;
	}
	?>
<form id="testform" method="post" class="no_border">
<p>
	<input type="hidden" name="fname" id="fname" />
	<input type="hidden" name="lname" id="lname" />
	<input type="hidden" name="stid" id="stid" />
</p>
</form>
</div>
</div>
<p class="spacer"></p>
<!-- menu contestuale -->
    <div id="std_list_ctx" class="context_menu">
    	<a href="#" class="profile_link">Visualizza il profilo</a><br />
	    <a href="#" class="phone_link">Numeri di telefono</a><br />
    	<a href="#" class="profile_print">Scarica scheda</a><br />
    </div>
<!-- fine menu contestuale -->
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
				<a href="<?php echo getFileName() ?>?reload=1&cls=<?php echo $cl['id_classe'] ?>">
					<img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%"/>
					Classe <?php echo $cl['classe'] ?>
				</a>
			</div>
		<?php
		}
	}
	?>
</div>
<div id="listfilter" style="display: none; width: 250px">
	<p><a href="pdf_elenco_alunni.php?t=1">Solo nomi</a></p>
	<p><a href="pdf_elenco_alunni.php?t=2">Anagrafica</a></p>
	<p><a href="pdf_elenco_alunni.php?t=3">Con indirizzi</a></p>
	<p><a href="pdf_elenco_alunni.php?t=4">Numeri di telefono</a></p>
	<p><a href="pdf_elenco_alunni.php?t=5">Numeri di telefono con descrizione</a></p>
	<p><a href="pdf_elenco_alunni.php?t=6">Completo</a></p>
</div>
</body>
</html>
