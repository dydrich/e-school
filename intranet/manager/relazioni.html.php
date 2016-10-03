<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Elenco relazioni per classe</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
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
			$('.show_detail').click(function(event){
				event.preventDefault();
				var idc = $(this).data("div");
				$('#cls'+idc).toggle(400);
				if($(this).children("i").hasClass("fa-folder")) {
					$(this).children("i").removeClass("fa-folder");
					$(this).children("i").addClass("fa-folder-open");
				}
				else {
					$(this).children("i").removeClass("fa-folder-open");
					$(this).children("i").addClass("fa-folder");
				}
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
<?php include "header.php" ?>
<?php include $_SESSION['__administration_group__']."/navigation.php" ?>
	<div id="main">
		<div id="right_col">
			<?php include $_SESSION['__administration_group__']."/menu_supplenze.php" ?>
		</div>
		<div id="left_col">
			<div class="fright" style="margin-right: 50px">
				<a href="scarica_archivio_relazioni.php?all=1">
					<i style="margin-left: 10px; font-size: 1.5em;" class="fa fa-file-archive-o"></i>
					Scarica l'archivio completo
				</a>
			</div>
			<div id="card_container" class="card_container" style="margin-top: 30px">
				<?php
				$tipo = "";
				foreach ($relazioni as $idc => $relazione) {

				?>
				<div class="card" style="">
					<div class="card_title normal card_nocontent">
						Classe <?php echo $relazione['classe'] ?>
						<div style="float: right; margin-right: 20px">
							<a href="#" title="Vedi il dettaglio" class="show_detail" data-div="<?php echo $relazione['id_classe'] ?>">
								<i class="fa fa-folder accent_color" style="margin-left: 5px"></i>
							</a>
							<a href="scarica_archivio_relazioni.php?classe=<?php echo $idc ?>" title="Scarica tutti i documenti" data-div="<?php echo $relazione['id_classe'] ?>">
								<i class="fa fa-file-archive-o" style="margin-left: 25px"></i>
							</a>
						</div>
					</div>
					<div id="cls<?php echo $relazione['id_classe'] ?>" class="card_varcontent" style="display: none; overflow: hidden; padding-bottom: 10px">
						<?php
						foreach ($relazione['docs'] as $id => $rel) {
						?>
						<div style="width: 65%; margin-left: 15%" class="fleft accent_decoration"><?php echo $rel['titolo'] ?></div>
						<a href="../../modules/documents/load_module.php?module=docs&area=teachers&page=document&value=<?php echo $id ?>">
							<div class="fleft accent_decoration" style="width: 5%"><i class="fa fa-download normal"></i></div>
						</a>
						<?php
						}
						?>
					</div>
				</div>
				<?php
				}
				?>
			</div>
			<p class="spacer"></p>
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
