<?php

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<?php if (!empty($arResult['ITEMS'])) : ?>
    <div class="promo-subscribe__money text-center">
        <div class="head-h2"><?=$arParams['BLOCK_TITLE']?></div>
        <p><?=$arParams['BLOCK_DESCRIPTION']?></p>
        <div class="promo-subscribe__month__wrapper">
            <?php foreach ($arResult['ITEMS'] as $item): ?>
                <?php
                $price = number_format($item['ITEM_PRICES'][0]['PRICE'], 0, '', ' ');
                ?>
                <div class="promo-subscribe__month__block">
                    <div class="promo-subscribe__month" style="background-color: #<?=$item['CODE']?>">
                        <div class="head-h2"><?=$item['NAME']?></div>
                        <p><?=$item['PREVIEW_TEXT']?></p>
                        <div class="head-h2"><?=$price?> руб.</div>
                        <p><?=$item['DETAIL_TEXT']?></p>
                        <div class="promo-subscribe__month__btn">
                            <a
                                    href="#"
                                    class="btn btn__main btn__main--white js-add-to-cart"
                                    data-id="<?=$item['ID']?>"
                            >Заказать
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>