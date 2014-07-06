<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="../css/reg.css" type="text/css" />
	<link rel="stylesheet" href="../css/general.css" type="text/css" />
	<link href="../css/themes/default.css" rel="stylesheet" type="text/css"/>
	<link href="../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
	<link href="../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="../js/prototype.js"></script>
	<script type="text/javascript" src="../js/scriptaculous.js"></script>
	<script type="text/javascript" src="../js/controls.js"></script>
	<script type="text/javascript" src="../js/page.js"></script>
	<script type="text/javascript" src="../js/window.js"></script>
	<script type="text/javascript">
		var win;
		var msg;
		function gruppo(gr){
			document.location.href = "scegli_utente.php?gruppo="+gr;
		}

		var su_do = function(area, uid){
			document.location.href = "sudo_manager.php?area="+area+"&uid="+uid+"&action=sudo";
		}
	</script>
	<style>
		tbody td:hover {background-color: #e8f2fe }
	</style>
	<title>SuDo</title>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "adm_users/menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Seleziona utente</div>
		<form>
			<table class="admin_table">
				<thead>
				<tr class="admin_row" style="border: 0">
					<td colspan="4" style="text-align: center">
						<a href="#" onclick="gruppo(3)" style="">Personale della scuola</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
						<a href="#" onclick="gruppo(2)" style="">Studenti</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
						<a href="#" onclick="gruppo(1)" style="">Genitori</a>
					</td>
				</tr>
				<tr class="admin_void" style="border: 0; height: 5px">
					<td colspan="4"></td>
				</tr>
				<?php
				if($gruppo == 2){
					?>
					<tr style="vertical-align: middle; text-align: center; height: 20px; background-color: #E7E7E7">
						<td colspan="4" style="border: 0; border-radius: 3px 3px 3px 3px; ">
							<?php
							for($i = 0; $i < count($alfabeto); $i++){
								if(isset($_REQUEST['start']) && $_REQUEST['start'] == $alfabeto[$i]){
									?>
									<span>[&nbsp;<?php print $alfabeto[$i] ?>&nbsp;]</span>
								<?php
								}
								else{
									?>
									<a style="text-decoration: none" href="scegli_utente.php?gruppo=<?php print $gruppo?>&start=<?php print $alfabeto[$i] ?>">&nbsp;&nbsp;<?php print $alfabeto[$i] ?>&nbsp;&nbsp;</a>
								<?php
								}
							}
							?>
							<a style="text-decoration: none" href="#" onclick="gruppo(<?php print $gruppo?>)">&nbsp;&nbsp;Tutti&nbsp;&nbsp;</a>
						</td>
					</tr>
					<tr class="admin_void">
						<td colspan="4"></td>
					</tr>
				<?php
				}
				?>
				</thead>
				<tbody>
				<tr class="admin_row" style="height: 20px">
					<?php
					$x = 1;
					while($utente = $res_utenti->fetch_assoc()){
						?>
						<td style="width: 20%; vertical-align: middle"><a href="#" onclick="su_do(<?php print $gruppo ?>, <?php  print $utente['id'] ?>)" title="<?php  print $utente['id'] ?>" style="text-decoration: none;"><?php print $utente['cognome']." ".$utente['nome'] ?></a></td>
						<?php
						if($x%4 == 0){
							print("</tr>\n\t<tr class='admin_row' style='height: 20px'>\n");
						}
						$x++;
					}
					?>
				</tr>
				</tbody>
				<tfoot>
				<tr class="admin_void">
					<td colspan="4"></td>
				</tr>
				<tr class="admin_void">
					<td colspan="4">&nbsp;&nbsp;&nbsp;</td>
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