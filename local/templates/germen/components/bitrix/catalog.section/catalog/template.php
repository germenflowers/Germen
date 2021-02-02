<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<?php if (!empty($arResult['ITEMS']) || !empty($arResult['BANNER'])): ?>
    <div class="promo-catalog promo-catalog--main">
        <div class="promo-catalog__header">
            <div class="head-h2 promo-catalog__title"><?=$arParams['BLOCK_TITLE']?></div>
            <?php if ($arParams['SHOW_SECTION_DESC']): ?>
                <div class="promo-catalog__desc promo-section-desc"><?=$arResult['DESCRIPTION']?></div>
            <?php endif; ?>
        </div>

        <?php if (!empty($arResult['BANNER'])): ?>
            <div class="promo-catalog__banner" style="margin-bottom: 30px;">
                <a href="#" class="js-detail" data-id="<?=$arResult['BANNER']['id']?>">
                    <picture>
                        <source srcset="<?=$arResult['BANNER']['image']['src']?>" media="(max-width: 768px)">
                        <img src="<?=$arResult['BANNER']['image']['src']?>" alt="<?=$arResult['BANNER']['name']?>">
                    </picture>
                </a>
                <div class="promo-catalog__banner-block">
                    <h3>
                        <a href="#" class="js-detail" data-id="<?=$arResult['BANNER']['id']?>">
                            <?=$arResult['BANNER']['title']?>
                        </a>
                    </h3>
                    <p><?=$arResult['BANNER']['text']?></p>
                    <span class="promo-catalog__banner-price">
                        <?=number_format((int)$arResult['BANNER']['prices']['PRICES'][0], 0, '', ' ')?>
                        <span class="rouble"></span>
                    </span>
                    <?php
                    $style = 'background-color: #'.$arResult['BANNER']['buttonParams']['background'].';';
                    if (!empty($arResult['BANNER']['buttonParams']['textColor'])) {
                        $style .= ' color: #'.$arResult['BANNER']['buttonParams']['textColor'].';';
                    }
                    ?>
                    <a href="/order/?id=<?=$arResult['BANNER']['id']?>" class="promo-item__delivery" style="<?=$style?>">
                        <div class="promo-item__delivery__text"><?=$arResult['BANNER']['buttonParams']['text']?></div>
                        <div class="promo-item__delivery__time">
                            <?php if ($arResult['BANNER']['buttonParams']['showIcon']): ?>
                                <svg class="promo-item__delivery__icon">
                                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=<?=VERSION_SPRITE__ICONS?>#properties-car-mini"></use>
                                </svg>
                            <?php endif; ?>
                            <div class="promo-item__delivery__time__text js-prod_time">
                                <?=$arResult['BANNER']['buttonParams']['time']?>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <div class="promo-catalog__block__wrapper">
            <?php if ($arResult['CODE'] === 'novyy-god-2019'): ?>
                <!-- Выводим на главной в блоке Самое популярное -->
                <div class="promo-catalog__block promo-catalog__block--wide js-detail" data-id="221">
                    <div class="promo-main-product">
                        <div class="promo-main-product__image promo-main-product__image--free-delivery">
                            <img
                                    src="<?=SITE_TEMPLATE_PATH?>/img/main-product/xtree.png"
                                    srcset="<?=SITE_TEMPLATE_PATH?>/img/main-product/xtree.png 1x, <?=SITE_TEMPLATE_PATH?>/img/main-product/xtree@2x.png 2x"
                                    alt=""
                            >
                        </div>
                        <div class="promo-main-product__content">
                            <div class="promo-main-product__title">Пихта Нордмана</div>
                            <div class="promo-main-product__text">
                                Прямиком из лесного питомника — пушистая датская ель, ростом примерно 2,4м.
                            </div>
                            <div class="promo-main-product__price">
                                4 990
                                <span class="rouble"></span>
                            </div>
                            <div class="promo-main-product__order">
                                <a href="/order/?id=221"
                                   class="promo-item__delivery promo-main-product__order-button js-order_link">
                                    <div class="promo-item__delivery__text">Заказать</div>
                                    <div class="promo-item__delivery__time">
                                        <svg class="promo-item__delivery__icon">
                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg#properties-car-mini"></use>
                                        </svg>
                                        <div class="promo-item__delivery__time__text">60 мин.</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php foreach ($arResult['ITEMS'] as $item): ?>
                <?php
                $this->AddEditAction(
                    $item['ID'],
                    $item['EDIT_LINK'],
                    CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT')
                );
                $this->AddDeleteAction(
                    $item['ID'],
                    $item['DELETE_LINK'],
                    CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE'),
                    array('CONFIRM' => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM'))
                );
                ?>
                <div class="promo-catalog__block" id="<?=$this->GetEditAreaId($item['ID'])?>">
                    <div class="promo-item">
                        <div class="promo-item__add-to-fav">
                            <button class="promo-item__add-to-fav-btn red-heart"></button>
                        </div>

                        <div class="promo-item__img js-detail">
                            <a href="#" class="js-detail" data-id="<?=$item['ID']?>">
                                <img src="<?=$item['PICTURE']?>" alt="<?=$item['NAME']?>">
                            </a>
                        </div>

                        <div class="promo-item__description">
                            <a href="#" class="promo-item__title js-detail" data-id="<?=$item['ID']?>">
                                <?=$item['NAME']?>
                            </a>

                            <div class="promo-item__price">
                                <?=number_format($item['ITEM_PRICES'][0]['PRICE'], 0, '', ' ')?>
                                <span class="rouble"></span>
                            </div>

                            <?php
                            $style = 'background-color: #'.$item['BUTTON_PARAMS']['background'].';';
                            if (!empty($item['BUTTON_PARAMS']['textColor'])) {
                                $style .= ' color: #'.$item['BUTTON_PARAMS']['textColor'].';';
                            }
                            ?>
                            <a href="/order/?id=<?=$item['ID']?>" class="promo-item__delivery" style="<?=$style?>">
                                <div class="promo-item__delivery__text"><?=$item['BUTTON_PARAMS']['text']?></div>
                                <div class="promo-item__delivery__time">
                                    <?php if ($item['BUTTON_PARAMS']['showIcon']): ?>
                                        <svg class="promo-item__delivery__icon">
                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=<?=VERSION_SPRITE__ICONS?>#properties-car-mini"></use>
                                        </svg>
                                    <?php endif; ?>
                                    <div class="promo-item__delivery__time__text js-prod_time">
                                        <?=$item['BUTTON_PARAMS']['time']?>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?=$arResult['NAV_STRING']?>
    </div>
<?php endif; ?>