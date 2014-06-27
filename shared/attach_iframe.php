<?php 

require_once "../lib/MimeType.php";
require_once "../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();

if(!isset($_REQUEST['upl'])){
	/**
	 * se esistono file in sessione, cancellarli ed eliminarli dalla sessione
	 */
	if(isset($_SESSION['files'])){
		$dir = $_SESSION['__config__']['html_root']."/tmp/attachements/";
		foreach($_SESSION['files'] as $file){
			$file_to_delete = $dir.$file['server_name'];
			if(file_exists($file_to_delete)){
				unlink($file_to_delete);
			}
		}
	}
	unset($_SESSION['files']);
}
	
if(isset($_REQUEST['upl'])){
	$counter = $_REQUEST['ct'];
	$name = $_FILES['input'.$counter]['name'];
	$_SESSION['files'][$counter] = array();
	$_SESSION['files'][$counter]['name'] = $name;
	$mime = MimeType::getMimeContentType($name, $tipo);
	$_SESSION['files'][$counter]['mime'] = $mime['tipo'];
	$filesize = filesize($_FILES['input'.$counter]['tmp_name']);
	if($filesize < 1024)
		$filesize .= "B";
	else{
		$filesize /= 1024;
		$filesize = round($filesize, 0);
		$filesize .= "K";
	}
	$_SESSION['files'][$counter]['filesize'] = $filesize;
	//$new_name = ereg_replace(" ", "_", basename( $_FILES['input'.$counter]['name']));
	$new_name = basename( $_FILES['input'.$counter]['name']);
	$_SESSION['files'][$counter]['server_name'] = $new_name;
	$dir = $_SESSION['__config__']['html_root']."/tmp/attachements/";
	$target_path = $dir.$new_name;
	if(move_uploaded_file($_FILES['input'.$counter]['tmp_name'], $target_path)) {
		//echo "The file ".  basename( $_FILES['fname']['name']). " has been uploaded";
		//chdir($dir);
		chmod($target_path, 0644);
	} else{
		echo "There was an error uploading the file, please try again!";
		exit;
	}
}
else{
	$counter = 0;
}

include "attach_iframe.html.php";

?>