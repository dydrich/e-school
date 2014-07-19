<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Consiglio di classe</title>
	<link href="../../css/reg.css" rel="stylesheet" />
	<link href="../../css/general.css" rel="stylesheet" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript">

function upd_cdc(classe, materia, sel){
    var doc = sel.value;
    var text = sel.options[sel.selectedIndex].text;
    if(doc == 0){
        alert("Docente non selezionato");
        return;
    }
    var url = "update_cdc.php";
    var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {cls: classe, mat: materia, doc: doc},
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
			    		//alert(response);
			    		dati = response.split("|");
			    		if(dati[0] != "ko"){
							//alert(response);
			            }
			            else{
			                alert("Aggiornamento non riuscito. Query: "+dati[1]+"\nErrore: "+dati[2]);
			                return;
			            }
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
	
}

function add_teacher(cls){
    win = new Window({className: "mac_os_x", url: "add_teacher.php?cls="+cls,  width:350, height:200, zIndex: 100, resizable: true, title: "Aggiungi docente", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
	win.showCenter(false);
}

var del_teacher = function(uid, cls){
	var url = "update_cdc.php";
    var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {cls: cls, mat: 27, doc: uid, action: "del"},
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
			    		//alert(response);
			    		dati = response.split("|");
			    		if(dati[0] != "ko"){
							//alert(response);
			            }
			            else{
			                alert("Aggiornamento non riuscito. Query: "+dati[1]+"\nErrore: "+dati[2]);
			                return;
			            }
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
	  $('row_'+uid).setStyle({display: "none"});
	  $('row_'+uid).setAttribute("id", "");
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
		<div class="group_head">Consiglio di classe: <?php print $classe['anno_corso'].$classe['sezione'] ?> - <?php echo $classe['nome'] ?></div>
	    <form action="cdc.php?upd=1" method="post" class="popup_form" style="width: 90%">
	    <div style="text-align: left">
	    <table style="width: 95%; margin: auto; border-spacing: 0" >
	    <?php
	    $res_mat->data_seek(0);
	    while($mat = $res_mat->fetch_assoc()){
	        if($mat['idpadre'] != "")
	            $mt = $mat['idpadre'];
	        else
	            $mt = $mat['id_materia'];
	        $sel_doc = "SELECT uid, nome, cognome FROM rb_utenti, rb_docenti WHERE uid = id_docente AND materia = $mt ORDER BY cognome, nome";
	        //print $sel_doc;
	        try{
	        	$res_doc = $db->executeQuery($sel_doc);
	        } catch (MySQLException $ex){
	        	$ex->alert();
	        }
	    ?>
	        <tr>
	            <td class="popup_title" style="width: 50%; padding-top: 1px; padding-bottom: 1px; font-weight: bold"><?php print $mat['materia'] ?></td>
	            <td style="width: 50%; padding-top: 3px; padding-bottom: 3px">
	                <select onchange="upd_cdc(<?php print $classID ?>, <?php print $mat['id_materia'] ?>, this)" name="sel<?php print $mat['id_materia'] ?>" id="sel<?php print $mat['id_materia'] ?>" style="width: 90%; font-size: 11px">
	   	<?php if($res_doc->num_rows > 1){ ?>
	                    <option value="0">Nessuno</option>
	    <?php } ?>
	    <?php
	        while($dc = $res_doc->fetch_assoc()){
	            if($dc['uid'] == $consiglio[$mat['id_materia']]){
	    ?>
	                    <option value="<?php print $dc['uid'] ?>" selected="selected"><?php print $dc['cognome']." ".$dc['nome'] ?></option>
	    <?php
	            }
	            else{
	    ?>
	                    <option value="<?php print $dc['uid'] ?>"><?php print $dc['cognome']." ".$dc['nome'] ?></option>
	    <?php
	            }
	        }
	    ?>
	                </select>
		<?php if($res_doc->num_rows == 1){ ?>
					<script type="text/javascript">
					upd_cdc(<?php print $classID ?>, <?php print $mat['id_materia'] ?>, $('sel<?php print $mat['id_materia'] ?>'));
					</script>
		<?php } ?>
	            </td>
	        </tr>
	        <?php 
	    	} 
	    	
	        ?>
	        <tr>
	        	<td class="popup_title" style="width: 50%; padding-top: 1px; padding-bottom: 1px; font-weight: bold; border-left: 1px solid #BBB; border-top: 1px solid #BBB; border-bottom: 1px solid #BBB; border-top-left-radius: 10px; border-bottom-left-radius: 10px; ">Sostegno</td>
	        	<td style="width: 50%; padding-top: 3px; padding-bottom: 3px; border-right: 1px solid #BBBBBB; border-top: 1px solid #BBBBBB; border-bottom: 1px solid #BBBBBB; border-bottom-right-radius: 10px; border-top-right-radius: 10px;">
<?php 
if ($res_sost->num_rows < 1){
?>	        	
					<span>Nessuno</span>
<?php 
}
else if ($res_sost->num_rows > 0){
	while ($row = $res_sost->fetch_assoc()){
?>
					<div id="row_<?php echo $row['uid'] ?>">
					<span><?php echo $row['cognome']." ".$row['nome'] ?> (<?php echo $row['ore'] ?> ore)</span>
					<span style="float: right; margin-right: 40px"><a href="#" onclick="del_teacher(<?php echo $row['uid'] ?>, <?php echo $classID ?>)" style="color: red; font-weight: bold">x</a></span>
					</div>
<?php
	}
}
?>
					<span style="float: right; margin-right: 30px"><a href="#" onclick="add_teacher(<?php print $classID ?>)">Aggiungi</a></span><br />
	        	</td>
	        </tr>
	        <tr>
	            <td colspan="2" style="height: 15px">&nbsp;&nbsp;&nbsp;</td>
	        </tr>
	    </table>

	    </div>
		</form>
		<div style="margin: 10px 10px 0 0 ; width: 95%; text-align: right">
			<a href="classi.php?school_order=<?php echo $classe['ordine_di_scuola'] ?>" id="close_btn" class="standard_link">Torna all'elenco classi</a>
		</div>
	</div>
	<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
</body>
</html>