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

$sumFormat = number_format($arResult['allSum'], 0, '', ' ');
$discountFormat = number_format($arResult['DISCOUNT_PRICE_ALL'], 0, '', ' ');
?>
<div class="cart js-cart">
    <?php if (empty($arResult['ITEMS'])): ?>
        <div class="empty">
            <div class="empty__block">
                <div class="empty__title head-h2">Корзина</div>
                <div class="empty__text">
                    Здесь пока пусто. Перейдите в каталог, чтобы добавить нужные товары.
                </div>
                <div class="empty__links">
                    <a class="empty__link" href="/">Перейти в каталог</a>
                    <a class="empty__link" href="/">Написать в чат</a>
                </div>
                <div class="empty__text empty__text--grey">
                    Не можете выбрать? Напишите нам в удобный мессенджер&nbsp;—&nbsp;поможем.
                </div>
            </div>
        </div>
    <?php else: ?>
        <h1 class="cart__title">Корзина</h1>

        <?php if (!empty($arResult['WARNING_MESSAGE']) && is_array($arResult['WARNING_MESSAGE'])): ?>
            <div id="warning_message">
                <?php foreach ($arResult['WARNING_MESSAGE'] as $v): ?>
                    <?php
                    ShowError($v);
                    ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($arResult['ERROR_MESSAGE'])): ?>
            <div class="cart__list js-cart-list">
                <?php foreach ($arResult['ITEMS'] as $item): ?>
                    <div class="cart__list-item cart-item">
                        <div class="cart-item__image">
                            <img src="<?=$item['image']?>" alt="" width="64" height="64">
                        </div>
                        <div class="cart-item__title">
                            <?=$item['name']?>
                            <?php if (!empty($item['cover'])): ?>
                                <span class="cart-item__title--extra">(Упаковка: <?=$item['cover']?>)</span>
                            <?php endif; ?>
                            <?php if (!empty($item['subscribeParams'])): ?>
                                <span class="cart-item__title--extra">(<?=$item['subscribeParams']['type']?>, <?=$item['subscribeParams']['delivery']?>)</span>
                            <?php endif; ?>
                        </div>
                        <?php if (empty($item['subscribeParams'])): ?>
                            <div class="cart-item__ammount">
                                <div class="quantity-control">
                                    <button
                                            class="moins js-cart-item-minus"
                                            type="button"
                                            data-id="<?=$item['id']?>"
                                            data-productid="<?=$item['productId']?>"
                                    ></button>
                                    <input
                                            class="count quantity-control__input js-quantity-field"
                                            name="quantity"
                                            value="<?=$item['quantity']?>"
                                            data-id="<?=$item['id']?>"
                                            data-productid="<?=$item['productId']?>"
                                        <?=$item['upsale'] ? 'data-max="5"' : ''?>
                                    >
                                    <button
                                            class="plus js-cart-item-plus"
                                            type="button"
                                            data-id="<?=$item['id']?>"
                                            data-productid="<?=$item['productId']?>"
                                    ></button>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="cart-item__price">
                            <?=$item['sumFormat']?>
                            <span>₽</span>
                        </div>
                        <div class="cart-item__delete">
                            <button
                                    class="js-cart-item-delete"
                                    type="button"
                                    data-id="<?=$item['id']?>"
                                    data-productid="<?=$item['productId']?>"
                            >
                                <svg width="16" height="16" aria-hidden="true">
                                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#trash-2"></use>
                                </svg>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart__add">
                <h2 class="cart__add-title">Добавить к заказу</h2>
                <div class="cart__add-slider swiper-container product-add-slider">
                    <div class="swiper-wrapper">
                        <?php foreach ($arResult['UPSALE_PRODUCTS'] as $item): ?>
                            <?php
                            $upsalePriceFormat = number_format($item['price'], 0, '', ' ');

                            $class = 'product-add-slider__btn js-upsale-add-to-cart';
                            if ($item['inCart']) {
                                $class .= ' product-add-slider__btn--is-chosen';
                            }
                            ?>
                            <div class="swiper-slide product-add-slider__item">
                                <img src="<?=$item['image']?>" alt="">
                                <div class="product-add-slider__header">
                                    <h3 class="product-add-slider__title"><?=$item['name']?> +<?=$upsalePriceFormat?>
                                        ₽
                                    </h3>
                                    <p class="product-add-slider__info"></p>
                                    <?=$item['text']?>
                                    <button class="<?=$class?>" data-id="<?=$item['id']?>" data-productid="<?=$item['id']?>">
                                        <svg width="20" height="20" aria-hidden="true">
                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#round-tick"></use>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="cart__bottom">
                <div class="cart__promo">
                    <p class="cart__promo-used">Промокод</p>
                    <form class="cart__promo-form js-promocode-form" name="promocodeForm" action="" method="post">
                        <?php
                        $class = '';
                        if (!($arResult['coupon']['status'] === 2 || $arResult['coupon']['status'] === 4)) {
                            $class .= 'cart__promo-alert';
                        }
                        ?>
                        <div class="js-promocode-container">
                            <?php if (empty($arResult['coupon'])): ?>
                                <input type="text" name="coupon" value="" placeholder="Введите промокод">
                            <?php else: ?>
                                <div class="cart__promo-promocode"><?=$arResult['coupon']['coupon']?></div>
                            <?php endif; ?>
                        </div>
                        <div class="js-promocode-button-container">
                            <button
                                    class="cart__promo-btn <?=empty($arResult['coupon']) ? '' : 'js-promocode-delete'?>"
                                    type="submit"
                            >
                                <?=empty($arResult['coupon']) ? 'Применить' : 'Удалить'?>
                            </button>
                        </div>
                        <div class="js-promocode-status cart__promo-status <?=$class?>">
                            <?php if (!empty($arResult['coupon'])): ?>
                                <?php if (!($arResult['coupon']['status'] === 2 || $arResult['coupon']['status'] === 4)): ?>
                                    <svg width="18" height="18" aria-hidden="true">
                                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#alert"></use>
                                    </svg>
                                <?php endif; ?>
                                <span><?=$arResult['coupon']['statusText']?></span>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <div class="cart__sum js-cart-sum">
                    <div class="cart__total">
                        Сумма заказа:
                        <b><?=$sumFormat?></b>
                        ₽
                    </div>
                    <?php if (!empty($arResult['DISCOUNT_PRICE_ALL'])): ?>
                        <div class="cart__discount">
                            Промокод:
                            <p>-<?=$discountFormat?></p>
                            ₽
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="cart__order">
                <a class="cart__order-btn" href="/order/">Оформить заказ</a>
            </div>
        <?php else: ?>
            <?php
            ShowError($arResult['ERROR_MESSAGE']);
            ?>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script id="cartEmptyTmpl" type="text/x-jsrender">
    <div class="empty">
        <div class="empty__block">
            <div class="empty__title head-h2">Корзина</div>
            <div class="empty__text">
                Здесь пока пусто. Перейдите в каталог, чтобы добавить нужные товары.
            </div>
            <div class="empty__links">
                <a class="empty__link" href="/">Перейти в каталог</a>
                <a class="empty__link" href="/">Написать в чат</a>
            </div>
            <div class="empty__text empty__text--grey">
                Не можете выбрать? Напишите нам в удобный мессенджер&nbsp;—&nbsp;поможем.
            </div>
        </div>
    </div>
</script>

<script id="cartTmpl" type="text/x-jsrender">
    {{for items}}
        <div class="cart__list-item cart-item">
            <div class="cart-item__image">
                <img src="{{:image}}" alt="" width="64" height="64">
            </div>
            <div class="cart-item__title">
                {{:name}}
                {{if cover}}
                    <span class="cart-item__title--extra">(Упаковка: {{:cover}})</span>
                {{/if}}
                {{if subscribe}}
                    <span class="cart-item__title--extra">({{:subscribeParams.type}}, {{:subscribeParams.delivery}})</span>
                {{/if}}
            </div>
            {{if !subscribe}}
                <div class="cart-item__ammount">
                    <div class="quantity-control">
                        <button
                            class="moins js-cart-item-minus"
                            type="button"
                            data-id="{{:id}}"
                            data-productid="{{:productId}}"
                        ></button>
                        <input
                            class="count quantity-control__input js-quantity-field"
                            name="quantity"
                            value="{{:quantity}}"
                            data-id="{{:id}}"
                            data-productid="{{:productId}}"
                            {{if upsale}}data-max="5"{{/if}}
                        >
                        <button
                            class="plus js-cart-item-plus""
                            type="button"
                            data-id="{{:id}}"
                            data-productid="{{:productId}}"
                        ></button>
                    </div>
                </div>
            {{/if}}
            <div class="cart-item__price">{{:sumFormat}} <span>₽</span></div>
            <div class="cart-item__delete">
                <button class="js-cart-item-delete" type="button" data-id="{{:id}}" data-productid="{{:productId}}">
                    <svg width="16" height="16" aria-hidden="true">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#trash-2"></use>
                    </svg>
                </button>
            </div>
        </div>
    {{/for}}
</script>

<script id="cartSumTmpl" type="text/x-jsrender">
    <div class="cart__total">
        Сумма заказа: <b>{{:sumFormat}}</b> ₽
    </div>
    {{if discount}}
        <div class="cart__discount">
            Промокод: <p>-{{:discountFormat}}</p> ₽
        </div>
    {{/if}}
</script>

<script id="promocodeButtonLoadingTmpl" type="text/x-jsrender">
    <button class="cart__promo-btn cart__promo-btn--loading" type="submit">
        <svg width="18" height="18" aria-hidden="true">
            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#loading"></use>
        </svg>
    </button>
</script>

<script id="promocodeButtonAddTmpl" type="text/x-jsrender">
    <button class="cart__promo-btn" type="submit">Применить</button>
</script>

<script id="promocodeButtonDeleteTmpl" type="text/x-jsrender">
    <button class="cart__promo-btn js-promocode-delete" type="submit">Удалить</button>
</script>

<script id="promocodeAddTmpl" type="text/x-jsrender">
    <div class="cart__promo-promocode">{{:coupon}}</div>
</script>

<script id="promocodeDeleteTmpl" type="text/x-jsrender">
    <input type="text" name="coupon" value="" placeholder="Введите промокод">
</script>

<script id="promocodeStatusTmpl" type="text/x-jsrender">
    {{if !(status == 2 || status == 4)}}
        <svg width="18" height="18" aria-hidden="true">
            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#alert"></use>
        </svg>
    {{/if}}
    <span>{{:statusText}}</span>
</script>