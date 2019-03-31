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
    <div class="promo-subscribe__faq">
        <div class="head-h2 text-center"><?=$arParams['BLOCK_TITLE']?></div>
        <ul class="promo-faq">
        <?foreach($arResult["ITEMS"] as $arItem):?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <li id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                <div class="promo-collapse">
                    <div class="promo-collapse__btn">
                        <?=$arItem['NAME']?>
                    </div>
                    <div class="promo-collapse__text">
                        <p><?=$arItem['PREVIEW_TEXT']?></p>
                    </div>
                </div>
            </li>
        <?endforeach;?>
        </ul>
    </div>
<?endif;?>