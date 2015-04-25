<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 22/04/15
 * Time: 18.58
 */

require_once "../../lib/start.php";

$param = $_REQUEST['uid'];

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$query = "SELECT distinct(rb_reg_classi.id_anno) AS anno, descrizione FROM rb_reg_alunni, rb_reg_classi, rb_anni WHERE id_reg = id_registro AND rb_reg_classi.id_anno = rb_anni.id_anno AND id_alunno = $param ORDER BY rb_anni.id_anno DESC";
$res = $db->executeQuery($query);
$reports = array();
while ($row = $res->fetch_assoc()) {
	$reports[$row['anno']] = array("id" => $row['anno'], "desc" => $row['descrizione']);
}

$response['data'] = $reports;

$res = json_encode($response);
echo $res;
exit;
