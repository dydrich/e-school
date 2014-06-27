<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Firma il registro di classe</title>
<link rel="stylesheet" href="reg_classe_popup.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/prototype.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript">
var IE = document.all?true:false;
if (!IE) document.captureEvents(Event.MOUSEMOVE);

var tempX = 0;
var tempY = 0;

var id_ore = new Array();
<?php 
while(list($k, $v) = each($ids)){
?>
id_ore[<?php print $k ?>] = <?php print $v ?>; 
<?php } ?>

function firma(){
    //alert("ok");
    $('hid').hide();
    var ora = $F('ora');
    var mat = $F('mat');
    var id_reg = $F('id_reg');
    var plus = $F('plus');
    var upd = 1;
    if($(ora+'ora').innerHTML == "Firma"){
        upd = 0;
    }
    ido = id_ore[ora];
    var url = "firma.php";
    var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {ora: ora, mat: mat, id_reg: id_reg, plus: plus, upd: upd, id: id_ore[ora]},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split(";");
			      		
			      		if(dati[0] == "ko"){
			      			alert("Impossibile completare l'operazione richiesta. Riprovare tra qualche secondo o segnalare l'errore al webmaster");
				     		return;
			     		}
			     		else{
			     			var campo = dati[1]+"ora";
			                var mat = dati[3];
			                var plus = dati[4];
			                var id_ora = dati[5];
			                var x = $(campo);
			                if(plus == 0){
			                	x.update(mat);
			                	del_link = document.createElement("a");
				               	del_link.setStyle({color: 'red', textDecoration: 'none'});
				               	del_link.setAttribute("href", "#");	
				               	//alert(plus);	               	
				               	if (plus == 0){
				               		del_link.setAttribute("id", dati[1]+"del");
				               		del_link.setAttribute("onclick", "document.forms[0].ora.value = "+dati[1]+"; document.forms[0].plus.value = 0; del("+dati[1]+")");
				               	}
				               	else {
				               		del_link.setAttribute("id", dati[1]+"delc");
				               		del_link.setAttribute("onclick", "document.forms[0].ora.value = "+dati[1]+"; document.forms[0].plus.value = 1; del_c("+dati[1]+")");
				               	}
				               	del_link.appendChild(document.createTextNode(" (x) "));
				               
				               	x.insert({after: del_link});
				               	id_ore[dati[1]] = dati[5];
				               	visualizza_textarea();
			                }
			                else{
			                    //mat = " / "+mat;
			                    if (upd > 0){
				                    y = $(dati[1]+"del");
			                    }
			                    else {
									y = $(dati[1]+"ora");
			                    }
			                    y.insert({after: "<span id='"+dati[1]+"sep'> / </span>"});
			                    comp_link = document.createElement("a");
			                    comp_link.setStyle({fontWeight: 'bold', color: 'black', textDecoration: 'none'});
			                    comp_link.setAttribute("href", "#");
			                    comp_link.setAttribute("id", dati[1]+"ora_comp");
			                    comp_link.setAttribute("onclick", "document.forms[0].ora.value = "+dati[1]+"; document.forms[0].plus.value = 1; visualizza(event)");
			                    comp_link.appendChild(document.createTextNode(mat));
			                    $(dati[1]+'sep').insert({after: comp_link});
			                    
			                    del_link = document.createElement("a");
				               	del_link.setStyle({color: 'red', textDecoration: 'none'});
				               	del_link.setAttribute("href", "#");	
				               	del_link.setAttribute("id", dati[1]+"delc");
			               		del_link.setAttribute("onclick", "document.forms[0].ora.value = "+dati[1]+"; document.forms[0].plus.value = 1; del_c("+dati[1]+")");
			               		del_link.appendChild(document.createTextNode(" (x) "));
			               		comp_link.insert({after: del_link});
			                }
			                id_ore[dati[1]] = dati[5];
			     		}
			    	},
			    	onFailure: function(){ _alert("Si e' verificato un errore...") }
			  });
}

function visualizza(e) {
	<?php
	if (count($materie) == 1){
		$m = $materie[0];
	?>
	document.forms[0].mat.value = <?php print $m['id_materia'] ?>; 
	firma();
	<?php
	}
	else {
	?> 
    var hid = $('hid');
    //alert(hid.style.top);
    if (IE) { // grab the x-y pos.s if browser is IE
        tempX = event.clientX + document.body.scrollLeft;
        tempY = event.clientY + document.body.scrollTop;
    } else {  // grab the x-y pos.s if browser is NS
        tempX = e.pageX;
        tempY = e.pageY;
    }  
    // catch possible negative values in NS4
    if (tempX < 0){tempX = 0;}
    if (tempY < 0){tempY = 0;}  
    hid.setStyle({top:  parseInt(tempY)+"px"});
    hid.setStyle({left: parseInt(tempX)+"px"});
    hid.show();
    <?php
	}
	?>
    return true;
}

function visualizza_r(e) {
    var hid = $('hid');
    //alert(hid.style.top);
    if (IE) { // grab the x-y pos.s if browser is IE
        tempX = event.clientX + document.body.scrollLeft;
        tempY = event.clientY + document.body.scrollTop;
    } else {  // grab the x-y pos.s if browser is NS
        tempX = e.pageX;
        tempY = e.pageY;
    }  
    // catch possible negative values in NS4
    if (tempX < 0){tempX = 0;}
    if (tempY < 0){tempY = 0;}  
    tempX -= 240;
    hid.setStyle({top:  parseInt(tempY)+"px"});
    hid.setStyle({left: parseInt(tempX)+"px"});
    hid.show();
    return true;
}

function visualizza_textarea() {
	//alert("IN");
	var teacher_id = '<?php print $_SESSION['__user__']->getUid() ?>';
	var argomenti = new Array();
	<?php 
	while(list($k, $v) = each($argomenti)){
		if(is_array($v)){
			$v = '';	
		}
	?>
	argomenti[<?php print $k ?>] = '<?php print utf8_decode(addslashes($v)) ?>'; 
	<?php } ?>
	var teachers = new Array();
	<?php 
	while(list($k, $v) = each($docenti)){
	?>
	teachers[<?php print $k ?>] = '<?php print $v ?>'; 
	<?php } ?>
	var ora = $F('ora');
	//alert(teachers[ora]);
	if(teachers[ora] != 0 && teachers[ora] != teacher_id && teachers[ora] != ""){
		return false;
	}
    var arg = prompt("Argomento della lezione", argomenti[ora]);
    if (arg != null && arg != ""){
    	
        var id_reg = $F('id_reg');
        var url = "arg.php";
        var req = new Ajax.Request(url,
  			  {
  			    	method:'post',
  			    	parameters: {ora: ora, id_reg: id_reg, arg: arg},
  			    	onSuccess: function(transport){
  			      		var response = transport.responseText || "no response text";
  			      		var dati = response.split(";");
  			      		if(dati[0] == "ko"){
  			      			alert("Impossibile completare l'operazione richiesta. Riprovare tra qualche secondo o segnalare l'errore al webmaster");
  				     		return;
  			     		}
  			     		else{
  			                var campo = dati[1]+"ora_arg";
  			                //alert(campo);
  			                var x = $(campo);
  			                x.update("argomento inserito");
  			                x.setAttribute("onclick", "visualizza_textarea("+ora+")");
  			     		}
  			    	},
  			    	onFailure: function(){ alert("Si e' verificato un errore...");}
  			  });
    }
}

function del(id_registro){
	// cancella una firma
	var idr = id_ore[id_registro];
	//alert(idr);
	var url = "firma.php";
	var ora = $F('ora');
    var id_reg = $F('id_reg');
    //alert(ora);
    //alert(id_reg);
    var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {ora: ora, id_reg: id_reg, del: '1', del_id: idr},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split("|");
			      		if(dati[0] == "ko"){
			      			return;
			     		}
			     		else{
			     			var campo = dati[1]+"ora";
			                var x = $(campo);
			                x.update("Firma");
			                $(dati[1]+"del").update("");
			                $(dati[1]+"ora_arg").update("");
			     		}
			    	},
			    	onFailure: function(){ _alert("Si e' verificato un errore..."); }
			  });
}

function del_c(id_registro){
	// cancella una firma
	var idr = id_ore[id_registro];
	//alert(idr);
	var url = "firma.php";
	var ora = $F('ora');
    var id_reg = $F('id_reg');
    //alert(ora);
    //alert(id_reg);
    var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {ora: ora, id_reg: id_reg, del: '2', del_id: idr},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split("|");
			      		if(dati[0] == "ko"){
			      			return;
			     		}
			     		else{
			     			var campo = dati[1]+"ora_comp";
			                var x = $(campo);
			                x.setStyle({display: 'none'});
			                $(dati[1]+'sep').setStyle({display: 'none'});
			                $(dati[1]+"delc").update("");
			     		}
			    	},
			    	onFailure: function(){ _alert("Si e' verificato un errore..."); }
			  });
}

</script>
</head>
<body>
<!--
DIV nascosto che contiene le materie
-->
<div id="hid" style="display: none">
<?php
$k = 0;
foreach($materie as $m){
?>
    <a style="font-weight: normal;" href="#" onclick="document.forms[0].mat.value = <?php print $m['id_materia'] ?>; firma(); "><?php echo truncateString($m['materia'], 20) ?></a><br />
<?php
    $k++;
}
?>
</div>
<form>
<div id="main">
<div style="text-align: center; font-weight: bold; padding-bottom: 15px"><?php print $_SESSION['__classe__']->to_string() ?><br />Registro di classe di <?php print ($giorno_str ." ". format_date($dati['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/")) ?></div>
<div style="width: 90%; text-align: left; margin: auto;">
<?php 
$i = $prima_ora;
foreach ($_materie as $_m){
	$mat_in = $_m;
	$subjects = array();
	$sel_mat = "SELECT materia FROM rb_materie WHERE id_materia IN ($mat_in) AND id_materia <> 0";
	try{
		$res_mat = $db->executeQuery($sel_mat);
		while($s = $res_mat->fetch_assoc()){
			array_push($subjects, $s['materia']);
		}
	} catch (MySQLException $ex){
		
	}
	if (count($subjects) > 0){
		$_sub = join(" / ", $subjects);
	}
	else{
		$_sub = "Firma";
	}
	$comp = "";
	if ($c_materie[$i] != ""){
		$sel_mt = "SELECT materia FROM rb_materie WHERE id_materia = {$c_materie[$i]}";
		try{
			$comp = $db->executeCount($sel_mt);
		} catch (MySQLException $ex){
		
		}
	}
		
?>
<div class="row" style="<?php if($i%2) print("background-color: rgba(211, 222, 199, 0.6);") ?>">
<span style="padding-left: 10px; float: left"><?php print $i ?> ora</span>&nbsp;&nbsp;&nbsp;&nbsp;
<a id="<?php print $i."ora" ?>" style="color: black; font-weight: bold" href="#" onclick="document.forms[0].ora.value = '<?php print $i ?>'; document.forms[0].plus.value = 0; visualizza(event)"><?php echo truncateString($_sub, 20) ?></a><?php if(count($subjects) > 0){ ?> <a id="<?php print $i."del" ?>" style="color: red; text-decoration: none" href="#" onclick="document.forms[0].ora.value = '<?php print $i ?>'; document.forms[0].plus.value = 0; del(<?php print $i ?>)">(x)</a><?php } ?>
<?php if ($comp != ""): ?>
<span id="<?php echo $i ?>sep"> / </span><a id="<?php print $i."ora_comp" ?>" style="color: black; font-weight: bold" href="#" onclick="document.forms[0].ora.value = '<?php print $i ?>'; document.forms[0].plus.value = 1; visualizza(event)"><?php echo truncateString($comp, 20) ?></a><a id="<?php print $i."delc" ?>" style="color: red; text-decoration: none" href="#" onclick="document.forms[0].ora.value = '<?php print $i ?>'; document.forms[0].plus.value = 1; del_c(<?php print $i ?>)"> (x)</a>
<?php endif; ?>
<a id="<?php print $i."ora" ?>_plus" style="float: right; color: black; font-weight: bold; padding-right: 10px" href="#" onclick="document.forms[0].ora.value = '<?php print $i ?>'; document.forms[0].plus.value = 1; visualizza_r(event)"> + </a>
<a id="<?php print $i."ora" ?>_arg" style="float: right; padding-right: 70px; color: black; font-weight: normal; font-style: italic " href="#" onclick="document.forms[0].ora.value = '<?php print $i ?>'; document.forms[0].plus.value = 0; <?php if(count($subjects) > 0) {?>visualizza_textarea(<?php print $i ?>)<?php } else {?>alert('Ora non firmata')<?php } ?>">
<?php 
	if(count($subjects) > 0) {
		if($argomenti[$i] != ""){
			print("argomento inserito");
		}
		else {
			print("inserisci argomento");
		}
	} 
	else {
		print " ";
	} 
?>
</a>
</div>
<?php 
	$i++;
}
?>
<p style="height: 0px">
<input type="hidden" name="ora" id="ora" value="" />
<input type="hidden" name="mat" id="mat" value="" />
<input type="hidden" name="plus" id="plus" value="0" />
<input type="hidden" name="id_reg" id="id_reg" value="<?php print $_REQUEST['id_reg'] ?>" />
</p>
</div>
</div>
</form>
</body>
</html>