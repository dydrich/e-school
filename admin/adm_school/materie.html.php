<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Gestione materie</title>
<link rel="stylesheet" href="../../css/site_themes/blue_red/reg.css" type="text/css" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript">
var messages = new Array('', 'Materia inserita con successo', 'Materia cancellata con successo', 'Materia modificata con successo');
var index = 0;
<?php 
if(isset($_REQUEST['msg'])){
?>
index = <?php print $_REQUEST['msg'] ?>;
<?php } ?>

function del_subject(id){
	children = $('children'+id).innerHTML;
	if(children != "") {
		_alert("Impossibile cancellare la materia: sono presenti delle sotto materie. Cancellare prima le sotto materie.");
		return false;
	}
	if(!confirm("Sei sicuro di voler cancellare questa materia?"))
        return false;
	var url = "subject_manager.php";
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {action: 2, _i: id},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		if(dati[0] == "ko"){
				      		_alert("Impossibile completare l'operazione. Ti preghiamo di riprovare tra poco");
							console.log("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
							return;
			      		}
			      		link = "materie.php?offset=<?php print $offset ?>";
			      		_alert(messages[2]);
			      		window.setTimeout("document.location.href = link", 2000);
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

<?php if((count($_SESSION['__school_level__']) > 1) && $_SESSION['__school_order__'] == 0){ echo $page_menu->getJavascript(); } ?>

document.observe("dom:loaded", function(){
	$$('table tbody > tr').invoke("observe", "mouseover", function(event){
		//alert(this.id);
		var strs = this.id.split("_");
		$('link_'+strs[1]).setStyle({display: 'block'});
	});
	$$('table tbody > tr').invoke("observe", "mouseout", function(event){
		//alert(this.id);
		var strs = this.id.split("_");
		$('link_'+strs[1]).setStyle({display: 'none'});
	});

	$$('table tbody a.del_link').invoke("observe", "click", function(event){
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
