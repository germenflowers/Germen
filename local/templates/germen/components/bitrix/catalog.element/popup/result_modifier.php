<?php
$arResult["DELIVERY_TIME"] = \PDV\Tools::getTimeByDelivery();
$arResult["IS_ORDER"] = $arParams["IS_ORDER"];

$arResult["IMAGES"] = [];

if (!empty($arResult["PREVIEW_PICTURE"])) {
    $arResult["IMAGES"][] = $arResult["PREVIEW_PICTURE"];
}

$propImages = $arResult["DISPLAY_PROPERTIES"]["IMAGES"];

if (!empty($propImages)) {
    if (count($propImages["VALUE"]) > 1) {
        foreach ($propImages["FILE_VALUE"] as $image) {
            $arResult["IMAGES"][] = $image;
        }
    } else {
        $arResult["IMAGES"][] = $propImages["FILE_VALUE"];
    }
}
