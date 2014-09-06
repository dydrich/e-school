<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Elenco alunni</title>
<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
<link href="../../css/general.css" rel="stylesheet" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
var upd_cls = function(sel, student){
	var url = "update_class.php";
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
		<div class="group_head">Elenco alunni classe <?php echo $myclass['anno_corso'].$myclass['sezione'] ?> - <?php echo $myclass['nome'] ?> (<span id="st_count"><?php print $res_alunni->num_rows ?></span>)</div>
		<form method="post">
        <table class="admin_table">
        <thead>
            <tr>
            	<td style="padding-left: 2px; width: 5%" class="adm_titolo_elenco_first"></td>
                <td style="padding-left: 2px; width: 75%" class="adm_titolo_elenco">Alunno</td>
                <td style="padding-left: 10px; width: 20%" class="adm_titolo_elenco_last">Classe</td>
            </tr>
            <tr class="admin_void">
                <td colspan="2"></td>
            </tr>
        </thead>
        <tbody>
            <?php
            $x = 1;
            while($stud = $res_alunni->fetch_assoc()){               
            ?>
            <tr class="admin_row<?php if($x % 2) print(" odd") ?>" style="height: 20px" id="tr_<?php echo $stud['id_alunno'] ?>">
            	<td style="padding-right: 12px; text-align: right"><?php print $x ?>.</td>
                <td style="padding-left: 2px; text-align: left"><?php print utf8_decode($stud['cognome']." ".$stud['nome']) ?></td>
                <td style="">
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
            <tr class="admin_menu">
                <td colspan="3">
                    <a href="classi.php?school_order=<?php echo $myclass['ordine_di_scuola'] ?><?php if($offset != 0) echo "&second=1&offset={$offset}" ?>" class="standard_link">Torna all'elenco classi</a>
                </td>
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
</body>
</html>
