<?php

include "../lib/start.php";

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="/css/main.css" type="text/css" />
	<title>Registro elettronico</title>
</head>
<body>
	<div id="header">
		<div class="wrap">
			<?php include '../header.php'; ?>
			<?php include 'main_menu.php'; ?>
		</div>
	</div>
	<div class="wrap">
		<div id="main">
			<h3 style="margin-bottom: 20px; margin-left: auto; margin-right: auto; text-align: center">Si &egrave; verificato un errore nell'accesso al database MySQL</h3>
			<?php print $_SESSION['connect_errno'] ?><br />
			<?php print $_SESSION['connect_error'] ?>
		</div>
		
		<?php include "../news.php" ?>
	
		<?php include "../admin/footer.php" ?>
	</div>	
</body>
</html>