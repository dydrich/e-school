<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Docenti</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
		});
	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include $_SESSION['__administration_group__']."/navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include $_SESSION['__administration_group__']."/menu.php" ?>
</div>
<div id="left_col">
   		<table style="width: 95%; margin: 0 auto 0 auto">
	 	    <?php
	 	    if ($res_sos->num_rows < 1){
			?>
			<tr>
				<td colspan="5" style="height: 55px; font-weight: bold; text-align: center; font-size: 1.1em">Nessun docente trovato</td>
			</tr> 	
			<?php
			}
			else {
	 	    	foreach ($sos as $k => $docente){
					$classi = implode(", ", $docente['classi']);
					$sel_stud = "SELECT cognome, nome FROM rb_alunni, rb_assegnazione_sostegno WHERE anno = {$anno} AND id_alunno = alunno AND docente = {$k} ORDER BY cognome, nome";
					$res_stud = $db->executeQuery($sel_stud);
					$studenti = array();
					while ($row = $res_stud->fetch_assoc()){
						$studenti[] = $row['cognome']." ".$row['nome'];
					}
					$stds = implode(", ", $studenti);
	 	    ?>
 	    	<tr id="row<?php echo $k ?>" class="list_row">
 	    		<td style="width: 30%; text-align: left"><a href="docente_sostegno.php?did=<?php echo $k ?>" style="text-decoration: none"><?php echo $docente['nome'] ?></a></td>
 	    		<td style="width: 20%; text-align: center"><?php echo $classi ?></td>
 	    		<td style="width: 50%; text-align: center"><?php echo $stds ?></td>	
 	    	</tr>
	 	    
	 	    <?php
	 	    	}
	 	    }
            ?>
            <tr>
	    		<td colspan="5" style="height: 25px"></td> 
		    </tr>
		</table>		
	</div>
<p class="spacer"></p>		
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
		<div class="drawer_link"><a href="utility.php"><img src="../../images/59.png" style="margin-right: 10px; position: relative; top: 5%" />Utility</a></div>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
