<?php

/**
 * @var array $arCurrentValues
 */

use Bitrix\Main\Loader;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!Loader::includeModule('iblock')) {
    return;
}

$arComponentParameters = array(
    'GROUPS' => array(
        'SETTINGS' => array(
            'NAME' => 'Настройки',
            'SORT' => 100,
        ),
    ),
    'PARAMETERS' => array(),
);

CIBlockParameters::Add404Settings($arComponentParameters, $arCurrentValues);
