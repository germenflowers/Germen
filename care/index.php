<?php

/**
 * @global CMain $APPLICATION
 */

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

$APPLICATION->SetTitle('Уход за цветами');
?>
<div class="care">
    <div class="care__inner">
        <h1 class="care__title">Вам приехали красивые цветы</h1>
        <div class="care__note">Чтобы цветы дольше простояли, ознакомьтесь с рекомендациями по уходу.</div>
        <div class="care__tabs js-tabs" id="care-tabs">
            <div class="care__tabs-header js-tabs__header">
                <li class="care__tabs-item">
                    <a class="care__tabs-link js-tabs__title" href="">Уход&nbsp;за&nbsp;букетом</a>
                </li>
                <li class="care__tabs-item">
                    <a class="care__tabs-link js-tabs__title" href="">за&nbsp;подпиской</a>
                </li>
                <li class="care__tabs-item">
                    <a class="care__tabs-link js-tabs__title" href="">за&nbsp;набором</a>
                </li>
            </div>
            <div class="care__tabs-content js-tabs__content">
                <div class="care__list">
                    <div class="care__list-item">
                        <div class="care__list-title">
                            <svg width="24" height="22" aria-hidden="true">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#care-1"></use>
                            </svg>
                            Освободите цветы от транспортной упаковки
                        </div>
                        <div class="care__image">
                            <img src="<?=SITE_TEMPLATE_PATH?>/img/care-1@1x.jpg" srcset="<?=SITE_TEMPLATE_PATH?>/img/care-1@1x.jpg 1x, <?=SITE_TEMPLATE_PATH?>/img/care-1@2x.jpg 2x">
                        </div>
                    </div>
                    <div class="care__list-item">
                        <div class="care__list-title">
                            <svg width="24" height="22" aria-hidden="true">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#care-2"></use>
                            </svg>
                            По желанию, разрежьте бичевку — чтобы цветы свободно стояли в вазе
                        </div>
                        <div class="care__image">
                            <img src="<?=SITE_TEMPLATE_PATH?>/img/care-2@1x.jpg" srcset="<?=SITE_TEMPLATE_PATH?>/img/care-2@1x.jpg 1x, <?=SITE_TEMPLATE_PATH?>/img/care-2@2x.jpg 2x">
                        </div>
                    </div>
                    <div class="care__list-item">
                        <div class="care__list-title">
                            <svg width="24" height="22" aria-hidden="true">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#care-3"></use>
                            </svg>
                            Наполните вазу прохладной водой и&nbsp;подрежьте под ее рост цветы
                        </div>
                        <div class="care__image">
                            <img src="<?=SITE_TEMPLATE_PATH?>/img/care-3@1x.jpg" srcset="<?=SITE_TEMPLATE_PATH?>/img/care-3@1x.jpg 1x, <?=SITE_TEMPLATE_PATH?>/img/care-3@2x.jpg 2x">
                        </div>
                    </div>
                    <div class="care__list-item">
                        <div class="care__list-title">
                            <svg width="24" height="22" aria-hidden="true">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#care-4"></use>
                            </svg>
                            Повторяйте последний пункт раз в 2–3&nbsp;дня и храните цветы в комфортном месте: там не
                            должно быть душно или сквозняк
                        </div>
                        <div class="care__image">
                            <img src="<?=SITE_TEMPLATE_PATH?>/img/care-4@1x.jpg" srcset="<?=SITE_TEMPLATE_PATH?>/img/care-4@1x.jpg 1x, <?=SITE_TEMPLATE_PATH?>/img/care-4@2x.jpg 2x">
                        </div>
                    </div>
                </div>
            </div>
            <div class="care__tabs-content js-tabs__content">
                <span>2</span>
                <div class="care__list">
                    <div class="care__list-item">
                        <div class="care__list-title">
                            <svg width="24" height="22" aria-hidden="true">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#care-1"></use>
                            </svg>
                            Освободите цветы от транспортной упаковки
                        </div>
                        <div class="care__image">
                            <img src="<?=SITE_TEMPLATE_PATH?>/img/care-1@1x.jpg" srcset="<?=SITE_TEMPLATE_PATH?>/img/care-1@1x.jpg 1x, <?=SITE_TEMPLATE_PATH?>/img/care-1@2x.jpg 2x">
                        </div>
                    </div>
                    <div class="care__list-item">
                        <div class="care__list-title">
                            <svg width="24" height="22" aria-hidden="true">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#care-2"></use>
                            </svg>
                            По желанию, разрежьте бичевку — чтобы цветы свободно стояли в вазе
                        </div>
                        <div class="care__image">
                            <img src="<?=SITE_TEMPLATE_PATH?>/img/care-2@1x.jpg" srcset="<?=SITE_TEMPLATE_PATH?>/img/care-2@1x.jpg 1x, <?=SITE_TEMPLATE_PATH?>/img/care-2@2x.jpg 2x">
                        </div>
                    </div>
                    <div class="care__list-item">
                        <div class="care__list-title">
                            <svg width="24" height="22" aria-hidden="true">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#care-3"></use>
                            </svg>
                            Наполните вазу прохладной водой и&nbsp;подрежьте под ее рост цветы
                        </div>
                        <div class="care__image">
                            <img src="<?=SITE_TEMPLATE_PATH?>/img/care-3@1x.jpg" srcset="<?=SITE_TEMPLATE_PATH?>/img/care-3@1x.jpg 1x, <?=SITE_TEMPLATE_PATH?>/img/care-3@2x.jpg 2x">
                        </div>
                    </div>
                    <div class="care__list-item">
                        <div class="care__list-title">
                            <svg width="24" height="22" aria-hidden="true">
                                <use xlink:href="img/sprite.svg#care-4"></use>
                            </svg>
                            Повторяйте последний пункт раз в 2–3 дня и храните цветы в комфортном месте: там не
                            должно быть душно или сквозняк
                        </div>
                        <div class="care__image">
                            <img src="<?=SITE_TEMPLATE_PATH?>/img/care-4@1x.jpg" srcset="<?=SITE_TEMPLATE_PATH?>/img/care-4@1x.jpg 1x, <?=SITE_TEMPLATE_PATH?>/img/care-4@2x.jpg 2x">
                        </div>
                    </div>
                </div>
            </div>
            <div class="care__tabs-content js-tabs__content">
                <span>3</span>
                <div class="care__list">
                    <div class="care__list-item">
                        <div class="care__list-title">
                            <svg width="24" height="22" aria-hidden="true">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#care-1"></use>
                            </svg>
                            Освободите цветы от транспортной упаковки
                        </div>
                        <div class="care__image">
                            <img src="<?=SITE_TEMPLATE_PATH?>/img/care-1@1x.jpg" srcset="<?=SITE_TEMPLATE_PATH?>/img/care-1@1x.jpg 1x, <?=SITE_TEMPLATE_PATH?>/img/care-1@2x.jpg 2x">
                        </div>
                    </div>
                    <div class="care__list-item">
                        <div class="care__list-title">
                            <svg width="24" height="22" aria-hidden="true">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#care-2"></use>
                            </svg>
                            По желанию, разрежьте бичевку — чтобы цветы свободно стояли в вазе
                        </div>
                        <div class="care__image">
                            <img src="<?=SITE_TEMPLATE_PATH?>/img/care-2@1x.jpg" srcset="<?=SITE_TEMPLATE_PATH?>/img/care-2@1x.jpg 1x, <?=SITE_TEMPLATE_PATH?>/img/care-2@2x.jpg 2x">
                        </div>
                    </div>
                    <div class="care__list-item">
                        <div class="care__list-title">
                            <svg width="24" height="22" aria-hidden="true">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#care-3"></use>
                            </svg>
                            Наполните вазу прохладной водой и&nbsp;подрежьте под ее рост цветы
                        </div>
                        <div class="care__image">
                            <img src="<?=SITE_TEMPLATE_PATH?>/img/care-3@1x.jpg" srcset="<?=SITE_TEMPLATE_PATH?>/img/care-3@1x.jpg 1x, <?=SITE_TEMPLATE_PATH?>/img/care-3@2x.jpg 2x">
                        </div>
                    </div>
                    <div class="care__list-item">
                        <div class="care__list-title">
                            <svg width="24" height="22" aria-hidden="true">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#care-4"></use>
                            </svg>
                            Повторяйте последний пункт раз в 2–3 дня и храните цветы в комфортном месте: там не
                            должно быть душно или сквозняк
                        </div>
                        <div class="care__image">
                            <img src="<?=SITE_TEMPLATE_PATH?>/img/care-4@1x.jpg" srcset="<?=SITE_TEMPLATE_PATH?>/img/care-4@1x.jpg 1x, <?=SITE_TEMPLATE_PATH?>/img/care-4@2x.jpg 2x">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="first-order">
    <h2 class="first-order__title">
        Хотите заказать цветы?
        <br>
        У нас есть скидка на первый заказ
    </h2>
    <div class="first-order__links">
        <a class="first-order__link" href="#">Перейти в каталог</a>
        <a class="first-order__link" href="#">Написать в чат</a>
    </div>
    <div class="first-order__messangers">Не можете выбрать? Напишите нам в удобный мессенджер — поможем.</div>
</div>
<?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>