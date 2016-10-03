<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link href="../../css/general.css" rel="stylesheet" />
	<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var import_data = function(){
			var url = "import_students.php";

			background_process("Operazione in corso", 20, true);

			$.ajax({
				type: "POST",
				url: url,
				data: {file: $('#server_file').val(), school_order: $('#school_order').val()},
				dataType: 'json',
				error: function() {
					clearTimeout(bckg_timer);
					$('#background_msg').text("Errore di trasmissione dei dati");
					setTimeout(function() {
						$('#background_msg').dialog("close");
					}, 2000);
					console.log(json.dbg_message);
					//j_alert("error", "Errore di trasmissione dei dati");
				},
				succes: function() {

				},
				complete: function(data){
					clearTimeout(bckg_timer);
					r = data.responseText;
					if(r == "null"){
						return false;
					}
					var json = $.parseJSON(r);
					if (json.status == "kosql"){
						$('#background_msg').text(json.message);
						setTimeout(function() {
							$('#background_msg').dialog("close");
						}, 2000);
						console.log(json.dbg_message);
					}
					else {
						$('#load_info').text(json.ok);
						$('#tot').text(json.tot);
						$('#err').text(json.ko);
						$('#info_div').show();
						if(json.dbg_message){
							$('#errors_info').html(json.dbg_message+"===="+json.query);
						}
						$('#dw_link').attr("href", "../../shared/get_file.php?f="+json.log_path+"&delete=1&dir=tmp");
						$('#dw_link').show();
						loaded("Operazione conclusa");
					}
				}
			});
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#import_button').click(function(event){
				event.preventDefault();
				import_data();
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
    <form class="no_border">
    	<div style="width: 90%; margin: 0 auto 0 auto;">
    		<em>Questa funzione ti permette di importare tutti gli alunni con un solo comando, usando un file che contenga i dati nel formato richiesto.<br />
    		Tale formato DEVE essere preciso ed &egrave; facilmente ottenibile, se si hanno i dati in un file excel: basta esportare il file in formato
    		CSV, rispettando le indicazioni di sotto riportate. Ecco le caratteristiche:<br /></em>
    		<ul style="list-style-type: disc; margin-left: 20px">
    			<li>Ogni riga deve contenere le informazioni di un alunno</li>
    			<li>I campi devono essere separati tra loro con un carattere di punto e virgola (;)</li>
    			<li>I campi NON devono essere racchiusi da virgolette</li>
    			<li>L'ordine dei campi deve essere il seguente: </li>
    			<li style="margin-left: 20px">Cognome (obbligatorio)</li>
    			<li style="margin-left: 20px">Nome (obbligatorio)</li>
    			<li style="margin-left: 20px">Data di nascita (obbligatorio per la scuola primaria)</li>
    			<li style="margin-left: 20px">Luogo di nascita (obbligatorio per la scuola primaria)</li>
    			<li style="margin-left: 20px">Codice fiscale</li>
    			<li style="margin-left: 20px">Sesso (obbligatorio: nel formato M o F)</li>
    			<li style="margin-left: 20px">Ripetente (1 o 0)</li>
    			<li style="margin-left: 20px">Classe (nel formato di 2 lettere, ad esempio 2F, senza spazi)</li>
    			<li>In ogni riga devono essere presenti tutti i campi (ad eccezione dell'ultimo), quindi se non volete indicare i campi facoltativi (come ad esempio il codice fiscale),
    			lasciate il campo vuoto, senza spazi bianchi.<br />
    			Esempio di riga corretta (con i soli campi obbligatori): mario;rossi;;;M;0;2C<br />
    			Esempio di riga errata (con i soli campi obbligatori): mario;rossi;M;0;2C<br />
    			La prima riga, quella corretta, contiene 8 campi, mentre la seconda solamente 5: in questo modo i campi compaiono in ordine errato.</li>
    		</ul>
    	</div>
	    <div class="pop_label">Carica il file per l'importazione</div>
        <table class="admin_table">
            <tr class="admin_void" style="height: 60px">
	            <td class="popup_title" style="width: 150px; padding-left: 10px">File</td>
	            <td style="padding-right: 0px" colspan="2">
	            <iframe src="upload_file.php" style="border: none; width: 75%;  margin: 0px; height: 47px" id="aframe"></iframe>
	            <a href="#" onclick="del_file()" id="del_upl" style="float: right; display: none; text-decoration: none">Annulla upload</a>
	            </td>
	        </tr>
	        <tr class="admin_row">
	        	<td class="popup_title" style="width: 150px; padding-left: 10px">Ordine di scuola</td>
                <td colspan="2" class="admin_void">
                	<select id="school_order" id="school_order" style="width: 55%">
                	<?php 
                	while($row = $res_tipologie->fetch_assoc()){
                	?>
                	<option value="<?php echo $row['id_tipo'] ?>"><?php echo $row['tipo'] ?></option>
                	<?php } ?>
                	</select>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="admin_void"></td>
            </tr>
	        <tr>
                <td colspan="3" class="admin_void">Caricamento: <span id="info_div" style="font-weight: bold; display: none">inseriti <span id="load_info"></span> su <span id="tot"></span> con <span id="err"></span> errori</span></td>
            </tr>
            <tr>
                <td colspan="3" class="admin_void"></td>
            </tr>
            <tr>
                <td colspan="3" class="admin_void"><a href="" id="dw_link" style="display: none; font-weight: bold; font-size: 1.3em">Scarica il file con i dati</a></td>
            </tr>
            <tr>
                <td colspan="3" class="admin_void"></td>
            </tr>
            <tr>
                <td colspan="3" class="admin_void">Errori<div id="errors_info" style="color: red"></div></td>
            </tr>
            <tr>
                <td colspan="3" class="admin_void"></td>
            </tr>
        </table>
	    <div class="_right" style="margin: 20px 20px 0 0">
		    <input type="hidden" name="server_file" id="server_file" />
		    <a href="../../shared/no_js.php" id="import_button" class="material_link nav_link_first">Importa il file</a>
		    <a href="../index.php" class="material_link nav_link_last">Torna menu</a>
	    </div>
        </form>
        </div>
        <p class="spacer"></p>
	</div>
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="../../index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="../index.php"><img src="../../images/31.png" style="margin-right: 10px; position: relative; top: 5%" />Admin</a></div>
		<div class="drawer_link"><a href="https://www.istitutoiglesiasserraperdosa.gov.it"><img src="../../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
