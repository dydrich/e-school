<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Elenco alunni</title>
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript">
var stid = 0;
var IE = document.all?true:false;
if (!IE) document.captureEvents(Event.MOUSEMOVE);
var tempX = 0;
var tempY = 0;

function repeating_switch(student, is_repeating){
	var rep = 0;
	if(is_repeating == 0){
		rep = 1;
	}
	var row = 'row'+student+'_field5';
	if($(row).innerHTML == "SI")
		rep = 0;
	else
		rep = 1;
	//alert(rep);
	var req = new Ajax.Updater({success: row}, 'update_student.php',
			  {
			    	parameters: {student: student, repeat: rep, action: 'switch'},
			  });
}

var new_address = true;

function change_phone(link,id_alunno){
	//alert("ID alunno in change2: "+id_alunno);
     //link.innerText e link.text sono stessa cosa, c'è differenza tra ie e firefox
    textname = "phn"+id_alunno;
    field_name = "p_inp"+id_alunno;
	if(link.innerText){
		testovecchio = link.innerText;
	}
	else{
		testovecchio = link.text;
	}
	//riscrivo il contenuto del div
	if(testovecchio == "Non presente")
		testovecchio = "";
	$(textname).innerHTML="<input type='text' style='border: 1px solid gray; font-size: 10px; width: 70%' id='"+field_name+"' value='"+testovecchio+"'> <input type='button' style='border: 1px solid; font-size: 10px' value='Registra' onclick=\"phone(\'"+field_name+"\', "+id_alunno+")\">";
}

function phone(field, student){
	//alert(rep);
	phone = $(field).value;
	var row = "phn"+student;
	var req = new Ajax.Updater({success: row}, 'update_student.php',
			  {
			    	parameters: {student: student, phone: phone, action: "phone"},
			  });
}

function change_address(link,id_alunno){
	//alert("ID alunno in change2: "+id_alunno);
     //link.innerText e link.text sono stessa cosa, c'è differenza tra ie e firefox
    textname = "add"+id_alunno;
    field_name = "a_inp"+id_alunno;
	if(link.innerText){
		testovecchio = link.innerText;
	}
	else{
		testovecchio = link.text;
	}
	//riscrivo il contenuto del div
	if(testovecchio == "Non presente")
		testovecchio = "";
	$(textname).innerHTML="<input type='text' style='border: 1px solid gray; font-size: 10px; width: 50%' id='"+field_name+"' value='"+testovecchio+"'> <input type='button' style='border: 1px solid; font-size: 10px' value='Registra' onclick=\"address(\'"+field_name+"\', "+id_alunno+")\">";
}

function address(field, student){
	//alert(rep);
	_address = $(field).value;
	var row = "add"+student;
	var req = new Ajax.Updater({success: row}, 'update_student.php',
			  {
			    	parameters: {student: student, address: _address, action: "address"},
			  });
}

var show_profile = function(id){
	$('context_menu').style.display = "none";
	var win = new Window({className: "mac_os_x",  width:400, height:260, zIndex: 100, resizable: true, title: "Profilo alunno ", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true, url: "student_profile.php?sid="+id});	
	//win.getContent().update("<div style='font-weight: bold; font-size: 12px; text-align: center; margin-top: 20px' class='Titolo'>Funzione strumentale per la gestione e il monitoraggio del POF</div><div style='text-align: left; font-weight: normal; font-size: 11px; padding: 10px; margin-top: 20px; padding-bottom: 35px'><ul  style='margin-left: 20px;'><li style='list-style-type: disc;'>Gestione, revisione e monitoraggio del POF</li><li style='list-style-type: disc;'>Monitoraggio dei progetti extra-curricolo</li><li style='list-style-type: disc;'>Coordinamento delle funzioni strumentali</li></ul></div>");     
	win.showCenter(true);
};

var upd_date = function(student){
	//alert("Update student "+id+" with "+$F("date_"+id));
	var req = new Ajax.Request('update_student.php',
		  {
		    	method:'post',
		    	parameters: {student: student, action: "date", date: $F('date_'+student)},
		    	onSuccess: function(transport){
		      		var response = transport.responseText || "no response text";
		      		var dati = response.split("|");
		      		if (dati[0] == "ok"){
		      		}
		    	},
		    	onFailure: function(){ _alert("Si e' verificato un errore..."); return; }
		  });
};

function show_menu(e, _stid){
	if (IE) { 
        tempX = event.clientX + document.body.scrollLeft;
        tempY = event.clientY + document.body.scrollTop;
    } else {  
        tempX = e.pageX;
        tempY = e.pageY;
    }  
    
    if (tempX < 0){tempX = 0;}
    if (tempY < 0){tempY = 0;}  
    $('context_menu').setStyle({top: parseInt(tempY)+"px"});
    $('context_menu').setStyle({left: parseInt(tempX)+"px"});
    $('context_menu').show();
    stid = _stid;
    return false;
}

function change_name(num){
	$('context_menu').style.display = "none";
	new_lname = prompt("Inserisci il cognome:");
	if(new_lname == null)
		return false;
	new_fname = prompt("Inserisci il nome:");
	if(new_fname == null)
		return false;
	var url = "change_name.php";
	$('lname').value = new_lname;
	$('fname').value = new_fname;
	$('stid').value = stid;
	//alert(url);
	var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: $('testform').serialize(true),
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split("#");
			      		if(dati[0] == "ko"){
							alert("Nome non modificato. Dettaglio: "+dati[1]+"---"+dati[2]);
							return false;
			     		}
			     		else{
			     			alert("Nome modificato");	     			
			     		}
			     		var anchor = $('row'+stid+"_field1").firstChild;
			     		anchor.innerHTML = dati[1];
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

</script>
<style>
tbody tr:hover {
	background-color: rgba(211, 222, 199, 0.6);
}
tbody a {
	text-decoration: none
}
</style>
</head> 
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "class_working.php" ?>
</div>
<div id="left_col">
<div class="page_label">
	Elenco alunni (<?php print $res_alunni->num_rows ?>)
</div>
<div class="outline_line">
	<div class="outline_cell wd_30">Nome e cognome</div>
	<div class="outline_cell wd_15 _right">Data nascita</div>
	<div class="outline_cell wd_35">Indirizzo</div>
	<div class="outline_cell wd_15">Telefono</div>
	<div class="outline_cell wd_5">Rip.</div>
</div>
<table id="std_list" class="wd_95 _elem_center">
<thead>
</thead>
<tbody>
	<?php 
	$background = "";
	$idx = 1;
	while($alunno = $res_alunni->fetch_assoc()){
		$ripetente = "NO";
		if($alunno['ripetente'] == 1)
			$ripetente = "SI";
			
		// estraggo l'indirizzo
		$address = "Non presente";
		$sel_add = "SELECT * FROM rb_indirizzi_alunni WHERE id_alunno = ".$alunno['id_alunno'];
		$res_add = $db->execute($sel_add);
		if($res_add->num_rows > 0){
			$add = $res_add->fetch_assoc();
			if($add['indirizzo'] != "")
				$address = $add['indirizzo'];
		}
		
		// estraggo il telefono
		$phone = "Non presente";
		$sel_add = "SELECT * FROM rb_indirizzi_alunni WHERE id_alunno = ".$alunno['id_alunno'];
		$res_add = $db->execute($sel_add);
		if($res_add->num_rows > 0){
			$add = $res_add->fetch_assoc();
			if($add['telefono1'] != "")
				$phone = $add['telefono1'];
		}
		
		$data_nascita = "--";
		if ($alunno['data_nascita'] != ""){
			$data_nascita = format_date($alunno['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/");
		}
		
	?>
	<tr class="bottom_decoration">
		<td id="row<?php print $alunno['id_alunno'] ?>_field1" class="wd_35 first_cell"><a href="#" style="font-weight: normal" onclick="<?php if((!$_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())) && (!$_SESSION['__user__']->isAdministrator()) ) print("return false;"); else { ?>show_menu(event, <?php print $alunno['id_alunno'] ?>)<?php } ?>"><?php print ($alunno['cognome']." ".$alunno['nome']) ?></a></td>
		<td id="row<?php print $alunno['id_alunno'] ?>_field2" class="wd_10 _center">
		<input type="text" id="date_<?php print $alunno['id_alunno'] ?>" name="date_<?php print $alunno['id_alunno'] ?>" value="<?php print $data_nascita ?>" style="width: 99%; background-color: #F3F3F6; border: 1px solid rgba(111,111,111, 0); font-size: 11px; text-align: center" onblur="upd_date(<?php echo $alunno['id_alunno'] ?>)" />
		<script type="text/javascript">
        <?php 
        if(isset($alunno) && $alunno['data_nascita'] != ""){
           	list($y, $m, $d) = explode("-", $alunno['data_nascita']);
          	$m--;
        }
        ?>
            Calendar.setup({
            date		: new Date(<?php if(isset($alunno) && $alunno['data_nascita'] != "") print("$y, $m, $d") ?>),
			inputField	: "date_<?php print $alunno['id_alunno'] ?>",
			ifFormat	: "%d/%m/%Y",
			daFormat	: "%d/%m/%Y",
			showsTime	: false,
			firstDay	: 1,
			timeFormat	: "24",			
		});
	    </script>
		</td>
		<td class="wd_35 _center"><span id="add<?php print $alunno['id_alunno'] ?>"><a href="#" id="row<?php print $alunno['id_alunno'] ?>_field3" onclick="change_address(this, <?php print $alunno['id_alunno'] ?>)" style="font-weight: normal; color: #303030"><?php print $address ?></a></span></td>
		<td class="wd_15 _center"><span id="phn<?php print $alunno['id_alunno'] ?>"><a id="row<?php print $alunno['id_alunno'] ?>_field4" href="#" onclick="change_phone(this, <?php print $alunno['id_alunno'] ?>, event)" style="font-weight: normal; color: #303030"><?php print $phone ?></a></span></td>
		<td class="wd_5 _center"><a id="row<?php print $alunno['id_alunno'] ?>_field5" href="#" onclick="repeating_switch(<?php print $alunno['id_alunno'] ?>, <?php print $alunno['ripetente'] ?>)" style="font-weight: normal; color: #303030"><?php print $ripetente ?></a></td>
	</tr>
	<?php 
		$idx++;
	}
	?>
</tbody>
</table>
<form id="testform" method="post">
<p>
	<input type="hidden" name="fname" id="fname" />
	<input type="hidden" name="lname" id="lname" />
	<input type="hidden" name="stid" id="stid" />
</p>
</form>
</div> 
<p class="spacer"></p>
<!-- menu contestuale -->
    <div id="context_menu" style="text-align: left; position: absolute; width: 150px; height: 60px; display: none; ">
    	<a style="text-decoration: none" href="#" onclick="show_profile(stid)">Visualizza il profilo</a><br />
    	<a style="text-decoration: none" href="#" onclick="change_name(0)">Modifica il nome</a><br />
    	<a style="text-decoration: none" href="#" onclick="$('context_menu').style.display = 'none'">Chiudi</a>
    </div>
<!-- fine menu contestuale -->
</div>
<?php include "../footer.php" ?>
</body>
</html>
