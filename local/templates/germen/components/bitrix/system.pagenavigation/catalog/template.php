<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");

$count = $arResult['NavRecordCount'] - $arResult['NavPageNomer']*$arResult['NavPageSize'];
if ( $count > $arResult['NavPageSize'] )
    $count = $arResult['NavPageSize'];
?>

<div class="promo-catalog__more" data-pagen="<?=$arResult["NavNum"]?>">
    <?if($arResult["bDescPageNumbering"] === true):?>

        <?if ($arResult["NavPageNomer"] > 1):?>
            <button class="btn btn__promo btn__loading js-pagin" data-href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>">
                <span class="btn__loading__text">
                    + еще <?=\PDV\Tools::Declension($count, array('букет','букета','букетов'))?>
                    <svg class="btn__loading__icon">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg#loading"></use>
                    </svg>
                </span>
            </button>
        <?endif?>

    <?else:?>

        <?if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
            <button class="btn btn__promo btn__loading js-pagin" data-href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>">
                <span class="btn__loading__text">
                    + еще <?=\PDV\Tools::Declension($count, array('букет','букета','букетов'))?>
                    <svg class="btn__loading__icon">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg#loading"></use>
                    </svg>
                </span>
            </button>
        <?endif?>

    <?endif?>
</div>