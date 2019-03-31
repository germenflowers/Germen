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
                    $result['data'] .= '<div class="popup-promo__slider"><div class="promo-product">';
                    foreach ( $images as $img ) {
                        $result['data'] .= '<div class="promo-product__item">
                            <div class="promo-product__img">
                                <img src="' . CFile::GetPath($img) .'" alt="">
                            </div>
                        </div>';
                    }
                    $result['data'] .= '</div></div>';
                }
                $result['data'] .= '<div class="popup-promo__content">
                    <div class="popup-promo__description">
                        <div class="popup-promo__title">
                            '.$arProd['NAME'].' <strong class="popup-promo__price">'.number_format($arProd['PRICES'][1]['PRICE'],0,'', ' ').' <span class="rouble"></span></strong>
                        </div>
                        <div class="popup-promo__delivery">
                            <div>'.$arProd['PREVIEW_TEXT'].'</div>
                            Самая быстрая доставка — '.$deliveryTime.'
                        </div>
                    </div>';
                
                    if ( $order != 'Y' )
                        $result['data'] .= '<div class="popup-promo__btn popup-promo__btn--desktop">
                            <a href="/order/?id='.$arProd['ID'].'" class="btn btn__main">Заказать</a>
                        </div>';

                $result['data'] .= '</div>';

                if ( $order != 'Y' )
                    $result['data'] .= '<div class="popup-promo__btn popup-promo__btn--mobile">
                        <a href="/order/?id='.$arProd['ID'].'" class="btn btn__main">Заказать</a>
                    </div>';
            }
        }

        break;
}

echo json_encode($result);
?>