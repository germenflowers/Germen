<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
?>
<div class="hero">
    <div class="hero__text">
        <h2 class="hero__title">
            Подписка на цветы
            <?php if (!empty($arResult['ITEMS'])): ?>
                <span class="change-title" style="color:#FF323D">
                    для
                    <?php
                    $first = true;
                    ?>
                    <?php foreach ($arResult['ITEMS'] as $item): ?>
                        <span class="place <?=$item['CODE']?> <?=$first ? '' : 'hidden'?>"><?=$item['NAME']?></span>
                        <?php
                        $first = false;
                        ?>
                    <?php endforeach; ?>
                </span>
            <?php endif; ?>
        </h2>
        <div class="hero__intro">
            Подписка — это еженедельная доставка цветов. Свежие авторские букеты у вас дома. Составные или монобукеты,
            как вам больше нравится.
        </div>
        <a class="hero__btn button" href="/subscribe/choice/">Подписаться</a>
    </div>
    <div class="hero__slider">
        <div class="swiper-container hero__slider-container-index">
            <div class="swiper-wrapper">
                <?php foreach ($arResult['ITEMS'] as $item): ?>
                    <?php
                    $src = '';
                    $srcset = '';
                    if (!empty($item['PREVIEW_PICTURE']) && !empty($item['DETAIL_PICTURE'])) {
                        $image = CFile::ResizeImageGet(
                            $item['PREVIEW_PICTURE'],
                            array('width' => 953, 'height' => 688),
                            BX_RESIZE_IMAGE_PROPORTIONAL
                        );

                        $src = $image['src'];
                        $srcset = $item['DETAIL_PICTURE']['SRC'];
                    } elseif (!empty($item['PREVIEW_PICTURE'])) {
                        $image = CFile::ResizeImageGet(
                            $item['PREVIEW_PICTURE'],
                            array('width' => 953, 'height' => 688),
                            BX_RESIZE_IMAGE_PROPORTIONAL
                        );

                        $src = $image['src'];
                        $srcset = $item['PREVIEW_PICTURE']['SRC'];
                    } elseif (!empty($item['DETAIL_PICTURE'])) {
                        $image = CFile::ResizeImageGet(
                            $item['DETAIL_PICTURE'],
                            array('width' => 953, 'height' => 688),
                            BX_RESIZE_IMAGE_PROPORTIONAL
                        );

                        $src = $image['src'];
                        $srcset = $item['DETAIL_PICTURE']['SRC'];
                    }

                    if (empty($src) && empty($srcset)) {
                        continue;
                    }
                    ?>
                    <div class="swiper-slide swiper-slide-<?=$item['CODE']?>">
                        <img src="<?=$src?>" srcset="<?=$srcset?> 2x" alt="">
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="hero__slider-pagination swiper-pagination"></div>
        </div>
    </div>
</div>
