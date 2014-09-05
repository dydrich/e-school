<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="author" content="" />
<link rel="stylesheet" href="../../css/site_themes/blue_red/reg.css" type="text/css" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript">
var win;
var messages = new Array('', 'Alunno inserito con successo', 'Alunno cancellato con successo', 'Alunno modificato con successo', 'Account modificato con successo');
function user(id, type){
	/*
	type: 0 = new user
	type: 1 = mod. data
	type: 2 = mod. account
	*/
    usr_win = new Window({className: "mac_os_x", url: "dettaglio_alunno.php?offset=<?php echo $offset ?>&type="+type+"&order=<?php if (isset($_REQUEST['order'])) echo $_REQUEST['order'] ?>&id="+id,  width:650, height:450, zIndex: 100, resizable: true, title: "Dettaglio alunno", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
    usr_win.showCenter(false);
}
    
function filter(order){
    win = new Window({className: "mac_os_x", url: "filter.php?order="+order,  width:400, height:250, zIndex: 100, resizable: true, title: "Filtro alunni", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
	win.showCenter(false);
}

function del_user(id){
	if(!confirm("Sei sicuro di voler cancellare questo alunno?"))
        return false;
	var url = "student_manager.php";
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {action: 2, _i: id},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		if(dati[0] == "ko"){
							alert("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
							return;
			      		}
			      		link = "alunni.php?msg=2&second=1&offset=<?php print $offset ?>";
			      		//alert(link);
			      		document.location.href = link;
			      		//parent.win.close();
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

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
	$$('table tbody a.del_link').invoke("observe", "click", function(event){
		event.preventDefault();
		var strs = this.parentNode.id.split("_");
		del_user(strs[1]);
	});
	$('filter_button').observe("click", function(event){
		event.preventDefault();
		filter('<?php print $current_order ?>');
	});
});

</script>
</head>
<body <?php if(isset($_REQUEST['msg'])){ ?>onload="openInfoDialog(messages[<?php print $_REQUEST['msg'] ?>], 2)"<?php } ?>>
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
</body>
</html>
