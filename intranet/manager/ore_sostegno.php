<?php

require_once "../../lib/start.php";

$id = $_REQUEST['id'];
$id_st = substr($id, 1);
$value = $_REQUEST['value'];

$upd = "UPDATE rb_alunni SET legge104 = {$value} WHERE id_alunno = {$id_st}";
$update_var = $db->executeUpdate($upd);

header("Content-type: text/plain");
echo $value;
exit;
