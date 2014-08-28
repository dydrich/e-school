<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area studenti</title>
<link rel="stylesheet" href="../../css/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script>
document.observe("dom:loaded", function(){
	$('imglink').observe("click", function(event){
		event.preventDefault();
		show_menu('imglink');
	});
	$('menu_div').observe("mouseleave", function(event){
		event.preventDefault();
        $('menu_div').hide();
    });
});

function show_menu(el) {
	if($('menu_div').style.display == "none") {
	    position = getElementPosition(el);
	    dimensions = $(el).getDimensions();
	    ftop = position['top'] + dimensions.height;
	    fleft = position['left'] - 180 + dimensions.width;
	    console.log("top: "+ftop+"\nleft: "+fleft);
	    $('menu_div').setStyle({top: ftop+"px", left: fleft+"px", position: "absolute", zIndex: 100});
	    $('menu_div').blindDown({duration: 0.5});
	}
	else {
		$('menu_div').hide();
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
	<div class="group_head">
		Argomento delle lezioni
		<a href="../../shared/no_js.php" id="imglink" style="float: right">
            <img src="../../images/19.png" id="ctx_img" style="margin: 0 0 4px 0; opacity: 0.5; vertical-align: bottom" />
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
<p style="text-align: center; font-weight: bold; border: 1px solid rgba(30, 67, 137, .3); outline-style: double; outline-color: rgba(30, 67, 137, .5); background-color: rgba(30, 67, 137, .5); height: 15px"><?php print $giorno; if ($lday['assente']) echo "<span class='attention'> (assente)</span>" ?></p>
<?php
	if ($lday){
		foreach($lday['ore'] as $ac){
?>
<p style='border-bottom: 1px solid rgba(211, 222, 199, 0.6); height: 15px; padding-bottom: 2px; font-weight: bold'><?php echo $ac['ora']." ora - "; if (isset($materie[$ac['materia']]))  echo $materie[$ac['materia']].": "  ?><span style='font-weight: normal'><?php if (isset($ac['argomento'])) echo $ac['argomento'] ?></span></p>

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
	<a href="lezioni.php?view=m&m=<?php echo $num_mesi_scuola[$z]; if ($_GET['sub']) echo "&f=sub&sub=".$_GET['sub'] ?>" style="margin: 0 5px 0 5px; text-decoration: none"><?php echo $mesi_scuola[$z] ?></a>
	<?php if ($z < $max_m){ ?>|<?php } ?>
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
<div id="menu_div" class="page_menu" style="width: 180px; height: 270px; position: absolute; padding: 10px 0 10px 0px; display: none">
	<a href="lezioni.php?view=m&m=current" style="padding-left: 10px; line-height: 16px">Vista mensile</a><br />
	<a href="lezioni.php" style="padding-left: 10px; line-height: 16px">Vista normale</a><br /><br />
	<span style="padding-left: 10px; line-height: 16px; border-bottom: 1px solid #CCC">Viste per materia (mensile)</span><br />
<?php
foreach ($materie as $k => $materia){
?>
	<a href="lezioni.php?view=m&m=current&f=sub&sub=<?php echo $k ?>" style="padding-left: 10px; line-height: 16px"><?php echo $materia ?></a><br />
<?php } ?>
</div>
<?php include "footer.php" ?>
</body>
</html>
