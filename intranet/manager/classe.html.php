<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript">
$(function(){
	$('table tbody > tr').mouseover(function(event){
		//alert(this.id);
		var strs = this.id.split("_");
		$('#link_'+strs[1]).show();
	});
	$('table tbody > tr').mouseout(function(event){
		//alert(this.id);
		var strs = this.id.split("_");
		$('#link_'+strs[1]).hide();
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
		<?php echo $label ?>
	</div>
	<div class="outline_line_wrapper">
		<div style="width: 5%; float: left; position: relative; top: 25%"></div>
	<?php 
	$max = count($widths);
	for($i = 0; $i < $max; $i++){
	?>
		<div style="width: <?php echo $widths[$i] ?>%; float: left; position: relative; top: 25%"><?php echo $fields[$i] ?></div>
	<?php 
	}
	?>
	</div>
   	<table style="width: 95%; margin: 20px auto 0 auto">
   		<tbody>
   		<?php 
   		reset($widths);
   		reset($fields);
	    reset($data);
   		$x = 1;
   		foreach ($data as $row) {
   			reset($widths);
   		?>
	 	<tr class="docs_row">
	 		<td style="width: 5%; text-align: right; font-weight: bold"><?php if ($_REQUEST['show'] == "alunni") echo $x ?></td>
	 		<td style="width: <?php echo $widths[0] - 5 ?>%; text-align: left; padding-left: 20px"><?php echo $row['nome'] ?></td>
	 		<td style="width: <?php echo $widths[1] ?>%; text-align: center; padding-left: 20px"><?php echo implode(', ', $row['sec_f']) ?></td>
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
