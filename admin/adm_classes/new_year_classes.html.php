<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="author" content="" />
<link href="../../css/general.css" rel="stylesheet" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
var step = <?php echo $_SESSION['__new_classes_step__'] ?>;

var cancella_terze = function(){
	if(step > 1){
		alert("Funzione completata");
		$('#step'+(step+1)).css({backgroundColor: '#FAF6B7'});
		return false;
	}
	if(!confirm("Questa procedura eliminerà tutte le classi terminali, cancellando dall'archivio tutti gli alunni non segnalati come ripetenti. Vuoi continuare?")){
		return false;
	}
	var url = "cancella_classi_terminali.php";
	t_step = $('#lnk2').attr("step");
	$.ajax({
		type: "POST",
		url: url,
		data: {school_order: <?php echo $school_order ?>, t_step: t_step},
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
			else if (json.status == "wrong_step"){
				alert(json.message);
				$('#step'+(step+1)).css({backgroundColor: '#FAF6B7'});
			}
			else {
				step = 2;
				alert("Cancellazione completata");
				$('#step'+(step+1)).css({backgroundColor: '#FAF6B7'});
			}
		}
	});
};

var avanza = function(){
	t_step = $('#lnk3').attr("step");
	var url = "avanza_classi.php";
	$.ajax({
		type: "POST",
		url: url,
		data: {school_order: <?php echo $school_order ?>, t_step: t_step},
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
			else if (json.status == "wrong_step"){
				alert(json.message);
				$('#step3').css({backgroundColor: ''});
				$('#step'+(step+1)).css({backgroundColor: '#FAF6B7'});
			}
			else {
				step = 3;
				alert("Attivazione completata");
				$('#step3').css({backgroundColor: ''});
				$('#step'+(step+1)).css({backgroundColor: '#FAF6B7'});
			}
		}
	});
};

$(function(){
	$('table tbody a').mouseover(function(event){
		$('#'+this.id).css({color: '#8a1818', fontWeight: 'bold'});
	});
	$('table tbody a').mouseout(function(event){
		//alert(this.id);
		$('#'+this.id).css({color: '', fontWeight: 'normal'});
	});
	$('#lnk2').click(function(event){
		event.preventDefault();
		cancella_terze();
	});
	$('#lnk3').click(function(event){
		event.preventDefault();
		avanza();
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
		<div class="group_head">Gestione classi per nuovo anno scolastico: <?php echo $cl_label ?></div>
		<table class="admin_table">
        <thead>
        </thead>
        <tbody>
        	<tr class="no_border">
            	<td colspan="2" style="" class="title_row _center no_border">Classi <?php echo $term_cls ?> uscenti</td>
            </tr>
            <tr class="admin_row_before_text">
                <td colspan="2"></td>
            </tr>
            <tr class="admin_row" id="step1">
	            <td style="width: 30%"><a href="ripetenti.php?school_order=<?php echo $school_order ?>" step="1" id="rip">Segnala ripetenti</a></td>
	            <td style="width: 70%">Segnala tutti i ripetenti delle classi <?php echo $term_cls ?>, per poi assegnarli a nuove classi. Tutti gli alunni non segnalati verranno cancellati dall'archivio.</td>
            </tr>
            <tr class="admin_row" id="step2">
	            <td style="width: 30%"><a href="../../shared/no_js.php" id="lnk2" step="2">Cancella classi</a></td>
	            <td style="width: 70%">Elimina dall'archivio le classi <?php echo $term_cls ?>: questa operazione &egrave; necessaria per permettere l'attivazione delle nuove <?php echo $term_cls ?>.</td>
            </tr>
            <tr class="admin_void">
                <td colspan="2"></td>
            </tr>
            <tr>
            	<td colspan="2" style="" class="title_row _center">Nuove classi</td>
            </tr>
            <tr class="admin_row_before_text">
                <td colspan="2"></td>
            </tr>
            <tr class="admin_row" id="step3">
	            <td style="width: 30%"><a href="../../shared/no_js.php" id="lnk3" step="3">Attivazione nuove classi </a></td>
	            <td style="width: 70%">Avanza tutte le classi: questa operazione aggiorna automaticamente i dati degli alunni.</td>
            </tr>
	        <tr class="admin_row" id="step4">
		        <td style="width: 30%"><a href="nuove_prime.php?school_order=<?php echo $school_order ?>" step="4" id="npc">Crea classi prime</a></td>
		        <td style="width: 70%">Crea le nuove classi prime, alle quali potrai in seguito assegnare gli alunni. </td>
	        </tr>
            <tr class="admin_void">
                <td colspan="2"></td>
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
	<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
</body>
</html>
