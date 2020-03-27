<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<?if ( !empty($arResult['ITEMS']) ) {?>
    <div class="promo-catalog promo-catalog--main">
        <div class="promo-catalog__header">
            <div class="head-h2 promo-catalog__title"><?= $arParams['BLOCK_TITLE'] ?></div>
            <? if ($arParams['SHOW_SECTION_DESC']): ?>
                <div class="promo-catalog__desc promo-section-desc"><?= $arResult["DESCRIPTION"] ?></div>
            <? endif; ?>
        </div>

        <div class="promo-catalog__block__wrapper">
	        <?if ($arResult["CODE"] === "novyy-god-2019"): ?>
		        <!-- Выводим на главной в блоке Самое популярное -->
		        <div class="promo-catalog__block promo-catalog__block--wide js-detail" data-id="221">
			        <div class="promo-main-product">
				        <div class="promo-main-product__image promo-main-product__image--free-delivery">
					        <img src="<?= SITE_TEMPLATE_PATH ?>/img/main-product/xtree.png"
					             srcset="<?= SITE_TEMPLATE_PATH ?>/img/main-product/xtree.png 1x, <?= SITE_TEMPLATE_PATH ?>/img/main-product/xtree@2x.png 2x"
					             alt="">
				        </div>
				        <div class="promo-main-product__content">
					        <div class="promo-main-product__title">Пихта Нордмана</div>
					        <div class="promo-main-product__text">Прямиком из лесного питомника — пушистая датская ель, ростом примерно 2,4м.</div>
					        <div class="promo-main-product__price">4 990 <span class="rouble"></span></div>
					        <div class="promo-main-product__order">
						        <a href="/order/?id=221"
						           class="promo-item__delivery promo-main-product__order-button js-order_link">
							        <div class="promo-item__delivery__text">Заказать</div>
							        <div class="promo-item__delivery__time">
								        <svg class="promo-item__delivery__icon">
									        <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/icons/icons.svg#properties-car-mini"></use>
								        </svg>
								        <div class="promo-item__delivery__time__text">60 мин.</div>
							        </div>
						        </a>
					        </div>
				        </div>
			        </div>
		        </div>
	        <? endif; ?>

            <?foreach($arResult["ITEMS"] as $arElement):?>
                <?
                $this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
                ?>
                <div class="promo-catalog__block" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
                    <div class="promo-item">
                        <div class="promo-item__img js-detail">
                            <a href="#" class="js-detail" data-id="<?=$arElement['ID']?>">
                                <img src="<?=$arElement['PICTURE']?>" alt="<?=$arElement['NAME']?>">
                            </a>
                        </div>

                        <div class="promo-item__description">
                            <a href="#" class="promo-item__title js-detail" data-id="<?=$arElement['ID']?>"><?=$arElement['NAME']?></a>

                            <div class="promo-item__price"><?=number_format($arElement['ITEM_PRICES'][0]['PRICE'],0, '', ' ')?> <span class="rouble"></span></div>

                            <a
                                href="/order/?id=<?=$arElement['ID']?>"
                                class="promo-item__delivery"
                                style="background-color: #<?=$arElement['BUTTON_PARAMS']['background']?>;"
                            >
                                <div class="promo-item__delivery__text"><?=$arElement['BUTTON_PARAMS']['text']?></div>
                                <div class="promo-item__delivery__time">
                                    <?php if ($arElement['BUTTON_PARAMS']['showIcon']): ?>
                                        <svg class="promo-item__delivery__icon">
                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=<?=VERSION_SPRITE__ICONS?>#properties-car-mini"></use>
                                        </svg>
                                    <?php endif; ?>
                                    <div class="promo-item__delivery__time__text js-prod_time">
                                        <?=$arElement['BUTTON_PARAMS']['time']?>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            <?endforeach;?>
        </div>

        <?=$arResult["NAV_STRING"]?>
    </div>
<?}?>