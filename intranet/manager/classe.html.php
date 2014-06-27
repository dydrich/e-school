<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
<link rel="stylesheet" href="../teachers/reg.css" type="text/css" media="screen,projection" />
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
	background-color: rgb(211, 222, 199);
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
	<div style="width: 95%; height: 30px; margin: 10px auto 0 auto; text-align: center; font-size: 1.1em; text-transform: uppercase">
		<?php echo $label ?>
	</div>
	<div style="width: 95%; margin: auto; height: 25px; text-align: center; text-transform: uppercase; font-weight: bold; border: 1px solid rgb(211, 222, 199); outline-style: double; outline-color: rgb(211, 222, 199); background-color: rgba(211, 222, 199, 0.7)">
	<?php 
	$max = count($widths);
	for($i = 0; $i < $max; $i++){
	?>
		<div style="width: <?php echo $widths[$i] ?>%; float: left; position: relative; top: 30%"><?php echo $fields[$i] ?></div>
	<?php 
	}
	?>
	</div>
   	<table style="width: 95%; margin: 20px auto 0 auto">
   		<tbody>
   		<?php 
   		reset($widths);
   		reset($fields);
   		$x = 1;
   		while($row = $result->fetch_assoc()){
   			reset($widths);
   		?>
	 	<tr class="docs_row">
	 		<td style="width: 5%; text-align: right; font-weight: bold"><?php if ($_REQUEST['show'] == "alunni") echo $x ?></td>
	 		<td style="width: <?php echo $widths[0] - 5 ?>%; text-align: left; padding-left: 20px"><?php echo $row['cognome']." ".$row['nome'] ?></td>
	 		<td style="width: <?php echo $widths[1] ?>%; text-align: center; padding-left: 20px"><?php echo $row['sec_f'] ?></td>
 	    </tr>
 	    <?php
 	    	$x++;
   		}
 	    ?>
 	    </tbody>
 	    <tfoot>
	 	<tr>
    		<td colspan="3" style="height: 25px"></td> 
    	</tr>
		<tr>
			<td colspan="3" style="height: 40px; text-align: right"><a href="elenco_classi.php" style="text-transform: uppercase"><img src="../../images/back.png" style="margin-right: 8px; position: relative; top: 5px" />Torna all'elenco classi</a></td>
		</tr>
		</tfoot>
	</table>		
	</div>
<p class="spacer"></p>		
</div>
<?php include "footer.php" ?>	
</body>
</html>
