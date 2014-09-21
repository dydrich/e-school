<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Elenco docenti</title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
var IE = document.all?true:false;

var tempX = 0;
var tempY = 0;

var materia = function(event){
    //alert("ok");
    $('#hid').hide();
    var uid = $('#uid').val();
    var mat = $('#mat').val();
    var url = "materia.php";

	$.ajax({
		type: "POST",
		url: url,
		data: {uid: uid, mat: mat},
		dataType: 'json',
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
				j_alert("error", json.message);
				console.log(json.dbg_message);
			}
			else {
				$('#doc_'+uid).text(json.subject);
			}
		}
	});
};

var ruolo = function(_uid){
    var uid = _uid;
    var url = "ruolo.php";

	$.ajax({
		type: "POST",
		url: url,
		data: {uid: uid},
		dataType: 'json',
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
				j_alert("error", json.message);
				console.log(json.dbg_message);
			}
			else {
				$('#rl_'+uid).text(json.value);
			}
		}
	});
};

var set_type = function(event){
    //alert("ok");
    $('#d_tp').hide();
    var uid = $('#uid').val();
    var type = $('#type').val();
    var url = "set_school_type.php";

	$.ajax({
		type: "POST",
		url: url,
		data: {uid: uid, type: type},
		dataType: 'json',
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
				j_alert("error", json.message);
				console.log(json.dbg_message);
			}
			else if (json.status == "ko") {
				j_alert("error", json.message);
			}
			else {
				$('#tipo_'+uid).text(json.tipo);
			}
		}
	});
};

var load_subjects = function(user){
	var url = "load_subjects.php";

	$.ajax({
		type: "POST",
		url: url,
		data: {uid: user},
		dataType: 'json',
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
				j_alert("error", json.message);
				console.log(json.dbg_message);
			}
			else if (json.status == "ko") {
				j_alert("error", json.message);
			}
			else {
				$('#hid').html("");
				var subjs = json.materie;
				var print_string = "";
				for(data in subjs){
					var t = subjs[data];
					print_string += "<a href='../../shared/no_js.php' class='sub_link' id='mat_"+t.id+"'>"+t.materia+"</a><br />";
				}
				$('#hid').html(print_string);
				$('#hid').css({height: json.height+"px"});
				$('a.sub_link').click(function(event){
					event.preventDefault();
					var strs = this.id.split("_");
					$('#mat').val(strs[1]);
					materia(event);
				});
			}
		}
	});
};

function visualizza(e) {
    if (IE) { 
        tempX = event.clientX + document.body.scrollLeft;
        tempY = event.clientY + document.body.scrollTop;
    } else {  
        tempX = e.pageX;
        tempY = e.pageY;
    }  

    if (tempX < 0){tempX = 0;}
    if (tempY < 0){tempY = 0;}  
    $('#hid').css({top: parseInt(tempY)+"px"});
    $('#hid').css({left: parseInt(tempX)+"px"});
    $('#hid').show();
    return true;
}

var show_types = function(e){
	if (IE) { 
        tempX = event.clientX + document.body.scrollLeft;
        tempY = event.clientY + document.body.scrollTop;
    } else {  
        tempX = e.pageX;
        tempY = e.pageY;
    }  
    
    if (tempX < 0){tempX = 0;}
    if (tempY < 0){tempY = 0;}
    tempX -= 100;
    $('#d_tp').css({top: parseInt(tempY)+"px"});
    $('#d_tp').css({left: parseInt(tempX)+"px"});
    $('#d_tp').show();
    return true;
};

$(function(){
	load_jalert();
	$('a.sub_link').click(function(event){
		event.preventDefault();
		var strs = this.id.split("_");
		$('#mat').val(strs[1]);
		materia(event);
	});
	$('a.ch_link').click(function(event){
		event.preventDefault();
		var strs = this.id.split("_");
		$('#uid').val(strs[1]);
		load_subjects(strs[1]);
		visualizza(event);
	});
	$('a.ruolo').click(function(event){
		event.preventDefault();
		var strs = this.id.split("_");
		ruolo(strs[1]);
	});
	$('a.tipo').click(function(event){
		event.preventDefault();
		<?php if($_SESSION['__user__']->isAdministrator()){ ?>
		var strs = this.id.split("_");
		$('#uid').val(strs[1]);
		show_types(event);
		<?php } ?>
	});
	$('a.sc_link').click(function(event){
		event.preventDefault();
		var strs = this.id.split("_");
		$('#type').val(strs[1]);
		set_type(event);
	});
	$('#d_tp').mouseleave(function(event){
		event.preventDefault();
		$('#d_tp').hide();
	});
	$('#hid').mouseleave(function(event){
		event.preventDefault();
		$('#hid').hide();
	});
});

</script>
</head>
<body>
    <!--
    DIV nascosto che contiene le materie: ogni riga e' un link che carica materie.php
    -->
    <div id="hid" style="position: absolute; width: 200px; height: <?php echo (20 * $res_m->num_rows) ?>px; display: none; ">
    <?php
    $k = 0;
    while($mt = $res_m->fetch_assoc()){
    ?>
        <a href="../../shared/no_js.php" class="sub_link" id="mat_<?php echo $mt['id_materia'] ?>"><?php print $mt['materia'] ?></a><br />
    <?php
        $k++;
    }
    ?>
    </div>
    <!--
    DIV nascosto che contiene le tipologie di scuola
    -->
    <div id="d_tp" style="position: absolute; width: 200px; height: 80px; display: none; ">
    <?php
    while($t = $res_tipologie->fetch_assoc()){
    ?>
        <a href="../../shared/no_js.php" class="sc_link" id="tp_<?php echo $t['id_tipo'] ?>"><?php print $t['tipo'] ?></a><br />
    <?php
    }
    ?>
    </div>
    <?php include "../header.php" ?>
    <?php include "../navigation.php" ?>
    <div id="main">
	    <div id="right_col">
		    <?php include "menu.php" ?>
	    </div>
	    <div id="left_col">
		   <div class="group_head">Elenco Docenti: pagina <?php print $page ?> di <?php print $pagine ?></div>
		<form method="post" style="width: 100%" class="no_border">
        <table class="admin_table">
            <tr>
                <td style="width: 30%" class="adm_titolo_elenco_first">Nome e cognome</td>
                <td style="width: 20%" class="adm_titolo_elenco">Materia</td>
                <td style="width: 10%" class="adm_titolo_elenco _center">Ruolo</td>
                <td style="width: 40%" class="adm_titolo_elenco_last _center">Tipologia scuola</td>
            </tr>
            <tr class="admin_row_before_text">
                <td colspan="4"></td>
            </tr>
            <?php
            $x = 1;
            if($res_user->num_rows > $limit)
                $max = $limit;
            else
                $max = $res_user->num_rows;

            while($user = $res_user->fetch_assoc()){
                $ruolo = "SI";
                if($user['ruolo'] != "S")
                    $ruolo = "NO";
                if($x > $limit) break;
            ?>
            <tr class="admin_row" style="height: 20px">
                <td><?php print $user['cognome']." ".$user['nome'] ?></td>
                <td><a href="../../shared/no_js.php" class="ch_link" id="doc_<?php print $user['id_docente'] ?>"><?php print $user['materia'] ?></a></td>
                <td class="_center"><a href="../../shared/no_js.php" id="rl_<?php print $user['id_docente'] ?>" class="ruolo"><?php print $ruolo ?></a></td>
                <td class="_center"><a href="../../shared/no_js.php" id="tipo_<?php print $user['id_docente'] ?>" class="tipo"><?php echo $user['tipologia'] ?></a>
            </tr>
            <?php
                $x++;
            }
            include "../../shared/navigate.php";
            ?>

            <tr class="admin_void">
                <td colspan="4">&nbsp;&nbsp;&nbsp;
                	<input type="hidden" name="mat" id="mat" />
        			<input type="hidden" name="uid" id="uid" />
        			<input type="hidden" name="type" id="type" />
                </td>
            </tr>
        </table>
        </form>
	    </div>
	    <p class="spacer"></p>
    </div>
    <?php include "../footer.php" ?>
</body>
</html>
