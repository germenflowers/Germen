<?php

/**
 * @global CMain $APPLICATION
 */

use \Bitrix\Main\Page\Asset;
use \Germen\Admin\User;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$currentPage = $APPLICATION->GetCurPage(false);

$isLoginPage = $currentPage === '/admin/login/';
$isPasswordPage = $currentPage === '/admin/change-password/' || $currentPage === '/admin/forgot-password/';
$isRegistrationPage = $currentPage === '/admin/registration/' || $currentPage === '/admin/registration/confirm/' || $currentPage === '/admin/registration/end/';
$isProfilePage = $currentPage === '/admin/profile/';

$user = new User();

$userData = $user->getUserData();
?>
<!doctype html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="theme-color" content="#2F3BA2">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="Germen Admin">
        <meta name="application-name" content="Germen Admin">
        <meta name="msapplication-TileColor" content="#FFFFFF">
        <meta name="msapplication-config" content="/admin/browserconfig.xml">

        <title><?php $APPLICATION->ShowTitle() ?></title>

        <link rel="manifest" href="/admin/manifest.json">
        <link rel="apple-touch-icon" type="image/png" sizes="180x180" href="<?=SITE_TEMPLATE_PATH?>/img/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="<?=SITE_TEMPLATE_PATH?>/img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?=SITE_TEMPLATE_PATH?>/img/favicon-16x16.png">

        <?php $APPLICATION->ShowHead(); ?>

        <link rel="preload" href="<?=SITE_TEMPLATE_PATH?>/fonts/SFProDisplay-Regular.woff2" as="font" type="font/woff2" crossorigin>
        <link rel="preload" href="<?=SITE_TEMPLATE_PATH?>/fonts/SFProDisplay-Bold.woff2" as="font" type="font/woff2" crossorigin>
        <link rel="preload" href="<?=SITE_TEMPLATE_PATH?>/fonts/SFProDisplay-Medium.woff2" as="font" type="font/woff2" crossorigin>
        <link rel="preload" href="<?=SITE_TEMPLATE_PATH?>/fonts/SFProDisplay-Semibold.woff2" as="font" type="font/woff2" crossorigin>

        <?php
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/style.min.css');
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/dev.css');
        Asset::getInstance()->addString(
            '<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">'
        );

        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/jquery-3.6.0.min.js');
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/jquery-ui.min.js');
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/jquery.validate.min.js');
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/jsrender.min.js');
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/dev.js');
        ?>

        <?php $APPLICATION->IncludeComponent(
            'bitrix:main.include',
            '',
            array(
                'AREA_FILE_SHOW' => 'file',
                'AREA_FILE_SUFFIX' => '',
                'EDIT_TEMPLATE' => '',
                'PATH' => SITE_TEMPLATE_PATH.'/include/head/counters.php',
            )
        ); ?>
    </head>
    <body>
        <?php $APPLICATION->ShowPanel() ?>

        <?php $APPLICATION->IncludeComponent(
            'bitrix:main.include',
            '',
            array(
                'AREA_FILE_SHOW' => 'file',
                'AREA_FILE_SUFFIX' => '',
                'EDIT_TEMPLATE' => '',
                'PATH' => SITE_TEMPLATE_PATH.'/include/body/counters.php',
            )
        ); ?>

        <main>
            <div class="container">
                <?php if (!$isLoginPage && !$isPasswordPage && !$isRegistrationPage && !$isProfilePage) : ?>
                    <div class="header__logo">
                        <svg width="102" height="24" aria-hidden="true">
                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite.svg#logo"></use>
                        </svg>
                    </div>
                    <div class="header">
                        <?php $APPLICATION->IncludeComponent(
                            'bitrix:menu',
                            'header',
                            array(
                                'ALLOW_MULTI_SELECT' => 'N',
                                'CHILD_MENU_TYPE' => '',
                                'DELAY' => 'N',
                                'MAX_LEVEL' => 1,
                                'MENU_CACHE_GET_VARS' => array(),
                                'MENU_CACHE_TIME' => 3600,
                                'MENU_CACHE_TYPE' => 'N',
                                'MENU_CACHE_USE_GROUPS' => 'Y',
                                'ROOT_MENU_TYPE' => 'admin',
                                'USE_EXT' => 'N',
                            )
                        ); ?>
                        <div class="header__account">
                            <span><?=$userData['letter']?></span>
                            <div class="header__dropdown">
                                <div class="header__dropdown-name"><?=$userData['name']?> <?=$userData['surname']?></div>
                                <div class="header__dropdown-email"><?=$userData['email']?></div>
                                <a class="header__dropdown-password" href="/admin/profile/">Изменить пароль</a>
                                <a class="header__dropdown-logout" href="/admin/logout/">Выход</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

