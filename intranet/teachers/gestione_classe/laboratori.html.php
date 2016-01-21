<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Elenco laboratori</title>
	<link rel="stylesheet" href="../../../font-awesome/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		var labID = 0;
		var max = <?php if ($ordine_scuola == 1) echo "5"; else echo "8"; ?>;
		$(function(){
			load_jalert();
			setOverlayEvent();
			$('.show_ctx').click(function(event){
				event.preventDefault();
				$labID = $(this).data('id');
				labID = $labID;
				filter();
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
			$('#chkdate').datepicker({
				dateFormat: "dd/mm/yy",
				maxDate: "+15d",
				minDate: "-0d"
			});
			$('#verify').on('click', function(event) {
				event.preventDefault();
				verify();
			});
		});

		var verify = function() {
			var day = $('#chkdate').val();
			$.ajax({
				type: "POST",
				url: "lab_manager.php",
				data: {day: day, lab: labID, action: 'check'},
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
						j_alert('error', json.message);
						console.log(json.dbg_message);
					}
					else {
						for (var i = 1; i <= max; i++) {
							$('#h'+i).html('');
							$('#h'+i).hide();
						}
						data = json.data;
						if (!data) {
							for (i = 1; i <= max; i++) {
								$('<span>'+i+' ora </span><a href="#" onclick="reserve('+i+')" class="normal no_dec" style="margin-left: 20px">Prenota</a>').appendTo($('#h'+i));
								$('#h'+i).show();
							}
						}
						else {
							for (var i = 1; i <= max; i++) {
								if (data[i]) {
									var item = data[i];
									$('<span>'+i+' ora </span><span class="material_label" style="margin-left: 20px">'+item.desc_cls+' - '+item.desc_tea+'</span>').appendTo($('#h' + i));
									if (item.delete) {
										$('<a href="#" onclick="del('+ i +')"><i class="fa fa-trash fright accent_color"></i></a>').appendTo($('#h' + i));
									}
									$('#h' + i).show();
								}
								else {
									$('<span>'+i+' ora </span><a href="#" onclick="reserve(' + i + ')" class="normal no_dec" style="margin-left: 20px">Prenota</a>').appendTo($('#h' + i));
									$('#h' + i).show();
								}
							}
						}
					}
				}
			});
		};

		var filter = function(lab){
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
				title: 'Verifica disponibilita',
				open: function (event, ui) {

				},
				close: function (event) {
					$('#overlay').hide();
					$('#chkdate').val('');
					for (var i = 1; i <= max; i++) {
						$('#h'+i).html('');
						$('#h'+i).hide();
					}
				}
			});
		};

		var reserve = function(hour) {
			var day = $('#chkdate').val();
			$.ajax({
				type: "POST",
				url: "lab_manager.php",
				data: {day: day, lab: labID, hour: hour, action: 'book'},
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
						j_alert('error', json.message);
						console.log(json.dbg_message);
					}
					else {
						$('#h'+hour).html('');
						$('#h'+hour).html('<span>'+hour+' ora </span><span class="material_label" style="margin-left: 20px"><?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?> - <?php echo $_SESSION['__user__']->getFullName(2) ?></span>');
						$('<a href="#" onclick="del('+ hour +')"><i class="fa fa-trash fright accent_color"></i></a>').appendTo($('#h' + hour));
					}
				}
			});
		};

		var del = function(hour) {
			var day = $('#chkdate').val();
			$.ajax({
				type: "POST",
				url: "lab_manager.php",
				data: {day: day, lab: labID, hour: hour, action: 'del'},
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
						j_alert('error', json.message);
						console.log(json.dbg_message);
					}
					else {
						$('#h'+hour).html('');
						$('<span>'+hour+' ora </span><a href="#" onclick="reserve(' + hour + ')" class="normal no_dec" style="margin-left: 20px">Prenota</a>').appendTo($('#h' + hour));
					}
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
		<div class="card_container" style="margin-top: 10px">
			<?php
			while($lab = $res_labs->fetch_assoc()){
				?>
				<div class="card">
					<div class="card_title card_nocontent">
						<a href="#" class="show_ctx normal" data-id="<?php print $lab['id_lab'] ?>"><?php print ($lab['nome']) ?>
						<div style="float: right; margin-right: 20px" class="accent_color">
							<i class="fa fa-search"></i>
						</div>
						</a>
					</div>
				</div>
				<?php
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
<div id="listfilter" style="display: none; width: 250px">
	Verifica disponibilit&agrave; per il...
	<input type="text" id="chkdate" name="chkdate" style="width: 200px; margin-left: 10px" class="material_input" />
	<p style="width: 90%; text-align: right; margin-right: 20px">
		<a href="#" id="verify" class="material_link">Verifica</a>
	</p>
	<div id="h1" style="display: none; padding-top: 10px" class="bottom_decoration"></div>
	<div id="h2" style="display: none; padding-top: 10px" class="bottom_decoration"></div>
	<div id="h3" style="display: none; padding-top: 10px" class="bottom_decoration"></div>
	<div id="h4" style="display: none; padding-top: 10px" class="bottom_decoration"></div>
	<div id="h5" style="display: none; padding-top: 10px" class="bottom_decoration"></div>
	<?php if ($ordine_scuola == 2) : ?>
	<div id="h6" style="display: none; padding-top: 10px" class="bottom_decoration"></div>
	<div id="h7" style="display: none; padding-top: 10px" class="bottom_decoration"></div>
	<div id="h8" style="display: none; padding-top: 10px" class="bottom_decoration"></div>
	<?php endif; ?>
</div>
</body>
</html>
