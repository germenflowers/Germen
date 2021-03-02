<?php

/**
 * @global CMain $USER
 * @var array $arResult
 * @var array $arParams
 */

use \PDV\Tools;
use \Germen\BuyButton;
use \Germen\Content;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult['DELIVERY_TIME'] = Tools::getTimeByDelivery();
$arResult['IS_ORDER'] = $arParams['IS_ORDER'];

$arResult['IMAGES'] = array();
if (!empty($arResult['PREVIEW_PICTURE'])) {
    $arResult['IMAGES'][] = $arResult['PREVIEW_PICTURE'];
}

if (!empty($arResult['DISPLAY_PROPERTIES']['IMAGES'])) {
    if (count($arResult['DISPLAY_PROPERTIES']['IMAGES']['VALUE']) > 1) {
        foreach ($arResult['DISPLAY_PROPERTIES']['IMAGES']['FILE_VALUE'] as $image) {
            $arResult['IMAGES'][] = $image;
        }
    } else {
        $arResult['IMAGES'][] = $arResult['DISPLAY_PROPERTIES']['IMAGES']['FILE_VALUE'];
    }
}

$buyButton = new BuyButton();
$defaultButton = $buyButton->getDefaultParams();
$buyButtons = $buyButton->getList();

$arResult['BUTTON_PARAMS'] = $defaultButton;
if (
    !empty($arResult['PROPERTIES']['BUTTON']['VALUE']) &&
    !empty($buyButtons[$arResult['PROPERTIES']['BUTTON']['VALUE']])
) {
    $arResult['BUTTON_PARAMS'] = $buyButtons[$arResult['PROPERTIES']['BUTTON']['VALUE']];
}

$products = Content::getUpsaleBookmateProducts();

$arResult['UPSALE_PRODUCTS'] = $products['upsaleProducts'];
$arResult['BOOKMATE_PRODUCTS'] = $products['bookmateProducts'];
