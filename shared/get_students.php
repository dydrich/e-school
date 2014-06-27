<?php

require_once "../lib/start.php";

ini_set("display_errors", "1");

header("Content-type: text/plain");

$sel_students = "SELECT * FROM rb_alunni WHERE attivo = '1' AND id_classe = ".$_REQUEST['classe']." ORDER BY cognome, nome";
try {
	$result = $db->executeQuery($sel_students);
} catch (MySQLException $ex) {
	echo "ko|".$ex->getQuery()."|".$ex->getMessage();
	exit;
}

$return = "ok|";
$str = "";
while ($st = $result->fetch_assoc()) {
	if ($_REQUEST['req'] == 'name') {
		$str .= $st['cognome']." ".$st['nome']."#";
	}
}

$return .= $str;
echo $return; 
exit;

?>
