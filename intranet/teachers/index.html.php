<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Area docenti</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript" src="../../js/md5-min.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#su').on('click', function(event) {
				event.preventDefault();
				ins_pass();
			});
			$('#su_go').on('click', function(event) {
				event.preventDefault();
				_su();
			});
		});

		var ins_pass = function(){
			$('#drawer').hide();
			$('#adm_pwd').dialog({
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
				height: 200,
				title: 'Admin Login',
				open: function(event, ui){

				},
				close: function(event) {
					$('#overlay').hide();
				}
			});
		};

		var _su = function() {
			var pass = hex_md5($('#pass').val());
			document.location.href = "../../admin/sudo_manager.php?pwd="+pass+"&action=su";
		};

	</script>
	<style>
		div.welcome:last-of-type {
			padding-bottom: 35px;
		}
	</style>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "working.php" ?>
</div>
<div id="left_col">
	<div class="welcome" style="padding-top: 0">
		<p id="s_head" style="margin-bottom: 0; background-image: none">
			<i class="fa fa-calendar" style="position: relative; left: -30px; font-size: 1.4em"></i>
			<span style="position: relative; left: -20px" class="normal"><?php echo $label ?></span>
		</p>
		<?php
		if(!$free_day){
			for($x = 1; $x < 6; $x++){
				if (isset($schedule[$x])){
					$a = $schedule[$x];
					if($a['materia'] != ""){
						?>
						<p class="s_hour"><?php print $x." ora: ".$a['cl'].$a['sez']."::".$a['materia'] ?><?php if($a['att'] != "") print(" (".$a['att'].")"); else if($a['hw'] != "") print(" (ci sono compiti da correggere)") ?></p>
					<?php
					}
				}
				else {
					?>
					<p class="s_hour"><?php echo $x." ora:    -------" ?></p>
				<?php
				}
			}
		}
		?>
	</div>
	<?php if(is_installed("wflow")) include $_SESSION['__path_to_root__'].'modules/workflow/w_front.php'; ?>
	<?php if(is_installed("com")) include $_SESSION['__path_to_root__'].'modules/communication/w_notices.php'; ?>
	<?php include $_SESSION['__path_to_root__'].'modules/documents/w_highlighteddocs_flow.php'; ?>
	<?php include $_SESSION['__path_to_root__'].'modules/documents/w_trainingdocs_flow.php'; ?>
	<?php if(is_installed("com")) include $_SESSION['__path_to_root__'].'modules/communication/w_circ.php'; ?>
	<?php if(is_installed("com")) include $_SESSION['__path_to_root__'].'modules/communication/w_news.php'; ?>
	<?php if(is_installed("com")) include $_SESSION['__path_to_root__'].'modules/communication/w_events.php'; ?>
	<?php if(is_installed("messenger")) include $_SESSION['__path_to_root__'].'modules/messenger/w_msg.php'; ?>
</div>
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=teachers"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=teachers"><img src="../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
		<?php if(is_installed("messenger")): ?>
            <div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/messenger/load_module.php?module=messenger&area=teachers"><img src="../../images/60.png" style="margin-right: 10px; position: relative; top: 5%" />Messaggi</a></div>
		<?php endif; ?>
		<?php if ($_SESSION['__user__']->hasConnectedAccounts()) {
			$acc = $_SESSION['__user__']->getConnectedAccounts();
			foreach ($acc as $ca) {
				$mat = $db->executeCount("SELECT rb_materie.materia FROM rb_materie, rb_docenti WHERE rb_docenti.materia = id_materia AND id_docente = $ca");
				?>
				<div class="drawer_link">
					<a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=sudo&area=3&uid=<?php echo $ca ?>">
						<img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%"/>
						Cambia utente (<?php echo $mat ?>)</a>
				</div>
				<?php
			}
		}
		?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
	<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
<div id="adm_pwd" style="display: none">
	<p>
		<label for="pass" class="material_label">Inserisci la password</label>
		<input type="password" class="material_input" id="pass" name="pass" style="width: 200px" />
	</p>
	<p style="margin-top: 45px; text-align: right">
		<a href="#" id="su_go" class="material_link">SuperUser</a>
	</p>
</div>
</body>
</html>
