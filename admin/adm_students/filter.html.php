<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Filtra alunni</title>
	<link href="../../css/reg.css" rel="stylesheet" />
	<link href="../../css/general.css" rel="stylesheet" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
function go(){
    var url = "alunni.php?order=nome";
    if(document.forms[0].sezione.value != "all")
    	url += "&sezione="+document.forms[0].sezione.value;
    if(document.forms[0].classe.value != "all")
    	url += "&classe="+document.forms[0].classe.value;
    if(document.forms[0].anno.value != "")
    	url += "&anno="+document.forms[0].anno.value;
    if(document.forms[0].nome.value != "")
    	url += "&nome="+document.forms[0].nome.value;
    parent.window.document.location.href = url;
    //parent.win.close();
}

document.observe("dom:loaded", function(){
	$('go_link').observe("click", function(event){
		event.preventDefault();
		go();
	});
	$('close_link').observe("click", function(event){
		event.preventDefault();
		parent.win.close();
	});
});

</script>
</head>
<body style="background-color: whitesmoke; margin: 0; background-image: none">
    <p style="font-size: 12px; font-weight: bold; text-align: center; padding-top: 5px">Filtra elenco alunni</p>
    <form action="#" method="post">
    <fieldset style="width: 350px; border: 1px solid #BBB; margin-top: 15px; margin-left: auto; margin-right: auto">
    <legend style="font-weight: bold;">Parametri di ricerca</legend>
    <table style="width: 350px; margin-left: auto; margin-right: auto; margin-top: 10px">
        <tr>
            <td class="popup_title" align="left" style="width: 150px">Sezione</td>
            <td style="width: 200px">
                <select style="border: 1px solid; width: 200px; font-size: 11px; color: #777" name="sezione">
                	<option selected="selected" value="all" style="padding-left: 10px">Tutte</option>
                	<?php
					while($sez = $res_sezioni->fetch_assoc()){
                	?>
                	<option value="<?php print $sez['sezione'] ?>" style="padding-left: 10px"><?php print $sez['sezione'] ?></option>
                	<?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="popup_title" align="left" style="width: 150px">Classe</td>
            <td style="width: 200px">
                <select style="border: 1px solid; width: 200px; font-size: 11px; color: #777" name="classe">
                	<option selected="selected" value="all">Tutte</option>
                	<?php 
                	foreach ($classi as $k => $v){
                	?>
                	<option value="<?php echo $k ?>"><?php echo $v ?></option>
                	<?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="popup_title" align="left" style="width: 150px">Anno di nascita</td>
            <td style="width: 200px">
                <input type="text" name="anno" style="width: 199px; font-size: 11px" value="" maxlength="4" />
            </td>
        </tr>
        <tr>
            <td class="popup_title" align="left" style="width: 150px">Nome</td>
            <td style="width: 200px">
                <input type="text" name="nome" style="width: 199px; font-size: 11px" value="" />
            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;&nbsp;&nbsp;</td>
        </tr>
    </table>
    </fieldset>
    <div style="width: 350px; margin-left: 15px; margin-top: 20px; margin-bottom: 20px; text-align: right">
        <a href="../../shared/no_js.php" id="go_link" class="standard_link nav_link_first" style="color: #003366">Estrai</a>|
        <a href="../../shared/no_js.php" id="close_link" class="standard_link nav_link_last" style="color: #003366">Chiudi</a>
    </div>
   </form>
</body>
</html>