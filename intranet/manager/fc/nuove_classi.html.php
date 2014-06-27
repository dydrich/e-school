<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Area dirigenza::settore docenti</title>
<link rel="stylesheet" href="../styles.css" type="text/css" />
<script type="text/javascript" src="/js/page.js"></script>
<?php include $_ENV["DOCUMENT_ROOT"]."/js/prototype.php" ?>
<script type="text/javascript">
var del = function(id, element){
	if(!confirm("Sei sicuro di voler eliminare questa classe?"))
		return false;
	manage_class(id, 2, '', $(element));
	//alert("ID: "+id+"\nElement: "+element);
};

var manage_class = function(id, action, name, element){
	sede = tp = 0;
	if(action == 1){
		if(confirm("La classe si trova nella sede centrale?"))
			sede = 1;
		else
			sede = 2;
		if(confirm("La classe e' a tempo prolungato?"))
			tp = 1;
		else
			tp = 2;
	}
	else if(action == 3)
		return false;
	//alert(element);
	var req = new Ajax.Request('class_manager.php',
			  {
			    	method:'post',
			    	parameters: {id: id, action: action, name: name, sede: sede, tp: tp},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		//alert(action);
		            	if(dati[0] == "ko"){
							alert(dati[1]+": ==>"+dati[1]+"\n"+dati[2]);
							return false;
		            	}
		            	if(action == 2){
							element.innerHTML = "Aggiungi";
							el = element.id.split("_");
							element.setAttribute("onclick", "manage_class(0, 1, '"+el[1]+"', this)");
							$('del_'+el[1]).innerHTML = "";
							$('del_'+el[1]).setAttribute("style", "color: red; font-weight: bold; padding-left: 10px");
		            	}
		            	else if(action == 1){
		            		element.innerHTML = name;
		            		//alert(element.getAttribute("onclick"));
		            		element.setAttribute("href", "#");
		            		element.setAttribute("onclick", "");
		            		el = element.id.split("_");
		            		$('del_'+el[1]).innerHTML = "x";
							$('del_'+name).setAttribute("style", "color: red; font-weight: bold; padding-left: 10px");
							$("del_"+name).setAttribute("onclick", "del("+dati[1]+", 'mng_"+name+"')");
							
		            	}
					
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};

var save = function(){
	if(!confirm("Stai per registrare le classi per il nuovo anno scolastico: questa operazione non puo' essere cancellata. Vuoi continuare?"))
		return false;
	var req = new Ajax.Request('save_classes.php',
			  {
			    	method:'post',
			    	parameters: {},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		//alert(action);
		            	if(dati[0] == "ko"){
							alert("Errore: ==>"+dati[1]+"\n"+dati[2]);
							return false;
		            	}
		            	alert("Le classi sono state create correttamente");
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
	
};
</script>
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
            <h3>Gestione classi nuovo anno</h3>
	 	    <table style="border-collapse: collapse; width: 95%; margin-top: 20px">
	 	    	<tr>
	 	    	<?php 
	 	    	for($i = 0; $i < 7; $i++){
	 	    	?>
	 	    		<td style="width: 14%; font-weight: bold; text-align: center"><?php print ("Corso ".$alfabeto[$i]) ?></td>
	 	    	<?php } ?>
	 	    	</tr>
	 	    	<?php 
	 	    	for($x = 1; $x < 4; $x++){
	 	    	?>
	 	    	<tr>
				<?php 
	 	    		for($i = 0; $i < 7; $i++){
	 	    			if(isset($classi[$alfabeto[$i]][$x])){
	 	    				$text = $x.$alfabeto[$i];
	 	    				$id = $classi[$alfabeto[$i]][$x]['id_classe'];
	 	    				$delete = true;
	 	    				$param = $text;
	 	    				$action = 3;
	 	    			}
	 	    			else{
	 	    				$text = "+";
	 	    				$id = 0;
	 	    				$delete = false;
	 	    				$param = $x.$alfabeto[$i];
	 	    				$action = 1;
	 	    			}
	 	    				
	 	    	?>
					<td style="width: 14%; font-weight: normal; text-align: center"><a id="mng_<?php print $param ?>" href="#" onclick="manage_class(<?php print $id ?>, <?php print $action ?>, '<?php print $param ?>', this)"><?php print $text ?></a><a id="del_<?php print $param ?>" href="#" onclick="del(<?php print $id ?>, 'mng_<?php print $param ?>')" style='color: red; font-weight: bold; padding-left: 10px'><?php if($delete){ print ("x"); } ?></a></td>
	 	    	<?php 
	 	    		}
	 	    	?>	
	 	    	</tr>
	 	    	<?php 
	 	    	} 
	 	    	?>
	 	    	<tr>
	 	    	<?php 
	 	    	for($i = 7; $i < 14; $i++){
	 	    	?>
	 	    		<td style="width: 14%; font-weight: bold; text-align: center"><?php print ("Corso ".$alfabeto[$i]) ?></td>
	 	    	<?php } ?>
	 	    	</tr>
	 	    	<?php 
	 	    	for($x = 1; $x < 4; $x++){
	 	    	?>
	 	    	<tr>
				<?php 
	 	    		for($i = 7; $i < 14; $i++){
	 	    			if(isset($classi[$alfabeto[$i]][$x])){
	 	    				$text = $x.$alfabeto[$i];
	 	    				$id = $classi[$alfabeto[$i]][$x]['id_classe'];
	 	    				$delete = true;
	 	    				$param = $text;
	 	    				$action = 3;
	 	    			}
	 	    			else{
	 	    				$text = "Aggiungi";
	 	    				$id = 0;
	 	    				$delete = false;
	 	    				$param = $x.$alfabeto[$i];
	 	    				$action = 1;
	 	    			}
	 	    				
	 	    	?>
					<td style="width: 14%; font-weight: normal; text-align: center"><a id="mng_<?php print $param ?>" href="#" onclick="manage_class(<?php print $id ?>, <?php print $action ?>, '<?php print $param ?>', this)"><?php print $text ?></a><a id="del_<?php print $param ?>" href="#" onclick="del(<?php print $id ?>, 'mng_<?php print $param ?>')" style='color: red; font-weight: bold; padding-left: 10px'><?php if($delete){ print ("x"); } ?></a></td>
	 	    	<?php 
	 	    		}
	 	    	?>	
	 	    	</tr>
	 	    	<?php 
	 	    	} 
	 	    	?>
	 	    	<tr>
	 	    		<td colspan="7" style="padding-top: 20px; text-align: right"><a href="#" onclick="save()">Registra classi</a></td>
	 	    	</tr>
	 	    </table>
			<br /><br />
			<!-- END CONTENT -->		
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