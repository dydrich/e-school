<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Area dirigenza::gestione nuove classi</title>
<link rel="stylesheet" href="../styles.css" type="text/css" />
<script type="text/javascript" src="/js/page.js"></script>
<?php include $_ENV["DOCUMENT_ROOT"]."/js/prototype.php" ?>
<script type="text/javascript">
var show_list = function(class_id){
	/**
	*	#1: fetch # of rows and ask for confirm
	*	#2: fetch data from database using Ajax
	*	#3: format data 
	*	#4: show data
	*/
	go_ahead = false;
	var req = new Ajax.Request('get_students.php',
			  {
			    	method:'post',
			    	asynchronous: false,
			    	parameters: $('_form').serialize(true),
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
		            	if(dati[0] == "ko"){
							//alert("Errore nella cancellazione dell'alunno dalla classe: "+dati[1]+"\n"+dati[2]);
							return false;
		            	}
		            	if(dati[1] == 0){
							alert("Nessun alunno presente");
							return false;
		            	}
		            	if(!confirm("Saranno estratti "+dati[1]+" nomi: vuoi continuare?")){
		            		go_ahead = false;
		            	}
		            	else
			            	go_ahead = true;
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
	//alert(go_ahead);
	if(go_ahead){
		
		var req2 = new Ajax.Request('get_students.php',
			  {
			    	method:'post',
			    	asynchronous: false,
			    	parameters: {step: 2},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
		            	if(dati[0] == "ko"){
							alert(dati[1]+"\n"+dati[2]);
							return false;
		            	}
		            	win2 = parent.win2;
		            	mydiv = parent.document.getElementById('list_div');
		            	
		            	for(var i = 1; i < dati.length; i++){
			            	lista = dati[i].split(";");
			            	var par = document.createElement("p");
							par.setAttribute("style", "text-align: left; margin-left: 15px; padding: 0 0 15 0; height: 12px; margin-top: 0; margin-bottom: 0; font-weight: normal");
							par.setAttribute("id", "p"+lista[1]);
							var lnk = document.createElement("a");
							lnk.setAttribute("onclick", "update_class("+lista[1]+", "+class_id+")");
							lnk.setAttribute("href", "#");
							var a_txt = document.createTextNode(lista[0]+" ("+lista[2]+")");
							lnk.appendChild(a_txt);
							par.appendChild(lnk);
							mydiv.appendChild(par);
		            	}
		            	
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
		parent.win2.show();
	}	
};

var _close = function(){
	parent.document.location.href = "class.php?id_classe=<?php print $_SESSION['__class_id__'] ?>";
	parent.win2.close();
	parent.win.close();
	
};
</script>
<style>
td { border: 0}
</style>
</head>
<body style="background-color: #FFFFFF">
<div style="width: 380px; margin: auto; padding-top: 10px">
	<p style="font-weight: bold; text-align: center; width: 100%">Seleziona alunni</p>
	<form method="post" id="_form">
	<table style="width: 100%; border: 0">
		<tr>
			<td style="width: 30%; font-weight: bold">Cognome</td>
			<td style="width: 70%">
				<input type="text" name="name" id="name" style="width: 220px; font-size: 11px; border: 1px solid #dddddd" />
			</td>
		</tr>
		<tr>
			<td style="width: 40%; font-weight: bold">Sesso</td>
			<td style="width: 60%">
				<select name="sex" id="sex" style="width: 220px; font-size: 11px; border: 1px solid #dddddd">
					<option value="all">Tutti</option>
					<option value="F">Femmine</option>
					<option value="M">Maschi</option>
				</select>
			</td>
		</tr>
		<tr>
			<td style="width: 40%; font-weight: bold">Ripetente</td>
			<td style="width: 60%">
				<select name="rip" id="rip" style="width: 220px; font-size: 11px; border: 1px solid #dddddd">
					<option value="all">Tutti</option>
					<option value="1">Solo ripetenti</option>
					<option value="0">Solo non ripetenti</option>
				</select>
			</td>
		</tr>
		<tr>
			<td style="width: 40%; font-weight: bold">H e DSA</td>
			<td style="width: 60%">
				<select name="h" id="h" style="width: 220px; font-size: 11px; border: 1px solid #dddddd">
					<option value="0">Tutti</option>
					<option value="1">Solo DSA</option>
					<option value="2">Solo sostegno</option>
					<option value="3">Sostegno  e DSA</option>
				</select>
			</td>
		</tr>
		<tr>
			<td style="width: 40%; font-weight: bold">Provenienza</td>
			<td style="width: 60%">
				<select name="from" id="from" style="width: 220px; font-size: 11px; border: 1px solid #dddddd">
					<option value="0">Tutte</option>
				<?php 
				while($from = $res_from->fetch_assoc()){
				?>
					<option value="<?php print $from['id_classe'] ?>"><?php print $from['class_from'] ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td style="width: 40%; font-weight: bold">Voto</td>
			<td style="width: 60%">
				<select name="grade" id="grade" style="width: 220px; font-size: 11px; border: 1px solid #dddddd">
					<option value="0">Tutti</option>
					<option value="1">Gravemente insufficiente</option>
					<option value="2">Mediocre (5)</option>
					<option value="3">Sufficiente (6)</option>
					<option value="3">Buono (7-8)</option>
					<option value="3">Ottimo (9-10)</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: right; padding-top: 10px">
				<a href="#" onclick="show_list(<?php print $_SESSION['__class_id__'] ?>)">Estrai</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" onclick="_close()">Chiudi</a>
				<input type="hidden" name="step" id="step" value="1" />			
			</td>
		</tr>
	</table>
	</form>
</div>
</body>
</html>