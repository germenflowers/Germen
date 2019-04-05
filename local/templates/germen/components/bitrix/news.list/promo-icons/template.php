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

<?if (!empty($arResult["ITEMS"])):?>
    <div class="promo-main__icons">
        <div class="content__container">
            <div class="promo-properties promo-properties--promo">
            <?foreach($arResult["ITEMS"] as $arItem):?>
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <div class="promo-properties__block" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                    <div class="promo-properties__img">
                        <div class="promo-properties__img__wrapper">
                            <svg class="<?if($arItem['CODE']=='properties-car'){?>promo-properties__icon promo-properties__icon--car <?}else{?>promo-properties__icon promo-properties__icon--flowers<?}?>">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=1.1#<?=$arItem['CODE']?>"></use>
                            </svg>
                        </div>
                    </div>

                    <p class="promo-properties__text"><?=$arItem['NAME']?></p>
                </div>
            <?endforeach;?>
            </div>
        </div>
    </div>
<?endif;?>