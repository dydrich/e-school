<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
<link rel="stylesheet" href="../../css/reg.css" type="text/css" media="screen,projection" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
</head>
<body>
<?php include "header.php" ?>
<?php include $_SESSION['__administration_group__']."/navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include $_SESSION['__administration_group__']."/menu.php" ?>
</div>
<div id="left_col">
	<div class="group_head">
		Elenco alunni <?php print strtolower($_SESSION['__current_year__']->to_string()) ?> (<?php echo $l_ext ." - ".$r_ext ?> <span style="text-transform: lowercase">di</span> <?php echo $_SESSION['count_teac'] ?>)
	</div>
	<div class="outline_line_wrapper">
		<div style="width: 55%; float: left; position: relative; top: 30%">Cognome e nome</div>
		<div style="width: 30%; float: left; position: relative; top: 30%">Classe</div>
		<div style="width: 15%; float: left; position: relative; top: 30%">Ripetente</div>
	</div>
   	<table style="width: 95%; margin: 20px auto 0 auto">
	 	    <?php 
	 	    if($res_alunni->num_rows > $limit)
	 	    	$max = $limit;
	 	    else
	 	    	$max = $res_alunni->num_rows;
	 	    $x = 0;
	 	    $bgcolor = "";
	 	    while($alunno = $res_alunni->fetch_assoc()){
	 	    	if($x >= $limit) break;
	 	    ?>
	 	    	<tr class="<?php echo $row_class ?>">
	 	    		<td style="width: 55%; text-align: left"><?php print $alunno['cognome']." ".$alunno['nome'] ?></td>
	 	    		<td style="width: 30%; text-align: center"><?php print $alunno['anno_corso'].$alunno['sezione'] ?></td>
	 	    		<td style="width: 15%; text-align: center"><?php print ($alunno['ripetente'] ? "SI" : "NO") ?></td>
	 	    	</tr>
	 	    
	 	    <?php 
	 	    	$x++;
	 	    }
	 	    include "../../shared/navigate.php";
            ?>
            <tr>
    	<td colspan="3" style="height: 25px"></td> 
    </tr>
	<tr>
		<td style="text-align: right;"></td>
		<td colspan="2" style="text-align: center">
		<div style="margin-left: 30%; width: 70%; height: 20px; border: 1px solid rgba(30, 67, 137, .3); border-radius: 8px; background-color: rgba(30, 67, 137, .1)">
			<span id="ingresso" style="font-weight: bold; "></span>
			<a href="elenco_alunni.php?order=<?php if($order == "name") print "class"; else print "name" ?>" style="font-weight: normal; text-decoration: none; text-transform: uppercase; position: relative; top: 15%">Ordina per <?php if($order == "name") print "classe"; else print "nome" ?></a> 
		</div>
		</td>
	</tr>
	</table>		
	</div>
<p class="spacer"></p>		
</div>
<?php include "footer.php" ?>	
</body>
</html>
