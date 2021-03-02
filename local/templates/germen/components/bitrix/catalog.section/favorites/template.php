<?php

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
?>
<?php if (!empty($arResult['ITEMS'])): ?>
    <div class="favorite js-favorite-block">
        <div class="promo-catalog promo-catalog--main">
            <div class="promo-catalog__header">
                <div class="head-h2 promo-catalog__title">Избранное</div>
            </div>
            <div class="promo-catalog__block__wrapper">
                <?php foreach ($arResult['ITEMS'] as $item): ?>
                    <?php
                    $style = 'background-color: #'.$item['BUTTON_PARAMS']['background'].';';
                    if (!empty($item['BUTTON_PARAMS']['textColor'])) {
                        $style .= ' color: #'.$item['BUTTON_PARAMS']['textColor'].';';
                    }
                    ?>
                    <div class="promo-catalog__block">
                        <div class="promo-item">
                            <div class="promo-item__add-to-fav">
                                <button
                                        class="promo-item__add-to-fav-btn red-heart"
                                        data-id="<?=$item['ID']?>"
                                        data-delete="Y"
                                ></button>
                            </div>

                            <div class="promo-item__img">
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

                                <a
                                        href="#"
                                        class="promo-item__delivery js-add-to-cart"
                                        data-id="<?=$item['ID']?>"
                                        style="<?=$style?>"
                                >
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
        </div>
    </div>
<?php endif; ?>