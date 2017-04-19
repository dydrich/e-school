<?php

include "../lib/start.php";

$_SESSION['__path_to_root__'] = "../";

$drawer_label = "Errore SQL";

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Registro elettronico</title>
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
    <link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
	var send_email = function(){
		var url = "bug_notification.php";
		$.ajax({
			type: "POST",
			url: url,
			data: {cls: cls},
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
					j_alert ("alert", "Segnalazione inviata");
				}
			}
		});
	};
	</script>
</head>
<body>
<?php include "../intranet/".$_SESSION['__area__']."/header.php" ?>
<?php include "../intranet/".$_SESSION['__area__']."/navigation.php" ?>
<div id="main">
		<?php if($_SESSION['__config__']['debug']){ ?>
			<table style="width: 75%; margin: 25px auto">
			<thead>
	            <tr>
	                <td colspan="2" class="_bold" style="padding-bottom: 15px; font-size: 1.2em">
                        <i class="fa fa-warning attention"></i>
                        Si è verificato un errore nell'accesso al database MySQL
                    </td>
	            </tr>
				<tr>
					<td colspan="2"> </td>
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
					<td style="width: 20%; padding-left: 10px" class="accent_decoration material_label"><?php print $k ?></td>
					<td style="width: 80%; padding-left: 10px" class="accent_decoration _bold normal"><?php print $v ?></td>
				</tr>
				<?php } ?>
				<tr>
					<td colspan="2">
                        <p class="spacer"></p>
                    </td>
				</tr>
				<tr>
	                <td colspan="2" align="right">
	                    <a href="<?php echo $referer ?>" style="margin-left: 10px">Torna indietro</a>
	                </td>
            </tr>
            <tr>
                <td colspan="2">
                    &nbsp;
                </td>
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
			<table style="margin: auto; width: 75%">
			<thead>
	            <tr>
	                <td colspan="2">Si è verificato un errore non previsto</td>
	            </tr>
			</thead>
				<tr>
					<td colspan="2" style="text-align: center; line-height: 20px"><br />Si è verificato un errore imprevisto, che ha impedito di caricare la pagina richiesta.<br />
				</tr>
				<tr>
					<td colspan="2"></td>
				</tr>
				<tr>
	                <td colspan="2" align="right">
	                    <a href="<?php echo $referer ?>" style="margin-left: 10px">Torna indietro</a>
	                </td>
            </tr>
            <tr>
                <td colspan="3"></td>
            </tr>
			</table>
			<?php } ?>
		</div>
		<?php include "../admin/footer.php" ?>
</body>
</html>
