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

        <div class="promo-main__media-rating">
            <div class="media-rating">
                <div class="media-rating__logo">
                    <img src="<?=SITE_TEMPLATE_PATH?>/img/media-rating/yell.png" alt="Yell">
                </div>
                <div class="media-rating__content">
                    <div class="media-rating__rating">
                        <div class="media-rating__rating-value"><?= $arResult["YELL_RATING"] ?></div>
                        <div class="media-rating__rating-stars" aria-hidden="true">
                            <div class="media-rating__rating-stars-filled"
                                 style="width: <?= $arResult["YELL_RATING_PERCENT"]; ?>%">
                            </div>
                        </div>
                    </div>
                    <div class="media-rating__desc">
                        <span class="media-rating__title">
                            <a class="media-rating__link" href="https://www.yell.ru/moscow/com/germen_11913117/" title="Yell" target="_blank">Yell</a>
                        </span>
                        <span class="media-rating__count"><?= $arResult["YELL_REVIEWS_CNT"] ?> отзывов</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?endforeach;?>