<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Elenco ripetenti per assegnazione alle classi</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
<link href="../../css/general.css" rel="stylesheet" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
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
	load_jalert();
	setOverlayEvent();
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
		<form method="post" class="no_border">
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
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 360px">
		<div class="drawer_link"><a href="../../index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="../index.php"><img src="../../images/31.png" style="margin-right: 10px; position: relative; top: 5%" />Admin</a></div>
		<div class="drawer_link"><a href="http://www.istitutoiglesiasserraperdosa.it"><img src="../../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
