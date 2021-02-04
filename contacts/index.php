<?php

/**
 * @global CMain $APPLICATION
 */

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

$APPLICATION->SetTitle('Контакты');
?>
<ul class="promo-contacts">
    <li>
        <span class="promo-contacts__icon"></span>
        <div class="promo-contacts__text">
            <p>
                <a href="mailto:service@germen.me">service@germen.me</a>
            </p>
        </div>
    </li>
    <li>
        <span class="promo-contacts__icon"></span>
        <div class="promo-contacts__text">
            <p>
                <a href="tel:+74955656556">+ 7(964) 507-62-82</a>
            </p>
            Адрес магазина
            <br>
            127051, Россия, Москва, большой Сухаревский переулок, 15/1
            <br>
            <br>
            <p>
                Можно писать в телеграм, вотсап или мессенджер
            </p>
        </div>
    </li>
</ul>
<?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>