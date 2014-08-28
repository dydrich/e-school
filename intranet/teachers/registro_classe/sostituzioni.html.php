<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
<link rel="stylesheet" href="../../../css/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link href="../../../css/skins/aqua/theme.css" type="text/css" rel="stylesheet"  />
<script type="text/javascript" src="../../../js/prototype.js"></script>
<script type="text/javascript" src="../../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript" src="../../../js/calendar.js"></script>
<script type="text/javascript" src="../../../js/lang/calendar-it.js"></script>
<script type="text/javascript" src="../../../js/calendar-setup.js"></script>
<script type="text/javascript">
var toggle_div = function(div){
	if($(div).readAttribute("id") == 'new_sig'){
		other_div = 'old_sig';
	}
	else{
		other_div = 'new_sig';
	}
	
	if($(div).style.display == "none"){
		$(other_div).fade({duration: .5});
		$(div).appear({duration: 1.0});
	}
	else {
		$(div).fade({duration: 1.0});
	}
};

var firma = function(id_registro, ora, id_ora, action){
	var url = "firma.php";
	
    req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {id_reg: id_registro, ora: ora, id_ora: id_ora, action: action, mat: 33},
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
			    		var json = response.evalJSON();
			    		if(json.status == "kosql"){
			    			sqlalert();
			    			console.log("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
			    			return;
			    		}
			    		else{
				    		if (action == 'sign'){
				    			$("p_"+ora).update("");
				    			var span = document.createElement('span');
								span.setStyle({marginRight: '20px'});
								span.appendChild(document.createTextNode(ora+ " ora"));
								$("p_"+ora).appendChild(span);
								_body = document.createElement("span");	
								_body.appendChild(document.createTextNode("Sostituzione"));
								$("p_"+ora).appendChild(_body);
				    		}
				    		else {
								$('tr_'+id_ora).hide();
				    		}
			    		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};

var load_signatures = function(){
	if($F('classe') == 0 || $F('data') == ""){
		alert("Scegli una classe ed una data per firmare");
		return false;
	}
	var url = "get_signatures.php";
	
    req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {data: $F('data'), classe: $F('classe')},
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
			    		dati = response.split("#");
			    		if(dati[0] == "kosql"){
			    			sqlalert();
			    			console.log("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
			    			return;
			    		}
			    		else{
			    			$('signatures').update("");
			    			var json = dati[1].evalJSON();
			    			for(data in json){
								var t = json[data];
								var _p = document.createElement('p');
								_p.setStyle({
									borderBottom: '1px solid #CCCCCC',
									lineHeight: '10px'
								});
								_p.setAttribute("id", "p_"+t.ora);
								var span = document.createElement('span');
								span.setStyle({marginRight: '20px'});
								span.appendChild(document.createTextNode(t.ora+ " ora"));
								_p.appendChild(span);
								if(t.dmat != 0){
									_body = document.createElement("span");	
									_body.appendChild(document.createTextNode(t.dmat));
								}
								else{
									var update = 0;
									if(t.id != 0){
										update = 1;
									}
									_body = document.createElement("a");
									_body.appendChild(document.createTextNode("Firma"));
									_body.setStyle({textDecoration: 'none'});
									_body.setAttribute("onclick", "firma("+t.id_registro+", "+t.ora+", "+t.id+", 'sign')");
									_body.setAttribute("href", "#");
								}
								_p.appendChild(_body);
								$('signatures').appendChild(_p);
								$('signatures').setStyle({
									backgroundColor: 'rgba(211, 222, 199, 0.2)',
									padding: '10px',
									border: '1px solid rgba(211, 222, 199, 1)',
									borderRadius: '6px'
								});
			    			}
			    		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};

var set_data = function(){
	if($F('data') != ""){
		$('tr_classe').appear({duration: .5});
	}
	else{
		$('tr_classe').hide();
	}
};

document.observe("dom:loaded", function(){
	$('show_new_sig').observe("click", function(event){
		toggle_div('new_sig');
	});
	$('show_sig').observe("click", function(event){
		toggle_div('old_sig');
	});
	$('classe').observe("change", function(event){
		load_signatures();
	});
	$$('tr.show_del').invoke("observe", "mouseover", function(event){
		var strs = this.id.split("_");
		$('unsign_'+strs[1]).setStyle({display: 'block'});
	});
	$$('tr.show_del').invoke("observe", "mouseout", function(event){
		var strs = this.id.split("_");
		$('unsign_'+strs[1]).setStyle({display: 'none'});
	});
	$$('a.del_sign').invoke("observe", "click", function(event){
		var strs = this.id.split("_");
		var idreg = this.dataset.idreg;
		var ora = this.dataset.ora;
		firma(idreg, ora, strs[1], 'unsign');
	});
});
</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "../working.php" ?>
</div>
<div id="left_col">
	<div class="group_head">Sostituzioni</div>
	<div style="width: 70%; margin: 30px auto">
		<a href="#" id="show_new_sig">Firma per una sostituzione</a>
		<div style="width: 95%; border: 1px solid #DDDDDD; margin: 5px 0 0 0; display: none; padding: 10px" id="new_sig">
			<p style="margin: 0 auto 10px auto; width: 70%; text-align: center">Seleziona una classe ed una data per firmare</p>
			<form method="post" id="myform" name="myform">
			<table style="width: 70%; margin: auto">
				<tr>
					<td style="width: 30%">Data</td>
					<td style="width: 70%">
						<input type="text" id="data" name="data" style="width: 95%" onchange="set_data()" />
						<script type="text/javascript">
			            Calendar.setup({
			                date		: new Date(),
							inputField	: "data",
							ifFormat	: "%d/%m/%Y",
							showsTime	: false,
							firstDay	: 1,
							timeFormat	: "24"					
						});
			        	</script>
					</td>
				</tr>
				<tr id="tr_classe" style="display: none">
					<td style="width: 30%">Classe</td>
					<td style="width: 70%">
						<select id="classe" name="classe" style="width: 95%">
							<option value="0">.</option>
							<?php 
							while($row = $res_classes->fetch_assoc()){
							?>
							<option value="<?php echo $row['id_classe'] ?>"><?php echo $row['anno_corso'],$row['sezione'] ?></option>
							<?php 
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div style="width: 90%" id="signatures">
						
						</div>
					</td>
				</tr>
			</table>
			</form>
		</div>
		<br /><br />
		<a href="#" id="show_sig">Visualizza le sostituzioni effettuate</a>
		<div style="width: 95%; border: 1px solid #DDDDDD; margin: 5px 0 0 0; display: none; padding: 10px; background-color: rgba(211, 222, 199, 0.2)" id="old_sig">
		<?php 
		if($res_subs->num_rows < 1){
		?>
		Nessuna sostituzione effettuata finora
		<?php 
		}
		else{
		?>
			<table style="width: 85%">
			<tr style="width: 30px; border-bottom: 1px solid rgba(211, 222, 199, 1)">
				<td style="width: 40%; padding: 10px 0">Data</td>
				<td style="width: 15%; text-align: center">Classe</td>
				<td style="width: 15%; text-align: center">Ora</td>
				<td style="width: 30%; text-align: center"></td>
			</tr>
		<?php
			while($sub = $res_subs->fetch_assoc()){	
		?>
			<tr id="tr_<?php echo $sub['id'] ?>" class="show_del" style="border-bottom: 1px solid rgba(211, 222, 199, 1)">
				<td style="width: 40%"><?php echo format_date($sub['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></td>
				<td style="width: 15%; text-align: center"><?php echo $sub['anno_corso'],$sub['sezione'] ?></td>
				<td style="width: 15%; text-align: center"><?php echo $sub['ora'] ?></td>
				<td style="width: 30%; text-align: center"><a href="#" id="unsign_<?php echo $sub['id'] ?>" data-idreg="<?php echo $sub['id_registro'] ?>" data-ora="<?php echo $sub['ora'] ?>" class="del_sign" style="display: none; text-decoration: none">Elimina</a></td>
			</tr>
		<?php
			}
		?>
		</table>
		<?php
		}
		?>
		</div>
	</div>
</div>
<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
</body>
</html>
