<?php

require_once "../../lib/start.php";

$alunno = $_REQUEST['id'];
$anno = $_SESSION['__current_year__']->get_ID();

$db->executeUpdate("DELETE FROM rb_assegnazione_sostegno WHERE alunno = {$alunno} AND anno = {$anno}");
$db->executeUpdate("UPDATE rb_alunni SET legge104 = NULL WHERE id_alunno = {$alunno}");

header("Content-type: text/plain");
echo "ok";
exit;