<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Area dirigenza::gestione nuove classi</title>
<link rel="stylesheet" href="../styles.css" type="text/css" />
<link href="/css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="/css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="/js/page.js"></script>
<?php include $_ENV["DOCUMENT_ROOT"]."/js/prototype.php" ?>
<script type="text/javascript" src="/js/window.js"></script>
<script type="text/javascript" src="/js/window_effects.js"></script>
<script type="text/javascript">
var win;

var show_school = function(id, school, code, classes_count){
	win = new Window({className: "mac_os_x", width:400, height:null, zIndex: 100, resizable: true, title: "Modifica scuola", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
	var action = 1;
	if(id == 0)
		action = 2;
	win.getContent().update("<table style='width: 95%; margin: auto; padding-top: 20px;'><tr><td style='width: 40%; font-weight: bold'>Nome</td><td style='width: 60%'><input type='text' style='width: 90%; border: 1px solid #dddddd; font-size: 11px' name='nome' id='nome' value='"+school+"' /></td></tr><tr><td style='width: 40%; font-weight: bold'>Codice (max 3 lett)</td><td style='width: 60%'><input type='text' style='width: 90%; border: 1px solid #dddddd; font-size: 11px' name='code' id='code' value='"+code+"' /></td></tr><tr><td colspan='2' style='padding-top: 20px; text-align: right; padding-right: 5%'><a href='#' onclick='upd_school("+action+", "+id+", "+classes_count+")'>Salva</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' id='del_h' onclick='upd_school(3, "+id+", "+classes_count+")'>Elimina</a></td></tr></table>");
	if(action == 2) 
		$('del_h').style.display = "none";
	win.showCenter(false);
	$('nome').focus();
};

var upd_school = function(action, id, classes_count){
	
	if(action == 3 && classes_count > 0){
		alert("Impossibile cancellare la scuola: sono presenti delle classi. Cancellare prima tutte le classi associate alla scuola"+classes_count);
		return false;
	}
	var code = $('code').value;
	var name = $('nome').value;
	
	var req = new Ajax.Request('manage_schools_from.php',
			  {
			    	method:'post',
			    	parameters: {action: action, class_id: id, class_name: name, class_code: code},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("#");
		            	if(dati[0] == "ko"){
							alert("Errore nella modifica della classe: "+dati[1]+"\n"+dati[2]);
							return false;
		            	}
		            	if(action == "1"){
		            		$('sc'+id).innerHTML = name;
							$('sc'+id).setAttribute("onclick", "show_school("+id+", '"+name+"', '"+code+"', "+classes_count+")");
							
		            	}
		            	else if(action == "2"){
			            	alert(response);
							var row = document.createElement("tr");
							row.setAttribute("id", "tr"+dati[1]);
							td1 = document.createElement("td");
							td1.setAttribute("style", "width: 50%");
							td2 = document.createElement("td");
							td2.setAttribute("style", "width: 50%");
							var lnk = document.createElement("a");
							lnk.setAttribute("id", "sc"+dati[1]);
							lnk.setAttribute("href", "#");
							lnk.setAttribute("style", "font-size: 14px; font-weight: normal; margin-left: 0px; text-decoration: underline");
							lnk.setAttribute("onclick", "show_school("+dati[1]+", '"+name+"', '"+code+"', 0)");
							lnk.appendChild(document.createTextNode(name));
							td1.appendChild(lnk);
							var lnk2 = document.createElement("a");
							lnk2.setAttribute("href", "#");
							lnk2.setAttribute("style", "text-decoration: none; font-weight: bold;");
							lnk2.setAttribute("onclick", "add_class("+dati[1]+")");
							lnk2.appendChild(document.createTextNode("(+)"));
							td2.appendChild(lnk2);
							row.appendChild(td1);
							row.appendChild(td2);
							$('sc_table').appendChild(row);
		            	}
		            	else if(action == "3"){
		            		$('tr'+id).style.display = "none";
		            	}
		            	win.close();
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};

var add_class = function(school){
	win = new Window({className: "mac_os_x", width:200, height:null, zIndex: 100, resizable: true, title: "Nuova classe", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
	win.getContent().update("<table style='width: 95%; margin: auto; padding-top: 20px;'><tr><td style='width: 40%; font-weight: bold'>Classe</td><td style='width: 60%'><input type='text' style='width: 90%; border: 1px solid #dddddd; font-size: 11px' name='nome' id='nome' /></tr><tr><td colspan='2' style='padding-top: 20px; text-align: right; padding-right: 5%'><a href='#' onclick='_upd_class("+school+")'>Salva</a></td></tr></table>");
	win.showCenter(false);
};

var _upd_class = function(school){
	var name = $('nome').value;
	var req = new Ajax.Request('manage_classes_from.php',
			  {
			    	method:'post',
			    	parameters: {action: '2', school_id: school, class_name: name},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("#");
		            	if(dati[0] == "ko"){
							alert("Errore nell'inserimento: "+dati[1]);
							return false;
		            	}
		            	var lnk2 = document.createElement("a");
						lnk2.setAttribute("href", "class_from.php?class_id="+dati[1]);
						lnk2.setAttribute("style", "text-decoration: none; font-weight: bold;");
						lnk2.appendChild(document.createTextNode(name));
						$('sp_'+school).appendChild(lnk2);
						win.close();
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};
</script>
<style>
td {border: 0}
</style>
</head>
<body>
<div class="pagewidth">
	<div class="header">
		<!-- TITLE -->
		<h1><a href="htp://www.scuolamediatre.it">Scuola Media Statale Iglesias</a></h1>
		<h2>Area riservata::dirigenza</h2>
		<!-- END TITLE -->
	</div>
	<?php include "navbar.php" ?>
	<div class="page-wrap">
		<div class="content">	
			<!-- CONTENT -->
            <h3 style="padding-bottom: 30px">Elenco scuole e classi di provenienza<a href="#" onclick="show_school(0, '', '', 0)" style="float: right; font-size: 12px; margin-right: 10%">Nuova scuola</a></h3>
            <table id="sc_table">
            <?php
            $x = 0;
            while(list($k, $school) = each($schools)){
            	list($desc, $code) = explode("#", $school[0]);
            ?>
            <tr id="tr<?php print $k ?>">
            	<td style="width: 50%">
            		<a id="sc<?php print $k ?>" href='#' onclick="show_school(<?php print $k ?>, '<?php print $desc ?>', '<?php print $code ?>', <?php print count($school[1]) ?>)" style='font-size: 14px; font-weight: normal; margin-left: 0px; text-decoration: underline'>
					<?php print $desc ?>
				</a>
				</td>
				<td style="width: 50%" id="tr_2_<?php print $k ?>">
				<span id="sp_<?php print $k ?>">
			<?php
				foreach($school[1] as $s){
			?>
            	<a href="class_from.php?class_id=<?php print $s['class_id'] ?>" style="text-decoration: none; font-weight: bold;"><?php print $s['class'] ?></a>&nbsp;&nbsp;&nbsp;&nbsp;
            <?php 
            	}
            ?>
            	</span>
            	(<a href="#" id="last<?php print $k ?>" onclick="add_class(<?php print $k ?>)" style="text-decoration: none; font-weight: bold;">+</a>)&nbsp;&nbsp;&nbsp;&nbsp;
            	</td>
            </tr>
            <?php
            	$x++;
            }
            ?>
            </table>
        </div>
		<div class="sidebar">	
			<?php include 'menu.php'; ?>
		</div>
		<div class="clear"></div>		
	</div>
    <?php include "../footer.php" ?>	
</div>
</body>
</html>