<?php

require_once "../../lib/start.php";

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_reg_home__'] = "./";

list($birthday, $birthday_perms) = explode("#", $_SESSION['__user__']->getBirthday());
list($address, $address_perms) = explode("#", $_SESSION['__user__']->getAddress());
list($phone, $phone_perms) = explode("#", $_SESSION['__user__']->getPhone());
list($cellphone, $cellphone_perms) = explode("#", $_SESSION['__user__']->getMobile());
list($email, $email_perms) = explode("#", $_SESSION['__user__']->getEmail());
list($messenger, $messenger_perms) = explode("#", $_SESSION['__user__']->getMessenger());
list($website, $website_perms) = explode("#", $_SESSION['__user__']->getWeb());
list($blog, $blog_perms) = explode("#", $_SESSION['__user__']->getBlog());

$navigation_label = "Registro elettronico - Gestione dati personali";

include "account.html.php";
