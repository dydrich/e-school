<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Rappresentanti di classe</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link href="../../css/general.css" rel="stylesheet" />
	<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var sc_order = <?php echo $classe['ordine_di_scuola'] ?>;
		var raps = [];
		<?php
		if (count($raps) > 0) {
			foreach ($raps as $rap) {
		?>
		raps.push(<?php echo $rap ?>);
		<?php
			}
		}
		?>
		$(function(){
			load_jalert();
			setOverlayEvent();
			$('.useradd').on('click', function(event) {
				event.preventDefault();
				uid = $(this).data('uid');
				if (in_array(raps, uid)) {
					return false;
				}
				add_user(uid);
			});
			$('.userdel').on('click', function(event) {
				event.preventDefault();
				id = $(this).data('id');
				del_user(id);
			});
		});

		var add_user = function(user) {
			var url = "update_reps.php";
			$.ajax({
				type: "POST",
				url: url,
				data: {action: "add", cls: <?php echo $classID ?>, rapp: user},
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
						$('<div id="rep'+json.id+'" style="width: 95%; height: 20px" class="normal"><a href="#" onclick="del_user('+json.id+')" class="userdel" data-id="'+json.id+'">'+json.user+'</a></div>').appendTo($('#rep_set'));
						$('.useradd[data-uid='+user+']').addClass('disabled_link');
						//$('.userdel[data-id='+json.id+']').on('click', del_user(json.id));
					}
				}
			});
		};

		var del_user = function(id) {
			var url = "update_reps.php";
			$.ajax({
				type: "POST",
				url: url,
				data: {action: "del", id: id},
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
						$('#rep'+id).hide();
						$('.useradd[data-uid='+json.uid+']').removeClass('disabled_link');
					}
				}
			});
		};
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
		<div style="position: absolute; top: 115px; margin-left: 55px; margin-bottom: 0" class="rb_button">
			<a href="classi.php?school_order=<?php echo $_SESSION['school_order'] ?>">
				<img src="../../images/47bis.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<form action="rappresentanti_di_classe.php.php?upd=1" method="post" class="popup_form" style="width: 90%; overflow: hidden">
			<div style="text-align: left; margin-left: 20px; margin-top: 20px; width: 55%" class="fleft">
			<?php
			while ($row = $res_parents->fetch_assoc()) {
			?>
				<div style="width: 95%; height: 20px" class="bottom_decoration normal">
					<a href="#" class="useradd <?php if(in_array($row['uid'], $raps)) echo 'disabled_link' ?>" data-uid="<?php echo $row['uid'] ?>" style="">
						<?php echo $row['utente'] ?> (<span class="_italic" style="font-weight: normal"><?php echo $row['alunno'] ?></span>)
					</a>
				</div>
			<?php
			}
			?>
			</div>
			<fieldset id="rep_set" style="width: 30%; margin-top: 40px; margin-right: 5%; padding: 10px; min-height: 100px" class="fright">
				<legend class="material_label" >Rappresentanti</legend>
				<?php
				$res_rap->data_seek(0);
				while ($row = $res_rap->fetch_assoc()) {
					?>
					<div id="rep<?php echo $row['id'] ?>" style="width: 95%; height: 20px" class="normal">
						<a href="#" class="userdel" data-id="<?php echo $row['id'] ?>">
							<?php echo $row['utente'] ?>
						</a>
					</div>
					<?php
				}
				?>
			</fieldset>
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
