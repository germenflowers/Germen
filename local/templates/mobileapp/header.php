<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;

if (Loader::IncludeModule("mobileapp")) {
    CMobile::Init();
}
?>
<!doctype html>
<html lang="ru">
    <head>
        <meta charset="utf-8">

        <title><?$APPLICATION->ShowTitle()?></title>

        <?$APPLICATION->ShowHead();?>
    </head>
    <body>