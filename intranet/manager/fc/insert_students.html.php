<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Area dirigenza::gestione nuove classi</title>
<link rel="stylesheet" href="../styles.css" type="text/css" />
<script type="text/javascript" src="/js/page.js"></script>
<?php include $_ENV["DOCUMENT_ROOT"]."/js/prototype.php" ?>
<script type="text/javascript">
var save = function(_continue){
	var req = new Ajax.Request('manage_student.php?action=2',
			  {
			    	method:'post',
			    	asynchronous: false,
			    	parameters: $('_form').serialize(true),
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
		            	if(dati[0] == "ko"){
							alert(dati[1]);
							return false;
		            	}
		            	if(!_continue)
		            		document.location.href = "students.php";
		            	else{
							$('fname').value = "";
							$('lname').value = "";
							$('from').selectedIndex = 0;
							$('sex').selectedIndex = 0;
							$('h').selectedIndex = 0;
							$('diagnose').innerHTML = "";
							$('tr_diag').style.display = "none";
							$('grade').selectedIndex = 0;
							$('note').innerHTML = "";
							$('note').value = "";
							$('fname').focus();
		            	}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};
</script>
<style>
td {border: 0}
tr:hover {background-color: #e8d5c5}
</style>
</head>
<body onload="$('fname').focus()">
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
            <h3 style="padding-bottom: 30px">Inserimento studenti</h3>
	<form method="post" id="_form" action="update_student.php">
	<table style="width: 50%; border: 0; margin: auto">
	<thead>
		<tr>
			<td style="width: 30%; font-weight: bold; font-size: 13px">Nome</td>
			<td style="width: 70%">
				<input type="text" name="fname" id="fname" style="width: 320px; font-size: 13px; border: 1px solid #dddddd" />
			</td>
		</tr>
		<tr>
			<td style="width: 30%; font-weight: bold; font-size: 13px">Cognome</td>
			<td style="width: 70%">
				<input type="text" name="lname" id="lname" style="width: 320px; font-size: 13px; border: 1px solid #dddddd" />
			</td>
		</tr>
		<tr>
			<td style="width: 30%; font-weight: bold; font-size: 13px">Provenienza</td>
			<td style="width: 70%">
				<select name="from" id="from" style="width: 320px; font-size: 13px; border: 1px solid #dddddd">
					<option value="0">.</option>
				<?php 
				while($from = $res_classes_from->fetch_assoc()){
				?>
					<option value="<?php print $from['id_classe'] ?>"><?php print $from['description'] ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td style="width: 30%; font-weight: bold; font-size: 13px">Sesso</td>
			<td style="width: 70%">
				<select name="sex" id="sex" style="width: 320px; font-size: 13px; border: 1px solid #dddddd">
					<option value="all">.</option>
					<option value="F">Femmina</option>
					<option value="M">Maschio</option>
				</select>
			</td>
		</tr>
		<tr>
			<td style="width: 30%; font-weight: bold; font-size: 13px">H e DSA</td>
			<td style="width: 70%">
				<select name="h" id="h" onchange="_diagnose(this)" style="width: 320px; font-size: 13px; border: 1px solid #dddddd">
					<option value="0">No</option>
					<option value="1">DSA</option>
					<option value="2">Sostegno non grave + DSA</option>
					<option value="3">Sostegno grave + DSA</option>
					<option value="4">Sostegno non grave</option>
					<option value="5">Sostegno grave</option>
				</select>
			</td>
		</tr>
		<tr id="tr_diag" style="display: none; font-size: 13px">
			<td style="width: 30%; font-weight: bold">Diagnosi H</td>
			<td style="width: 70%">
				<textarea id="diagnose" name="diagnose" style="width: 320px; border: 1px solid #dddddd; font-size: 13px"></textarea>
			</td>
		</tr>
		<tr>
			<td style="width: 30%; font-weight: bold; font-size: 13px">Voto</td>
			<td style="width: 70%">
				<select name="grade" id="grade" style="width: 320px; font-size: 13px; border: 1px solid #dddddd">
					<option value="0">.</option>
				<?php 
				for($i = 4; $i < 11; $i++){
				?>
					<option value="<?php print $i ?>"><?php print $i ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td style="width: 30%; font-weight: bold; font-size: 13px">Note</td>
			<td style="width: 70%">
				<textarea id="note" name="note" style="width: 320px; border: 1px solid #dddddd; font-size: 13px"></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: right; padding-top: 10px; padding-bottom: 20px">
					
			</td>
		</tr>
	</thead>
	</table>
	</form>
    <div style="width: 95%; text-align: right"><a href="#" style="font-size: 12px" onclick="save(false)">Salva ed esci</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" style="font-size: 12px" onclick="save(true)">Salva e continua</a></div>	  
            
            
            
            
            
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