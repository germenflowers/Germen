<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

use \Bitrix\Sale\DiscountCouponsManager;

$APPLICATION->AddHeadScript('//api-maps.yandex.ru/2.1/?lang=ru_RU');
?>

<?if ( !$arResult["CONFIRM"] ):?>
    <div class="content content--order">
        <div class="content__container content__container--thinner">
            <div class="promo-order">
                <div class="promo-order__header">
                    <div class="promo-order__logo">
                        <a href="/" class="logo"></a>
                    </div>
                </div>

                <div class="head-h2 promo-order__title">Оформление заказа</div>

                <?foreach ( $arResult['ERROR'] as $error) {?>
                    <div class="order-error"><?=$error?></div>
                <? } ?>

                <div class="promo-order__item" data-price="<?=$arResult['PRODUCT']['PRICE']?>">
                    <div class="promo-order__item__inside">
                        <?if (!empty($arResult['PRODUCT']['PICTURE'])) {?>
                            <div class="promo-order__item__img">
                                <a href="#" class="js-detail" data-id="<?=$arResult['PRODUCT']['ID']?>">
                                    <img src="<?=$arResult['PRODUCT']['PICTURE']?>" alt="<?=$arResult['PRODUCT']['NAME']?>">
                                </a>
                            </div>
                        <? } ?>

                        <div class="promo-order__item__description">
                            <p class="promo-order__item__title" class="js-detail" data-id="<?=$arResult['PRODUCT']['ID']?>"><?=$arResult['PRODUCT']['NAME']?></p>

                            <div class="promo-order__item__description__text">
                                <p class="text--light promo-order__item__delivery">Доставка бесплатно</p>

                                <div class="promo-order__item__price js-prod_price">
                                    <?if( $arResult['PRODUCT']['OLD_PRICE'] > 0){?>
                                        <span class="promo-order__item__price__old"><?=number_format($arResult['PRODUCT']['OLD_PRICE'],0,'', ' ')?> <span class="rouble"></span></span>
                                    <? } ?>
                                    <?=number_format($arResult['PRODUCT']['PRICE'],0,'', ' ')?> <span class="rouble"></span>
                                </div>
                            </div>
                            <div class="promo-order__item__coupon">
                                <a class="promo-order__item__coupon__link js-coupon_link" href="#" title="у меня есть промо-код">у меня есть промо-код</a>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="form-order" action="<?=$APPLICATION->GetCurPageParam(false)?>" method="post">
                    <div class="promo-order__coupon" id="coupon_wrap"<?if(empty($arResult['COUPON'])){?> style="display: none;"<?}?>>
                        <div class="head-h3 promo-order__coupon__title">Промо-код</div>
                        <div class="promo-order__row">
                            <div class="promo-order__block">
                                <div class="input__wrapper"<?if ($arResult['COUPON']['STATUS_ENTER'] != 'BAD' && !empty($arResult['COUPON'])){?> style="display: none;"<?}?>>
                                    <input type="text" class="input" value="<?=$arResult['COUPON']['COUPON']?>">
                                </div>
                                <p class="promo-order__error"<?if ($arResult['COUPON']['STATUS_ENTER'] != 'BAD' || empty($arResult['COUPON'])) {?> style="display: none;"<?}?>>Промо-код не найден :(</p>
                                <div class="promo-order__coupon__discount"<?if ($arResult['COUPON']['STATUS_ENTER'] == 'BAD' || empty($arResult['COUPON'])){?> style="display: none;"<?}?>>
                                    <?=$arResult['COUPON']['DISCOUNT_NAME']?> (<strong><?=$arResult['COUPON']['COUPON']?></strong>)
                                    <span class="promo-order__coupon__discount__cancel">Отменить</span>
                                </div>
                            </div>
                            <div class="promo-order__block">
                                <button type="button" class="btn btn__main promo-order__coupon__button"<?if ($arResult['COUPON']['STATUS_ENTER'] != 'BAD' && !empty($arResult['COUPON'])){?> style="display: none;"<?}?>>Применить</button>
                                <span class="promo-order__coupon__sum"<?if( $arResult['PRODUCT']['OLD_PRICE'] == 0){?> style="display: none;"<?}?>>-<?=$arResult['PRODUCT']['OLD_PRICE']-$arResult['PRODUCT']['PRICE']?> <span class="rouble"></span></span>
                            </div>
                        </div>
                    </div>

                    <div class="promo-order__map">
                        <div class="head-h3 promo-order__map__title">Адрес доставки</div>

                        <?$prop = $arResult['ORDER_PROPS'][1];?>
                        <input type="hidden" class="input" name="ORDER_PROP_<?=$prop['ID']?>" value="<?=$prop['VALUE']?>">

                        <div class="promo-order__address">
                            <div class="promo-order__row">
                                <?$prop = $arResult['ORDER_PROPS'][2];?>
                                <div class="promo-order__block js-address-block">
                                    <div class="input__wrapper input__wrapper--mark">
                                        <input id="ORDER_PROP_<?=$prop['ID']?>" type="text" class="input" name="ORDER_PROP_<?=$prop['ID']?>" placeholder="<?=$prop['NAME']?>" value="<?=$prop['VALUE']?>">
                                        <div class="list_street">
                                            <ul id="search-street"></ul>
                                        </div>
                                    </div>
                                </div>

                                <?$prop = $arResult['ORDER_PROPS'][3];?>
                                <div class="promo-order__block js-address-block">
                                    <div class="input__wrapper">
                                        <input type="text" class="input" name="ORDER_PROP_<?=$prop['ID']?>" placeholder="<?=$prop['NAME']?>" value="<?=$prop['VALUE']?>">
                                    </div>
                                </div>

                                <?$prop = $arResult['ORDER_PROPS'][15];?>
                                <div class="promo-order__block promo-order__block--full">
                                    <div class="styled-checkbox__block">
                                        <input class="styled-checkbox" name="ORDER_PROP_<?=$prop['ID']?>" id="ORDER_PROP_<?=$prop['ID']?>" type="checkbox" value="Y"<?if(!empty($prop['VALUE']))echo ' checked';?>>
                                        <label for="ORDER_PROP_<?=$prop['ID']?>"><?=$prop['NAME']?></label>

                                        <?if( !empty($prop['DESCRIPTION']) ) {?>
                                            <span class="promo-tooltip promo-tooltip--question" data-toggle="tooltip" data-placement="bottom" title="<?=$prop['DESCRIPTION']?>">
                                                <svg class="" width="23px" height="23px">
                                                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=1.1#question"></use>
                                                </svg>
                                            </span>
                                        <? } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="delivery-map" class="promo-order__map__block js-address-block" style="width:100%"></div>
                    </div>

                    <div class="promo-order__delivery">
                        <?$prop = $arResult['ORDER_PROPS'][4];?>
                        <div class="promo-order__delivery__block">
                            <div class="head-h3 promo-order__delivery__title">Когда доставить</div>

                            <div class="promo-order__row">
                                <?for ($i=1; $i<=$arResult['COUNT_DATE_DELIVERY']; $i++) {?>
                                    <div class="promo-order__block promo-order__block--full">
                                        <input type="text" class="input order-datetime" name="ORDER_PROP_<?=$prop['ID']?>[<?=$i-1?>]" id="ORDER_PROP_<?=$prop['ID']?>_<?=$i-1?>" placeholder="<?=$prop['NAME']?>" value="<?=$prop['VALUE'][$i-1]?>" data-time="<?=$arResult['DELIVERY_TIME']?>">
                                    </div>
                                <? } ?>
                            </div>
                        </div>

                        <?$prop = $arResult['ORDER_PROPS'][5];?>
                        <div class="promo-order__delivery__block">
                            <div class="head-h3 promo-order__delivery__title">Ваш телефон</div>

                            <div class="promo-order__row">
                                <div class="promo-order__block">
                                    <div class="input__wrapper input__wrapper--mark">
                                        <input type="text" class="input phonemask" name="ORDER_PROP_<?=$prop['ID']?>" id="ORDER_PROP_<?=$prop['ID']?>" value="<?= !empty($prop['VALUE']) ? $prop['VALUE'] : '+7' ?>" placeholder="+7">
                                    </div>
                                </div>
                                <div class="promo-order__block hidden" id="contacts-name">
                                    <div class="input__wrapper input__wrapper--mark">
                                        <input type="text" class="input" id="ORDER_PROP_name" name="ORDER_PROP_name" value="" placeholder="Имя">
                                    </div>
                                </div>
                            </div>

                            <div class="promo-order__delivery__checkbox">
                                <?$prop = $arResult['ORDER_PROPS'][6];?>
                                <div class="styled-checkbox__block">
                                    <input class="styled-checkbox" name="ORDER_PROP_<?=$prop['ID']?>" id="ORDER_PROP_<?=$prop['ID']?>" type="checkbox" value="Y"<?if(!empty($prop['VALUE']))echo ' checked';?>>
                                    <label for="ORDER_PROP_<?=$prop['ID']?>"><?=$prop['NAME']?></label>
                                </div>

                                <?$prop = $arResult['ORDER_PROPS'][7];?>
                                <div class="styled-checkbox__block">
                                    <input class="styled-checkbox" name="ORDER_PROP_<?=$prop['ID']?>" id="ORDER_PROP_<?=$prop['ID']?>" type="checkbox" value="Y"<?if(!empty($prop['VALUE']))echo ' checked';?>>
                                    <label for="ORDER_PROP_<?=$prop['ID']?>"><?=$prop['NAME']?></label>
                                </div>
                            </div>
                            <div>
                                <div class="styled-checkbox__block">
                                    <input class="styled-checkbox" name="ORDER_PROP_recipient" id="ORDER_PROP_recipient" type="checkbox" value="Y">
                                    <label for="ORDER_PROP_recipient">Я получатель</label>
                                    <span class="promo-tooltip promo-tooltip--question" data-toggle="tooltip" data-placement="bottom" title="Скрыть данные получателя">
                                        <svg class="" width="23px" height="23px">
                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg#question"></use>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?$prop = $arResult['ORDER_PROPS'][16];?>
                    <ul class="promo-order__social" id="social-wrap">
                        <?foreach ( $prop['VARIANTS'] as $code => $name ) {?>
                            <li>
                                <div class="promo-order__social__btn styled-checkbox__block">
                                    <input name="ORDER_PROP_<?=$prop['ID']?>" class="styled-checkbox" id="add_<?=$code?>" type="radio" value="<?=$code?>"<?if( $prop['VALUE'] == $code )echo ' checked';?>>

                                    <label for="add_<?=$code?>">
                                        <svg class="promo-order__social__icon">
                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=1.1#social-<?=$code?>"></use>
                                        </svg>

                                        <span class="promo-order__social__text"><?=$name?></span>
                                    </label>
                                </div>
                            </li>
                        <? } ?>
                    </ul>

                    <div class="promo-order__recipient" id="recipient">
                        <div class="head-h3">Данные получателя</div>

                        <div class="promo-order__row">
                            <?$prop = $arResult['ORDER_PROPS'][8];?>
                            <div class="promo-order__block">
                                <div class="input__wrapper input__wrapper--mark">
                                    <input type="text" class="input phonemask" id="ORDER_PROP_<?=$prop['ID']?>" name="ORDER_PROP_<?=$prop['ID']?>" value="<?= !empty($prop['VALUE']) ? $prop['VALUE'] : '+7' ?>" placeholder="+7">
                                </div>
                            </div>

                            <?$prop = $arResult['ORDER_PROPS'][9];?>
                            <div class="promo-order__block">
                                <div class="input__wrapper input__wrapper--mark">
                                    <input type="text" class="input" id="ORDER_PROP_<?=$prop['ID']?>" name="ORDER_PROP_<?=$prop['ID']?>" value="<?=$prop['VALUE']?>" placeholder="Имя">
                                </div>
                            </div>

                            <?$prop = $arResult['ORDER_PROPS'][14];?>
                            <div class="promo-order__block promo-order__block--full hidden" id="write_comment">
                                <div class="styled-checkbox__block">
                                    <input class="styled-checkbox" name="ORDER_PROP_<?=$prop['ID']?>" id="ORDER_PROP_<?=$prop['ID']?>" type="checkbox" value="Y"<?if(!empty($prop['VALUE']))echo ' checked';?>>
                                    <label for="ORDER_PROP_<?=$prop['ID']?>"><?=$prop['NAME']?></label>
                                    <?if( !empty($prop['DESCRIPTION']) ) {?>
                                        <span class="promo-tooltip promo-tooltip--question" data-toggle="tooltip" data-placement="bottom" title="<?=$prop['DESCRIPTION']?>">
                                            <svg class="" width="23px" height="23px">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=1.1#question"></use>
                                            </svg>
                                        </span>
                                    <? } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?if ( !empty($arResult['VASE']) ):?>
                        <div class="promo-order__optional" id="vase-wrap" data-price="<?=$arResult['VASE']['PRICE']?>">
                            <div class="promo-order__item">
                                <div class="promo-order__item__img">
                                    <img src="<?=CFile::GetPath($arResult['VASE']['PREVIEW_PICTURE'])?>" alt="<?=$arResult['VASE']['NAME']?>">
                                </div>
                                <div class="promo-order__item__description">
                                    <p class="promo-order__item__title"><?=$arResult['VASE']['NAME']?></p>

                                    <div class="promo-order__item__description__text">
                                        <div class="promo-order__item__price">+<?=number_format($arResult['VASE']['PRICE'],0,'', ' ')?> <span class="rouble"></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?endif;?>

                    <div class="promo-order__row">
                        <div class="promo-order__block promo-order__block--full">
                            <div class="promo-order__comment">
                                <textarea name="COMMENT" class="textarea" placeholder="Комментарий к заказу" maxlength="400"></textarea>
                            </div>
                        </div>

                        <!-- Это сюрприз -->
                        <div class="promo-order__block promo-order__block--full">
                            <?if ( !empty($arResult['VASE']) ):?>
                                <?$prop = $arResult['ORDER_PROPS'][11];?>
                                <div class="styled-checkbox__block">
                                    <input class="styled-checkbox" name="ORDER_PROP_<?=$prop['ID']?>" id="ORDER_PROP_<?=$prop['ID']?>" type="checkbox" value="Y"<?if(!empty($prop['VALUE']))echo ' checked';?>>
                                    <label for="ORDER_PROP_<?=$prop['ID']?>"><?=$prop['NAME']?></label>
                                    <?if( !empty($prop['DESCRIPTION']) ) {?>
                                        <span class="promo-tooltip promo-tooltip--question" data-toggle="tooltip" data-placement="bottom" title="<?=$prop['DESCRIPTION']?>">
                                            <svg class="" width="23px" height="23px">
                                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=1.1#question"></use>
                                            </svg>
                                        </span>
                                    <? } ?>
                                </div>
                            <?endif;?>

                            <?$prop = $arResult['ORDER_PROPS'][10];?>
                            <div class="styled-checkbox__block">
                                <input class="styled-checkbox" name="ORDER_PROP_<?=$prop['ID']?>" id="ORDER_PROP_<?=$prop['ID']?>" type="checkbox" value="Y"<?if(!empty($prop['VALUE']))echo ' checked';?>>
                                <label for="ORDER_PROP_<?=$prop['ID']?>"><?=$prop['NAME']?></label>

                                <?if( !empty($prop['DESCRIPTION']) ) {?>
                                    <span class="promo-tooltip promo-tooltip--question" data-toggle="tooltip" data-placement="bottom" title="<?=$prop['DESCRIPTION']?>">
                                        <svg class="" width="23px" height="23px">
                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg#question"></use>
                                        </svg>
                                    </span>
                                <? } ?>
                            </div>

                            <!-- Добавить записку -->
                            <?$prop = $arResult['ORDER_PROPS'][12];?>
                            <div class="styled-checkbox__block">
                                <input class="styled-checkbox" name="ORDER_PROP_<?=$prop['ID']?>" id="ORDER_PROP_<?=$prop['ID']?>" type="checkbox" value="Y"<?if(!empty($prop['VALUE']))echo ' checked';?>>
                                <label for="ORDER_PROP_<?=$prop['ID']?>"><?=$prop['NAME']?></label>
                                <?if( !empty($prop['DESCRIPTION']) ) {?>
                                    <span class="promo-tooltip promo-tooltip--question" data-toggle="tooltip" data-placement="bottom" title="<?=$prop['DESCRIPTION']?>">
                                        <svg class="" width="23px" height="23px">
                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg#question"></use>
                                        </svg>
                                    </span>
                                <? } ?>
                            </div>
                        </div>
                    </div>

                    <?$prop = $arResult['ORDER_PROPS'][13];?>
                    <div class="promo-order__comment" id="note-wrap">
                        <textarea name="ORDER_PROP_<?=$prop['ID']?>" class="textarea" placeholder="Текст записки"><?=$prop['VALUE']?></textarea>
                    </div>

                    <div class="promo-order__submit">
                        <button class="btn btn__main" type="submit">Оплатить
                            <span class="js-order_total">
                            <?if( $arResult['PRODUCT']['OLD_PRICE'] > 0){?>
                                <span class="promo-order__submit__old-price" data-price="<?=$arResult['PRODUCT']['OLD_PRICE']?>"><?=number_format($arResult['PRODUCT']['OLD_PRICE'],0,'', ' ')?> <span class="rouble"></span></span>
                            <? } ?>
                            <span class="js-orderprice">
                                <?=number_format($arResult['PRODUCT']['PRICE'],0,'', ' ')?>  </span> <span class="rouble"></span>
                            </span>
                        </button>
                    </div>
                    <input type="hidden" name="order_send" value="Y">
                </form>
            </div>
        </div>
    </div>
<?else:?>
    <div class="modal fade" id="popup-flowers" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                            <a href="/" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/img/logo2.png" alt=""></a>
                        </div>
                        <p class="popup-promo__greate__description">
                            <?if ( $arResult['ORDER']['ID'] > 0 ):?>
                                <div class="head-h2">Спасибо! Ваш заказ №<?=$arResult['ORDER']['ID']?> принят.</div>
                                <p>Сейчас будет переход к оплате...</p>
                                <p style="font-size: 13px; padding-top: 25px;">Если этого не произошло, пожалуйста, нажмите на кнопку ниже:
                                    <?
                                    if ($arResult["ORDER"]["IS_ALLOW_PAY"] === 'Y')
                                    {
                                        if (!empty($arResult["PAYMENT"]))
                                        {
                                            foreach ($arResult["PAYMENT"] as $payment)
                                            {
                                                if ( $payment["PAID"] != 'Y' )
                                                {
                                                    if (!empty($arResult['PAY_SYSTEM_LIST'])
                                                        && array_key_exists($payment["PAY_SYSTEM_ID"], $arResult['PAY_SYSTEM_LIST'])
                                                    )
                                                    {
                                                        $arPaySystem = $arResult['PAY_SYSTEM_LIST'][$payment["PAY_SYSTEM_ID"]];

                                                        if (empty($arPaySystem["ERROR"]))
                                                        {
                                                        if (strlen($arPaySystem["ACTION_FILE"]) > 0 && $arPaySystem["NEW_WINDOW"] == "Y" && $arPaySystem["IS_CASH"] != "Y"):
                                                            $orderAccountNumber = urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]));
                                                            $paymentAccountNumber = $payment["ACCOUNT_NUMBER"];
                                                            ?>
                                                            <script>
                                                                window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=$orderAccountNumber?>&PAYMENT_ID=<?=$paymentAccountNumber?>');
                                                            </script>
                                                        <? else: ?>
                                                            <?=$arPaySystem["BUFFERED_OUTPUT"]?>
                                                        <? endif ?>

                                                            <script>setTimeout(function(){ window.location.href = document.forms[0].getAttribute('action') }, 1000);</script>
                                                            <?
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </p>
                            <?else:?>
                                <div class="head-h2">Извинте, заказ №<?=intval($_REQUEST['ORDER_ID'])?> не найден.</div>
                                <p></p>
                            <?endif;?>
                        </div>
                        <br/><br/>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?endif;?>