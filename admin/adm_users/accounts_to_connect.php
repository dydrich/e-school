<?php
/**
 * list of teacher accounts to connect
 * User: riccardo
 * Date: 10/15/16
 * Time: 6:07 PM
 */
require_once "../../lib/start.php";
require_once "../../lib/AccountConnector.php";

check_session();
check_permission(ADM_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$atc = new \eschool\AccountConnector(new MySQLDataLoader($db));
$connected = $atc->getConnectedAccounts();
$accounts = $atc->getAccountsToConnect();

foreach ($accounts as $n => $account) {
	$uids = [];
	foreach ($account['uids'] as $uid) {
		$uids[] = $uid['uid'];
	}
	$a = $uids[0];
	$b = $uids[1];
	if ($atc->checkAccountToConnect($a, $b)) {
		unset($accounts[$n]);
	}
}

$drawer_label = "Account docente da collegare";

include "accounts_to_connect.html.php";