<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Gestione orario di classe</title>
<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
<link href="../../css/general.css" rel="stylesheet" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
var IE = document.all?true:false;

var tempX = 0;
var tempY = 0;

function materia(event){
    //alert("ok");
    document.getElementById('hid').style.display = "none";
    var uid = document.forms[0].id_ora.value;
    var mat = document.forms[0].mat.value;
    var teacher = document.forms[0].teach.value;
    var url = "../../shared/upd_ora.php";
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
			                $(id_ora).update(materia);
			     		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

function visualizza(e) {
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
}

document.observe("dom:loaded", function(){
	$('hid').observe("mouseleave", function(event){
		event.preventDefault();
        $('hid').hide();
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
			<div class="group_head">Orario delle lezioni della classe <?php print $_REQUEST['desc'] ?> - <?php echo $myclass['nome'] ?></div>
        <form method="post" class="no_border">
        <table style="width: 80%; margin-right: auto; margin-left: auto; text-align: center; border-collapse: collapse">
        <tr>
            <td colspan="4" style="padding-bottom: 10px">&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <tr style="font-weight: bold; height: 30px">
        	<td style="width: 7%; border-top: 1px solid rgba(30, 67, 137, .6); border-bottom: 2px solid rgba(30, 67, 137, .6); border-right: 1px solid rgba(30, 67, 137, .6); border-left: 1px solid rgba(30, 67, 137, .6)">Ora</td>
        	<td style="width: 31%; border-top: 1px solid rgba(30, 67, 137, .6); border-bottom: 2px solid rgba(30, 67, 137, .6); border-right: 1px solid rgba(30, 67, 137, .6)">Luned&igrave;</td>
        	<td style="width: 31%; border-top: 1px solid rgba(30, 67, 137, .6); border-bottom: 2px solid rgba(30, 67, 137, .6); border-right: 1px solid rgba(30, 67, 137, .6)">Marted&igrave;</td>
        	<td style="width: 31%; border-top: 1px solid rgba(30, 67, 137, .6); border-bottom: 2px solid rgba(30, 67, 137, .6); border-right: 1px solid rgba(30, 67, 137, .6)">Mercoled&igrave;</td>
        </tr>
        <?php 
        for($i = 0; $i < $ore; $i++){
        	reset($materie);
        ?>
        <tr>
        	<td style="width: 7%; border-bottom: 1px solid rgba(30, 67, 137, .6); border-right: 1px solid rgba(30, 67, 137, .6); border-left: 1px solid rgba(30, 67, 137, .6)"><?php echo $i+1 ?></td>
        	<td style="width: 31%; border-bottom: 1px solid rgba(30, 67, 137, .6); border-right: 1px solid rgba(30, 67, 137, .6);"><a href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(1, $i+1, $classe); if ($d) print $d->getID() ?>; visualizza(event)" id="ora<?php $d = $orario_classe->searchHour(1, $i+1, $classe); if ($d) print $d->getID() ?>"><?php print $materie[$orario_classe->getMateria($classe, 1, $i+1)][0] ?></a></td>
        	<td style="width: 31%; border-bottom: 1px solid rgba(30, 67, 137, .6); border-right: 1px solid rgba(30, 67, 137, .6);"><a href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(2, $i+1, $classe); if ($d) print $d->getID() ?>; visualizza(event)" id="ora<?php $d = $orario_classe->searchHour(2, $i+1, $classe); if ($d) print $d->getID() ?>"><?php print $materie[$orario_classe->getMateria($classe, 2, $i+1)][0] ?></a></td>
        	<td style="width: 31%; border-bottom: 1px solid rgba(30, 67, 137, .6); border-right: 1px solid rgba(30, 67, 137, .6);"><a href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(3, $i+1, $classe); if ($d) print $d->getID() ?>; visualizza(event)" id="ora<?php $d = $orario_classe->searchHour(3, $i+1, $classe); if ($d) print $d->getID() ?>"><?php print $materie[$orario_classe->getMateria($classe, 3, $i+1)][0] ?></a></td>
        </tr>
        <?php 
        }
        ?>
        <tr>
            <td colspan="4">&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <tr style="font-weight: bold; text-align: center; height: 30px">
        	<td style="width: 7%; border-bottom: 2px solid rgba(30, 67, 137, .6); border-right: 1px solid rgba(30, 67, 137, .6); border-left: 1px solid rgba(30, 67, 137, .6); border-top: 1px solid rgba(30, 67, 137, .6)">Ora</td>
        	<td style="width: 31%; border-bottom: 2px solid rgba(30, 67, 137, .6); border-right: 1px solid rgba(30, 67, 137, .6); border-top: 1px solid rgba(30, 67, 137, .6)">Gioved&igrave;</td>
        	<td style="width: 31%; border-bottom: 2px solid rgba(30, 67, 137, .6); border-right: 1px solid rgba(30, 67, 137, .6); border-top: 1px solid rgba(30, 67, 137, .6)">Venerd&igrave;</td>
        	<td style="width: 31%; border-bottom: 2px solid rgba(30, 67, 137, .6); border-right: 1px solid rgba(30, 67, 137, .6); border-top: 1px solid rgba(30, 67, 137, .6)">Sabato</td>
        </tr>
        <?php 
        for($i = 0; $i < $ore; $i++){
        	reset($materie);
        ?>
        <tr>
        	<td style="width: 7%; border-bottom: 1px solid rgba(30, 67, 137, .6); border-left: 1px solid rgba(30, 67, 137, .6); border-right: 1px solid rgba(30, 67, 137, .6)"><?php print $i+1 ?></td>
        	<td style="width: 31%; border-bottom: 1px solid rgba(30, 67, 137, .6); border-right: 1px solid rgba(30, 67, 137, .6);"><a href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(4, $i+1, $classe); if ($d) print $d->getID() ?>; visualizza(event)" id="ora<?php $d = $orario_classe->searchHour(4, $i+1, $classe); if ($d) print $d->getID() ?>"><?php print $materie[$orario_classe->getMateria($classe, 4, $i+1)][0] ?></a></td>
        	<td style="width: 31%; border-bottom: 1px solid rgba(30, 67, 137, .6); border-right: 1px solid rgba(30, 67, 137, .6);"><a href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(5, $i+1, $classe); if ($d) print $d->getID() ?>; visualizza(event)" id="ora<?php $d = $orario_classe->searchHour(5, $i+1, $classe); if ($d) print $d->getID() ?>"><?php print $materie[$orario_classe->getMateria($classe, 5, $i+1)][0] ?></a></td>
        	<td style="width: 31%; border-bottom: 1px solid rgba(30, 67, 137, .6); border-right: 1px solid rgba(30, 67, 137, .6);"><a href="#" onclick="document.forms[0].id_ora.value = <?php $d = $orario_classe->searchHour(6, $i+1, $classe); if ($d) print $d->getID() ?>; visualizza(event)" id="ora<?php $d = $orario_classe->searchHour(6, $i+1, $classe); if ($d) print $d->getID() ?>"><?php print $materie[$orario_classe->getMateria($classe, 6, $i+1)][0] ?></a></td>
        </tr>
        <?php 
        }
        ?>
        <tr>
                <td colspan="4">&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4">&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4" align="right">
                    <a href="classi.php?school_order=<?php echo $myclass['ordine_di_scuola'] ?><?php if($offset != 0) echo "&second=1&offset={$offset}" ?>" class="standard_link">Torna all'elenco classi</a>
                </td>
            </tr>
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
</body>
</html>
