<!DOCTYPE html> 
<html> 
<head> 
<title>Scuola Media E. d'Arborea - Lamarmora </title> 
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" /> 
<meta name="description" content="Il sito della scuola secondaria superiore di Iglesias, in Sardegna" /> 
<meta name="keywords" content="scuola, iglesias, didattica, insegnamento, docenti" /> 
<link rel="stylesheet" href="andreas08.css" type="text/css" media="screen,projection" /> 
<script type="text/javascript" src="../../../js/prototype.js"></script> 
<script src="../../../js/scriptaculous.js" type="text/javascript"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript">
function view_order(sesso, tipo_statistica, anno_scolastico, quadrimestre, classe, anno_classe){
	document.forms[0].sesso.value = sesso;
	document.forms[0].quadrimestre.value = quadrimestre;
	document.forms[0].anno_classe.value = anno_classe;
	document.forms[0].statistica.value = tipo_statistica;
	document.forms[0].anno_rif.value = anno_scolastico;
	document.forms[0].classe.value = classe;
	document.forms[0].submit();
}

function show_all(val){
	if(val == 0){
		$('table_all').style.display = "none";
		$('table_anno').style.display = "table";
		$('legend').innerHTML = "<?php print $legend2 ?>";
	}
	else{
		$('table_all').style.display = "table";
		$('table_anno').style.display = "none";
		$('legend').innerHTML = "<?php print $legend ?>";
	}
}
</script>
<style>
tbody tr:hover { background-color: #f4f8fd }
</style>
</head> 
 
<body> 
<div id="container" > 
 
<div id="header"> 
<h1>Scuola Media E. d'Arborea - Lamarmora</h1> 
<h2>area riservata ai docenti</h2> 
</div> 
<?php include "navigation.php" ?>
<div id="content"> 
<h2 style="text-align: center"><?php print ($_SESSION['__classe__']->to_string()." - ". $_SESSION['__current_year__']->to_string()) ?></h2> 
<fieldset style="border: 1px solid; width: 600px; height: auto; text-align: center; margin-left: auto; margin-right: auto; border-color: #e7d7d7; padding-bottom: 15px">
<legend style="font-weight: bold; margin-left: 10px; padding-left: 5px; padding-right: 5px; text-align: left" id="legend"><?php print $legend ?></legend>
<?php include "../../../shared/order.php" ?>
</fieldset> 
<form method="post" action="vedi_stat.php">
<div class="splitcontentleft"> 

<p></p>
<input type="hidden" name="quadrimestre" value="<?php print $quadrimestre ?>" />
<input type="hidden" name="anno_classe" value="<?php print $anno_classe ?>" />
<input type="hidden" name="sesso" value="<?php print $sesso ?>" />
<input type="hidden" name="statistica" value="<?php print $statistica ?>" />
<input type="hidden" name="anno_rif" value="<?php print $anno_scolastico ?>" />
<input type="hidden" name="classe" value="<?php print $classe ?>" />
</div> 
</form>

</div> 
 
<div id="subcontent"> 
<?php include 'smallbox.php'; ?>
 
<?php include "class_working.php" ?>
</div>
<?php include "footer.php" ?>
 
</div> 
</body> 
</html>