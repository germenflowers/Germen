<?php

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

global $APPLICATION;
$APPLICATION->RestartBuffer();
echo '<pre>'.print_r($arResult, true).'</pre>';
die();
