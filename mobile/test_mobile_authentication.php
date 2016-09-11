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
    <title>Prova autenticazione da dispositivi mobili</title>
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

        var auth = function() {
            url = "authenticate.php";

            $.ajax({
                type: "POST",
                url: url,
                data: {token: 'LHJJKHJHJHKYHDI*(#J', area: 2},
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

        var ntoken = function() {
            url = "authenticate.php";

            $.ajax({
                type: "POST",
                url: url,
                data: {nick: 'rebecca.locci', pwd: 'locci', area: 2},
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
                        _user = $.parseJSON(json.user);
                        $('#user').html('<p>'+_user.username+'</p>');
                    }
                    $('#response').text(json.message).css({fontWeight: 'bold'});
                }
            });
        };

        var auth_token = function() {
            url = "authenticate.php";

            $.ajax({
                type: "POST",
                url: url,
                data: {token: 'fbc81e76a44ab83e5bf00c55777c4a58', area: 2},
                dataType: 'json',
                error: function () {
                    alert("Errore di trasmissione dei dati");
                },
                succes: function () {

                },
                complete: function (data) {
                    r = data.responseText;
                    if (r == "null") {
                        return false;
                    }
                    var json = $.parseJSON(r);
                    if (json.status == "ko") {
                        j_alert("error", json.message);
                    }
                    else {
                        _user = $.parseJSON(json.user);
                        $('#user').html('<p>' + _user.uid + '</p>');
                    }
                    $('#response').text(json.message).css({fontWeight: 'bold'});
                }
            });
        };
    </script>
</head>
<body>
    <p>
        <a href="#" onclick="auth()">Verifica autenticazione</a>
    </p>
    <p>
        <a href="#" onclick="ntoken()">Verifica creazione token</a>
    </p>
    <p>
        <a href="#" onclick="auth_token()">Verifica autenticazione tramite token</a>
    </p>
    <div id="response">

    </div>
    <div id="user">

    </div>
</body>
</html>