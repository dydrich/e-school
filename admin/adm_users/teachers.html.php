<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Elenco docenti</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var doc = mat = type = 0;
		var materia = function(event){
		    $('#hid').hide();
		    var url = "materia.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {uid: doc, mat: mat},
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
						$('#doc_'+doc).hide("fade", "400");
						setTimeout(function(){
							$('#doc_'+doc).text(json.subject);
							$('#doc_'+doc).show("fade", "400");
						}, 400);
					}
				}
			});
		};

		var ruolo = function(){
		    var url = "ruolo.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {uid: doc},
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
						$('#rl_'+doc).hide("fade", "400");
						setTimeout(function(){
							$('#rl_'+doc).text(json.value);
							$('#rl_'+doc).show("fade", "400");
						}, 400);
					}
				}
			});
		};

		var set_type = function(event){
		    $('#d_tp').hide();
		    var url = "set_school_type.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {uid: doc, type: type},
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
						$('#tipo_'+doc).hide("fade", "400");
						setTimeout(function(){
							$('#tipo_'+doc).text(json.tipo);
							$('#tipo_'+doc).show("fade", "400");
						}, 400);
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
							print_string += "<a href='../../shared/no_js.php' class='sub_link' data-id='"+t.id+"' id='mat_"+t.id+"'>"+t.materia+"</a><br />";
						}
						$('#hid').html(print_string);
						$('#hid').css({height: json.height+"px"});
						$('a.sub_link').click(function(event){
							event.preventDefault();
							var strs = $(this).attr("data-id");
							mat = strs;
							materia(event);
						});
					}
				}
			});
		};

		var visualizza = function(e, off) {
			if ($('#hid').is(":visible")) {
				$('#hid').slideUp(500);
				return;
			}
		    $('#hid').css({top: off.top+"px"});
		    $('#hid').css({left: off.left+"px"});
		    $('#hid').slideDown(500);
		    return true;
		};

		var show_types = function(e, off){
			if ($('#d_tp').is(":visible")) {
				$('#d_tp').slideUp(500);
				return;
			}
			$('#d_tp').css({top: off.top+"px"});
		    $('#d_tp').css({left: off.left+"px"});
		    $('#d_tp').slideDown(500);
		    return true;
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('a.sub_link').click(function(event){
				event.preventDefault();
				var strs = $(this).attr("data-id");
				alert(strs);
				mat = strs;
				materia(event);
			});
			$('a.ch_link').click(function(event){
				event.preventDefault();
				var id = $(this).attr("data-id");
				doc = id;
				load_subjects(id);
				var off = $(this).offset();
				off.top += $(this).height();
				visualizza(event, off);
			});
			$('a.ruolo').click(function(event){
				event.preventDefault();
				doc = $(this).attr("data-id");
				ruolo();
			});
			$('a.tipo').click(function(event){
				event.preventDefault();
				<?php if($_SESSION['__user__']->isAdministrator()){ ?>
				doc = $(this).attr("data-id");
				var off = $(this).offset();
				off.top += $(this).height();
				show_types(event, off);
				<?php } ?>
			});
			$('a.sc_link').click(function(event){
				event.preventDefault();
				var strs = $(this).attr("data-id");
				type = strs;
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
    <div id="hid" style="position: absolute; width: 200px; display: none; ">
    <?php
    $k = 0;
    while($mt = $res_m->fetch_assoc()){
    ?>
        <a href="../../shared/no_js.php" class="sub_link" id="mat_<?php echo $mt['id_materia'] ?>" data-id="<?php echo $mt['id_materia'] ?>"><?php print $mt['materia'] ?></a><br />
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
        <a href="../../shared/no_js.php" class="sc_link" id="tp_<?php echo $t['id_tipo'] ?>" data-id="<?php echo $t['id_tipo'] ?>"><?php print $t['tipo'] ?></a><br />
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
		   <div class="card_container">
			<form method="post" style="width: 100%" class="no_border">
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
	            <div class="card" id="row_<?php print $user['id_docente'] ?>">
		            <div class="card_title">
			            <?php print $user['cognome']." ".$user['nome'] ?>
			            <div style="float: right; margin-right: 20px; color: #1E4389" id="<?php print $user['id_docente'] ?>">Titolare:
				            <a href="../../shared/no_js.php" id="rl_<?php print $user['id_docente'] ?>" data-id="<?php print $user['id_docente'] ?>" class="ruolo">
					            <?php print $ruolo ?>
				            </a>
			            </div>
		            </div>
		            <div class="card_minicontent">
			            <div style="float: left; text-align: left; width: 400px">
				            <a href="../../shared/no_js.php" id="tipo_<?php print $user['id_docente'] ?>" data-id="<?php print $user['id_docente'] ?>" style="text-transform: capitalize" class="tipo normal"><?php echo $user['tipologia'] ?></a>
			            </div>
			            <a href="../../shared/no_js.php" class="ch_link normal" id="doc_<?php print $user['id_docente'] ?>" data-id="<?php print $user['id_docente'] ?>"><?php print $user['materia'] ?></a>
		            </div>
	            </div>
            <?php
                $x++;
            }
            include "../../shared/navigate.php";
            ?>
        </form>
    </div>
	    </div>
    <p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
    <div id="drawer" class="drawer" style="display: none; position: absolute">
	    <div style="width: 100%; height: 430px">
		    <div class="drawer_link"><a href="../../index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		    <div class="drawer_link"><a href="../index.php"><img src="../../images/31.png" style="margin-right: 10px; position: relative; top: 5%" />Admin</a></div>
		    <div class="drawer_link"><a href="http://www.istitutoiglesiasserraperdosa.it"><img src="../../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	    </div>
	    <div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
    </div>
</body>
</html>
