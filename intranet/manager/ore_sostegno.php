<?php

require_once "../../lib/start.php";

$alunno = $_REQUEST['f'];
$value = $_REQUEST['val'];

$upd = "UPDATE rb_alunni SET legge104 = {$value} WHERE id_alunno = {$alunno}";
$update_var = $db->executeUpdate($upd);

header("Content-type: text/plain");
echo $value;
exit;