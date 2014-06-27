<?php

require_once "../lib/start.php";
require_once "../lib/UploadManager.php";
require_once "../lib/RecordGradesDocument.php";

check_session();

if($_REQUEST['area'] == "admin"){
	$main = "#FFFFFF";
}
else{
	$main = "#F3F3F6";
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>File uploader</title>
<link rel="stylesheet" href="../css/main.css" type="text/css" />
<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/scriptaculous.js"></script>
<script type="text/javascript" src="../js/page.js"></script>
<script type="text/javascript"></script>
</head>
<body style="background-color: <?php echo $main ?>">
<form action="upload_manager.php?action=upload&upl_type=<?php echo $_REQUEST['upl_type'] ?>&tipo=<?php echo $_GET['tipo'] ?>" method="post" enctype="multipart/form-data" id="doc_form">
<div style="height: 25px; display: inline" id="_div">
<?php if ($_REQUEST['upl_type'] == "document" && isset($_REQUEST['action'])){ ?>
<span style="font-weight: bold; color: red" id="_span">Attendere la copia del file...</span>
<?php 
}
else{
?>
<input class="form_input" type="file" name="fname" id="fname" class="file" style="width: 300px" onchange="parent.loading(30); document.forms[0].submit()" />
<?php
}
?>
</div>
</form>
</body>
</html>
<?php 
if ($_REQUEST['action'] == "upload"){
	switch ($_GET['upl_type']){
		case "teacherbook_att":
			$path = "download/registri/{$_SESSION['__current_year__']->get_ID()}/docenti/{$_SESSION['__user__']->getUid()}/";
			break;
		case "document":
			$type = $_GET['tipo'];
			$path = "download/{$type}/";
			break;
	}
	
	$file_name = basename( $_FILES['fname']['name']);
	$file = ereg_replace(" ", "_", basename($file_name));
	$upload_manager = new UploadManager($path, $_FILES['fname'], $_GET['upl_type'], $db);
	$upload_manager->setData($_SESSION['registro']);
	$ret = $upload_manager->upload();
	if ($_GET['upl_type'] == "document") {
		switch ($ret){
			case UploadManager::FILE_EXISTS:
				print("<script>parent.timeout = 0; window.setTimeout('parent._alert(\"Il file esiste gi&agrave; in archivio. Rinominalo prima di inserirlo\")', 100); window.setTimeout('parent.parent.win.close()', 2000)</script>");
				break;
			case UploadManager::UPL_ERROR:
				echo "ko|There was an error uploading the file, please try again!|".$_FILES['fname']['name'];
				print("<script>parent.timeout = 0; window.setTimeout('parent._alert(\"There was an error uploading the file, please try again!\")', 100); </script>");
				break;
			case UploadManager::UPL_OK:
				print("<script>parent.timeout = 0; $('_span').innerHTML = '$file_name'; $('_span').style.color = 'black'; parent.document.forms[0].server_file.value = '$file';parent.document.getElementById('del_upl').style.display = 'inline'; </script>");
				break;
		}
	}
	else{
		switch ($ret){
			case UploadManager::FILE_EXISTS:
				print("<script>parent.timeout = 0; window.setTimeout('parent._alert(\"Il file esiste gi&agrave; in archivio. Rinominalo prima di inserirlo\")', 100); window.setTimeout('parent.parent.win.close()', 2000)</script>");
				break;
			case UploadManager::UPL_ERROR:
				echo "ko|There was an error uploading the file, please try again!|".$_FILES['fname']['name'];
				print("<script>parent.timeout = 0; window.setTimeout('parent._alert(\"There was an error uploading the file, please try again!\")', 100); </script>");
				break;
			default:
				echo "<script>parent.timeout = 0; var cont = parent.document.getElementById('att_container');var np = document.createElement('p');np.setAttribute('id', 'att_{$ret}');var _a = document.createElement('a'); _a.setAttribute('href', '#');_a.setAttribute('onclick', 'show_menu(event, {$last}, \"{$ff}\")');_a.setStyle({'textDecoration': 'none'});_a.appendChild(document.createTextNode('{$file_name}'));np.appendChild(_a);cont.appendChild(np);</script>";
				break;
		}	
	}
}
?>