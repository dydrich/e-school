<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>Elenco alunni ripetenti non assegnati</title>
	<link href="../../css/reg.css" rel="stylesheet" />
	<link href="../../css/general.css" rel="stylesheet" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
function upd_cls(sel, student, row){
	var url = "update_class.php";
    var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {cls: sel.value, stud_id: student},
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
				    	dati = response.split(";");
			    		if(dati[0] == "ok"){
							//sel.parentNode.parentNode.setStyle({display: 'none'});
			            }
			            else if(dati[0] == "kosql"){
				            sqlalert();
			                console.log("Aggiornamento non riuscito. Query: "+dati[1]+"\nErrore: "+dati[2]);
			                return;
			            }
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}
</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Elenco alunni ripetenti da assegnare (estratti <?php echo $res_alunni->num_rows ?> alunni)</div>
		<form method="post">
        <table class="admin_table">
        <thead>
            <tr class="admin_void">
                <td colspan="3"></td>
            </tr>
        </thead>
        <tbody>
            <?php
            $x = 1;
            while($stud = $res_alunni->fetch_assoc()){
                
            ?>
            <tr class="admin_row" style="height: 20px">
            	<td style="padding-right: 12px; color: #003366; text-align: right"><?php print $x ?>.</td>
                <td style="padding-left: 2px; color: #003366; text-align: left"><?php print $stud['cognome']." ".$stud['nome'] ?></td>
                <td style="color: #003366;">
                <select name="cls<?php echo $stud['id_alunno']  ?>" id="cls<?php echo $stud['id_alunno']  ?>" style="width: 95%; font-size: 11px; border: 1px solid #CCCCCC" onchange="upd_cls(this, <?php print $stud['id_alunno'] ?>, <?php print $x ?>, '<?php print $stud['classe'] ?>')">
                	<option value="0">Seleziona</option>
                <?php
                $res_classi->data_seek(0);
                while($_class = $res_classi->fetch_assoc()){
                ?>
                	<option value="<?php print $_class['id_classe'].";".$_class['ordine_di_scuola'] ?>"><?php print $_class['classe']." - ".$_class['nome'] ?></option>
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
                    <a href="../index.php">Torna al menu</a>
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