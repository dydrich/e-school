<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="author" content="" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">

var del_user = function(id){
	if(!confirm("Sei sicuro di voler cancellare questo alunno?"))
        return false;
	var url = "student_manager.php";
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
			}
			else {
				j_alert("alert", json.message);
				$('#row_'+id).hide();
			}
		}
	});
};

var filter = function(){
	$('#listfilter').dialog({
		autoOpen: true,
		show: {
			effect: "appear",
			duration: 500
		},
		hide: {
			effect: "slide",
			duration: 300
		},
		modal: true,
		width: 450,
		height: 350,
		title: 'Filtra elenco',
		open: function(event, ui){

		}
	});
};

var go = function(){
	var url = "alunni.php?order=nome";
	if(document.forms[1].sezione.value != "all")
		url += "&sezione="+document.forms[1].sezione.value;
	if(document.forms[1].classe.value != "all")
		url += "&classe="+document.forms[1].classe.value;
	if(document.forms[1].anno.value != "")
		url += "&anno="+document.forms[1].anno.value;
	if(document.forms[1].nome.value != "")
		url += "&nome="+document.forms[1].nome.value;
	document.location.href = url;
	//parent.win.close();
};

<?php echo $page_menu->getJavascript() ?>

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
		del_user(strs[1]);
	});
	$('#filter_button').click(function(event){
		event.preventDefault();
		filter('<?php print $current_order ?>');
	});
	$('#go_link').click(function(event){
		event.preventDefault();
		go();
	});
});

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
		<div class="group_head"><div style="float: left"><?php $page_menu->printLink() ?></div>Elenco alunni: pagina <?php print $page ?> di <?php print $pagine ?> (estratti <?php print $_SESSION['count_alunni'] ?> alunni) <span style="text-decoration: underline"><?php print $query_label ?></span></div>
	<?php $page_menu->toHTML() ?>
	<form class="no_border">
        <table class="admin_table">
        <thead>
            <tr>
                <td style="width: 50%" class="adm_titolo_elenco_first">Nome e cognome</td>
                <td style="width: 25%" class="adm_titolo_elenco">Login</td>
                <td style="width: 25%" style="text-align: center" class="adm_titolo_elenco_last">Classe</td>
            </tr>
            <tr class="admin_row_before_text">
                <td colspan="3"></td>
            </tr>
        </thead>
        <tbody>
            <?php
            $x = 1;
            if($res_user->num_rows > $limit)
                $max = $limit;
            else
                $max = $res_user->num_rows;

            while($user = $res_user->fetch_assoc()){
                if($x > $limit) break;
                $class_string = $user['classe']." (";
                if($classes_table == "rb_classi"){
					$class_string .= $user['codice']." - ";
				}
				$class_string .= $user['sede'].")";
            ?>
            <tr class="admin_row" id="row_<?php echo $user['id_alunno'] ?>">
                <td style="padding-left: 10px; ">
                	<span class="ov_red" style="font-weight: bold"><?php echo stripslashes($user['cognome']." ".$user['nome']) ?></span>
                	<div id="link_<?php echo $user['id_alunno'] ?>" style="display: none">
                	<a href="dettaglio_alunno.php?id=<?php print $user['id_alunno'] ?>&type=1?order=" class="mod_link">Modifica alunno</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="dettaglio_alunno.php?id=<?php print $user['id_alunno'] ?>&type=2&order=" class="acc_link">Modifica account</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="student_manager.php?action=2&_id=<?php echo $user['id_alunno'] ?>" class="del_link">Cancella</a>
                	</div>
                </td>
                <td><?php print $user['username'] ?></td>
                <td><?php print $class_string ?></td>
            </tr>
            <?php
                $x++;
            }
            ?>
            </tbody>
            <tfoot>
            <?php
            include "../../shared/navigate.php";
            ?>
            <tr class="admin_menu">
                <td colspan="3" align="right">
                    <a href="dettaglio_alunno.php?id=0&type=0" id="new_button" class="standard_link nav_link_first">Nuovo studente</a>
                </td>
            </tr>
        </tfoot>
        </table>
        </form>
        </div>
        <p class="spacer"></p>
	</div>
<?php include "../footer.php" ?>
<div id="listfilter" style="display: none; width: 450px">
	<form action="#" method="post">
		<fieldset style="width: 350px; border: 1px solid #BBB; margin-top: 15px; margin-left: auto; margin-right: auto">
			<legend style="font-weight: bold;">Parametri di ricerca</legend>
			<table style="width: 350px; margin-left: auto; margin-right: auto; margin-top: 10px">
				<tr>
					<td class="popup_title" align="left" style="width: 150px">Sezione</td>
					<td style="width: 200px">
						<select style="border: 1px solid; width: 200px; font-size: 11px; color: #777" name="sezione">
							<option selected="selected" value="all" style="padding-left: 10px">Tutte</option>
							<?php
							while($sez = $res_sezioni->fetch_assoc()){
								?>
								<option value="<?php print $sez['sezione'] ?>" style="padding-left: 10px"><?php print $sez['sezione'] ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="popup_title" align="left" style="width: 150px">Classe</td>
					<td style="width: 200px">
						<select style="border: 1px solid; width: 200px; font-size: 11px; color: #777" name="classe">
							<option selected="selected" value="all">Tutte</option>
							<?php
							foreach ($classi as $k => $v){
								?>
								<option value="<?php echo $k ?>"><?php echo $v ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="popup_title" align="left" style="width: 150px">Anno di nascita</td>
					<td style="width: 200px">
						<input type="text" name="anno" style="width: 199px; font-size: 11px" value="" maxlength="4" />
					</td>
				</tr>
				<tr>
					<td class="popup_title" align="left" style="width: 150px">Nome</td>
					<td style="width: 200px">
						<input type="text" name="nome" style="width: 199px; font-size: 11px" value="" />
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;&nbsp;&nbsp;</td>
				</tr>
			</table>
		</fieldset>
		<div style="width: 350px; margin-left: 15px; margin-top: 20px; margin-bottom: 20px; text-align: right">
			<a href="../../shared/no_js.php" id="go_link" class="standard_link nav_link_first" style="color: #003366">Estrai</a>
		</div>
	</form>
</div>
</body>
</html>
