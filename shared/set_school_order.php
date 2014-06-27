<?php

include "../lib/start.php";

check_session();

$anno = $_SESSION['__current_year__']->get_ID();

header("Content-type: text/plain");

$_SESSION['__school_order__'] = $_POST['id'];

echo "ok";
exit;