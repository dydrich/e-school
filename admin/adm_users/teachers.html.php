<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Elenco docenti</title>
<link rel="stylesheet" href="../../css/reg.css" type="text/css" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
var IE = document.all?true:false;
if (!IE) document.captureEvents(Event.MOUSEMOVE);

var tempX = 0;
var tempY = 0;

function materia(event){
    //alert("ok");
    $('hid').setStyle({display: "none"});
    var uid = $F('uid');
    var mat = $F('mat');
    var url = "materia.php";
    req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {uid: uid, mat: mat},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		if(response == "ko"){
			      			alert("I dati inseriti sono errati");
			                return;
			     		}
			     		else{
			     			var dati = response.split(";");
			                var usr = dati[1];
			                $(usr).update(dati[2]);
			     		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore...") }
			  });
}

function ruolo(_uid){
    var uid = _uid;
    var url = "ruolo.php";
    req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {uid: uid},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		if(response == "ko"){
			      			alert("I dati inseriti sono errati");
			                return;
			     		}
			     		else{
			     			var dati = response.split(";");
			     			var val = dati[0];
			                var usr = dati[1];
			                //alert(dati[1]);
			                $(usr).update(val);
			     		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

function set_type(event){
    //alert("ok");
    $('d_tp').hide();
    var uid = $F('uid');
    var type = $F('type');
    var url = "set_school_type.php";
    req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {uid: uid, type: type},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split(";");
			      		if(dati[0] == "ko"){
			      			alert(dati[1]);
			                return;
			     		}
			      		else if(dati[0] == "kosql"){
							sqlalert();
							console.log(dati[1]+"\n"+dati[2]);
			      		}
			     		else{
			                $('tipo_'+uid).update(dati[1]);
			     		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore...") }
			  });
}

var load_subjects = function(user){
	var url = "load_subjects.php";
    req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {uid: user},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split("#");
			      		if(dati[0] == "ko"){
			      			alert(dati[1]);
			                return;
			     		}
			      		else if(dati[0] == "kosql"){
							sqlalert();
							console.log(dati[1]+"\n"+dati[2]);
			      		}
			     		else{
			     			var json = dati[1].evalJSON();
			     			var print_string = "";
				     		for(data in json){
					     		var t = json[data];
					     		print_string += "<a href='../../shared/no_js.php' class='sub_link' id='mat_"+t.id+"'>"+t.materia+"</a><br />";
				     		}
				     		$('hid').update(print_string);
				     		$('hid').setStyle({height: dati[2]+"px"});
				     		$$('a.sub_link').invoke("observe", "click", function(event){
				     			event.preventDefault();
				     			var strs = this.id.split("_");
				     			$('mat').setValue(strs[1]);
				     			materia(event);
				     		});
			     		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore...") }
			  });
};

function visualizza(e) {
    if (IE) { 
        tempX = event.clientX + document.body.scrollLeft;
        tempY = event.clientY + document.body.scrollTop;
    } else {  
        tempX = e.pageX;
        tempY = e.pageY;
    }  

    if (tempX < 0){tempX = 0;}
    if (tempY < 0){tempY = 0;}  
    $('hid').setStyle({top: parseInt(tempY)+"px"});
    $('hid').setStyle({left: parseInt(tempX)+"px"});
    $('hid').setStyle({display: "inline"});
    return true;
}

var show_types = function(e){
	if (IE) { 
        tempX = event.clientX + document.body.scrollLeft;
        tempY = event.clientY + document.body.scrollTop;
    } else {  
        tempX = e.pageX;
        tempY = e.pageY;
    }  
    
    if (tempX < 0){tempX = 0;}
    if (tempY < 0){tempY = 0;}
    tempX -= 100;
    $('d_tp').setStyle({top: parseInt(tempY)+"px"});
    $('d_tp').setStyle({left: parseInt(tempX)+"px"});
    $('d_tp').setStyle({display: "inline"});
    return true;
};

document.observe("dom:loaded", function(){
	$$('a.sub_link').invoke("observe", "click", function(event){
		event.preventDefault();
		var strs = this.id.split("_");
		$('mat').setValue(strs[1]);
		materia(event);
	});
	$$('a.ch_link').invoke("observe", "click", function(event){
		event.preventDefault();
		var strs = this.id.split("_");
		$('uid').setValue(strs[1]);
		load_subjects(strs[1]);
		visualizza(event);
	});
	$$('a.ruolo').invoke("observe", "click", function(event){
		event.preventDefault();
		var strs = this.id.split("_");
		ruolo(strs[1]);
	});
	$$('a.tipo').invoke("observe", "click", function(event){
		event.preventDefault();
		<?php if($_SESSION['__user__']->isAdministrator()){ ?>
		var strs = this.id.split("_");
		$('uid').setValue(strs[1]);
		show_types(event);
		<?php } ?>
	});
	$$('a.sc_link').invoke("observe", "click", function(event){
		event.preventDefault();
		var strs = this.id.split("_");
		$('type').setValue(strs[1]);
		set_type(event);
	});
	$('d_tp').observe("mouseleave", function(event){
		event.preventDefault();
		$('d_tp').hide();
	});
	$('hid').observe("mouseleave", function(event){
		event.preventDefault();
		$('hid').hide();
	});
});

</script>
</head>
<body>
    <!--
    DIV nascosto che contiene le materie: ogni riga e' un link che carica materie.php
    -->
    <div id="hid" style="position: absolute; width: 200px; height: <?php echo (20 * $res_m->num_rows) ?>px; display: none; ">
    <?php
    $k = 0;
    while($mt = $res_m->fetch_assoc()){
    ?>
        <a href="../../shared/no_js.php" class="sub_link" id="mat_<?php echo $mt['id_materia'] ?>"><?php print $mt['materia'] ?></a><br />
    <?php
        $k++;
    }
    ?>
    </div>
    <!--
    DIV nascosto che contiene le tipologie di scuola
    -->
    <div id="d_tp" style="position: absolute; width: 200px; height: 80px; display: none; ">
    <?php
    while($t = $res_tipologie->fetch_assoc()){
    ?>
        <a href="../../shared/no_js.php" class="sc_link" id="tp_<?php echo $t['id_tipo'] ?>"><?php print $t['tipo'] ?></a><br />
    <?php
    }
    ?>
    </div>
    <?php include "../header.php" ?>
    <?php include "../navigation.php" ?>
    <div id="main">
	    <div id="right_col">
		    <?php include "menu.php" ?>
	    </div>
	    <div id="left_col">
		   <div class="group_head">Elenco Docenti: pagina <?php print $page ?> di <?php print $pagine ?></div>
		<form method="post" style="width: 100%">
        <table class="admin_table">
            <tr>
                <td style="width: 30%" class="adm_titolo_elenco_first">Nome e cognome</td>
                <td style="width: 20%" class="adm_titolo_elenco">Materia</td>
                <td style="width: 10%" class="adm_titolo_elenco _center">Ruolo</td>
                <td style="width: 40%" class="adm_titolo_elenco_last _center">Tipologia scuola</td>
            </tr>
            <tr class="admin_row_before_text">
                <td colspan="4"></td>
            </tr>
            <?php
            $x = 1;
            if($res_user->num_rows > $limit)
                $max = $limit;
            else
                $max = $res_user->num_rows;

            while($user = $res_user->fetch_assoc()){
                $ruolo = "SI";
                if($user['ruolo'] != "S")
                    $ruolo = "NO";
                if($x > $limit) break;
            ?>
            <tr class="admin_row" style="height: 20px">
                <td><?php print $user['cognome']." ".$user['nome'] ?></td>
                <td><a href="../../shared/no_js.php" class="ch_link" id="doc_<?php print $user['id_docente'] ?>"><?php print $user['materia'] ?></a></td>
                <td class="_center"><a href="../../shared/no_js.php" id="rl_<?php print $user['id_docente'] ?>" class="ruolo"><?php print $ruolo ?></a></td>
                <td class="_center"><a href="../../shared/no_js.php" id="tipo_<?php print $user['id_docente'] ?>" class="tipo"><?php echo $user['tipologia'] ?></a>
            </tr>
            <?php
                $x++;
            }
            include "../../shared/navigate.php";
            ?>
            <tr class="admin_menu">
                <td colspan="4" style="text-align: right">
                    <a href="<?php echo $goback_link ?>"><?php echo $goback ?></a>
                </td>
            </tr>
            <tr class="admin_void">
                <td colspan="4">&nbsp;&nbsp;&nbsp;
                	<input type="hidden" name="mat" id="mat" />
        			<input type="hidden" name="uid" id="uid" />
        			<input type="hidden" name="type" id="type" />
                </td>
            </tr>
        </table>
        </form>
	    </div>
	    <p class="spacer"></p>
    </div>
    <?php include "../footer.php" ?>
</body>
</html>