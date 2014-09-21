<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Elenco sedi</title>
	<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link href="../../css/general.css" rel="stylesheet" />
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
	<div id="left_col">
		<div class="group_head">Elenco sedi: pagina <?php print $page ?> di <?php print $pagine ?></div>
		<table class="admin_table">
		<thead>
            <tr>
                <td style="width: 45%" class="adm_titolo_elenco_first">Sede</td>
                <td style="width: 55%" class="adm_titolo_elenco">Indirizzo</td>
            </tr>
            <tr class="admin_row_before_text">
                <td colspan="2"></td>
            </tr>
		</thead>
		<tbody id="t_body">
            <?php
            $x = 1;
            while($sede = $res_sedi->fetch_assoc()){
            ?>
            <tr class="admin_row" id="row_<?php echo $sede['id_sede'] ?>">
                <td style="padding-left: 10px; ">
                	<span class="ov_red" style="font-weight: bold"><?php echo $sede['nome'] ?></span>
                	<div id="link_<?php echo $sede['id_sede'] ?>" style="display: none">
                	<a href="dettaglio_sede.php?id=<?php echo $sede['id_sede'] ?>" class="mod_link">Modifica</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="venues_manager.php?action=2&_id=<?php echo $sede['id_sede'] ?>" class="del_link">Cancella</a>
                	</div>
                </td>
                <td><?php echo $sede['indirizzo'] ?></td>
            </tr>
            <?php
                $x++;
            }
            ?>
            </tbody>
            <tfoot>
            <tr class="admin_void">
	            <td colspan="2"></td>
            </tr>
            <tr class="admin_menu">
                <td colspan="2">
                    <a href="dettaglio_sede.php?id=0" id="new_site" class="standard_link nav_link_last">Nuova sede</a>
                </td>
            </tr>
        </tfoot>
        </table>
        </div>
		<p class="spacer"></p>
    </div>
<?php include "../footer.php" ?>
</body>
</html>
