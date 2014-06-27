<?php

include "../lib/start.php";

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link rel="stylesheet" href="../css/main.css" type="text/css" />
<title>Registro elettronico</title>
<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/scriptaculous.js"></script>
<script type="text/javascript">
var send_email = function(){
	var url = "bug_notification.php";
	alert("Bug notification");
	req = new Ajax.Request(url,
		  {
		    	method:'post',
		    	onSuccess: function(transport){
			    	var response = transport.responseText || "no response text";
			    	dati = response.split("#");
		    		if(dati[0] == "ko"){
			    		alert("KO");
		    			return;
		    		}
		    		else{
		    			alert('Segnalazione inviata', 2);
		    		}
		    	},
		    	onFailure: function(){ alert("Si e' verificato un errore..."); }
		  });
};
/*
document.observe("dom:loaded", function(){
	$('send_bug').observe("click", function(event){
		event.preventDefault();
		send_email();
	});	
});
*/
</script>
</head>
<body>
	<div id="header">
		<div class="wrap">
			<?php include '../admin/header.php'; ?>
		</div>
	</div>
	<div class="wrap">
		<div id="main" style="width: 100%">
		<?php if($_SESSION['__config__']['debug']){ ?>
			<table class="admin_table">
			<thead>
	            <tr class="admin_title_row" >
	                <td colspan="2">Si &egrave; verificato un errore nell'accesso al database MySQL</td>
	            </tr>
				<tr>
					<td style="width: 20%; border: 1px solid gray; padding-left: 10px; font-weight: bold">Var</td>
					<td style="width: 80%; border: 1px solid gray; padding-left: 10px; font-weight: bold">Valore</td>
				</tr>
			</thead>
					<?php
						reset($_SESSION['__mysql_error__']);
						$referer = "";
						while(list($k, $v) = each($_SESSION['__mysql_error__'])){
							if($k == "referer")
								$referer = $v;
					?>
				<tr>
					<td style="width: 20%; border: 1px solid gray; padding-left: 10px"><?php print $k ?></td>
					<td style="width: 80%; border: 1px solid gray; padding-left: 10px"><?php print $v ?></td>
				</tr>
				<?php } ?>
				<tr class="admin_void">
					<td colspan="2"></td>
				</tr>
				<tr class="admin_void">
					<td colspan="2"></td>
				</tr>
				<tr class="admin_menu">
	                <td colspan="2" align="right">
	                    <a href="<?php echo $referer ?>" style="margin-left: 10px">Torna indietro</a>
	                </td>
            </tr>
            <tr>
                <td colspan="3" class="admin_void"></td>
            </tr>
			</table>
			<?php }
			else{
				$referer = $_SESSION['__mysql_error__']['referer'];
				$to = $_SESSION['__config__']['admin_email'];
				$subject = "Segnalazione di errore";
				$text = "";
				
				$referer = "";
				reset($_SESSION['__mysql_error__']);
				while(list($k, $v) = each($_SESSION['__mysql_error__'])){
					if($k == "referer")
						$referer = $v;
					$text .= "{$k}::{$v}\n";
				}
				$text .= "utente::".$_SESSION['__user__']->getUsername()."==>".$_SESSION['__user__']->getUid()."\n";
				$text .= "Browser::{$_SERVER['HTTP_USER_AGENT']}\n";
				$text .= "Installazione::{$_SESSION['__config__']['intestazione_scuola']}, {$_SESSION['__config__']['indirizzo_scuola']}\n\n";
				$headers = "From: " .$_SESSION['__config__']['admin_email']. "\r\n" .	"Reply-To: ".$_SESSION['__config__']['admin_email']. "\r\n" .'X-Mailer: PHP/' . phpversion();
				
				mail($to, $subject, $text, $headers);
			?>
			<table class="admin_table" style="margin: auto; width: 75%">
			<thead>
	            <tr class="admin_title_row" >
	                <td colspan="2">Si &egrave; verificato un errore non previsto</td>
	            </tr>
			</thead>
				<tr class="admin_void">
					<td colspan="2" style="text-align: center; line-height: 20px"><br />Si &egrave; verificato un errore imprevisto, che ha impedito di caricare la pagina richiesta.<br />
					Il problema &egrave; stato segnalato agli sviluppatori e sar&agrave; risolto al pi&ugrave; presto.<br />
					<!-- <a href="no_js.php" id="send_bug" style="font-weight: bold; font-size: 1em">Segnala il problema</a> -->
					</td>
				</tr>
				<tr class="admin_void">
					<td colspan="2"></td>
				</tr>
				<tr class="admin_menu">
	                <td colspan="2" align="right">
	                    <a href="<?php echo $referer ?>" style="margin-left: 10px">Torna indietro</a>
	                </td>
            </tr>
            <tr>
                <td colspan="3" class="admin_void"></td>
            </tr>
			</table>
			<?php } ?>
		</div>
		<?php include "../admin/footer.php" ?>
	</div>	
</body>
</html>