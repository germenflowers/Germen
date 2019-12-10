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
?>

<?foreach($arResult["ITEMS"] as $arItem):?>
    <?
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    ?>
    <div class="promo-main__img" style="background: url(<?=$arItem['PREVIEW_PICTURE']['SRC']?>);"></div>
    <div class="content__container promo-main__content" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
        <?if ( !empty($arItem['PREVIEW_TEXT']) ) {?>
            <div class="head-h1 promo-main__title"><?=$arItem['PREVIEW_TEXT']?></div>
        <? } ?>
        <? if ($APPLICATION->GetCurPage(false) === '/') {?>
            <div class="promo-main__cta">
                <a href="#advantages" class="btn btn__main btn--md btn--base js-scroll-to" title="Как это работает?" data-offset="50">Как это работает?</a>
            </div>
        <? } ?>

        <p>Рейтинг <?= $arResult["YELL_RATING"] ?></p>
        <p>Количество отзывов <?= $arResult["YELL_REVIEWS_CNT"] ?></p>
    </div>
<?endforeach;?>