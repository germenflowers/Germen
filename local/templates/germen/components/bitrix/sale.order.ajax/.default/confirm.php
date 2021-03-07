<?php

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $templateFolder
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
?>
<div
        class="modal fade"
        id="popup-flowers"
        tabindex="-1"
        role="dialog"
        aria-labelledby="myModalLabel"
        aria-hidden="true"
>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="popup-promo">
                <div class="popup-promo__header">
                    <div class="logo"></div>
                </div>
                <div class="popup-promo__close mobile-menu active" data-dismiss="modal" aria-hidden="true">
                    <span></span>
                </div>
                <div class="popup-promo__greate">
                    <div class="popup-promo__greate__logo">
                        <a href="/" target="_blank">
                            <img src="<?=SITE_TEMPLATE_PATH?>/img/logo2.png" alt="">
                        </a>
                    </div>
                    <div class="popup-promo__greate__description">
                        <?php if ($arResult['ORDER']['ID'] > 0): ?>
                            <div class="head-h2">Спасибо! Ваш заказ №<?=$arResult['ORDER']['ID']?> принят.</div>
                            <p>Сейчас будет переход к оплате...</p>
                            <p style="font-size: 13px; padding-top: 25px;">
                                Если этого не произошло, пожалуйста, нажмите на кнопку ниже:
                                <?php if ($arResult["ORDER"]["IS_ALLOW_PAY"] === 'Y' && !empty($arResult["PAYMENT"])): ?>
                                    <?php foreach ($arResult["PAYMENT"] as $payment): ?>
                                        <?php if (
                                            $payment["PAID"] !== 'Y' &&
                                            !empty($arResult['PAY_SYSTEM_LIST']) &&
                                            array_key_exists($payment["PAY_SYSTEM_ID"], $arResult['PAY_SYSTEM_LIST'])
                                        ): ?>
                                            <?php
                                            $arPaySystem = $arResult['PAY_SYSTEM_LIST'][$payment["PAY_SYSTEM_ID"]];
                                            ?>
                                            <?php if (empty($arPaySystem["ERROR"])): ?>
                                                <?php if (
                                                    $arPaySystem["NEW_WINDOW"] === "Y" &&
                                                    $arPaySystem["IS_CASH"] !== "Y" &&
                                                    strlen($arPaySystem["ACTION_FILE"]) > 0
                                                ): ?>
                                                    <?php
                                                    $orderAccountNumber = urlencode(
                                                        urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"])
                                                    );
                                                    $paymentAccountNumber = $payment["ACCOUNT_NUMBER"];
                                                    ?>
                                                    <script>
                                                      window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=$orderAccountNumber?>&PAYMENT_ID=<?=$paymentAccountNumber?>');
                                                    </script>
                                                <?php else: ?>
                                                    <?=$arPaySystem["BUFFERED_OUTPUT"]?>
                                                <?php endif; ?>
                                                <script>
                                                  setTimeout(function () {
                                                    window.location.href = document.forms[0].getAttribute('action')
                                                  }, 1000);
                                                </script>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </p>
                        <?php else: ?>
                            <div class="head-h2">Извините, заказ №<?=(int)$_REQUEST['ORDER_ID']?> не найден.</div>
                            <p></p>
                        <?php endif; ?>
                    </div>
                </div>
                <br/>
                <br/>
            </div>
        </div>
    </div>
</div>