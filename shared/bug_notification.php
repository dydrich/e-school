<?php

include "../lib/start.php";

header("Content-type: text/plain");

$to = $_SESSION['__config__']['admin_email'];
$subject = "Segnalazione di errore";
$text = "";

$referer = "";
while(list($k, $v) = each($_SESSION['__mysql_error__'])){
	if($k == "referer")
		$referer = $v;
	$text .= "{$k}::{$v}\n";
}
$text .= "Browser::{$_SERVER['HTTP_USER_AGENT']}\n";
$text .= "Installazione::{$_SESSION['__config__']['intestazione_scuola']}, {$_SESSION['__config__']['indirizzo_scuola']}\n\n";
$headers = "From: " .$_SESSION['__config__']['admin_email']. "\r\n" .	"Reply-To: ".$_SESSION['__config__']['admin_email']. "\r\n" .'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $text, $headers);

echo "ok";
exit;