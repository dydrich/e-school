<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Collega account docente</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var minicard = 0;
		var connect_accounts = function(str) {
			var acs = str.split('-');
			var url = "connect_accounts.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {action: 'connect', uid1: acs[0], uid2: acs[1]},
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
						location.reload(true);
					}
				}
			});
		};

		var disconnect_accounts = function(id) {
			var url = "connect_accounts.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {action: 'disconnect', id: id},
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
						location.reload(true);
					}
				}
			});
		};

		$(function(){
			load_jalert();
			setOverlayEvent();

			$('.conn_acc').on('click', function (event) {
				var uids = $(this).data('uids');
				minicard = $(this).data('index');
				connect_accounts(uids);
			});
			$('.disconn_acc').on('click', function (event) {
				var id = $(this).data('id');
				disconnect_accounts(id);
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
		<?php include "menu.php" ?>
	</div>
	<div id="left_col">
		<div class="card_container">
			<form method="post" style="width: 100%" class="no_border">
				<div class="accent_decoration _bold normal" style="font-size: 1.2em; margin-bottom: 20px">Account esistenti</div>
				<?php
				$index = 0;
				foreach ($connected as $id => $account) {
					?>
					<div class="minicard" id="minicard<?php echo $index ?>" style="display: flex; flex-direction: column; justify-content: center; align-content: center; margin-left: 35px">
						<div style="order: 1; flex: 0 1 100%; align-self: center; width: 100%; color: white" class="_bold _center material_dark_bg">
							<?php echo $account['name'] ?>
						</div>
						<div style="display: flex; order: 2; justify-content: center; align-content: center">
							<div style="order: 1; flex-grow: 3; flex-direction: row">
								<?php
								foreach ($account['uids'] as $data) {
									?>
									<div style="min-height: 20px; margin-top: 5px" class="_bold accent_color"><?php echo $data['subject'] ?></div>
									<?php
								}
								?>
							</div>
							<div style="order: 2; flex-grow: 1; align-self: center; " class="_center">
								<a href="#" title="Scollega gli account" class="disconn_acc" data-index="<?php echo $index ?>" data-id="<?php echo $id ?>"><i class="fa fa-user-times accent_color" style="font-size: 1.4em"></i></a>
							</div>
						</div>
					</div>
					<?php
					$index++;
				}
				?>
				<div class="accent_decoration _bold normal" style="font-size: 1.2em; margin: 130px 0 20px 0">Account possibili</div>
				<?php
				$index = 0;
				foreach ($accounts as $name => $account) {
					?>
					<div class="minicard" id="minicard<?php echo $index ?>" style="display: flex; justify-content: center; align-content: center; margin-left: 35px">
						<div style="order: 1; flex-grow: 3">
						<?php
						$str_uids = "";
						foreach ($account['uids'] as $data) {
							if ($str_uids != "") {
								$str_uids .= "-";
							}
							$str_uids .= $data['uid'];
							?>
							<div style="min-height: 25px; margin-top: 5px" class="_bold normal"><?php echo $name . "::" . $data['subject'] ?></div>
							<?php
						}
						?>
						</div>
						<div style="order: 2; flex-grow: 1; align-self: center; " class="_center">
							<a href="#" title="Collega gli account" class="conn_acc" data-index="<?php echo $index ?>" data-uids="<?php echo $str_uids ?>"><i class="fa fa-users accent_color" style="font-size: 1.4em"></i></a>
						</div>
					</div>
					<?php
					$index++;
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
		<div class="drawer_link"><a href="https://www.istitutoiglesiasserraperdosa.gov.it"><img src="../../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
<a href="#" id="top_btn" class="rb_button float_button top_button">
	<i class="fa fa-arrow-up"></i>
</a>
</body>
</html>
