<?php

include "../../lib/start.php";

check_session();
//check_permission(GEN_PERM, $_SESSION['__perms__']);

$new_style = $_REQUEST['stile'];
$uid = $_SESSION['__user__']->getUid();

$upd_style = "UPDATE alunni SET stile = $new_style WHERE id_alunno = $uid";
$db->execute($upd_style);

$_SESSION['__stile__'] = $new_style;
switch($new_style){
	case 0:
		$_SESSION['__css__'] = "blue.css";
		break;
	case 1:
		$_SESSION['__css__'] = "lime.css";
		break;
	case 2:
		$_SESSION['__css__'] = "orange.css";
		break;
	case 3:
		$_SESSION['__css__'] = "red.css";
		break;
	case 4:
		$_SESSION['__css__'] = "teal.css";
		break;
}

?>