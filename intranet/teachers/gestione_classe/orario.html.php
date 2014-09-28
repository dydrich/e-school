<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript">
var IE = document.all?true:false;

var tempX = 0;
var tempY = 0;

$(function(){
	load_jalert();
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
				$('#ora'+json.id_ora).text(materia);
			}
		}
	});
};

var visualizza = function(e) {
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
    var hid = document.getElementById("hid");
    //alert(hid.style.top);
    if (IE) { // grab the x-y pos.s if browser is IE
        tempX = event.clientX + document.body.scrollLeft;
        tempY = event.clientY + document.body.scrollTop;
    } else {  // grab the x-y pos.s if browser is NS
        tempX = e.pageX;
        tempY = e.pageY;
    }  
    // catch possible negative values in NS4
    if (tempX < 0){tempX = 0;}
    if (tempY < 0){tempY = 0;}  
    hid.style.top = parseInt(tempY)+"px";
    //alert(hid.style.top);
    hid.style.left = parseInt(tempX)+"px";
    hid.style.display = "inline";
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
	<div id="hid" style="position: absolute; width: 220px; height: 290px; display: none; ">
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
	<div class="group_head">
		Orario delle lezioni [ <a href="pdf_class_schedule.php">PDF</a> ] 
	</div>
	<div class="outline_line_wrapper">
		<div style="width: 9%; float: left; position: relative; top: 25%"><span style="padding-left: 45px">Ora</span></div>
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
        	<td style="width: 31%; "><a style="font-weight: normal" href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(1, $i+1, $classe); if($d != null) echo $d->getID() ?>; visualizza(event)" id="ora<?php $d = $orario_classe->searchHour(1, $i+1, $classe); if($d != null) echo $d->getID() ?>"><?php if (isset($materie[$orario_classe->getMateria($classe, 1, $i+1)])) echo $materie[$orario_classe->getMateria($classe, 1, $i+1)][0] ?></a></td>
        	<td style="width: 31%; "><a style="font-weight: normal" href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(2, $i+1, $classe); if($d != null) echo $d->getID() ?>; visualizza(event)" id="ora<?php $d = $orario_classe->searchHour(2, $i+1, $classe); if($d != null) echo $d->getID() ?>"><?php if (isset($materie[$orario_classe->getMateria($classe, 2, $i+1)])) echo $materie[$orario_classe->getMateria($classe, 2, $i+1)][0] ?></a></td>
        	<td style="width: 31%; "><a style="font-weight: normal" href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(3, $i+1, $classe); if($d != null) echo $d->getID() ?>; visualizza(event)" id="ora<?php $d = $orario_classe->searchHour(3, $i+1, $classe); if($d != null) echo $d->getID() ?>"><?php if (isset($materie[$orario_classe->getMateria($classe, 3, $i+1)])) echo $materie[$orario_classe->getMateria($classe, 3, $i+1)][0] ?></a></td>
        </tr>
        <?php 
        }
        ?>
        <tr>
            <td colspan="4" style="height: 40px"></td>
        </tr>
    </table>
	<div class="outline_line_wrapper">
		<div style="width: 9%; float: left; position: relative; top: 25%"><span style="padding-left: 45px">Ora</span></div>
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
        	<td style="width: 31%; "><a style="font-weight: normal" href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(4, $i+1, $classe); if($d != null) echo $d->getID() ?>; visualizza(event)" id="ora<?php $d = $orario_classe->searchHour(4, $i+1, $classe); if($d != null) echo $d->getID() ?>"><?php if (isset($materie[$orario_classe->getMateria($classe, 4, $i+1)])) echo $materie[$orario_classe->getMateria($classe, 4, $i+1)][0] ?></a></td>
        	<td style="width: 31%; "><a style="font-weight: normal" href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(5, $i+1, $classe); if($d != null) echo $d->getID() ?>; visualizza(event)" id="ora<?php $d = $orario_classe->searchHour(5, $i+1, $classe); if($d != null) echo $d->getID() ?>"><?php if (isset($materie[$orario_classe->getMateria($classe, 5, $i+1)])) echo $materie[$orario_classe->getMateria($classe, 5, $i+1)][0] ?></a></td>
        	<td style="width: 31%; "><a style="font-weight: normal" href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(6, $i+1, $classe); if($d != null) echo $d->getID() ?>; visualizza(event)" id="ora<?php $d = $orario_classe->searchHour(6, $i+1, $classe); if($d != null) echo $d->getID() ?>"><?php if (isset($materie[$orario_classe->getMateria($classe, 6, $i+1)])) echo $materie[$orario_classe->getMateria($classe, 6, $i+1)][0] ?></a></td>
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
</body>
</html>
