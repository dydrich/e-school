<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Docente sostegno</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
			$('.sel').change(function(){
				dati = this.id.split("_");
				registra(dati[1]);
			});
		});

		var registra = function(id){
			//alert($('#ore').val());
			$.ajax({
				type: "POST",
				url: "assegna_alunno.php",
				data: {id: id, alunno: $('#al_'+id).val()},
				dataType: 'text',
				error: function() {
					alert("Errore di trasmissione dei dati");
				},
				succes: function() {

				},
				complete: function(data){
					r = data.responseText;
					if(r == "null"){
						return false;
					}
					if (r == "kosql"){

					}
					else {

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
	<div style="top: -10px; margin-left: 35px; margin-bottom: -25px" class="rb_button">
		<a href="docenti_sostegno.php">
			<img src="../../images/47bis.png" style="padding: 12px 0 0 12px" />
		</a>
	</div>
 	<form id="my_form" method="post" style="border: 0; margin-top: 10px; text-align: left; width: 85%; margin-left: auto; margin-right: auto">
	<fieldset style="width: 90%; margin: auto; border-radius: 8px">
	<legend style="font-weight: bold">Dati docente</legend>
	<table style="width: 90%; margin-left: auto; margin-right: auto; margin-top: 15px; margin-bottom: 15px">
		<tr class="hover_row">
			<td style="width: 40%; font-weight: bold">Docente</td>
			<td style="width: 60%"><?php echo $user ?></td> 
		</tr>
		<tr class="hover_row">
			<td style="width: 40%; font-weight: bold">Numero di ore</td>
			<td style="width: 60%"><?php echo $ore ?></td> 
		</tr>
		<tr class="hover_row">
			<td style="width: 40%; font-weight: bold">Classi</td>
			<td style="width: 60%"><?php echo implode(", ", $sos[$did]['classi']) ?></td> 
		</tr>
	</table>
	</fieldset>
	<fieldset style="width: 90%; margin: 10px auto; border-radius: 8px">
	<legend style="font-weight: bold">Alunni assegnati</legend>
	<table style="width: 90%; margin-left: auto; margin-right: auto; margin-top: 15px; margin-bottom: 15px">
		<tr class="accent_decoration">
			<td style="width: 15%; font-weight: bold">Classe</td>
			<td style="width: 15%; font-weight: bold">Ore</td>
			<td style="width: 55%; font-weight: bold">Alunno</td>
			<td style="width: 15%"></td>
		</tr>
	<?php
	$res_sos->data_seek(0);
	while ($row = $res_sos->fetch_assoc()){
	?>
		<tr>
			<td style="width: 15%"><?php echo $row['d_classe'] ?></td>
			<td style="width: 15%"><?php echo $row['ore'] ?></td>
			<td style="width: 55%">
			<select id="al_<?php echo $row['id'] ?>" name="al_<?php echo $row['id'] ?>" class="sel" style="width: 100%">
				<option value="0" <?php if ($row['alunno'] == "") echo "selected" ?>>Assegna</option>
	<?php 
		foreach ($classi[$row['classe']]['alunni'] as $id => $al){
	?>
				<option value="<?php echo $id ?>" <?php if ($row['alunno'] == $id) echo "selected" ?>><?php echo $al ?></option>
	<?php
		}
	?>
			</select>
			</td>
			<td style="width: 15%; text-align: center">
				<i class="fa fa-trash"></i>
			</td>
		</tr>
	<?php
	}
	?>
	</table>
	</fieldset>
	</form>
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
