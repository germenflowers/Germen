<?php

/**
 * @global CMain $APPLICATION
 * @global CMain $USER
 */

use \Germen\Admin\Rights;

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

if ($USER->IsAuthorized() === false) {
    LocalRedirect('/admin/login/');
    exit;
}

$rights = new Rights();
if (!$rights->checkOrdersRights('view')) {
    LocalRedirect('/');
    exit;
}

LocalRedirect('/admin/orders/');
exit;
