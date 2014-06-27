<?php

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

// estraggo i progetti
if(!isset($_REQUEST['offset'])){
    $offset = 0;
}
else{
    $offset = $_REQUEST['offset'];
}

$limit = 12;

//$sel_projects = "SELECT * FROM progetti WHERE referenti LIKE '%#".$_SESSION['__uid__']."#%' ORDER BY anno_inizio DESC ";
$sel_projects = "SELECT rb_progetti.*, rb_anni.descrizione AS anno FROM rb_progetti, rb_anni WHERE id_anno = anno_inizio ORDER BY anno_inizio DESC ";

if(!isset($_GET['second'])){
    $res_projects = $db->execute($sel_projects);
    //print $sel_links;
    $count = $res_projects->num_rows;
    $_SESSION['count_projects'] = $count;
}
else{
    $sel_projects .= "LIMIT $limit OFFSET $offset";
    $res_projects = $db->execute($sel_projects);
}

if($offset == 0){
    $page = 1;
}
else{
    $page = ($offset / $limit) + 1;
}

$pagine = ceil($_SESSION['count_projects'] / $limit);
if($pagine < 1){
    $pagine = 1;
}

// dati per la paginazione (navigate.php)
$colspan = 5;
$link = basename($_SERVER['PHP_SELF']);
$count_name = "count_projects";

include "progetti.html.php";


?>