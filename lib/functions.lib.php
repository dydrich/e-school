<?php

require_once "pers_define.php";

define("IT_DATE_STYLE", 1);
define("SQL_DATE_STYLE", 2);
define("US_DATE_STYLE", 3);

define("INSERT_OBJECT", 1);
define("DELETE_OBJECT", 2);
define("UPDATE_OBJECT", 3);

// check_permission (vedi tabella gruppi)
define("ADM_PERM", 1);
define("DOC_PERM", 2);
define("ATA_PERM", 4);
define("GEN_PERM", 8);
define("COO_PERM", 16);
define("SEG_PERM", 32);
define("DIR_PERM", 64);
define("DSG_PERM", 128);
define("STD_PERM", 256);
define("APS_PERM", 512);
define("AMS_PERM", 1024);
define("AIS_PERM", 2048);
/*
 * raggruppa i permessi di accesso alle statistiche alunni
 */
define("STAT_STUD_PERM", 240);

/*
 * tipologie di finestra per check_session
 * 
 */
define("MAIN_WINDOW", 1);
define("POPUP_WINDOW", 2);
define("FAKE_WINDOW", 3);
define("AJAX_CALL", 4);

/*
 * durata dell'autenticazione in admin senza attivita'
 */
define("ACTIVE_ADMIN_MINUTES", 10);
define("ACTIVE_ADMIN_SECONDS", (ACTIVE_ADMIN_MINUTES*60));

/*
 * ordini di scuola
 */
define("MIDDLE_SCHOOL", 1);
define("PRIMARY_SCHOOL", 2);
define("FIRST_SCHOOL", 3);

/*
 * paginazione
 */
define("PREVIOUS", -1);
define("NEXT", 1);
define("INDEX_OUT_OF_BOUND", 0);

/*
 * administration area groups
 */
define("DS_GROUP", 6);
define("SEG_GROUP", 5);
define("DSGA_GROUP", 7);


/**

         linkedPages

@Author:                Riccardo Bachis
@Copyright:             2003 Riccardo Bachis
@Created at:            Roma, 16 apr 2003
@Last Modified Time:    19 ott 2007

@DESC:      paginatore: restituisce la pagina di partenza e quella
            di arrivo per i link del paginatore
@param:     $offset - variabile per calcolo della pagina
@param:     $record_per_page - numero di record per pagina
@param:     $pages - il numero di pagine totali
@return:    array - la pagina di partenza e quella finale in array

 *****************************************************************************************/
function linkedPages($offset, $record_per_page, $pages){
    if($offset == 0)
        $page = 1;
    else
        $page = ($offset / $record_per_page) + 1;
    if(($offset % $record_per_page) != 0)
    	$page++;
    if($pages < 11){
        return array(1, $pages);
    }
    else{
        $start = $end = 0;
        if($page < 6){
            $start = 1;
            $end = 10;
            $pagine = array(1, 10);
        }
        else{
            $max = $page + 5;
            $back_nmb = 4;
            $forw_nmb = 5;
            if($max > $pages){
                $diff = $max - $pages;
                $back_nmb += $diff;
            }
            $pagine = array($page - $back_nmb, $page + $forw_nmb);
        }
    }
    return $pagine;
}

/**

        check_mail

@Author:                Riccardo Bachis
@Copyright:             2003 Riccardo Bachis
@Created at:            Roma, 16 mar 2003
@Last Modified Time:    16 mar 2003

@DESC:      verifica la struttura formale di un indirizzo email
@param:     $mail - l'indirizzo da verificare
@return:    bolean

*****************************************************************************************/

function check_mail($mail){
    if(!preg_match("/.+@.+\..+/", $mail))
        return false;

    return true;

}


/**

         field_null

@Author:                Riccardo Bachis
@Copyright:             2003-2007 Riccardo Bachis
@Created at:            Roma, 15 mar 2003
@Last Modified Time:    11 gen 2011

@DESC:      formatta i parametri delle stringhe sql settandoli a NULL se
            non presenti o aggiungendoci gli apici singoli se necessario
@param:     $var - il parametro da controllare
@param		$is_char - indica se il parametro e' di tipo stringa: nel caso, servono gli apici singoli
@return:    $res - la stringa convertita

*****************************************************************************************/

function field_null($var, $is_char){
    if($var == "")
        $res = "NULL";
    else{
        $res = $var;
        if($is_char)
        	$res = "'$res'";
    }
    return $res;
}

/**

         number_format

@Author:                Riccardo Bachis
@Copyright:             2003 Riccardo Bachis
@Created at:            Roma, 15 apr 2003
@Last Modified Time:    15 apr 2003

@DESC:      formatta i parametri numerici o stringhe portandoli ad un numero
            di caratteri passato come parametro, aggiungendo serie di caratteri
@param:     $val - il parametro da manipolare
@param:     $length - la lunghezza da raggiungere
@param:     $char - il carattere da usare come riempi posto
@return:    $res - la stringa modificata

*****************************************************************************************/

function nmb_format($val, $length, $char){
    if(strlen($val) < $length){
        $start = strlen($val) - 1;
        for($i = $srtart; $i < $length - 1; $i++)
            $val = $char.$val;
    }
    return $val;
}


/**

         format_date

@Author:                Riccardo Bachis
@Copyright:             2003 Riccardo Bachis
@Created at:            Roma, 15 mar 2003
@Last Modified Time:    15 mar 2003

@desc:      formatta le date
@param:     $data - la stringa da formattare
@param:     $or_style - stile della data da formattare (1=>g/m/a, 2=>a/m/g, 3=>m/g/a)
@param:     $style - stile di conversione (1=>g/m/a, 2=>a/m/g)
@param:     $separator - carattere separatore
@return:    $data_mod - la stringa convertita

*****************************************************************************************/

function format_date($data, $or_style, $style, $separator){
    if($data == ""){
    	return "";
    }
    if(get_date_format($data) == $style){
    	return $data;
    }
    if($or_style == IT_DATE_STYLE)
        list($day, $month, $year) = preg_split("/[\/\.-]/", $data);
    else if($or_style == SQL_DATE_STYLE)
        list($year, $month, $day) = preg_split("/[\/\.-]/", $data);
    else
        list($month, $day, $year) = preg_split("/[\/\.-]/", $data);
	
	if(!checkdate($month, $day, $year))
    	return "";
	
    if($style == IT_DATE_STYLE)
        $data_mod = $day.$separator.$month.$separator.$year;
    else
        $data_mod = $year.$separator.$month.$separator.$day;

    return $data_mod;
}

function check_session($window = MAIN_WINDOW){
    if(!isset($_SESSION['__user__'])){
    	switch($window){
    		case POPUP_WINDOW:
    			print("<script type='text/javascript'>alert('Sessione scaduta: rifai il login'); window.opener.document.location.href = '".$_SESSION['__config__']['root_site']."/index.php'; window.close();</script>");
    			break;
    		case FAKE_WINDOW:
    			print("<script type='text/javascript'>alert('Sessione scaduta: rifai il login'); window.parent.document.location.href = '".$_SESSION['__config__']['root_site']."/index.php';</script>");
    			break;
    		case AJAX_CALL:
    		case MAIN_WINDOW:
    		default:
    			header("Location: ".ROOT_SITE);
    			break;
    	}
        exit;
    }
}

function check_permission($admitted, $window = MAIN_WINDOW){
	if($_SESSION['__user__']->check_perms($admitted) == false){
		// registro in sessione la pagina chiamante
		$_SESSION['__referer__'] = $_SERVER['HTTP_REFERER'];
		switch ($_SESSION['__area__']){
			case "teachers":
				$basepath = $_SESSION['__path_to_root__']."intranet/teachers/";
				break;
		}
		switch($window){
    		case POPUP_WINDOW:
    			print("<script type='text/javascript'>window.opener.document.location.href = '{$basepath}no_permission.php'; window.close();</script>");
    			break;
    		case FAKE_WINDOW:
    			print("<script type='text/javascript'>window.parent.document.location.href = '{$basepath}no_permission.php';</script>");
    			break;
    		case AJAX_CALL:
    			echo "no_permission";
    			break;
    		case MAIN_WINDOW:
    		default:
    			header("Location: {$basepath}no_permission.php");
    			break;
    	}
		
		exit;
	}

}

/**

get_login

@Author:                Riccardo Bachis
@Copyright:             2004 Riccardo Bachis


@desc:      riceve in input nome, cognome di un utente e un elenco di username, compone la login e verifica che non sia già presente,
			aggiungendo un contatore numerico
@param:     $names - array di usernames tra i quali controllare la presenza di quello creato
@param:     $nome - nome dell'utente
@param:     $cognome - cognome dell'utente
@return:    $login - username nel formato nome.cognome

*****************************************************************************************/
function get_login($names, $nome, $cognome){
	// analizzo il nome: se composto, utilizzo solo il primo
	if(preg_match("/ /", $nome)){
		$nomi = explode(" ", $nome);
	}
	else{
		$nomi[0] = $nome;
		$nomi[1] = "";
	}
	// elimino eventuali accenti (apostrofi) e spazi (solo dal cognome)
	$nm = strtolower(preg_replace("/'/", "", $nomi[0].$nomi[1]));
	$cm = strtolower(preg_replace("/'/", "", trim($cognome)));
	$cm = strtolower(preg_replace("/ /", "", $cm));
	// creo la login e verifico
	$login = $nm.".".$cm;
	$base_login = $login;
	$length = count($login);
	$ok = false; 
	// valore numerico per la creazione di login univoche
	$index = 1;
	while(!$ok){
		if(!in_array($login, $names)){
			return $login;
		}
		else{
			$login = $base_login.$index;
			$index++;
		}
	}
}

function get_password($str1, $str2, &$pwd_chiaro){
	$pwd_chiaro = substr(strrev($str1), 0, 4).rand(0, 9).rand(0, 9).substr(strrev($str1), 0, 2);
	return md5($pwd_chiaro);
}

function text2html($html){
    if($html == "")
    	return $html;
    $html = preg_replace("/\n/", "<br />", $html);
    //$html = preg_replace("/<br>/", "\n", $html);
    return $html;
}

function time_to_sec($time) {
    list($hours, $minutes, $seconds) = explode(":", $time);

    return $hours * 3600 + $minutes * 60 + $seconds;
} 

/**

        minutes2hours

@Author:                Riccardo Bachis
@Copyright:             2011 Riccardo Bachis
@Created at:            Siliqua, 14 gen 2011
@Last Modified Time:    14 gen 2011

@DESC:      restituisce il tempo ricevuto in minuti nel formato hh:mm
@param:     $time_from - il tempo da formattare
@param:     $zero_string - la stringa da restituire se il tempo = 0
@return:	string: tempo formattato

*****************************************************************************************/

function minutes2hours($time_from, $zero_string){
	$fmt_time = $time_from%60;
	$x = $time_from - $fmt_time;
	$x /= 60;
	if($x < 10)
		$x = "0".$x;
	if($fmt_time < 10)
		$fmt_time = "0".$fmt_time;
	$fmt_time = $x.":".$fmt_time;
	if($fmt_time == "00:00")
		return $zero_string;
	return $fmt_time;
}

/**

        truncateString

@Author:                
@Copyright:             
@Created at:            
@Last Modified Time:    31 mag 2011
@source:				http://www.senamion.it/2006/05/30/c-troncare-una-stringa-senza-tagliare-una-parola/

@DESC:      restituisce una stringa troncandola ad un numero max di caratteri, aggiungendo se necessario dei caratteri di "continua"
@param:     $txt - la stringa da formattare
@param:     il numero max di caratteri
@return:	string: la stringa formattata

*****************************************************************************************/
function truncateString($txt, $chars=50) { 
	if (strlen($txt) <= $chars) 
		return $txt; 
	$new = wordwrap($txt, $chars, "|"); 
	$new_text = explode("|",$new); 
	return $new_text[0]." [...]"; 
}

/**

is_installed

@Author:                Riccardo Bachis
@Copyright:             2012 Riccardo Bachis
@Created at:            Siliqua, 24 mar 2012
@Last Modified Time:    24 mar 2012

@DESC:      verifica se un modulo e' installato
@param:     $module - il modulo del quale controllare l'installazione
@return:	boolean: true - installato, false - non installato

*****************************************************************************************/
function is_installed($module){
	if (!isset($_SESSION['__modules__'][$module]))
		return false;
	return $_SESSION['__modules__'][$module]['installed'];
}

/**

check_time

@Author:                Riccardo Bachis
@Copyright:             2012 Riccardo Bachis
@Created at:            Siliqua, 15 apr 2012
@Last Modified Time:    15 apr 2012

@DESC:      verifica la stringa passata e` un orario valido
@param:     $orario - la stringa da controllare
@return:	boolean: 

*****************************************************************************************/
function check_time($orario){
	$data = explode(":", $orario);
	$h = intval($data[0]);
	if($h < 0 || $h > 23)
		return false;
	$m = intval($data[1]);
	if($m < 0 || $m > 59)
		return false;
	if(isset($data[3])){
		$s = intval($data[3]);
		if($s < 0 || $s > 59)
			return false;
	}
	return true;
}

/**

get_sibling
Restituisce l'elemento precedente o successivo in un array contenente 2 campi: id e valore.
Riceve un array di elementi, un id da ricercare e un parametro che indica se restituire il precedente o il successivo

@Author:                Riccardo Bachis
@Copyright:             2012 Riccardo Bachis
@Created at:            Siliqua, 6 feb 2012
@Last Modified Time:    14 gen 2011

@DESC:      restituisce, in un elenco di elementi id-valore (array), l'elemento precedente o successivo
@param:     $elements - array con gli elementi
@param:     $value - il valore di riferimento
@param:		$par - PREVIOUS o NEXT
@return:	array: elemento ricercato

*****************************************************************************************/

function get_sibling($elements, $value, $par){
	$ct = count($elements);
	$index = -1;
	for($i = 0; $i < $ct; $i++){
		if($elements[$i]['id'] == $value)
			$index = $i;
	}
	if($index == -1)
		return $index;
	if($index == 0 && ($par == PREVIOUS))
		return $elements[$ct - 1];
	else if(($index == ($ct - 1)) && ($par == NEXT))
		return $elements[0];
	if($par == PREVIOUS)
		$index--;
	else if($par == NEXT)
		$index++;
	return $elements[$index];
}

/**

date_get_format
Restituisce la formattazione di una data ricevuta come argomento.

@Author:                Riccardo Bachis
@Copyright:             2012 Riccardo Bachis
@Created at:            Siliqua, 22 set 2012
@Last Modified Time:    

@DESC:      restituisce uno tra IT_DATE_STYLE, SQL_DATE_STYLE, 0 per formato non riconosciuto, -1 se data non valida
@param:     $date - la data da analizzare
@return:	integer: stile di formattazione

*****************************************************************************************/
function get_date_format($date){
	if(strlen($date) != 10){
		return -1;
	}
	$IT_pattern  = "/\d\d\/\d\d\/\d\d\d\d/";
	$SQL_pattern = "/\d\d\d\d-\d\d-\d\d/";
	if(preg_match($IT_pattern, $date)){
		return IT_DATE_STYLE;
	}
	else if(preg_match($SQL_pattern, $date)){
		return SQL_DATE_STYLE;
	}
	else return 0;
}

function formatBytes($bytes, $precision = 2) {
	$units = array('B', 'KB', 'MB', 'GB', 'TB');

	$bytes = max($bytes, 0);
	$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
	$pow = min($pow, count($units) - 1);
	$bytes /= (1 << (10 * $pow));

	return round($bytes, $precision) . ' ' . $units[$pow];
}

/**

fix_magic_quotes
http://www.nyphp.org/phundamentals/5_Storing-Data-Submitted-Form-Displaying-Database

*****************************************************************************************/
function fix_magic_quotes ($var = NULL, $sybase = NULL)
{
	// if sybase style quoting isn't specified, use ini setting
	if ( !isset ($sybase) )
	{
		$sybase = ini_get ('magic_quotes_sybase');
	}

	// if no var is specified, fix all affected superglobals
	if ( !isset ($var) )
	{
		// if magic quotes is enabled
		if ( get_magic_quotes_gpc () )
		{
			// workaround because magic_quotes does not change $_SERVER['argv']
			$argv = isset($_SERVER['argv']) ? $_SERVER['argv'] : NULL;

			// fix all affected arrays
			foreach ( array ('_ENV', '_REQUEST', '_GET', '_POST', '_COOKIE', '_SERVER') as $var )
			{
				$GLOBALS[$var] = fix_magic_quotes ($GLOBALS[$var], $sybase);
			}

			$_SERVER['argv'] = $argv;

			// turn off magic quotes, this is so scripts which
			// are sensitive to the setting will work correctly
			ini_set ('magic_quotes_gpc', 0);
		}

		// disable magic_quotes_sybase
		if ( $sybase )
		{
			ini_set ('magic_quotes_sybase', 0);
		}

		// disable magic_quotes_runtime
		set_magic_quotes_runtime (0);
		return TRUE;
	}

	// if var is an array, fix each element
	if ( is_array ($var) )
	{
		foreach ( $var as $key => $val )
		{
			$var[$key] = fix_magic_quotes ($val, $sybase);
		}

		return $var;
	}

	// if var is a string, strip slashes
	if ( is_string ($var) )
	{
		return $sybase ? str_replace ('\'\'', '\'', $var) : stripslashes ($var);
	}

	// otherwise ignore
	return $var;
}

/**

getAdminLevel

@Author:                Riccardo Bachis
@Copyright:             2014 Riccardo Bachis
@Created at:            Siliqua, 15 feb 2014
@Last Modified Time:

@DESC:      Restituisce il livello di amministrazione di un utente
@param:     $use - l'utente
@return:	integer: livello di amministrazione

 *****************************************************************************************/
function getAdminLevel($user){
	if ($user->isAdministrator()){
		return 0;
	}
	else {
		return $_SESSION['__school_order__'];
	}
}
