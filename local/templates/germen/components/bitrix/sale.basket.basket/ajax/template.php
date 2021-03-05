<?php

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

use \PDV\Tools;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$count = 0;
$items = array();
foreach ($arResult['ITEMS'] as $item) {
    $count += $item['quantity'];
    $items[] = $item;
}

$goodsPrice = 0;
$upsalePrice = 0;
foreach ($items as $item) {
    if ($item['upsale']) {
        $upsalePrice += $item['sum'];
    } else {
        $goodsPrice += $item['sum'];
    }
}

$sumFormat = number_format($arResult['allSum'], 0, '', ' ');
$discountFormat = number_format($arResult['DISCOUNT_PRICE_ALL'], 0, '', ' ');
$goodsPriceFormat = number_format($goodsPrice, 0, '', ' ');
$upsalePriceFormat = number_format($upsalePrice, 0, '', ' ');

Tools::setStorage(
    'cartData',
    array(
        'items' => $items,
        'upsaleItems' => $arResult['UPSALE_PRODUCTS'],
        'count' => $count,
        'sum' => (int)$arResult['allSum'],
        'sumFormat' => $sumFormat,
        'discount' => (int)$arResult['DISCOUNT_PRICE_ALL'],
        'discountFormat' => $discountFormat,
        'goodsPrice' => $goodsPrice,
        'goodsPriceFormat' => $goodsPriceFormat,
        'upsalePrice' => $upsalePrice,
        'upsalePriceFormat' => $upsalePriceFormat,
        'coupon' => $arResult['coupon'],
    )
);
