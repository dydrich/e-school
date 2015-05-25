<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Alunni</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/jquery.jeditable.mini.js"></script>
	<script>
		$(function(){
			load_jalert();
			setOverlayEvent();
			$(function(){
				$('.edit').editable('ore_sostegno.php', {
					indicator : 'Saving...',
					tooltip   : 'Click to edit...',
					cssclass: "no_border"
				});
			});
		});

	var del = function(id){
		var url = "elimina_segnalazione.php";
		$.ajax({
			type: "POST",
			url: url,
			data: {id: id},
			error: function() {
				j_alert("error", "Errore di trasmissione dei dati");
			},
			succes: function() {

			},
			complete: function(data){
				r = data.responseText;
				if(r == "null"){
					return false;
				}
				var json = $.parseJSON(r);
				if (json.status == "kosql"){
					sqlalert();
					console.log(json.dbg_message);
					return;
				}
				else if(json.status == "ko") {
					j_alert("error", "Impossibile completare l'operazione richiesta. Riprovare tra qualche secondo o segnalare l'errore al webmaster");
					return;
				}
				else {
					$('#row'+id).hide();
				}
			}
		});
	};
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
	<div style="position: absolute; top: 75px; margin-left: 645px; margin-bottom: 0; z-index: 100" class="rb_button">
		<a href="segnala_alunno.php">
			<img src="../../images/39.png" style="padding: 12px 0 0 12px" />
		</a>
	</div>
   		<table style="width: 95%; margin: 10px auto 0 auto">
	 	    <?php
	 	    if ($res_sos->num_rows < 1){
			?>
			<tr>
				<td colspan="5" style="height: 55px; font-weight: bold; text-align: center; font-size: 1.1em">Nessun alunno trovato</td>
			</tr> 	
			<?php
			}
			else {
	 	    	while($alunno = $res_sos->fetch_assoc()){
					$teachs = array();
					$teacher = "Non assegnato";
					$sel_teach = "SELECT cognome, nome FROM rb_utenti, rb_assegnazione_sostegno WHERE uid = docente AND anno = {$anno} AND alunno = {$alunno['alunno']} ORDER BY cognome, nome";
					$res_teach = $db->execute($sel_teach);
					if ($res_teach->num_rows > 0){
						while ($r = $res_teach->fetch_assoc()){
							$teachs[] = $r['cognome']." ".$r['nome'];
						}
						$teacher = implode(", ", $teachs);
					}
	 	    ?>
 	    	<tr id="row<?php echo $alunno['alunno'] ?>" class="list_row">
 	    		<td style="width: 40%; text-align: left"><?php echo $alunno['stud'] ?></td>
 	    		<td style="width: 15%; text-align: center"><?php echo $alunno['classe'] ?></td>
 	    		<td style="width: 30%; text-align: center"><?php echo $teacher ?></td>
 	    		<td style="width: 10%; text-align: center">
 	    		<p id="c<?php echo $alunno['alunno'] ?>" class="edit" style="height: 14px; margin: 1px"><?php echo $alunno['ore'] ?></p>
				</td>
				<td style="width: 5%; text-align: center" class="attention">
					<a href="#" onclick="del(<?php echo $alunno['alunno'] ?>)" class="fa fa-close" style="font-weight: normal; text-decoration: none; color: red"></a>
				</td>
 	    	</tr>
	 	    
	 	    <?php
	 	    	}
	 	    }
            ?>
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
