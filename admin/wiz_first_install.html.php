<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Attivazione del software</title>
<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../js/page.js"></script>
<script type="text/javascript">
var cdc_created = <?php print $exist_cdc ?>;
var reg_created = <?php print $exist_reg ?>;
var schedule_created = <?php print $exist_sch ?>;
var scr_created1 = <?echo $count_data1 ?>;
var scr_created2 = <?echo $count_data2 ?>;

var tm = 0;
var complete = false;
var timer;

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
			j_alert("error", "Errore di trasmissione dei dati");
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
				j_alert("error", json.message);
				console.log(json.dbg_message);
			}
			else {
				j_alert("alert", "Operazione conclusa con successo");
				cdc_created = 999;
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
			j_alert("error", "Errore di trasmissione dei dati");
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
				j_alert("error", json.message);
				console.log(json.dbg_message);
			}
			else if(json.status == "ko"){
				j_alert("error", json.message);
			}
			else {
				j_alert("alert", "Operazione conclusa con successo");
				schedule_created = 999;
			}
		}
	});
}

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
			j_alert("error", "Errore di trasmissione dei dati");
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


var pop_scrutini = function(){
	if(scr_created1 > 0){	
		_alert("Operazione gia` effettuata");
		return false;
	}
	else{
		var url = "popola_tabella_scrutini.php";

		$.ajax({
			type: "POST",
			url: url,
			data: {quadrimestre: 1, action: "reinsert"},
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
					scr_created1 = 999;
					$.ajax({
						type: "POST",
						url: url,
						data: {quadrimestre: 2, action: "reinsert"},
						dataType: 'json',
						error: function() {
							j_alert("error", "Errore di trasmissione dei dati");
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
								scr_created2 = 999;
							}
						}
					});
				}
			}
		});
	}
};

var upd_str = function(){
	tm++;
	//alert(tm);
	if(tm > 5){ 
		tm = 0;
		$('wait_label').update("Operazione in corso");
	}
	else
		$('wait_label').innerHTML += ".";
	timer = setTimeout("upd_str()", 1000);
};

var _hide = function(){
	$('over1').hide();
	$('wait_label').hide();
};

var close_and_go = function(){
	var url = "../shared/update_env.php";

	$.ajax({
		type: "POST",
		url: url,
		data: {field: 'installazione_completata', value: '1'},
		dataType: 'json',
		error: function() {
			j_alert("error", "Errore di trasmissione dei dati");
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
				j_alert("error", json.message);
				console.log(json.dbg_message);
			}
			else if(json.status == "ko"){
				j_alert("error", json.message);
			}
			else {
				document.location.href = "index.php";
			}
		}
	});
};

$(function(){
	load_jalert();
})

</script>
<style>
li a {
	text-transform: uppercase;
	font-weight: bold
}
.group_head{
	width: 100%;
	padding-top: 5px; 
	padding-bottom: 5px; 
	text-align: center; 
	font-weight: bold; 
	background-color: #E7E7E7; 
	border-radius: 5px 5px 5px 5px
}
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
    background-image: url(../images/overlay.png);
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
	<div id="header">
		<div class="wrap">
			<?php include "header.php" ?>
		</div>
	</div>
	<div class="wrap">
	<div id="main" style="background-color: #FFFFFF; padding-bottom: 30px; width: 100%">
        <div style="width: 90%; margin: 20px auto 0 auto">
        	<p class="admin_title_row">Procedura guidata: prima installazione</p>
        	<?php if($step < 2){ ?>
        	<p>Benvenuto, admin! Se ti trovi in questa pagina, significa che l'installazione del software &egrave; terminata correttamente.</p>
        	<p>Prima per&ograve; che i tuoi utenti possano accedervi e utilizzarlo, sono necessarie alcune operazione, che solo gli utenti con i permessi
        	di amministrazione possono compiere. <br />Tutte queste operazioni sono disponibili dal menu di amministrazione (che puoi raggiungere in qualsiasi
        	momento utilizzando il link in fondo a questa pagina).<br/>
        	<?php } ?>
        	<?php include "wiz_first_install_inc{$step}.php" ?>
        	<div style="width: 100%; text-align: right; margin-top: 30px">
        	<?php if($step > 1){ ?>
        	<a href="wiz_first_install.php?step=<?php echo ($step - 1) ?>" style="float: left">Torna indietro</a>
        	<?php } ?>
        	<?php if($step != 5){ ?>
        	<a href="wiz_first_install.php?step=<?php echo ($step + 1) ?>" class="nav_link_first">Prosegui</a>|
        	<a href="index.php" class="nav_link_last">Vai al menu</a>
        	<?php } else { ?>
        	<a href="../shared/no_js.php" id="done_lnk" class="nav_link_last">Termina</a>
        	<?php } ?>
        	
        	</div>
        </div>
    </div>
	<?php include "footer.php" ?>
	</div>
	<div class="overlay" id="over1" style="display: none">
        <div id="wait_label" style="position: absolute; display: none; padding-top: 25px">Operazione in corso</div>
    </div>
</body>
</html>
