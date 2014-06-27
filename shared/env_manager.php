<?php

require_once "../lib/start.php";

$field = $_REQUEST['f'];
$value = $_REQUEST['val'];

$upd = "UPDATE rb_config SET valore = '$value' WHERE variabile = '$field'";
$update_var = $db->executeUpdate($upd);
$_SESSION['__config__'][$field] = $value;

require_once "../lib/load_env.php";

header("Content-type: text/plain");
print $value;
exit;

?>