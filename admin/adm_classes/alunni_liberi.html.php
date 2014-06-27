<!DOCTYPE html>
<html>
<head>
<title>Elenco alunni da assegnare alle classi</title>
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
function upd_cls(sel, student){
	var url = "update_class.php";
    var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {cls: sel.value, stud_id: student},
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
				    	//alert(response);
			    		dati = response.split(";");
			    		if(dati[0] == "ok"){
							$('tr_'+student).hide();
							var st_count = parseInt($('st_count').innerHTML);
							$('st_count').update(--st_count);
			            }
			    		else if(dati[0] == "kosql"){
				            sqlalert();
			                console.log("Aggiornamento non riuscito. Query: "+dati[1]+"\nErrore: "+dati[2]);
			                return;
			            }
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
	  //alert(sel.parentNode.parentNode);
	  sel.parentNode.parentNode.setStyle({backgroundColor: 'rgba(200, 200, 200, .3)'});
}

document.observe("dom:loaded", function(event){
	$$('.alpha_lnk').invoke("observe", "mouseover", function(event){
		this.setStyle({cursor: 'pointer'});
	});
	$$('.alpha_lnk').invoke("observe", "click", function(event){
		document.location.href = 'alunni_liberi.php?lettera='+this.innerHTML;
	});
});

</script>
<style>
#alpha_row {
	border-top: 1px solid #CCCCCC;
	border-bottom: 1px solid #CCCCCC;
	text-align: center;
}
#alpha_row span {
	margin-right: 10px;
}

table tbody tr:hover {
	background-color: #FAF6B7;
}
</style>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Elenco alunni non assegnati alle classi (estratti <span id="st_count"><?php echo $res_alunni->num_rows ?></span> alunni)</div>
		<form method="post">
        <table class="admin_table">
        <thead>
            <tr class="admin_void">
                <td colspan="3"></td>
            </tr>
            <tr class="admin_void">
                <td colspan="3" id="alpha_row">
                <?php 
		        foreach($alpha as $a){
		        ?>
		        <span class="alpha_lnk"><?php echo $a ?></span>
		        <?php 
		        }
		        ?>
                </td>
            </tr>
            <tr class="admin_void">
                <td colspan="3"></td>
            </tr>
            <tr>
            	<td style="padding-left: 2px; width: 5%" class="adm_titolo_elenco_first"></td>
                <td style="padding-left: 2px; width: 75%" class="adm_titolo_elenco">Alunno</td>
                <td style="padding-left: 10px; width: 20%" class="adm_titolo_elenco_last">Classe</td>
            </tr>
            <tr class="admin_void">
                <td colspan="3"></td>
            </tr>
        </thead>
        <tbody>
            <?php
            $x = 1;
            while($stud = $res_alunni->fetch_assoc()){               
            ?>
            <tr class="admin_row" style="height: 20px" id="tr_<?php echo $stud['id_alunno'] ?>">
            	<td style="padding-right: 12px; color: #003366; text-align: right"><?php echo $x ?>.</td>
                <td style="padding-left: 2px; color: #003366; text-align: left"><?php echo $stud['cognome']." ".$stud['nome'] ?></td>
                <td style="color: #003366;">
                <select name="cls" id="cls" style="width: 95%; font-size: 11px; border: 1px solid #CCCCCC" onchange="upd_cls(this, <?php echo $stud['id_alunno'] ?>)">
                	<option value="0" selected="selected" >Seleziona</option>
                <?php
                $res_classi->data_seek(0);
                while($_class = $res_classi->fetch_assoc()){
                ?>
                	<option value="<?php echo $_class['id_classe'].";".$_class['ordine_di_scuola'] ?>" <?php if($_class['id_classe'] == $_REQUEST['id_classe']) echo("selected") ?> ><?php echo $_class['classe']." - ".$_class['nome'] ?></option>
                <?php 
                }
                ?>
                </select>
                </td>
            </tr>
            <?php
            	$x++;
            }
            ?>
            </tbody>
            <tfoot>
            <tr class="admin_void">
                <td colspan="3"></td>
            </tr>
            <tr class="admin_menu">
                <td colspan="3">
                	<a href="classi.php" class="nav_link_first">Vai alle classi</a>|
                    <a href="../index.php" class="nav_link_last">Torna al menu</a>
                </td>
            </tr>
            <tr class="admin_void">
                <td colspan="3"></td>
            </tr>
            </tfoot>
        </table>
        </form>
        </div>
        <p class="spacer"></p>
	</div>
<?php include "../footer.php" ?>
</body>
</html>