<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Classi</title>
<link href="../../css/reg.css" rel="stylesheet" />
<link href="../../css/general.css" rel="stylesheet" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript">
var win;
var classi = new Array();
<?php 
while($class = $res_cls->fetch_assoc()){
?>
classi.push('<?php echo $class['anno_corso'].$class['sezione'] ?>');
<?php 
}
?>

var coord = function(classe){
	win = new Window({className: "mac_os_x", url: "coord.php?offset=<?php print $offset ?>&id="+classe,  width:450, height: 160, zIndex: 100, resizable: true, title: "Coordinatore di classe", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
	win.showCenter(true);
};

var update_class = function(class_id, action, students){
	if(action == 'delete'){
		if(students > 0){
			alert("Impossibile cancellare la classe: ci sono degli studenti assegnati ad essa. Elimina o sposta gli studenti prima di procedere con la cancellazione");
			return false; 
		}
		if(!confirm("Sei sicuro di voler cancellare questa classe?")){
			return false;
		}	
	}
	
	var url = "class_manager.php";
    req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {cls: class_id, action: action},
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
				    	//alert(response);
			    		dati = response.split("|");
			    		if(dati[0] == "ok"){
				    		_alert("Classe cancellata correttamente");
							$('row_'+class_id).hide();
			            }
			            else{
			                alert("Operazione non riuscita. Si prega di riprovare tra qualche minuto.");
			                console.log("Query: "+dati[1]+"\nErrore: "+dati[2]);
			                return;
			            }
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};

<?php echo $page_menu->getJavascript() ?>

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
	$$('table tbody a.coord_link').invoke("observe", "click", function(event){
		event.preventDefault();
		var strs = this.parentNode.id.split("_");
		coord(strs[1], 0);
	});
	$$('table tbody a.del_link').invoke("observe", "click", function(event){
		event.preventDefault();
		var strs = this.parentNode.id.split("_");
		update_class(strs[1], 'delete', this.readAttribute("st"));
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
		<div class="group_head"><div style="float: left"><?php $page_menu->printLink() ?></div> Elenco classi <?php echo truncateString($ordini[$school_order]['tipo'], 57) ?> <?php if(isset($sede)) echo " [{$sede}]" ?>: pagina <?php print $page ?> di <?php print $pagine ?></div>
        <form method="post">
        <table class="admin_table">
        <thead>
            <tr>
            	<td style="padding-left: 10px; width: 23%" class="adm_titolo_elenco_first"></td>
                <td style="width: 70%; padding-left: 15px" class="adm_titolo_elenco">Consiglio di classe</td>
                <td style="padding-left: 10px; width: 7%" class="adm_titolo_elenco_last _center">Alunni</td>
            </tr>
            <tr class="admin_row_before_text">
                <td colspan="3"></td>
            </tr>
        </thead>
        <tbody>
            <?php
            $res_cls->data_seek(0);
            $x = 1;
            if($res_cls->num_rows > $limit)
                $max = $limit;
            else
                $max = $res_cls->num_rows;

            while($class = $res_cls->fetch_assoc()){
                if($x > $limit) break;
                $cdc = "";
                // estrazione consiglio di classe
                $sel_state = "SELECT count(*) AS count FROM rb_cdc WHERE id_anno = $anno AND id_classe = ".$class['id_classe']." AND id_docente IS NOT NULL AND id_materia <>11";
                //print $sel_state;
                $not_nulls = $db->executeCount($sel_state);
                
                $sel_cdc = "SELECT uid, nome, cognome FROM rb_utenti, rb_docenti, rb_cdc WHERE uid = rb_docenti.id_docente AND rb_docenti.id_docente = rb_cdc.id_docente AND rb_cdc.id_anno = $anno AND rb_cdc.id_classe = ".$class['id_classe'];
                $res_cdc = $db->executeQuery($sel_cdc);
                $num_docenti = $res_cdc->num_rows;
                $ids = array();
                if($num_docenti > 0){ 
                    while($doc = $res_cdc->fetch_assoc()){
						if(!in_array($doc['uid'], $ids)){
                        	$cdc .= $doc['cognome']." ".substr($doc['nome'], 0, 1)."., ";
                       	}
                       	$ids[] = $doc['uid'];
                    }
                }
                $sel_sos = "SELECT uid, nome, cognome FROM rb_utenti, rb_assegnazione_sostegno WHERE uid = rb_assegnazione_sostegno.docente AND rb_assegnazione_sostegno.anno = $anno AND rb_assegnazione_sostegno.classe = ".$class['id_classe'];
                $res_sos = $db->executeQuery($sel_sos);
                if($res_sos->num_rows > 0){
                	while($doc = $res_sos->fetch_assoc()){
                		if(!in_array($doc['uid'], $ids)){
                			$cdc .= $doc['cognome']." ".substr($doc['nome'], 0, 1)."., ";
                		}
                		$ids[] = $doc['uid'];
                	}
                }
                $cdc = substr($cdc, 0, (strlen($cdc) - 2));
                
                $sel_students_count = "SELECT COUNT(*) FROM rb_alunni WHERE id_classe = ".$class['id_classe'];
                $stud_count = $db->executeCount($sel_students_count);
            ?>
            <tr class="admin_row" id="row_<?php echo $class['id_classe'] ?>">
            	
            	<td><span style="font-weight: bold"><?php print $class['anno_corso']." ".$class['sezione'] ?></span><br /><span id="" style="margin-left: 0px; margin-right: 30px"><?php echo $class['nome'] ?></span></td>
            	<td style="padding-left: 10px; ">
                	<span id="span_<?php echo $class['id_classe'] ?>" class="ov_red" <?php if($cdc != "") print("style='font-weight: normal'") ?>><?php if($cdc != "") print("$cdc"); else print "Non presente" ?></span>
                	<div id="link_<?php echo $class['id_classe'] ?>" style="display: none; vertical-align: bottom">
                	<a href="classe.php?id=<?php echo $class['id_classe'] ?>&offset=<?php echo $offset ?>&school_order=<?php echo $school_order ?>" class="ren_link">Modifica</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="../../shared/no_js.php" class="del_link" st="<?php echo $stud_count ?>">Cancella</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="cdc.php?id=<?php echo $class['id_classe'] ?>&offset=<?php echo $offset ?>" class="cdc_link">Consiglio di classe</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="cdc.php?id=<?php echo $class['id_classe'] ?>&coord=1" class="coord_link">Coordinatore</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="orario.php?cls=<?php print $class['id_classe'] ?>&tp=<?php print $class['tempo_prolungato'] ?>&desc=<?php print $class['anno_corso']."".$class['sezione'] ?>&offset=<?php echo $offset ?>">Orario</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="alunni.php?id_classe=<?php print $class['id_classe'] ?>&offset=<?php echo $offset ?>">Alunni</a>
                	</div>
                </td>
                <td style="color: #003366; text-align: center"><span><?php print $stud_count ?></span></td>
                
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
                <td colspan="4">
                	<a href="classe.php?id=0&offset=<?php echo $offset ?>&school_order=<?php echo $_GET['school_order'] ?>" id="nc_link" class="nav_link_first">Nuova classe</a>|
                    <a href="<?php echo $goback_link ?>" class="nav_link_last"><?php echo $goback ?></a>
                </td>
            </tr>
            <tr class="admin_void">
                <td colspan="4">&nbsp;&nbsp;&nbsp;</td>
            </tr>
        </tfoot>
        </table>
        </form>
        </div>
        <?php include "../footer.php" ?>
	</div>
	<?php $page_menu->toHTML() ?>
</body>
</html>