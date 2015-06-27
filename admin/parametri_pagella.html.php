<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Elenco parametri pagella</title>
	<link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../js/page.js"></script>
	<script type="text/javascript">

		var del_param = function(id){
			if(!confirm("Sei sicuro di voler cancellare questo parametro?"))
		        return false;
			var url = "params_manager.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {action: 2, _i: id},
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
					}
					else {
						j_alert("alert", "Parametro cancellato");
						$("#row_"+id).hide();
					}
				}
			});
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('table tbody > tr').mouseover(function(event){
				//alert(this.id);
				var strs = this.id.split("_");
				$('#link_'+strs[1]).show();
			});
			$('table tbody > tr').mouseout(function(event){
					//alert(this.id);
					var strs = this.id.split("_");
					$('#link_'+strs[1]).hide();
			});

			$('table tbody a.del_link').click(function(event){
					event.preventDefault();
					var strs = this.parentNode.id.split("_");
					del_param(strs[1]);
			});
		});

	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
	<div id="main">
		<div id="right_col">
			<?php include "scr_menu.php" ?>
		</div>
		<div id="left_col">
			<div style="position: absolute; top: 75px; margin-left: 625px; margin-bottom: 5px" class="rb_button">
				<a href="dettaglio_parametro.php?id=0&school=<?php echo $school_order ?>" id="new_site">
					<img src="../images/39.png" style="padding: 12px 0 0 12px" />
				</a>
			</div>
			<div class="card_container" style="margin-top: 20px">
            <?php
            while($param = $res_params->fetch_assoc()){
               
            ?>
	        <div class="card" id="row_<?php echo $param['id'] ?>">
		        <div class="card_title" id="link_<?php echo $param['id'] ?>">
			        <a href="dettaglio_parametro.php?id=<?php echo $param['id'] ?>&school=<?php echo $school_order ?>" class="mod_link"><?php echo $param['nome'] ?></a>
			        <div style="float: right; width: 75px; text-align: right; margin-right: 20px">
				        <a href="params_manager.php?action=2&_id=<?php echo $param['id'] ?>&school=<?php echo $school_order ?>" class="del_link">
					        <img src="../images/51.png" style="position: relative; bottom: 2px" />
				        </a>
			        </div>
		        </div>
		        <div class="card_content">
			        <a href="valori_parametro.php?id=<?php echo $param['id'] ?>&school=<?php echo $school_order ?>" class="normal mod_par">
				        Presenti <?php echo $param['count'] ?> parametri
			        </a>
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
</body>
</html>
