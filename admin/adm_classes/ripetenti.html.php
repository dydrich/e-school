<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Elenco ripetenti per assegnazione alle classi</title>
<link href="../../css/reg.css" rel="stylesheet" />
<link href="../../css/general.css" rel="stylesheet" />
<link rel="stylesheet" href="../../modules/documents/theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
var shown_up = '<?php echo $first ?>';
var show_class = function(cls){
	$('#tb'+shown_up).hide();
	$('#tb'+cls).show();
	shown_up = cls;
};

$(function(){
	$('input[type=checkbox]').change(function(event){
		upd_student(this.value, this.checked);
	});
	$('#close_lnk').click(function(event){
		event.preventDefault();
		close_step();
	});
});

var upd_student = function(student, checked){
	var url = "check_ripetente.php";
	$.ajax({
		type: "POST",
		url: url,
		data: {school_order: <?php echo $school_order ?>, alunno: student, checked: checked},
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

var close_step = function(){
	var url = "aggiorna_stato.php";
	$.ajax({
		type: "POST",
		url: url,
		data: {school_order: <?php echo $school_order ?>, step: 1},
		dataType: 'json',
		error: function() {
			alert("Errore di trasmissione dei dati");
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
				document.location.href = "new_year_classes.php?school_order=<?php echo $school_order ?>";
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
		<div class="group_head">Elenco alunni classi <?php echo $cl_label." ".$sc_label ?> (estratti <?php echo $res_alunni->num_rows ?> alunni)</div>
        <form method="post">
        <div style="width: 95%; margin: 10px auto 0 auto; text-align: center">[
        <?php 
        foreach($classi as $cls){
        ?>
        	<a href="#" onclick="show_class('<?php echo $cls ?>')" style="margin: 0 5px 0 5px"><?= $cls ?></a>
        <?php 
        }
        ?>
         ]</div>
        <?php 
        while(list($k, $classe) = each($alunni)){
        ?>
        <table class="admin_table" id="tb<?php echo $k ?>" style="<?php if($k != $first) print("display: none") ?>">
        <thead>
            <tr class="admin_title_row">
                <td colspan="2">Elenco alunni classe <?php echo $k ?></td>
            </tr>
            <tr>
            	<td style="padding-left: 10px; width: 75%" class="adm_titolo_elenco_first">Alunno</td>
                <td style="width: 25%" class="adm_titolo_elenco_last _center">Ripetente</td>
                
            </tr>
            <tr class="admin_row_before_text">
                <td colspan="2"></td>
            </tr>
        </thead>
        <tbody>
        <?php 
        foreach ($classe as $al){
        ?>
        	<tr style="border-bottom: 1px solid #CCCCCC">
            	<td style="padding-left: 10px; width: 75%"><?php echo $al['cognome']." ".$al['nome'] ?></td>
                <td style="width: 25%" class="_center">
                	<input type="checkbox" name="al<?php echo $al['id_alunno'] ?>" value="<?php echo $al['id_alunno'] ?>" <?php if($al['ripetente'] == 1) print "checked" ?> />
                </td>
            </tr>
        <?php 
        }
        ?>
        </tbody>
        <tfoot>
            <tr class="admin_void">
                <td colspan="2">&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr class="admin_menu">
                <td colspan="2">
                	<a href="new_year_classes.php" class="nav_link_first">Torna indietro</a>|
                	<a href="../../shared/no_js.php" id="close_lnk" class="nav_link_last">Concludi prima fase</a>
                </td>
            </tr>
        </tfoot>
        </table>
        <?php 
        }
        ?>
        </form>
        </div>
	<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
</body>
</html>