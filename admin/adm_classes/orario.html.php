<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Gestione orario di classe</title>
	<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link href="../../css/general.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var materia = function(event){
		    //alert("ok");
		    $('#hid').hide();
		    var uid = document.forms[0].id_ora.value;
		    var mat = document.forms[0].mat.value;
		    var teacher = document.forms[0].teach.value;
		    var url = "../../shared/upd_ora.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {uid: uid, mat: mat, teacher: teacher},
				dataType: 'json',
				error: function() {
					show_error("Errore di trasmissione dei dati");
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
					else {
						var materia = json.materia;
						var id_ora = "ora"+json.id_ora;
						$('#'+id_ora).fadeOut(400);
						setTimeout(function(){
							$('#'+id_ora).text(materia);
							$('#'+id_ora).fadeIn(400);
						}, 400);
					}
				}
			});

		};

		var visualizza = function(e, elem) {
			if ($('#hid').is(":visible")) {
				$('#hid').slideUp(400);
				return false;
			}
			off = $(elem).parent().offset();
			off.top += $(elem).parent().height();
			$('#hid').css({top: off.top+"px", left: off.left+"px"});
			$('#hid').slideDown(500);
			return true;
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#hid').mouseleave(function(event){
				event.preventDefault();
		        $('#hid').hide();
		    });
		});

	</script>
</head>
<body>
	<!--
    DIV nascosto che contiene le materie: ogni riga e' un link che carica materie.php
    -->
    <div id="hid" style="position: absolute; width: 220px; height: 330px; display: none; ">
    <?php
    while(list($key, $value) = each($materie)){
    	if($key != 1){
    ?>
        <a style="" href="#" onclick="document.forms[0].mat.value = <?php print $key ?>; document.forms[0].teach.value = <?php print $value[2] ?>; materia(event); "><?php print $value[0] ?></a><br />
    <?php
    	}
    }
    ?>
    </div>
	<?php include "../header.php" ?>
	<?php include "../navigation.php" ?>
	<div id="main">
		<div id="right_col">
			<?php include "menu.php" ?>
		</div>
		<div id="left_col">
        <form method="post" class="no_border">
	        <div class="outline_line_wrapper" style="margin-top: 30px">
		        <div style="width: 9%; float: left; position: relative; top: 25%"><span style="padding-left: 35px">Ora</span></div>
		        <div style="width: 31%; float: left; position: relative; top: 25%" class="_center">Luned&igrave;</div>
		        <div style="width: 27%; float: left; position: relative; top: 25%" class="_center">Marted&igrave;</div>
		        <div style="width: 29%; float: left; position: relative; top: 25%" class="_center">Mercoled&igrave;</div>
	        </div>
        <table style="width: 90%; margin-right: auto; margin-left: auto; text-align: center; border-collapse: collapse">
        <?php 
        for($i = 0; $i < $ore; $i++){
        	reset($materie);
        ?>
        <tr class="bottom_decoration">
        	<td style="width: 7%"><?php echo $i+1 ?></td>
        	<td style="width: 31%"><a href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(1, $i+1, $classe); if ($d) print $d->getID() ?>; visualizza(event, this)" id="ora<?php $d = $orario_classe->searchHour(1, $i+1, $classe); if ($d) print $d->getID() ?>"><?php print $materie[$orario_classe->getMateria($classe, 1, $i+1)][0] ?></a></td>
        	<td style="width: 31%"><a href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(2, $i+1, $classe); if ($d) print $d->getID() ?>; visualizza(event, this)" id="ora<?php $d = $orario_classe->searchHour(2, $i+1, $classe); if ($d) print $d->getID() ?>"><?php print $materie[$orario_classe->getMateria($classe, 2, $i+1)][0] ?></a></td>
        	<td style="width: 31%"><a href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(3, $i+1, $classe); if ($d) print $d->getID() ?>; visualizza(event, this)" id="ora<?php $d = $orario_classe->searchHour(3, $i+1, $classe); if ($d) print $d->getID() ?>"><?php print $materie[$orario_classe->getMateria($classe, 3, $i+1)][0] ?></a></td>
        </tr>
        <?php 
        }
        ?>
	</table>
    <div class="outline_line_wrapper" style="margin-top: 50px">
        <div style="width: 9%; float: left; position: relative; top: 25%"><span style="padding-left: 35px">Ora</span></div>
        <div style="width: 31%; float: left; position: relative; top: 25%" class="_center">Gioved&igrave;</div>
        <div style="width: 27%; float: left; position: relative; top: 25%" class="_center">Venerd&igrave;</div>
        <div style="width: 29%; float: left; position: relative; top: 25%" class="_center">Sabato</div>
    </div>
	<table style="width: 90%; margin-right: auto; margin-left: auto; text-align: center; border-collapse: collapse">
        <?php 
        for($i = 0; $i < $ore; $i++){
        	reset($materie);
        ?>
        <tr class="bottom_decoration">
        	<td style="width: 7%"><?php print $i+1 ?></td>
        	<td style="width: 31%"><a href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(4, $i+1, $classe); if ($d) print $d->getID() ?>; visualizza(event, this)" id="ora<?php $d = $orario_classe->searchHour(4, $i+1, $classe); if ($d) print $d->getID() ?>"><?php print $materie[$orario_classe->getMateria($classe, 4, $i+1)][0] ?></a></td>
        	<td style="width: 31%"><a href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(5, $i+1, $classe); if ($d) print $d->getID() ?>; visualizza(event, this)" id="ora<?php $d = $orario_classe->searchHour(5, $i+1, $classe); if ($d) print $d->getID() ?>"><?php print $materie[$orario_classe->getMateria($classe, 5, $i+1)][0] ?></a></td>
        	<td style="width: 31%"><a href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(6, $i+1, $classe); if ($d) print $d->getID() ?>; visualizza(event, this)" id="ora<?php $d = $orario_classe->searchHour(6, $i+1, $classe); if ($d) print $d->getID() ?>"><?php print $materie[$orario_classe->getMateria($classe, 6, $i+1)][0] ?></a></td>
        </tr>
        <?php 
        }
        ?>
        <tr>
                <td colspan="4">&nbsp;&nbsp;&nbsp;</td>
        </tr>
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
			<div class="drawer_link"><a href="../../index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
			<div class="drawer_link"><a href="../index.php"><img src="../../images/31.png" style="margin-right: 10px; position: relative; top: 5%" />Admin</a></div>
			<div class="drawer_link"><a href="http://www.istitutoiglesiasserraperdosa.it"><img src="../../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
		</div>
		<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
	</div>
</body>
</html>
