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
var students = <?php print $res_students->num_rows ?>;
var win;
var win2;

var upd_cls = function(id){
	if(!confirm("Sei sicuro di voler togliere l'alunno dalla classe?"))
		return false;
	
	var req = new Ajax.Request('upd_class.php',
			  {
			    	method:'post',
			    	parameters: {std: id, cl: "0"},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
		            	if(dati[0] == "ko"){
							alert("Errore nella cancellazione dell'alunno dalla classe: "+dati[1]+"\n"+dati[2]);
							return false;
		            	}
		            	$('tr'+dati[1]).style.display = "none";
						upd_summary(<?php print $_REQUEST['id_classe'] ?>);
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
		
};

var upd_summary = function(class_id){
	var req = new Ajax.Request('get_class_summary.php',
			  {
			    	method:'post',
			    	parameters: {cl: class_id},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split(";");
		            	if(dati[0] == "ko"){
							//alert("Errore nella cancellazione dell'alunno dalla classe: "+dati[1]+"\n"+dati[2]);
							return false;
		            	}
		            	$('nmb_st').innerHTML = (parseInt(dati[1]) + parseInt(dati[2]));
		            	$('nmb_male').innerHTML = dati[1];
		            	$('nmb_female').innerHTML = dati[2];
		            	$('nmb_rip').innerHTML = dati[3];
		            	$('nmb_h').innerHTML = dati[4]+" / "+dati[5]+" ("+dati[6]+")";
		            	$('avg').innerHTML = dati[7];
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};

var add_student = function(class_id){
	win = new Window({className: "mac_os_x", url: "stud_filter.php", top:100, left:100,  width:400, zIndex: 100, resizable: true, title: "Selezione alunni", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
	win.show(false);
	
	win2 = new Window({className: "mac_os_x", top:100, left: 510, width:400, zIndex: 100, resizable: true, title: "Elenco alunni", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
	win2.getContent().update("<div style='width:100%; font-weight: bold; text-align: center;' id='list_div'><p style='padding-top: 20px; font-weight: bold'>Elenco alunni estratti<a href='#' onclick='_close()' style='float: right; font-size: 12px; padding-right: 25px; font-weight: normal'>Chiudi</a></p></div>");	
};

var update_class = function(id, cl){
	var req = new Ajax.Request('upd_class.php',
			  {
			    	method:'post',
			    	parameters: {std: id, cl: cl},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
		            	if(dati[0] == "ko"){
							alert("Errore nell'aggiornamento della classe: "+dati[1]);
							return false;
		            	}
		            	$('p'+id).style.display = "none";
		            	
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });	
};

var _close = function(){
	document.location.href = "class.php?id_classe=<?php print $_REQUEST['id_classe'] ?>";
	win2.close();
	win.close();
	
};
</script>
<style>
td {border: 0; padding: 4px 2px 4px 4px}
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
            <h3>Classe <?php print $class_desc ?><span style="border-bottom: 1px solid; float: right; font-weight: bold; font-size: 13px">Media generale: <?php print $mv ?></span></h3>
            <form action="" method="post">
	 	    <table style="border-collapse: collapse; width: 100%; margin-top: 10px">
	 	    <thead>
	 	    	<tr>
	 	    		<td colspan="6" style="text-align: center; font-weight: bold; border: 0">Riepilogo</td>
	 	    		<td></td>
	 	    		<td colspan="5" style="text-align: center; font-weight: bold; border: 0">Classi di provenienza</td>
	 	    	</tr>
	 	    	<tr style="font-weight: bold">
					<td style="width: 8%; border-top: 1px solid #cccccc; border-left: 1px solid #cccccc">Alunni: </td>
					<td id="nmb_st" style="width: 8%; border-top: 1px solid #cccccc"><?php print $n_std ?></td>
					<td style="width: 8%; border-top: 1px solid #cccccc">Maschi: </td>
					<td id="nmb_male" style="width: 8%; border-top: 1px solid #cccccc"><?php print $male ?></td>
					<td style="width: 8%; border-top: 1px solid #cccccc">H/DSA: </td>
					<td id="nmb_h" style="width: 8%; border-top: 1px solid #cccccc; border-right: 1px solid #cccccc"><?php print ($h." / ".$dsa." (".($res_h->num_rows).")") ?></td>
					<td rowspan="2" style="width: 2%; text-align: center"></td>
					<?php 
					$index = 0;
					foreach($colors_from as $color){
						if($index > 7)
							break;
						if($index == 0){
					?>
					<td style="width: 5%; text-align: center; border-top: 1px solid #cccccc; border-left: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['class'] ?></td>
					<!-- <td style="width: 2%; text-align: center; border-top: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"></td> -->
					<?php 
						}
						else if($index == 6){
					?>
					<td style="width: 5%; text-align: center; border-top: 1px solid #cccccc; border-right: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['class'] ?></td>
					<!-- <td style="width: 2%; text-align: center; border-top: 1px solid #cccccc; border-right: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"></td> -->
					<?php 
						}
						else{
					?>
					<td style="width: 5%; text-align: center; border-top: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['class'] ?></td>
					<!-- <td style="width: 2%; text-align: center; border-top: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"></td> -->
					<?php 
						}
						$index++;
					}
					?>
						    	
	 	    	</tr>
	 	    	<tr style="font-weight: bold">
					<td style="width: 8%; border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc">Ripetenti: </td>
					<td id="nmb_rip" style="width: 8%; border-bottom: 1px solid #cccccc"><?php print $ripetenti ?></td>
					<td style="width: 8%; border-bottom: 1px solid #cccccc">Femmine: </td>
					<td id="nmb_female" style="width: 8%; border-bottom: 1px solid #cccccc"><?php print $female ?></td>
					<td style="width: 8%; border-bottom: 1px solid #cccccc">Media: </td>
					<td id="avg" style="width: 8%; border-bottom: 1px solid #cccccc; border-right: 1px solid #cccccc"><?php print $avg ?></td>
					<?php 
					$ar = array_slice($colors_from, 8);
					$index = 0;
					foreach($ar as $color){
						if($index > 6)
							break;
						if($index == 0){
					?>
					<td style="width: 5%; text-align: center; border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['class'] ?></td>
					<!-- <td style="width: 2%; text-align: center; border-bottom: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"></td> -->
					<?php 
						}
						else if($index == 6){
					?>
					<td style="width: 5%; text-align: center; border-bottom: 1px solid #cccccc; border-right: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['class'] ?></td>
					<!-- <td style="width: 2%; text-align: center; border-bottom: 1px solid #cccccc; border-right: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"></td> -->
					<?php 
						}
						else{
					?>
					<td style="width: 5%; text-align: center; border-bottom: 1px solid #cccccc; background-color: #<?php print $color['color'] ?>"><?php print $color['class'] ?></td>
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
	 	    		<td colspan="7" style="text-align: right; padding-right: 20px"><a href="class.php?id_classe=<?php print $_REQUEST['id_classe'] ?>" style="float: left">Ordina per cognome</a><span style="float: left">&nbsp;&nbsp;|&nbsp;&nbsp;</span><a href="class.php?id_classe=<?php print $_REQUEST['id_classe'] ?>&order=from" style="float: left">Ordina per provenienza</a><a href="#" onclick="add_student(<?php print $_REQUEST['id_classe'] ?>)">Aggiungi alunno</a></td>
	 	    	</tr>
	 	    	<tr style="font-weight: bold">
					<td style="width: 27%; border-bottom: 1px solid #cccccc">Cognome e nome</td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc">Ripetente</td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc">H / DSA</td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc">Sesso</td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc">Voto</td>
					<td style="width: 30%; text-align: center; border-bottom: 1px solid #cccccc">Note</td>
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
	 	    	<tr id="tr<?php print $st['id_alunno'] ?>" style="<?php if($st['school'] != "5") print("background-color: #".$colors_from[$st['classe_provenienza']]['color']) ?>">
					<td style="width: 27%; border-bottom: 1px solid #cccccc; padding-left: 10px"><?php print $st['name'] ?></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc;"><?php print $ripetente ?></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc;"><?php print $sost ?></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc;"><?php print $st['sesso'] ?></td>
					<td style="width: 10%; text-align: center; border-bottom: 1px solid #cccccc;"><?php print $st['voto'] ?></td>
					<td style="width: 30%; text-align: center; border-bottom: 1px solid #cccccc;"><?php print $st['note'] ?></td>
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
