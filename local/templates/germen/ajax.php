<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
set_time_limit(0);
use \Bitrix\Main\Loader,
	\Bitrix\Main\Application,
	\Bitrix\Main\Web\Cookie;
global $USER, $APPLICATION;

$request = Application::getInstance()->getContext()->getRequest();

$action = htmlspecialchars($request->getPost('action'));
$id = intval($request->getPost('id'));
$order = htmlspecialchars($request->getPost('order'));

$result = array(
    'data' => '',
    'error' => false
);
switch ( $action ) {
    case 'setdelivery':
        if ( $id > 0 ) {
            $context = Application::getInstance()->getContext();

            $cookie = new Cookie('USER_DELIVERY_ID', $id);
            $cookie->setDomain(SITE_SERVER_NAME);
            $cookie->setHttpOnly(false);

            $context->getResponse()->addCookie($cookie);
            $context->getResponse()->flush("");

            $arrDeliv = \PDV\Tools::getDeliveryTime();
            $result['data'] = $arrDeliv[$id];
        }
        break;
    case 'prod_detail':
        if ( $id > 0 ) {
            Loader::includeModule('catalog');
            $arProd = \CCatalogProduct::GetByIDEx($id);
            if ( $arProd ) {
                $deliveryTime = \PDV\Tools::getTimeByDelivery();

                $images = array();
                $images[] = $arProd['PREVIEW_PICTURE'];
                $res = CIBlockElement::GetProperty(
                    IBLOCK_ID__CATALOG,
                    $arProd['ID'],
                    array('sort' => 'asc'),
                    array('CODE' => 'IMAGES')
                );
                while ( $arres = $res->GetNext() )
                {
                    if ( !empty($arres['VALUE']) )
                        $images[] = $arres['VALUE'];
                }

                if ( !empty($images) ) {
                    $result['data'] .= '<div class="product-info__gallery"><div class="product-slider" data-product-slider="">';
                    foreach ( $images as $img ) {
                        /*$result['data'] .= '<div class="product-slider__item">
                            <div class="type-label">
                                <svg width="16px" height="13px">
                                    <use xlink:href="'.SITE_TEMPLATE_PATH.'/icons/icons.svg#aroma"></use>
                                </svg>
                                Ароматная роза
                            </div>
                            <img data-lazy="' . CFile::GetPath($img) .'" alt="">
                        </div>';*/
                        $result['data'] .= '<div class="product-slider__item">
                            <img data-lazy="' . CFile::GetPath($img) .'" alt="">
                        </div>';
                    }
                    $result['data'] .= '</div></div>';
                }
                $result['data'] .= '<div class="product-info__content"><div class="product-info__content-inner">
                    <div class="product-info__header">
                        <h2 class="product-info__title">
                            '.$arProd['NAME'].'
                        </h2>
                        <p class="product-info__text">'.$arProd['PREVIEW_TEXT'].'</p>
                    </div>
                    <div class="product-info__row product-info__row--baseline">
                        <div class="product-info__cell">
                            <span class="product-info__price">
                                '.number_format($arProd['PRICES'][1]['PRICE'],0,'', ' ').' <span class="product-info__price-currency">руб</span>
                            </span>
                        </div>
                        <div class="product-info__cell">
                            <div class="payment-systems">
                                <div class="payment-systems__item" aria-label="Оплата по счёту">
                                    <svg class="payment-systems__icon payment-systems__icon--invoice">
                                        <use xlink:href="'.SITE_TEMPLATE_PATH.'/icons/icons.svg#payment-invoice"></use>
                                    </svg>
                                </div>
                                <div class="payment-systems__item" aria-label="MasterCard">
                                    <svg class="payment-systems__icon payment-systems__icon--mastercard">
                                        <use xlink:href="'.SITE_TEMPLATE_PATH.'/icons/icons.svg#payment-mastercard"></use>
                                    </svg>
                                </div>
                                <div class="payment-systems__item" aria-label="Visa">
                                    <svg class="payment-systems__icon payment-systems__icon--visa" width="49" height="17" viewBox="0 0 49 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path id="Vector" d="M18.6774 0.737724L12.288 15.9773H8.13382L4.99559 3.8308C4.81497 3.08575 4.63435 2.81483 4.06992 2.49875C3.0991 1.97947 1.54127 1.48277 0.164062 1.18927L0.254371 0.737724H6.95981C7.81774 0.737724 8.58537 1.30215 8.76598 2.29555L10.4367 11.1007L14.5458 0.737724H18.6774ZM35.0007 11.0104C35.0233 6.99162 29.4467 6.76584 29.4693 4.98224C29.4919 4.44039 30.0111 3.85338 31.14 3.69534C31.7044 3.62761 33.2623 3.55988 35.0233 4.37266L35.7232 1.14411C34.7749 0.805455 33.5558 0.466797 32.0431 0.466797C28.1598 0.466797 25.4054 2.5439 25.3828 5.50152C25.3602 7.69151 27.347 8.91068 28.8371 9.65573C30.3724 10.4008 30.8917 10.8749 30.8917 11.5522C30.8691 12.5682 29.6725 13.0197 28.5436 13.0423C26.5568 13.0649 25.4054 12.5005 24.5023 12.0715L23.7798 15.4129C24.7055 15.8419 26.3988 16.2031 28.1598 16.2257C32.2915 16.2257 34.9781 14.1712 35.0007 11.0104ZM45.2959 15.9773H48.9309L45.7475 0.737724H42.3835C41.6158 0.737724 40.9837 1.16669 40.7128 1.84401L34.7975 15.9548H38.9291L39.7419 13.6745H44.7992L45.2959 15.9773ZM40.8934 10.5814L42.9705 4.86936L44.1671 10.5814H40.8934ZM24.2991 0.737724L21.048 15.9773H17.1196L20.3707 0.737724H24.2991Z" fill="url(#paint0_linear)"/>
                                        <defs>
                                            <linearGradient id="paint0_linear" x1="0.164062" y1="8.34153" x2="48.9309" y2="8.34153" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#24215B"/>
                                                <stop offset="1" stop-color="#0C509F"/>
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>';

                    if ( $order != 'Y' )
                        $result['data'] .= '<div class="product-info__action">
                            <a class="promo-item__delivery product-info__order-button" href="/order/?id='.$arProd['ID'].'">
                                <div class="promo-item__delivery__text">Заказать</div>
                                <div class="promo-item__delivery__time">
                                    <div class="promo-item__delivery__time__text">'.$deliveryTime.'</div>
                                </div>
                            </a>
                        </div>';

                    $result['data'] .= '<div class="product-info__features">
                        <div class="product-info__feature">
                            <div class="product-info__feature-icon">
                                <svg width="26px" height="22px">
                                    <use xlink:href="'.SITE_TEMPLATE_PATH.'/icons/icons.svg#feature-car"></use>
                                </svg>
                            </div>
                            <h5 class="product-info__feature-title">Бесплатная доставка</h5>
                            <p class="product-info__feature-text">Бесплатно и&nbsp;быстро доставим букет&nbsp;&mdash; от&nbsp;60 минут в&nbsp;пределах МКАД.*</p>
                            <p class="product-info__feature-note"><span class="product-info__feature-note-star">*</span> Доставка за&nbsp;МКАД&nbsp;&mdash; 500&nbsp;руб</p>
                        </div>
                        <div class="product-info__feature">
                            <div class="product-info__feature-icon">
                                <svg width="19px" height="26px">
                                    <use xlink:href="'.SITE_TEMPLATE_PATH.'/icons/icons.svg#feature-paper"></use>
                                </svg>
                            </div>
                            <h5 class="product-info__feature-title">Открытка в&nbsp;подарок</h5>
                            <p class="product-info__feature-text">К&nbsp;каждому букету дарим открытку в&nbsp;конверте, подписанную от&nbsp;вашего имени.</p>
                        </div>
                        <div class="product-info__feature">
                            <div class="product-info__feature-icon">
                                <svg width="26px" height="22px">
                                    <use xlink:href="'.SITE_TEMPLATE_PATH.'/icons/icons.svg#feature-camera"></use>
                                </svg>
                            </div>
                            <h5 class="product-info__feature-title">Контроль на&nbsp;каждом этапе</h5>
                            <p class="product-info__feature-text">Пришлем фото всех цветов на&nbsp;базе, сфотографируем букет и&nbsp;подписанную открытку перед отправкой.</p>
                        </div>
                        <div class="product-info__feature">
                            <div class="product-info__feature-icon">
                                <svg width="20px" height="26px">
                                    <use xlink:href="'.SITE_TEMPLATE_PATH.'/icons/icons.svg#feature-badge"></use>
                                </svg>
                            </div>
                            <h5 class="product-info__feature-title">Гарантия качества</h5>
                            <p class="product-info__feature-text">Возвращаем деньги, если цветы окажутся несвежими или завянут в&nbsp;день доставки.</p>
                        </div>
                    </div>';

                $result['data'] .= '</div></div>';
            }
        }

        break;
}

echo json_encode($result);
?>