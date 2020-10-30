<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';

global $APPLICATION;

$APPLICATION->SetTitle('Подписка на цветы');
?>
<div class="container-block subscribe-container">
    <div class="subscribe-page hero">
        <div class="subscribe-page__sticky">
            <div class="hero__slider">
                <div class="swiper-container hero__slider-container">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="img/bunch-4@1x.jpg" srcset="img/bunch-4@2x.jpg 2x" alt="букет">
                        </div>
                        <div class="swiper-slide">
                            <img src="img/bunch-1@1x.jpg" srcset="img/bunch-1@2x.jpg 2x" alt="букет">
                        </div>
                        <div class="swiper-slide">
                            <img src="img/bunch-2@1x.jpg" srcset="img/bunch-2@2x.jpg 2x" alt="букет">
                        </div>
                    </div>
                    <div class="hero__slider-pagination swiper-pagination"></div>
                    <div class="hero__slider-button-prev swiper-button-prev">
                        <svg width="11" height="18" aria-hidden="true">
                            <use xlink:href="img/sprite.svg#slider-arrow-prev"></use>
                        </svg>
                    </div>
                    <div class="hero__slider-button-next swiper-button-next">
                        <svg width="11" height="18" aria-hidden="true">
                            <use xlink:href="img/sprite.svg#slider-arrow-next"></use>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="subscribe-page__labels subscribe-page__labels--desktop">
                <div class="subscribe-page__labels-list">
                    <div class="subscribe-page__labels-item">
                        <svg width="29" height="27" aria-hidden="true">
                            <use xlink:href="img/sprite.svg#parcel"></use>
                        </svg>
                        <span>Быстрая, бесконтактная доставка</span>
                    </div>
                    <div class="subscribe-page__labels-item">
                        <svg width="34" height="28" aria-hidden="true">
                            <use xlink:href="img/sprite.svg#delivery"></use>
                        </svg>
                        <span>Бесплатная доставка в пределах МКАД</span>
                    </div>
                    <div class="subscribe-page__labels-item">
                        <svg width="30" height="30" aria-hidden="true">
                            <use xlink:href="img/sprite.svg#discount-black"></use>
                        </svg>
                        <span>Дешевле на 40% чем покупать по одному букету</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="subscribe-page__form-block">
            <div class="subscribe-page__title">Подписка на цветы</div>
            <div class="subscribe-page__text">Подписка — это еженедельная доставка цветов. Свежие авторские
                букеты у вас дома. Составные или монобукеты, как вам больше нравится.
            </div>
            <div class="subscribe-page__form">
                <form action="">
                    <fieldset>
                        <legend>Выберите букеты</legend>
                        <input type="radio" id="mono" name="type">
                        <label for="mono">
                            <img src="img/bunch-mono@1x.jpg" srcset="img/bunch-mono@2x.jpg 2x" alt="">
                            <h3>Монобукеты</h3>
                        </label>
                        <input type="radio" id="compose" name="type">
                        <label for="compose">
                            <img src="img/bunch-compose@1x.jpg" srcset="img/bunch-compose@2x.jpg 2x" alt="">
                            <h3>Монобукеты</h3>
                        </label>
                    </fieldset>
                    <fieldset>
                        <legend>Как вам доставить цветы?</legend>
                        <input type="radio" id="delivery-1" name="delivery">
                        <label for="delivery-1">
                            <h3>Собранный букет</h3>
                            <span>Это дешевле на 40% чем покупать по одному букету</span>
                        </label>
                        <input type="radio" id="delivery-2" name="delivery">
                        <label for="delivery-2">
                            <h3>Набор</h3>
                            <span>Это дешевле на 40% чем покупать по одному букету</span>
                        </label>
                    </fieldset>
                    <fieldset>
                        <legend>Какой размер?</legend>
                        <input type="radio" id="standart-size" name="size">
                        <label for="standart-size">
                            <h3>Стандартный</h3>
                            <span>Это дешевле на 40% чем покупать по одному букету</span>
                        </label>
                        <input type="radio" id="big-size" name="size">
                        <label for="big-size">
                            <h3>Увеличенный</h3>
                            <span>Это дешевле на 40% чем покупать по одному букету</span>
                        </label>
                    </fieldset>
                    <fieldset>
                        <legend>Выберите срок подписки
                            <span>Все букеты с бесплатной доставкой.</span>
                        </legend>
                        <input type="radio" id="1month" name="subscribe">
                        <label for="1month">
                            <h3>1&nbsp;месяц · 12&nbsp;000&nbsp;₽</h3>
                            <span>24 букета</span>
                        </label>
                        <input type="radio" id="3months" name="subscribe">
                        <label class="hit" for="3months">
                            <h3>3&nbsp;месяца · 33&nbsp;000&nbsp;₽</h3>
                            <span>12 букетов</span>
                        </label>
                        <input type="radio" id="6months" name="subscribe">
                        <label for="6months">
                            <h3>6&nbsp;месяцев · 60&nbsp;000&nbsp;₽</h3>
                            <span>24 букетов</span>
                        </label>
                    </fieldset>
                    <button class="button subscribe-page__btn">Заказать</button>
                    <div class="subscribe-page__present">
                        <h3>Подарить подписку</h3>
                        <p>Вы можете подарить подписку: мы уточним удобный адрес и время, не раскрывая
                            сюрприз. Получатель будет наслаждаться свежими букетами каждую неделю.
                        </p>
                    </div>
                </form>
            </div>
        </div>
        <div class="subscribe-page__labels subscribe-page__labels--mob">
            <div class="subscribe-page__labels-list">
                <div class="subscribe-page__labels-item">
                    <svg width="29" height="27" aria-hidden="true">
                        <use xlink:href="img/sprite.svg#parcel"></use>
                    </svg>
                    <span>Быстрая, бесконтактная доставка</span>
                </div>
                <div class="subscribe-page__labels-item">
                    <svg width="34" height="28" aria-hidden="true">
                        <use xlink:href="img/sprite.svg#delivery"></use>
                    </svg>
                    <span>Бесплатная доставка в пределах МКАД</span>
                </div>
                <div class="subscribe-page__labels-item">
                    <svg width="30" height="30" aria-hidden="true">
                        <use xlink:href="img/sprite.svg#discount-black"></use>
                    </svg>
                    <span>Дешевле на 40% чем покупать по одному букету</span>
                </div>
            </div>
        </div>
    </div>
    <div class="faq">
        <h2 class="faq__title">Частые вопросы</h2>
        <div class="faq__accord js-Accordion" id="accordion">
            <button class="faq__btn">Как это работает?
                <svg width="14" height="15" aria-hidden="true">
                    <use xlink:href="img/sprite.svg#cross"></use>
                </svg>
            </button>
            <div class="faq__answer">
                <span>Выбираете стилистику букетов, привязываете карту и получаете свежий букет каждую
                    неделю!
                </span>
            </div>
            <button class="faq__btn">Сколько это стоит?
                <svg width="14" height="15" aria-hidden="true">
                    <use xlink:href="img/sprite.svg#cross"></use>
                </svg>
            </button>
            <div class="faq__answer">
                <span>Выбираете стилистику букетов, привязываете карту и получаете свежий букет каждую
                    неделю!
                </span>
            </div>
            <button class="faq__btn">А можно подарить подписку?
                <svg width="14" height="15" aria-hidden="true">
                    <use xlink:href="img/sprite.svg#cross"></use>
                </svg>
            </button>
            <div class="faq__answer">
                <span>Выбираете стилистику букетов, привязываете карту и получаете свежий букет каждую
                    неделю!
                </span>
            </div>
        </div>
    </div>
</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';
?>