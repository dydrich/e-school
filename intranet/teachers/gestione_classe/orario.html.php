<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
<link rel="stylesheet" href="../reg.css" type="text/css" media="screen,projection" />
<link href="../../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../../js/prototype.js"></script>
<script type="text/javascript" src="../../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript" src="../../../js/window.js"></script>
<script type="text/javascript" src="../../../js/window_effects.js"></script>
<script type="text/javascript">
var IE = document.all?true:false;
if (!IE) document.captureEvents(Event.MOUSEMOVE);

var tempX = 0;
var tempY = 0;

function materia(event){
    //alert("ok");   
    document.getElementById('hid').style.display = "none";
    var uid = document.forms[0].id_ora.value;
    var mat = document.forms[0].mat.value;
    var teacher = document.forms[0].teach.value;
    var url = "../../../shared/upd_ora.php";
    //var data = "uid="+uid+"&mat="+mat+"&teacher="+teacher;
    //alert(data);
    //httpRequest("POST", url, true, modMateria, data);
    var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {uid: uid, mat: mat, teacher: teacher},
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
			      		dati = response.evalJSON();
			      		if(dati.status == "kosql"){
			      			alert("Impossibile completare l'operazione richiesta. Riprovare tra qualche secondo o segnalare l'errore al webmaster");
			      			console.log(dati.dbg_message);
				     		return;
			     		}
			     		else{
			     			var materia = dati.materia;
			                var id_ora = "ora"+dati.id_ora;
			                var x = document.getElementById(id_ora);
			                $(id_ora).update(materia);
			     		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore...") }
			  });
    
}

function visualizza(e) {
	<?php 
    if($_SESSION['__classe__']->getSchoolOrder() == 1 && !$coordinatore){
    ?>
    //do_nothing();
    alert("Modifica permessa solamente al coordinatore della classe");
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
}

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
	<form method="post">
	<div style="width: 90%; height: 30px; margin: 30px auto 0 auto; text-align: center; font-size: 1.1em; text-transform: uppercase">
		Orario delle lezioni [ <a href="pdf_class_schedule.php">PDF</a> ] 
	</div>
	<div style="width: 90%; margin: auto; height: 30px; text-align: center; font-weight: bold; border: 1px solid rgb(211, 222, 199); outline-style: double; outline-color: rgb(211, 222, 199); background-color: rgba(211, 222, 199, 0.7)">
		<div style="width: 7%; float: left; position: relative; top: 30%">Ora</div>
		<div style="width: 31%; float: left; position: relative; top: 30%">Luned&igrave;</div>
		<div style="width: 31%; float: left; position: relative; top: 30%">Marted&igrave;</div>
		<div style="width: 31%; float: left; position: relative; top: 30%">Mercoled&igrave;</div>
	</div>
    <table style="margin: 10px auto 0 auto; text-align: center; font-size: 1em; width: 90%">
        <?php 
        for($i = 0; $i < $ore; $i++){
        	reset($materie);
        ?>
        <tr style="border-bottom: 1px solid #C0C0C0;">
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
	<div style="width: 90%; margin: auto; height: 30px; text-align: center; font-weight: bold; border: 1px solid rgb(211, 222, 199); outline-style: double; outline-color: rgb(211, 222, 199); background-color: rgba(211, 222, 199, 0.7)">
		<div style="width: 7%; float: left; position: relative; top: 30%">Ora</div>
		<div style="width: 31%; float: left; position: relative; top: 30%">Gioved&igrave;</div>
		<div style="width: 31%; float: left; position: relative; top: 30%">Venerd&igrave;</div>
		<div style="width: 31%; float: left; position: relative; top: 30%">Sabato</div>
	</div>       
    <table style="margin: 10px auto 0 auto; text-align: center; font-size: 1em; width: 90%">
        <?php 
        for($i = 0; $i < $ore; $i++){
        	reset($materie);
        ?>
        <tr style="border-bottom: 1px solid #C0C0C0;">
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
