<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Elenco ripetenti per assegnazione alle classi</title>
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
var shown_up = '<?= $first ?>';
var show_class = function(cls){
	$('tb'+shown_up).setStyle({display: 'none'});
	$('tb'+cls).setStyle({display: ''});
	shown_up = cls;
};

document.observe("dom:loaded", function(){
	$$('input[type=checkbox]').invoke("observe", "change", function(event){
		upd_student(this.value, this.checked);
	});
	$('close_lnk').observe("click", function(event){
		event.preventDefault();
		close_step();
	});
});

var upd_student = function(student, checked){
	var url = "check_ripetente.php";
    req = new Ajax.Request(url,
		  {
		    	method:'post',
		    	parameters: {alunno: student, checked: checked},
		    	onSuccess: function(transport){
		    		var response = transport.responseText || "no response text";
		    		//alert(response);
		    		dati = response.split(";");
		    		if(dati[0] != "ko"){
						//alert(response);
		            }
		            else{
		                alert("Aggiornamento non riuscito. Query: "+dati[1]+"\nErrore: "+dati[2]);
		                return;
		            }
		    	},
		    	onFailure: function(){ alert("Si e' verificato un errore...") }
		  });
};

var close_step = function(){
	var url = "aggiorna_stato.php";
    req = new Ajax.Request(url,
		  {
		    	method:'post',
		    	parameters: {step: 1},
		    	onSuccess: function(transport){
		    		var response = transport.responseText || "no response text";
		    		//alert(response);
		    		dati = response.split(";");
		    		if(dati[0] != "ko"){
						document.location.href = "new_year_classes.php";
		            }
		            else{
		                alert("Aggiornamento non riuscito. Query: "+dati[1]+"\nErrore: "+dati[2]);
		                return;
		            }
		    	},
		    	onFailure: function(){ alert("Si e' verificato un errore..."); }
		  });
};

</script>
<style>
tbody tr:hover {
	background-color: #FAF6B7;
}
</style>
</head>
<body>
    <div id="header">
		<div class="wrap">
			<?php include "../header.php" ?>
		</div>
	</div>
	<div class="wrap">
	<div id="main" style="background-color: #FFFFFF; padding-bottom: 30px; width: 100%">
        <form method="post">
        <div style="width: 95%; margin: auto; text-align: center">[ 
        <?php 
        foreach($classi as $cls){
        ?>
        	<a href="#" onclick="show_class('<?= $cls ?>')" style="margin: 0 5px 0 5px"><?= $cls ?></a>
        <?php 
        }
        ?>
         ]</div>
        <?php 
        while(list($k, $classe) = each($alunni)){
        ?>
        <table class="admin_table" id="tb<?= $k ?>" style="<?php if($k != $first) print("display: none") ?>">
        <thead>
            <tr class="admin_title_row">
                <td colspan="2">Elenco alunni classe <?= $k ?></td>
            </tr>
            <tr>
            	<td style="padding-left: 10px; width: 75%" class="adm_titolo_elenco_first">Alunno</td>
                <td style="width: 25%" class="adm_titolo_elenco_last _center">Ripetente</td>
                
            </tr>
            <tr class="admin_row_before_text">
                <td colspan="2"></td>
            </tr>
        </thead>
        <tbody>
        <?php 
        foreach ($classe as $al){
        ?>
        	<tr style="border-bottom: 1px solid #CCCCCC">
            	<td style="padding-left: 10px; width: 75%"><?= $al['cognome']." ".$al['nome'] ?></td>
                <td style="width: 25%" class="_center">
                	<input type="checkbox" name="al<?= $al['id_alunno'] ?>" value="<?= $al['id_alunno'] ?>" <?php if($al['ripetente'] == 1) print "checked" ?> />
                </td>
            </tr>
        <?php 
        }
        ?>
        </tbody>
        <tfoot>
            <tr class="admin_void">
                <td colspan="2">&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr class="admin_menu">
                <td colspan="2">
                	<a href="new_year_classes.php" class="nav_link_first">Torna indietro</a>|
                	<a href="../../shared/no_js.php" id="close_lnk" class="nav_link_last">Concludi prima fase</a>
                </td>
            </tr>
        </tfoot>
        </table>
        <?php 
        }
        ?>
        </form>
        </div>
        <?php include "../footer.php" ?>
	</div>
</body>
</html>