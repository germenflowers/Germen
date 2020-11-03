<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$offers = array();
foreach ($arResult['ITEMS'] as $item) {
    foreach ($item['OFFERS'] as $offer) {
        $offers[$offer['PROPERTIES']['SIZE']['VALUE_XML_ID']][] = array(
            'id' => $offer['ID'],
            'name' => $offer['NAME'],
            'productName' => $item['NAME'],
            'price' => $offer['MIN_PRICE']['VALUE'],
            'hit' => $offer['PROPERTIES']['HIT']['VALUE_XML_ID'] === 'yes',
            'caption' => $offer['PROPERTIES']['CAPTION']['VALUE'],
        );
    }
}
?>
<form action="/order/" method="get">
    <fieldset class="subscribe-page__form-type">
        <legend>Выберите букеты</legend>
        <input type="radio" id="mono" name="type" value="mono" checked>
        <label class="subscribe-page__form-type--mono" for="mono">
            <img src="<?=SITE_TEMPLATE_PATH?>/img/bunch-mono@1x.jpg" srcset="<?=SITE_TEMPLATE_PATH?>/img/bunch-mono@2x.jpg 2x" alt="">
            <h3>Монобукеты</h3>
        </label>
        <input type="radio" id="compose" name="type" value="compose">
        <label class="subscribe-page__form-type--compose" for="compose">
            <img src="<?=SITE_TEMPLATE_PATH?>/img/bunch-compose@1x.jpg" srcset="<?=SITE_TEMPLATE_PATH?>/img/bunch-compose@2x.jpg 2x" alt="">
            <h3>Составные букеты</h3>
        </label>
    </fieldset>
    <fieldset>
        <legend>Как вам доставить цветы?</legend>
        <input type="radio" id="assembled" name="delivery" value="assembled" checked>
        <label for="assembled">
            <h3>Собранный букет</h3>
            <span>Это дешевле на 40% чем покупать по одному букету</span>
        </label>
        <input type="radio" id="set" name="delivery" value="set">
        <label for="set">
            <h3>Набор</h3>
            <span>Это дешевле на 40% чем покупать по одному букету</span>
        </label>
    </fieldset>
    <fieldset>
        <legend>Какой размер?</legend>
        <?php if (!empty($offers['standart'])): ?>
            <input type="radio" id="standart" name="size" value="standart" checked>
            <label for="standart">
                <h3>Стандартный</h3>
                <span>Это дешевле на 40% чем покупать по одному букету</span>
            </label>
        <?php endif; ?>
        <?php if (!empty($offers['big'])): ?>
            <input type="radio" id="big" name="size" value="big" <?=empty($offers['standart']) ? 'checked' : ''?>>
            <label for="big">
                <h3>Увеличенный</h3>
                <span>Это дешевле на 40% чем покупать по одному букету</span>
            </label>
        <?php endif; ?>
    </fieldset>
    <fieldset>
        <legend>
            Выберите срок подписки <span>Все букеты с бесплатной доставкой.</span>
        </legend>
        <?php
        $first = true;
        ?>
        <?php foreach ($offers['standart'] as $offer): ?>
            <div class="js-subscribe" data-size="standart">
                <input type="radio" id="<?=$offer['id']?>" name="id" value="<?=$offer['id']?>" <?=$first ? 'checked' : ''?>>
                <label for="<?=$offer['id']?>" class="<?=$offer['hit'] ? 'hit' : ''?>">
                    <h3>
                        <?=$offer['productName']?>
                        ·
                        <?=number_format($offer['price'], 0, '', '&nbsp;')?>&nbsp;₽
                    </h3>
                    <span><?=$offer['caption']?></span>
                </label>
            </div>
            <?php
            $first = false;
            ?>
        <?php endforeach; ?>
        <?php
        $first = empty($offers['standart']);
        ?>
        <?php foreach ($offers['big'] as $offer): ?>
            <div class="js-subscribe" data-size="big" style="display: none;">
                <input type="radio" id="<?=$offer['id']?>" name="id" value="<?=$offer['id']?>" <?=$first ? 'checked' : ''?>>
                <label for="<?=$offer['id']?>" class="<?=$offer['hit'] ? 'hit' : ''?>">
                    <h3>
                        <?=$offer['productName']?>
                        ·
                        <?=number_format($offer['price'], 0, '', '&nbsp;')?>&nbsp;₽
                    </h3>
                    <span><?=$offer['caption']?></span>
                </label>
            </div>
            <?php
            $first = false;
            ?>
        <?php endforeach; ?>
    </fieldset>
    <button type="submit" class="button subscribe-page__btn">Заказать</button>
    <div class="subscribe-page__present">
        <h3>Подарить подписку</h3>
        <p>
            Вы можете подарить подписку: мы уточним удобный адрес и время, не раскрывая сюрприз. Получатель будет наслаждаться
            свежими букетами каждую неделю.
        </p>
    </div>
</form>