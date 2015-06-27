<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Elenco alunni</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link href="../../css/general.css" rel="stylesheet" />
	<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javaScript">
		var add_student = function(nome, id){
			var doc = window.parent.document;
			var l = "<a href='#' onclick='del("+id+")' id='al"+id+"'>"+nome+"</a>";
			if(doc.getElementById("figli").innerHTML != "")
				l = ", "+l;
			doc.getElementById("figli").innerHTML += l;
			window.parent.id_alunni[id] = nome;
			//alert("##"+window.opener.document.forms[0].id_figli.value+"###");
			st_id = id;
			if(doc.forms[0].id_figli.value != "")
				id = ","+id;
			doc.forms[0].id_figli.value += id;
			//alert("hiding st"+st_id);
			$('#st'+st_id).hide();
		};

		var go = function(){
			document.forms[0].action.value = "elenco_alunni.php";
			document.forms[0].submit();
		};

		var get_all_classes = function(){
				//alert("ok");
			var url = "get_all_classes.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {v: 1},
				dataType: 'json',
				error: function() {
					alert("Errore di trasmissione dei dati");
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
					else {
						$('#filtro_classe').html("");
						$('#filtro_classe').append("<option value='0'>Seleziona</option>");
						data = json.data;
						$.each(data, function(){
							$('#filtro_classe').append("<option value='"+this.id+"'>"+this.classe+" "+this.sede+"</option>");
						});
					}
				}
			});
		};

	</script>
</head>
<body class="popup_body">
	<form class="popup_form" style="width: 95%">
    <div style="margin-right: auto; margin-left: auto; margin-top: 20px; width: 95%">
    	<table style="margin-right: auto; margin-left: auto; width: 95%">
    	<tr>
    		<td style="font-weight: bold; width: 25%">Classe</td>
    		<td style="width: 37%">
    			<select style="border: 1px solid; color: #777; font-size: 11px; width: 150px" onchange="go()" name="filtro_classe" id="filtro_classe">
    				<option value="0">Seleziona</option>
    				<?php
					while($cls = $res_classi->fetch_assoc()){
						$a = $cls['anno_corso'].$cls['sezione']." (".$cls['codice']." - ".$cls['nome'].")";
    				?>
    				<option <?php if(isset($_REQUEST['filtro_classe']) && $cls['id_classe'] == $_REQUEST['filtro_classe']) print("selected='selected'") ?> value="<?php echo $cls['id_classe'] ?>"><?php echo $a ?></option>
    				<?php
					}
    				?>
    			</select>
    		</td>
    		<td style="text-align: right; width: 37%">
				<a href="#" onclick="get_all_classes()" style="color: #003366">Tutte le classi</a>
    		</td>
    	</tr>
    	<tr>
    		<td colspan="3" style="height: 20px"></td>
    	</tr>
    	<?php
    	$index = 1;
    	if(isset($res_alunni)){
	    	while($alunno = $res_alunni->fetch_assoc()){
			    $cognome = $db->real_escape_string($alunno['cognome']);
			    $nome = $db->real_escape_string($alunno['nome']);
	    		if($index == 1){

    	?>
    		<tr>
	            
    	<?php		
    			}
    	?>
	            <td style="padding-left: 5px; color: #003366; width: 37%"><a href="#" onclick="add_student('<?php print $cognome." ".$nome ?>', <?php print $alunno['id_alunno'] ?>)" id="st<?php echo $alunno['id_alunno'] ?>" style="color: #003366; text-decoration: none"><?php print $alunno['cognome']." ".$alunno['nome'] ?></a></td>
    	<?php
    			if(($index%2) == 0){
    	?>
	    	</tr>
	    	<tr>
	    <?php		
    			}  	
    			$index++;
    		}
    	}
    	?>
	        
	        </tr>
	        <tr>
	            <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
	        </tr>
	    </table>
    </div>
    <div style="margin-top: 30px">
    <input type="hidden" name="funzione" />
    <input type="hidden" name="_i" />
    <input type="hidden" name="teachers" />
    </div>
    </form>
</body>
</html>
