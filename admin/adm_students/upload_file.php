<?php

include "../../lib/start.php";

check_session();

if(isset($_REQUEST['action'])){
	$display = "inline";
}
else{
	$display = "none";
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Iframe file</title>
<script type="text/javascript">
var load = function(){
	document.getElementById('_file').style.display = "none";
	document.getElementById('_span').style.display = "inline";
	document.forms[0].submit();
};
</script>
<style>
input, select, textarea {

}
</style>
</head>
<body>
<form action="upload_file.php?action=upload" method="post" enctype="multipart/form-data" id="doc_form">
<div style="height: 45px; display: inline" id="_div">
<input id="_file" class="form_input" type="file" name="fname" class="file" style="width: 250px; <?php if(isset($_REQUEST['action'])){ ?>display: none<?php } ?>" onchange="load()" />
<span style="font-weight: normal; color: red; font-size: 1.1em; display: <?php echo $display ?>" id="_span">Attendere la copia del file...</span>

<?php
flush();
flush();
?>
</div>
</form>
</body>
</html>
<?php
// create
flush();
flush();
if(isset($_REQUEST['action']) && $_REQUEST['action'] == "upload"){
	/**
	 * gestione del filesystem
	 */
	$file_name = basename( $_FILES['fname']['name']);
	$file = ereg_replace(" ", "_", basename( $_FILES['fname']['name']));

	/**
	 * gestione file nel filesystem
	 */
	$dir = $_SESSION['__config__']['html_root']."/tmp";
	$link = "didattica/".$_SESSION['__user__']->getUid()."/".$file;

	$target_path = $dir . "/" . $file;
	//print $target_path;
	print("<script>document.getElementById('_span').innerHTML = 'Attendere il caricamento del file...'</script>");
	flush();
	flush();
	if(move_uploaded_file($_FILES['fname']['tmp_name'], $target_path)) {
		//echo "The file ".  basename( $_FILES['fname']['name']). " has been uploaded";
		chdir($dir);
		chmod($file, 0644);
	} else{
		//fwrite($log, "There was an error uploading the file, please try again!\n");
		echo "ko|There was an error uploading the file, please try again!|".$target_path;
		//fclose($log);
		exit;
	}
	print("<script>document.getElementById('_span').innerHTML = '$file_name'; document.getElementById('_span').style.color = 'black'; parent.document.forms[0].server_file.value = '$file';</script>");
	flush();
	flush();
}