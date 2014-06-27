<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="author" content="" />
	<link href="../../css/reg.css" rel="stylesheet" />
	<link href="../../css/general.css" rel="stylesheet" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript">
var step = <?php echo $_SESSION['__new_classes_step__'] ?>;

var wrong_step = function(t_step){
	if(step == 5){
		alert("La procedura e' stata completata con successo.");
		return false;
	}
	if(t_step > step)
		alert("Non puoi attivare questa funzione sino a quando non avrai completato le precedenti");
	else if(((t_step == step) && (t_step != 1)) || (step > t_step))
		alert("Funzione completata. Passa alla successiva");
	$('step'+(step+1)).setStyle({backgroundColor: '#FAF6B7'});
	return false;
};

var cancella_terze = function(){
	if(step > 1){
		return false;
	}
	if(!confirm("Questa procedura eliminerà tutte le terze attualmente presenti, cancellando dall'archivio tutti gli alunni non segnalati come ripetenti. Vuoi continuare?")){
		return false;
	}
	var url = "cancella_terze.php";
    req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {},
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
				    	//alert(response);
			    		dati = response.split("#");
			    		if(dati[0] == "ok"){
							step = 2;
							alert("Cancellazione completata");
			            }
			            else{
			                alert("Operazione non riuscita. Query: "+dati[1]+"\nErrore: "+dati[2]);
			                return;
			            }
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};

var avanza = function(cls){
	if(cls == 2){
		if(step > 2) return false;
	}
	else{
		if(step > 3) return false;
	}
	var url = "avanza_classi.php";
    req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {cls: cls},
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
				    	//alert(response);
			    		dati = response.split("#");
			    		if(dati[0] == "ok"){
							if(cls == 2)
								step = 3;
							else if(cls = 1)
								step = 4;
							alert("Operazione completata");
			            }
			            else{
			                alert("Operazione non riuscita. Query: "+dati[1]+"\nErrore: "+dati[2]);
			                return;
			            }
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};

document.observe("dom:loaded", function(){
	$$('table tbody a').invoke("observe", "mouseover", function(event){
		//alert(this.id);
		this.setStyle({color: '#8a1818', fontWeight: 'bold'});
	});
	$$('table tbody a').invoke("observe", "mouseout", function(event){
		//alert(this.id);
		this.setStyle({color: '', fontWeight: 'normal'});
	});
	$$('table tbody a').invoke("observe", "click", function(event){
		
		t_step = this.readAttribute("step");
		if(((step > t_step)) || ((t_step == step) && t_step != 1)){
			event.preventDefault();
			wrong_step(t_step);
		}
	});
	$('lnk2').observe("click", function(event){
		event.preventDefault();
		cancella_terze();
	});
	$('lnk3').observe("click", function(event){
		event.preventDefault();
		avanza(2);
	});
	$('lnk4').observe("click", function(event){
		event.preventDefault();
		avanza(1);
	});
});

</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "../new_year_menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Gestione classi per nuovo anno scolastico</div>
		<table class="admin_table">
        <thead>
        </thead>
        <tbody>
        	<tr class="no_border">
            	<td colspan="2" style="" class="title_row _center no_border">Classi terze uscenti</td>
            </tr>
            <tr class="admin_row_before_text">
                <td colspan="2"></td>
            </tr>
            <tr class="admin_row" id="step1">
	            <td style="width: 30%"><a href="ripetenti.php" step="1">Segnala ripetenti</a></td>
	            <td style="width: 70%">Segnala tutti i ripetenti delle classi terze, per poi assegnarli a nuove classi. Tutti gli alunni non segnalati verranno cancellati dall'archivio.</td>
            </tr>
            <tr class="admin_row" id="step2">
	            <td style="width: 30%"><a href="../../shared/no_js.php" id="lnk2" step="2">Cancella classi</a></td>
	            <td style="width: 70%">Elimina dall'archivio le classi terze: questa operazione &egrave; necessaria per permettere l'attivazione delle nuove terze.</td>
            </tr>
            <tr class="admin_void">
                <td colspan="2"></td>
            </tr>
            <tr>
            	<td colspan="2" style="" class="title_row _center">Nuove classi terze e seconde</td>                
            </tr>
            <tr class="admin_row_before_text">
                <td colspan="2"></td>
            </tr>
            <tr class="admin_row" id="step3">
	            <td style="width: 30%"><a href="../../shared/no_js.php" id="lnk3" step="3">Attivazione nuove terze </a></td>
	            <td style="width: 70%">Avanza tutte le classi seconde perch&eacute; diventino terze: questa operazione aggiorna automaticamente i dati degli alunni.</td>
            </tr>
            <tr class="admin_row" id="step4">
	            <td style="width: 30%"><a href="../../shared/no_js.php" id="lnk4" step="4">Attivazione nuove seconde</a></td>
	            <td style="width: 70%">Avanza tutte le classi prime perch&eacute; diventino seconde: questa operazione aggiorna automaticamente i dati degli alunni.</td>
            </tr>
            <tr class="admin_void">
                <td colspan="2"></td>
            </tr>
            <tr>
            	<td colspan="2" style="" class="title_row _center">Nuove classi prime</td>                
            </tr>
            <tr class="admin_row_before_text">
                <td colspan="2"></td>
            </tr>
            <tr class="admin_row" id="step5">
	            <td style="width: 30%"><a href="nuove_prime.php" step="5">Crea classi prime</a></td>
	            <td style="width: 70%">Crea le nuove classi prime, alle quali potrai in seguito assegnare gli alunni. </td>
            </tr>
        </tbody>
        <tfoot>
        	<tr class="admin_void">
                <td colspan="2"></td>
            </tr>
            <tr class="admin_menu">
            	<td colspan="2">
                    <a href="../index.php">Torna Menu</a>
                </td>
            </tr>
        </tfoot>
        </table>
    </div>
    <?php include "../footer.php" ?>
	</div>
</body>
</html>