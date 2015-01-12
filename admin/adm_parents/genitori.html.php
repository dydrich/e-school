<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Elenco genitori</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link href="../../css/general.css" rel="stylesheet" />
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var id_alunni = new Array;

		var get_pop_search = function(){
			$('#pop_search').dialog({
				autoOpen: true,
				show: {
					effect: "fade",
					duration: 500
				},
				hide: {
					effect: "fade",
					duration: 300
				},
				modal: true,
				width: 350,
				height: 450,
				title: 'Filtra elenco',
				buttons: {
					Ok: function() {
						//$( this ).dialog( "close" );
						name = $('#search').val();
						search_name(name);
					}
				},
				open: function(event, ui){

				},
				close: function(event) {
					$('#overlay').hide();
				}
			});
		};

		var search_name = function (nm) {
			var url = "parent_manager.php";
			$.ajax({
				type: "POST",
				url: url,
				data: {action: 7, cognome: nm},
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
						return;
					}
					else if (json.status == "ok"){
						$('#list_names').text(json.message);
						return;
					}
					else {
						var txt = "";
						for (i in json.data) {
							t = json.data[i];
							if (t.uid) {
								txt += "<p><a href='dettaglio_genitore.php?id=" + t.uid + "&search=1&school_order=<?php echo $_GET['school_order'] ?>'>" + t.user + "</a></p>"
							}
						}
						$('#list_names').html(txt);
						return;
					}
				}
			});
		};

		var filtro = function(){
			cls = $('#mysel').val();
			if(cls == 0)
				document.location.href = "genitori.php";
			else
				document.location.href = "genitori.php?classe="+cls;
		};

		var go = function(val){
			if(val == 1){
				if(trim(document.forms[0].parent.value) == "")
					document.location.href = "genitori.php";
				else{
					document.location.href = "genitori.php?nome="+trim(document.forms[0].parent.value);
				}
			}
			else{
				if(trim(document.forms[0].student.value) == "")
					document.location.href = "genitori.php";
				else{
					document.location.href = "genitori.php?aname="+trim(document.forms[0].student.value);
				}
			}
		};

		var filtro_nome = function(val){
			if(val == 2){
				$('#stud_td').html("<input type='text' name='student' style='font-size: 10px; width: 150px; border: 1px solid #CCCCCC; color: #777' />&nbsp;&nbsp;<input type='button' value='filtra' style='border: 1px solid #CCCCCC; width: 40px' onclick='go(2)' />");
			}
			else{
				$('#$par_td').html("<input type='text' name='parent' style='font-size: 10px; width: 150px; border: 1px solid #CCCCCC; color: #777' />&nbsp;&nbsp;<input type='button' value='filtra' style='border: 1px solid #CCCCCC; width: 40px' onclick='go(1)' />");
			}
		};

		var del_user = function(id){
			if(!confirm("Sei sicuro di voler cancellare questo utente?"))
		        return false;
			var url = "parent_manager.php";
			$.ajax({
				type: "POST",
				url: url,
				data: {action: 2, _i: id},
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
						return;
					}
					else if (json.status == "ko"){
						j_alert("error", json.message);
						return;
					}
					else {
						link = "genitori.php?offset=<?php print $offset ?>&school_order=<?php echo $school_order ?>";
						j_alert("alert", "Utente cancellato correttamente");
						window.setTimeout(function(){
							document.location.href = link;
						}, 3000);
					}
				}
			});
		};

		var show = function(e, off) {
			if ($('#order_menu').is(":visible")) {
				$('#order_menu').slideUp(500);
				return;
			}
			$('#order_menu').css({top: off.top+"px"});
			$('#order_menu').css({left: off.left+"px"});
			$('#order_menu').slideDown(500);
			return true;
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
		<?php if(count($ordered_parents) > 0) { ?>
			$('a.del_link').click(function(event){
				event.preventDefault();
				var strs = this.parentNode.id.split("_");
				del_user(strs[1]);
			});

		<?php } ?>
			$('#show_submenu').click(function(event){
				event.preventDefault();
				var off = $('#cmenu').offset();
				off.top += $('#cmenu').height();
				show(event, off);
			});
			$('#open_search').click(function(event){
				event.preventDefault();
				get_pop_search();
			});
		});

	</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "../adm_users/menu.php" ?>
	</div>
	<div id="left_col">
		<div style="position: absolute; top: 75px; left: 52%; margin-bottom: -5px" class="rb_button">
			<a href="#" id="open_search">
				<img src="../../images/7.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<div style="position: absolute; top: 75px; left: 57%; margin-bottom: -5px" class="rb_button">
			<a href="dettaglio_genitore.php?id=0&school_order=<?php echo $_GET['school_order'] ?>">
				<img src="../../images/39.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<a href="#" id="show_submenu">
			<div id="cmenu" style="margin-bottom: 10px; width: 90%; margin: auto">
				<i class="fa fa-sort-alpha-asc fa-lg accent_color"></i>
				<span style="margin-left: 8px; font-size: 1.2em" class="normal">Ordina per...</span>
			</div>
		</a>
		<div class="card_container" style="margin-top: 20px">
    <?php
    if(count($ordered_parents) < 1) {
    ?>
    <tr style="height: 150px; text-align: center">
        <td colspan="3" style="font-size: 1.2em; font-weight: bold">Nessun genitore presente</td>
    </tr>
    <?php
    }
    else {
        $index = 0;

        $id_genitore = 0;
        $classe = "";
        $final_user = "";
        $final_uid = 0;
        $figli = array();
        $classi = array();
        $_max = ($offset + $limit) -1;
        if(count($ordered_parents) > $limit)
            $max = $limit;
        else
            $max = count($ordered_parents);
        foreach ($ordered_parents as $user){
            if($offset > 0 && ($index < $offset)) {
                $index++;
                continue;
            }
            //echo $index . "==" .$_max ."<br>";
            if ($index >= $_max) {
                break;
            }

            if($id_genitore != $user['uid'] && $id_genitore != 0){
                $index++;
    ?>
        <div class="card" id="row_<?php echo $final_uid ?>">
            <div class="card_title">
                <a href="dettaglio_genitore.php?id=<?php print $final_uid ?>&school_order=<?php echo $_GET['school_order'] ?><?php echo $offlink ?>" class="mod_link"><?php print $final_user ?></a>
                <div style="float: right; margin-right: 20px" id="del_<?php echo $final_uid ?>">
	                <a href="parents_manager.php?action=2&id=<?php print $final_uid ?>&school_order=<?php echo $_GET['school_order'] ?>" class="del_link">
		                <img src="../../images/51.png" style="position: relative; bottom: 2px" />
	                </a>
                </div>
                <div style="float: right; margin-right: 120px; text-align: left; width: 200px; text-transform: none" class="normal"><?php echo $final_uname ?></div>
            </div>
            <div class="card_content">
                Alunni: <?php print join(", ", $figli); ?>
                <div style="float: right; margin-right: 10px; text-align: left; width: 400px; font-size: 0.95em"><?php print join(", ", $classi); ?></div>
            </div>
        </div>
        <?php
                    $figli = array_slice($figli, 0, 0);
                    $classi = array_slice($classi, 0, 0);
				}

				if(!in_array($user['al_name'], $figli)) {
					array_push($figli, $user['al_name']);
				}
				$class_string = $user['desc_classe']." (";
				if($classes_table == "rb_classi"){
					$class_string .= $user['codice']." - ";
				}
				$class_string .= $user['sede'].")";
				if(!in_array($class_string, $classi)) {
					array_push($classi, $class_string);
				}
				$id_genitore = $user['uid'];

                $final_user = $user['nome'];
                $final_uid = $user['uid'];
	            $final_uname = $user['username'];
            }
            //print(count($figli)."-".count($classi));
            $index++;
        ?>
        <div class="card" id="row_<?php echo $final_uid ?>">
            <div class="card_title">
	            <a href="dettaglio_genitore.php?id=<?php print $final_uid ?>&school_order=<?php echo $_GET['school_order'] ?>" class="mod_link"><?php print $final_user ?></a>
	            <div style="float: right; margin-right: 20px" id="del_<?php echo $final_uid ?>">
		            <a href="parents_manager.php?action=2&id=<?php print $final_uid ?>&school_order=<?php echo $_GET['school_order'] ?>" class="del_link">
			            <img src="../../images/51.png" style="position: relative; bottom: 2px" />
		            </a>
	            </div>
	            <div style="float: right; margin-right: 120px; text-align: left; width: 200px; text-transform: none" class="normal"><?php echo $final_uname ?></div>
            </div>
            <div class="card_content" style="">
	            Alunni: <?php print join(", ", $figli); ?>
	            <div style="float: right; margin-right: 10px; text-align: left; width: 400px; font-size: 0.95em"><?php print join(", ", $classi); ?></div>
            </div>
        </div>
        <?php
            include "../../shared/navigate.php";
        }
        ?>
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
<div id="order_menu" class="page_menu" style="position: absolute; width: 200px; display: none; ">
	<a href="genitori.php?order=desc_classe&school_order=<?php echo $_GET['school_order'] ?>">Ordina per classe</a><br />
	<a href="genitori.php?school_order=<?php echo $_GET['school_order'] ?>">Ordina per nome</a><br />
	<a href="genitori.php?order=al_name&school_order=<?php echo $_GET['school_order'] ?>">Ordina per nome alunno</a><br />
</div>
<div id="pop_search" style="width: 350px; display: none">
	<div>
		<span>Cerca per cognome</span><br />
		<input type="text" name="search" id="search" style="width: 290px" />
	</div>
	<div id="list_names" style="width: 90%; margin: auto"></div>
</div>
</body>
</html>
