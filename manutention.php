<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>Registro elettronico::login</title>
<script type="text/javascript" src="./js/prototype.js"></script>
<script type="text/javascript" src="./js/scriptaculous.js"></script>
<script type="text/javascript" src="./js/controls.js"></script>
<script type="text/javascript" src="./js/page.js"></script>
<script type="text/javascript" src="./js/md5-min.js"></script>
<link href="css/site_themes/<?php echo getTheme() ?>/index.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">

</script>
</head>
<body>
<header id="header">
    <div class="wrap">
        <div style="" id="_header">
            <?php echo stripslashes($_SESSION['__config__']['intestazione_scuola']) ?><br />
            <p style="font-size: 0.7em; font-weight: normal; line-height: 20px; margin: 0; padding-top: 10px; text-transform: none">
                <?php echo $_SESSION['__config__']['software_name']." ".$_SESSION['__config__']['software_version'] ?> - Registro elettronico<span id="area"></span>
            </p>
        </div>
    </div>
</header>
<section class="wrap">
    <div id="login_form" style="font-size: 2em; font-weight: bold; text-align: center; padding: 75px 15px 0 15px">
        Il sito è temporaneamente inutilizzabile. Ci scusiamo per il disagio
    </div>
</section>
</body>
</html>
