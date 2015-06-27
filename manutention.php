<?php

include "lib/start.php";
session_destroy();
unset($_SESSION);

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>Registro elettronico::login</title>
<link href="css/index.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<header id="header">
    <div class="wrap">
        <div style="" id="_header">
            Istituto comprensivo Nivola<br />
            <p style="font-size: 0.7em; font-weight: normal; line-height: 20px; margin: 0; padding-top: 10px; text-transform: none">
                Registro elettronico<span id="area"></span>
            </p>
        </div>
    </div>
</header>
<section class="wrap">
    <div id="login_form" style="font-size: 2.5em; font-weight: bold; text-align: center; padding: 95px 35px 0 35px">
        Il sito è temporaneamente inutilizzabile. <br /><br />Ci scusiamo per il disagio
    </div>
</section>
</body>
</html>

