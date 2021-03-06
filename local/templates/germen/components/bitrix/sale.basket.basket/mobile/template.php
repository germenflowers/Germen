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

$sumFormat = number_format((int)$arResult['allSum'], 0, '', ' ');
?>
<div class="catalog__fixed-footer">
    <a
        href="<?=$arParams['PATH_TO_ORDER']?>"
        class="js-mobile-cart"
        style="<?=(int)$arResult['allSum'] === 0 ? 'display: none;' : ''?>"
    >
        <div class="catalog__fixed-footer-inner">
            <div class="catalog__fixed-footer-time">
                <svg class="catalog__fixed-footer-icon">
                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#car-delivery"></use>
                </svg>
                <span>60 мин.</span>
            </div>
            <div class="catalog__fixed-footer-text">Корзина</div>
            <div class="catalog__fixed-footer-total">
                <span class="js-mobile-cart-sum"><?=$sumFormat?></span>
                ₽
            </div>
        </div>
    </a>
</div>
