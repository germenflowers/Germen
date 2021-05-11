<?php

/**
 * @global CMain $APPLICATION
 * @global CMain $USER
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';

if (!empty((int)$USER->GetID())) {
    $USER->Logout();
}

if ($USER->IsAuthorized() === false) {
    LocalRedirect('/admin/');
    exit;
}

$APPLICATION->SetTitle('Выход');
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';
?>