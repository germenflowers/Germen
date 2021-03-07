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

$hasUpsale = false;
$hasBookmate = false;

$goodsPrice = 0;
$oldGoodsPrice = 0;
$upsalePrice = 0;
$oldUpsalePrice = 0;
foreach ($items as $item) {
    if ($item['upsale']) {
        $hasUpsale = true;

        $upsalePrice += $item['sum'];
        $oldUpsalePrice += $item['oldSum'];
    } else {
        $goodsPrice += $item['sum'];
        $oldGoodsPrice += $item['oldSum'];
    }

    if ($item['bookmate']) {
        $hasBookmate = true;
    }
}

$oldSum = (int)$arResult['allSum'] + (int)$arResult['DISCOUNT_PRICE_ALL'];

$sumFormat = number_format((int)$arResult['allSum'], 0, '', ' ');
$oldSumFormat = number_format($oldSum, 0, '', ' ');
$discountFormat = number_format((int)$arResult['DISCOUNT_PRICE_ALL'], 0, '', ' ');
$goodsPriceFormat = number_format((int)$goodsPrice, 0, '', ' ');
$oldGoodsPriceFormat = number_format((int)$oldGoodsPrice, 0, '', ' ');
$upsalePriceFormat = number_format((int)$upsalePrice, 0, '', ' ');
$oldUpsalePriceFormat = number_format((int)$oldUpsalePrice, 0, '', ' ');

Tools::setStorage(
    'cartData',
    array(
        'items' => $items,
        'upsaleItems' => $arResult['UPSALE_PRODUCTS'],
        'count' => $count,
        'sum' => (int)$arResult['allSum'],
        'sumFormat' => $sumFormat,
        'oldSum' => $oldSum,
        'oldSumFormat' => $oldSumFormat,
        'discount' => (int)$arResult['DISCOUNT_PRICE_ALL'],
        'discountFormat' => $discountFormat,
        'goodsPrice' => $goodsPrice,
        'goodsPriceFormat' => $goodsPriceFormat,
        'oldGoodsPrice' => $oldGoodsPrice,
        'oldGoodsPriceFormat' => $oldGoodsPriceFormat,
        'upsalePrice' => $upsalePrice,
        'upsalePriceFormat' => $upsalePriceFormat,
        'oldUpsalePrice' => $oldUpsalePrice,
        'oldUpsalePriceFormat' => $oldUpsalePriceFormat,
        'coupon' => $arResult['coupon'],
        'hasUpsale' => $hasUpsale,
        'hasBookmate' => $hasBookmate,
        'activeEndTime' => $arResult['activeEndTime'],
        'countDateDelivery' => $arResult['countDateDelivery'],
    )
);
