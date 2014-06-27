<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Area dirigenza::gestione nuove classi</title>
<link rel="stylesheet" href="../styles.css" type="text/css" />
<link href="/css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="/css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="/js/page.js"></script>
<?php include $_ENV["DOCUMENT_ROOT"]."/js/prototype.php" ?>
<script type="text/javascript" src="/js/window.js"></script>
<script type="text/javascript" src="/js/window_effects.js"></script>
<script type="text/javascript">
var win;
var upd_grade = function(sel, stid){
	var grade = sel.value;
	var req = new Ajax.Request('upd_grade.php',
			  {
			    	method:'post',
			    	parameters: {stid: stid, grade: grade, cl: <?php print $_REQUEST['class_id'] ?>},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
		            	if(dati[0] == "ko"){
							alert("Errore nell'aggiornamento del voto: "+dati[1]);
							return false;
		            	}
		            	$('avg').innerHTML = dati[1];            	
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};

var mod_class = function(){
	win = new Window({className: "mac_os_x", width:200, height:null, zIndex: 100, resizable: true, title: "Modifica classe", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
	win.getContent().update("<table style='width: 95%; margin: auto; padding-top: 20px;'><tr><td style='width: 40%; font-weight: bold'>Classe</td><td style='width: 60%'><input type='text' style='width: 90%; border: 1px solid #dddddd; font-size: 11px' name='nome' id='nome' value='<?php print $sc['descrizione'] ?>' /></tr><tr><td colspan='2' style='padding-top: 20px; text-align: right; padding-right: 5%'><a href='#' onclick='_upd_class(1)'>Salva</a></td></tr></table>");
	win.showCenter(false);
};

var _upd_class = function(action){
	if(action == 3){
		if(!confirm("Sei sicuro di voler cancellare la classe? Dovrai poi assegnare gli studenti ad un'altra classe."))
			return false;
		name = "";
	}
	else{
		name = $('nome').value;
	}
	var cl = <?php print(isset($_REQUEST['class_id']) ? $_REQUEST['class_id'] : 0) ?>;
	var req = new Ajax.Request('manage_classes_from.php',
			  {
			    	method:'post',
			    	parameters: {action: action, class_id: cl, class_name: name},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("#");
		            	if(dati[0] == "ko"){
							alert("Errore nell'aggiornamento: "+dati[1]);
							return false;
		            	}
		            	if(action == 1){
		            		$('cls_d').innerHTML = name;
		            		win.close();
		            	}
		            	else if(action == 3){
							document.location.href = "schools.php";
		            	}      	
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};
</script>
<style>
td {border: 0}
</style>
</head>
<body>
<div class="pagewidth">
	<div class="header">
		<!-- TITLE -->
		<h1><a href="htp://www.scuolamediatre.it">Scuola Media Statale Iglesias</a></h1>
		<h2>Area riservata::dirigenza</h2>
		<!-- END TITLE -->
	</div>
	<?php include "navbar.php" ?>
	<div class="page-wrap">
		<div class="content">	
			<!-- CONTENT -->
            <h3><?php print $sc['sc'] ?>:: classe <span id="cls_d"><?php print $sc['descrizione'] ?></span><span style="float: right; margin-right: 10%; font-size: 13px"><a href="#" onclick="mod_class()">Modifica classe</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" onclick="_upd_class(3)">Cancella classe</a></span></h3>
            <form action="" method="post">
	 	    <table style="border-collapse: collapse; width: 100%; margin-top: 10px">
	 	    <thead>
	 	    	<tr>
	 	    		<td colspan="4" style="text-align: center; font-weight: bold; border: 0">Riepilogo</td>
	 	    		<td></td>
	 	    		<td colspan="5" style="text-align: center; font-weight: bold; border: 0">Classi assegnate</td>
	 	    	</tr>
	 	    	<tr style="font-weight: bold">
					<td style="width: 8%; border-top: 1px solid #cccccc; border-left: 1px solid #cccccc">Alunni: </td>
					<td id="nmb_st" style="width: 8%; border-top: 1px solid #cccccc"><?php print $n_std ?></td>
					<td style="width: 8%; border-top: 1px solid #cccccc">Maschi: </td>
					<td id="nmb_male" style="width: 8%; border-top: 1px solid #cccccc; border-right: 1px solid #cccccc"><?php print $male ?></td>
					<td rowspan="2" style="width: 2%; text-align: center"></td>
					<?php 
					$index = 0;
					foreach($classes_and_colors as $color){
						if($index > 5)
							break;
						if($index == 0){
					?>
					<td style="width: 8%; text-align: center; border-top: 1px solid #cccccc; border-left: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['name'] ?></td>
					<!-- <td style="width: 2%; text-align: center; border-top: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"></td> -->
					<?php 
						}
						else if($index == 5){
					?>
					<td style="width: 8%; text-align: center; border-top: 1px solid #cccccc; border-right: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['name'] ?></td>
					<!-- <td style="width: 2%; text-align: center; border-top: 1px solid #cccccc; border-right: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"></td> -->
					<?php 
						}
						else{
					?>
					<td style="width: 8%; text-align: center; border-top: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['name'] ?></td>
					<!-- <td style="width: 2%; text-align: center; border-top: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"></td> -->
					<?php 
						}
						$index++;
					}
					?>
						    	
	 	    	</tr>
	 	    	<tr style="font-weight: bold">
					<td style="width: 8%; border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc">Media: </td>
					<td id="nmb_female" style="width: 8%; border-bottom: 1px solid #cccccc"><?php print $avg ?></td>
					<td style="width: 8%; border-bottom: 1px solid #cccccc">Femmine: </td>
					<td id="avg" style="width: 8%; border-bottom: 1px solid #cccccc; border-right: 1px solid #cccccc"><?php print $female ?></td>
					<?php 
					$ar = array_slice($classes_and_colors, 6);
					$index = 0;
					foreach($ar as $color){
						if($index > 5)
							break;
						if($index == 0){
					?>
					<td style="width: 8%; text-align: center; border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['name'] ?></td>
					<!-- <td style="width: 2%; text-align: center; border-bottom: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"></td> -->
					<?php 
						}
						else if($index == 5){
					?>
					<td style="width: 8%; text-align: center; border-bottom: 1px solid #cccccc; border-right: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['name'] ?></td>
					<!-- <td style="width: 2%; text-align: center; border-bottom: 1px solid #cccccc; border-right: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"></td> -->
					<?php 
						}
						else{
					?>
					<td style="width: 8%; text-align: center; border-bottom: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['name'] ?></td>
					<!-- <td style="width: 2%; text-align: center; border-bottom: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"></td> -->
					<?php 
						}
						$index++;
					}
					?>  	
	 	    	</tr>
			</thead>
	 	    </table>
	 	    <p></p>
	 	    <table style="border-collapse: collapse; width: 100%; margin-top: 30px">
	 	    	<thead>
	 	    	<tr>
	 	    		<td colspan="6" style="text-align: right; padding-right: 20px"><a href="class_from.php?class_id=<?php print $_REQUEST['class_id'] ?>" style="float: left">Ordina per cognome</a><span style="float: left">&nbsp;&nbsp;|&nbsp;&nbsp;</span><a href="class_from.php?class_id=<?php print $_REQUEST['class_id'] ?>&order=cls" style="float: left">Ordina per classe</a></td>
	 	    	</tr>
	 	    	<tr style="font-weight: bold">
					<td style="width: 32%; border-bottom: 1px solid #cccccc">Cognome e nome</td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc">H / DSA</td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc">Sesso</td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc">Voto</td>
					<td style="width: 35%; text-align: center; border-bottom: 1px solid #cccccc">Note</td>
					<td style="width: 3%; text-align: center; border-bottom: 1px solid #cccccc"></td>	 	    	
	 	    	</tr>
	 	    	</thead>
	 	    	<tbody>
	 	    	<?php
	 	    	while($st = $res_students->fetch_assoc()) {
	 	    		$ripetente = ($st['ripetente'] == 1) ? "SI" : "NO";
	 	    		$h = $dsa = $sost = "";
	 	    		if($st['H'] != 0){
	 	    			if($st['H'] < 4)
	 	    				$dsa = "DSA";
	 	    			if($st['H'] == 2 || $st['H'] == 4)
	 	    				$h = "H";
	 	    			else if($st['H'] == 3 || $st['H'] == 5)
	 	    				$h = "<span style='color: red; font-weight: bold'>H</span>";
	 	    		}
	 	    		if($h != ""){
	 	    			$sost = $h;
	 	    			if($dsa != ""){
	 	    				if($h != "")
	 	    					$sost .= " / $dsa";
	 	    			}
	 	    		}
	 	    		else if($dsa != "")
	 	    			$sost = $dsa;
	 	    	?>
	 	    	<tr id="tr<?php print $st['id_alunno'] ?>" style="<?php if($st['school'] != "5") print("background-color: #".$classes_and_colors[$st['id_classe']]['color']) ?>">
					<td style="width: 32%; border-bottom: 1px solid #cccccc;"><?php print $st['name'] ?></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc;"><?php print $sost ?></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc;"><?php print $st['sesso'] ?></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc;">
						<select name="grade" id="grade" style="width: 40px; font-size: 11px; border: 1px solid #dddddd" onchange="upd_grade(this, <?php print $st['id_alunno'] ?>)">
							<option value="0">.</option>
							<?php 
							for($i = 4; $i < 11; $i++){
							?>
								<option  <?php if($st['voto'] == $i) print "selected='selected'" ?> value="<?php print $i ?>"><?php print $i ?></option>
							<?php } ?>
							</select>
					</td>
					<td style="width: 35%; text-align: center; border-bottom: 1px solid #cccccc;"><?php print $st['note'] ?></td>
					<td style="width: 3%; font-weight: bold; text-align: center; border-bottom: 1px solid #cccccc;"><a style="color: red; font-weight: bold" href="#" onclick="upd_cls(<?php print $st['id_alunno'] ?>)">x</a></td>	 	    	
	 	    	</tr>
	 	    	<?php } ?>
	 	    	</tbody>
	 	    	<tfoot>
	 	    	</tfoot>
	 	    </table>
			<!-- END CONTENT -->
			</form>	
		</div>
		<div class="sidebar">	
			<?php include 'menu.php'; ?>
		</div>
		<div class="clear"></div>		
	</div>
    <?php include "../footer.php" ?>	
</div>
</body>
</html>
