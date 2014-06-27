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
<fieldset style="border: 1px solid; width: 600px; height: 310px; text-align: center; margin-left: auto; margin-right: auto; border-color: #e7d7d7">
<legend style="font-weight: bold; margin-left: 10px; padding-left: 5px; padding-right: 5px; text-align: left"><?php print $legend ?></legend>
	<table style="width: 100%; margin-left: auto; margin-right: auto" cellpadding="0" cellspacing="0">
		<thead>
		<tr style="">
			<td colspan="2" style="border-bottom: 1px solid; border-color: #e7d7d7"></td>
			<td style="width: 25%; font-weight: bold; text-align: center; border-bottom: 1px solid; border-color: #e7d7d7">Maschi</td>
			<td style="width: 25%; font-weight: bold; text-align: center; border-bottom: 1px solid; border-color: #e7d7d7">Femmine</td>
		</tr>
		</thead>
		<?php 
		$male = $female = $all = 0;
		$male_order = $female_order = $all_order = 0;
		$query = "//Class/statistiche[@quadrimestre='".$quadrimestre."']/statistica[@legend='Media voto generale']";
		$entries = $xpath->query($query);
		foreach($entries as $entry){
			$figli = $entry->childNodes;
			if($figli->item(1)->nodeValue == "all"){
				$all = $figli->item(0)->nodeValue;
				$all_order = $figli->item(2)->nodeValue;
			}
			else if ($figli->item(1)->nodeValue == "M"){
				$male = $figli->item(0)->nodeValue;
				$male_order = $figli->item(2)->nodeValue;
			}
			else if($figli->item(1)->nodeValue == "F"){
				$female = $figli->item(0)->nodeValue;
				$female_order = $figli->item(2)->nodeValue;
			}
		}
		?>
		
		<tr>
			<td style="width: 25%; font-weight: bold; text-align: left; padding-left: 25px; border-bottom: 1px solid; border-color: #e7d7d7">Media voto generale</td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><a href="#" style="font-weight: normal; color: #303030" onclick="view_order('all', 'mvg', <?php print $year ?>, '<?php print $quadrimestre ?>', <?php print $argo ?>, <?php print $anno_classe ?>)"><?php print $all." (".$all_order.")" ?></a></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $male." (".$male_order.")" ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $female." (".$female_order.")" ?></td>		
		</tr>
		<thead>
		<tr>
			<td style="width: 25%; font-weight: bold; text-align: left; padding-left: 25px; border-bottom: 1px solid; border-color: #e7d7d7; padding-top: 20px">Media per materia</td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"></td>		
		</tr>
		</thead>
		<?php 
		$male = $female = $all = 0;
		$male_order = $female_order = $all_order = 0;
		$query = "//Class/statistiche[@quadrimestre='".$quadrimestre."']/statistica[@tipo='italiano']";
		$entries = $xpath->query($query);
		foreach($entries as $entry){
			$figli = $entry->childNodes;
			if($figli->item(1)->nodeValue == "all"){
				$all = $figli->item(0)->nodeValue;
				$all_order = $figli->item(2)->nodeValue;
			}
			else if ($figli->item(1)->nodeValue == "M"){
				$male = $figli->item(0)->nodeValue;
				$male_order = $figli->item(2)->nodeValue;
			}
			else if($figli->item(1)->nodeValue == "F"){
				$female = $figli->item(0)->nodeValue;
				$female_order = $figli->item(2)->nodeValue;
			}
		}
		?>
		<tbody>
		<tr>
			<td style="width: 25%; font-weight: bold; text-align: left; padding-left: 25px; border-bottom: 1px solid; border-color: #e7d7d7">Italiano</td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><a href="#" style="font-weight: normal; color: #303030" onclick="view_order('all', 'italiano', <?php print $year ?>, '<?php print $quadrimestre ?>', <?php print $argo ?>, <?php print $anno_classe ?>)"><?php print $all." (".$all_order.")" ?></a></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $male." (".$male_order.")" ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $female." (".$female_order.")" ?></td>		
		</tr>
		<?php 
		$male = $female = $all = 0;
		$male_order = $female_order = $all_order = 0;
		$query = "//Class/statistiche[@quadrimestre='".$quadrimestre."']/statistica[@tipo='inglese']";
		$entries = $xpath->query($query);
		foreach($entries as $entry){
			$figli = $entry->childNodes;
			if($figli->item(1)->nodeValue == "all"){
				$all = $figli->item(0)->nodeValue;
				$all_order = $figli->item(2)->nodeValue;
			}
			else if ($figli->item(1)->nodeValue == "M"){
				$male = $figli->item(0)->nodeValue;
				$male_order = $figli->item(2)->nodeValue;
			}
			else if($figli->item(1)->nodeValue == "F"){
				$female = $figli->item(0)->nodeValue;
				$female_order = $figli->item(2)->nodeValue;
			}
		}
		?>
		<tr>
			<td style="width: 25%; font-weight: bold; text-align: left; padding-left: 25px; border-bottom: 1px solid; border-color: #e7d7d7">Lingua Inglese</td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><a href="#" style="font-weight: normal; color: #303030" onclick="view_order('all', 'inglese', <?php print $year ?>, '<?php print $quadrimestre ?>', <?php print $argo ?>, <?php print $anno_classe ?>)"><?php print $all." (".$all_order.")" ?></a></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $male." (".$male_order.")" ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $female." (".$female_order.")" ?></td>		
		</tr>
		<?php 
		$male = $female = $all = 0;
		$male_order = $female_order = $all_order = 0;
		$query = "//Class/statistiche[@quadrimestre='".$quadrimestre."']/statistica[@tipo='francese']";
		$entries = $xpath->query($query);
		foreach($entries as $entry){
			$figli = $entry->childNodes;
			if($figli->item(1)->nodeValue == "all"){
				$all = $figli->item(0)->nodeValue;
				$all_order = $figli->item(2)->nodeValue;
			}
			else if ($figli->item(1)->nodeValue == "M"){
				$male = $figli->item(0)->nodeValue;
				$male_order = $figli->item(2)->nodeValue;
			}
			else if($figli->item(1)->nodeValue == "F"){
				$female = $figli->item(0)->nodeValue;
				$female_order = $figli->item(2)->nodeValue;
			}
		}
		?>
		<tr>
			<td style="width: 25%; font-weight: bold; text-align: left; padding-left: 25px; border-bottom: 1px solid; border-color: #e7d7d7">Lingua francese</td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><a href="#" style="font-weight: normal; color: #303030" onclick="view_order('all', 'francese', <?php print $year ?>, '<?php print $quadrimestre ?>', <?php print $argo ?>, <?php print $anno_classe ?>)"><?php print $all." (".$all_order.")" ?></a></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $male." (".$male_order.")" ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $female." (".$female_order.")" ?></td>		
		</tr>
		<?php 
		$male = $female = $all = 0;
		$male_order = $female_order = $all_order = 0;
		$query = "//Class/statistiche[@quadrimestre='".$quadrimestre."']/statistica[@tipo='storia']";
		$entries = $xpath->query($query);
		foreach($entries as $entry){
			$figli = $entry->childNodes;
			if($figli->item(1)->nodeValue == "all"){
				$all = $figli->item(0)->nodeValue;
				$all_order = $figli->item(2)->nodeValue;
			}
			else if ($figli->item(1)->nodeValue == "M"){
				$male = $figli->item(0)->nodeValue;
				$male_order = $figli->item(2)->nodeValue;
			}
			else if($figli->item(1)->nodeValue == "F"){
				$female = $figli->item(0)->nodeValue;
				$female_order = $figli->item(2)->nodeValue;
			}
		}
		?>
		<tr>
			<td style="width: 25%; font-weight: bold; text-align: left; padding-left: 25px; border-bottom: 1px solid; border-color: #e7d7d7">Storia</td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><a href="#" style="font-weight: normal; color: #303030" onclick="view_order('all', 'storia', <?php print $year ?>, '<?php print $quadrimestre ?>', <?php print $argo ?>, <?php print $anno_classe ?>)"><?php print $all." (".$all_order.")" ?></a></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $male." (".$male_order.")" ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $female." (".$female_order.")" ?></td>		
		</tr>
		<?php 
		$male = $female = $all = 0;
		$male_order = $female_order = $all_order = 0;
		$query = "//Class/statistiche[@quadrimestre='".$quadrimestre."']/statistica[@tipo='geografia']";
		$entries = $xpath->query($query);
		foreach($entries as $entry){
			$figli = $entry->childNodes;
			if($figli->item(1)->nodeValue == "all"){
				$all = $figli->item(0)->nodeValue;
				$all_order = $figli->item(2)->nodeValue;
			}
			else if ($figli->item(1)->nodeValue == "M"){
				$male = $figli->item(0)->nodeValue;
				$male_order = $figli->item(2)->nodeValue;
			}
			else if($figli->item(1)->nodeValue == "F"){
				$female = $figli->item(0)->nodeValue;
				$female_order = $figli->item(2)->nodeValue;
			}
		}
		?>
		<tr>
			<td style="width: 25%; font-weight: bold; text-align: left; padding-left: 25px; border-bottom: 1px solid; border-color: #e7d7d7">Geografia</td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><a href="#" style="font-weight: normal; color: #303030" onclick="view_order('all', 'geografia', <?php print $year ?>, '<?php print $quadrimestre ?>', <?php print $argo ?>, <?php print $anno_classe ?>)"><?php print $all." (".$all_order.")" ?></a></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $male." (".$male_order.")" ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $female." (".$female_order.")" ?></td>		
		</tr>
		<?php 
		$male = $female = $all = 0;
		$male_order = $female_order = $all_order = 0;
		$query = "//Class/statistiche[@quadrimestre='".$quadrimestre."']/statistica[@tipo='matematica']";
		$entries = $xpath->query($query);
		foreach($entries as $entry){
			$figli = $entry->childNodes;
			if($figli->item(1)->nodeValue == "all"){
				$all = $figli->item(0)->nodeValue;
				$all_order = $figli->item(2)->nodeValue;
			}
			else if ($figli->item(1)->nodeValue == "M"){
				$male = $figli->item(0)->nodeValue;
				$male_order = $figli->item(2)->nodeValue;
			}
			else if($figli->item(1)->nodeValue == "F"){
				$female = $figli->item(0)->nodeValue;
				$female_order = $figli->item(2)->nodeValue;
			}
		}
		?>
		<tr>
			<td style="width: 25%; font-weight: bold; text-align: left; padding-left: 25px; border-bottom: 1px solid; border-color: #e7d7d7">Matematica</td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><a href="#" style="font-weight: normal; color: #303030" onclick="view_order('all', 'matematica', <?php print $year ?>, '<?php print $quadrimestre ?>', <?php print $argo ?>, <?php print $anno_classe ?>)"><?php print $all." (".$all_order.")" ?></a></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $male." (".$male_order.")" ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $female." (".$female_order.")" ?></td>		
		</tr>
		<?php 
		$male = $female = $all = 0;
		$male_order = $female_order = $all_order = 0;
		$query = "//Class/statistiche[@quadrimestre='".$quadrimestre."']/statistica[@tipo='scienze']";
		$entries = $xpath->query($query);
		foreach($entries as $entry){
			$figli = $entry->childNodes;
			if($figli->item(1)->nodeValue == "all"){
				$all = $figli->item(0)->nodeValue;
				$all_order = $figli->item(2)->nodeValue;
			}
			else if ($figli->item(1)->nodeValue == "M"){
				$male = $figli->item(0)->nodeValue;
				$male_order = $figli->item(2)->nodeValue;
			}
			else if($figli->item(1)->nodeValue == "F"){
				$female = $figli->item(0)->nodeValue;
				$female_order = $figli->item(2)->nodeValue;
			}
		}
		?>
		<tr>
			<td style="width: 25%; font-weight: bold; text-align: left; padding-left: 25px; border-bottom: 1px solid; border-color: #e7d7d7">Scienze</td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><a href="#" style="font-weight: normal; color: #303030" onclick="view_order('all', 'scienze', <?php print $year ?>, '<?php print $quadrimestre ?>', <?php print $argo ?>, <?php print $anno_classe ?>)"><?php print $all." (".$all_order.")" ?></a></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $male." (".$male_order.")" ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $female." (".$female_order.")" ?></td>		
		</tr>
		<?php 
		$male = $female = $all = 0;
		$male_order = $female_order = $all_order = 0;
		$query = "//Class/statistiche[@quadrimestre='".$quadrimestre."']/statistica[@tipo='tecnologia']";
		$entries = $xpath->query($query);
		foreach($entries as $entry){
			$figli = $entry->childNodes;
			if($figli->item(1)->nodeValue == "all"){
				$all = $figli->item(0)->nodeValue;
				$all_order = $figli->item(2)->nodeValue;
			}
			else if ($figli->item(1)->nodeValue == "M"){
				$male = $figli->item(0)->nodeValue;
				$male_order = $figli->item(2)->nodeValue;
			}
			else if($figli->item(1)->nodeValue == "F"){
				$female = $figli->item(0)->nodeValue;
				$female_order = $figli->item(2)->nodeValue;
			}
		}
		?>
		<tr>
			<td style="width: 25%; font-weight: bold; text-align: left; padding-left: 25px; border-bottom: 1px solid; border-color: #e7d7d7">Tecnologia</td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><a href="#" style="font-weight: normal; color: #303030" onclick="view_order('all', 'tecnologia', <?php print $year ?>, '<?php print $quadrimestre ?>', <?php print $argo ?>, <?php print $anno_classe ?>)"><?php print $all." (".$all_order.")" ?></a></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $male." (".$male_order.")" ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $female." (".$female_order.")" ?></td>		
		</tr>
		<?php 
		$male = $female = $all = 0;
		$male_order = $female_order = $all_order = 0;
		$query = "//Class/statistiche[@quadrimestre='".$quadrimestre."']/statistica[@tipo='musica']";
		$entries = $xpath->query($query);
		foreach($entries as $entry){
			$figli = $entry->childNodes;
			if($figli->item(1)->nodeValue == "all"){
				$all = $figli->item(0)->nodeValue;
				$all_order = $figli->item(2)->nodeValue;
			}
			else if ($figli->item(1)->nodeValue == "M"){
				$male = $figli->item(0)->nodeValue;
				$male_order = $figli->item(2)->nodeValue;
			}
			else if($figli->item(1)->nodeValue == "F"){
				$female = $figli->item(0)->nodeValue;
				$female_order = $figli->item(2)->nodeValue;
			}
		}
		?>
		<tr>
			<td style="width: 25%; font-weight: bold; text-align: left; padding-left: 25px; border-bottom: 1px solid; border-color: #e7d7d7">Musica</td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><a href="#" style="font-weight: normal; color: #303030" onclick="view_order('all', 'musica', <?php print $year ?>, '<?php print $quadrimestre ?>', <?php print $argo ?>, <?php print $anno_classe ?>)"><?php print $all." (".$all_order.")" ?></a></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $male." (".$male_order.")" ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $female." (".$female_order.")" ?></td>		
		</tr>
		<?php 
		$male = $female = $all = 0;
		$male_order = $female_order = $all_order = 0;
		$query = "//Class/statistiche[@quadrimestre='".$quadrimestre."']/statistica[@tipo='arte_e_immagine']";
		$entries = $xpath->query($query);
		foreach($entries as $entry){
			$figli = $entry->childNodes;
			if($figli->item(1)->nodeValue == "all"){
				$all = $figli->item(0)->nodeValue;
				$all_order = $figli->item(2)->nodeValue;
			}
			else if ($figli->item(1)->nodeValue == "M"){
				$male = $figli->item(0)->nodeValue;
				$male_order = $figli->item(2)->nodeValue;
			}
			else if($figli->item(1)->nodeValue == "F"){
				$female = $figli->item(0)->nodeValue;
				$female_order = $figli->item(2)->nodeValue;
			}
		}
		?>
		<tr>
			<td style="width: 25%; font-weight: bold; text-align: left; padding-left: 25px; border-bottom: 1px solid; border-color: #e7d7d7">Arte e immagine</td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><a href="#" style="font-weight: normal; color: #303030" onclick="view_order('all', 'arte_e_immagine', <?php print $year ?>, '<?php print $quadrimestre ?>', <?php print $argo ?>, <?php print $anno_classe ?>)"><?php print $all." (".$all_order.")" ?></a></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $male." (".$male_order.")" ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $female." (".$female_order.")" ?></td>		
		</tr>
		<?php 
		$male = $female = $all = 0;
		$male_order = $female_order = $all_order = 0;
		$query = "//Class/statistiche[@quadrimestre='".$quadrimestre."']/statistica[@tipo='scienze_motorie']";
		$entries = $xpath->query($query);
		foreach($entries as $entry){
			$figli = $entry->childNodes;
			if($figli->item(1)->nodeValue == "all"){
				$all = $figli->item(0)->nodeValue;
				$all_order = $figli->item(2)->nodeValue;
			}
			else if ($figli->item(1)->nodeValue == "M"){
				$male = $figli->item(0)->nodeValue;
				$male_order = $figli->item(2)->nodeValue;
			}
			else if($figli->item(1)->nodeValue == "F"){
				$female = $figli->item(0)->nodeValue;
				$female_order = $figli->item(2)->nodeValue;
			}
		}
		?>
		<tr>
			<td style="width: 25%; font-weight: bold; text-align: left; padding-left: 25px; border-bottom: 1px solid; border-color: #e7d7d7">Scienze motorie</td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><a href="#" style="font-weight: normal; color: #303030" onclick="view_order('all', 'scienze_motorie', <?php print $year ?>, '<?php print $quadrimestre ?>', <?php print $argo ?>, <?php print $anno_classe ?>)"><?php print $all." (".$all_order.")" ?></a></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $male." (".$male_order.")" ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $female." (".$female_order.")" ?></td>		
		</tr>
		</tbody>
	</table>
</fieldset> 
<form method="post" action="vedi_stat.php">
<div class="splitcontentleft"> 

<p></p>
<input type="hidden" name="quadrimestre" />
<input type="hidden" name="anno_classe" />
<input type="hidden" name="sesso" />
<input type="hidden" name="statistica" />
<input type="hidden" name="anno_rif" />
<input type="hidden" name="classe" />
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