<?
$USER_DELIVERY_ID = intval(\Bitrix\Main\Context::getCurrent()->getRequest()->getCookie('USER_DELIVERY_ID'));
if ( $USER_DELIVERY_ID == 0 ):
?>
    <div class="modal fade" id="popup-login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog--mini">
            <div class="modal-content">
                <div class="popup-promo popup-promo--login">
                    <div class="popup-promo__close mobile-menu active" data-dismiss="modal" aria-hidden="true">
                        <span></span>
                    </div>

                    <div class="popup-promo__content">
                        <p class="popup-promo__text">Скажите куда вы доставить цветы, мы покажем ближайшее время доставки каждого букета</p>

                        <?$arrDeliv = \PDV\Tools::getDeliveryTime();?>
                        <ul class="modal-login">
                            <?
                            $lastDelivId = 0;
                            $i = 0;
                            $count = count($arrDeliv);
                            foreach ( $arrDeliv as $id => $deliv) {
                                if ( $i < $count-1 ) {
                            ?>
                                    <li>
                                        <a href="#" class="btn btn__main js-set_delivery" data-id="<?=$id?>" data-time="<?=$deliv['TIME']?>"><?=$deliv['NAME']?></a>
                                    </li>
                            <?
                                }
                                $lastDelivId = $id;
                                $i++;
                            } ?>
                        </ul>

                        <p class="popup-promo__text popup-promo__text--mini">Доставка за МКАД :(</p>

                        <?if ( $lastDelivId > 0 ){?>
                            <a href="#" class="btn btn__promo popup-promo__submit js-set_delivery" data-id="<?=$lastDelivId?>" data-time="<?=$arrDeliv[$lastDelivId]['TIME']?>"><?=$arrDeliv[$lastDelivId]['NAME']?></a>
                        <? } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?endif;?>

<div class="modal fade product-modal" id="popup-product" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <button class="modal-close" type="button" data-dismiss="modal" aria-label="Close">
                <svg width="24px" height="24px">
                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=1.2#cross"></use>
                </svg>
            </button>
            <div class="product-info js-body"></div>
        </div>
    </div>
</div>
