<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link rel="stylesheet" href="../css/site_themes/blue_red/reg.css" type="text/css" />
<link rel="stylesheet" href="../css/skins/aqua/theme.css" type="text/css" />
<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/scriptaculous.js"></script>
<script type="text/javascript" src="../js/controls.js"></script>
<script type="text/javascript" src="../js/calendar.js"></script>
<script type="text/javascript" src="../js/lang/calendar-it.js"></script>
<script type="text/javascript" src="../js/calendar-setup.js"></script>
<script type="text/javascript" src="../js/page.js"></script>
<script type="text/javascript">
var year = <?php echo $y ?>;
var go = function(){
	//alert($('vacanze').value);
	var url = "school_year_manager.php?action=<?php echo $action ?>";
	
    req = new Ajax.Request(url,
	  {
	    	method:'post',
	    	parameters: $('myform').serialize(true),
	    	onSuccess: function(transport){
	    		var response = transport.responseText || "no response text";
	    		dati = response.split("#");
	    		if(dati[0] == "kosql"){
		    		sqlalert();
	    			console.log("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
	    			return;
	    		}
	    		else{
					alert("Anno scolastico creato o modificato con successo");
					document.location.href = "index.php";
	    		}
	    	},
	    	onFailure: function(){ alert("Si e' verificato un errore..."); }
	  });
};

var load_default = function(load_on_update){
	if(load_on_update){
		<?php if ($action != "new"): ?>
		$('data_inizio').value = '<?php print format_date($year->get_data_apertura(), SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>';
		$('data_fine').value = '<?php print format_date($year->get_data_chiusura(), SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>';
		<?php endif; ?>
	}
	else{
		$('data_inizio').value = '01/09/'+year;
		$('data_fine').value = '31/08/'+(year+1);
	}
};


</script>
<title>Gestione anno scolastico</title>
<style>
input {
	font-size: 11px
}
</style>
</head>
<body onload="load_default(true); ">
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "adm_school/menu.php" ?>
	</div>
	<div id="left_col">
	<div class="group_head">Gestione anni scolastici</div>
	<form method="post" id="myform" class="popup_form">
		<table style="width: 90%; margin-right: auto; margin-left: auto; margin-bottom: 20px;">
            <tr>
            	<td colspan="2">&nbsp;</td>
            </tr>
           	<tr>
                <td style="width: 40%; font-weight: normal" class="">Data inizio anno scolastico</td>
                <td style="width: 60%; color: #003366"><input type="text" name="data_inizio" id="data_inizio" style="width: 100%" readonly="readonly" /></td>
	        </tr>
			<tr>
                <td style="width: 40%; padding-left: 10px; font-weight: normal" class="">Data fine anno scolastico</td>
                <td style="width: 60%; color: #003366"><input type="text" name="data_fine" id="data_fine" style="width: 100%" readonly="readonly" /></td>
            </tr>
            <tr>
            	<td colspan="2">
            	<script type="text/javascript">
				Calendar.setup({
					date		: new Date(year, 8),
					inputField	: "data_inizio",
					ifFormat	: "%d/%m/%Y",
					showsTime	: false,
					firstDay	: 1,
					timeFormat	: "24"
				});
				Calendar.setup({
					date		: new Date(year+1, 7),
					inputField	: "data_fine",
					ifFormat	: "%d/%m/%Y",
					showsTime	: false,
					firstDay	: 1,
					timeFormat	: "24"
				});
				</script>
            	</td>
            </tr>
			<tr>
                <td style="padding-top: 20px; text-align: right" colspan="2">
                	<a href="#" onclick="go()" class="standard_link nav_link">Registra</a>
                </td>
			</tr>
		</table>
		</form>
		<p class="spacer"></p>
	</div>
</div>
<?php include "footer.php" ?>
</body>
</html>
