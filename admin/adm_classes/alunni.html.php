<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Elenco alunni</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link href="../../css/general.css" rel="stylesheet" />
	<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var upd_cls = function(sel, student){
			var url = "update_class.php";
			$.ajax({
				type: "POST",
				url: url,
				data: {cls: $('#'+sel).val(), stud_id: student, old_cls: <?php echo $_REQUEST['id_classe'] ?>},
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
						$('#tr_'+student).hide();
						var st_count = parseInt($('#st_count').text());
						$('#st_count').text(--st_count);
					}
				}
			});
		};

		var exempt = function(_id) {
			var url = "exemption_manager.php";
			var action = "uncheck";
			if ($('#es'+_id).prop("checked")) {
				action = "check";
			}
			$.ajax({
				type: "POST",
				url: url,
				data: {student: _id, cls: <?php echo $_REQUEST['id_classe'] ?>, action: action},
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

					}
				}
			});
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('.esonerati').click(function(event){
				var _id = $(this).attr("data-id");
				exempt(_id);
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
		<form method="post" class="no_border">
        <table class="admin_table">
        <thead>
            <tr class="admin_row _bold _center" style="height: 20px">
                <td colspan="2">Alunno</td>
	            <td>Modifica classe</td>
	            <td>Esonero</td>
            </tr>
        </thead>
        <tbody>
            <?php
            $x = 1;
            while($stud = $res_alunni->fetch_assoc()){               
            ?>
            <tr class="admin_row<?php if($x % 2) print(" odd") ?>" style="height: 20px" id="tr_<?php echo $stud['id_alunno'] ?>">
            	<td style="width: 5%; padding-right: 12px; text-align: right"><?php print $x ?>.</td>
                <td style="width: 40%; padding-left: 2px; text-align: left"><?php print utf8_decode($stud['cognome']." ".$stud['nome']) ?></td>
                <td style="width: 40%">
	                <select name="cls_<?php print $stud['id_alunno'] ?>" id="cls_<?php print $stud['id_alunno'] ?>" style="width: 95%; font-size: 11px;" onchange="upd_cls(this.id, <?php print $stud['id_alunno'] ?>)">
	                <?php
	                $res_classi->data_seek(0);
	                while($_class = $res_classi->fetch_assoc()){
	                ?>
	                    <option value="<?php print $_class['id_classe'] ?>;<?php echo $_class['ordine_di_scuola'] ?>" <?php if($_class['id_classe'] == $_REQUEST['id_classe']) print("selected") ?> ><?php print $_class['classe']." - ".$_class['nome'] ?></option>
	                <?php
	                }
	                ?>
	                </select>
                </td>
	            <td style="width: 15%; text-align: center">
		            <input type="checkbox" class="esonerati" name="es<?php echo $stud['id_alunno'] ?>" id="es<?php echo $stud['id_alunno'] ?>" data-id="<?php echo $stud['id_alunno'] ?>" <?php if(in_array($stud['id_alunno'], $esonerati)) echo "checked"; ?>>
	            </td>
            </tr>
            <?php
            	$x++;
            }
            ?>
            </tbody>
            <tfoot>
            <tr class="admin_void">
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr class="admin_void">
                <td colspan="3"></td>
            </tr>
            </tfoot>
        </table>
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
