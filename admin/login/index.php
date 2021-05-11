<?php

/**
 * @global CMain $APPLICATION
 * @global CMain $USER
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';

if ($USER->IsAuthorized() === true) {
    LocalRedirect('/admin/');
    exit;
}

$APPLICATION->SetTitle('Авторизация');
?>
<?php $APPLICATION->IncludeComponent(
    'bitrix:system.auth.form',
    '',
    array(
        'FORGOT_PASSWORD_URL' => '/admin/forgot-password/',
        'PROFILE_URL' => '/admin/profile/',
        'REGISTER_URL' => '/admin/registration/',
        'SHOW_ERRORS' => 'Y',
    )
); ?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';
?>