<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
<link rel="stylesheet" href="../../css/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
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
		Elenco docenti <?php print strtolower($_SESSION['__current_year__']->to_string()) ?> (<?php echo $l_ext ." - ".$r_ext ?> <span style="text-transform: lowercase">di</span> <?php echo $_SESSION['count_teac'] ?>)
	</div>
	<div class="outline_line_wrapper">
		<div style="width: 40%; float: left; position: relative; top: 30%">Cognome e nome</div>
		<div style="width: 20%; float: left; position: relative; top: 30%">Materia</div>
		<div style="width: 30%; float: left; position: relative; top: 30%">Classi</div>
		<div style="width: 10%; float: left; position: relative; top: 30%">Titolare</div>
	</div>
   	<table style="width: 95%; margin: 20px auto 0 auto">
	 	    <?php 
	 	    if($res_docenti->num_rows > $limit)
	 	    	$max = $limit;
	 	    else
	 	    	$max = $res_docenti->num_rows;
	 	    $x = 0;
	 	    $bgcolor = "";
	 	    while($docente = $res_docenti->fetch_assoc()){
	 	    	if($x >= $limit) break;
	 	    	
	 	    	if ($docente['id_materia'] != 27 && $docente['id_materia'] != 41){
			        if ($docente['ruolo'] == 'N') {
				        $sel_classes = "SELECT anno_corso, sezione FROM rb_classi, rb_classi_supplenza, rb_supplenze WHERE id_classe = classe AND id_supplente = ".$docente['uid']." AND anno = ".$_SESSION['__current_year__']->get_ID()." ORDER BY rb_classi.sezione, rb_classi.anno_corso";
			        }
			        else {
		 	    	    $sel_classes = "SELECT anno_corso, sezione FROM rb_classi, rb_cdc WHERE rb_classi.id_classe = rb_cdc.id_classe AND rb_cdc.id_docente = ".$docente['uid']." AND id_anno = ".$_SESSION['__current_year__']->get_ID()." ORDER BY rb_classi.sezione, rb_classi.anno_corso";
			        }
		 	    	//print $sel_classes;
		 	    	$res_classes = $db->execute($sel_classes);
		 	    	$classi = array();
		 	    	while($cl = $res_classes->fetch_assoc()){
		 	    		if(!in_array($cl['anno_corso'].$cl['sezione'], $classi))
		 	    			array_push($classi, $cl['anno_corso'].$cl['sezione']);
		 	    	}
		 	    }
		 	    else {
					$sel_classes = "SELECT anno_corso, sezione FROM rb_classi, rb_assegnazione_sostegno WHERE rb_classi.id_classe = rb_assegnazione_sostegno.classe AND rb_assegnazione_sostegno.docente = ".$docente['uid']." AND anno = ".$_SESSION['__current_year__']->get_ID()." ORDER BY rb_classi.sezione, rb_classi.anno_corso";
					//print $sel_classes;
					$res_classes = $db->execute($sel_classes);
					$classi = array();
					while($cl = $res_classes->fetch_assoc()){
						if(!in_array($cl['anno_corso'].$cl['sezione'], $classi))
							array_push($classi, $cl['anno_corso'].$cl['sezione']);
					}
				}
	 	    ?>
	 	    	<tr class="<?php echo $row_class ?>">
	 	    		<td style="width: 40%; text-align: left"><a href="registro_docente.php?doc=<?php echo $docente['uid'] ?>" class="no_dec"><?php print $docente['cognome']." ".$docente['nome'] ?></a></td>
	 	    		<td style="width: 20%; text-align: left"><?php print $docente['materia'] ?></td>
	 	    		<td style="width: 30%; text-align: center"><?php print join(", ", $classi) ?></td>
	 	    		<td style="width: 10%; text-align: center"><?php print $docente['ruolo'] ?></td>
	 	    	</tr>
	 	    
	 	    <?php 
	 	    	$x++;
	 	    }
	 	    include "../../shared/navigate.php";
            ?>
            <tr>
    	<td colspan="4" style="height: 25px"></td> 
    </tr>
	<tr>
		<td colspan="2" style="text-align: right;"></td>
		<td colspan="2" style="text-align: center">
		<div style="margin-left: 30%; width: 70%; height: 20px; border: 1px solid rgba(30, 67, 137, .3);; border-radius: 8px; background-color: rgba(30, 67, 137, .1);">
			<span id="ingresso" style="font-weight: bold; "></span>
			<a href="elenco_docenti.php?order=<?php if($order == "name") print "subject"; else print "name" ?>" style="font-weight: normal; text-decoration: none; text-transform: uppercase; position: relative; top: 15%">Ordina per <?php if($order == "name") print "materia"; else print "nome" ?></a> 
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
