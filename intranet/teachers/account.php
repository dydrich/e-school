<?php

require_once "../../lib/start.php";

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

if ($_SESSION['__user__']->getBirthday() != "") list($birthday, $birthday_perms) = explode("#", $_SESSION['__user__']->getBirthday());
if ($_SESSION['__user__']->getAddress() != "") list($address, $address_perms) = explode("#", $_SESSION['__user__']->getAddress());
if ($_SESSION['__user__']->getPhone() != "") list($phone, $phone_perms) = explode("#", $_SESSION['__user__']->getPhone());
if ($_SESSION['__user__']->getMobile() != "") list($cellphone, $cellphone_perms) = explode("#", $_SESSION['__user__']->getMobile());
if ($_SESSION['__user__']->getEmail() != "") list($email, $email_perms) = explode("#", $_SESSION['__user__']->getEmail());
if ($_SESSION['__user__']->getMessenger() != "") list($messenger, $messenger_perms) = explode("#", $_SESSION['__user__']->getMessenger());
if ($_SESSION['__user__']->getWeb() != "") list($website, $website_perms) = explode("#", $_SESSION['__user__']->getWeb());
if ($_SESSION['__user__']->getBlog() != "") list($blog, $blog_perms) = explode("#", $_SESSION['__user__']->getBlog());

$navigation_label = "Registro elettronico - Gestione dati personali";

include "account.html.php";
