<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>SuDo</title>
	<link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../js/page.js"></script>
	<script type="text/javascript">
		$(function (){
			load_jalert();
			setOverlayEvent();
		});
		function gruppo(gr){
			document.location.href = "scegli_utente.php?gruppo="+gr<?php if(isset($_REQUEST['school_order'])) echo '+"&school_order='.$_REQUEST['school_order'].'"'; ?>;
		}

		var su_do = function(area, uid){
			document.location.href = "sudo_manager.php?area="+area+"&uid="+uid+"&action=sudo";
		}
	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "dev_menu.php" ?>
	</div>
	<div id="left_col">
		<div class="navigate">
			<a href="#" onclick="gruppo(3)" style="margin-right: 10px">Personale della scuola</a>
			<?php if ($admin_level == 0) : ?>
			<a href="#" onclick="gruppo(2)" style="margin: 0 10px 0 10px">Studenti</a>
			<?php endif; ?>
			<a href="#" onclick="gruppo(1)" style="margin-left: 10px">Genitori</a>
		</div>
		<form class="no_border">
			<table class="admin_table">
				<thead>
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
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="../index.php"><img src="../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="index.php"><img src="../images/31.png" style="margin-right: 10px; position: relative; top: 5%" />Admin</a></div>
		<div class="drawer_link"><a href="http://www.istitutoiglesiasserraperdosa.it"><img src="../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../shared/do_logout.php"><img src="../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
