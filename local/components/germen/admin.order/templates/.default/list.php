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

if (!empty($arParams['FILTER_NAME'])) {
    global ${$arParams['FILTER_NAME']};
}
?>
<?php $APPLICATION->IncludeComponent(
    'germen:admin.order.list',
    '',
    array(
        'FILTER_NAME' => $arParams['FILTER_NAME'],
        'PAGER_ID' => $arParams['PAGER_ID'],
        'PAGE_ELEMENT_COUNT' => $arParams['PAGE_ELEMENT_COUNT'],
        'SET_STATUS_404' => $arParams['SET_STATUS_404'],
        'SHOW_404' => $arParams['SHOW_404'],
        'FILE_404' => $arParams['FILE_404'],
        'DISPLAY_BOTTOM_PAGER' => $arParams['DISPLAY_BOTTOM_PAGER'],
        'DISPLAY_TOP_PAGER' => $arParams['DISPLAY_TOP_PAGER'],
        'PAGER_BASE_LINK' => $arParams['PAGER_BASE_LINK'],
        'PAGER_BASE_LINK_ENABLE' => $arParams['PAGER_BASE_LINK_ENABLE'],
        'PAGER_DESC_NUMBERING' => $arParams['PAGER_DESC_NUMBERING'],
        'PAGER_DESC_NUMBERING_CACHE_TIME' => $arParams['PAGER_DESC_NUMBERING_CACHE_TIME'],
        'PAGER_PARAMS_NAME' => $arParams['PAGER_PARAMS_NAME'],
        'PAGER_SHOW_ALL' => $arParams['PAGER_SHOW_ALL'],
        'PAGER_SHOW_ALWAYS' => $arParams['PAGER_SHOW_ALWAYS'],
        'PAGER_TEMPLATE' => $arParams['PAGER_TEMPLATE'],
        'PAGER_TITLE' => $arParams['PAGER_TITLE'],
        'CACHE_FILTER' => $arParams['CACHE_FILTER'],
        'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
        'CACHE_TIME' => $arParams['CACHE_TIME'],
        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
    ),
    $component
); ?>