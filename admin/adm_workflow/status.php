<?php

include "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

//$log = fopen("/tmp/mysql.log", "w+");

$sel_status = "SELECT id_status, rb_w_status.nome, permessi, rb_w_uffici.nome AS uff, id_ufficio FROM rb_w_status, rb_w_uffici WHERE permessi&codice_permessi ORDER BY id_status, id_ufficio";
$res_status = $db->execute($sel_status);

include "status.html.php";

?>