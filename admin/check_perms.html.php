<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="../css/reg.css" type="text/css" />
	<link rel="stylesheet" href="../css/general.css" type="text/css" />
<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/page.js"></script>
<script type="text/javascript">

var check = function(uid, name){
	url = "permission_check.php";
	
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {uid: uid},
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
			    		dati = response.split("#");
			    		if(dati[0] == "kosql"){
			    			sqlalert();
			    			console.log("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
			    			return;
			    		}
			    		else{
				    		// reset
				    		for(k = 1; k <= <?php echo $groups->num_rows ?>; k++){
								$('gr_'+k).update("NO");
				    		}
				    		$('is_admin').update("NO");
				    		$('is_ps_admin').update("NO");
				    		$('is_ms_admin').update("NO");

				    		$('us_label').update(name);
				    		gids = dati[0].split(",");
							for(i = 0; i < gids.length; i++){
								$('gr_'+gids[i]).update("SI");
							}
							if(dati[1] == 1){
								$('is_admin').update("SI");
							}
							if(dati[2] == 1){
								$('is_ps_admin').update("SI");
							}
							if(dati[3] == 1){
								$('is_ms_admin').update("SI");
							}
							$('panel').show();
			    		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};
</script>
<title>Verifica permessi utente</title>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "dev_menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Verifica permessi utente</div>
    <form>
        <table class="admin_table">
        <thead>
            <tr class="admin_void" style="border: 0; height: 5px">
                <td colspan="5"></td>
            </tr>
            </thead>
            <tbody>
            <tr class="admin_row" style="height: 20px">
            <?php 
            $x = 1;
            while($utente = $res_utenti->fetch_assoc()){
            ?>
                <td style="width: 20%; vertical-align: middle"><a href="#" onclick="check(<?php  print $utente['id'] ?>, '<?php print $utente['cognome']." ".$utente['nome'] ?>')" title="<?php  print $utente['id'] ?>" style="text-decoration: none;"><?php print $utente['cognome']." ".$utente['nome'] ?></a></td>
            <?php 
            	if($x%5 == 0){
            		print("</tr>\n\t<tr class='admin_row' style='height: 20px'>\n");
            	}
            	$x++;
            }
            ?>
            </tr>
            <tr id="panel" class="admin_row" style="border: 0; height: 5px; display: none">
                <td colspan="5">
                <p id="us_label" style="font-weight: bold"></p>
                	<?php 
                	while($g = $groups->fetch_assoc()){
                	?>
                	<p style="margin: 0"><?php echo $g['nome'] ?>: <span id="gr_<?php echo $g['gid'] ?>" style="font-weight: bold">NO</span></p>
                	<?php } ?>
                	<p style="margin: 0">isAdministrator: <span id="is_admin" style="font-weight: bold">NO</span></p>
                	<p style="margin: 0">isPrimarySchoolAdministrator: <span id="is_ps_admin" style="font-weight: bold">NO</span></p>
                	<p style="margin: 0">isMiddleSchoolAdministrator: <span id="is_ms_admin" style="font-weight: bold">NO</span></p>
                </td>
            </tr>
            </tbody>
            <tfoot>
            <tr class="admin_void">
                <td colspan="5"></td>
            </tr>
            </tfoot>
        </table>
    </form>
    </div>	
	<p class="spacer"></p>
	</div>
<?php include "footer.php" ?>
</body>
</html>