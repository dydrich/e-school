<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link href="../../../css/skins/aqua/theme.css" type="text/css" rel="stylesheet"  />
<link href="../../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../../js/prototype.js"></script>
<script type="text/javascript" src="../../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript" src="../../../js/window.js"></script>
<script type="text/javascript" src="../../../js/window_effects.js"></script>
<script type="text/javascript" src="../../../js/calendar.js"></script>
<script type="text/javascript" src="../../../js/lang/calendar-it.js"></script>
<script type="text/javascript" src="../../../js/calendar-setup.js"></script>
<script type="text/javascript">
var y = <?php echo $year ?>;
var q = <?php echo $q ?>;
var search = function(){
	if(($F('cognome').empty())){
		alert("E' obbligatorio indicare il cognome");
		yellow_fade("tr_cognome");
		return false;
	}
	var url = "../../manager/report_manager.php";
	var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {y: y, cls: <?php echo $_SESSION['__classe__']->get_ID() ?>, lname: $F('cognome'), q: q, action: "search"},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
						if(response.substr(0, 5) == "kosql"){
			      			var dati = response.split("#");
						
				      		if(dati[0] == "kosql"){
								sqlalert();
								console.log(dati[1]+"\n"+dati[2]);
								return false;
				     		}
						}
						else if(response.substr(0, 5) == "nostd"){
							var dati = response.split("#");
							//alert(dati[1]);
							$('container').update("Nessun alunno in archivio per i parametri richiesti");
						}
			     		else{
				     		//alert(response);
				     		var json = response.evalJSON();
				     		var print_string = "";
				     		for(data in json){
					     		var t = json[data];
					     		//alert(t.del);
					     		if (t.del == 1){ 
					     			//print_string += "<p><a href='#' onclick='dwld_file(\"../../../lib/download_manager.php?dw_type=report&f="+t.file+"&sess=1&stid="+t.id+"&y=<?php echo $_SESSION['__current_year__']->get_ID() ?>&noread=1&delete=1\")' style=''>"+t.nome+" (1 quadrimestre)</a></p>";
					     			print_string += "<p><a href='#' onclick='dwld_file(\"../../../modules/documents/download_manager.php?doc=report&school_order=<?php echo $school ?>&area=teachers&f="+t.file+"&sess=1&stid="+t.id+"&y=<?php echo $_SESSION['__current_year__']->get_ID() ?>&noread=1&delete=1\")' style=''>"+t.nome+" (1 quadrimestre)</a></p>";
					     		}
					     		else {
					     			//print_string += "<p><a href='../../../lib/download_manager.php?dw_type=report&f="+t.file+"&sess=2&stid="+t.id+"&y=<?php echo $_SESSION['__current_year__']->get_ID() ?>&noread=1' style=''>"+t.nome+" (2 quadrimestre)</a></p>";
					     			print_string += "<p><a href='../../../modules/documents/download_manager.php?doc=report&school_order=<?php echo $school ?>&area=teachers&f="+t.file+"&sess=2&stid="+t.id+"&y=<?php echo $_SESSION['__current_year__']->get_ID() ?>&noread=1' style=''>"+t.nome+" (2 quadrimestre)</a></p>";
					     		}
				     		}
				     		$('container').update(print_string);
			     		}
			     		
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};

var dwld_file = function(href){
	document.location = href;
	$('container').update('');
};

document.observe("dom:loaded", function(){
	$('search_lnk').observe("click", function(event){
		event.preventDefault();
		search();
	});
});	

</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "class_working.php" ?>
</div>
<div id="left_col">
	<div class="group_head">
		Pagelle online
	</div>
	<form class="reg_form" id="search_form" style="height: 150px;">
	<div style="width: 45%; margin-left: 20px; float: left">
		<table style="width: 95%">
			<tr id="tr_cognome">
				<td style="width: 40%">Cognome</td>
				<td style="width: 60%">
					<input type="text" id="cognome" style="width: 95%" autofocus />
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding-top: 20px"><a href="../../shared/no_js.php" id="search_lnk" style="text-decoration: none; text-transform: uppercase">Cerca la pagella</a></td>
			</tr>
		</table>
	</div>
	<div style="float: right; width: 45%" id="container"></div>
	</form>
</div>
<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
</body>
</html>
