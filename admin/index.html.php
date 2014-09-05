<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Admin home page</title>
<link rel="stylesheet" href="../css/site_themes/blue_red/reg.css" type="text/css" />
<link rel="stylesheet" href="../modules/documents/theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../js/page.js"></script>
<script type="text/javascript">
/*
 * is already there the new year?
 */
var count_year = <?php print $count ?>;

var cdc_created = <?php print $exist_cdc ?>;
var reg_created = <?php print $exist_reg ?>;
var schedule_created = <?php print $exist_sch ?>;
var scr_created1 = <?echo $count_data1 ?>;
var scr_created2 = <?echo $count_data2 ?>;

var tm = 0;
var complete = false;
var timer;

var show_message = function(msg){
	alert(msg);
};

function crea_cdc(){
	if(cdc_created){
		if(!confirm("I dati relativi ai consigli di classe sono gia' presenti in archivio. Vuoi modificarli?")) {
			return false;
		}
		else {
			document.location.href = "cdc_state.php";
			return false;
		}
	}
	var url = "crea_cdc.php";

	$.ajax({
		type: "POST",
		url: url,
		data: {},
		dataType: 'json',
		error: function() {
			show_error("Errore di trasmissione dei dati");
		},
		succes: function() {
			
		},
		complete: function(data){
			r = data.responseText;
			if(r == "null"){
				return false;
			}
			var json = $.parseJSON(r);
			if (json.status == "kosql"){
				alert(json.message);
				console.log(json.dbg_message);
			}
			else {
				alert("Operazione conclusa con successo");
			}
		}
    });
}

function crea_orario(){
	action = "insert";
	if(schedule_created){
		if(!confirm("I dati relativi all'orario sono gia' presenti in archivio. Vuoi cancellarli e ricrearli? ATTENZIONE: cliccando su OK tutte le modifiche apportate all'orario verranno perse.")) {
			return false;
		}
		else {
			action = "reinsert";
		}
	}
	var url = "popola_tabella_orario.php";

	$.ajax({
		type: "POST",
		url: url,
		data: {action: action},
		dataType: 'json',
		error: function() {
			show_error("Errore di trasmissione dei dati");
		},
		succes: function() {
			
		},
		complete: function(data){
			r = data.responseText;
			if(r == "null"){
				return false;
			}
			var json = $.parseJSON(r);
			if (json.status == "kosql"){
				show_error(json.message);
				console.log(json.dbg_message);
			}
			else if(json.status == "ko"){
				show_error(json.message);
			}
			else {
				alert("Operazione conclusa con successo");
			}
		}
    });
}

var new_year = function(){
	today = new Date();
	y = today.getFullYear();
	if(count_year > 0){
		alert("L'anno e' gia' presente in archivio");
		return false;
	}
	document.location.href = "year.php?do=new";
};

var crea_registro = function(){
	if(reg_created){
		if(!confirm("I dati relativi al registro di classe sono gia' presenti in archivio. Vuoi modificarli?")) {
			return false;
		}
		else {
			document.location.href = "classbook_state.php";
			return false;
		}
	}
	var url = "classbook_manager.php";
	leftS = (screen.width - 200) / 2;
	$('#wait_label').css("left", leftS+"px");
	$('#wait_label').css("top", "300px");
	$('#over1').show();
	$('#wait_label').show(800);
	$.ajax({
		type: "POST",
		url: url,
		data: {action: "insert"},
		dataType: 'json',
		error: function() {
			show_error("Errore di trasmissione dei dati");
		},
		succes: function() {
			
		},
		complete: function(data){
			r = data.responseText;
			if(r == "null"){
				return false;
			}
			var json = $.parseJSON(r);
			if (json.status == "kosql"){
				console.log(json.dbg_message);
				$('#wait_label').text(json.message);
				setTimeout("$('#wait_label').hide(2000)", 2000);
				setTimeout("$('#overlay').hide()", 3800);
				
			}
			else if(json.status == "ko"){
				$('#wait_label').text(json.message);
				setTimeout("$('#wait_label').hide(2000)", 2000);
				setTimeout("$('#overlay').hide()", 3800);
    			return;
			}
			else {
				$('#wait_label').text("Operazione conclusa");
				setTimeout("$('#wait_label').hide(2000)", 2000);
				setTimeout("$('#over1').hide()", 3800);
				reg_created = 999;
			}
		}
    });

	upd_str();
};


var pop_scrutini = function(q){
	leftS = (screen.width - 200) / 2;
	$('#wait_label').css("left", leftS+"px");
	$('#wait_label').css("top", "300px");
	$('#over1').show();
	var myvar = "scr_created"+q;
	if(eval(myvar) > 0){	
		if(!confirm("I dati relativi agli scrutino per il "+q+" quadrimestre sono gia' presenti in archivio. Vuoi modificarli?")) {
			return false;
		}
		else {
			document.location.href = "assignment_table.php?quadrimestre="+q;
			return false;
		}
	}
	else{
		var url = "popola_tabella_scrutini.php";
		$('#wait_label').show(800);
		
		$.ajax({
			type: "POST",
			url: url,
			data: {quadrimestre: q, action: "insert"},
			dataType: 'json',
			error: function() {
				show_error("Errore di trasmissione dei dati");
			},
			succes: function() {
				
			},
			complete: function(data){
				complete = true;
		    	clearTimeout(timer);
				r = data.responseText;
				if(r == "null"){
					return false;
				}
				var json = $.parseJSON(r);
				if (json.status == "kosql"){
					console.log(json.dbg_message);
					$('#wait_label').text(json.message);
					setTimeout("$('#wait_label').hide(2000)", 2000);
					setTimeout("$('#overlay').hide()", 3800);
					
				}
				else if(json.status == "ko"){
					$('#wait_label').text(json.message);
					setTimeout("$('#wait_label').hide(2000)", 2000);
					setTimeout("$('#overlay').hide()", 3800);
	    			return;
				}
				else {
					$('#wait_label').text("Operazione conclusa");
					setTimeout("$('#wait_label').hide(2000)", 2000);
					setTimeout("$('#over1').hide()", 3800);
					reg_created = 999;
				}
			}
	    });
		upd_str();
	}
};

var upd_str = function(){
	tm++;
	//alert(tm);
	if(tm > 5){ 
		tm = 0;
		$('#wait_label').text("Caricamento in corso");
	}
	else
		$('#wait_label').text($('#wait_label').text()+".");
	timer = setTimeout("upd_str()", 1000);
};

var _hide = function(){
	$('#over1').hide();
	$('#wait_label').hide();
};

var school_header = function(){
	if(!(_header = prompt("Inserisci l'intestazione della scuola")))
		return false;
	var url = "../shared/update_env.php";
	$.ajax({
		type: "POST",
		url: url,
		data: {field: 'intestazione_scuola', value: _header},
		dataType: 'json',
		error: function() {
			show_error("Errore di trasmissione dei dati");
		},
		succes: function() {
			
		},
		complete: function(data){
			complete = true;
	    	clearTimeout(timer);
			r = data.responseText;
			if(r == "null"){
				return false;
			}
			var json = $.parseJSON(r);
			if (json.status == "kosql"){
				console.log(json.dbg_message);
				show_message(json.message);
				
			}
			else if(json.status == "ko"){
				show_message(json.message);
			}
			else {
				show_message("Operazione completata");
			}
		}
    });

};

var normalize = function(table){
	var url = "normalize_users.php";
	$.ajax({
		type: "POST",
		url: url,
		data: {table: table},
		dataType: 'json',
		error: function() {
			show_error("Errore di trasmissione dei dati");
		},
		succes: function() {
			
		},
		complete: function(data){
			r = data.responseText;
			if(r == "null"){
				return false;
			}
			var json = $.parseJSON(r);
			if (json.status == "kosql"){
				console.log(json.dbg_message);
				show_message(json.message);
				
			}
			else if(json.status == "ko"){
				show_message(json.message);
			}
			else {
				show_message(json.message);
			}
		}
    });

};

var open_tab = "1";
var show_tab = function(tab){
	$("#tb"+open_tab).fadeOut(300);
	$("#tab_"+open_tab).css("color", "#373946");
	$("#tb"+tab).fadeIn(1000);
	$("#tab_"+tab).css("color", "#000000");
	open_tab = tab;
}

$(function(){
	show_tab(<?php echo $tab ?>);
	<?php if($admin_level == 0){ ?>
	$('#head_lnk').click(function(event){
		event.preventDefault();
		school_header();
	});
	$('#head_lnk_1').click(function(event){
		event.preventDefault();
		school_header();
	});
	$('#cdc_lnk').click(function(event){
		event.preventDefault();
		crea_cdc();
	});
	$('#cdc_lnk_1').click(function(event){
		event.preventDefault();
		crea_cdc();
	});
	<?php } ?>
	$('#new_year_lnk').click(function(event){
		event.preventDefault();
		new_year();
	});
	$('#new_year_lnk_1').click(function(event){
		event.preventDefault();
		new_year();
	});
	
	
	$('#sc1_lnk').click(function(event){
		event.preventDefault();
		pop_scrutini(1);
	});
	$('#sc1_lnk_1').click(function(event){
		event.preventDefault();
		pop_scrutini(1);
	});
	$('#sc2_lnk').click(function(event){
		event.preventDefault();
		pop_scrutini(2);
	});
	$('#sc2_lnk_1').click(function(event){
		event.preventDefault();
		pop_scrutini(2);
	});

	$('._tab').click(function(event){
		event.preventDefault();
		var strs = this.id.split("_");
		show_tab(strs[1]);
	});

	$('.norm').click(function(event){
		event.preventDefault();
		var strs = this.id.split("_");
		normalize(strs[1]);
	});
	
});
</script>
<style>
#wait_label{
	width: 200px;
	height: 40px;
	text-align: center;
	background-color: #000000; 
	border: 1px solid #CCCCCC; 
	border-radius: 8px 8px 8px 8px;
	color: white;
	font-weight: bold;
	vertical-align: middle;
}

div.overlay{
    background-image: url('../images/overlay.png');
    position: absolute;
    top: 0px;
    left: 0px;
    z-index: 90;
    width: 100%;
    height: 100%;
}
</style>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu.php" ?>
	</div>
	<div id="left_col">
		<div id="tb1">
		<div class="group_head">Gestione scuola</div>
        <table style="" class="admin_table">
            <?php if($admin_level == 0){ ?>
            <tr style="color: red; font-weight: bold; <?php if($_SESSION['__config__']['installazione_completata'] == 1) echo "display: none" ?>">
                <td style=" width: 30%"><a href="wiz_first_install.php?step=<?php echo $step ?>">Prima installazione</a></td>
                <td style="color: red">
                    <a href="wiz_first_install.php?step=<?php echo $step ?>">Procedura guidata attraverso tutte le operazioni necessarie per utilizzare il software</a>
                </td>
            </tr>
            <tr>
                <td class="col1"><a href="../shared/no_js.php" id="head_lnk">Informazioni di base</a></td>
                <td class="col2">
                    <a href="../shared/no_js.php" id="head_lnk_1">Informazioni di base sulla scuola: nome, indirizzo, tipologia...</a>
                </td>
            </tr>
            <tr>
                <td class="col1"><a href="adm_school/sedi.php">Sedi</a></td>
                <td class="col2">
                    <a href="adm_school/sedi.php">Gestisci le sedi della scuola...</a>
                </td>
            </tr>
            <tr>
                <td class="col1"><a href="adm_school/moduli_orario.php">Moduli orario</a></td>
                <td class="col2">
                    <a href="adm_school/moduli_orario.php">Gestisci i moduli orario da assegnare alle classi...</a>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <td class="col1"><a href="adm_school/materie.php?school_order=<?php echo $admin_level ?>">Materie</a></td>
                <td class="col2">
                    <a href="adm_school/materie.php?school_order=<?php echo $admin_level ?>">Gestisci le materie di insegnamento previste nella tua scuola...</a>
                </td>
            </tr>
            <?php 
            if((count($_SESSION['__school_level__']) > 1)){ 
            	if( $admin_level == 0){
            ?>
            <tr>
                <td class="col1"><a href="year.php">Gestione date anno scolastico</a></td>
                <td class="col2">
                    <a href="year.php">Gestisci le date di inizio e termine dell'anno scolastico</a>
                </td>
            </tr>
            <?php
            	}
            	foreach ($_SESSION['__school_level__'] as $k => $sl){
					if ($k == $admin_level || $admin_level == 0){
            ?>
            <tr>
            	<td class="col1"><a href="year_data.php?school_order=<?php echo $k ?>">A. S. <?php echo $sl ?></a></td>
                <td class="col2">
                    <a href="year_data.php?school_order=<?php echo $k ?>">Gestisci le date dell'<?php echo $_SESSION['__current_year__']->to_string() ?> per la <?php echo $sl ?></a>
                </td>
            </tr>
            <?php
            		}
            	} 
			} else{ ?>
            <tr>
                <td class="col1"><a href="year.php">Gestione anno in corso</a></td>
                <td class="col2"><a href="year.php">Gestisci le date di inizio e termine dell'anno scolastico</a>
                </td>
            </tr>
            <tr>
            	<td class="col1"><a href="year_data.php?school_order=<?php echo $_SESSION['__only_school_level__'] ?>"><?php echo $_SESSION['__current_year__']->to_string() ?></a></td>
                <td class="col2">
                    <a href="year_data.php?school_order=<?php echo $_SESSION['__only_school_level__'] ?>">Gestisci le date dell'<?php echo $_SESSION['__current_year__']->to_string() ?> </a>
                </td>
            </tr>
            <?php } ?>
        </table>
        </div>
        <div id="tb2" style="display: none">
	    <div class="group_head">Gestione utenti</div>
        <table class="admin_table">
            <?php if($admin_level == 0){ ?>
             <tr>
                <td class="col1"><a href="adm_users/users.php">Utenti</a></td>
                <td class="col2">
                    <a href="adm_users/users.php">Inserisci nuovi utenti, modifica i dati di quelli gi&agrave; presenti e cancellali dal db...</a>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <td class="col1"><a href="adm_users/teachers.php?school_order=<?php echo $admin_level ?>">Docenti</a></td>
                <td class="col2">
                    <a href="adm_users/teachers.php?school_order=<?php echo $admin_level ?>">Gestisci i dati dei docenti...</a>
                </td>
            </tr>
            <?php if((count($_SESSION['__school_level__']) > 1) && $admin_level == 0){
            	foreach ($_SESSION['__school_level__'] as $k => $sl){ 
            ?>
            <tr>
                <td class="col1"><a href="adm_students/alunni.php?school_order=<?php echo $k ?>">Alunni <?php echo $sl ?></a></td>
                <td class="col2">
                    <a href="adm_students/alunni.php?school_order=<?php echo $k ?>" >Gestisci gli alunni della <?php echo $sl ?></a>
                </td>
            </tr>
            <?php } ?>
            <?php 
            if(is_installed("parents")): 
            	if((count($_SESSION['__school_level__']) > 1) && $admin_level == 0){	
            		foreach ($_SESSION['__school_level__'] as $k => $sl){
			?>
			<tr>
                <td class="col1"><a href="adm_parents/genitori.php?school_order=<?php echo $k ?>">Genitori <?php echo $sl ?></a></td>
                <td class="col2">
                    <a href="adm_parents/genitori.php?school_order=<?php echo $k ?>">Gestisci i genitori della <?php echo $sl ?></a>
                </td>
            </tr>
			<?php
					}
			?>
            <tr>
                <td class="col1"><a href="adm_parents/genitori_inattivi.php">Genitori inattivi</a></td>
                <td class="col2">
                    <a href="adm_parents/genitori_inattivi.php">Gestisci i genitori con figli inattivi o licenziati...</a>
                </td>
            </tr>
			<?php 
            	}
            endif; ?>
            <?php } else{ ?>
            <tr>
                <td class="col1"><a href="adm_students/alunni.php?school_order=<?php echo $admin_level ?>">Alunni</a></td>
                <td class="col2">
                    <a href="adm_students/alunni.php?school_order=<?php echo $admin_level ?>">Gestisci i dati degli alunni</a>
                </td>
            </tr>
            <?php if(is_installed("parents")): ?>
            <tr>
                <td class="col1"><a href="adm_parents/genitori.php?school_order=<?php echo $admin_level ?>">Genitori</a></td>
                <td class="col2">
                    <a href="adm_parents/genitori.php?school_order=<?php echo $admin_level ?>">Gestisce i dati dei genitori e le associazioni con gli alunni...</a>
                </td>
            </tr>
            <?php endif; ?>
            <?php } ?>
            <?php if($admin_level == 0){ ?>
            <tr>
                <td class="col1"><a href="new_pwd.php">Modifica password utente</a></td>
                <td class="col2">
                    <a href="new_pwd.php">Modifica la password di un utente a scelta...</a>
                </td>
            </tr>
            <?php } ?>
        </table>
        </div>
        <div id="tb3" style="display: none">
        <div class="group_head">Gestione classi</div>
        <table style="" class="admin_table">
            <?php 
            	if(count($_SESSION['__school_level__']) > 1){ 
            		foreach ($_SESSION['__school_level__'] as $k => $sl){
						if ($k == $admin_level || $admin_level == 0){
            ?>
            <tr>
                <td class="col1"><a href="adm_classes/classi.php?school_order=<?php echo $k ?>">Classi <?php echo $sl ?></a></td>
                <td class="col2">
                    <a href="adm_classes/classi.php?school_order=<?php echo $k ?>">Crea e modifica classi, consigli di classe della <?php echo $sl ?></a>
                </td>
            </tr>
            <?php 
					}
            	}
            } 
            ?>
            <?php if($admin_level == 1 || $admin_level == 0): ?>
            <tr>
                <td class="col1"><a href="adm_classes/elenco_ripetenti.php">Assegna ripetenti</a></td>
                <td class="col2">
                    <a href="adm_classes/elenco_ripetenti.php">Assegna i ripetenti delle classi terze che ancora non sono stati inseriti in una nuova classe...</a>
                </td>
            </tr>
            <?php endif; ?>
	        <?php if($admin_level == 2 || $admin_level == 0): ?>
		        <tr>
			        <td class="col1"><a href="adm_classes/moduli_primaria.php">Moduli scuola primaria</a></td>
			        <td class="col2">
				        <a href="adm_classes/moduli_primaria.php">Associa le classi per creare i moduli...</a>
			        </td>
		        </tr>
	        <?php endif; ?>
            <tr>
                <td class="col1"><a href="adm_classes/alunni_liberi.php">Alunni senza classe</a></td>
                <td class="col2">
                    <a href="adm_classes/alunni_liberi.php">Assegna alle classi gli alunni che ancora non sono stati assegnati ad alcune classe (tipicamente i nuovi alunni)...</a>
                </td>
            </tr>
        </table>
       	</div>
       	<div id="tb4" style="display: none">
	        <div class="group_head">Gestione nuovo anno</div>
	        <table style="" class="admin_table">
            <?php if ($admin_level == 0): ?>
            <tr>
                <td class="col1"><a href="../shared/no_js.php" id="new_year_lnk">Crea il nuovo anno </a></td>
                <td class="col2">
                    <a href="../shared/no_js.php" id="new_year_lnk_1">Crea il record relativo al nuovo anno scolastico...</a>
                </td>
            </tr>
	        <?php endif; ?>
		    <?php if($admin_level == 1 || $admin_level == 0): ?>
            <tr>
                <td class="col1"><a href="adm_classes/new_year_classes.php?school_order=1">Classi scuola secondaria di primo grado </a></td>
                <td class="col2">
                    <a href="adm_classes/new_year_classes.php?school_order=1">Gestisci le classi della scuola secondaria per il nuovo anno...</a>
                </td>
            </tr>
			<?php endif; ?>
		    <?php if($admin_level == 2 || $admin_level == 0): ?>
            <tr>
	            <td class="col1"><a href="adm_classes/new_year_classes.php?school_order=2">Classi scuola primaria </a></td>
	            <td class="col2">
		            <a href="adm_classes/new_year_classes.php?school_order=2">Gestisci le classi della scuola primaria per il nuovo anno...</a>
	            </td>
            </tr>
			<?php endif; ?>
		        <?php if ($admin_level == 0): ?>
            <tr>
                <td class="col1"><a href="adm_students/load_students.php">Importa alunni</a></td>
                <td class="col2">
                    <a href="adm_students/load_students.php">Importa i dati degli alunni, usando un file contenente i dati</a>
                </td>
            </tr>
            <?php endif; ?>
            <?php
            if((count($_SESSION['__school_level__']) > 1) && $admin_level == 0){ 
            	foreach ($_SESSION['__school_level__'] as $k => $sl){
            ?>
            <tr>
                <td class="col1"><a href="adm_students/insert_students.php?school_order=<?php echo $k ?>">Inserisci alunni <?php echo substr($sl, 7) ?></a></td>
                <td class="col2">
                    <a href="adm_students/insert_students.php?school_order=<?php echo $k ?>">Inserisci velocemente gli alunni della <?php echo $sl ?></a>
                </td>
            </tr>
            <?php 
				} 
			} 
			else{ 
			?>
            <tr>
                <td class="col1"><a href="adm_students/insert_students.php?school_order=<?php echo $admin_level ?>">Inserisci alunni velocemente</a></td>
                <td class="col2">
                    <a href="adm_students/insert_students.php?school_order=<?php echo $admin_level ?>">Inserisci gli alunni, usando una funzione di inserimento veloce, con dati ridotti</a>
                </td>
            </tr>
            <?php } ?>
            <?php if($admin_level == 0): ?>
            <tr>
                <td class="col1"><a href="schedule_table.php" id="sched_lnk">Orario</a></td>
                <td class="col2">
                    <a href="schedule_table.php" id="sched_lnk_1">Popola la tabella orario del db</a>
                </td>
            </tr>
            <?php endif; ?>
        </table>
        </div>
        <div id="tb5" style="display: none">
	        <div class="group_head">Gestione tabella consigli di classe</div>
	        <table style="" class="admin_table">
            <?php if($admin_level == 0): ?>
            <tr>
                <td class="col1"><a href="../shared/no_js.php" id="cdc_lnk">Popola la tabella</a></td>
                <td class="col2">
                    <a href="../shared/no_js.php" id="cdc_lnk_1">Crea i record che ti permetteranno di gestire i cdc...</a>
                </td>
            </tr>
            <?php endif; ?>
            <?php
            if((count($_SESSION['__school_level__']) > 1) && $admin_level == 0){ 
            	foreach ($_SESSION['__school_level__'] as $k => $sl){
					if($admin_level == $k || $admin_level == 0){
			?>
            <tr>
                <td class="col1"><a href="cdc_state.php?school_order=<?php echo $k ?>" id="cdc_sm">CDC <?php echo $sl ?></a></td>
                <td class="col2">
                    <a href="cdc_state.php?school_order=<?php echo $k ?>" id="cdc_sm<?php echo $k ?>">Modifica i record relativi ai cdc della <?php echo $sl ?></a>
                </td>
            </tr>
            <?php 
					}
				}
			}
			else { 
			?>
			<tr>
                <td class="col1"><a href="cdc_state.php?school_order=<?php echo $admin_level ?>" id="cdc_sm">Gestisci CDC</a></td>
                <td class="col2">
                    <a href="cdc_state.php?school_order=<?php echo $admin_level ?>" id="cdc_sm<?php echo $_SESSION['__only_school_level__'] ?>">Modifica i record relativi ai cdc</a>
                </td>
            </tr>
			<?php } ?>
        </table>
       	</div>
       	<div id="tb6" style="display: none">
	        <div class="group_head">Gestione tabella registro di classe</div>
	        <table style="" class="admin_table">
            <?php
            if((count($_SESSION['__school_level__']) > 1) && $admin_level == 0){ 
            	foreach ($_SESSION['__school_level__'] as $k => $sl){
					if($admin_level == $k || $admin_level == 0){
			?>
            <tr>
                <td class="col1"><a href="classbook_state.php?school_order=<?php echo $k ?>" id="reg_lnk">Registro <?php echo $sl ?></a></td>
                <td class="col2">
                    <a href="classbook_state.php?school_order=<?php echo $k ?>" id="reg_lnk_<?php echo $k ?>">Gestisci i record del registro di classe della <?php echo $sl ?></a>
                </td>
            </tr>
            <?php 
					}
				}
			}
			else { 
			?>
            <tr>
                <td class="col1"><a href="classbook_state.php?school_order=<?php echo $admin_level ?>" id="reg_lnk">Registro di classe</a></td>
                <td class="col2">
                    <a href="classbook_state.php?school_order=<?php echo $admin_level ?>" id="reg_lnk_<?php echo $_SESSION['__only_school_level__'] ?>">Gestisci i record del registro di classe della scuola</a>
                </td>
            </tr>
            <?php } ?>
         </table>
         </div> 
            
        <div id="tb7" style="display: none">
	        <div class="group_head">Gestione scrutini e pagelle</div>
	        <table style="" class="admin_table">
            <?php if ($admin_level == 0): ?>
            <tr>
                <td class="col1"><a href="../shared/no_js.php" id="sc1_lnk">Scrutini 1 quadrimestre</a></td>
                <td class="col2">
                    <a href="../shared/no_js.php" id="sc1_lnk_1">Carica i dati per gli scrutini...</a>
                </td>
            </tr>
            <tr>
                <td class="col1"><a href="../shared/no_js.php" id="sc2_lnk">Scrutini 2 quadrimestre</a></td>
                <td class="col2">
                    <a href="../shared/no_js.php" id="sc2_lnk_1">Carica i dati per gli scrutini...</a>
                </td>
            </tr>
             <?php endif; ?>
             <?php
            if((count($_SESSION['__school_level__']) > 1) && $admin_level == 0){ 
            	foreach ($_SESSION['__school_level__'] as $k => $sl){
					if($admin_level == $k || $admin_level == 0){
			?>
			<tr>
                <td class="col1"><a href="parametri_pagella.php?school_order=<?php echo $k ?>" id="par_lnk">Pagella <?php echo $sl ?></a></td>
                <td class="col2">
                    <a href="parametri_pagella.php?school_order=<?php echo $k ?>" id="par_lnk_<?php echo $k ?>">Gestisci i parametri pagella della <?php echo $sl ?></a>
                </td>
            </tr>
            <?php 
					}
				}
			}
			else { 
			?>
            <tr>
                <td class="col1"><a href="parametri_pagella.php?school_order=<?php echo $admin_level ?>" id="reg_lnk">Parametri pagella</a></td>
                <td class="col2">
                    <a href="parametri_pagella.php?school_order=<?php echo $admin_level ?>" id="reg_lnk_<?php echo $_SESSION['__only_school_level__'] ?>">Gestisci i parametri da inserire in pagella</a>
                </td>
            </tr>
            <?php } ?>
        </table>
        </div>
		<div id="tb8" style="display: none">
			<div class="group_head">Varie</div>
			<table style="" class="admin_table">
				<?php if(is_installed("wflow")){ ?>
					<tr>
						<td class="col1"><a href="adm_workflow/index.php">Workflow</a></td>
						<td class="col2">
							<a href="adm_workflow/index.php">Gestisce tutto ci&ograve; che riguarda i processi di workflow relativi alle richieste ...</a>
						</td>
					</tr>
				<?php } if(is_installed("projects")){ ?>
					<tr>
						<td class="col1"><a href="adm_projects/progetti.php">Progetti</a></td>
						<td class="col2">
							<a href="adm_projects/progetti.php">Gestisci i progetti della scuola</a>
						</td>
					</tr>
				<?php } ?>
				<tr>
					<td class="col1"><a href="statistiche_registro.php">Statistiche registro</a></td>
					<td class="col2">
						<a href="statistiche_registro.php">Visualizza elenchi e dati statistici riguardo il registro ...</a>
					</td>
				</tr>
			</table>
		</div>
        <div id="tb10" style="display: none">
	        <div class="group_head">Moduli</div>
	        <table style="" class="admin_table">
            <?php if(is_installed("com")){ ?>
            <tr>
                <td class="col1"><a href="adm_modules/communication/index.php">Comunicazioni</a></td>
                <td class="col2">
                    <a href="adm_modules/communication/index.php">Gestione modulo communication</a>
                </td>
            </tr>
            <?php } ?>
        </table>
        </div>
            <?php if($admin_level == 0){ ?>
        <div id="tb9" style="display: none">
	        <div class="group_head">Sviluppo</div>
	        <table style="" class="admin_table">
            <tr>
                <td class="col1"><a href="modules.php">Modifica moduli installati</a></td>
                <td class="col2">
                    <a href="modules.php">Modifica l'installazione di moduli e aree ...</a>
                </td>
            </tr>
            <tr>
                <td class="col1"><a href="env.php" id="env_lnk">Variabili</a></td>
                <td class="col2">
                    <a href="env.php" id="env_lnk_1">Modifica le variabili d'ambiente...</a>
                </td>
            </tr>
            <tr>
                <td class="col1"><a href="check_perms.php" id="">Verifica permessi utente</a></td>
                <td class="col2">
                    <a href="check_perms.php" id="">Verifica i permessi di un utente a scelta...</a>
                </td>
            </tr>
            <tr>
                <td class="col1"><a href="tests.php" id="">Test classi</a></td>
                <td class="col2">
                    <a href="tests.php" id="">Testa le classi Manager...</a>
                </td>
            </tr>
            <tr>
                <td class="norm_us"><a href="../shared/no_js.php" class="norm" id="norm1_us">Normalizza utenti</a></td>
                <td class="norm_us">
                    <a href="../shared/no_js.php" class="norm" id="norm2_us">Correggi l'uso delle maiuscole nei nomi...</a>
                </td>
            </tr>
            <tr>
                <td class="norm_st"><a href="../shared/no_js.php" class="norm" id="norm1_st">Normalizza alunni</a></td>
                <td class="norm_st">
                    <a href="../shared/no_js.php" class="norm" id="norm2_st">Correggi l'uso delle maiuscole nei nomi degli alunni...</a>
                </td>
            </tr>
	        <tr>
		        <td class="col1"><a href="scegli_utente.php" class="sudo" id="sudo1">SuDo</a></td>
		        <td class="col2">
			        <a href="scegli_utente.php" class="sudo" id="sudo2">Cambia utente...</a>
		        </td>
	        </tr>
        </table>
        </div>
            <?php } ?>
	</div>

    <div class="overlay" id="over1" style="display: none">
        <div id="wait_label" style="position: absolute; display: none; padding-top: 25px">Caricamento dati in corso</div>
    </div>

	<p class="spacer"></p>
	</div>
<?php include "footer.php" ?>
</body>
</html>
