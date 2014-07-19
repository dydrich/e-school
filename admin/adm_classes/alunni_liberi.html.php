<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Elenco alunni da assegnare alle classi</title>
<link href="../../css/reg.css" rel="stylesheet" />
<link href="../../css/general.css" rel="stylesheet" />
<link rel="stylesheet" href="../../modules/documents/theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
function upd_cls(sel, student){
	//alert($('#'+sel).val());
	var url = "update_class.php";
	$.ajax({
		type: "POST",
		url: url,
		data: {cls: $('#'+sel).val(), stud_id: student},
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
}

$(function(){
	$('.alpha_lnk').mouseover(function(event){
		$('#'+this.id).css({cursor: 'pointer'});
	});
	$('.alpha_lnk').click(function(event){
		document.location.href = 'alunni_liberi.php?lettera='+$('#'+this.id).text();
	});
});

</script>
<style>
#alpha_row {
	border-top: 1px solid #CCCCCC;
	border-bottom: 1px solid #CCCCCC;
	text-align: center;
}
#alpha_row span {
	margin-right: 10px;
}

table tbody tr:hover {
	background-color: #FAF6B7;
}
</style>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Elenco alunni non assegnati alle classi (estratti <span id="st_count"><?php echo $res_alunni->num_rows ?></span> alunni)</div>
		<form method="post">
        <table class="admin_table">
        <thead>
            <tr class="admin_void">
                <td colspan="3"></td>
            </tr>
            <tr class="admin_void">
                <td colspan="3" id="alpha_row">
                <?php 
		        foreach($alpha as $a){
		        ?>
		        <span class="alpha_lnk"><?php echo $a ?></span>
		        <?php 
		        }
		        ?>
                </td>
            </tr>
            <tr class="admin_void">
                <td colspan="3"></td>
            </tr>
            <tr>
            	<td style="padding-left: 2px; width: 5%" class="adm_titolo_elenco_first"></td>
                <td style="padding-left: 2px; width: 75%" class="adm_titolo_elenco">Alunno</td>
                <td style="padding-left: 10px; width: 20%" class="adm_titolo_elenco_last">Classe</td>
            </tr>
            <tr class="admin_void">
                <td colspan="3"></td>
            </tr>
        </thead>
        <tbody>
            <?php
            $x = 1;
            while($stud = $res_alunni->fetch_assoc()){               
            ?>
            <tr class="admin_row" style="height: 20px" id="tr_<?php echo $stud['id_alunno'] ?>">
            	<td style="padding-right: 12px; color: #003366; text-align: right"><?php echo $x ?>.</td>
                <td style="padding-left: 2px; color: #003366; text-align: left"><?php echo $stud['cognome']." ".$stud['nome'] ?></td>
                <td style="color: #003366;">
                <select name="cls_<?php echo $stud['id_alunno'] ?>" id="cls_<?php echo $stud['id_alunno'] ?>" style="width: 95%; font-size: 11px; border: 1px solid #CCCCCC" onchange="upd_cls(this.id, <?php echo $stud['id_alunno'] ?>)">
                	<option value="0" selected="selected" >Seleziona</option>
                <?php
                $res_classi->data_seek(0);
                while($row = $res_classi->fetch_assoc()){
	                print_r($row);
                ?>
                	<option value="<?php echo $row['id_classe'].";".$row['ordine_di_scuola'] ?>"><?php echo $row['classe']." - ".$row['nome'] ?></option>
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
                <td colspan="3"></td>
            </tr>
            <tr class="admin_menu">
                <td colspan="3">
                	<a href="classi.php" class="nav_link_first">Vai alle classi</a>|
                    <a href="../index.php" class="nav_link_last">Torna al menu</a>
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