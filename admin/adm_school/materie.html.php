<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Gestione materie</title>
	<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link href="../../css/general.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">

var del_subject = function(id){
	children = $('#children'+id).html();
	if(children != "") {
		j_alert("error", "Impossibile cancellare la materia: sono presenti delle sotto materie. Cancellare prima le sotto materie.");
		return false;
	}
	if(!confirm("Sei sicuro di voler cancellare questa materia?"))
        return false;
	var url = "subject_manager.php";

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
				link = "materie.php?offset=<?php print $offset ?>";
				j_alert("alert", json.message);
				window.setTimeout(function() {
					document.location.href = link;
				}, 2000);
			}
		}
	});
};

<?php if((count($_SESSION['__school_level__']) > 1) && $_SESSION['__school_order__'] == 0){ echo $page_menu->getJavascript(); } ?>

$(function(){
	$('table tbody > tr').mouseover(function(event){
		//alert(this.id);
		var strs = this.id.split("_");
		$('#link_'+strs[1]).show();
	});
	$('table tbody > tr').mouseout(function(event){
		//alert(this.id);
		var strs = this.id.split("_");
		$('$link_'+strs[1]).hide();
	});

	$('table tbody a.del_link').click(function(event){
		event.preventDefault();
		var strs = this.parentNode.id.split("_");
		del_subject(strs[1]);
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
		<div class="group_head">Elenco materie<div style="float: left"><?php if((count($_SESSION['__school_level__']) > 1) && $_SESSION['__school_order__'] == 0){ $page_menu->printLink(); } ?></div></div>
		<table class="admin_table">
		<thead>
			<tr class="admin_void">
                <td style="width: 20%; text-align: right">

                </td>
            </tr>
            <tr>
                <td style="width: 40%; " class="adm_titolo_elenco_first">Materia</td>
                <td style="width: 50%; " class="adm_titolo_elenco">Sotto materie</td>
                <td style="width: 10%" class="adm_titolo_elenco_last">Pagella</td>
            </tr>
            <tr class="admin_row_before_text">
                <td colspan="3"></td>
            </tr>
		</thead>
		<tbody id="t_body">
            <?php
            $index = 1;
            $printed = array();

            foreach ($subjects as $subject){
            	$children = array();
            	if ($subject->hasChildren()){
            		foreach ($subject->getChildren() as $child) {
            			array_push($children, $child->getDescription());
            		}
            	}
            ?>
            <tr class="admin_row" id="row_<?php echo $subject->getId() ?>">
                <td style="padding-left: 10px; ">
                	<span class="ov_red" style="font-weight: bold"><?php echo $subject->getDescription(); if((count($_SESSION['__school_level__']) > 1) && $_SESSION['__school_order__'] == 0) echo " (".$tipologie[$subject->getSchoolType()]['code'].")";  ?></span>
                	<div id="link_<?php echo $subject->getId() ?>" style="display: none">
                	<a href="dettaglio_materia.php?id=<?php echo $subject->getId() ?>" class="mod_link">Modifica</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="subject_manager.php?action=2&_id=<?php echo $subject->getId() ?>" class="del_link">Cancella</a>
                	</div>
                </td>
                <td id="children<?php echo $subject->getId() ?>"><?php echo join(", ", $children) ?></td>
                <td style="text-align: center"><?php echo ($subject->isInReport() ? "SI" : "NO") ?></td>
            </tr>
            <?php
            	$index++;
            }
            ?>
            </tbody>
            <tfoot>
            <tr class="admin_menu">
                <td colspan="3">
                    <a href="dettaglio_materia.php?id=0" id="new_sub" style="margin-right: 10px">Nuova materia</a>|
                    <a href="../index.php" style="margin-left: 10px">Torna al menu</a>
                </td>
            </tr>

        </tfoot>
        </table>

        </div>
	<p class="spacer"></p>
    </div>
<?php include "../footer.php" ?>
    <?php if((count($_SESSION['__school_level__']) > 1) && $_SESSION['__school_order__'] == 0){ $page_menu->toHTML(); } ?>		
</body>
</html>
