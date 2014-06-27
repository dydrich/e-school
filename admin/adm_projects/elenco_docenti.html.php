<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Elenco docenti</title>
<link href="../../css/main.css" rel="stylesheet" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javaScript">
function add_teacher(nome, id){
	doc = parent.document;
	f1 = parent.frames[0];
	if(doc.forms[0].referenti.value != "")
		nome = "\n"+nome;
	doc.forms[0].referenti.value += nome;
	if(doc.forms[0].teachers.value != "")
		id = "#"+id;
	doc.forms[0].teachers.value += id; 
}

document.observe("dom:loaded", function(){
	$('cl_link').observe("click", function(event){
		event.preventDefault();
		parent.win2.close();
	});
});

</script>
<style type="text/css">
<!--
body {font-size: 11px}
TD {height: 15px}
-->
</style>
</head>
<body>
    <p style="font-size: 12px; font-weight: bold; text-align: center; margin-top: 10px">Seleziona i referenti</p>
    <form>
    <div style="margin-right: auto; margin-left: auto; margin-top: 20px; width: 620px">
    	<table style="width: 600px; margin-right: auto; margin-left: auto">
    	<?php
    	$index = 1;
    	while($teacher = $res_docenti->fetch_assoc()){
    		if($index == 1){
    	?>
    		<tr>
	            
    	<?php		
    		}
    	?>
	            <td style="width: 150px; padding-left: 5px; color: #003366; "><a href="#" onclick="add_teacher('<?php print $teacher['cognome']." ".$teacher['nome'] ?>', <?php print $teacher['uid'] ?>)"><?php print $teacher['cognome']." ".$teacher['nome'] ?></a></td>
    	<?php
    		if(($index%4) == 0){
    	?>
	    	</tr>
	    	<tr>
	    <?php		
    		}  	
    		$index++;
    	}
    	
    	?>
	        
	        </tr>
	    </table>
	    <div style="width: 580px; text-align: right; margin-top: 20px">
	        <a href="../../shared/no_js.php" id="cl_link">Chiudi</a>       
	    </div>
    </div>
    </form>
</body>
</html>