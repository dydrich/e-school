<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="author" content="" />
	<link href="../../css/reg.css" rel="stylesheet" />
	<link href="../../css/general.css" rel="stylesheet" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript">
var sezioni = new Array();
<?php 
while($sec = $res_sezioni->fetch_assoc()){
?>
	sezioni.push('<?= $sec['sezione'] ?>');
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
	$('classi').update("");
	for(var i = 0; i < sezioni.length; i++){
		var _a = document.createElement("a");
		_a.setAttribute("href", "#");
		_a.setStyle({marginRight: '10px'});
		_a.setAttribute("onclick", "del('"+sezioni[i]+"')");
		_a.appendChild(document.createTextNode("1"+sezioni[i]));
		$('classi').appendChild(_a);
	}
};

var save = function(){
	var url = "crea_classi_prime.php";
	var cls = sezioni.join();
    req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {cls: cls},
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
				    	//alert(response);
			    		dati = response.split("#");
			    		if(dati[0] == "ok"){
							step = 5;
							alert("Operazione completata");
			            }
			            else{
			                alert("Operazione non riuscita. Query: "+dati[1]+"\nErrore: "+dati[2]);
			                return;
			            }
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};

document.observe("dom:loaded", function(){
	$$('div#sezioni a').invoke("observe", "mouseover", function(event){
		//alert(this.id);
		this.setStyle({color: '#8a1818', fontWeight: 'bold'});
	});
	$$('div#sezioni a').invoke("observe", "mouseout", function(event){
		//alert(this.id);
		this.setStyle({color: '', fontWeight: 'normal'});
	});
	$$('table a').invoke("observe", "click", function(event){
		
		t_step = this.readAttribute("step");
		if(((step > t_step)) || ((t_step == step) && t_step != 1)){
			event.preventDefault();
			wrong_step(t_step);
		}
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
        <div id="title" class="admin_title_row">Attivazione classi prime</div>
        <div style="width: 80%; margin: 0 auto 20px auto">Saranno attivate le classi seguenti. 
        Puoi aggiungerne altre, cliccando sulla sezione, o eliminarne alcune, cliccando sul nome della classe: quando hai terminato le modifiche, clicca sul link "Registra" in fondo alla pagina.</div>
        <div id="sezioni" style="width: 90%; margin: auto; text-align: center; border-width: 1px 0 1px 0; border-style: solid; border-color: #CCCCCC">
        <?php 
        foreach($alpha as $a){
        ?>
        <a href="#" onclick="add('<?= $a ?>')" style="margin-right: 10px"><?= $a ?></a>
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
        <a href="#" onclick="del('<?= $sez['sezione'] ?>')" style="margin-right: 10px">1<?= $sez['sezione'] ?></a>
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
    <?php include "../footer.php" ?>
	</div>
</body>
</html>