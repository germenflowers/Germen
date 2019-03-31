<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<?if ( !empty($arResult['ITEMS']) ) {?>

<div class="promo-subscribe__money text-center">
    <div class="head-h2"><?=$arParams['BLOCK_TITLE']?></div>
    <p><?=$arParams['BLOCK_DESCRIPTION']?></p>

    <div class="promo-subscribe__month__wrapper">
        <?foreach($arResult["ITEMS"] as $arElement):?>
            <?
            $this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
            ?>
            <div class="promo-subscribe__month__block" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
                <div class="promo-subscribe__month" style="background-color: #<?=$arElement['CODE']?>">
                    <div class="head-h2"><?=$arElement['NAME']?></div>

                    <p><?=$arElement['PREVIEW_TEXT']?></p>

                    <div class="head-h2"><?=number_format($arElement['ITEM_PRICES'][0]['PRICE'],0, '', ' ')?> руб.</div>

                    <p><?=$arElement['DETAIL_TEXT']?></p>

                    <div class="promo-subscribe__month__btn">
                        <a href="/order/?id=<?=$arElement['ID']?>" class="btn btn__main btn__main--white">Заказать</a>
                    </div>
                </div>
            </div>
        <?endforeach;?>
    </div>
</div>
<?}?>