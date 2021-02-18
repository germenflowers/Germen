<?php

/**
 * @global CMain $APPLICATION
 */

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

$APPLICATION->SetTitle('Корзина');
?>
<div class="cart">
  <div class="empty js-favorite-empty-block" style="">
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
  <h1 class="cart__title">Корзина</h1>
  <div class="cart__list">
    <div class="cart__list-item cart-item">
      <div class="cart-item__image"><img src="<?=SITE_TEMPLATE_PATH?>/img/bunch-mono@1x.jpg" alt="" width="64" height="64"></div>
      <div class="cart-item__title">Букет розовая нежность</div>
      <div class="cart-item__ammount">
        <div class="quantity-control"><button class="moins" type="button"></button><input class="count quantity-control__input js-quantity-field" name="quantity" value="1"><button class="plus" type="button"></button></div>
      </div>
      <div class="cart-item__price">3 990 <span>₽</span></div>
      <div class="cart-item__delete"><button type="button"><svg width="16" height="16" aria-hidden="true">
            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#trash-2"></use>
          </svg></button></div>
    </div>
    <div class="cart__list-item cart-item">
      <div class="cart-item__image"><img src="<?=SITE_TEMPLATE_PATH?>/img/bunch-mono@1x.jpg" alt="" width="64" height="64"></div>
      <div class="cart-item__title">Chàmomile</div>
      <div class="cart-item__ammount">
        <div class="quantity-control"><button class="moins" type="button"></button><input class="count quantity-control__input js-quantity-field" name="quantity" value="1"><button class="plus" type="button"></button></div>
      </div>
      <div class="cart-item__price">3 990 <span>₽</span></div>
      <div class="cart-item__delete"><button type="button"><svg width="16" height="16" aria-hidden="true">
            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#trash-2"></use>
          </svg></button></div>
    </div>
    <div class="cart__list-item cart-item">
      <div class="cart-item__image"><img src="<?=SITE_TEMPLATE_PATH?>/img/bunch-mono@1x.jpg" alt="" width="64" height="64"></div>
      <div class="cart-item__title">Chernichny</div>
      <div class="cart-item__ammount">
        <div class="quantity-control"><button class="moins" type="button"></button><input class="count quantity-control__input js-quantity-field" name="quantity" value="1"><button class="plus" type="button"></button></div>
      </div>
      <div class="cart-item__price">3 990 <span>₽</span></div>
      <div class="cart-item__delete"><button type="button"><svg width="16" height="16" aria-hidden="true">
            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#trash-2"></use>
          </svg></button></div>
    </div>
    <div class="cart__list-item cart-item">
      <div class="cart-item__image"><img src="<?=SITE_TEMPLATE_PATH?>/img/bunch-mono@1x.jpg" alt="" width="64" height="64"></div>
      <div class="cart-item__title">CoraLL</div>
      <div class="cart-item__ammount">
        <div class="quantity-control"><button class="moins" type="button"></button><input class="count quantity-control__input js-quantity-field" name="quantity" value="1"><button class="plus" type="button"></button></div>
      </div>
      <div class="cart-item__price">3 990 <span>₽</span></div>
      <div class="cart-item__delete"><button type="button"><svg width="16" height="16" aria-hidden="true">
            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#trash-2"></use>
          </svg></button></div>
    </div>
    <div class="cart__list-item cart-item">
      <div class="cart-item__image"><img src="<?=SITE_TEMPLATE_PATH?>/img/helper-cart.png" alt="" width="64" height="64"></div>
      <div class="cart-item__title">Helper</div>
      <div class="cart-item__price">250 <span>₽</span></div>
      <div class="cart-item__delete"><button type="button"><svg width="16" height="16" aria-hidden="true">
            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#trash-2"></use>
          </svg></button></div>
    </div>
    <div class="cart__list-item cart-item">
      <div class="cart-item__image"><img src="<?=SITE_TEMPLATE_PATH?>/img/small-cart.png" alt="" width="64" height="64"></div>
      <div class="cart-item__title">Mini</div>
      <div class="cart-item__ammount">
        <div class="quantity-control"><button class="moins" type="button"></button><input class="count quantity-control__input js-quantity-field" name="quantity" value="1"><button class="plus" type="button"></button></div>
      </div>
      <div class="cart-item__price">200 <span>₽</span></div>
      <div class="cart-item__delete"><button type="button"><svg width="16" height="16" aria-hidden="true">
            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#trash-2"></use>
          </svg></button></div>
    </div>
    <div class="cart__list-item cart-item">
      <div class="cart-item__image"><img src="<?=SITE_TEMPLATE_PATH?>/img/delivery-cart.png" alt="" width="64" height="64"></div>
      <div class="cart-item__title">Доставка</div>
      <div class="cart-item__price">250 <span>₽</span></div>
      <div class="cart-item__delete"><button type="button"><svg width="16" height="16" aria-hidden="true">
            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#trash-2"></use>
          </svg></button></div>
    </div>
  </div>
  <div class="cart__add">
    <h2 class="cart__add-title">Добавить к заказу</h2>
    <div class="cart__add-slider swiper-container product-add-slider">
      <div class="swiper-wrapper">
        <div class="swiper-slide product-add-slider__item"><img src="<?=SITE_TEMPLATE_PATH?>/img/helper-cart.png" alt="">
          <div class="product-add-slider__header">
            <h3 class="product-add-slider__title">Helper +250 ₽</h3>
            <p class="product-add-slider__info"></p>Наш курьер зайдет за любимым вином или десертом. Стоимость товара оплачивается отдельно по чеку.<button class="product-add-slider__btn product-add-slider__btn--is-chosen"><svg width="20" height="20" aria-hidden="true">
                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#round-tick"></use>
              </svg></button>
          </div>
        </div>
        <div class="swiper-slide product-add-slider__item"><img src="<?=SITE_TEMPLATE_PATH?>/img/small-cart.png" alt="">
          <div class="product-add-slider__header">
            <h3 class="product-add-slider__title">Mini +200 ₽</h3>
            <p class="product-add-slider__info"></p>Мини букет из сезонных цветов<button class="product-add-slider__btn product-add-slider__btn--is-chosen"><svg width="20" height="20" aria-hidden="true">
                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#round-tick"></use>
              </svg></button>
          </div>
        </div>
        <div class="swiper-slide product-add-slider__item"><img src="<?=SITE_TEMPLATE_PATH?>/img/delivery-cart.png" alt="">
          <div class="product-add-slider__header">
            <h3 class="product-add-slider__title">Доставка +250 ₽</h3>
            <p class="product-add-slider__info"></p>Доставка за Мкад до 7 км. +1 час ко времени доставки.<button class="product-add-slider__btn product-add-slider__btn--is-chosen"><svg width="20" height="20" aria-hidden="true">
                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#round-tick"></use>
              </svg></button>
          </div>
        </div>
        <div class="swiper-slide product-add-slider__item"><img src="<?=SITE_TEMPLATE_PATH?>/img/glasses.jpg" alt="">
          <div class="product-add-slider__header">
            <h3 class="product-add-slider__title">Vaze +550 ₽</h3>
            <p class="product-add-slider__info"></p>Лаконичная стеклянная ваза, подобранная под рост и форму букета. Подчеркивает красоту цветов, не обременяя букет лишними деталями<button class="product-add-slider__btn product-add-slider__btn--is-chosen"><svg width="20" height="20" aria-hidden="true">
                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#round-tick"></use>
              </svg></button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="cart__bottom">
    <div class="cart__promo">
      <p class="cart__promo-used">Промокод применен</p>
      <form class="cart__promo-form" action="post"><input type="text" placeholder="Введие промокод"><button class="cart__promo-btn" type="submit">Применить</button></form>
      <form class="cart__promo-form" action="post"><input type="text" placeholder="Введие промокод"><button class="cart__promo-btn cart__promo-btn--loading" type="submit"><svg width="18" height="18" aria-hidden="true">
                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#loading"></use>
              </svg></button></form>
      <form class="cart__promo-form" action="post"><input type="text" placeholder="Введие промокод"><button class="cart__promo-btn" type="submit">Удалить</button></form>
      <p class="cart__promo-alert"><svg width="18" height="18" aria-hidden="true">
                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#alert"></use>
              </svg> <span>Купон не действителен</span></p>
    </div>
    <div class="cart__sum">
      <div class="cart__total">Сумма заказа: <b>12 350</b> ₽</div>
      <div class="cart__discount">Промокод:: <p>-1 200</p> ₽</div>
    </div>
  </div>
  <div class="cart__order"><button type="submit">Оформить заказ</button></div>
</div>
<?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>