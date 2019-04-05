<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Ваш заказ успешно оплачен");
?>

<div class="modal fade" id="popup-flowers-success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

                    <div class="popup-promo__greate__description">
                        <div class="head-h2">Ваш заказ успешно оплачен!</div>
                        <p>Статус заказа вы получите СМС-сообщением</p>
                    </div>

                    <div class="popup-promo__greate__social">
                        <a href="#">
                            <svg width="64px" height="64px" class="popup-promo__greate__social__icon">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=1.1#instagram"></use>
                            </svg>
                        </a>

                        <p>Подпишитесь на наш инстаграм, чтобы видеть самые свежие букеты в нашей коллекции</p>
                    </div>

                    <?$posts = \PDV\Tools::getInstagramPosts();
                    if ( !empty($posts) ):?>
                        <div class="popup-promo__greate__img">
                            <div class="popup-promo__greate__img__row">
                                <?for ($i=0; $i<8; $i++){?>
                                    <div class="popup-promo__greate__img__block">
                                        <a href="<?=$posts['ITEMS'][$i]['LINK']?>" target="_blank">
                                            <img src="<?=$posts['ITEMS'][$i]['IMAGE']?>" alt="">
                                        </a>
                                    </div>
                                <? } ?>
                            </div>
                            <div class="popup-promo__greate__img__row">
                                <?for ($i=8; $i<16; $i++){?>
                                    <div class="popup-promo__greate__img__block">
                                        <a href="<?=$posts['ITEMS'][$i]['LINK']?>" target="_blank">
                                            <img src="<?=$posts['ITEMS'][$i]['IMAGE']?>" alt="">
                                        </a>
                                    </div>
                                <? } ?>
                            </div>
                        </div>
                    <?endif;?>
                </div>
            </div>
        </div>
    </div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>