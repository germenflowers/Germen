<?php

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
?>
<?php $APPLICATION->IncludeComponent(
    'germen:admin.order.history.element',
    '',
    array(
        'ID' => $arResult['VARIABLES']['ID'],
        'SET_STATUS_404' => $arParams['SET_STATUS_404'],
        'SHOW_404' => $arParams['SHOW_404'],
        'FILE_404' => $arParams['FILE_404'],
        'CACHE_FILTER' => $arParams['CACHE_FILTER'],
        'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
        'CACHE_TIME' => $arParams['CACHE_TIME'],
        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
    ),
    $component
); ?>
