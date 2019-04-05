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
    <ul class="promo-subscribe__pluse">
        <?foreach($arResult["ITEMS"] as $arItem):?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <li id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                <div class="promo-subscribe__pluse__img">
                    <div class="promo-subscribe__pluse__img__cell">
                        <svg class="promo-subscribe__pluse__icon promo-subscribe__pluse__icon--price ">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=1.2#<?=$arItem['CODE']?>"></use>
                        </svg>
                    </div>
                </div>

                <div class="promo-subscribe__pluse__description">
                    <p><strong><?=$arItem['NAME']?></strong></p>
                    <p><?=$arItem['PREVIEW_TEXT']?></p>
                </div>
            </li>
        <?endforeach;?>
    </ul>
<?endif;?>