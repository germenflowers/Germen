<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$buyButton = new Germen\buybutton();
$defaultButton = $buyButton->getDefaultParams();
$buyButtons = $buyButton->getList();

foreach ($arResult['ITEMS'] as $i => $item) {
    $item['BUTTON_PARAMS'] = $defaultButton;

    if (!empty($item['PROPERTIES']['BUTTON']['VALUE']) && !empty($buyButtons[$item['PROPERTIES']['BUTTON']['VALUE']])) {
        $item['BUTTON_PARAMS'] = $buyButtons[$item['PROPERTIES']['BUTTON']['VALUE']];
    }

    $arResult['ITEMS'][$i] = $item;
}
