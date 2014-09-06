<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link href="../../css/general.css" rel="stylesheet" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript">
var tm = 0;
var complete = false;
var timer;

var import_data = function(){
	var url = "import_students.php";
	
	leftS = (screen.width - 200) / 2;
	$('wait_label').setStyle({left: leftS+"px"});
	$('wait_label').setStyle({top: "300px"});
	$('over1').show();
	$('wait_label').appear({duration: 0.8});
	//$('wait_label').show();
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {file: $F('server_file'), school_order: $F('school_order')},
			    	onSuccess: function(transport){
			    		complete = true;
				    	clearTimeout(timer);
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split(";");
			      		if(dati[0] == "ok"){
			      			$('load_info').update(dati[1]);
				      		$('tot').update(dati[2]);
				      		$('err').update(dati[3]);
				      		$('info_div').show();
				      		if(dati[4]){
								$('errors_info').innerHTML += dati[4];
				      		}
				      		$('dw_link').setAttribute("href", "../../shared/get_file.php?f="+dati[5]+"&delete=1&dir="+dati[6]);
				      		$('dw_link').show();
				      		$('wait_label').update("Operazione conclusa");
							setTimeout("$('wait_label').fade({duration: 2.0})", 2000);
							//setTimeout("$('wait_label').hide()", 2000);
							setTimeout("$('over1').hide()", 3800);
			      		}
			      		
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
	upd_str();
};

var upd_str = function(){
	tm++;
	//alert(tm);
	if(tm > 5){ 
		tm = 0;
		$('wait_label').update("Caricamento in corso");
	}
	else
		$('wait_label').innerHTML += ".";
	timer = setTimeout("upd_str()", 1000);
};

var _hide = function(){
	$('over1').hide();
	$('wait_label').hide();
};

document.observe("dom:loaded", function(){
	$('import_button').observe("click", function(event){
		event.preventDefault();
		import_data();
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
    background-image: url(../../images/overlay.png);
    position: absolute;
    top: 0px;
    left: 0px;
    z-index: 90;
    width: 100%;
    height: 100%;
}
.group_head{
	padding-top: 5px; 
	padding-bottom: 5px; 
	text-align: center; 
	font-weight: bold; 
	background-color: #E7E7E7; 
	border-radius: 5px 5px 5px 5px
}
</style>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "../new_year_menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Importazione alunni</div>
    <form>
    	<div style="width: 90%; margin: 0px auto 0 auto;">
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
	    <div class="group_head">Carica il file per l'importazione</div>
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
            <tr class="admin_menu">
                <td colspan="3" align="right">
                	<input type="hidden" name="server_file" id="server_file" />
                	<a href="../../shared/no_js.php" id="import_button" class="standard_link nav_link_first">Importa il file</a>|
                	<a href="../index.php" class="standard_link nav_link_last">Torna menu</a>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="admin_void"></td>
            </tr>
        </table>
        </form>
        </div>
        <p class="spacer"></p>
	</div>
	<div class="overlay" id="over1" style="display: none">
        <div id="wait_label" style="position: absolute; display: none; padding-top: 25px">Caricamento dati in corso</div>
    </div>
<?php include "../footer.php" ?>
</body>
</html>
