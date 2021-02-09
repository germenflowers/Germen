<?php

/**
 * @global CMain $USER
 * @var array $arResult
 * @var array $arParams
 */

use \PDV\Tools;
use \Germen\BuyButton;

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

$arResult['UPSALE_PRODUCTS'] = array();
$arResult['BOOKMATE_PRODUCTS'] = array();

$order = array('SORT' => 'ASC');
$filter = array('IBLOCK_ID' => IBLOCK_ID__UPSALE, 'ACTIVE' => 'Y');
$select = array(
    'IBLOCK_ID',
    'ID',
    'NAME',
    'PREVIEW_TEXT',
    'PREVIEW_PICTURE',
    'PROPERTY_IS_BOOKMATE',
);
$result = \CIBlockElement::GetList($order, $filter, false, false, $select);
while ($row = $result->fetch()) {
    $price = \CCatalogProduct::GetOptimalPrice($row['ID'], 1, $USER->GetUserGroupArray());

    $image['src'] = array();
    if (!empty($row['PREVIEW_PICTURE'])) {
        $image = CFile::ResizeImageGet(
            $row['PREVIEW_PICTURE'],
            array('width' => 174, 'height' => 174),
            BX_RESIZE_IMAGE_PROPORTIONAL
        );
    }

    $item = array(
        'id' => (int)$row['ID'],
        'name' => $row['NAME'],
        'text' => $row['PREVIEW_TEXT'],
        'price' => (int)$price['DISCOUNT_PRICE'],
        'image' => $image['src'],
    );

    if ($row['PROPERTY_IS_BOOKMATE_VALUE'] === 'Y') {
        $arResult['BOOKMATE_PRODUCTS'][] = $item;
    } else {
        $arResult['UPSALE_PRODUCTS'][] = $item;
    }
}
