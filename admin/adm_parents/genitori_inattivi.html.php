<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Genitori inattivi</title>
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link href="../../css/general.css" rel="stylesheet" />
	<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
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
			$('#select_all').change(function(event){
				if ($('#select_all').is(":checked")) {
					bol = true;
				}
				else {
					bol = false;
				}
				check_all(bol);
			});
			$('table tbody a.del_link').click(function(event){
				event.preventDefault();
				var strs = this.parentNode.id.split("_");
				del_user(strs[1]);
			});
		});

		var check_all = function(bol){
			$("input[type=checkbox]").each(function(){
				if ($(this).attr("id") != "select_all") {
					$(this).prop("checked", bol);
				}
			});
		};

		var del_user = function(id){
			if(!confirm("Sei sicuro di voler cancellare questo utente?")) {
		        return false;
			}
			var url = "parent_manager.php";
			$.ajax({
				type: "POST",
				url: url,
				data: {action: 2, _i: id},
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
						return;
					}
					else if (json.status == "ko"){
						j_alert("error", json.message);
						return;
					}
					else {
						$('#row_'+id).hide();
						j_alert("alert", "Utente cancellato correttamente");
						window.setTimeout(function(){
							document.location.href = "genitori_inattivi.php";
						}, 3000);
					}
				}
			});
		};

		var del_all = function(){
			if(!confirm("Sei sicuro di voler cancellare tutti gli utenti selezionati?")) {
		        return false;
			}
			var url = "parent_manager.php";
			$('#action').val(5);
			$.ajax({
				type: "POST",
				url: url,
				data: $('#myform').serialize(true),
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
						return;
					}
					else if (json.status == "ko"){
						j_alert("error", json.message);
						return;
					}
					else {
						j_alert("alert", "Operazione completata");
						window.setTimeout(function(){
							document.location.href = "genitori_inattivi.php";
						}, 3000);
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
		<?php include "../adm_users/menu.php" ?>
	</div>
	<div id="left_col">
		<form id="myform" method="post" class="no_border">
        <table class="admin_table">
        <thead>
            <tr class="admin_row_before_text">
                <td colspan="2"></td>
            </tr>
        </thead>
        <tbody>
            <?php
            $res_new_parents->data_seek(0);
            while($parent = $res_new_parents->fetch_assoc()){
            ?>
            <tr class="admin_row" id="row_<?php echo $parent['uid'] ?>">
            	<td style="width: 15%; text-align: center; padding-left: 10px"><input type="checkbox" name="ids[]" value="<?php echo $parent['uid'] ?>" /></td>
            	<td style="">
                	<span id="span_<?php echo $parent['uid'] ?>" class="ov_red"><?php echo $parent['cognome']." ".$parent['nome'] ?></span>
                	<div id="link_<?php echo $parent['uid'] ?>" style="display: none; vertical-align: bottom">
                	<a href="dettaglio_genitore.php?id=<?php echo $parent['uid'] ?>&referer=inactive" class="ren_link material_link">Modifica</a>
                	<span style="margin-left: 5px; margin-right: 5px"></span>
                	<a href="../../shared/no_js.php" class="del_link material_link">Elimina</a>
                	</div>
                </td>
                <td style=""><span><?php echo $parent['username'] ?></span></td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
        <tr class="admin_void">
	        <td colspan="3">&nbsp;&nbsp;&nbsp;<input type="hidden" name="action" id="action" /></td>
        </tr>
        	<tr class="admin_menu">
                <td colspan="3" >
                	<a href="#" class="material_link nav_link_first" onclick="del_all()">Elimina selezionati</a>
                    <a href="../index.php" class="material_link nav_link_last">Torna al menu</a>
                </td>
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
		<div class="drawer_link"><a href="https://www.istitutoiglesiasserraperdosa.gov.it"><img src="../../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
