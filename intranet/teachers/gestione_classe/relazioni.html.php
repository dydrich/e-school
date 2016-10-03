<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Elenco relazioni</title>
	<link rel="stylesheet" href="../../../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
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
			<?php if(($_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())) || ($_SESSION['__user__']->getUsername() == "rbachis")): ?>
			<div class="mdtabs">
				<div class="mdtab<?php if ($_REQUEST['all'] == 0) echo " mdselected_tab" ?>">
					<a href="relazioni.php?all=0"><span>Personali</span></a>
				</div>
				<div class="mdtab<?php if ($_REQUEST['all'] == 1) echo " mdselected_tab" ?>">
					<a href="relazioni.php?all=1"><span>Tutte</span></a>
				</div>
			</div>
			<?php endif; ?>
			<?php if ($_REQUEST['all'] == 1): ?>
			<div class="fright" style="margin-right: 50px">
				<a href="scarica_archivio_relazioni.php?all=<?php echo $_REQUEST['all'] ?>">
					<i style="margin-left: 10px; font-size: 1.5em;" class="fa fa-file-archive-o"></i>
					Scarica l'archivio completo
				</a>
			</div>
			<?php endif; ?>
			<div id="card_container" class="card_container" style="margin-top: 30px">
				<?php
				$tipo = "";
				foreach ($relazioni as $relazione) {
					$owner = true;
					$card_style = "normal";
					$mod_link = "../../../modules/documents/load_module.php?module=docs&area=teachers&page=doc&value=".$relazione['id_documento'];
					$link_color = "accent_color";
					$download_style = "";
					if ($relazione['owner'] != $_SESSION['__user__']->getUid()) {
						$owner = false;
						$card_style = "";
						$mod_link = "javascript: void()";
						$link_color = "disabled_link";
						$download_style = "main_700";
					}
					if ($relazione['tipo'] == 11) {
						$card_style = "accent_color";
					}
				?>
				<div class="card" style="<?php if ($relazione['tipo'] == 10 && $tipo != "" && $tipo != $relazione['tipo']) echo "margin-top: 30px" ?>">
					<div class="card_title card_nocontent <?php echo $card_style ?>">
						<?php echo $relazione['titolo'] ?>
						<div style="float: right; margin-right: 20px; color: #1E4389">
							<a href="../../../modules/documents/load_module.php?module=docs&area=teachers&page=document&value=<?php echo $relazione['id_documento'] ?>" class="normal">
								<i class="fa fa-download <?php echo $download_style ?>"></i>
							</a>
							<a href="<?php echo $mod_link ?>" class="normal">
								<i class="fa fa-pencil <?php echo $link_color ?>" style="margin-left: 20px"></i>
							</a>
						</div>
					</div>
				</div>
				<?php
					$tipo = $relazione['tipo'];
				}
				?>
			</div>
			<p class="spacer"></p>
		</div>
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
</body>
</html>
