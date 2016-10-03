<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Verifica registro di classe</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script>
		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#menu_div').mouseleave(function(event){
				event.preventDefault();
		        $('#menu_div').slideToggle();
		    });
			$('.show_abs').click(function(event){
				data = $(this).attr("data-id");
				show_absents(data);
				//$('#abs_'+data[1]).slideToggle(600);
			});
		});

		var show_absents = function(id_reg){
			$.ajax({
				type: "POST",
				url: "../../lib/ws/get_absents.php",
				data: {id_reg: id_reg},
				dataType: 'json',
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
					var json = $.parseJSON(r);
					if (json.status == "kosql"){
						alert(json.message);
						console.log(json.dbg_message);
					}
					else {
						data = json.data.split(";");
						string = data.join(", ");
						$('#abs_'+id_reg).html("Assenti: "+string);
						$('#abs_'+id_reg).slideToggle(600);
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
<?php
$current = $start;
while($current < $max){
	$lday = $lezioni[$current];
	setlocale(LC_TIME, "it_IT.utf8");
	$giorno_str = strftime("%A", strtotime($lday['data']));
	$giorno = ucfirst(substr($giorno_str, 0, 3))." ". format_date($lday['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/");
?>
<div style="text-align: left; width: 95%; margin-left: auto; margin-right: auto; margin-bottom: 15px; ">
<p id="lnk_<?php echo $lday['id_reg'] ?>" data-id="<?php echo $lday['id_reg'] ?>" class="show_abs" style="text-align: center; font-weight: bold; height: 15px"><?php print $giorno ?></p>
<div id="abs_<?php echo $lday['id_reg'] ?>" style="text-align: left; width: 100%; margin-left: auto; margin-right: auto; display: none "></div>
<?php
	if ($lday){
		foreach($lday['ore'] as $ac){
?>
<p style='border-bottom: 1px solid rgba(211, 222, 199, 0.6); height: 15px; padding-bottom: 2px; font-weight: bold'><?php echo $ac['ora']." ora - ".$ac['docente']." (".$materie[$ac['materia']]."): "  ?><span style='font-weight: normal'><?php echo $ac['argomento'] ?></span></p>

<?php 
		}
		$current++;
	}
?>
</div>
<?php
}
?>
<div style="width: 90%; height: 20px; font-weight: normal; padding:20px; display: block; text-align: center; margin: 0 auto 0 auto; border-bottom: 1px solid rgb(211, 222, 199);">
<?php 
if (isset($_GET['view']) && $_GET['view'] == "m"){ 
	for ($z = 0; $z <= $max_m; $z++){		
?>
	<a href="registro_classe.php?idc=<?php echo $class ?>&view=m&m=<?php echo $num_mesi_scuola[$z]; if (isset($_GET['sub']) && $_GET['sub']) echo "&f=sub&sub=".$_GET['sub'] ?>" style="margin: 0 5px 0 5px; text-decoration: none"><?php echo $mesi_scuola[$z] ?></a>
	<?php if ($z < $max_m){ ?>|<?php } ?>
<?php 
	}
}
else{ ?>
	<a href="registro_classe.php?offset=<?php echo $last ?>&idc=<?php echo $class ?>" style="margin-right: 30px; text-decoration: none">&lt;&lt;</a>
	<a href="registro_classe.php?offset=<?php echo $next ?>&idc=<?php echo $class ?>" style="margin-right: 30px; text-decoration: none">&lt;</a>
	<a href="registro_classe.php?offset=<?php echo $previous ?>&idc=<?php echo $class ?>" style="margin-right: 30px; text-decoration: none">&gt;</a>
	<a href="registro_classe.php?offset=0&idc=<?php echo $class ?>" style="; text-decoration: none">&gt;&gt;</a>
<?php } ?>
</div>
</div>
<p class="spacer"></p>
</div>
<div id="menu_div" class="page_menu" style="width: 180px; height: 50px; position: absolute; padding: 10px 0 10px 0px; display: none">
	<a href="registro_classe.php?view=m&m=current&idc=<?php echo $class ?>" style="padding-left: 10px; line-height: 16px">Vista mensile</a><br />
	<a href="registro_classe.php&idc=<?php echo $class ?>" style="padding-left: 10px; line-height: 16px">Vista normale</a><br /><br />
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="registro_classe.php?view=m&m=current&idc=<?php echo $class ?>"><img src="../../images/70.png" style="margin-right: 10px; position: relative; top: 5%" />Vista mensile</a></div>
		<div class="drawer_link separator"><a href="registro_classe.php?idc=<?php echo $class ?>"><img src="../../images/47.png" style="margin-right: 10px; position: relative; top: 5%" />Vista normale</a></div>
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
