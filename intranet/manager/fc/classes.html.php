<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Area dirigenza::gestione nuove classi</title>
<link rel="stylesheet" href="../styles.css" type="text/css" />
<script type="text/javascript" src="/js/page.js"></script>
<?php include $_ENV["DOCUMENT_ROOT"]."/js/prototype.php" ?>
<script type="text/javascript">
var _classes = function(){
	var _cls = prompt("Inserisci le sezioni che vuoi creare, separate da una virgola");
	if(trim(_cls) == ""){
		alert("Non hai inserito nessuna sezione");
		return false;
	}
	document.forms[0].cls.value = _cls;
	document.forms[0].submit();
};

var del_cls = function(id){
	if(!confirm("Sei sicuro di voler cancellare questa classe?"))
		return false;
	
	var req = new Ajax.Request('del_cls.php',
			  {
			    	method:'post',
			    	parameters: {id: id},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split(";");
		            	if(dati[0] == "ko"){
							alert("Errore nella cancellazione della classe: "+dati[1]);
							return false;
		            	}
		            	$('tr'+dati[1]).style.display = "none";
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
		
};
</script>
<style>
tbody tr:hover { background-color: #D5C5AC }
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
            <h3>Classi prime<span style="border-bottom: 1px solid; float: right; font-weight: bold; font-size: 13px; margin-right: 5%">Media generale: <?php print $mv ?></span></h3>
            <form action="classes.php?update=1" method="post">
	 	    <?php if($n_cls < 1){ ?>
	 	    <p style="margin-top: 20px; margin-bottom: 50px; font-weight: bold">Non hai ancora inserito nessuna classe.</p>
	 	    <div style="width: 90%; text-align: right"><input style="padding: 5px" name="add_cls" id="_classes()" type="button" value="Inserisci classi" onclick="_classes()" class="button" />
			<input type="hidden" name="cls" id="cls" /> 	    
	 	    </div>
	 	    <?php } 
			else{	 	    
	 	    ?>	
	 	    <table style="border-collapse: collapse; width: 95%; margin-top: 30px">
	 	    	<thead>
	 	    	<tr style="font-weight: bold">
					<td style="width: 13%; border-bottom: 1px solid #cccccc">Classe</td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc">Alunni</td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc">Ripetenti</td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc">Maschi</td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc">Femmine</td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc">H / DSA</td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc">Media</td>
					<td style="width: 9%; text-align: center; border-bottom: 1px solid #cccccc">Elimina</td>	 	    	
	 	    	</tr>
	 	    	</thead>
	 	    	<tbody>
	 	    	<?php
	 	    	while($cl = $res_classes->fetch_assoc()) {
	 	    		$sel_sex = "SELECT sesso, COUNT(sesso) AS count FROM fc_alunni WHERE id_classe = ".$cl['id']." GROUP BY sesso";
	 	    		$res_sex = $db->executeQuery($sel_sex);
	 	    		$male = $female = 0;
	 	    		while($sx = $res_sex->fetch_assoc()){
						if($sx['sesso'] == 'M')
							$male = $sx['count'];
						else
							$female = $sx['count'];
	 	    		}
	 	    		$sel_rip = "SELECT COUNT(id_alunno) FROM fc_alunni WHERE id_classe = ".$cl['id']." AND ripetente = 1";
	 	    		$ripetenti = $db->executeCount($sel_rip); 
	 	    		
	 	    		$sel_h = "SELECT H FROM fc_alunni WHERE id_classe = ".$cl['id']." AND H IS NOT NULL AND H <> 0";
	 	    		$res_h = $db->executeQuery($sel_h);
	 	    		$h = $dsa = 0;
	 	    		while($al = $res_h->fetch_assoc()){
	 	    			if($al['H'] < 4)
	 	    				$dsa++;
	 	    			if($al['H'] > 1)
	 	    				$h++;	
	 	    		}
	 	    		$sel_avg = "SELECT ROUND(AVG(voto), 2) FROM fc_alunni WHERE id_classe = ".$cl['id'];
	 	    		$avg = $db->executeCount($sel_avg);
	 	    	?>
	 	    	<tr id="tr<?php print $cl['id'] ?>">
					<td style="width: 13%; border-bottom: 1px solid #cccccc"><a href="class.php?id_classe=<?php print $cl['id'] ?>"><?php print $cl['descrizione'] ?></a></td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc"><?php print $cl['alunni'] ?></td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc"><?php print $ripetenti ?></td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc"><?php print $male ?></td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc"><?php print $female ?></td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc"><?php print ($h." / ".$dsa." (".($res_h->num_rows).")") ?></td>
					<td style="width: 13%; text-align: center; border-bottom: 1px solid #cccccc"><?php print $avg ?></td>
					<td style="width: 9%; font-weight: bold; text-align: center; border-bottom: 1px solid #cccccc"><a style="color: red; font-weight: bold" href="#" onclick="del_cls(<?php print $cl['id'] ?>)">x</a></td>	 	    	
	 	    	</tr>
	 	    	<?php } ?>
	 	    	</tbody>
	 	    	<tfoot>
	 	   		<tr>
	 				<td colspan="8" style="text-align: right; margin-right: 10px; padding-top: 30px">
						<a href="#" style="" onclick="_classes()">Aggiungi classi</a>
						<input type="hidden" name="cls" id="cls" /> 				
	 				</td>    		
	 	   		</tr>
	 	    	</tfoot>
	 	    </table>
	 	    <?php } ?>

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
