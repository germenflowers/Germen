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

$buyButton = new BuyButton();
$defaultButton = $buyButton->getDefaultParams();
$buyButtons = $buyButton->getList();

foreach ($arResult['ITEMS'] as $i => $item) {
    $image = CFile::ResizeImageGet($item['PREVIEW_PICTURE'], array('width' => 600, 'height' => 600));
    $item['PICTURE'] = $image['src'];

    $item['BUTTON_PARAMS'] = $defaultButton;
    if (!empty($item['PROPERTIES']['BUTTON']['VALUE']) && !empty($buyButtons[$item['PROPERTIES']['BUTTON']['VALUE']])) {
        $item['BUTTON_PARAMS'] = $buyButtons[$item['PROPERTIES']['BUTTON']['VALUE']];
    }

    $arResult['ITEMS'][$i] = $item;
}
