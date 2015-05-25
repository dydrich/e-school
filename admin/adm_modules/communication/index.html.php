<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Area admin: comunicazioni</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link href="../../../css/general.css" rel="stylesheet" />
	<link href="../../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" rel="stylesheet" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		var tm = 0;
		var complete = false;
		var timer;

		var show_message = function(msg){
			alert(msg);
		};

		var create_groups = function(){
			leftS = (screen.width - 200) / 2;
			$('#wait_label').css("left", leftS+"px");
			$('#wait_label').css("top", "300px");
			$('#over1').show();
			$('#wait_label').show(800);
			$.ajax({
				type: "POST",
				url: "create_system_groups.php",
				data: {},
				dataType: 'json',
				error: function() {
					show_error("Errore di trasmissione dei dati");
				},
				succes: function() {

				},
				complete: function(data){
					complete = true;
					clearTimeout(timer);
					r = data.responseText;
					if(r == "null"){
						return false;
					}
					var json = $.parseJSON(r);
					if (json.status == "kosql"){
						console.log(json.dbg_message);
						$('#wait_label').text(json.message);
						setTimeout("$('#wait_label').hide(2000)", 2000);
						setTimeout("$('#over1').hide()", 3800);

					}
					else {
						$('#wait_label').text(json.message);
						setTimeout("$('#wait_label').hide(2000)", 2000);
						setTimeout("$('#over1').hide()", 3800);
					}
				}
			});
			upd_str();
		};

		var upd_str = function(){
			tm++;
			//alert(tm);
			if(tm > 5){
				tm = 0;
				$('#wait_label').text("Creazione gruppi in corso");
			}
			else
				$('#wait_label').text($('#wait_label').text()+".");
			timer = setTimeout("upd_str()", 1000);
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#create_link').click(function(event){
				event.preventDefault();
				create_groups();
			});
		});
	</script>
</head>
<body>
<?php include "../../header.php" ?>
<?php include "../../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu.php" ?>
	</div>
	<div id="left_col">
		<div class="welcome">
			<p id="w_head">Gruppi di sistema</p>
			<p class="w_text" style="width: 350px">
				<a href="#" id="create_link">Crea gruppi di sistema</a>
			</p>
			<p class="w_text" style="width: 350px">
				<a href="#">Aggiorna gruppi di sistema</a>
			</p>
		</div>
	</div>
	<p class="spacer"></p>
</div>
<div class="overlay" id="over1" style="display: none">
	<div id="wait_label" style="position: absolute; display: none; padding-top: 25px">Creazione gruppi in corso</div>
</div>
<?php include "../../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="../../../index.php"><img src="../../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="../../index.php"><img src="../../../images/31.png" style="margin-right: 10px; position: relative; top: 5%" />Admin</a></div>
		<div class="drawer_link"><a href="http://www.istitutoiglesiasserraperdosa.it"><img src="../../../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../../../shared/do_logout.php"><img src="../../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
