<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Elenco sedi</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link href="../../css/general.css" rel="stylesheet" />
	<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">

		var del_sede = function(id){
			if(!confirm("Sei sicuro di voler cancellare questa sede?"))
		        return false;
			var url = "venues_manager.php";

			$.ajax({
				type: "POST",
				url: url,
				data:  {action: 2, _i: id},
				dataType: 'json',
				error: function() {
					show_error("Errore di trasmissione dei dati");
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
						alert(json.message);
						console.log(json.dbg_message);
					}
					else {
						link = "sedi.php?msg=2&second=1&offset=<?php print $offset ?>";
						j_alert("alert", json.message);
						window.setTimeout(function() {
							document.location.href = link;
						}, 2000);
					}
				}
			});
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('a.del_link').click(function(event){
					event.preventDefault();
					var strs = this.parentNode.id.split("_");
					del_sede(strs[1]);
			});
		});

	</script>
<title>Registro elettronico</title>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu.php" ?>
	</div>
	<div id="left_col" class="cardbody">
		<div style="position: absolute; top: 75px; margin-left: 625px; margin-bottom: -5px" class="rb_button">
			<a href="dettaglio_sede.php?id=0">
				<img src="../../images/39.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<div class="card_container" style="margin-top: 20px">
		<?php
        $x = 1;
        while($sede = $res_sedi->fetch_assoc()){
        ?>
        <div class="card" id="row_<?php echo $sede['id_sede'] ?>">
	        <div class="card_title">
		        <a href="dettaglio_sede.php?id=<?php echo $sede['id_sede'] ?>" class="mod_link"><?php echo $sede['nome'] ?></a>
		        <div style="float: right; margin-right: 20px" id="del_<?php echo $sede['id_sede'] ?>">
			        <a href="venues_manager.php?action=2&_id=<?php echo $sede['id_sede'] ?>" class="del_link">
				        <img src="../../images/51.png" style="position: relative; bottom: 2px" />
			        </a>
		        </div>
	        </div>
	        <div class="card_content">
		        Responsabile di plesso: <strong><?php echo $sede['responsabile'] ?></strong>
	        </div>
        </div>
        <?php
            $x++;
        }
        ?>
        </div>
		<p class="spacer"></p>
    </div>
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
</body>
</html>
