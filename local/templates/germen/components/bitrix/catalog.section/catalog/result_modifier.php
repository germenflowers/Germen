<?php

foreach ($arResult["ITEMS"] as $key => $arElement) {
    $arResult["ITEMS"][$key]['PICTURE'] = '';

    $renderImage = CFile::ResizeImageGet($arElement['PREVIEW_PICTURE'], Array("width" => 600, "height" => 600));
    $arResult["ITEMS"][$key]['PICTURE'] = $renderImage['src'];
    unset($renderImage);
}

$arParams["SHOW_SECTION_DESC"] = $arParams["SHOW_SECTION_DESC"] === "Y" && !empty($arResult["DESCRIPTION"]);

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
