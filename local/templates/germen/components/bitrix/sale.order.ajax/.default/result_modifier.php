<?php

/**
 * @var array $arParams
 * @var array $arResult
 */

use Germen\Content;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult['BASKET_ITEMS'] = Content::getCartItemsData($arResult['BASKET_ITEMS'], array('width' => 134, 'height' => 124));

$products = Content::getUpsaleBookmateProducts();

$arResult['UPSALE_PRODUCTS'] = $products['upsaleProducts'];
$arResult['BOOKMATE_PRODUCTS'] = $products['bookmateProducts'];
