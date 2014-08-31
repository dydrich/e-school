<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Elenco genitori</title>
<link href="../../css/reg.css" rel="stylesheet" />
<link href="../../css/general.css" rel="stylesheet" />
<link rel="stylesheet" href="../../css/jquery/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
var id_alunni = new Array;

var filtro = function(){
	cls = $('#mysel').val();
	if(cls == 0)
		document.location.href = "genitori.php";
	else
		document.location.href = "genitori.php?classe="+cls;
};

var go = function(val){
	if(val == 1){
		if(trim(document.forms[0].parent.value) == "")
			document.location.href = "genitori.php";
		else{
			document.location.href = "genitori.php?nome="+trim(document.forms[0].parent.value);
		}
	}
	else{
		if(trim(document.forms[0].student.value) == "")
			document.location.href = "genitori.php";
		else{
			document.location.href = "genitori.php?aname="+trim(document.forms[0].student.value);
		}
	}
};

var filtro_nome = function(val){
	if(val == 2){
		$('#stud_td').html("<input type='text' name='student' style='font-size: 10px; width: 150px; border: 1px solid #CCCCCC; color: #777' />&nbsp;&nbsp;<input type='button' value='filtra' style='border: 1px solid #CCCCCC; width: 40px' onclick='go(2)' />");
	}
	else{
		$('#$par_td').html("<input type='text' name='parent' style='font-size: 10px; width: 150px; border: 1px solid #CCCCCC; color: #777' />&nbsp;&nbsp;<input type='button' value='filtra' style='border: 1px solid #CCCCCC; width: 40px' onclick='go(1)' />");
	}
};

var del_user = function(id){
	if(!confirm("Sei sicuro di voler cancellare questo utente?"))
        return false;
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
				link = "genitori.php?offset=<?php print $offset ?>&school_order=<?php echo $school_order ?>";
				j_alert("alert", "Utente cancellato correttamente");
				window.setTimeout(function(){
					document.location.href = link;
				}, 3000);
			}
		}
	});
};

var IE = document.all?true:false;
var tempX = 0;
var tempY = 0;

<?php echo $page_menu->getJavascript() ?>
$(function(){
	load_jalert();

<?php if(count($ordered_parents) > 0) { ?>
	$('table tbody > tr').mouseover(function(event){
			//alert(this.id);
			var strs = this.id.split("_");
			if($('#link_'+strs[1]))
				$('#link_'+strs[1]).show();
	});
	$('table tbody > tr').mouseout(function(event){
			//alert(this.id);
			var strs = this.id.split("_");
			if($('#link_'+strs[1]))
				$('#link_'+strs[1]).hide();
	});
	$('table tbody a.del_link').click(function(event){
		event.preventDefault();
		var strs = this.parentNode.id.split("_");
		del_user(strs[1]);
	});

<?php } ?>
});

</script>
<title>Registro elettronico</title>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "../adm_users/menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head"><div style="float: left"><?php $page_menu->printLink() ?></div> Elenco genitori: estratti <?php echo $_SESSION['count_parents'] ?> (<?php echo $first." - ".$last ?>)</div>
    <form class="no_border">
    <?php $page_menu->toHTML() ?>
        <table class="admin_table">
        <thead>
            <tr>
                <td class="adm_titolo_elenco_first" style="width: 30%" id="par_td">Nome e cognome</td>
                <td class="adm_titolo_elenco" style="width: 35%" id="stud_td">Alunni</td>
                <td class="adm_titolo_elenco_last _center" style="width: 35%" id="class_td">Classi</td>
            </tr>
            <tr class="admin_row_before_text">
                <td id="row_before" colspan="3" style="color: red"></td>
            </tr>
        </thead>
        <tbody>
            <?php
            if(count($ordered_parents) < 1) {
            ?>	
            <tr style="height: 150px; text-align: center">
                <td colspan="3" style="font-size: 1.2em; font-weight: bold">Nessun genitore presente</td>
            </tr>
            <?php	
            }
            else {
	            $index = 0;
	
	            $id_genitore = 0;
	            $classe = "";
	            $final_user = "";
	            $final_uid = 0;
	            $figli = array();
	            $classi = array();
	            $_max = ($offset + $limit) -1;
	            if(count($ordered_parents) > $limit)
		            $max = $limit;
	            else
		            $max = count($ordered_parents);
	            foreach ($ordered_parents as $user){
		            if($offset > 0 && ($index < $offset)) {
	            		$index++;
	            		continue;
	            	}
	            	//echo $index . "==" .$_max ."<br>";
	            	if ($index >= $_max) {
	            		//break;
	            	}
	                
	                if($id_genitore != $user['uid'] && $id_genitore != 0){
	                	$index++;
            ?>
            <tr class="admin_row" id="row_<?php print $final_uid ?>">
                <td>
                	<span class="ov_red" style="font-weight: bold"><?php print $final_user ?></span>
                	<div id="link_<?php print $final_uid ?>" style="display: none">
                	<a href="dettaglio_genitore.php?id=<?php print $final_uid ?>&school_order=<?php echo $_GET['school_order'] ?>" class="mod_link">Modifica</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="parents_manager.php?action=2&_i=<?php print $final_uid ?>&school_order=<?php echo $_GET['school_order'] ?>" class="del_link">Cancella</a>
                	</div>
                </td>
                <td><?php print join(", ", $figli); ?></td>
                <td class="_center"><?php print join(", ", $classi); ?></td>
            </tr>
            <?php           		
	            		$figli = array_slice($figli, 0, 0);
	                	$classi = array_slice($classi, 0, 0);
					}
	
					if(!in_array($user['al_name'], $figli)) {
						array_push($figli, $user['al_name']);
					}
					$class_string = $user['desc_classe']." (";
					if($classes_table == "rb_classi"){
						$class_string .= $user['codice']." - ";
					}
					$class_string .= $user['sede'].")";
					if(!in_array($class_string, $classi)) {
						array_push($classi, $class_string);
					}
					$id_genitore = $user['uid'];
	                
	                $final_user = $user['nome'];
	                $final_uid = $user['uid'];
	            }
	            //print(count($figli)."-".count($classi));
	            $index++;
            ?>
            <tr class="admin_row" id="row_<?php print $final_uid ?>">
                <td>
                	<span class="ov_red" style="font-weight: bold"><?php print $final_user ?></span>
                	<div id="link_<?php print $final_uid ?>" style="display: none">
                	<a href="dettaglio_genitore.php?id=<?php print $final_uid ?>&school_order=<?php echo $_GET['school_order'] ?>" class="mod_link">Modifica</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="parents_manager.php?action=2&id=<?php print $final_uid ?>&school_order=<?php echo $_GET['school_order'] ?>" class="del_link">Cancella</a>
                	</div>
                </td>
                <td><?php print join(", ", $figli); ?></td>
                <td class="_center"><?php print join(", ", $classi); ?></td>
            </tr>
            <?php
            	//include "../../shared/navigate.php";
            }
            ?>
        </tbody>
        <tfoot>
            <tr class="admin_menu">
                <td colspan="3" >
                	<a href="dettaglio_genitore.php?id=0&school_order=<?php echo $_GET['school_order'] ?>" class="standard_link nav_link_first">Nuovo genitore</a>|
                    <a href="../index.php" class="standard_link nav_link_last">Torna al menu</a>
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
