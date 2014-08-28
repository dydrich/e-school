<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Verifica registro di classe</title>
<link rel="stylesheet" href="../../css/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../modules/communication/theme/style.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../modules/communication/theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script>
$(function(){
	$('#imglink').click(function(event){
		event.preventDefault();
		show_menu('imglink');
	});
	$('#menu_div').mouseleave(function(event){
		event.preventDefault();
        $('#menu_div').slideToggle();
    });
	$('.show_abs').click(function(event){
		data = this.id.split("_");
		show_absents(data[1]);
		//$('#abs_'+data[1]).slideToggle(600);
	});
});

var show_menu = function(el) {
	if($('#menu_div').css("display") == "none") {
	    position = $("#"+el).position();
	    //dimensions = $('#'+el).getDimensions();
	    ftop = position['top'] + $('#'+el).height();
	    fleft = position['left'] - 180 + $('#'+el).width();
	    console.log("top: "+ftop+"\nleft: "+fleft);
	    $('#menu_div').css({top: ftop+"px", left: fleft+"px", position: "absolute", zIndex: 100});
	    $('#menu_div').slideToggle(600);
	}
	else {
		$('#menu_div').slideUp();
	}
};

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
<style type="text/css">
<!--
td a {
	text-decoration: none;
}
-->
</style>
</head>
<body>
<?php include "header.php" ?>
<?php include $_SESSION['__administration_group__']."/navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include $_SESSION['__administration_group__']."/menu.php" ?>
</div>
<div id="left_col">
	<div class="group_head">
		Verifica registro di classe
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
<p id="lnk_<?php echo $lday['id_reg'] ?>" class="show_abs" style="text-align: center; font-weight: bold; border: 1px solid rgba(30, 67, 137, .5);; outline-style: double; outline-color: rgba(30, 67, 137, .5);; background-color: rgba(30, 67, 137, .2);; height: 15px"><?php print $giorno ?></p>
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
	<a href="lezioni.php?view=m&m=<?php echo $num_mesi_scuola[$z]; if ($_GET['sub']) echo "&f=sub&sub=".$_GET['sub'] ?>" style="margin: 0 5px 0 5px; text-decoration: none"><?php echo $mesi_scuola[$z] ?></a>
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
</body>
</html>
