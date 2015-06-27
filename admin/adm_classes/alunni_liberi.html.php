<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Elenco alunni da assegnare alle classi</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link href="../../css/general.css" rel="stylesheet" />
	<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var upd_cls = function(sel, student){
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
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('.alpha_lnk').mouseover(function(event){
				$('#'+this.id).css({cursor: 'pointer'});
			});
			$('.alpha_lnk').click(function(event){
				document.location.href = 'alunni_liberi.php?lettera='+$('#'+this.id).text();
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
            <tr class="admin_void">
                <td colspan="3"></td>
            </tr>

            <tr class="admin_void">
                <td colspan="3"></td>
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
