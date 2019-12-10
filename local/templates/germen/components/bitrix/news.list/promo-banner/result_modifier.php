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
$arResult["YELL_RATING_PERCENT"] = $arResult["YELL_RATING"] * 100 / 5;

$titles = ['отзыв', 'отзыва', 'отзывов'];
$cases = [2, 0, 1, 1, 1, 2];

$declensionReviews = $titles[($arResult["YELL_REVIEWS_CNT"] % 100 > 4 && $arResult["YELL_REVIEWS_CNT"] % 100 < 20) ?
    2 : $cases[min($arResult["YELL_REVIEWS_CNT"] % 10, 5)]];

$arResult["DECLENSION_REVIEWS"] = "{$arResult["YELL_REVIEWS_CNT"]} {$declensionReviews}";