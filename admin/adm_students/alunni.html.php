<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="author" content="" />
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="../../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var student = 0;
		var del_user = function(why){
			if(!confirm("Sei sicuro di voler cancellare questo alunno?"))
		        return false;
			var url = "student_manager.php";
			$.ajax({
				type: "POST",
				url: url,
				data: {action: 2, _i: student, why: why},
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
						j_alert("alert", json.message);
						$('#row_'+student).hide();
					}
				}
			});
		};

		var filter = function(){
			$('#drawer').hide();
			$('#listfilter').dialog({
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
				width: 450,
				height: 350,
				title: 'Filtra elenco',
				open: function(event, ui){

				},
				close: function(event) {
					$('#overlay').hide();
				}
			});
		};

		var why_delete = function(){
			$('#drawer').hide();
			$('#mtrasf').dialog({
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
				height: 250,
				title: 'Cancellazione alunno',
				open: function(event, ui){

				},
				close: function(event) {
					$('#overlay').hide();
				}
			});
		};

		var go = function(){
			var url = "alunni.php?order=nome";
			if(document.forms[1].sezione.value != "all")
				url += "&sezione="+document.forms[1].sezione.value;
			if(document.forms[1].classe.value != "all")
				url += "&classe="+document.forms[1].classe.value;
			if(document.forms[1].anno.value != "")
				url += "&anno="+document.forms[1].anno.value;
			if(document.forms[1].nome.value != "")
				url += "&nome="+document.forms[1].nome.value;
			document.location.href = url;
			//parent.win.close();
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('a.del_link').click(function(event){
				event.preventDefault();
				student = $(this).data("stud");
				why_delete();
				//del_user(strs[1]);
			});
			$('a.trasf').click(function(event){
				event.preventDefault();
				why = $(this).data("reason");
				del_user(why);
				$('#mtrasf').dialog("close");
			});
			$('#filter_button').click(function(event){
				event.preventDefault();
				filter('<?php print $current_order ?>');
			});
			$('#go_link').button();
			$('#go_link').click(function(event){
				event.preventDefault();
				go();
			});
			$('#top_btn').click(function() {
				$('html,body').animate({
					scrollTop: 0
				}, 700);
				return false;
			});

			var amountScrolled = 200;

			$(window).scroll(function() {
				if ($(window).scrollTop() > amountScrolled) {
					$('#plus_btn').fadeOut('slow');
					$('#float_btn').fadeIn('slow');
					$('#top_btn').fadeIn('slow');
				} else {
					$('#float_btn').fadeOut('slow');
					$('#plus_btn').fadeIn();
					$('#top_btn').fadeOut('slow');
				}
			});
		});

	</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include $menu ?>
	</div>
	<div id="left_col">
		<div style="position: absolute; top: 75px; left: 53%; margin-bottom: -5px" class="rb_button">
			<a href="#" id="filter_button">
				<img src="../../images/7.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<div style="position: absolute; top: 75px; left: 57%; margin-bottom: -5px" class="rb_button">
			<a href="dettaglio_alunno.php?id=0&type=0">
				<img src="../../images/39.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<div class="card_container" style="margin-top: 10px">
	<form class="no_border">
		<a href="alunni.php?order=<?php echo $new_order ?>">
		<div style="margin-bottom: 10px">
			<i class="fa <?php echo $icon ?> fa-lg accent_color"></i>
			<span style="margin-left: 8px; font-size: 1.2em" class="normal"><?php echo $button_label ?></span>
		</div>
		</a>
    <?php
    $x = 1;

    while($user = $res_user->fetch_assoc()){
        $class_string = $user['classe']." (";
        if($classes_table == "rb_classi"){
			$class_string .= $user['codice']." - ";
		}
		$class_string .= $user['sede'].")";
    ?>
	    <div class="card" id="row_<?php echo $user['id_alunno'] ?>">
		    <div class="card_title">
			    <a href="dettaglio_alunno.php?id=<?php print $user['id_alunno'] ?>&type=1&order=" class="mod_link"><?php echo stripslashes($user['cognome']." ".$user['nome']) ?></a>
			    <div style="float: right; margin-right: 20px" id="del_<?php echo $user['id_alunno'] ?>">
				    <a href="student_manager.php?action=2&_id=<?php echo $user['id_alunno'] ?>" data-stud="<?php echo $user['id_alunno'] ?>" class="del_link">
					    <img src="../../images/51.png" style="position: relative; bottom: 2px" />
				    </a>
			    </div>
		    </div>
		    <div class="card_minicontent">
			    Classe: <?php print $class_string ?>
			    <a href="dettaglio_alunno.php?id=<?php print $user['id_alunno'] ?>&type=2&order=" style="float: right; margin-right: 100px; text-align: left; width: 200px" class="acc_link">
				    <?php print $user['username'] ?>
			    </a>
		    </div>
	    </div>
        <?php
            $x++;
        }
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
<div id="listfilter" style="display: none; width: 450px">
	<form action="#" method="post">
		<fieldset style="width: 350px; border: 1px solid #BBB; margin-top: 15px; margin-left: auto; margin-right: auto">
			<legend style="font-weight: bold;">Parametri di ricerca</legend>
			<table style="width: 350px; margin-left: auto; margin-right: auto; margin-top: 10px">
				<tr>
					<td class="popup_title" align="left" style="width: 150px">Sezione</td>
					<td style="width: 200px">
						<select style="border: 1px solid; width: 200px; font-size: 11px; color: #777" name="sezione">
							<option selected="selected" value="all" style="padding-left: 10px">Tutte</option>
							<?php
							while($sez = $res_sezioni->fetch_assoc()){
								?>
								<option value="<?php print $sez['sezione'] ?>" style="padding-left: 10px"><?php print $sez['sezione'] ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="popup_title" align="left" style="width: 150px">Classe</td>
					<td style="width: 200px">
						<select style="border: 1px solid; width: 200px; font-size: 11px; color: #777" name="classe">
							<option selected="selected" value="all">Tutte</option>
							<?php
							foreach ($classi as $k => $v){
								?>
								<option value="<?php echo $k ?>"><?php echo $v ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="popup_title" align="left" style="width: 150px">Anno di nascita</td>
					<td style="width: 200px">
						<input type="text" name="anno" style="width: 199px; font-size: 11px" value="" maxlength="4" />
					</td>
				</tr>
				<tr>
					<td class="popup_title" align="left" style="width: 150px">Cognome</td>
					<td style="width: 200px">
						<input type="text" name="nome" style="width: 199px; font-size: 11px" value="" />
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;&nbsp;&nbsp;</td>
				</tr>
			</table>
		</fieldset>
		<div style="width: 380px; margin-left: 15px; margin-top: 20px; margin-bottom: 20px; text-align: right">
			<button id="go_link">Estrai</button>
		</div>
	</form>
</div>
<div id="mtrasf" style="display: none">
	<p class="_center _bold">Motivo della cancellazione</p>
	<p>
		<a href="#" class="trasf normal" data-reason="TRASFERITO">Trasferito ad altra scuola</a>
	</p>
	<p>
		<a href="#" class="trasf normal" data-reason="LICENZIATO">Licenziato</a>
	</p>
	<p>
		<a href="#" class="trasf normal" data-reason="SCONOSCIUTO">Non precisato</a>
	</p>
</div>
<a href="dettaglio_alunno.php?id=0&type=0" id="float_btn" class="rb_button float_button">
	<i class="fa fa-pencil"></i>
</a>
<a href="#" id="top_btn" class="rb_button float_button top_button">
	<i class="fa fa-arrow-up"></i>
</a>
</body>
</html>
