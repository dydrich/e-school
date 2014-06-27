<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Area dirigenza::gestione nuove classi</title>
<link rel="stylesheet" href="../styles.css" type="text/css" />
<script type="text/javascript" src="/js/page.js"></script>
<?php include $_ENV["DOCUMENT_ROOT"]."/js/prototype.php" ?>
<script type="text/javascript">
var _diagnose = function(sel){
	if(sel.value > 1)
		$('tr_diag').style.display = "";
	else
		$('tr_diag').style.display = "none";
};

var _close = function(){
	//parent.document.location.href = "students.php";
	parent.win.close();
};

var save = function(){
	if($('stid').value != 0)
		action = 1;
	else
		action = 2;
	var req = new Ajax.Request('manage_student.php?action='+action,
			  {
			    	method:'post',
			    	asynchronous: false,
			    	parameters: $('_form').serialize(true),
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
		            	if(dati[0] == "ko"){
							alert(dati[1]+"##"+dati[2]);
							return false;
		            	}
		            	//alert("Aggiornamento terminato");
		            	parent.win.close();
		            	parent.document.location.href = "students.php?q=<?php print $_REQUEST['q'] ?>&order=<?php print $_REQUEST['order'] ?>";
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};
</script>
<style>
td { border: 0}
tbody tr:hover { background-color: none }
</style>
</head>
<body style="background-color: #FFFFFF">
<div style="width: 380px; margin: auto; padding-top: 10px">
	<p style="font-weight: bold; text-align: center; width: 100%">Dettaglio alunno</p>
	<form method="post" id="_form" action="update_student.php">
	<table style="width: 100%; border: 0">
	<thead>
		<tr>
			<td style="width: 30%; font-weight: bold">Nome</td>
			<td style="width: 70%">
				<input type="text" name="fname" id="fname" value="<?php if($student) print $student['nome'] ?>" style="width: 220px; font-size: 11px; border: 1px solid #dddddd" />
			</td>
		</tr>
		<tr>
			<td style="width: 30%; font-weight: bold">Cognome</td>
			<td style="width: 70%">
				<input type="text" name="lname" id="lname" value="<?php if($student) print $student['cognome'] ?>" style="width: 220px; font-size: 11px; border: 1px solid #dddddd" />
			</td>
		</tr>
		<tr>
			<td style="width: 40%; font-weight: bold">Provenienza</td>
			<td style="width: 60%">
				<select name="from" id="from" style="width: 220px; font-size: 11px; border: 1px solid #dddddd">
					<option value="0">.</option>
				<?php 
				while($from = $res_classes_from->fetch_assoc()){
				?>
					<option <?php if($student && $student['classe_provenienza'] == $from['id_classe']) print "selected='selected'" ?>  value="<?php print $from['id_classe'] ?>"><?php print $from['description'] ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td style="width: 40%; font-weight: bold">Sesso</td>
			<td style="width: 60%">
				<select name="sex" id="sex" style="width: 220px; font-size: 11px; border: 1px solid #dddddd">
					<option value="all">.</option>
					<option <?php if($student && $student['sesso'] == "F") print "selected='selected'" ?> value="F">Femmina</option>
					<option <?php if($student && $student['sesso'] == "M") print "selected='selected'" ?> value="M">Maschio</option>
				</select>
			</td>
		</tr>
		<tr>
			<td style="width: 40%; font-weight: bold">H e DSA</td>
			<td style="width: 60%">
				<select name="h" id="h" onchange="_diagnose(this)" style="width: 220px; font-size: 11px; border: 1px solid #dddddd">
					<option <?php if($student && $student['H'] == "0") print "selected='selected'" ?> value="0">No</option>
					<option <?php if($student && $student['H'] == "1") print "selected='selected'" ?> value="1">DSA</option>
					<option <?php if($student && $student['H'] == "2") print "selected='selected'" ?> value="2">Sostegno non grave + DSA</option>
					<option <?php if($student && $student['H'] == "3") print "selected='selected'" ?> value="3">Sostegno grave + DSA</option>
					<option <?php if($student && $student['H'] == "4") print "selected='selected'" ?> value="4">Sostegno non grave</option>
					<option <?php if($student && $student['H'] == "5") print "selected='selected'" ?> value="5">Sostegno grave</option>
				</select>
			</td>
		</tr>
		<tr id="tr_diag" <?php if($student && $student['H'] < 2){ ?>style="display: none"<?php } ?>>
			<td style="width: 40%; font-weight: bold">Diagnosi H</td>
			<td style="width: 60%">
				<textarea id="diagnose" name="diagnose" style="width: 220px; border: 1px solid #dddddd; font-size: 11px"><?php if($student && trim($student['diagnosi_h']) != "") print trim($student['diagnosi_h']) ?></textarea>
			</td>
		</tr>
		<tr>
			<td style="width: 40%; font-weight: bold">Voto</td>
			<td style="width: 60%">
				<select name="grade" id="grade" style="width: 220px; font-size: 11px; border: 1px solid #dddddd">
					<option value="0">.</option>
				<?php 
				for($i = 4; $i < 11; $i++){
				?>
					<option  <?php if($student && $student['voto'] == $i) print "selected='selected'" ?> value="<?php print $i ?>"><?php print $i ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td style="width: 40%; font-weight: bold">Note</td>
			<td style="width: 60%">
				<textarea id="note" name="note" style="width: 220px; border: 1px solid #dddddd; font-size: 11px"><?php if($student && $student['note'] != "") trim(print $student['note']) ?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: right; padding-top: 10px; padding-bottom: 20px">
				<input type="hidden" name="stid" id="stid" value="<?php print $_REQUEST['stid'] ?>" />
				<a href="#" onclick="save()">Salva le modifiche</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" onclick="_close()">Chiudi</a>		
			</td>
		</tr>
	</thead>
	</table>
	</form>
</div>
</body>
</html>