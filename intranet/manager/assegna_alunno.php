<?php

require_once "../../lib/start.php";

$alunno = $_REQUEST['alunno'];
$id = $_REQUEST['id'];

$upd = "UPDATE rb_assegnazione_sostegno SET alunno = {$alunno} WHERE id = {$id}";
$update_var = $db->executeUpdate($upd);

header("Content-type: text/plain");
echo "ok";
exit;