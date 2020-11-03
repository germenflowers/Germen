<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
?>
<?php if (!empty($arResult['ITEMS'])): ?>
    <div class="subscribe__slider">
        <div class="swiper-container subscribe__slider-container subscribe__mono-container">
            <div class="swiper-wrapper">
                <?php foreach ($arResult['ITEMS'] as $item): ?>
                    <?php
                    $src = '';
                    $srcset = '';
                    if (!empty($item['PREVIEW_PICTURE']) && !empty($item['DETAIL_PICTURE'])) {
                        $image = CFile::ResizeImageGet(
                            $item['PREVIEW_PICTURE'],
                            array('width' => 376, 'height' => 376),
                            BX_RESIZE_IMAGE_PROPORTIONAL
                        );

                        $src = $image['src'];
                        $srcset = $item['DETAIL_PICTURE']['SRC'];
                    } elseif (!empty($item['PREVIEW_PICTURE'])) {
                        $image = CFile::ResizeImageGet(
                            $item['PREVIEW_PICTURE'],
                            array('width' => 376, 'height' => 376),
                            BX_RESIZE_IMAGE_PROPORTIONAL
                        );

                        $src = $image['src'];
                        $srcset = $item['PREVIEW_PICTURE']['SRC'];
                    } elseif (!empty($item['DETAIL_PICTURE'])) {
                        $image = CFile::ResizeImageGet(
                            $item['DETAIL_PICTURE'],
                            array('width' => 376, 'height' => 376),
                            BX_RESIZE_IMAGE_PROPORTIONAL
                        );

                        $src = $image['src'];
                        $srcset = $item['DETAIL_PICTURE']['SRC'];
                    }

                    if (empty($src) && empty($srcset)) {
                        continue;
                    }
                    ?>
                    <div class="swiper-slide">
                        <img src="<?=$src?>" srcset="<?=$srcset?> 2x" alt="">
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="subscribe__slider-button-prev swiper-button-prev">
                <svg width="11" height="18" aria-hidden="true">
                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/flower_subscription.svg#slider-arrow-prev"></use>
                </svg>
            </div>
            <div class="subscribe__slider-button-next swiper-button-next">
                <svg width="11" height="18" aria-hidden="true">
                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/flower_subscription.svg#slider-arrow-next"></use>
                </svg>
            </div>
        </div>
    </div>
<?php endif; ?>