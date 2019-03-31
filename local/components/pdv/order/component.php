<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

use \Bitrix\Main\Loader,
    \Bitrix\Main\Application,
    \Bitrix\Sale\Internals\OrderPropsTable,
    \Bitrix\Sale,
    \Bitrix\Sale\Order,
    \Bitrix\Sale\Internals\BasketTable,
    \Bitrix\Sale\PaySystem,
    \Bitrix\Main\Context,
	\Bitrix\Main\Web\Cookie,
    \Bitrix\Sale\DiscountCouponsManager;

global $USER, $APPLICATION;
Loader::includeModule('sale');

$request = Application::getInstance()->getContext()->getRequest();
$couponEnter = $request->getPost('coupon');
$sendOrder = $request->getPost('order_send') == 'Y';

$notIssetProd = true;
$id = intval( $arParams['ID'] );
$orderId = intval( $request->get('ORDER_ID') );
$arResult["ORDER"] = array();
$arResult["CONFIRM"] = false;

if ( isset($couponEnter) ):
    global $APPLICATION;
    $APPLICATION->RestartBuffer();

    DiscountCouponsManager::init();
    DiscountCouponsManager::clear(true);

    $result = array();
    $result['PRICE'] = 0;
    $result['OLD_PRICE'] = 0;
    $result['FULL_PRICE'] = 0;
    $result['OLD_FULL_PRICE'] = 0;
    if ( !empty($couponEnter) ) {
        DiscountCouponsManager::add($couponEnter);
        $coupons = DiscountCouponsManager::get(true, array(), true, true);
        if ( !empty($coupons) )
        {
            foreach ( $coupons as $coupon )
            {
                $result = $coupon;
                if ( $coupon['STATUS'] == DiscountCouponsManager::STATUS_NOT_FOUND || $coupon['STATUS'] == DiscountCouponsManager::STATUS_FREEZE )
                {
                    $result['STATUS_ENTER'] = 'BAD';
                }
            }
            unset($coupon);
        }
    }

    $siteId = Context::getCurrent()->getSite();
    $order = Order::create($siteId, REGISTER_USER_ID__DEFAULT);
    $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), $siteId);
    $order->setBasket($basket);
    $order->doFinalAction(true);

    foreach ( $basket as $basketItem ) {
        $price = $basketItem->getPrice();
        $discPrice = $basketItem->getDiscountPrice();

        if ( $basketItem->getField('PRODUCT_ID') == $id ) {
            $result['PRICE'] = $price;
            if ( $discPrice > 0 )
                $result['OLD_PRICE'] = $price + $discPrice;
        }
    }

    echo json_encode( $result );

    die();
elseif ( $orderId > 0 ):
    $arResult["CONFIRM"] = true;
    $notIssetProd = false;

    $registry = Sale\Registry::getInstance(Sale\Registry::REGISTRY_TYPE_ORDER);
    /** @var Order $orderClassName */
    $orderClassName = $registry->getOrderClassName();

    /** @var Order $order */
    if ($order = $orderClassName::loadByAccountNumber($orderId))
    {
        $arOrder = $order->getFieldValues();
        $arResult["ORDER_ID"] = $arOrder["ID"];
        $arOrder["IS_ALLOW_PAY"] = $order->isAllowPay()? 'Y' : 'N';
    }

    if ( !empty($arOrder) )
    {
        $arResult["PAYMENT"] = array();
        if ( $order->isAllowPay() )
        {
            $paymentCollection = $order->getPaymentCollection();
            /** @var Payment $payment */
            foreach ($paymentCollection as $payment)
            {
                $arResult["PAYMENT"][$payment->getId()] = $payment->getFieldValues();

                if (intval($payment->getPaymentSystemId()) > 0 && !$payment->isPaid())
                {
                    $paySystemService = PaySystem\Manager::getObjectById($payment->getPaymentSystemId());
                    if (!empty($paySystemService))
                    {
                        $arPaySysAction = $paySystemService->getFieldsValues();

                        if ($paySystemService->getField('NEW_WINDOW') === 'N' || $paySystemService->getField('ID') == PaySystem\Manager::getInnerPaySystemId())
                        {
                            /** @var PaySystem\ServiceResult $initResult */
                            $initResult = $paySystemService->initiatePay($payment, null, PaySystem\BaseServiceHandler::STRING);
                            if ($initResult->isSuccess())
                                $arPaySysAction['BUFFERED_OUTPUT'] = $initResult->getTemplate();
                            else
                                $arPaySysAction["ERROR"] = $initResult->getErrorMessages();
                        }

                        $arResult["PAYMENT"][$payment->getId()]['PAID'] = $payment->getField('PAID');

                        $arOrder['PAYMENT_ID'] = $payment->getId();
                        $arOrder['PAY_SYSTEM_ID'] = $payment->getPaymentSystemId();
                        $arPaySysAction["NAME"] = htmlspecialcharsEx($arPaySysAction["NAME"]);
                        $arPaySysAction["IS_AFFORD_PDF"] = $paySystemService->isAffordPdf();

                        if ($arPaySysAction > 0)
                            $arPaySysAction["LOGOTIP"] = CFile::GetFileArray($arPaySysAction["LOGOTIP"]);

                        if ($this->arParams['COMPATIBLE_MODE'] == 'Y' && !$payment->isInner())
                        {
                            // compatibility
                            \CSalePaySystemAction::InitParamArrays($order->getFieldValues(), $order->getId(), '', array(), $payment->getFieldValues());
                            $map = CSalePaySystemAction::getOldToNewHandlersMap();
                            $oldHandler = array_search($arPaySysAction["ACTION_FILE"], $map);
                            if ($oldHandler !== false && !$paySystemService->isCustom())
                                $arPaySysAction["ACTION_FILE"] = $oldHandler;

                            if (strlen($arPaySysAction["ACTION_FILE"]) > 0 && $arPaySysAction["NEW_WINDOW"] != "Y")
                            {
                                $pathToAction = Main\Application::getDocumentRoot().$arPaySysAction["ACTION_FILE"];

                                $pathToAction = str_replace("\\", "/", $pathToAction);
                                while (substr($pathToAction, strlen($pathToAction) - 1, 1) == "/")
                                    $pathToAction = substr($pathToAction, 0, strlen($pathToAction) - 1);

                                if (file_exists($pathToAction))
                                {
                                    if (is_dir($pathToAction) && file_exists($pathToAction."/payment.php"))
                                        $pathToAction .= "/payment.php";

                                    $arPaySysAction["PATH_TO_ACTION"] = $pathToAction;
                                }
                            }

                            $arResult["PAY_SYSTEM"] = $arPaySysAction;
                        }

                        $arResult["PAY_SYSTEM_LIST"][$payment->getPaymentSystemId()] = $arPaySysAction;
                    }
                    else
                        $arResult["PAY_SYSTEM_LIST"][$payment->getPaymentSystemId()] = array('ERROR' => true);
                }
            }
        }

        $arResult["ORDER"] = $arOrder;
    }
    else
        $arResult["ACCOUNT_NUMBER"] = $orderId;

    $this->includeComponentTemplate();
elseif ( $id > 0 ):
    Loader::includeModule('catalog');

    $arResult['COUPON'] = array();
    $coupons = DiscountCouponsManager::get(true, array(), true, true);
    if ( !empty($coupons) )
    {
        foreach ( $coupons as $coupon )
        {
            $arResult['COUPON'] = $coupon;
            if ( $coupon['STATUS'] == DiscountCouponsManager::STATUS_NOT_FOUND || $coupon['STATUS'] == DiscountCouponsManager::STATUS_FREEZE )
            {
                $arResult['COUPON']['STATUS_ENTER'] = 'BAD';
            }
        }
        unset($coupon);
    }

    $arProd = \CCatalogProduct::GetByIDEx($id);
    if ( $arProd ) {
        $notIssetProd = false;

        $arResult['COUNT_DATE_DELIVERY'] = 1;
        if ( $arProd['IBLOCK_ID'] == IBLOCK_ID__SUBSCRIBE ) {
            if ( intval($arProd['PREVIEW_TEXT']) > 0 )
                $arResult['COUNT_DATE_DELIVERY'] = intval($arProd['PREVIEW_TEXT']);
        }

        $needPropdAdd = true;
        $siteId = Context::getCurrent()->getSite();
        $price = 0;
        $oldPrice = 0;
        $dbBasket = BasketTable::getList(array(
            'filter' => array(
                'FUSER_ID' => Sale\Fuser::getId(),
                'ORDER_ID' => null,
                'LID' => $siteId,
                'CAN_BUY' => 'Y',
            ),
            'select' => array('ID', 'PRODUCT_ID', 'PRICE', 'DISCOUNT_PRICE'),
        ));
        while ( $arBasket = $dbBasket->Fetch() ) {
            if ( $arBasket['PRODUCT_ID'] != $id )
                \CSaleBasket::Delete( $arBasket['ID'] );
            else {
                $price = $arBasket['PRICE'];
                $oldPrice = 0;
                if ( $arBasket['DISCOUNT_PRICE'] > 0 )
                    $oldPrice = $arBasket['PRICE'] + $arBasket['DISCOUNT_PRICE'];

                \CSaleBasket::Update( $arBasket['ID'], ['QUANTITY' => 1] );

                $needPropdAdd = false;
            }
        }

        if ( $needPropdAdd )
            Add2BasketByProductID($id);

        $order = Order::create($siteId, REGISTER_USER_ID__DEFAULT);
        $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), $siteId);
        $order->setBasket($basket);
        $order->doFinalAction(true);

        foreach ( $basket as $basketItem ) {
            if ( $basketItem->getField('PRODUCT_ID') == $id ) {
                $price = $basketItem->getPrice();
                $discPrice = $basketItem->getDiscountPrice();
                if ( $discPrice > 0)
                    $oldPrice = $price + $discPrice;
            }
        }

        $renderImage = CFile::ResizeImageGet($arProd['PREVIEW_PICTURE'], Array("width" => 300, "height" => 300));
        $pic = $renderImage['src'];
        unset($renderImage);

        $arResult['PRODUCT'] = array(
            'ID' => $arProd['ID'],
            'NAME' => $arProd['NAME'],
            'PICTURE' => $pic,
            'PRICE' => $price,
            'OLD_PRICE' => $oldPrice
        );

        $arResult['VASE'] = array();
        $rsElem = CIBlockElement::GetList(
            array('sort' => 'asc', 'id' => 'desc'),
            array('IBLOCK_ID' => IBLOCK_ID__VASE, 'ACTIVE' => 'Y'),
            false,
            array('nPageSize' => 1),
            array('ID', 'NAME', 'PREVIEW_PICTURE')
        );
        if ( $arElem = $rsElem->GetNext() ) {
            $arPrice = CCatalogProduct::GetOptimalPrice($arElem['ID'], 1, $USER->GetUserGroupArray());
            $arElem['PRICE'] = $arPrice['DISCOUNT_PRICE'];

            $arResult['VASE'] = $arElem;
        }

        $arResult['ERROR'] = array();
        $arResult['PERSON_TYPE_ID'] = 0;
        $arResult['ORDER_PROPS'] = array();
        $dbProperties = OrderPropsTable::getList(
            array(
                'order' => array('SORT' => 'ASC'),
                'filter' => array('ACTIVE' => 'Y'),
                'select' => array('ID', 'NAME', 'CODE', 'TYPE', 'DESCRIPTION', 'PERSON_TYPE_ID', 'IS_LOCATION', 'REQUIRED')
            )
        );
        while ( $arProperty = $dbProperties->fetch() ) {
            if ( $arResult['PERSON_TYPE_ID'] == 0 )
                $arResult['PERSON_TYPE_ID'] = $arProperty['PERSON_TYPE_ID'];

            if ( $arProperty['IS_LOCATION'] == 'Y' )
                $arProperty['VALUE'] = LOCATION_ID__DEFAULT;
            else
                $arProperty['VALUE'] = $request->getPost('ORDER_PROP_'.$arProperty['ID']);

            $arProperty['ERROR'] = false;
            if ( $arProperty['REQUIRED'] == 'Y' && empty($arProperty['VALUE']) && $sendOrder )
                $arResult['ERROR'][] = 'Заполните поле "'.$arProperty['NAME'].'"';

            $arProperty['VARIANTS'] = array();
            if ( $arProperty['TYPE'] == 'ENUM' ) {
                $db_vars = CSaleOrderPropsVariant::GetList(
                    array('SORT' => 'ASC'),
                    array('ORDER_PROPS_ID' => $arProperty['ID'])
                );
                while ( $vars = $db_vars->Fetch() )
                {
                    $arProperty['VARIANTS'][ $vars['VALUE'] ] = $vars['NAME'];
                }
            }

            $arResult['ORDER_PROPS'][ $arProperty['ID'] ] = $arProperty;
        }

        $deliveryId = 0;
        $deliveryName = '';
        $deliveryTime = 0;
        $idDeliveryId = intval(Context::getCurrent()->getRequest()->getCookie('USER_DELIVERY_ID'));
        $arrDeliv = \PDV\Tools::getDeliveryTime();
        if ( $idDeliveryId > 0 && isset($arrDeliv[$id]['NAME']) ) {
            $deliveryId = $idDeliveryId;
            $deliveryName = $arrDeliv[$idDeliveryId]['NAME'];
            $deliveryTime = $arrDeliv[$idDeliveryId]['TIME_NUMBER'];
        }

        if ( $deliveryId == 0 ) {
            $deliveryId = DELIVERY_ID__DEFAULT;
            $deliveryName = $arrDeliv[DELIVERY_ID__DEFAULT]['NAME'];
            $deliveryTime = $arrDeliv[DELIVERY_ID__DEFAULT]['TIME_NUMBER'];

            $context = Application::getInstance()->getContext();
            $cookie = new Cookie('USER_DELIVERY_ID', DELIVERY_ID__DEFAULT);
            $cookie->setDomain(SITE_SERVER_NAME);
            $cookie->setHttpOnly(false);
            $context->getResponse()->addCookie($cookie);
        }

        $arResult['DELIVERY_TIME'] = $deliveryTime;

        if ( $sendOrder ) {
            if ( !empty($arResult['ORDER_PROPS'][15]['VALUE']) )
                $arResult['ORDER_PROPS'][2]['VALUE'] = '';
            elseif ( empty($arResult['ORDER_PROPS'][15]['VALUE']) && empty($arResult['ORDER_PROPS'][2]['VALUE']) )
                $arResult['ERROR'][] = 'Заполните поле "'.$arResult['ORDER_PROPS'][2]['NAME'].'"';
        }

        if ( $sendOrder && empty($arResult['ERROR']) ) {
            //Создание заказа
            if ( !empty($arResult['ORDER_PROPS'][11]['VALUE']) )
                Add2BasketByProductID($arResult['VASE']['ID']);

            $order = Order::create($siteId, REGISTER_USER_ID__DEFAULT);
            $order->setPersonTypeId( $arResult['PERSON_TYPE_ID'] );

            $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), $siteId)->getOrderableItems();

            $order->setBasket($basket);

            $shipmentCollection = $order->getShipmentCollection();
            $shipment = $shipmentCollection->createItem();

            $shipment->setFields(array(
                'DELIVERY_ID' => $deliveryId,
                'DELIVERY_NAME' => $deliveryName,
                'CURRENCY' => $order->getCurrency()
            ));
            $shipmentItemCollection = $shipment->getShipmentItemCollection();

            foreach ( $order->getBasket() as $item )
            {
                $shipmentItem = $shipmentItemCollection->createItem($item);
                $shipmentItem->setQuantity($item->getQuantity());
            }

            $paymentCollection = $order->getPaymentCollection();
            $extPayment = $paymentCollection->createItem();
            $extPayment->setFields(array(
                'PAY_SYSTEM_ID' => PAYMENT_ID__DEFAULT,
                'PAY_SYSTEM_NAME' => PAYMENT_NAME__DEFAULT,
                'SUM' => $order->getPrice()
            ));

            $order->doFinalAction(true);

            $propertyCollection = $order->getPropertyCollection();
            foreach ( $propertyCollection->getGroups() as $group )
            {
                foreach ( $propertyCollection->getGroupProperties($group['ID']) as $property )
                {
                    $p = $property->getProperty();

                    if ( $arResult['ORDER_PROPS'][ $p['ID'] ]['IS_LOCATION'] == 'Y' ) {
                        $codeValue = CSaleLocation::getLocationCODEbyID($arResult['ORDER_PROPS'][ $p['ID'] ]['VALUE']);
                        if ( $propertyDel = $propertyCollection->getDeliveryLocation() )
                            $propertyDel->setValue($codeValue);
                    }
                    elseif ( !empty($arResult['ORDER_PROPS'][ $p['ID'] ]['VALUE']) )
                        $property->setValue( $arResult['ORDER_PROPS'][ $p['ID'] ]['VALUE'] );
                }
            }

            $order->setField('CURRENCY', $order->getCurrency());
            $order->setField('COMMENTS', $request->getPost('COMMENT'));
            $order->save();

            DiscountCouponsManager::init();
            DiscountCouponsManager::clear(true);

            $orderId = intval($order->GetId());
            if ( $orderId > 0 ) {
                $APPLICATION->RestartBuffer();
                echo $orderId;
                die();
            }
        }

        $this->includeComponentTemplate();
    }
endif;

if ( $notIssetProd )
    LocalRedirect('/');
?>