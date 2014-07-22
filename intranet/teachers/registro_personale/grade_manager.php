<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 21/07/14
 * Time: 16.30
 */

require_once "../../../lib/start.php";
require_once "../../../lib/SessionUtils.php";
require_once "../../../lib/Widget.php";
require_once "../../../lib/ChangeSubject.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$docente = $_SESSION['__user__']->getUid();
$anno = $_SESSION['__current_year__']->get_ID();
$materia = $_SESSION['__materia__'];

$action = $_REQUEST['do'];

switch($action){
	case "new":

		break;
	case "update":

		break;
	case "delete":

		break;
	default:

		break;
}