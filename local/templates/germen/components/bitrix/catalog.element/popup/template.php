<?php

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$label = '';
$labelClass = '';
if(!empty($arResult['PROPERTIES']['LABEL']['VALUE'])) {
    $label = $arResult['PROPERTIES']['LABEL']['VALUE'];
    if($arResult['PROPERTIES']['LABEL']['VALUE_XML_ID'] === 'season') {
        $labelClass = 'product-info__season';
    }
}

$wishlistClass = in_array($arResult['ID'], $arParams['WISHLIST'], true) ? 'red-heart' : '';
$wishlistClassMobile = in_array($arResult['ID'], $arParams['WISHLIST'], true) ? 'white-heart' : '';

$deliveryTime = $arResult['BUTTON_PARAMS']['time'];
if(empty($deliveryTime)) {
    $deliveryTime = $arResult['DELIVERY_TIME'];
}

$priceFormat = number_format($arResult['PRICES']['BASE']['VALUE'], 0, '', ' ');
?>
<div class="product-info__gallery">
    <?php if (!empty($label)): ?>
        <div class="<?=$labelClass?>"><?=$label?></div>
    <?php endif; ?>
    <div class="product-slider swiper-container">
        <div class="swiper-wrapper" aria-label="Close" data-dismiss="modal">
            <?php foreach ($arResult['IMAGES'] as $image): ?>
                <div class="swiper-slide product-slider__item">
                    <img src="<?=$image['SRC']?>" alt="">
                </div>
            <?php endforeach; ?>
        </div>
        <div class="swiper-button-prev product-slider__prev product-slider__btn">
            <svg width="11" height="18" aria-hidden="true">
                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#slider-arrow-prev"></use>
            </svg>
        </div>
        <div class="swiper-button-next product-slider__next product-slider__btn">
            <svg width="11" height="18" aria-hidden="true">
                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#slider-arrow-next"></use>
            </svg>
        </div>
        <div class="swiper-pagination product-slider__pagination"></div>
    </div>
    <button class="product-info__favorite-mob <?=$wishlistClassMobile?>" type="button" data-id="<?=$arResult['ID']?>">
</div>
<div class="product-info__content">
    <div class="product-info__content-inner">
        <div class="product-info__header">
            <h2 class="product-info__title">
                <?=$arResult['NAME']?>
            </h2>
            <div class="product-info__discription">
                <div class="product-info__discription-text">
                    <?=$arResult['PREVIEW_TEXT']?>
                    <div class="product-info__discription-show"></div>
                </div>
            </div>
            <form>
                <?php /*
                <div class="product-info__color">
                    <div class="product-info__color-inner">
                        <input class="visually-hidden" id="red" type="radio" name="color" value="red" checked>
                        <label for="red" style="background-color: #942945; border: 1px solid #942945;"></label>
                        <input class="visually-hidden" id="black" type="radio" name="color" value="black">
                        <label for="black" style="background-color: #3A2F3D; border: 1px solid #3A2F3D;"></label>
                        <input class="visually-hidden" id="green" type="radio" name="color" value="green">
                        <label for="green" style="background-color: #557464; border: 1px solid #557464;"></label>
                    </div>
                </div>
                */ ?>
                <div class="product-info__cover">
                    <div class="product-info__cover-inner">
                        <input
                                class="visually-hidden"
                                id="craft"
                                type="radio"
                                name="cover"
                                value="craft"
                                checked
                                data-text="Упаковка: Стандартная крафтовая бумага"
                        >
                        <label for="craft">Крафт</label>
                        <input
                                class="visually-hidden"
                                id="film"
                                type="radio"
                                name="cover"
                                value="film"
                                data-text="Упаковка: Стандартная пленка"
                        >
                        <label for="film">Пленка</label>
                        <input
                                class="visually-hidden"
                                id="nothing"
                                type="radio"
                                name="cover"
                                value="nothing"
                                data-text="Упаковка: Без упаковки"
                        >
                        <label for="nothing">Без упаковки</label>
                        <div class="product-info__chosen"></div>
                    </div>
                    <div class="product-info__cover-text">Упаковка: Стандартная крафтовая бумага</div>
                </div>
            </form>
        </div>
        <?php if (!empty($arResult['UPSALE_PRODUCTS'])): ?>
            <div class="product-info__add">
                <h2 class="product-info__add-title">Добавить к заказу</h2>
                <div class="product-info__add-slider swiper-container product-add-slider">
                    <div class="swiper-wrapper">
                        <?php foreach ($arResult['UPSALE_PRODUCTS'] as $item): ?>
                            <?php
                            $upsalePriceFormat = number_format($item['price'], 0, '', ' ');
                            ?>
                            <div class="swiper-slide product-add-slider__item">
                                <img src="<?=$item['image']?>" alt="<?=$item['name']?>">
                                <div class="product-add-slider__header">
                                    <h3 class="product-add-slider__title">
                                        <?=$item['name']?> +<?=$upsalePriceFormat?> <span class="rouble"></span>
                                    </h3>
                                    <p class="product-add-slider__info"></p>
                                    <?=$item['text']?>
                                    <button class="product-add-slider__btn js-upsale-button" data-id="<?=$item['id']?>">
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
        <?php endif; ?>
        <div class="product-info__features">
            <div class="product-info__feature">
                <div class="product-info__feature-icon">
                    <svg width="26px" height="22px">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=<?=VERSION_SPRITE__ICONS?>#feature-car"></use>
                    </svg>
                </div>
                <h5 class="product-info__feature-title">Бесплатная доставка</h5>
                <p class="product-info__feature-text">
                    Бесплатно и&nbsp;быстро доставим букет&nbsp;&mdash; от&nbsp;60 минут в&nbsp;пределах МКАД.*
                </p>
                <p class="product-info__feature-note">
                    <span class="product-info__feature-note-star">*</span>
                    Доставка за&nbsp;МКАД&nbsp;&mdash;500&nbsp;руб
                </p>
            </div>
            <div class="product-info__feature">
                <div class="product-info__feature-icon">
                    <svg width="19px" height="26px">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=<?=VERSION_SPRITE__ICONS?>#feature-paper"></use>
                    </svg>
                </div>
                <h5 class="product-info__feature-title">Открытка в&nbsp;подарок</h5>
                <p class="product-info__feature-text">
                    К&nbsp;каждому букету дарим открытку в&nbsp;конверте, подписанную от&nbsp;вашего имени. Просто
                    введите текст при оформлении заказа.
                </p>
            </div>
            <div class="product-info__feature">
                <div class="product-info__feature-icon">
                    <svg width="26px" height="22px">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=<?=VERSION_SPRITE__ICONS?>#feature-camera"></use>
                    </svg>
                </div>
                <h5 class="product-info__feature-title">Контроль на&nbsp;каждом этапе</h5>
                <p class="product-info__feature-text">
                    Пришлем фото всех цветов на&nbsp;базе, сфотографируем букет и&nbsp;подписанную открытку перед
                    отправкой.
                </p>
            </div>
            <div class="product-info__feature">
                <div class="product-info__feature-icon">
                    <svg width="20px" height="26px">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=<?=VERSION_SPRITE__ICONS?>#feature-badge"></use>
                    </svg>
                </div>
                <h5 class="product-info__feature-title">Гарантия качества</h5>
                <p class="product-info__feature-text">
                    Возвращаем деньги, если цветы окажутся несвежими или завянут в&nbsp;день доставки.
                </p>
            </div>
            <div class="product-info__features-show"></div>
        </div>
        <div class="product-info__fixed-footer">
            <div class="product-info__row product-info__row--baseline">
                <div class="product-info__cell product-info__time">
                    <svg width="28" height="18" aria-hidden="true">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#delivery-time"></use>
                    </svg>
                    <span><?=$deliveryTime?></span>
                </div>
                <div class="product-info__cell">
                    <div class="payment-systems">
                        <div class="payment-systems__item" aria-label="Оплата по счёту">
                            <svg class="payment-systems__icon payment-systems__icon--invoice">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=<?=VERSION_SPRITE__ICONS?>#payment-invoice"></use>
                            </svg>
                        </div>
                        <div class="payment-systems__item" aria-label="MasterCard">
                            <svg class="payment-systems__icon payment-systems__icon--mastercard">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=<?=VERSION_SPRITE__ICONS?>#payment-mastercard"></use>
                            </svg>
                        </div>
                        <div class="payment-systems__item" aria-label="Visa">
                            <svg
                                    class="payment-systems__icon payment-systems__icon--visa"
                                    width="49"
                                    height="17"
                                    viewBox="0 0 49 17"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                            >
                                <path id="Vector" d="M18.6774 0.737724L12.288 15.9773H8.13382L4.99559 3.8308C4.81497 3.08575 4.63435 2.81483 4.06992 2.49875C3.0991 1.97947 1.54127 1.48277 0.164062 1.18927L0.254371 0.737724H6.95981C7.81774 0.737724 8.58537 1.30215 8.76598 2.29555L10.4367 11.1007L14.5458 0.737724H18.6774ZM35.0007 11.0104C35.0233 6.99162 29.4467 6.76584 29.4693 4.98224C29.4919 4.44039 30.0111 3.85338 31.14 3.69534C31.7044 3.62761 33.2623 3.55988 35.0233 4.37266L35.7232 1.14411C34.7749 0.805455 33.5558 0.466797 32.0431 0.466797C28.1598 0.466797 25.4054 2.5439 25.3828 5.50152C25.3602 7.69151 27.347 8.91068 28.8371 9.65573C30.3724 10.4008 30.8917 10.8749 30.8917 11.5522C30.8691 12.5682 29.6725 13.0197 28.5436 13.0423C26.5568 13.0649 25.4054 12.5005 24.5023 12.0715L23.7798 15.4129C24.7055 15.8419 26.3988 16.2031 28.1598 16.2257C32.2915 16.2257 34.9781 14.1712 35.0007 11.0104ZM45.2959 15.9773H48.9309L45.7475 0.737724H42.3835C41.6158 0.737724 40.9837 1.16669 40.7128 1.84401L34.7975 15.9548H38.9291L39.7419 13.6745H44.7992L45.2959 15.9773ZM40.8934 10.5814L42.9705 4.86936L44.1671 10.5814H40.8934ZM24.2991 0.737724L21.048 15.9773H17.1196L20.3707 0.737724H24.2991Z" fill="url(#paint0_linear)"/>
                                <defs>
                                    <linearGradient id="paint0_linear" x1="0.164062" y1="8.34153" x2="48.9309" y2="8.34153" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#24215B"/>
                                        <stop offset="1" stop-color="#0C509F"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (!$arResult['IS_ORDER']): ?>
                <div class="product-info__action">
                    <div class="product-info__ammount" id="input_div">
                        <input id="moins" type="button" value="-">
                        <input id="count" type="number" size="1" value="1" max="99" name="product-quantity">
                        <input id="plus" type="button" value="+">
                    </div>
                    <button
                            type="button"
                            data-dismiss="modal"
                            class="button product-info__order-button js-product-order-button"
                            data-url="/order/"
                            data-id="<?=$arResult['ID']?>"

                    >
                        <span class="promo-item__delivery__text">
                            Добавить ·
                            <span><?=$priceFormat?></span>
                            ₽
                        </span>
                    </button>
                    <button
                            class="product-info__favorite-desktop <?=$wishlistClass?>"
                            type="button"
                            data-id="<?=$arResult['ID']?>"
                    ></button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
