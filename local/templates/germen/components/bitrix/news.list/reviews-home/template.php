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

<?if ( !empty($arResult["ITEMS"]) ):?>
    <div class="promo-review promo-review--main">
        <div class="head-h2 promo-review__title">Отзывы</div>

        <div class="slider slider-review slider-review--main">
        <?foreach($arResult["ITEMS"] as $arItem):?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <div id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                <div class="item-review">
                    <div class="item-review__rating">
                        <div class="star-ratings-css">
                            <div class="star-ratings-css-top" style="width: 100%">
                                <?for( $i=0; $i<$arItem['PROPERTIES']['RATING']['VALUE']; $i++){?>
                                    <span class="star-ratings-css__star">★</span>
                                <? } ?>
                            </div>

                            <div class="star-ratings-css-bottom">
                                <?for( $i=0; $i<$arItem['PROPERTIES']['RATING']['VALUE']; $i++){?>
                                    <span class="star-ratings-css__star">★</span>
                                <? } ?>
                            </div>
                        </div>
                    </div>

                    <div class="item-review__description">
                        <p><?=$arItem['DETAIL_TEXT']?></p>
                    </div>

                    <div class="item-review__profile">
                        <?if (!empty($arItem['PREVIEW_PICTURE']) ){?>
                            <div class="item-review__profile__img">
                                <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>">
                            </div>
                        <? } ?>

                        <div class="item-review__profile__name">
                            <p>
                                <?=$arItem['NAME']?>
                                <?if (!empty($arItem['PROPERTIES']['LINK_FB']['VALUE']) ){?>
                                    <a href="<?=$arItem['PROPERTIES']['LINK_FB']['VALUE']?>" target="_blank">
                                        <svg class="item-review__profile__social">
                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg#fb"></use>
                                        </svg>
                                    </a>
                                <? }elseif (!empty($arItem['PROPERTIES']['LINK_YELL']['VALUE']) ){?>
                                    <a href="<?=$arItem['PROPERTIES']['LINK_YELL']['VALUE']?>" target="_blank">
                                        <svg class="item-review__profile__social">
                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg#yell"></use>
                                        </svg>
                                    </a>
                                <? } ?>
                            </p>
                            <?if (!empty($arItem['PREVIEW_TEXT']) ){?>
                                <p><?=$arItem['PREVIEW_TEXT']?></p>
                            <? } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?endforeach;?>
        </div>
    </div>
<?endif;?>