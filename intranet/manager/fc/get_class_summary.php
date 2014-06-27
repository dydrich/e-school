<?php

include "../../../lib/start.php";

ini_set("display_errors", "1");

check_session();
check_permission(DIR_PERM|DSG_PERM);

$perms = ($_SESSION['__user__']->getPerms()) ? $_SESSION['__user__']->getPerms() : $_SESSION['__perms__'];
//$nome = ($_SESSION['__user__']) ? $_SESSION['__user__']->getFullName() : $_SESSION['__fname__']." ".$_SESSION['__lname__'];

if(DIR_PERM&$perms)
	$_SESSION['__role__'] = "Dirigente scolastico";
else
	$_SESSION['__role__'] = "DSGA";

header("Content-type: text/plain");

$data = array();

$sel_sex = "SELECT sesso, COUNT(sesso) AS count FROM fc_alunni WHERE id_classe = ".$_REQUEST['cl']." GROUP BY sesso";
$res_sex = $db->executeQuery($sel_sex);
$male = $female = 0;
while($sx = $res_sex->fetch_assoc()){
	if($sx['sesso'] == 'M')
		$male = $sx['count'];
	else
		$female = $sx['count'];
}
$data['male'] = $male;
$data['female'] = $female;

$sel_rip = "SELECT COUNT(id_alunno) FROM fc_alunni WHERE id_classe = ".$_REQUEST['cl']." AND ripetente = 1";
$ripetenti = $db->executeCount($sel_rip);
$data['rip'] = $ripetenti;

$sel_h = "SELECT H FROM fc_alunni WHERE id_classe = ".$_REQUEST['cl']." AND H IS NOT NULL AND H <> 0";
$res_h = $db->executeQuery($sel_h);
$h = $dsa = 0;
while($al = $res_h->fetch_assoc()){
	if($al['H'] < 4)
		$dsa++;
	if($al['H'] > 1)
		$h++;
}
$data['H'] = $h;
$data['dsa'] = $dsa;
$data['sos'] = $res_h->num_rows;

$sel_avg = "SELECT ROUND(AVG(voto), 2) FROM fc_alunni WHERE id_classe = ".$_REQUEST['cl'];
$data['avg'] = $db->executeCount($sel_avg);

$out = join(";", $data);

print "ok;$out";
exit;

?>