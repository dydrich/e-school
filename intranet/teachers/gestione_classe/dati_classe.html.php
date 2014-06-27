<!DOCTYPE html> 
<html> 
<head> 
<title>Scuola Media E. d'Arborea - Lamarmora </title> 
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" /> 
<meta name="description" content="Il sito della scuola secondaria superiore di Iglesias, in Sardegna" /> 
<meta name="keywords" content="scuola, iglesias, didattica, insegnamento, docenti" /> 
<link rel="stylesheet" href="andreas08.css" type="text/css" media="screen,projection" /> 
<?php include $_ENV["DOCUMENT_ROOT"]."/js/prototype.php" ?>
<script type="text/javascript" src="/js/page.js"></script>
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
<fieldset style="border: 1px solid; border-color: #e7d7d7; width: 500px; height: 110px; text-align: center; margin-left: auto; margin-right: auto; ">
<legend style="font-weight: bold; margin-left: 10px; padding-left: 5px; padding-right: 5px; text-align: left">Dati generali</legend>
	<table style="width: 90%; margin-left: auto; margin-right: auto" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<td colspan="2" style="border-bottom: 1px solid; border-color: #e7d7d7"></td>
			<td style="width: 25%; font-weight: bold; text-align: center; border-bottom: 1px solid; border-color: #e7d7d7">Maschi</td>
			<td style="width: 25%; font-weight: bold; text-align: center; border-bottom: 1px solid; border-color: #e7d7d7">Femmine</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td style="width: 25%; font-weight: bold; text-align: left; padding-left: 25px; border-bottom: 1px solid; border-color: #e7d7d7">Alunni</td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $tot ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $tot_m ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $tot_f ?></td>		
		</tr>
		<tr>
			<td style="width: 25%; font-weight: bold; text-align: left; padding-left: 25px;border-bottom: 1px solid; border-color: #e7d7d7">Ripetenti</td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $ripetenti['T'] ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $ripetenti['M'] ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $ripetenti['F'] ?></td>		
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4" style="text-align: right; height: 40px"><a href="elenco_alunni.php" style="margin-right: 10px; color: #171743">Visualizza il dettaglio</a></td>
		</tr>
	</tfoot>
	</table>
</fieldset> 
<br />
<fieldset style="border: 1px solid; width: 500px; height: 180px; text-align: center; margin-left: auto; margin-right: auto; border-color: #e7d7d7">
<legend style="font-weight: bold; margin-left: 10px; padding-left: 5px; padding-right: 5px; text-align: left">Statistiche: <?php print $legend ?></legend>
	<table style="width: 90%; margin-left: auto; margin-right: auto" cellpadding="0" cellspacing="0">
	<thead>
		<tr style="">
			<td colspan="2" style="border-bottom: 1px solid; border-color: #e7d7d7"></td>
			<td style="width: 25%; font-weight: bold; text-align: center; border-bottom: 1px solid; border-color: #e7d7d7">Maschi</td>
			<td style="width: 25%; font-weight: bold; text-align: center; border-bottom: 1px solid; border-color: #e7d7d7">Femmine</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td style="width: 25%; font-weight: bold; text-align: left; padding-left: 25px; border-bottom: 1px solid; border-color: #e7d7d7">Alunni</td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $old_tot ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $old_tot_m ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $old_tot_f ?></td>		
		</tr>
		<tr>
			<td style="width: 25%; font-weight: bold; text-align: left; padding-left: 25px; border-bottom: 1px solid; border-color: #e7d7d7"><?php print $label_positive ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $ammessi ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $ammessi_m ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $ammessi_f ?></td>		
		</tr>
		<tr>
			<td style="width: 25%; font-weight: bold; text-align: left; padding-left: 25px; border-bottom: 1px solid; border-color: #e7d7d7">Non validati</td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $non_val ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $non_val_m ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $non_val_f ?></td>		
		</tr>
		<tr>
			<td style="width: 25%; font-weight: bold; text-align: left; padding-left: 25px; border-bottom: 1px solid; border-color: #e7d7d7"><?php print $label ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $non_amm ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $non_amm_m ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $non_amm_f ?></td>		
		</tr>
		<tr>
			<td style="width: 25%; font-weight: bold; text-align: left; padding-left: 25px; border-bottom: 1px solid; border-color: #e7d7d7">Ins. ammessi</td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $non_suff ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $non_suff_m ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $non_suff_f ?></td>		
		</tr>
		<tr>
			<td style="width: 25%; font-weight: bold; text-align: left; padding-left: 25px; border-bottom: 1px solid; border-color: #e7d7d7">Media voto</td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $media ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $media_m ?></td>
			<td style="width: 25%; font-weight: normal; text-align: center;border-bottom: 1px solid; border-color: #e7d7d7"><?php print $media_f ?></td>		
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4" style="text-align: right; height: 40px"><a href="statistiche.php?tab=<?php print $dt['table_name'] ?>" style="margin-right: 10px; color: #171743">Visualizza il dettaglio</a></td>
		</tr>
	</tfoot>
	</table>
</fieldset> 
 
<div class="splitcontentleft"> 

<p></p>
 
</div> 
 
 
</div> 
 
<div id="subcontent"> 
<?php include 'smallbox.php'; ?> 
 
<?php include "class_working.php" ?>
</div>
<?php include "footer.php" ?>
 
</div> 
</body> 
</html>