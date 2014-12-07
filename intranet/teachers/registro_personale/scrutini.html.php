<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro personale: scrutini</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		var stid = 0;

		var change_subject = function(id){
			document.location.href="scrutini.php?subject="+id+"&q=<?php print $q ?>";
		};

		var alunni = new Array();
		<?php
		while($r = $res_dati->fetch_assoc()){
		?>
		alunni.push(<?php echo$r['alunno'] ?>);
		<?php
		}
		?>
		$(function(){
			load_jalert();
			setOverlayEvent();
			for(var i = 0; i < alunni.length; i++){
				alunno = alunni[i];
				//alert(alunno);
				var on_arch = $('#abstd_'+alunno).html();
				$('#grade_'+alunno).load(
					'get_grade_absences.php',
					{
						req: 'grade',
						alunno: alunno,
						q: <?php echo $q ?>
					}
				);
				$('#abs_'+alunno).load(
					'get_grade_absences.php',
					{
						req: "absences",
						alunno: alunno,
						tot: 0,
						q: <?php echo $q ?>
					}
				);
				if(on_arch == 0) {
					$('#abstd_'+alunno).load(
						'get_grade_absences.php',
						{
							req: "absences",
							alunno: alunno,
							tot: 1,
							q: <?php echo $q ?>
						}
					);
				}
				$('#grade_'+alunno).show(1500);
				<?php if ($ordine_scuola == 1) : ?>
				$('#abs_'+alunno).show(1500)
				<?php endif; ?>
			}
			upd_avg();
			$('#imglink').click(function(event){
				event.preventDefault();
				show_menu('imglink');
			});
			$('#menu_div').mouseleave(function(event){
				event.preventDefault();
				$('#menu_div').hide();
			});
			$('#overlay').click(function(event) {
				if ($('#overlay').is(':visible')) {
					show_drawer(event);
				}
				$('#other_drawer').hide();
				$('#classeslist_drawer').hide();
			});
			$('.drawer_label span').click(function(event){
				var off = $(this).parent().offset();
				show_classlist(event, off);
			}).css({
				cursor: "pointer"
			});
			$('#showsub').click(function(event){
				var off = $(this).parent().offset();
				_show(event, off);
			});
		});

		var upd_avg = function(){
			var url = "get_avg.php";
			$('#avg').load(
				url,
				{
					param: 'avg',
					q: <?php echo $q ?>
				}
			);
			$('#avg2').load(
				url,
				{
					param: 'grd',
					q: <?php echo $q ?>
				}
			);
		};

		var upd_grade = function(sel, alunno){
			var url = "upd_grade.php";
			//alert(url);
			$.ajax({
				type: "POST",
				url: url,
				data: {grade: sel.value, alunno: alunno, q: <?php echo $q ?>},
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
					else {
						upd_avg();
					}
				}
			});
		};

		var show_menu = function(el) {
			if($('#menu_div').is(":hidden")) {
				position = $('#ctx_img').offset();
				ftop = position.top + $('#ctx_img').height();
				fleft = position.left - ($('#menu_div').width() - $('#ctx_img').width());
				console.log("top: "+ftop+"\nleft: "+fleft);
				$('#menu_div').css({top: ftop+"px", left: fleft+"px", position: "absolute", zIndex: 100});
				$('#menu_div').slideDown(500);
			}
			else {
				$('#menu_div').hide();
			}
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

		var show_classlist = function(e, off) {
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
	</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<?php
$label_subject = "";
if (count($_SESSION['__subjects__']) > 1) {
	?>
	<div class="mdtabs">
		<?php
		foreach ($_SESSION['__subjects__'] as $mat) {
			if (isset($_SESSION['__materia__']) && $_SESSION['__materia__'] == $mat['id']) {
				$label_subject = "::".$mat['mat'];
			}
			?>
		<div class="mdtab<?php if (isset($_SESSION['__materia__']) && $_SESSION['__materia__'] == $mat['id']) echo " mdselected_tab" ?>">
			<a href="#" onclick="change_subject(<?php echo $mat['id'] ?>)"><span><?php echo $mat['mat'] ?></span></a>
		</div>
		<?php
		}
		?>
	</div>
<?php
}

?>
<form action="student.php" method="post">
<table class="registro">
<thead>
<tr class="head_tr_no_bg">
	<td colspan="3" style="text-align: center; border-top: 0"><span id="ingresso" style="font-weight: normal; text-transform: uppercase; color: black"><?php print $_SESSION['__classe__']->to_string() ?><?php echo $label_subject ?></span></td>
</tr>
<tr class="title_tr">
	<td style="width: 50%; font-weight: bold; padding-left: 8px">Alunno</td>
	<td style="width: 25%; text-align: center; font-weight: bold">Voto (media voto)</td>
	<td style="width: 25%; text-align: center; font-weight: bold">Assenze</td>
</tr>
</thead>
<tbody>
<?php 
$idx = 0;
$res_dati->data_seek(0);
while($al = $res_dati->fetch_assoc()){
	$background = "";
	if($idx%2)
		$background = "background-color: #e8eaec";
?>
<tr>
	<td style="width: 40%; padding-left: 8px; font-weight: bold;"><?php if($idx < 9) print "&nbsp;&nbsp;"; ?><?php echo ($idx+1).". " ?>
		<span style="font-weight: normal"><?php print $al['cognome']." ".$al['nome']?></span>
	</td>
	<td style="width: 10%; text-align: center; font-weight: normal;">
	<?php 
	if (!$readonly){
	?>
		<select name="sel_<?php echo $al['alunno'] ?>" style="width: 75px; height: 15px; font-size: 11px" onchange="upd_grade(this, <?php echo $al['alunno'] ?>)">
			<option value="0">NC</option>
			<?php 
			if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
			?>
			<option value='10' <?php if($al['voto'] == 10) print "selected" ?>>Ottimo</option>
			<option value='9' <?php if($al['voto'] == 9) print "selected" ?>>Distinto</option>
			<option value='8' <?php if($al['voto'] == 8) print "selected" ?>>Buono</option>
			<option value='6' <?php if($al['voto'] == 6) print "selected" ?>>Sufficiente</option>
			<option value='4' <?php if($al['voto'] == 4) print "selected" ?>>Insufficiente</option>
			<?php 
			}
			else{
				for($i = 10; $i > 0; $i--){ 
			?>
			<option value="<?php echo $i ?>" <?php if($al['voto'] == $i) print "selected" ?>><?php echo $i ?></option>
			<?php 
				} 
			}
			?>
		</select>
	<?php 
	}
	else {
		if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
	?>
		<span><?php echo $voti_religione[RBUtilities::convertReligionGrade($al['voto'])] ?></span>
	<?php
		}
		else {
	?>
		<span><?php echo $al['voto'] ?></span>
	<?php
		}
	}
	?>
		<span id="grade_<?php echo $al['alunno'] ?>" style="margin-left: 15px; display: none"></span>
	</td>
	<td style="width: 10%; text-align: center; font-weight: normal">
		<span id="abstd_<?php echo $al['alunno'] ?>" style="<?php if ($ordine_scuola == 1) : ?>font-weight: bold;<?php endif; ?> font-size: 1.1em"><?php if ($ordine_scuola == 1) echo $al['assenze']; else echo "ND" ?></span>
		<span id="abs_<?php echo $al['alunno'] ?>" style="margin-left: 15px; display: none"></span>
	</td>
</tr>
<?php
	$idx++; 
}
?>
</tbody>
<tfoot>
<tr>
	<td colspan="3" style="height: 15px"></td>
</tr>
<tr class="riepilogo">
	<td style="padding-left: 10px">Media classe</td>
	<td style="text-align: center">
		<span id="avg" style="padding-right: 10px"></span>
		<span id="avg2" style="font-weight: normal"></span>
	</td>
	<td></td>
</tr>
<tr>
	<td colspan="3" style="height: 15px"></td>
</tr>
<tr class="nav_tr">
	<td colspan="3" style="text-align: center; height: 40px">
			<a href="scrutini.php?q=1" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/24.png" /><span>1 Quadrimestre</span>
			</a>
			<a href="scrutini.php?q=2" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/24.png" /><span>2 Quadrimestre</span>
			</a>
		<!-- <a href="index.php?q=1">1 Quadrimestre</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="index.php?q=2">2 Quadrimestre</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="index.php?q=0">Totale</a> -->
	</td>
</tr>
</tfoot>
</table>
</form>
</div>
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_label"><span>Classe <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></span></div>
		<?php if(count($_SESSION['__subjects__']) > 1){ ?>
			<div class="drawer_link submenu">
				<a href="riepilogo_scrutini.php?q=<?php echo $q ?>"><img src="../../../images/65.png" style="margin-right: 10px; position: relative; top: 5%"/>Riepilogo scrutini</a>
			</div>
		<?php
		}
		if($_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID()) || $_SESSION['__user__']->getUsername() == 'rbachis') { ?>
			<div class="drawer_link submenu separator">
				<a href="scrutini_classe.php?q=<?php echo $q ?>"><img src="../../../images/74.png" style="margin-right: 10px; position: relative; top: 5%"/>Scrutini classe</a>
			</div>
		<?php
		}
		?>
		<?php if ($q == 2): ?>
			<div class="drawer_link separator">
				<a href="confronta_scrutini.php"><img src="../../../images/46.png" style="margin-right: 10px; position: relative; top: 5%"/>Confronta scrutini</a>
			</div>
		<?php endif; ?>
		<div class="drawer_link submenu"><a href="index.php"><img src="../../../images/4.png" style="margin-right: 10px; position: relative; top: 5%" />Registro personale</a></div>
		<?php if($is_teacher_in_this_class && $_SESSION['__user__']->getSubject() != 27 && $_SESSION['__user__']->getSubject() != 44) { ?>
		<div class="drawer_link submenu separator">
			<a href="#" id="showsub"><img src="../../../images/68.png" style="margin-right: 10px; position: relative; top: 5%"/>Altro</a>
		</div>
		<div class="drawer_link submenu"><a href="../registro_classe/registro_classe.php?data=<?php echo date("Y-m-d") ?>"><img src="../../../images/28.png" style="margin-right: 10px; position: relative; top: 5%" />Registro di classe</a></div>
		<div class="drawer_link submenu separator"><a href="../gestione_classe/classe.php"><img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />Gestione classe</a></div>
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
<div id="other_drawer" class="drawer" style="height: 144px; display: none; position: absolute">
	<?php if (!isset($_REQUEST['__goals__']) && (isset($_SESSION['__user_config__']['registro_obiettivi']) && (1 == $_SESSION['__user_config__']['registro_obiettivi'][0]))): ?>
		<div class="drawer_link ">
			<a href="index.php?q=<?php echo $q ?>&subject=<?php echo $_SESSION['__materia__'] ?>&__goals__=1"><img src="../../../images/31.png" style="margin-right: 10px; position: relative; top: 5%"/>Registro per obiettivi</a>
		</div>
	<?php endif; ?>
	<?php if ($ordine_scuola == 1): ?>
		<div class="drawer_link">
			<a href="absences.php"><img src="../../../images/52.png" style="margin-right: 10px; position: relative; top: 5%"/>Assenze</a>
		</div>
	<?php endif; ?>
	<div class="drawer_link">
		<a href="tests.php"><img src="../../../images/79.png" style="margin-right: 10px; position: relative; top: 5%"/>Verifiche</a>
	</div>
	<div class="drawer_link">
		<a href="lessons.php"><img src="../../../images/62.png" style="margin-right: 10px; position: relative; top: 5%"/>Lezioni</a>
	</div>
	<?php
	}
	else { ?>
		<div class="drawer_link separator">
			<a href="scrutini_classe.php?q=<?php echo $_q ?>"><img src="../../../images/34.png" style="margin-right: 10px; position: relative; top: 5%"/>Scrutini</a>
		</div>
	<?php } ?>
</div>
<div id="classeslist_drawer" class="drawer" style="height: <?php echo (36 * (count($_SESSION['__user__']->getClasses()) - 1)) ?>px; display: none; position: absolute">
	<?php
	foreach ($_SESSION['__user__']->getClasses() as $cl) {
		if ($cl['id_classe'] != $_SESSION['__classe__']->get_ID()) {
			?>
			<div class="drawer_link ">
				<a href="<?php echo getFileName() ?>?reload=1&cls=<?php echo $cl['id_classe'] ?>&q=<?php echo $_q ?>">
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
