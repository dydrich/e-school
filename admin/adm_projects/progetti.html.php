<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Elenco progetti</title>
<link rel="stylesheet" href="../../css/main.css" type="text/css" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript">

function del_project(id){
	if(!confirm("Sei sicuro di voler cancellare questo progetto?"))
        return false;
	var url = "project_manager.php";
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {action: 2, _i: id},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		if(dati[0] == "ko"){
			      			_alert("Si e` verificato un errore. Ti preghiamo di riprovare tra poco");
							console.log("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
							return;
			      		}
			      		else if (dati[0] == "stop") {
			      			_alert(dati[1]);
			      			return
			      		}
			      		link = "progetti.php?msg="+dati[1]+"&second=1&offset=<?php print $offset ?>";
			      		//alert(link);
			      		document.location.href = link;
			      		//parent.win.close();
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

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
			count = parseInt($('count_'+ strs[1]).innerHTML);
			if(count > 0) {
				_alert("Impossibile cancellare il progetto: sono presenti dei documenti ad esso associati");
				return false;
			}
			del_project(strs[1]);
	});
});
</script>
</head>
<body>
    <div id="header">
		<div class="wrap">
			<?php include "../header.php" ?>
		</div>
	</div>
	<div class="wrap">
	<div id="main" style="background-color: #FFFFFF; padding-bottom: 30px; width: 100%">
        <table class="admin_table">
        <thead>
            <tr class="admin_title_row">
                <td style="font-weight: bold" colspan="5" align="center">Elenco progetti: pagina <?php print $page ?> di <?php print $pagine ?></td>
            </tr>
            <tr>
                <td style="width: 30%" class="adm_titolo_elenco_first">Progetto</td>
                <td style="width: 40%" class="adm_titolo_elenco">Docenti</td>
                <td style="width: 10%" class="adm_titolo_elenco _center">Documenti</td>
                <td style="width: 10%" class="adm_titolo_elenco _center">AS</td>
                <td style="width: 10%" class="adm_titolo_elenco_last _center">Attivo</td>
            </tr>
            <tr class="admin_row_before_text">
                <td colspan="5"></td>
            </tr>
        </thead>
        <tbody>
            <?php
            $x = 1;
            if($res_projects->num_rows > $limit)
                $max = $limit;
            else
                $max = $res_projects->num_rows;

            while($project = $res_projects->fetch_assoc()){
                if($x > $limit) break;
                
                $sel_teachers = "SELECT rb_utenti.nome, rb_utenti.cognome FROM rb_utenti, rb_responsabili_progetto WHERE rb_utenti.uid = docente AND progetto = ".$project['id_progetto']." ORDER BY cognome, nome";
                //print $sel_teachers;
                $res_teachers = $db->execute($sel_teachers);
                $teachers_string = array();
                while($ins = $res_teachers->fetch_assoc()){
                	array_push($teachers_string, $ins['cognome']." ".$ins['nome']);
                }
                $sel_docs = "SELECT COUNT(*) FROM rb_documents WHERE progetto = ".$project['id_progetto'];
    			$count_docs = $db->executeCount($sel_docs);
            ?>
            <tr class="admin_row" id="row_<?= $project['id_progetto'] ?>">
                <td style="padding-left: 10px; ">
                	<span class="ov_red" style="font-weight: bold"><?php echo $project['nome'] ?></span>
                	<div id="link_<?= $project['id_progetto'] ?>" style="display: none">
                	<a href="dettaglio_progetto.php?id=<?php echo $project['id_progetto'] ?>" class="mod_link">Modifica</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="project_manager.php?action=2&_id=<?php echo $project['id_progetto'] ?>" class="del_link">Cancella</a>
                	</div>
                </td>
                <td style="color: #003366"><?php print join(", ", $teachers_string) ?></td>
				<td style="color: #003366; text-align: right; padding-right: 15px" id="count_<?php echo $project['id_progetto'] ?>"><?php print $count_docs ?></td>
                <td align="center" style="color: #003366"><?php print $project['anno'] ?></td>
                <td align="center" style="color: #003366"><?php if ($project['attivo'] == 1) print "SI"; else print "NO"; ?></td>
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
                <td colspan="5">
                    <a href="dettaglio_progetto.php?id=0" style="margin-right: 10px">Nuovo progetto</a>|
                    <a href="../index.php" style="margin-left: 10px">Torna al menu</a>
                </td>
            </tr>
            <tr class="admin_void">
                <td colspan="5"></td>
            </tr>
        </tfoot>
        </table>
        </div>
        <?php include "../footer.php" ?>
	</div>
</body>
</html>