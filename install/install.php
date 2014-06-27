<?php

include_once '../lib/functions.lib.php';
include_once '../lib/classes.php';
include_once '../lib/database.lib.php';

session_start();

$step = (isset($_REQUEST['step'])) ? $_REQUEST['step'] : ((isset($_SESSION['step'])) ? $_SESSION['step'] : 1);

if($step == 1){
	// controllo se gia` esiste il file di configurazione della connessione
	if(file_exists("../lib/conn.php")){
		$warning = "Attenzione: il file con i parametri di connessione &egrave; gi&agrave; presente in archivio. Se invii nuovamente il modulo, il file esistente verr&agrave; cancellato.";
		$warning .= "<br />Se vuoi proseguire con la configurazione senza modificare i parametri di connessione, clicca su Avanti";
	}
}

include "install{$step}.html.php";

?>
