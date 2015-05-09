<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Admin home page</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
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
		var scr_sl1_created1 = <?echo $count_sl1_data1 ?>;
		var scr_sl1_created2 = <?echo $count_sl1_data2 ?>;
		var scr_sl2_created1 = <?echo $count_sl2_data1 ?>;
		var scr_sl2_created2 = <?echo $count_sl2_data2 ?>;

		var scrutini = [];
		scrutini[1] = [];
		scrutini[2] = [];
		scrutini[1][1] = <?echo $count_sl1_data1 ?>;
		scrutini[2][1] = <?echo $count_sl1_data2 ?>;
		scrutini[1][2] = <?echo $count_sl2_data1 ?>;
		scrutini[2][2] = <?echo $count_sl2_data2 ?>;

		var tm = 0;
		var complete = false;
		var timer;

		var show_message = function(msg){
			alert(msg);
		};

		function crea_cdc(){
			if(cdc_created){
				if(!confirm("I dati relativi ai consigli di classe sono già presenti in archivio. Vuoi modificarli?")) {
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
				if(!confirm("I dati relativi all'orario sono già presenti in archivio. Vuoi cancellarli e ricrearli? ATTENZIONE: cliccando su OK tutte le modifiche apportate all'orario verranno perse.")) {
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
				alert("L'anno è già presente in archivio");
				return false;
			}
			document.location.href = "year.php?do=new";
		};

		var crea_registro = function(){
			if(reg_created){
				if(!confirm("I dati relativi al registro di classe sono già presenti in archivio. Vuoi modificarli?")) {
					return false;
				}
				else {
					document.location.href = "classbook_state.php";
					return false;
				}
			}
			var url = "classbook_manager.php";
			background_process("Operazione in corso", 20, true);
			$.ajax({
				type: "POST",
				url: url,
				data: {action: "insert"},
				dataType: 'json',
				error: function() {
					clearTimeout(bckg_timer);
					$('#background_msg').text("Errore di trasmissione dei dati");
					setTimeout(function() {
						$('#background_msg').dialog("close");
					}, 2000);
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
						console.log(json.dbg_message);
						clearTimeout(bckg_timer);
						$('#background_msg').text(json.message);
						setTimeout(function() {
							$('#background_msg').dialog("close");
						}, 2000);

					}
					else if(json.status == "ko"){
						console.log(json.dbg_message);
						clearTimeout(bckg_timer);
						$('#background_msg').text(json.message);
						setTimeout(function() {
							$('#background_msg').dialog("close");
						}, 2000);
		                return;
					}
					else {
						clearTimeout(bckg_timer);
						$('#background_msg').text("Operazione conclusa");
						setTimeout(function() {
							$('#background_msg').dialog("close");
						}, 2000);
						reg_created = 999;
					}
				}
		    });
		};


		var pop_scrutini = function(q, school_level){
			leftS = (screen.width - 200) / 2;

			var myvar = scrutini[q][school_level];
			if(myvar > 0){
				if(!confirm("I dati relativi agli scrutini per il "+q+" quadrimestre sono già presenti in archivio. Vuoi modificarli?")) {
					$('#over1').hide();
					return false;
				}
				else {
					document.location.href = "assignment_table.php?quadrimestre="+q+"&school_order="+school_level;
					return false;
				}
			}
			else{
				var url = "eoyevaluation_manager.php";
				background_process("Operazione in corso", 20, true);

				$.ajax({
					type: "POST",
					url: url,
					data: {quadrimestre: q, action: "insert", school_order: school_level},
					dataType: 'json',
					error: function() {
						clearTimeout(bckg_timer);
						$('#background_msg').text("Errore di trasmissione dei dati");
						setTimeout(function() {
							$('#background_msg').dialog("close");
						}, 2000);
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
							clearTimeout(bckg_timer);
							$('#background_msg').text(json.message);
							setTimeout(function() {
								$('#background_msg').dialog("close");
							}, 2000);

						}
						else if(json.status == "ko"){
							clearTimeout(bckg_timer);
							$('#background_msg').text(json.message);
							setTimeout(function() {
								$('#background_msg').dialog("close");
							}, 2000);
			                return;
						}
						else {
							clearTimeout(bckg_timer);
							$('#background_msg').text("Operazione conclusa");
							setTimeout(function() {
								$('#background_msg').dialog("close");
							}, 2000);
							scrutini[q][school_level] = 999;
						}
					}
			    });
				//upd_str();
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
			var navlabel = $("#tb"+tab).attr("data-tablabel");
			$('#navlabel').text(navlabel);
			open_tab = tab;
		}

		$(function(){
			load_jalert();
			setOverlayEvent();
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


			$('.sc1_lnk').click(function(event){
				event.preventDefault();
				var quad = $(this).attr("data-q");
				var level = $(this).attr("data-sl");
				pop_scrutini(1, level);
			});
			$('.sc2_lnk').click(function(event){
				event.preventDefault();
				var quad = $(this).attr("data-q");
				var level = $(this).attr("data-sl");
				pop_scrutini(2, level);
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
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu.php" ?>
	</div>
	<div id="left_col">
		<div id="tb1" data-tablabel="gestione scuola" style="width: 90%; margin: auto">
        <?php if($admin_level == 0){ ?>
			<a href="wiz_first_install.php?step=<?php echo $step ?>">
				<div class="card">
					<div class="card_title">Prima installazione</div>
					<div class="card_minicontent">Procedura guidata attraverso tutte le operazioni necessarie per utilizzare il software</div>
				</div>
			</a>
	        <a href="../shared/no_js.php" id="head_lnk">
		        <div class="card">
			        <div class="card_title">Informazioni di base</div>
			        <div class="card_minicontent">Informazioni di base sulla scuola: nome, indirizzo, tipologia...</div>
		        </div>
	        </a>
	        <a href="adm_school/sedi.php">
		        <div class="card">
			        <div class="card_title">Sedi</div>
			        <div class="card_minicontent">Gestisci le sedi della scuola...</div>
		        </div>
	        </a>
	        <a href="adm_school/moduli_orario.php">
		        <div class="card">
			        <div class="card_title">Moduli orario</div>
			        <div class="card_minicontent">Gestisci i moduli orario da assegnare alle classi...</div>
		        </div>
	        </a>
            <?php } ?>
			<a href="adm_school/materie.php?school_order=<?php echo $admin_level ?>">
				<div class="card">
					<div class="card_title">Materie</div>
					<div class="card_minicontent">Gestisci le materie di insegnamento previste nella tua scuola...</div>
				</div>
			</a>
            <?php
            if((count($_SESSION['__school_level__']) > 1)){ 
            	if( $admin_level == 0){
            ?>
		    <a href="year.php">
	            <div class="card">
		            <div class="card_title">Gestione date anno scolastico</div>
		            <div class="card_minicontent">Gestisci le date di inizio e termine dell'anno scolastico</div>
	            </div>
            </a>
            <?php
            	}
            	foreach ($_SESSION['__school_level__'] as $k => $sl){
					if ($k == $admin_level || $admin_level == 0){
            ?>
			<a href="year_data.php?school_order=<?php echo $k ?>">
				<div class="card">
					<div class="card_title">A. S. <?php echo $sl ?></div>
					<div class="card_minicontent">Gestisci le date dell'<?php echo $_SESSION['__current_year__']->to_string() ?> per la <?php echo $sl ?></div>
				</div>
			</a>
            <?php
            		}
            	} 
			} else{ ?>
            <a href="year.php">
	            <div class="card">
		            <div class="card_title">Gestione anno in corso</div>
		            <div class="card_minicontent">Gestisci le date di inizio e termine dell'anno scolastico</div>
	            </div>
            </a>
            <a href="year_data.php?school_order=<?php echo $_SESSION['__only_school_level__'] ?>">
	            <div class="card">
		            <div class="card_title"><?php echo $_SESSION['__current_year__']->to_string() ?></div>
		            <div class="card_minicontent">Gestisci le date dell'<?php echo $_SESSION['__current_year__']->to_string() ?></div>
	            </div>
            </a>
            <?php } ?>
        </div>
        <div id="tb2" style="display: none" data-tablabel="utenti" class="card_container">
        <?php if($admin_level == 0){ ?>
	        <a href="adm_users/users.php">
		        <div class="card">
			        <div class="card_title">Utenti</div>
			        <div class="card_minicontent">Inserisci nuovi utenti, modifica i dati di quelli gi&agrave; presenti e cancellali dal db...</div>
		        </div>
	        </a>
            <?php } ?>
	        <a href="adm_users/teachers.php?school_order=<?php echo $admin_level ?>">
		        <div class="card">
			        <div class="card_title">Docenti</div>
			        <div class="card_minicontent">Gestisci i dati dei docenti...</div>
		        </div>
	        </a>
            <?php if((count($_SESSION['__school_level__']) > 1) && $admin_level == 0){
            	foreach ($_SESSION['__school_level__'] as $k => $sl){ 
            ?>
            <a href="adm_students/alunni.php?school_order=<?php echo $k ?>">
	            <div class="card">
		            <div class="card_title">Alunni <?php echo $sl ?></div>
		            <div class="card_minicontent">Gestisci gli alunni della <?php echo $sl ?></div>
	            </div>
            </a>
            <?php } ?>
            <?php 
            if(is_installed("parents")): 
            	if((count($_SESSION['__school_level__']) > 1) && $admin_level == 0){	
            		foreach ($_SESSION['__school_level__'] as $k => $sl){
			?>
            <a href="adm_parents/genitori.php?school_order=<?php echo $k ?>">
	            <div class="card">
		            <div class="card_title">Genitori <?php echo $sl ?></div>
		            <div class="card_minicontent">Gestisci i genitori della <?php echo $sl ?></div>
	            </div>
            </a>
			<?php
					}
			?>
            <a href="adm_parents/genitori_inattivi.php">
	            <div class="card">
		            <div class="card_title">Genitori inattivi</div>
		            <div class="card_minicontent">Gestisci i genitori con figli inattivi o licenziati...</div>
	            </div>
            </a>
			<?php 
            	}
            endif; ?>
            <?php } else{ ?>
            <a href="adm_students/alunni.php?school_order=<?php echo $admin_level ?>">
	            <div class="card">
		            <div class="card_title">Alunni</div>
		            <div class="card_minicontent">Gestisci i dati degli alunni</div>
	            </div>
            </a>
            <?php if(is_installed("parents")): ?>
            <a href="adm_parents/genitori.php?school_order=<?php echo $admin_level ?>">
	            <div class="card">
		            <div class="card_title">Genitori</div>
		            <div class="card_minicontent">Gestisce i dati dei genitori e le associazioni con gli alunni...</div>
	            </div>
            </a>
            <?php endif; ?>
            <?php } ?>
            <?php if($admin_level == 0){ ?>
            <a href="new_pwd.php">
	            <div class="card">
		            <div class="card_title">Modifica password utente</div>
		            <div class="card_minicontent">Modifica la password di un utente a scelta...</div>
	            </div>
            </a>
            <?php } ?>
        </div>
        <div id="tb3" style="display: none" data-tablabel="classi" class="card_container">
            <?php 
            	if(count($_SESSION['__school_level__']) > 1){ 
            		foreach ($_SESSION['__school_level__'] as $k => $sl){
						if ($k == $admin_level || $admin_level == 0){
            ?>
			<a href="adm_classes/classi.php?school_order=<?php echo $k ?>">
				<div class="card">
					<div class="card_title">Classi <?php echo $sl ?></div>
					<div class="card_minicontent">Crea e modifica classi, consigli di classe della <?php echo $sl ?></div>
				</div>
			</a>
            <?php 
					}
            	}
            } 
            ?>
            <?php if($admin_level == 1 || $admin_level == 0): ?>
            <a href="adm_classes/elenco_ripetenti.php">
	            <div class="card">
		            <div class="card_title">Assegna ripetenti</div>
		            <div class="card_minicontent">Assegna i ripetenti delle classi terze che ancora non sono stati inseriti in una nuova classe...</div>
	            </div>
            </a>
            <?php endif; ?>
	        <?php if($admin_level == 2 || $admin_level == 0): ?>
	        <a href="adm_classes/moduli_primaria.php">
		        <div class="card">
			        <div class="card_title">Moduli scuola primaria</div>
			        <div class="card_minicontent">Associa le classi per creare i moduli...</div>
		        </div>
	        </a>
	        <?php endif; ?>
	        <a href="adm_classes/alunni_liberi.php">
		        <div class="card">
			        <div class="card_title">Alunni senza classe</div>
			        <div class="card_minicontent">Assegna alle classi gli alunni che ancora non sono stati assegnati ad alcune classe (tipicamente i nuovi alunni)...</div>
		        </div>
	        </a>
       	</div>
       	<div id="tb4" style="display: none" data-tablabel="nuovo anno" class="card_container">
            <?php if ($admin_level == 0): ?>
            <a href="../shared/no_js.php" id="new_year_lnk">
	            <div class="card">
		            <div class="card_title">Crea il nuovo anno</div>
		            <div class="card_minicontent">Crea il record relativo al nuovo anno scolastico...</div>
	            </div>
            </a>
	        <?php endif; ?>
		    <?php if($admin_level == 1 || $admin_level == 0): ?>
		    <a href="adm_classes/new_year_classes.php?school_order=1">
			    <div class="card">
				    <div class="card_title">Classi scuola secondaria di primo grado</div>
				    <div class="card_minicontent">Gestisci le classi della scuola secondaria per il nuovo anno...</div>
			    </div>
		    </a>
			<?php endif; ?>
		    <?php if($admin_level == 2 || $admin_level == 0): ?>
		    <a href="adm_classes/new_year_classes.php?school_order=2">
			    <div class="card">
				    <div class="card_title">Classi scuola primaria</div>
				    <div class="card_minicontent">Gestisci le classi della scuola primaria per il nuovo anno...</div>
			    </div>
		    </a>
			<?php endif; ?>
		        <?php if ($admin_level == 0): ?>
	        <a href="adm_students/load_students.php">
		        <div class="card">
			        <div class="card_title">Importa alunni</div>
			        <div class="card_minicontent">Importa i dati degli alunni, usando un file in formato predefinito</div>
		        </div>
	        </a>
            <?php endif; ?>
            <?php
            if((count($_SESSION['__school_level__']) > 1) && $admin_level == 0){ 
            	foreach ($_SESSION['__school_level__'] as $k => $sl){
            ?>
            <a href="adm_students/insert_students.php?school_order=<?php echo $k ?>">
	            <div class="card">
		            <div class="card_title">Inserisci alunni <?php echo substr($sl, 7) ?></div>
		            <div class="card_minicontent">Inserisci velocemente gli alunni della <?php echo $sl ?></div>
	            </div>
            </a>
            <?php 
				} 
			} 
			else{ 
			?>
			<a href="adm_students/insert_students.php?school_order=<?php echo $admin_level ?>">
				<div class="card">
					<div class="card_title">Inserisci alunni velocemente</div>
					<div class="card_minicontent">Inserisci gli alunni, usando una funzione di inserimento veloce, con dati ridotti</div>
				</div>
			</a>
            <?php } ?>
            <?php if($admin_level == 0): ?>
            <a href="schedule_table.php" id="sched_lnk">
	            <div class="card">
		            <div class="card_title">Orario</div>
		            <div class="card_minicontent">Popola la tabella orario del db</div>
	            </div>
            </a>
            <?php endif; ?>
        </div>
        <div id="tb5" style="display: none" data-tablabel="cdc" class="card_container">
            <?php if($admin_level == 0): ?>
            <a href="../shared/no_js.php" id="cdc_lnk">
	            <div class="card">
		            <div class="card_title">Popola la tabella</div>
		            <div class="card_minicontent">Crea i record che ti permetteranno di gestire i cdc...</div>
	            </div>
            </a>
            <?php endif; ?>
            <?php
            if((count($_SESSION['__school_level__']) > 1) && $admin_level == 0){ 
            	foreach ($_SESSION['__school_level__'] as $k => $sl){
					if($admin_level == $k || $admin_level == 0){
			?>
			<a href="cdc_state.php?school_order=<?php echo $k ?>" id="cdc_sm">
				<div class="card">
					<div class="card_title">CDC <?php echo $sl ?></div>
					<div class="card_minicontent">Modifica i record relativi ai cdc della <?php echo $sl ?></div>
				</div>
			</a>
            <?php 
					}
				}
			}
			else { 
			?>
			<a href="cdc_state.php?school_order=<?php echo $admin_level ?>" id="cdc_sm">
				<div class="card">
					<div class="card_title">Gestisci CDC</div>
					<div class="card_minicontent">Modifica i record relativi ai cdc</div>
				</div>
			</a>
			<?php } ?>
       	</div>
       	<div id="tb6" style="display: none" data-tablabel="registri" class="card_container">
            <?php
            if((count($_SESSION['__school_level__']) > 1) && $admin_level == 0){ 
            	foreach ($_SESSION['__school_level__'] as $k => $sl){
					if($admin_level == $k || $admin_level == 0){
			?>
			<a href="classbook_state.php?school_order=<?php echo $k ?>" id="reg_lnk">
				<div class="card">
					<div class="card_title">Registro <?php echo $sl ?></div>
					<div class="card_minicontent">Gestisci i record del registro di classe della <?php echo $sl ?></div>
				</div>
			</a>
            <?php 
					}
				}
			}
			else { 
			?>
			<a href="classbook_state.php?school_order=<?php echo $admin_level ?>" id="reg_lnk">
				<div class="card">
					<div class="card_title">Registro di classe</div>
					<div class="card_minicontent">Gestisci i record del registro di classe della scuola</div>
				</div>
			</a>
            <?php } ?>
        </div>
        <div id="tb7" style="display: none" data-tablabel="scrutini" class="card_container">
	        <?php
	        if((count($_SESSION['__school_level__']) > 1) && $admin_level == 0) {
		        foreach ($_SESSION['__school_level__'] as $k => $sl) {
			        if ($admin_level == $k || $admin_level == 0) {
				        if ($k == 3) continue;
	        ?>
	        <div class="card">
		        <div class="card_title">Scrutini <?php echo $sl ?></div>
		        <div class="card_minicontent">
			        <p style="margin: 0">
				        <a href="../shared/no_js.php" id="link<?php echo $k ?>_1" class="sc1_lnk normal" data-sl="<?php echo $k ?>" data-q="1">Primo quadrimestre</a>
			        </p>
			        <p style="margin: 5px 0">
				        <a href="../shared/no_js.php" id="link<?php echo $k ?>_2" class="sc2_lnk normal" data-sl="<?php echo $k ?>" data-q="1">Secondo quadrimestre</a>
			        </p>
		        </div>
	        </div>
			        <?php
			        }
		        }
	        }
	        ?>
             <?php
            if((count($_SESSION['__school_level__']) > 1) && $admin_level == 0){ 
            	foreach ($_SESSION['__school_level__'] as $k => $sl){
					if($admin_level == $k || $admin_level == 0){
			?>
			<a href="parametri_pagella.php?school_order=<?php echo $k ?>" id="par_lnk">
				<div class="card">
					<div class="card_title">Pagella <?php echo $sl ?></div>
					<div class="card_minicontent">Gestisci i parametri pagella della <?php echo $sl ?></div>
				</div>
			</a>
            <?php 
					}
				}
			}
			else { 
			?>
			<a href="parametri_pagella.php?school_order=<?php echo $admin_level ?>" id="reg_lnk">
				<div class="card">
					<div class="card_title">Parametri pagella</div>
					<div class="card_minicontent">Gestisci i parametri da inserire in pagella</div>
				</div>
			</a>
            <?php } ?>
        </div>
		<div id="tb8" style="display: none" data-tablabel="varie" class="card_container">
				<?php if(is_installed("wflow")){ ?>
			<a href="adm_workflow/index.php">
				<div class="card">
					<div class="card_title">Workflow</div>
					<div class="card_minicontent">Gestisce tutto ci&ograve; che riguarda i processi di workflow relativi alle richieste ...</div>
				</div>
			</a>
				<?php } if(is_installed("projects")){ ?>
			<a href="adm_projects/progetti.php">
				<div class="card">
					<div class="card_title">Progetti</div>
					<div class="card_minicontent">Gestisci i progetti della scuola</div>
				</div>
			</a>
				<?php } ?>
			<a href="statistiche_registro.php">
				<div class="card">
					<div class="card_title">Statistiche registro</div>
					<div class="card_minicontent">Visualizza elenchi e dati statistici riguardo il registro...</div>
				</div>
			</a>
			<a href="login_failed.php">
				<div class="card">
					<div class="card_title">Login non riusciti</div>
					<div class="card_minicontent">Visualizza i log relativi ai login non riusciti...</div>
				</div>
			</a>
			<a href="events_list.php">
				<div class="card">
					<div class="card_title">Gestione eventi</div>
					<div class="card_minicontent">Gestisci gli eventi da registrare...</div>
				</div>
			</a>
		</div>
        <div id="tb10" style="display: none" data-tablabel="moduli" class="card_container">
            <?php if(is_installed("com")){ ?>
            <a href="adm_modules/communication/index.php">
	            <div class="card">
		            <div class="card_title">Comunicazioni</div>
		            <div class="card_minicontent">Gestione modulo communication</div>
	            </div>
            </a>
            <?php } ?>
        </div>
            <?php if($admin_level == 0){ ?>
        <div id="tb9" style="display: none" data-tablabel="sviluppo" class="card_container">
	        <a href="modules.php">
		        <div class="card">
			        <div class="card_title">Modifica moduli installati</div>
			        <div class="card_minicontent">Modifica l'installazione di moduli e aree...</div>
		        </div>
	        </a>
	        <a href="env.php">
		        <div class="card">
			        <div class="card_title">Variabili</div>
			        <div class="card_minicontent">Modifica le variabili d'ambiente...</div>
		        </div>
	        </a>
	        <a href="check_perms.php">
		        <div class="card">
			        <div class="card_title">Verifica permessi utente</div>
			        <div class="card_minicontent">Verifica i permessi di un utente a scelta...</div>
		        </div>
	        </a>
	        <a href="tests.php">
		        <div class="card">
			        <div class="card_title">Test classi</div>
			        <div class="card_minicontent">Testa le classi Manager...</div>
		        </div>
	        </a>
	        <a href="../shared/no_js.php" class="norm" id="norm1_us">
		        <div class="card">
			        <div class="card_title">Normalizza utenti</div>
			        <div class="card_minicontent">Correggi l'uso delle maiuscole nei nomi...</div>
		        </div>
	        </a>
	        <a href="../shared/no_js.php" class="norm" id="norm1_st">
		        <div class="card">
			        <div class="card_title">Normalizza alunni</div>
			        <div class="card_minicontent">Correggi l'uso delle maiuscole nei nomi degli alunni...</div>
		        </div>
	        </a>
	        <a href="scegli_utente.php" class="sudo" id="sudo1">
		        <div class="card">
			        <div class="card_title">SuDo</div>
			        <div class="card_minicontent">Cambia utente...</div>
		        </div>
	        </a>
	        <a href="scegli_tema.php" class="theme" id="theme1">
		        <div class="card">
			        <div class="card_title">Modifica tema</div>
			        <div class="card_minicontent">Modifica il tema di default del software</div>
		        </div>
	        </a>
        </div>
            <?php } ?>
	</div>

    <div class="overlay" id="over1" style="display: none">
        <div id="wait_label" style="position: absolute; display: none; padding-top: 25px">Caricamento dati in corso</div>
    </div>

	<p class="spacer"></p>
	</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="../index.php"><img src="../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="index.php"><img src="../images/31.png" style="margin-right: 10px; position: relative; top: 5%" />Admin</a></div>
		<div class="drawer_link"><a href="http://www.istitutoiglesiasserraperdosa.it"><img src="../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../shared/do_logout.php"><img src="../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
