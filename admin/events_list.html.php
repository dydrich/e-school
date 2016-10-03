<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Gestione eventi tracciati</title>
	<link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
			$('a.del_link').click(function(event){
				event.preventDefault();
				id = $(this).attr("data-id");
				del_event(id);
			});
			$('#new_button').click(function(event){
				event.preventDefault();
				document.location.href = "event.php?id=0";
			});
		});

		var del_event = function(id){
			action = "delete";

			var url = "events_manager.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {action: action, id: id},
				dataType: 'json',
				error: function() {
					console.log(json.dbg_message);
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
						console.log(json.dbg_message);
						console.log(json.query);
						j_alert("error", json.message);
						return;
					}
					else if (json.status == "ko") {
						j_alert("error", json.message);
						return;
					}
					else {
						j_alert("alert", "Operazione conclusa");
						$('#del_'+id).hide();
					}
				}
			});
		};
	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "stat_menu.php" ?>
	</div>
	<div id="left_col">
		<div style="position: absolute; top: 75px; margin-left: 575px; margin-bottom: -5px" class="rb_button">
			<a href="#" id="new_button">
				<img src="../images/39.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<div class="card_container" style="margin-top: 20px">
			<?php
			while($event = $res_events->fetch_assoc()){
				?>
				<div class="card" id="del_<?php echo $event['id'] ?>">
					<div class="card_title">
						<a href="event.php?id=<?php echo $event['id'] ?>"><?php echo $event['tipo'] ?></a>
						<div style="float: right; margin-right: 20px">
							<a href="events_manager.php?action=2&id=<?php echo $event['id'] ?>" data-id="<?php echo $event['id'] ?>" class="del_link">
								<img src="../images/51.png" style="position: relative; bottom: 2px" />
							</a>
						</div>
					</div>
					<div class="card_minicontent">
						<?php echo $event['descrizione'] ?>
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
		<div class="drawer_link"><a href="https://www.istitutoiglesiasserraperdosa.gov.it"><img src="../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../shared/do_logout.php"><img src="../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
