<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Elenco login falliti</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../js/page.js"></script>
	<script type="text/javascript">

		var filter = function(){
			$('#drawer').hide();
			$('#listfilter').dialog({
				autoOpen: true,
				show: {
					effect: "appear",
					duration: 200
				},
				hide: {
					effect: "slide",
					duration: 200
				},
				modal: true,
				width: 290,
				height: 200,
				title: 'Filtra elenco',
				open: function(event, ui){

				},
				close: function(event) {
					$('#overlay').hide();
				}
			});
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('a.del_link').click(function(event){
				event.preventDefault();
				var strs = this.parentNode.id.split("_");
				del_user(strs[1]);
			});
			$('#filter_button').click(function(event){
				event.preventDefault();
				filter();
			});
		});

	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu.php" ?>
	</div>
	<div id="left_col">
		<div style="position: absolute; top: 75px; margin-left: 575px; margin-bottom: -5px" class="rb_button">
			<a href="#" id="filter_button">
				<img src="../images/69.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<div class="card_container" style="margin-top: 20px">
			<?php
			while($log = $res_log->fetch_assoc()){
				list($data, $ora) = explode(" ", $log['data_ora']);
				setlocale(LC_TIME, "it_IT.utf8");
				$date_string = strftime("%A %d %B", strtotime($data))." ore ".$ora;
				$area = null;
				switch ($log['numeric1']) {
					case 1:
						$area = "genitori";
						break;
					case 2:
						$area = "studenti";
						break;
					case 3:
						$area = "scuola";
						break;
				}
				?>
				<div class="card">
					<div class="card_title">
						<span><?php echo $date_string ?></span>
						<div style="float: right; margin-right: 20px">
							Area: <?php echo $area ?>
						</div>
					</div>
					<div class="card_minicontent">
						Login: <?php echo $log['text2'] ?>
						<div style="float: right; margin-right: 20px">
							IP: <?php echo $log['text1'] ?>
						</div>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>
	<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="../index.php"><img src="../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="index.php"><img src="../images/31.png" style="margin-right: 10px; position: relative; top: 5%" />Admin</a></div>
		<div class="drawer_link"><a href="http://www.istitutoiglesiasserraperdosa.it"><img src="../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../shared/do_logout.php"><img src="../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
<div id="listfilter" style="display: none; width: 250px">
	<p><a href="login_failed.php?area=1">Area genitori</a></p>
	<p><a href="login_failed.php?area=2">Area studenti</a></p>
	<p><a href="login_failed.php?area=3">Area scuola</a></p>
</div>
</body>
</html>
