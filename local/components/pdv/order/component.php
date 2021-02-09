<?php

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;
use \Bitrix\Sale\Internals\OrderPropsTable;
use \Bitrix\Sale;
use \Bitrix\Sale\Order;
use \Bitrix\Sale\Registry;
use \Bitrix\Sale\Internals\BasketTable;
use \Bitrix\Sale\PaySystem;
use \Bitrix\Sale\PaySystem\Manager as PaySystemManager;
use \Bitrix\Sale\PaySystem\BaseServiceHandler;
use \Bitrix\Main\Context;
use \Bitrix\Main\Web\Cookie;
use \Bitrix\Sale\DiscountCouponsManager;
use \PDV\Tools;

global $USER;
global $APPLICATION;

Loader::includeModule('sale');
Loader::includeModule('catalog');

$request = Application::getInstance()->getContext()->getRequest();
$couponEnter = $request->getPost('coupon');
$sendOrder = $request->getPost('order_send') === 'Y';

$params = $request->getQueryList();

$notIssetProd = true;
$id = (int)$arParams['ID'];
$orderId = (int)$request->get('ORDER_ID');
$arResult['ORDER'] = array();
$arResult['CONFIRM'] = false;

if (isset($couponEnter)) {
    global $APPLICATION;
    $APPLICATION->RestartBuffer();

    DiscountCouponsManager::init();
    DiscountCouponsManager::clear(true);

    $result = array();
    $result['PRICE'] = 0;
    $result['OLD_PRICE'] = 0;
    $result['FULL_PRICE'] = 0;
    $result['OLD_FULL_PRICE'] = 0;

    if (!empty($couponEnter)) {
        DiscountCouponsManager::add($couponEnter);
        $coupons = DiscountCouponsManager::get(true, array(), true, true);

        if (!empty($coupons)) {
            foreach ($coupons as $coupon) {
                $result = $coupon;
                if (
                    $coupon['STATUS'] === DiscountCouponsManager::STATUS_NOT_FOUND ||
                    $coupon['STATUS'] === DiscountCouponsManager::STATUS_FREEZE
                ) {
                    $result['STATUS_ENTER'] = 'BAD';
                }
            }
        }
    }

    $siteId = Context::getCurrent()->getSite();
    $order = Order::create($siteId, REGISTER_USER_ID__DEFAULT);
    $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), $siteId);
    $order->setBasket($basket);
    $order->doFinalAction(true);

    foreach ($basket as $basketItem) {
        $price = $basketItem->getPrice();
        $discPrice = $basketItem->getDiscountPrice();

        if ((int)$basketItem->getField('PRODUCT_ID') === (int)$id) {
            $result['PRICE'] = $price;

            if ($discPrice > 0) {
                $result['OLD_PRICE'] = $price + $discPrice;
            }
        }
    }

    $result["PERCENT"] = (($result['OLD_PRICE'] - $result['PRICE']) * 100) / $result['OLD_PRICE'];

    echo json_encode($result);
    die();
}

if ($orderId > 0) {
    $arResult['CONFIRM'] = true;
    $notIssetProd = false;

    $registry = Registry::getInstance(Registry::REGISTRY_TYPE_ORDER);
    $orderClassName = $registry->getOrderClassName();

    if ($order = $orderClassName::loadByAccountNumber($orderId)) {
        $arOrder = $order->getFieldValues();
        $arResult['ORDER_ID'] = $arOrder['ID'];
        $arOrder['IS_ALLOW_PAY'] = $order->isAllowPay() ? 'Y' : 'N';
    }

    if (!empty($arOrder)) {
        $arResult['PAYMENT'] = array();
        if ($order->isAllowPay()) {
            $paymentCollection = $order->getPaymentCollection();
            foreach ($paymentCollection as $payment) {
                $arResult['PAYMENT'][$payment->getId()] = $payment->getFieldValues();

                if ((int)$payment->getPaymentSystemId() > 0 && !$payment->isPaid()) {
                    $paySystemService = PaySystemManager::getObjectById($payment->getPaymentSystemId());

                    if ($paySystemService !== null) {
                        $arPaySysAction = $paySystemService->getFieldsValues();

                        if (
                            $paySystemService->getField('NEW_WINDOW') === 'N' ||
                            (int)$paySystemService->getField('ID') === (int)PaySystemManager::getInnerPaySystemId()
                        ) {
                            $initResult = $paySystemService->initiatePay(
                                $payment,
                                null,
                                BaseServiceHandler::STRING
                            );
                            if ($initResult->isSuccess()) {
                                $arPaySysAction['BUFFERED_OUTPUT'] = $initResult->getTemplate();
                            } else {
                                $arPaySysAction['ERROR'] = $initResult->getErrorMessages();
                            }
                        }

                        $arResult['PAYMENT'][$payment->getId()]['PAID'] = $payment->getField('PAID');

                        $arOrder['PAYMENT_ID'] = $payment->getId();
                        $arOrder['PAY_SYSTEM_ID'] = $payment->getPaymentSystemId();
                        $arPaySysAction['NAME'] = htmlspecialcharsEx($arPaySysAction['NAME']);
                        $arPaySysAction['IS_AFFORD_PDF'] = $paySystemService->isAffordPdf();

                        if ($arPaySysAction > 0) {
                            $arPaySysAction['LOGOTIP'] = CFile::GetFileArray($arPaySysAction['LOGOTIP']);
                        }

                        if ($this->arParams['COMPATIBLE_MODE'] === 'Y' && !$payment->isInner()) {
                            $CSalePaySystemAction = new CSalePaySystemAction();

                            $CSalePaySystemAction->InitParamArrays(
                                $order->getFieldValues(),
                                $order->getId(),
                                '',
                                array(),
                                $payment->getFieldValues()
                            );

                            $map = CSalePaySystemAction::getOldToNewHandlersMap();
                            $oldHandler = array_search($arPaySysAction['ACTION_FILE'], $map, true);
                            if ($oldHandler !== false && !$paySystemService->isCustom()) {
                                $arPaySysAction['ACTION_FILE'] = $oldHandler;
                            }

                            if ($arPaySysAction['NEW_WINDOW'] !== 'Y' && strlen($arPaySysAction['ACTION_FILE']) > 0) {
                                $pathToAction = Application::getDocumentRoot().$arPaySysAction['ACTION_FILE'];

                                $pathToAction = str_replace('\\', '/', $pathToAction);
                                while ($pathToAction[strlen($pathToAction) - 1] === '/') {
                                    $pathToAction = substr($pathToAction, 0, -1);
                                }

                                if (file_exists($pathToAction)) {
                                    if (is_dir($pathToAction) && file_exists($pathToAction.'/payment.php')) {
                                        $pathToAction .= '/payment.php';
                                    }

                                    $arPaySysAction['PATH_TO_ACTION'] = $pathToAction;
                                }
                            }

                            $arResult['PAY_SYSTEM'] = $arPaySysAction;
                        }

                        $arResult['PAY_SYSTEM_LIST'][$payment->getPaymentSystemId()] = $arPaySysAction;
                    } else {
                        $arResult['PAY_SYSTEM_LIST'][$payment->getPaymentSystemId()] = array('ERROR' => true);
                    }
                }
            }
        }

        $arResult['ORDER'] = $arOrder;
    } else {
        $arResult['ACCOUNT_NUMBER'] = $orderId;
    }

    $this->includeComponentTemplate();
} elseif ($id > 0) {
    $arResult['COUPON'] = array();

    $coupons = DiscountCouponsManager::get(true, array(), true, true);
    if (!empty($coupons)) {
        foreach ($coupons as $coupon) {
            $arResult['COUPON'] = $coupon;

            if (
                $coupon['STATUS'] === DiscountCouponsManager::STATUS_NOT_FOUND ||
                $coupon['STATUS'] === DiscountCouponsManager::STATUS_FREEZE
            ) {
                $arResult['COUPON']['STATUS_ENTER'] = 'BAD';
            }
        }
    }

    $arProd = \CCatalogProduct::GetByIDEx($id);

    if ($arProd) {
        $arResult['ORDER_UPSALE_PRODUCTS'] = array();
        if (!empty(array_unique($params['upsale']))) {
            foreach (array_unique($params['upsale']) as $item) {
                $arResult['ORDER_UPSALE_PRODUCTS'][$item] = 1;
            }
        }

        $notIssetProd = false;
        $upSaleProducts = array();
        $bookMateProducts = array();

        $order = array('SORT' => 'ASC');
        $filter = array('IBLOCK_ID' => IBLOCK_ID__UPSALE, 'ACTIVE' => 'Y');
        $select = array(
            'IBLOCK_ID',
            'ID',
            'NAME',
            'PREVIEW_TEXT',
            'PREVIEW_PICTURE',
            'PROPERTY_IS_BOOKMATE',
        );
        $rsUpSaleProducts = \CIBlockElement::GetList($order, $filter, false, false, $select);
        while ($upSaleProduct = $rsUpSaleProducts->GetNext()) {
            $arPrice = \CCatalogProduct::GetOptimalPrice($upSaleProduct['ID'], 1, $USER->GetUserGroupArray());

            $pictureData = \CFile::GetFileArray($upSaleProduct['PREVIEW_PICTURE']);
            $retinaWidth = $pictureData['WIDTH'] > 290 ? 290 : ($pictureData['WIDTH'] - 1);
            $notRetinaWidth = $pictureData['WIDTH'] <= 145 ? ($pictureData['WIDTH'] - 1) : 145;

            $upSaleProduct['NOT_RETINA_PICTURE'] = \CFile::ResizeImageGet(
                $upSaleProduct['PREVIEW_PICTURE'],
                [
                    'width' => $notRetinaWidth,
                    'height' => $notRetinaWidth,
                ],
                BX_RESIZE_IMAGE_EXACT
            );

            $upSaleProduct['RETINA_PICTURE'] = \CFile::ResizeImageGet(
                $upSaleProduct['PREVIEW_PICTURE'],
                [
                    'width' => $retinaWidth,
                    'height' => $retinaWidth,
                ],
                BX_RESIZE_IMAGE_EXACT
            );

            $upSaleProduct['PRICE'] = $arPrice['DISCOUNT_PRICE'];

            if ($upSaleProduct['PROPERTY_IS_BOOKMATE_VALUE'] === 'Y') {
                $bookMateProducts[] = $upSaleProduct;
            } else {
                $upSaleProducts[] = $upSaleProduct;
            }
        }

        $arResult['UPSALE_PRODUCTS'] = $upSaleProducts;
        $arResult['BOOKMATE_PRODUCTS'] = $bookMateProducts;

        $arResult['COUNT_DATE_DELIVERY'] = 1;
        if ((int)$arProd['IBLOCK_ID'] === IBLOCK_ID__SUBSCRIBE_OFFERS) {
            if ((int)$arProd['PROPERTIES']['COUNT_DELIVERIES']['VALUE'] > 0) {
                $arResult['COUNT_DATE_DELIVERY'] = (int)$arProd['PROPERTIES']['COUNT_DELIVERIES']['VALUE'];
            }

            $filter = array('ID' => $arProd['PROPERTIES']['CML2_LINK']['VALUE']);
            $select = array('IBLOCK_ID', 'ID', 'NAME');
            $result = CIBlockElement::GetList(array(), $filter, false, false, $select);
            if ($row = $result->Fetch()) {
                $arProd['NAME'] = $row['NAME'];
            }
        }

        $needProductAdd = true;
        $siteId = Context::getCurrent()->getSite();
        $price = 0;
        $basePrice = 0;
        $oldPrice = 0;
        $mainProducts = $request->getPost('main_products');
        try {
            $mainProducts = json_decode($mainProducts, true);
        } catch (\Exception $e) {
            $mainProducts = [];
        }

        if ($sendOrder) {
            $mainAddQuantity = $mainProducts[$id];
        } elseif (!empty((int)$params['quantity'])) {
            $mainAddQuantity = (int)$params['quantity'];
        } else {
            $mainAddQuantity = 1;
        }

        $dbBasket = BasketTable::getList(
            array(
                'filter' => array(
                    'FUSER_ID' => Sale\Fuser::getId(),
                    'ORDER_ID' => null,
                    'LID' => $siteId,
                    'CAN_BUY' => 'Y',
                ),
                'select' => array('ID', 'PRODUCT_ID', 'PRICE', 'DISCOUNT_PRICE'),
            )
        );
        while ($arBasket = $dbBasket->Fetch()) {
            if ((int)$arBasket['PRODUCT_ID'] !== (int)$id) {
                \CSaleBasket::Delete($arBasket['ID']);
            } else {
                $price = $arBasket['PRICE'];
                $oldPrice = 0;
                if ($arBasket['DISCOUNT_PRICE'] > 0) {
                    $oldPrice = $arBasket['PRICE'] + $arBasket['DISCOUNT_PRICE'];
                }

                \CSaleBasket::Update($arBasket['ID'], ['QUANTITY' => $mainAddQuantity]);

                $needProductAdd = false;
            }
        }

        $mainAddQuantity = 0;
        if ($needProductAdd) {
            $basketId = (int)Add2BasketByProductID($id, $mainAddQuantity);

            if ($basketId > 0 && !empty($params['cover'])) {
                $value = 'Без упаковки';
                if($params['cover'] === 'craft') {
                    $value = 'Крафт';
                } elseif($params['cover'] === 'film') {
                    $value = 'Пленка';
                }

                $fields = array(
                    'PROPS' => array(
                        array(
                            'NAME' => 'Упаковка',
                            'CODE' => 'COVER',
                            'VALUE' => $value
                        )
                    ),
                );

                $CSaleBasket = new \CSaleBasket();
                $CSaleBasket->Update($basketId, $fields);
            }
        }

        $order = Order::create($siteId, REGISTER_USER_ID__DEFAULT);
        $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), $siteId);
        $order->setBasket($basket);
        $order->doFinalAction(true);

        foreach ($basket as $basketItem) {
            if ((int)$basketItem->getField('PRODUCT_ID') === (int)$id) {
                $price = $basketItem->getPrice();
                $basePrice = $basketItem->getBasePrice();
                $discPrice = $basketItem->getDiscountPrice();
                if ($discPrice > 0) {
                    $oldPrice = $price + $discPrice;
                }
            }
        }

        if (!empty($arResult['COUPON'])) {
            $arResult['COUPON']['PERCENT'] = $oldPrice / ($oldPrice - $price);
        }

        $renderImage = CFile::ResizeImageGet($arProd['PREVIEW_PICTURE'], array('width' => 300, 'height' => 300));
        $pic = $renderImage['src'];
        unset($renderImage);

        $arResult['PRODUCT'] = array(
            'ID' => $arProd['ID'],
            'NAME' => $arProd['NAME'],
            'PICTURE' => $pic,
            'PRICE' => (int)$basePrice,
            'QUANTITY' => 1,
            'FORMATTED_PRICE' => number_format($basePrice, 0, '', ' '),
            'ACTIVE_WEEK_DAYS' => $arProd['PROPERTIES']['ACTIVE_WEEK_DAYS'],
            'ACTIVE_START_SHIFT' => $arProd['PROPERTIES']['ACTIVE_START_SHIFT'],
            'ACTIVE_END_SHIFT' => $arProd['PROPERTIES']['ACTIVE_END_SHIFT'],
            'ACTIVE_END_DATE' => $arProd['PROPERTIES']['ACTIVE_END_DATE'],
        );

        if (!empty((int)$params['quantity'])) {
            $arResult['PRODUCT']['QUANTITY'] = (int)$params['quantity'];
        }

        if ((int)$arProd['IBLOCK_ID'] === IBLOCK_ID__SUBSCRIBE_OFFERS) {
            $arResult['PRODUCT']['type'] = $_REQUEST['type'];
            $arResult['PRODUCT']['delivery'] = $_REQUEST['delivery'];
        }

        $arResult['ERROR'] = array();
        $arResult['PERSON_TYPE_ID'] = 0;
        $arResult['ORDER_PROPS'] = array();
        $dbProperties = OrderPropsTable::getList(
            array(
                'order' => array('SORT' => 'ASC'),
                'filter' => array('ACTIVE' => 'Y'),
                'select' => array(
                    'ID',
                    'NAME',
                    'CODE',
                    'TYPE',
                    'DESCRIPTION',
                    'PERSON_TYPE_ID',
                    'IS_LOCATION',
                    'REQUIRED',
                ),
            )
        );
        while ($arProperty = $dbProperties->fetch()) {
            if ((int)$arResult['PERSON_TYPE_ID'] === 0) {
                $arResult['PERSON_TYPE_ID'] = $arProperty['PERSON_TYPE_ID'];
            }

            if ($arProperty['IS_LOCATION'] === 'Y') {
                $arProperty['VALUE'] = LOCATION_ID__DEFAULT;
            } else {
                $arProperty['VALUE'] = $request->getPost('ORDER_PROP_'.$arProperty['ID']);
            }

            $arProperty['ERROR'] = false;
            if ($arProperty['REQUIRED'] === 'Y' && empty($arProperty['VALUE']) && $sendOrder) {
                $arResult['ERROR'][] = 'Заполните поле "'.$arProperty['NAME'].'"';
            }

            $arProperty['VARIANTS'] = array();
            if ($arProperty['TYPE'] === 'ENUM') {
                $db_vars = CSaleOrderPropsVariant::GetList(
                    array('SORT' => 'ASC'),
                    array('ORDER_PROPS_ID' => $arProperty['ID'])
                );
                while ($vars = $db_vars->Fetch()) {
                    $arProperty['VARIANTS'][$vars['VALUE']] = $vars['NAME'];
                }
            }

            $arResult['ORDER_PROPS'][$arProperty['ID']] = $arProperty;
        }

        $deliveryId = 0;
        $deliveryName = '';
        $deliveryTime = 0;
        $idDeliveryId = (int)Context::getCurrent()->getRequest()->getCookie('USER_DELIVERY_ID');
        $arrDeliv = Tools::getDeliveryTime();
        if ($idDeliveryId > 0 && isset($arrDeliv[$id]['NAME'])) {
            $deliveryId = $idDeliveryId;
            $deliveryName = $arrDeliv[$idDeliveryId]['NAME'];
            $deliveryTime = $arrDeliv[$idDeliveryId]['TIME_NUMBER'];
        }

        if ((int)$deliveryId === 0) {
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

        if ($sendOrder) {
            if (!empty($arResult['ORDER_PROPS'][15]['VALUE'])) {
                $arResult['ORDER_PROPS'][2]['VALUE'] = '';
            } elseif (empty($arResult['ORDER_PROPS'][15]['VALUE']) && empty($arResult['ORDER_PROPS'][2]['VALUE'])) {
                $arResult['ERROR'][] = 'Заполните поле "'.$arResult['ORDER_PROPS'][2]['NAME'].'"';
            }
        }

        if ($sendOrder && empty($arResult['ERROR'])) {
            //Создание заказа

            $upSaleProducts = $request->getPost('upsale_products');
            try {
                $upSaleProducts = json_decode($upSaleProducts);
            } catch (\Exception $e) {
                $upSaleProducts = [];
            }

            foreach ($upSaleProducts as $productId => $quantity) {
                Add2BasketByProductID($productId, $quantity);
            }

            $order = Order::create($siteId, REGISTER_USER_ID__DEFAULT);
            $order->setPersonTypeId($arResult['PERSON_TYPE_ID']);

            $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), $siteId)->getOrderableItems();

            $order->setBasket($basket);

            $shipmentCollection = $order->getShipmentCollection();
            $shipment = $shipmentCollection->createItem();

            $shipment->setFields(
                array(
                    'DELIVERY_ID' => $deliveryId,
                    'DELIVERY_NAME' => $deliveryName,
                    'CURRENCY' => $order->getCurrency(),
                )
            );
            $shipmentItemCollection = $shipment->getShipmentItemCollection();

            foreach ($order->getBasket() as $item) {
                $shipmentItem = $shipmentItemCollection->createItem($item);
                $shipmentItem->setQuantity($item->getQuantity());
            }

            $paymentCollection = $order->getPaymentCollection();
            $extPayment = $paymentCollection->createItem();
            $extPayment->setFields(
                array(
                    'PAY_SYSTEM_ID' => PAYMENT_ID__DEFAULT,
                    'PAY_SYSTEM_NAME' => PAYMENT_NAME__DEFAULT,
                    'SUM' => $order->getPrice(),
                )
            );

            $order->doFinalAction(true);

            $propertyCollection = $order->getPropertyCollection();
            foreach ($propertyCollection->getGroups() as $group) {
                foreach ($propertyCollection->getGroupProperties($group['ID']) as $property) {
                    $p = $property->getProperty();

                    if ($arResult['ORDER_PROPS'][$p['ID']]['IS_LOCATION'] === 'Y') {
                        $codeValue = CSaleLocation::getLocationCODEbyID($arResult['ORDER_PROPS'][$p['ID']]['VALUE']);
                        if ($propertyDel = $propertyCollection->getDeliveryLocation()) {
                            $propertyDel->setValue($codeValue);
                        }
                    } elseif (!empty($arResult['ORDER_PROPS'][$p['ID']]['VALUE'])) {
                        $property->setValue($arResult['ORDER_PROPS'][$p['ID']]['VALUE']);
                    }
                }
            }

            $deliveryDates = array();
            foreach ($_REQUEST['ORDER_PROP_4'] as $key => $value) {
                $propertyCode = 'DELIVERY_DATE';
                if ($key !== 0) {
                    $propertyCode = 'DELIVERY_DATE'.'_'.++$key;
                }

                $date = DateTime::createFromFormat('d.m.Y H:i', $value);
                $date_errors = DateTime::getLastErrors();
                if ($date_errors['warning_count'] + $date_errors['error_count'] > 0) {
                    continue;
                }

                $deliveryDates[$propertyCode] = array(
                    'id' => null,
                    'name' => '',
                    'code' => $propertyCode,
                    'value' => $date->format('d.m.Y H:i'),
                );
            }

            foreach ($propertyCollection as $property) {
                $propertyCode = $property->getField('CODE');

                if (!empty($deliveryDates[$propertyCode])) {
                    $property->setValue($deliveryDates[$propertyCode]['value']);
                }
            }

            $order->setField('CURRENCY', $order->getCurrency());
            $order->setField('USER_DESCRIPTION', $request->getPost('COMMENT'));
            $order->save();

            DiscountCouponsManager::init();
            DiscountCouponsManager::clear(true);

            $orderId = (int)$order->GetId();
            if ($orderId > 0) {
                $APPLICATION->RestartBuffer();
                echo $orderId;
                die();
            }
        }

        $this->includeComponentTemplate();
    }
}

if ($notIssetProd) {
    LocalRedirect('/');
}
