<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Modifica account utente</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javaScript">
		var change_username = function(){
			var url = "../../shared/account_manager.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {action: "change_username", uid: <?php echo $_GET['uid'] ?>, area: "<?php echo $_REQUEST['area'] ?>", new_username: $('#uname').val()},
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
						$('#uname').val('<?php echo $user['username'] ?>');
						j_alert("error", json.message);
					}
					else {
						j_alert("alert", json.message);
					}
				}
			});
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#save_button').button();
			$('#save_button').click(function(event){
				event.preventDefault();
				change_username();
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
		<div style="position: absolute; top: 75px; margin-left: 225px; margin-bottom: -5px" class="rb_button">
			<a href="<?php echo $back_link ?>">
				<img src="../../images/47bis.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<form method="post" id="user_form" class="popup_form no_border" style="width: 90%">
			<div style="text-align: left; width: 100%; margin: auto; ">
				<fieldset style="margin-right: auto; margin-left: auto; margin-bottom: 20px; padding-bottom: 20px; width: 95%; padding-top: 10px; ">
					<legend>Account</legend>
					<table style="margin: auto; width: 95%">
						<tr class="popup_row header_row">
							<td style="width: 30%"><label for="uname" class="popup_title">UserName</label></td>
							<td style="width: 70%">
								<input class="form_input" type="text" name="uname" id="uname" style="width: 100%" value="<?php echo $user['username'] ?>" />
							</td>
						</tr>
					</table>
				</fieldset>
			</div>
			<div style="width: 99%; margin-right: 0px; text-align: right">
				<button id="save_button">Registra</button>
			</div>
		</form>
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
