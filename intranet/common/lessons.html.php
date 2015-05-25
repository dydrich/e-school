<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area studenti</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script>
		$(function(){
			$('#imglink').click(function(event){
				event.preventDefault();
				show_menu('imglink');
			});
			$('#menu_div').mouseleave(function(event){
				event.preventDefault();
		        $('#menu_div').hide();
		    });
			load_jalert();
			setOverlayEvent();
			$('#overlay').click(function(event) {
				if ($('#overlay').is(':visible')) {
					show_drawer(event);
				}
				$('#other_drawer').hide();
			});
			$('#showsub').click(function(event){
				var off = $(this).parent().offset();
				_show(event, off);
			});
		});

		var _show = function(e, off) {
			if ($('#other_drawer').is(":visible")) {
				$('#other_drawer').hide('slide', 300);
				return;
			}
			var offset = $('#drawer').offset();
			var top = off.top;

			var left = offset.left + $('#drawer').width() + 1;
			$('#other_drawer').css({top: top+"px", left: left+"px", zIndex: 1000});
			$('#other_drawer').show('slide', 300);
			return true;
		};

		function show_menu(el) {
			if($('#menu_div').is(":hidden")) {
			    position = getElementPosition(el);
				ftop = position['top'] + $('#'+el).height();
				fleft = position['left'] - 180 + $('#'+el).width();
				console.log("top: "+ftop+"\nleft: "+fleft);
				$('#menu_div').css({top: ftop+"px", left: fleft+"px", position: "absolute", zIndex: 100});
				$('#menu_div').slideToggle(600);
			}
			else {
				$('#menu_div').slideUp();
			}
		}
	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "class_working.php" ?>
</div>
<div id="left_col">
	<div style="width: 97%; text-align: right; margin-right: 2%; margin-bottom: 15px">
		<a href="../../shared/no_js.php" id="imglink">Menu
            <img src="../../images/19.png" id="ctx_img" style="margin: 0 0 4px 0; opacity: 0.5; vertical-align: bottom; position: relative; top: 5px" />
       	</a>
	</div>
<?php
$current = $start;
while($current < $max){
	$lday = $lezioni[$current];
	setlocale(LC_TIME, "it_IT.utf8");
	$giorno_str = strftime("%A", strtotime($lday['data']));
	$giorno = ucfirst(substr($giorno_str, 0, 3))." ". format_date($lday['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/");
?>
<div style="text-align: left; width: 95%; margin-left: auto; margin-right: auto; margin-bottom: 15px; ">
<div class="card_row normal _bold"><?php print $giorno; if ($lday['assente']) echo "<span class='attention'> (assente)</span>" ?></div>
<?php
	if ($lday){
		foreach($lday['ore'] as $ac){
?>
<p class="bottom_decoration" style='width: 90%; height: 15px; padding-bottom: 2px; font-weight: bold'><?php echo $ac['ora']." ora - "; if (isset($materie[$ac['materia']]))  echo $materie[$ac['materia']].": "  ?><span style='font-weight: normal'><?php if (isset($ac['argomento'])) echo $ac['argomento'] ?></span></p>

<?php 
		}
		$current++;
	}
?>
</div>
<?php
}
?>
<div class="navigate" style="margin-top: 45px">
<?php 
if (isset($_GET['view']) && $_GET['view'] == "m"){ 
	for ($z = 0; $z <= $max_m; $z++){		
?>
	<a href="lezioni.php?view=m&m=<?php echo $num_mesi_scuola[$z]; if (isset($_GET['sub'])) echo "&f=sub&sub=".$_GET['sub'] ?>" style="margin: 0 5px 0 5px; text-decoration: none"><?php echo $mesi_scuola[$z] ?></a>
	<?php if ($z < $max_m){ ?><span>|</span><?php } ?>
<?php 
	}
}
else{ ?>
	<a href="lezioni.php?offset=<?php echo $last ?>" style="margin-right: 30px; text-decoration: none">&lt;&lt;</a>
	<a href="lezioni.php?offset=<?php echo $next ?>" style="margin-right: 30px; text-decoration: none">&lt;</a>
	<a href="lezioni.php?offset=<?php echo $previous ?>" style="margin-right: 30px; text-decoration: none">&gt;</a>
	<a href="lezioni.php?offset=0" style="; text-decoration: none">&gt;&gt;</a>
<?php } ?>
</div>
</div>
<p class="spacer"></p>
</div>
<div id="menu_div" class="page_menu" style="width: 180px; height: 290px; position: absolute; padding: 10px 0 10px 0px; display: none">
	<a href="lezioni.php?view=m&m=current" style="padding-left: 10px; line-height: 16px">Vista mensile</a><br />
	<a href="lezioni.php" style="padding-left: 10px; line-height: 16px">Vista normale</a><br /><br />
	<span style="line-height: 16px; margin-left:10px;" class="accent_decoration">Viste per materia (mensile)</span><br />
<?php
foreach ($materie as $k => $materia){
?>
	<a href="lezioni.php?view=m&m=current&f=sub&sub=<?php echo $k ?>" style="padding-left: 10px; line-height: 16px"><?php echo $materia ?></a><br />
<?php } ?>
</div>
<?php include "footer.php" ?>
<<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<?php if ($area == "genitori" && count($_SESSION['__sons__']) > 1): ?>
			<div class="drawer_link separator">
				<a href="#" id="showsub"><img src="../../images/69.png" style="margin-right: 10px; position: relative; top: 5%"/>Seleziona alunno</a>
			</div>
		<?php endif; ?>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<?php if ($area == "alunni"): ?>
			<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=alunni"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php endif; ?>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=<?php echo $area ?>"><img src="<?php echo $_SESSION['__path_to_root__'] ?>images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
<?php if ($area == "genitori" && count($_SESSION['__sons__']) > 1){
	$height = 36 * (count($_SESSION['__sons__']));
?>
	<div id="other_drawer" class="drawer" style="height: <?php echo $height ?>px; display: none; position: absolute">
		<?php
		$indice = 1;
		reset($_SESSION['__sons__']);
		while(list($key, $val) = each($_SESSION['__sons__'])){
			$cl = "";
			if ($key == $_SESSION['__current_son__']) {
				$cl = " _bold";
			}
			?>
			<div class="drawer_link">
				<a href="<?php print $pagina ?>?son=<?php print $key ?>" clas="<?php echo $cl ?>"><?php print $val[0] ?></a>
			</div>
		<?php
		}
		?>
	</div>
<?php
}
?>
</body>
</html>
