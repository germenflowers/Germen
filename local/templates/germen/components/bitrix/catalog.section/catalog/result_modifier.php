<?php

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Germen\BuyButton;
use Germen\Content;

foreach ($arResult['ITEMS'] as $key => $arElement) {
    $arResult['ITEMS'][$key]['PICTURE'] = '';

    $renderImage = CFile::ResizeImageGet($arElement['PREVIEW_PICTURE'], array('width' => 600, 'height' => 600));
    $arResult['ITEMS'][$key]['PICTURE'] = $renderImage['src'];
    unset($renderImage);
}

$arParams['SHOW_SECTION_DESC'] = $arParams['SHOW_SECTION_DESC'] === 'Y' && !empty($arResult['DESCRIPTION']);

$buyButton = new BuyButton();
$defaultButton = $buyButton->getDefaultParams();
$buyButtons = $buyButton->getList();

foreach ($arResult['ITEMS'] as $i => $item) {
    $item['BUTTON_PARAMS'] = $defaultButton;

    if (!empty($item['PROPERTIES']['BUTTON']['VALUE']) && !empty($buyButtons[$item['PROPERTIES']['BUTTON']['VALUE']])) {
        $item['BUTTON_PARAMS'] = $buyButtons[$item['PROPERTIES']['BUTTON']['VALUE']];
    }

    $arResult['ITEMS'][$i] = $item;
}

$arResult['BANNER'] = array();
if ($arParams['POPULAR'] === 'Y') {
    $arResult['BANNER'] = Content::getBannerCached(
        array(
            'defaultButton' => $defaultButton,
            'buyButtons' => $buyButtons,
            'priceCode' => $arResult['ORIGINAL_PARAMETERS']['PRICE_CODE'],
            'iblockId' => (int)$arParams['IBLOCK_ID'],
            'sectionId' => (int)$arParams['SECTION_ID'],
            'popular' => true,
        )
    );
} elseif (!empty((int)$arParams['SECTION_ID'])) {
    $arResult['BANNER'] = Content::getBannerCached(
        array(
            'defaultButton' => $defaultButton,
            'buyButtons' => $buyButtons,
            'priceCode' => $arResult['ORIGINAL_PARAMETERS']['PRICE_CODE'],
            'iblockId' => (int)$arParams['IBLOCK_ID'],
            'sectionId' => (int)$arParams['SECTION_ID'],
            'popular' => false,
        )
    );
}
