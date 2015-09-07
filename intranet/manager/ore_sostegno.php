<?php

require_once "../../lib/start.php";

$id = $_REQUEST['f'];

$value = $_REQUEST['val'];

$upd = "UPDATE rb_alunni SET legge104 = {$value} WHERE id_alunno = {$id}";
$update_var = $db->executeUpdate($upd);

header("Content-type: text/plain");
echo $value;
exit;
