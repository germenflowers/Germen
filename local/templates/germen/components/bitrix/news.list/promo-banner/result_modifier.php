<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult["YELL_RATING"] = (float)$arParams["YELL_RATING"];
$arResult["YELL_REVIEWS_CNT"] = (int)$arParams["YELL_REVIEWS_CNT"];
if ($arResult["YELL_RATING"] < 0) {
    $arResult["YELL_RATING"] = 0;
}
$arResult["YELL_RATING"] = number_format($arResult["YELL_RATING"], 1, '.', '');