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
<script type="text/javascript">
document.observe("dom:loaded", function(){
	$$('table tbody > tr').invoke("observe", "mouseover", function(event){
		//alert(this.id);
		var strs = this.id.split("_");
		$('link_'+strs[1]).setStyle({display: 'block'});
	});
	$$('table tbody > tr').invoke("observe", "mouseout", function(event){
		//alert(this.id);
		var strs = this.id.split("_");
		$('link_'+strs[1]).setStyle({display: 'none'});
	});
});
</script>
<style type="text/css">
table tbody tr:hover {
	background-color: rgba(30, 67, 137, .1);
}
</style>
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
		Elenco classi <?php echo $school ?> - <?php print strtolower($_SESSION['__current_year__']->to_string()) ?>
	</div>
	<div class="outline_line_wrapper">
		<div style="width: 15%; float: left; position: relative; top: 30%">Classe</div>
		<div style="width: 45%; float: left; position: relative; top: 30%">&nbsp;</div>
		<div style="width: 30%; float: left; position: relative; top: 30%">Sede</div>
		<div style="width: 10%; float: left; position: relative; top: 30%">Alunni</div>
	</div>
   	<table style="width: 95%; margin: 20px auto 0 auto">
   			<tbody>
	 	    <?php 
	 	    if($res_cls->num_rows > $limit)
	 	    	$max = $limit;
	 	    else
	 	    	$max = $res_cls->num_rows;
	 	    $x = 0;
	 	    $bgcolor = "";
	 	    while($cls = $res_cls->fetch_assoc()){
	 	    	if($x >= $limit) break;
	 	    ?>
 	    	<tr class="<?php echo $row_class ?>" id="row_<?php echo $cls['id_classe'] ?>">
 	    		<td style="width: 15%; text-align: center"><?php print $cls['anno_corso'].$cls['sezione'] ?><span style="margin-left: 8px"><?php if (!$_SESSION['__school_order__']) echo $cls['tipo'] ?></span></td>
 	    		<td style="width: 45%; text-align: left">
 	    		<div id="link_<?php echo $cls['id_classe'] ?>" style="display: none; vertical-align: bottom">
                	<a href="classe.php?id=<?php echo $cls['id_classe'] ?>&show=cdc&desc=<?php print $cls['anno_corso']."".$cls['sezione'] ?>" class="cdc_link">Consiglio di classe</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="classe.php?id=<?php echo $cls['id_classe'] ?>&tp=<?php print $cls['tempo_prolungato'] ?>&desc=<?php print $cls['anno_corso']."".$cls['sezione'] ?>&show=orario">Orario</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="classe.php?id=<?php echo $cls['id_classe'] ?>&show=alunni&desc=<?php print $cls['anno_corso']."".$cls['sezione'] ?>">Alunni</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="medie_classe.php?cls=<?php echo $cls['id_classe'] ?>">Voti</a>
                	</div>
 	    		</td>
 	    		<td style="width: 30%; text-align: center"><?php print $cls['nome'] ?></td>
 	    		<td style="width: 10%; text-align: center"><?php print $cls['num_alunni'] ?></td>
 	    	</tr>
	 	    
	 	    <?php 
	 	    	$x++;
	 	    }
	 	    ?>
	 	    </tbody>
	 	    <tfoot>
	 	    <?php 
	 	    include "../../shared/navigate.php";
            ?>
            <tr>
    	<td colspan="4" style="height: 25px"></td> 
    </tr>
	<tr>
		<td colspan="2" style="text-align: right;"></td>
		<td colspan="2" style="text-align: center">
		<div style="margin-left: 30%; width: 70%; height: 20px; border: 1px solid rgb(211, 222, 199); border-radius: 8px; background-color: rgba(211, 222, 199, 0.4)">
			<span id="ingresso" style="font-weight: bold; "></span>
			<a href="elenco_classi.php?order=<?php if($order == "sez") print "year"; else print "sez" ?>" style="font-weight: normal; text-decoration: none; text-transform: uppercase; position: relative; top: 15%">Ordina per <?php if($order == "sez") print "anno corso"; else print "sezione" ?></a> 
		</div>
		</td>
	</tr>
	</tfoot>
	</table>		
	</div>
<p class="spacer"></p>		
</div>
<?php include "footer.php" ?>	
</body>
</html>
