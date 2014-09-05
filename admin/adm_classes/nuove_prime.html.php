<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="author" content="" />
<link href="../../css/site_themes/blue_red/reg.css" rel="stylesheet" />
<link href="../../css/general.css" rel="stylesheet" />
<link rel="stylesheet" href="../../modules/documents/theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
var sezioni = new Array();
<?php 
while($sec = $res_sezioni->fetch_assoc()){
?>
	sezioni.push('<?php echo $sec['sezione'] ?>');
<?php 
}
?>

var add = function(cl){
	if(!in_array(sezioni, cl)){
		sezioni.push(cl);
		sezioni.sort();
	}
	reload_cls();
};

var del = function(cl){
	sezioni.remove_by_value(cl);
	reload_cls();
};

var reload_cls = function(){
	$('#classi').text("");
	for(var i = 0; i < sezioni.length; i++){
		var _a = document.createElement("a");
		_a.setAttribute("href", "#");
		_a.setAttribute("style", "margin-right: 10px");
		_a.setAttribute("onclick", "del('"+sezioni[i]+"')");
		_a.appendChild(document.createTextNode("1"+sezioni[i]));
		$('#classi').append(_a);
	}
};

var save = function(){
	var url = "crea_classi_prime.php";
	var cls = sezioni.join();
	$.ajax({
		type: "POST",
		url: url,
		data: {school_order: <?php echo $school_order ?>, cls: cls},
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
			else if (json.status == "wrong_step"){
				alert(json.message);
			}
			else {
				alert("Operazione completata");
			}
		}
	});
};

$(function(){
	$('div#sezioni a').mouseover(function(event){
		//alert(this.id);
		$('#'+this.id).css({color: '#8a1818', fontWeight: 'bold'});
	});
	$('div#sezioni a').mouseout(function(event){
		//alert(this.id);
		$('#'+this.id).css({color: '', fontWeight: 'normal'});
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
		<div class="group_head">Attivazione classi prime</div>
        <div style="width: 80%; margin: 10px auto 20px auto">Saranno attivate le classi seguenti.
        Puoi aggiungerne altre, cliccando sulla sezione, o eliminarne alcune, cliccando sul nome della classe: quando hai terminato le modifiche, clicca sul link "Registra" in fondo alla pagina.</div>
        <div id="sezioni" style="width: 90%; margin: auto; text-align: center; border-width: 1px 0 1px 0; border-style: solid; border-color: #CCCCCC">
        <?php 
        foreach($alpha as $a){
        ?>
        <a href="#" onclick="add('<?php echo $a ?>')" style="margin-right: 10px" id="sez_<?php echo strtolower($a) ?>"><?php echo $a ?></a>
        <?php 
        }
        ?>
        </div>
        <div style="width: 90%; margin: 20px auto 10px auto; text-align: center" class="title_row">Classi</div>
        <div id="classi" style="width: 90%; margin: auto; text-align: center; border-bottom: 1px solid #CCCCCC; font-weight: bold" class="">
        <?php 
        $res_sezioni->data_seek(0);
        while($sez = $res_sezioni->fetch_assoc()){
        ?>
        <a href="#" onclick="del('<?php echo $sez['sezione'] ?>')" style="margin-right: 10px">1<?php echo $sez['sezione'] ?></a>
        <?php 
        }
        ?>
        </div>
        <div style="width: 90%; margin: 40px auto 0 auto; text-align: right">
        	<a href="new_year_classes.php" class="nav_link_first">Torna indietro</a>|
        	<a href="../index.php" class="nav_link">Torna menu</a>|
        	<a href="#" onclick="save()" class="nav_link_last">Registra</a>
        </div>
    </div>
	<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
</body>
</html>
