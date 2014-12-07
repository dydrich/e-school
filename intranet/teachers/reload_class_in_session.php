<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 07/12/14
 * Time: 19.02
 * reload class in session if reload=1
 */ 
if (isset($_REQUEST['reload']) && $_REQUEST['reload'] == 1) {
	require_once $_SESSION['__path_to_root__']."lib/SessionUtils.php";
	$utils = SessionUtils::getInstance($db);
	$utils->registerCurrentClassFromClassID($_REQUEST['cls'], "__classe__");
}
