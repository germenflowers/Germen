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
    'PARAMETERS' => array(
        'SEF_MODE' => array(
            'list' => array(
                'NAME' => 'Список',
                'DEFAULT' => '',
                'VARIABLES' => array(),
            ),
            'element' => array(
                'NAME' => 'Элемент',
                'DEFAULT' => '#ID#/',
                'VARIABLES' => array(),
            ),
        ),
        'CACHE_TIME' => array('DEFAULT' => 3600),
        'CACHE_FILTER' => array(
            'PARENT' => 'CACHE_SETTINGS',
            'NAME' => 'Кешировать при установленном фильтре',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
        ),
        'CACHE_GROUPS' => array(
            'PARENT' => 'CACHE_SETTINGS',
            'NAME' => 'Учитывать права доступа',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),
    ),
);

CIBlockParameters::Add404Settings($arComponentParameters, $arCurrentValues);
