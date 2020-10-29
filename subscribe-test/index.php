<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';

global $APPLICATION;

$APPLICATION->SetTitle('Подписка на цветы');
?>
<div class="container">
    <div class="hero">
        <div class="hero__slider">
            <div class="swiper-container hero__slider-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="img/bunch-1-big@1x.jpg" srcset="img/bunch-1-big@2x.jpg 2x" alt="букет">
                    </div>
                    <div class="swiper-slide">
                        <img src="img/bunch-1@1x.jpg" srcset="img/bunch-1@2x.jpg 2x" alt="букет">
                    </div>
                    <div class="swiper-slide">
                        <img src="img/bunch-1-big@1x.jpg" srcset="img/bunch-1-big@2x.jpg 2x" alt="букет">
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
        <div class="hero__text">
            <h2 class="hero__title">Подписка на цветы</h2>
            <div class="hero__intro">Подписка — это еженедельная доставка цветов. Свежие авторские букеты у
                вас дома. Составные или монобукеты, как вам больше нравится.
            </div>
            <button class="hero__btn button">Подписаться</button>
        </div>
    </div>
    <div class="benefits">
        <div class="benefits__list">
            <div class="benefits__list-item">
                <svg width="40" height="40" aria-hidden="true">
                    <use xlink:href="img/sprite.svg#discount"></use>
                </svg>
                <h3 class="benefits__title">Вы экономите</h3>
                <div class="benefits__text">Это дешевле на 40% чем покупать по одному букету</div>
            </div>
            <div class="benefits__list-item">
                <svg width="40" height="40" aria-hidden="true">
                    <use xlink:href="img/sprite.svg#bunch-green"></use>
                </svg>
                <h3 class="benefits__title">Свежие цветы</h3>
                <div class="benefits__text">Доставка от местных поставщиков, цветы cобираем прямо перед
                    доставкой
                </div>
            </div>
            <div class="benefits__list-item">
                <svg width="40" height="40" aria-hidden="true">
                    <use xlink:href="img/sprite.svg#bouquet"></use>
                </svg>
                <h3 class="benefits__title">Красота всегда</h3>
                <div class="benefits__text">Вам не надо помнить о цветах. Самый красивый букет у вас дома
                    или в офисе
                </div>
            </div>
        </div>
    </div>
    <div class="subscribe">
        <div class="subscribe__tabs js-tabs" id="tabs">
            <div class="subscribe__header js-tabs__header">
                <li class="subscribe__list-item">
                    <a class="subscribe__link js-tabs__title" href="">Монобукеты</a>
                </li>
                <li class="subscribe__list-item">
                    <a class="subscribe__link js-tabs__title" href="">Составные букеты</a>
                </li>
            </div>
            <div class="subscribe__content js-tabs__content">
                <p class="subscribe__text">1 -Букеты из цветов одного вида и, часто, одного цвета. В
                    качестве базового цветка могут быть использованы пионовидные розы, гвоздики, георгины,
                    тюльпаны, анемоны — любые цветы, которые любите вы или ваши близкие.
                </p>
                <div class="subscribe__slider">
                    <div class="swiper-container subscribe__slider-container subscribe__mono-container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="img/bunch-1@1x.jpg" srcset="img/bunch-1@2x.jpg 2x" alt="букет">
                            </div>
                            <div class="swiper-slide">
                                <img src="img/bunch-2@1x.jpg" srcset="img/bunch-2@2x.jpg 2x" alt="букет">
                            </div>
                            <div class="swiper-slide">
                                <img src="img/bunch-3@1x.jpg" srcset="img/bunch-3@2x.jpg 2x" alt="букет">
                            </div>
                            <div class="swiper-slide">
                                <img src="img/bunch-4@1x.jpg" srcset="img/bunch-4@2x.jpg 2x" alt="букет">
                            </div>
                        </div>
                        <div class="subscribe__slider-button-prev swiper-button-prev">
                            <svg width="11" height="18" aria-hidden="true">
                                <use xlink:href="img/sprite.svg#slider-arrow-prev"></use>
                            </svg>
                        </div>
                        <div class="subscribe__slider-button-next swiper-button-next">
                            <svg width="11" height="18" aria-hidden="true">
                                <use xlink:href="img/sprite.svg#slider-arrow-next"></use>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="subscribe__content js-tabs__content">
                <p class="subscribe__text">2 -Букеты из цветов одного вида и, часто, одного цвета. В
                    качестве базового цветка могут быть использованы пионовидные розы, гвоздики, георгины,
                    тюльпаны, анемоны — любые цветы, которые любите вы или ваши близкие.
                </p>
                <div class="subscribe__slider">
                    <div class="swiper-container subscribe__slider-container subscribe__compose-container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="img/bunch-1@1x.jpg" srcset="img/bunch-1@2x.jpg 2x" alt="букет">
                            </div>
                            <div class="swiper-slide">
                                <img src="img/bunch-2@1x.jpg" srcset="img/bunch-2@2x.jpg 2x" alt="букет">
                            </div>
                            <div class="swiper-slide">
                                <img src="img/bunch-3@1x.jpg" srcset="img/bunch-3@2x.jpg 2x" alt="букет">
                            </div>
                            <div class="swiper-slide">
                                <img src="img/bunch-4@1x.jpg" srcset="img/bunch-4@2x.jpg 2x" alt="букет">
                            </div>
                        </div>
                        <div class="subscribe__slider-button-prev swiper-button-prev">
                            <svg width="11" height="18" aria-hidden="true">
                                <use xlink:href="img/sprite.svg#slider-arrow-prev"></use>
                            </svg>
                        </div>
                        <div class="subscribe__slider-button-next swiper-button-next">
                            <svg width="11" height="18" aria-hidden="true">
                                <use xlink:href="img/sprite.svg#slider-arrow-next"></use>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <p class="subscribe__note">Заранее букеты неизвестны, потому что собираются утром в день перед
            доставкой
        </p>
        <button class="button subscribe__btn">Оформить подписку</button>
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