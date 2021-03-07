<?php

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

global $APPLICATION;
$APPLICATION->RestartBuffer();

header('Content-Type: application/json');
die(json_encode($arResult['response']));
