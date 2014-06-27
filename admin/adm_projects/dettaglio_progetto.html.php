<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dettaglio progetto</title>
<link href="../../css/main.css" rel="stylesheet" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javaScript">
var win2;
var messages = new Array('', 'Progetto inserito con successo', 'Progetto cancellato con successo', 'Progetto modificato con successo', 'Non e\' possibile cancellare il progetto in quanto in archivio sono presenti dei documenti');

function go(par, proj){
	$('teachers').setValue($F('teachers').replace(/#$/,""));
	var numero_documenti = <?php print $count_docs ?>;
	
    if(par == 2){
    	if(numero_documenti > 0){
			alert("Il progetto non puo' essere cancellato perche' sono presenti dei documenti associati allo stesso");
			return false;
		}
        if(!confirm("Sei sicuro di voler cancellare questo progetto?"))
            return false;
    }

    $('_i').setValue(proj);
    $('action').setValue(par);
    var url = "project_manager.php";
    //alert(url);
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: $('proj_form').serialize(true),
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		if(dati[0] == "ko"){
				      		_alert("Si e` verificato un errore. Ti preghiamo di riprovare tra poco");
							console.log("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
							return;
			      		}
			      		link = "progetti.php?msg="+par;
			      		if(par != 1){
							link += "&second=1&offset=<?php print ($offset ? $offset : 0)  ?>";
			      		}

						_alert(messages[par]);
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore...");}
			  });
}

function get_teacher(nuovo){
	/*
	funzione che gestisce i referenti, chiamando con AJAX il db se nuovo = 1
	e cancellando invece il contenuto della textarea se nuovo = 0
	*/
	if(nuovo == 0){
		$('referenti').setValue("");
		$('teachers').setValue("");
	}
	else if(nuovo == 1){
		win2 = new Window({className: "mac_os_x", url: "elenco_docenti.php", top: 100, left: 700, width:650, height:500, zIndex: 100, resizable: true, title: "Elenco docenti", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
		win2.show(false);
	}
}

document.observe("dom:loaded", function(){
	$('add_t').observe("click", function(event){
		event.preventDefault();
		get_teacher(1);
	});
	$('del_t').observe("click", function(event){
		event.preventDefault();
		get_teacher(0);
	});
});

<?php include_once '../popup_dom.php'; ?>

</script>
</head>
<body>
    <div id="header">
	<div class="wrap">
		<?php include "../header.php" ?>
	</div>
</div>
<div class="wrap">
	<div id="main" style="background-color: #FFFFFF; padding-bottom: 30px; width: 100%">
	<p class="popup_label">Dettaglio progetto</p>
    <form action="dettaglio_progetto.php?upd=1" method="post" id="proj_form" class="popup_form">
    <table style="width: 90%; margin: auto">
        <tr class="popup_row header_row">
            <td class="popup_title" style="width: 30%; padding-left: 10px; "><label for="nome">Nome</label></td>
            <td style="width: 70%; " colspan="2">
            	<input class="form_input" type="text" id="nome" name="nome" style="width: 100%" value="<?php if(isset($project)) print $project['nome'] ?>"  />
            </td>
        </tr>
        <tr class="popup_row">
        	<td class="popup_title" style="width: 30%; padding-left: 10px"><label for="referenti">Referenti</label></td>
            <td style="width: 40%; vertical-align: middle">
                <textarea class="form_input" id="referenti" name="referenti" style="width: 205px; height: 50px;"><?php if(isset($teachers_string)){ print join("\n", $teachers_string);} ?></textarea>
            </td>
            <td style="width: 30%; text-align: right">
             	<a href="../../shared/no_js.php" id="add_t">Add</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                <a href="../../shared/no_js.php" id="del_t">Delete</a>
            </td>
        </tr>
        <tr class="popup_row">
        	<td class="popup_title" style="width: 30%; padding-left: 10px"><label for="descrizione">Descrizione</label></td>
            <td colspan="2">
                <textarea class="form_input" name="descrizione" style="width: 100%; height: 75px; margin-top: auto; margin-bottom: auto"><?php if(isset($project)) print $project['descrizione'] ?></textarea>
            </td>
        </tr>
        <tr class="popup_row">
        	<td class="popup_title" style="width: 30%; padding-left: 10px"><label for="anno">Anno inizio</label></td>
            <td style="width: 40%; vertical-align: middle">
                <select class="form_input" style="width: 99%; font-size: 11px; color: #777" name="anno" id="annoo">
            <?php
			while($year = $res_anni->fetch_assoc()){
            ?>
            		<option <?php if($year['id_anno'] == $project['anno_inizio']) print("selected='selected'") ?> value="<?php print $year['id_anno'] ?>"><?php print $year['descrizione'] ?></option>
            <?php } ?>
                </select>
            </td>
            <td class="popup_title" style="width: 30%; padding-left: 30px">Attivo
            	<input class="form_input" style="padding-left: 5px" type="radio" name="attivo" id="attivo" value="1" <?php if($project['attivo'] == 1 || $_i == 0) print("checked='checked'") ?> /> <label style="font-weight: normal" for="attivo">SI</label> <input type="radio" name="attivo" value="0" <?php if($project['attivo'] == 0 && $_i != 0) print("checked='checked'") ?> /> <label style="font-weight: normal" for="attivo">NO</label>
            </td>
        </tr>
        <tr class="popup_row">
            <td colspan="4"></td>
        </tr>
    </table>
    <div style="width: 95%; text-align: right; margin-top: 20px">
    	<a href="../../shared/no_js.php" id="save_button" class="nav_link_first">Registra</a>|
    	<?php if(isset($_GET['id']) && $_GET['id'] != 0){
        ?>
        <a href="../../shared/no_js.php" id="del_button" class="nav_link">Cancella</a>|
        <?php
        }
        ?>
        <a href="progetti.php" id="close_button" class="nav_link_last">Torna all'elenco</a>
        <input type="hidden" name="action" id="action" />
	    <input type="hidden" name="_i" id="_i" />
	    <input type="hidden" name="teachers" id="teachers" value="<?php print $project['referenti'] ?>" />
    </div>
    </form>
</div>
</div>
</body>
</html>