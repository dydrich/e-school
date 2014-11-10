<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#hid').mouseleave(function(event){
				$('#hid').hide();
			});
		});

		var materia = function(event){
		    //alert("ok");
		    $('#hid').hide();
		    var uid = document.forms[0].id_ora.value;
		    var mat = document.forms[0].mat.value;
		    var teacher = document.forms[0].teach.value;
		    var url = "../../../shared/upd_ora.php";
			$.ajax({
				type: "POST",
				url: url,
				data:  {uid: uid, mat: mat, teacher: teacher},
				dataType: 'json',
				error: function() {
					alert("Errore di trasmissione dei dati");
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
						var materia = json.materia;
						$('#ora'+json.id_ora).fadeOut(400);
						setTimeout(function(){
							$('#ora'+json.id_ora).text(materia);
							$('#ora'+json.id_ora).fadeIn(400);
						}, 400);
					}
				}
			});
		};

		var visualizza = function(e, elem) {
			<?php
		    if($_SESSION['__classe__']->getSchoolOrder() == 1 && !$coordinatore){
		    ?>
		    //do_nothing();
		    j_alert("error", "Modifica permessa solamente al coordinatore della classe");
		    return false;
		    <?php
		    }
		    else{
		    ?>
			if ($('#hid').is(":visible")) {
				$('#hid').slideUp(400);
				return false;
			}
			off = $(elem).parent().offset();
			off.top += $(elem).parent().height();
		    $('#hid').css({top: off.top+"px", left: off.left+"px"});
			$('#hid').slideDown(500);
		    return true;
		    <?php
		    }
		    ?>
		};

	</script>
	<style>
	tbody a {
		text-decoration: none
	}
	</style>
</head> 
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "class_working.php" ?>
</div>
<div id="left_col">
	<!-- DIV nascosto che contiene le materie: ogni riga e' un link che carica materie.php    -->
	<div id="hid" style="position: absolute; width: 220px; height: 320px; display: none; ">
	<?php
	$k = 0;
	while(list($key, $value) = each($materie)){
	?>
	    <a style="font-weight: normal;" href="#" onclick="document.forms[0].mat.value = <?php echo $key ?>; document.forms[0].teach.value = <?php echo $value[2] ?>; materia(event); "><?php echo $value[0] ?></a><br />
	<?php
	    $k++;
	}
	?>
	</div> 
	<form method="post" class="no_border">
		<div style="position: absolute; top: 75px; margin-left: 675px; margin-bottom: 10px; " class="rb_button">
			<a href="pdf_class_schedule.php">
				<img src="../../../images/pdf-32.png" style="padding: 4px 0 0 7px" />
			</a>
		</div>
	<div class="outline_line_wrapper" style="margin-top: 30px">
		<div style="width: 9%; float: left; position: relative; top: 25%"><span style="padding-left: 35px">Ora</span></div>
		<div style="width: 31%; float: left; position: relative; top: 25%" class="_center">Luned&igrave;</div>
		<div style="width: 27%; float: left; position: relative; top: 25%" class="_center">Marted&igrave;</div>
		<div style="width: 29%; float: left; position: relative; top: 25%" class="_center">Mercoled&igrave;</div>
	</div>
    <table style="margin: 0 auto 0 auto; text-align: center; font-size: 1em; width: 90%">
        <?php 
        for($i = 0; $i < $ore; $i++){
        	reset($materie);
        ?>
        <tr class="bottom_decoration">
        	<td style="width: 7%; "><?php echo $i+1 ?></td>
        	<td style="width: 31%; "><a style="font-weight: normal" href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(1, $i+1, $classe); if($d != null) echo $d->getID() ?>; visualizza(event, this)" id="ora<?php $d = $orario_classe->searchHour(1, $i+1, $classe); if($d != null) echo $d->getID() ?>"><?php if (isset($materie[$orario_classe->getMateria($classe, 1, $i+1)])) echo $materie[$orario_classe->getMateria($classe, 1, $i+1)][0] ?></a></td>
        	<td style="width: 31%; "><a style="font-weight: normal" href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(2, $i+1, $classe); if($d != null) echo $d->getID() ?>; visualizza(event, this)" id="ora<?php $d = $orario_classe->searchHour(2, $i+1, $classe); if($d != null) echo $d->getID() ?>"><?php if (isset($materie[$orario_classe->getMateria($classe, 2, $i+1)])) echo $materie[$orario_classe->getMateria($classe, 2, $i+1)][0] ?></a></td>
        	<td style="width: 31%; "><a style="font-weight: normal" href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(3, $i+1, $classe); if($d != null) echo $d->getID() ?>; visualizza(event, this)" id="ora<?php $d = $orario_classe->searchHour(3, $i+1, $classe); if($d != null) echo $d->getID() ?>"><?php if (isset($materie[$orario_classe->getMateria($classe, 3, $i+1)])) echo $materie[$orario_classe->getMateria($classe, 3, $i+1)][0] ?></a></td>
        </tr>
        <?php 
        }
        ?>
        <tr>
            <td colspan="4" style="height: 40px"></td>
        </tr>
    </table>
	<div class="outline_line_wrapper">
		<div style="width: 9%; float: left; position: relative; top: 25%"><span style="padding-left: 35px">Ora</span></div>
		<div style="width: 31%; float: left; position: relative; top: 25%" class="_center">Gioved&igrave;</div>
		<div style="width: 27%; float: left; position: relative; top: 25%" class="_center">Venerd&igrave;</div>
		<div style="width: 29%; float: left; position: relative; top: 25%" class="_center">Sabato</div>
	</div>       
    <table style="margin: 0 auto 0 auto; text-align: center; font-size: 1em; width: 90%">
        <?php 
        for($i = 0; $i < $ore; $i++){
        	reset($materie);
        ?>
        <tr class="bottom_decoration">
        	<td style="width: 7%; "><?php echo $i+1 ?></td>
        	<td style="width: 31%; "><a style="font-weight: normal" href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(4, $i+1, $classe); if($d != null) echo $d->getID() ?>; visualizza(event, this)" id="ora<?php $d = $orario_classe->searchHour(4, $i+1, $classe); if($d != null) echo $d->getID() ?>"><?php if (isset($materie[$orario_classe->getMateria($classe, 4, $i+1)])) echo $materie[$orario_classe->getMateria($classe, 4, $i+1)][0] ?></a></td>
        	<td style="width: 31%; "><a style="font-weight: normal" href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(5, $i+1, $classe); if($d != null) echo $d->getID() ?>; visualizza(event, this)" id="ora<?php $d = $orario_classe->searchHour(5, $i+1, $classe); if($d != null) echo $d->getID() ?>"><?php if (isset($materie[$orario_classe->getMateria($classe, 5, $i+1)])) echo $materie[$orario_classe->getMateria($classe, 5, $i+1)][0] ?></a></td>
        	<td style="width: 31%; "><a style="font-weight: normal" href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(6, $i+1, $classe); if($d != null) echo $d->getID() ?>; visualizza(event, this)" id="ora<?php $d = $orario_classe->searchHour(6, $i+1, $classe); if($d != null) echo $d->getID() ?>"><?php if (isset($materie[$orario_classe->getMateria($classe, 6, $i+1)])) echo $materie[$orario_classe->getMateria($classe, 6, $i+1)][0] ?></a></td>
        </tr>
        <?php 
        }
        ?>
            <tr>
                <td colspan="4">&nbsp;&nbsp;&nbsp;
                	<input type="hidden" name="mat" />
                	<input type="hidden" name="teach" />
        			<input type="hidden" name="id_ora" />
                </td>
            </tr>
    </table>
    </form>
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
</body>
</html>
