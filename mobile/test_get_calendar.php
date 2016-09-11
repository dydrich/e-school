<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 8/22/16
 * Time: 2:33 PM
 */
include "../lib/start.php";

?>

<html>
<head>
    <title>Prova calendario da dispositivi mobili</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="../css/general.css" type="text/css" />
    <link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
    <link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
    <script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="../js/page.js"></script>
    <script type="text/javascript">
        $(function () {
            load_jalert();
            setOverlayEvent();
        });

        var get_cal = function() {
            url = "get_calendar.php";

            $.ajax({
                type: "POST",
                url: url,
                data: {cls: 48},
                dataType: 'json',
                error: function() {
                    alert("Errore di trasmissione dei dati");
                },
                succes: function() {

                },
                complete: function(data){
                    r = data.responseText;
                    if(r == "null"){
                        return false;
                    }
                    var json = $.parseJSON(r);
                    alert(json.status);
                    if (json.status == "ko"){
                        j_alert("error", json.message);
                    }
                    else {

                    }
                    $('#response').text(json.message).css({fontWeight: 'bold'});
                }
            });
        };
    </script>
</head>
<body>
<p>
    <a href="#" onclick="get_cal()">Verifica calendario</a>
</p>

<div id="response">

</div>
<div id="user">

</div>
</body>
</html>