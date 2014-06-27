<!DOCTYPE html>
<html>
<head>
<title>Dettaglio news</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="../../css/main.css" rel="stylesheet" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
var step = 0;
var idx = 0;
var ids = new Array();
var groups = 0;
<?php if(isset($ct)) print ("groups = ".$ct.";\n"); ?>

function start(){
	step = document.forms[0].num_step.value;
	idx = 0;
	//alert(step);
	var l = document.getElementById("links");
	l.innerHTML = "";
	var b = document.getElementById("but");
	b.innerHTML = "<input type='button' class='button' value='Aggiungi' onclick='mostra()' />";
}

function mostra(){
	document.forms[0].codice_step.value = "";
	ids.length = 0;
	document.getElementById("nascosto").style.position = "absolute";
	document.getElementById("nascosto").style.top = "55px";
	document.getElementById("nascosto").style.left = "175px";
	document.getElementById("nascosto").style.display = "block";
}

function nascondi(){
	document.getElementById("nascosto").style.display = "none";
}

function change(obj, ind){
	var l = document.getElementById("links");
	ids.push(ind);
	
	if(idx < step){
		l.innerHTML += obj.innerHTML+", ";
		idx++;
	}
	if(idx == step){
		//alert(ids.lenght);
		l.innerHTML = l.innerHTML.slice(0, (l.innerHTML.length - 2));
		var b = document.getElementById("but");
		b.innerHTML = "";
		nascondi();
		document.forms[0].codice_step.value = ids.join(",");
	}
	
}

function ctrl_gruppi (check){
	if(check.checked)
		groups++;
	else
		groups--;
}

function go(par, id){
    if(par == 2){
        if(!confirm("Sei sicuro di voler cancellare questa richiesta?"))
            return false;
    }
    
    var ok = true;
    var msg = "Ci sono degli errori nel modulo:\n";
    var indice = 1;
    if(document.forms[0].nome_flusso.value == ""){
    	msg += indice+". Inserire il nome della richiesta\n";
    	ok = false;
    	indice++;
    }
    if(document.forms[0].num_step.value == "" || document.forms[0].codice_step.value == ""){
    	msg += indice+". Inserire degli step per il flusso di gestione della richiesta\n";
    	ok = false;
    	indice++;
    }
    if(groups < 1){
    	msg += indice+". Inserire almeno un gruppo che ha accesso alla richiesta\n";
    	ok = false;
    	indice++;
    } 
    if(!ok && par != 2){
    	alert(msg);
    	return false;
    }
    //alert("##"+document.forms[0].codice_step.value+"##");
    $('_i').value = id;
    $('action').value = par;
    var url = "<?php print $_SESSION['__config__']['root_site'] ?>admin/adm_workflow/workflow_manager.php";
    //alert(url);
	var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: $('wf_form').serialize(true),
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		if(dati[0] == "ko"){
							alert("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
							return;
			      		}
			      		parent.win.close();
			      		link = "workflow.php?msg="+par;
			      		parent.document.location.href = link;
			      		//parent.win.close();
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}
</script>
</head>
<body>
    <p class="popup_header">Gestione richiesta</p>
    <form action="dettaglio_workflow.php?upd=1" method="post" id="wf_form">
    <div style="margin-right: auto; margin-left: auto; margin-top: 0px; width: 600px">
    	<fieldset style="width: 600px; border: 1px solid; padding-top: 10px; ">
	    <legend style="font-weight: bold;">Workflow</legend>
	    <table style="width: 580px; margin-right: auto; margin-left: auto">
	        <tr class="popup_row header_row">
	            <td class="popup_title" style="width: 150px; padding-left: 10px; ">Richiesta</td>
	            <td style="width: 430px; " colspan="3">
	            	<input class="form_input" type="text" name="nome_flusso" style="width: 430px" value="<?php if(isset($flow)) print $flow['richiesta']  ?>"  />
	            </td>
	        </tr>
	        <tr class="popup_row">
	            <td class="popup_title" style="width: 150px; padding-left: 10px">Step</td>
	            <td style="width: 30px; ">
	            	<input class="form_input" type="text" name="num_step" style="width: 30px" value="<?php if(isset($flow)) print $flow['num_step'] ?>" onblur="start()" />
	            </td>
	            <td style="width: 335px; padding-left: 10px" id="links"><?php if(isset($flow)) print $stringa_step ?></td>
	            <td style="width: 55px" id="but"></td>
	        </tr>
	        <tr class="popup_row">
	        	<td class="popup_title" style="width: 150px; padding-left: 10px">Permessi</td>
	            <td style="width: 430px" colspan="3">
	                <?php
	                while($_uf = $res_gruppi->fetch_assoc()){
	                	if(isset($_POST['gruppi']))
	                		$_g = $_POST['gruppi'];
	                	else if(isset($flow))
	                		$_g = $flow['gruppi'];
	                    $checked = "";
	                    if(isset($_REQUEST['id']) && $_REQUEST['id'] != 0){
	                        if($_uf['permessi']&$_g)
	                        //if($user['gruppi']&$gr['codice'])
	                            $checked = "checked";
	                    }
	                ?>
	                <input class="form_input" type="checkbox" onclick="ctrl_gruppi(this)" style="margin: auto" value="<?php print $_uf['permessi'] ?>" name="gruppi[]" <?php print $checked ?> />&nbsp;&nbsp;&nbsp;<?php print $_uf['nome'] ?>&nbsp;&nbsp;&nbsp;
	                <?php } ?>
	            </td>
	        </tr>
	        <tr class="popup_row">
	            <td colspan="2">
	            	<input type="hidden" name="action" id="action" />
				    <input type="hidden" name="_i" id="_i" />
				    <input type="hidden" name="codice_step" id="codice_step" value="<?php if($flow) print $flow['codice_step']; ?>" />
	            </td>
	        </tr>
	    </table>
	    </fieldset>
	    <div style="width: 600px; text-align: right; margin-top: 20px">
	    <a href="#" onclick="go(<?php if(isset($_GET['id']) && $_GET['id'] != 0) print("3, ".$_REQUEST['id']); else print("1, 0"); ?>)" class="nav_link_first">Registra</a>|
	    	<?php if(isset($_GET['id']) && $_GET['id'] != 0){
	        ?>
	        <a href="#" onclick="go(2, <?php print $_REQUEST['id'] ?>)" class="nav_link">Cancella</a>|
	        <?php
	        }
	        ?>
	        <a href="#" onclick="parent.win.close()" class="nav_link_last">Chiudi</a>
	    </div>
    </div>
   </form>
       <div style="background-color: rgb(235,235,241);width: 300px; heigth: 250px; margin: auto; display: none; border: 1px solid #000000; padding-bottom: 30px;" id="nascosto">
    	<div class="popup_title" style="margin-right: auto; margin-left: auto; text-align: center">Seleziona gli step</div><br />
    <?php
    $x = 0;
    while($st = $res_step->fetch_assoc()){
    ?>
    	<div id="sp<?php print $x ?>" style="padding-left: 10px"><a href="#" onclick="change(this, <?php print $st['id_step'] ?>)"><?php print $st['descrizione'] ?></a></div>
    <?php } ?>
    </div>
</body>
</html>