<?php

$now = time();

if($now < $_SESSION['__admin_authentication_timeout__'])
	$_SESSION['__admin_authentication_timeout__'] = $now + ACTIVE_ADMIN_SECONDS;
else
	header("Location: ".$_SESSION['__config__']['root_site']."/admin/login.php");