<?php

include_once '../lib/functions.lib.php';
include_once '../lib/classes.php';
include_once '../lib/database.lib.php';
if($_REQUEST['step'] > 1) include_once '../lib/conn.php';

session_start();

ini_set("display_errors", "0");

switch($_REQUEST['step']){
	case "1":
		header("Content-type: text/plain");
		$server   = $_REQUEST['host'];
		$db 	  = $_REQUEST['db'];
		$user     = $_REQUEST['user'];
		$password = $_REQUEST['password'];
		$port     = $_REQUEST['port'];
		$mysqli = new mysqli($server, $user, $password, $db, $port);
		if (mysqli_connect_error()) {
			$errno = mysqli_connect_errno();
			switch($errno){
				case "2005":
					// e
					$error = "il server indicato non esiste o &egrave; irraggiungibile.";
					break;
				case 1045:
					// access denied for user
					$error = "l'utente indicato non esiste o la password inserita non &egrave; corretta";
					break;
				case 1049:
					// unknow database
					$error = "il database indicato non esiste";
					break;
				case 2003:
					// Can't connect to MySQL server on...
					$error = "la porta indicata non &egrave; corretta";
					break;
			}
		    echo "ko#Errore di connessione: verifica attentamente i dati inseriti (".mysqli_connect_errno()."). Messaggio d'errore: ".mysqli_connect_error()."#".$errno."#".$error;
		    exit;
		}
		else{
			// parametri ok: crea il file
			//chmod("../lib", 0777);
			$conn_file = fopen("../lib/conn.php", "w");
			if(!$conn_file){
				echo "ko#File non creato";
				exit;
			}
			fwrite($conn_file, "<?php");
			fwrite($conn_file, "\n\n");
			fwrite($conn_file, "\$db = new MySQLConnection(\"$server\", \"$user\", \"$password\", \"$db\", \"$port\");");
			fwrite($conn_file, "\n\$db->set_charset(\"utf-8\");");
			fwrite($conn_file, "\n\n");
			fwrite($conn_file, "?>\n");
			$_SESSION['step'] = 2;
			echo "ok";
			exit;
		}
		break;
	case "2":
		header("Content-type: text/plain");
		$first_name = $_REQUEST['fname'];
		$last_name  = $_REQUEST['lname'];
		$email 		= $_REQUEST['email'];
		$pwd  		= md5($_REQUEST['pwd']);
		$insert_user = "INSERT INTO rb_utenti (username, password, nome, cognome, accessi, permessi, last_access, previous_access) VALUES ('admin', '$pwd', '$first_name', '$last_name', 0, 1, NULL, NULL);";
		$insert_group = "INSERT INTO rb_gruppi_utente (gid, uid) VALUES (1, 1);";
		$insert_profile = "INSERT INTO rb_profili (id, email) VALUES (1, '$email')";
		$insert_step = "INSERT INTO rb_config (variabile, valore, readonly) VALUES ('admin', '".$first_name." ".$last_name."', 0), ('admin_email', '$email', 0)";
		$file = file("db.sql");
		try{
			$db->executeUpdate("BEGIN");
		} catch (MySQLException $ex){
			$db->executeUpdate("ROLLBACK");
			echo "ko#".$ex->getMessage()."#".$ex->getQuery();
			exit;
		}
		foreach($file as $sql){
			try{
				$db->executeUpdate($sql);
			} catch (MySQLException $ex){
				$db->executeUpdate("ROLLBACK");
				echo "ko#".$ex->getMessage()."#".$ex->getQuery();
				exit;
			}
		}
		try{
			$db->executeUpdate($insert_user);
			$db->executeUpdate($insert_group);
			$db->executeUpdate($insert_profile);
			$db->executeUpdate($insert_step);
		} catch (MySQLException $ex){
			$db->executeUpdate("ROLLBACK");
			echo "ko#".$ex->getMessage();
			exit;
		}
		$db->executeUpdate("COMMIT");
		$_SESSION['step'] = 3;
		echo "ok";
		break;
	case "3":
		$school = $db->real_escape_string($_REQUEST['school']);
		$address = $db->real_escape_string($_REQUEST['address']);
		$start  = $_REQUEST['start'];
		$stop   = $_REQUEST['stop'];
		$insert_vars = "INSERT INTO rb_config (variabile, valore, readonly) VALUES ('intestazione_scuola', '$school', 0), ('indirizzo_scuola', '$address', 0)";
		$insert_vars .= ", ('last_stats_absences_manager', '".date("Y-m-d")."', 1), ('software_name', 'e-School', 1), ('software_version', '0.9.5-2', 1), ('installazione_completata', '0', 1)";
		/*
		 * inserimento dell'anno
		 */
		$start_day = $end_day = $lessons_start_day = $lessons_end_day = null;
		$description = "";
		$y = (int) date("Y");
		if(date("md") < "0831"){
			/*
			 * siamo nell'anno in scadenza
			 */
			$start_day = date("Y-m-d");
		}
		else{
			/* 
			 * tra il 1 settembre e il 31 dicembre
			 */
			$start_day = "{$y}-09-01";
			$end_day = ($y + 1)."-08-31";
			$lessons_start_day = "{$y}-09-15";
			$lessons_end_day = ($y + 1)."-06-10";
			$description = "{$y}-".($y + 1);
		}
		$insert_year = "INSERT INTO rb_anni (descrizione, data_inizio, data_fine, data_inizio_lezioni, data_termine_lezioni) VALUES ('{$description}', '{$start_day}', '{$end_day}', '{$lessons_start_day}', '{$lessons_end_day}')";
		/*
		 * creazione delle variabili di posizione
		 * #1: root_site - contiene la directory di installazione del software in uri web
		 * #2: document_root - contiene la directory base del server in uri filesystem
		 * #3: html_root - contiene la directory di installazione del software in uri filesystem, a partire da document_root
		 * #4: copia di root_site in functions.lib.php
		 */
		$rs = explode("rclasse", $_SERVER['HTTP_REFERER']);
		$root_site = $rs[0]."rclasse";
		$hr = explode("rclasse", $_SERVER['SCRIPT_FILENAME']);
		$html_root = $hr[0]."rclasse";
		$insert_vars .= ", ('root_site', '$root_site', 1), ('document_root', '".$_SERVER['DOCUMENT_ROOT']."', 1), ('html_root', '$html_root', 1), ('debug', '0', 1)";
		$del_install = "DELETE FROM rb_config WHERE variabile = 'nuova_installazione'";
		try{
			$db->executeUpdate($insert_vars);
			$db->executeUpdate($del_install);
			$year = $db->executeUpdate($insert_year);
			$db->executeUpdate("INSERT INTO rb_dati_lezione (id_anno, id_ordine_scuola) SELECT {$year}, id_tipo FROM rb_tipologia_scuola WHERE id_tipo != 4");
		} catch (MySQLException $ex){
			echo "ko#".$ex->getMessage()."#".$ex->getQuery();
			exit;
		}
		
		$funcs = file("../lib/functions.lib.php");
		$funcs[2] = "include 'pers_define.php';\n\n" . $funcs[2];
		$lib = fopen("../lib/functions.lib.php", "w");
		foreach($funcs as $line){
			if(!fwrite($lib, $line)){
				echo "ko#function.lib non modificato";
				exit;
			}
		}
		fclose($lib);
		
		$def_file = fopen("../lib/pers_define.php", "w");
		$def_row = "<?php\n\ndefine('ROOT_SITE', '{$root_site}');\n\n?>\n";
		if(!fwrite($def_file, $def_row)){
			echo "ko#file define non creato";
			exit;
		}
		fclose($def_file);
		mkdir("../download", 0777);
		mkdir("../tmp", 0777);
		chmod("../download", 0777);
		chmod("../tmp", 0777);
		$file_to_delete = "./to_be_installed";
		unlink($file_to_delete);
		$_SESSION['step'] = 4;
		echo "ok";
		exit;
		break;
		
}
