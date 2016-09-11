<?php

include "../lib/start.php";
include "../lib/Authenticator.php";
include "../lib/AccountManager.php";
header("Content-type: application/json");
$response = ['status' => 'ok', 'message' => 'ok'];

if (isset($_POST['token']) && $_POST['token'] != '') {
    // get the user
    $token = $db->real_escape_string($_POST['token']);
    $auth = new Authenticator(new MySQLDataLoader($db));
    $user = $auth->loginWithToken($token, $_POST['area']);

    if ($user == null) {
        $response['message'] = 'Token non presente. Rifare il login';
        $response['status'] = 'ko';
        echo json_encode($response);
        exit;
    }
    $acc_man = new AccountManager($user, new MySQLDataLoader($db));
    if ($acc_man->checkToken()) {
        $json = $user->toJSON();
        $response['user'] = $json;
        echo json_encode($response);
        exit;
    }
    else {
        // TODO: catch error
    }
}
else {
    if (isset($_POST['nick']) && isset($_POST['pwd'])) {
        // get the user
        $auth = new Authenticator(new MySQLDataLoader($db));
        $nick = $db->real_escape_string($_POST['nick']);
        $pass = md5($db->real_escape_string($_POST['pwd']));
        $user = $auth->login($_POST['area'], $nick, $pass);

        if ($user != null) {
            $acc_man = new AccountManager($user, new MySQLDataLoader($db));
            $token = $acc_man->createToken();
            $user->setToken($token);
            $json = $user->toJSON();
            $response['user'] = $json;
            echo json_encode($response);
            exit;
        }
        else {
            $response['status'] = 'ko';
            $response['message'] = "errore user null";
            $response['nick'] = $nick;
            $response['pass'] = $pass;
            echo json_encode($response);
            exit;
        }
    }
    else {
        // TODO: catch error
    }
}