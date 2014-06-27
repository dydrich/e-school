<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Segnala alunno</title>
<link rel="stylesheet" href="../teachers/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../js/jquery_themes/custom-theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
$(function() {   
    //autocomplete
    $("#student").autocomplete({
        source: "get_students.php",
        minLength: 2,
        select: function(event, ui){
			uid = ui.item.uid;
			$('#studentID').val(uid);
        }
    });                
 
});


function registra(){
	//alert($('#ore').val());
	$.ajax({
		type: "GET",
		url: "ore_sostegno.php",
		data: {f: $('#studentID').val(), val: $('#ore').val()},
		dataType: 'text',
		error: function() {
			alert("Errore di trasmissione dei dati");
		},
		succes: function() {
			
		},
		complete: function(data){
			r = data.responseText;
			if(r == "null"){
				return false;
			}
			if (r == "kosql"){

			}
			else {
				document.location.href = "alunni_sostegno.php";
			}
		}
    });
}
</script>
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
		Segnala alunno con sostegno
	</div>
 	<form id="my_form" method="post" action="" style="border: 1px solid #666666; border-radius: 10px; margin-top: 30px; text-align: left; width: 460px; margin-left: auto; margin-right: auto">
	<table style="width: 400px; margin-left: auto; margin-right: auto; margin-top: 30px; margin-bottom: 20px">
		<tr>
			<td style="width: 60%">Alunno</td>
			<td style="width: 40%"><input type="text" name="student" id="student" autofocus style="width: 250px; font-size: 11px; border: 1px solid #AAAAAA" /></td> 
		</tr>
		<tr>
			<td style="width: 60%">Numero di ore</td>
			<td style="width: 40%"><input type="text" name="ore" id="ore" style="width: 250px; font-size: 11px; border: 1px solid #AAAAAA" /></td> 
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td> 
		</tr>
		<tr>
			<td colspan="2" style="text-align: right; margin-right: 50px">
				<a href="#" onclick="registra()" class="standard_link">Registra</a>
			</td> 
		</tr>
	</table>
	<input type="hidden" name="studentID" id="studentID" />
	</form>
</div>
<p class="spacer"></p>	
</div>
<?php include "footer.php" ?>	
</body>
</html>
