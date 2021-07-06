<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\ArgumentException;
use \Bitrix\Main\ObjectPropertyException;
use \Bitrix\Main\Type\Date;
use \Bitrix\Main\Type\DateTime;
use \Bitrix\Sale\Order;
use \Bitrix\Sale\Basket;
use \Bitrix\Sale\Internals\OrderChangeTable;
use \Germen\Tools;
use \Germen\Admin\Rights;
use \Germen\Admin\Content;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * Class GermenAdminOrderList
 */
class GermenAdminOrderList extends CBitrixComponent
{
    private $mainOrdersFilter = array();
    private $todayDeliveredOrdersId = array();
    private $todayBuildOrdersId = array();
    private $todayCanceledOrdersId = array();
    private $newOrdersStatus = array('N', 'NP', 'NN');
    private $completedOrdersStatus = 'SP';
    private $canceledOrdersStatus = 'Q';

    /**
     * @param array $arParams
     * @return array
     */
    public function onPrepareComponentParams($arParams): array
    {
        if (empty($arParams['SET_STATUS_404'])) {
            $arParams['SET_STATUS_404'] = 'Y';
        }

        if (empty($arParams['SHOW_404'])) {
            $arParams['SHOW_404'] = 'Y';
        }

        if (empty($arParams['CACHE_FILTER'])) {
            $arParams['CACHE_FILTER'] = 'Y';
        }

        if (empty($arParams['CACHE_GROUPS'])) {
            $arParams['CACHE_GROUPS'] = 'Y';
        }

        if (empty($arParams['CACHE_TIME'])) {
            $arParams['CACHE_TIME'] = 3600;
        }

        if (empty($arParams['CACHE_TYPE'])) {
            $arParams['CACHE_TYPE'] = 'A';
        }

        return $arParams;
    }

    /**
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function executeComponent(): void
    {
        $this->checkModules();

        if ($this->request->isAjaxRequest()) {
            $this->processAjax();
        } else {
            $this->todayDeliveredOrdersId = $this->getTodayDeliveredOrdersId();
            $this->todayBuildOrdersId = $this->getTodayBuildOrdersId();
            $this->todayCanceledOrdersId = $this->getTodayCanceledOrdersId();

            $orders = array();
            if ($this->initMainOrdersFilter()) {
                if ($this->arParams['CACHE_TYPE'] === 'Y' || $this->arParams['CACHE_TYPE'] === 'A') {
                    $orders = $this->getOrdersDataCached($this->getOrders($this->mainOrdersFilter));
                } else {
                    $orders = $this->getOrdersData($this->getOrders($this->mainOrdersFilter));
                }
            }

            $this->arResult['buildSum'] = $this->getTodayBuildSum($orders);

            $this->arResult['orders'] = $this->groupOrders(
                $this->setAdditionalOrdersData($orders, $this->arResult['buildSum'])
            );

            $this->setOrdersDateShowAdmin($orders);

            $this->includeComponentTemplate();
        }
    }

    /**
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function processAjax(): void
    {
        header('Content-type: text/json');

        $get = $this->request->getQueryList();
        $post = $this->request->getPostList();

        if (empty($post['action'])) {
            die(json_encode(array('status' => 'error', 'message' => 'server error')));
        }

        $response = array('status' => 'error');

        if ($post['action'] === 'take') {
            if (!(new Rights())->checkOrdersRights('edit')) {
                die(json_encode(array('status' => 'error', 'message' => 'Forbidden')));
            }

            if (empty((int)$post['id'])) {
                die(json_encode(array('status' => 'error', 'message' => 'Empty id')));
            }

            $property = $this->getOrderPropertyByCode('BUILD_START_DATE');
            if (empty($property)) {
                die(json_encode(array('status' => 'error', 'message' => 'Property no exist')));
            }

            $value = new DateTime();

            if (Content::addOrderProperty($post['id'], $property['id'], $property['code'], $property['name'], $value)) {
                $response = array('status' => 'success');
            }
        }

        if ($post['action'] === 'ready') {
            if (!(new Rights())->checkOrdersRights('edit')) {
                die(json_encode(array('status' => 'error', 'message' => 'Forbidden')));
            }

            if (empty((int)$post['id'])) {
                die(json_encode(array('status' => 'error', 'message' => 'Empty id')));
            }

            $property = $this->getOrderPropertyByCode('BUILD_DATE');
            if (empty($property)) {
                die(json_encode(array('status' => 'error', 'message' => 'Property no exist')));
            }

            $value = new DateTime();

            if (Content::addOrderProperty($post['id'], $property['id'], $property['code'], $property['name'], $value)) {
                $response = array('status' => 'success');
            }
        }

        if ($post['action'] === 'courier') {
            if (!(new Rights())->checkOrdersRights('edit')) {
                die(json_encode(array('status' => 'error', 'message' => 'Forbidden')));
            }

            if (empty((int)$post['id'])) {
                die(json_encode(array('status' => 'error', 'message' => 'Empty id')));
            }

            $order = Order::load((int)$post['id']);

            if (is_null($order)) {
                die(json_encode(array('status' => 'error', 'message' => 'Order is null')));
            }

            $order->setField('STATUS_ID', $this->completedOrdersStatus);

            $result = $order->save();
            if ($result->isSuccess()) {
                $response = array('status' => 'success');
            }
        }

        if ($post['action'] === 'delete') {
            if (!(new Rights())->checkOrdersRights('edit')) {
                die(json_encode(array('status' => 'error', 'message' => 'Forbidden')));
            }

            if (empty((int)$post['id'])) {
                die(json_encode(array('status' => 'error', 'message' => 'Empty id')));
            }

            if (empty($post['comment'])) {
                die(json_encode(array('status' => 'error', 'message' => 'Empty comment')));
            }

            $order = Order::load((int)$post['id']);

            if (is_null($order)) {
                die(json_encode(array('status' => 'error', 'message' => 'Order is null')));
            }

            $order->setField('CANCELED', 'Y');
            $order->setField('REASON_CANCELED', $post['comment']);
            $order->setField('STATUS_ID', $this->canceledOrdersStatus);

            $result = $order->save();
            if ($result->isSuccess()) {
                $response = array('status' => 'success');
            }
        }

        if ($post['action'] === 'getNewOrders') {
            if (empty((int)$post['timestamp'])) {
                die(json_encode(array('status' => 'error', 'message' => 'Empty timestamp')));
            }

            $orders = array();
            $buildSum = 0;

            $this->todayDeliveredOrdersId = $this->getTodayDeliveredOrdersId(true);

            if ($this->initMainOrdersFilter()) {
                $orders = $this->getOrdersData($this->getOrders($this->mainOrdersFilter));
            }

            if (!empty($orders)) {
                $this->todayDeliveredOrdersId = $this->getTodayDeliveredOrdersId();
                $this->todayBuildOrdersId = $this->getTodayBuildOrdersId();

                if ($this->initMainOrdersFilter()) {
                    $allOrders = $this->getOrdersData($this->getOrders($this->mainOrdersFilter));
                    $buildSum = $this->getTodayBuildSum($allOrders);
                }

                $orders = $this->setAdditionalOrdersData($orders, $buildSum);

                $this->setOrdersDateShowAdmin($orders, (int)$post['timestamp']);
            }

            $response = array('status' => 'success', 'orders' => array_values($orders), 'buildSum' => $buildSum);
        }

        die(json_encode($response));
    }

    /**
     * @return void
     * @throws LoaderException
     * @throws Exception
     */
    private function checkModules(): void
    {
        if (!Loader::includeModule('iblock')) {
            throw new \Exception('Не установлен модуль iblock');
        }

        if (!Loader::includeModule('sale')) {
            throw new \Exception('Не установлен модуль sale');
        }
    }

    /**
     * Заказы которые нужно доставить сегодня
     * @param bool $isNoShowAdmin - выбрать ид заказов, которые еще не показывались в админке
     * @return array
     */
    private function getTodayDeliveredOrdersId(bool $isNoShowAdmin = false): array
    {
        $ordersId = array();

        $filter = array(
            'CODE' => 'DELIVERY_DATE',
            '>=VALUE' => date('d.m.Y').' 00:00',
            '<=VALUE' => date('d.m.Y').' 23:59',
        );

        $select = array();
        $result = \CSaleOrderPropsValue::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $ordersId[] = (int)$row['ORDER_ID'];
        }

        if ($isNoShowAdmin) {
            $filter = array('CODE' => 'DATE_SHOW_ADMIN', '!VALUE' => false);
            $select = array();
            $result = \CSaleOrderPropsValue::GetList(array(), $filter, false, false, $select);
            while ($row = $result->Fetch()) {
                if (in_array((int)$row['ORDER_ID'], $ordersId, true)) {
                    unset($ordersId[array_search((int)$row['ORDER_ID'], $ordersId, true)]);
                }
            }
        }

        return $ordersId;
    }

    /**
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function getTodayBuildOrdersId(): array
    {
        $ordersId = array();

        $result = OrderChangeTable::getList(
            array(
                'order' => array(),
                'filter' => array(
                    'TYPE' => 'ORDER_STATUS_CHANGED',
                    '>=DATE_CREATE' => Date::createFromTimestamp(strtotime('today')),
                    '<=DATE_CREATE' => DateTime::createFromTimestamp(strtotime(date('d.m.Y').' 23:59:59')),
                ),
                'select' => array('ORDER_ID', 'DATA'),
            )
        );
        while ($row = $result->fetch()) {
            $data = unserialize($row['DATA']);

            if ($data['STATUS_ID'] === $this->completedOrdersStatus) {
                $ordersId[] = (int)$row['ORDER_ID'];
            }
        }

        return $ordersId;
    }

    /**
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function getTodayCanceledOrdersId(): array
    {
        $ordersId = array();

        $result = OrderChangeTable::getList(
            array(
                'order' => array(),
                'filter' => array(
                    'TYPE' => 'ORDER_STATUS_CHANGED',
                    '>=DATE_CREATE' => Date::createFromTimestamp(strtotime('today')),
                    '<=DATE_CREATE' => DateTime::createFromTimestamp(strtotime(date('d.m.Y').' 23:59:59')),
                ),
                'select' => array('ORDER_ID', 'DATA'),
            )
        );
        while ($row = $result->fetch()) {
            $data = unserialize($row['DATA']);

            if ($data['STATUS_ID'] === $this->canceledOrdersStatus) {
                $ordersId[] = (int)$row['ORDER_ID'];
            }
        }

        return $ordersId;
    }

    /**
     * @return bool
     */
    private function initMainOrdersFilter(): bool
    {
        if (empty($this->todayDeliveredOrdersId) && empty($this->todayBuildOrdersId) && empty($this->todayCanceledOrdersId)) {
            return false;
        }

        $buildAndCanceledOrdersId = array_merge($this->todayBuildOrdersId, $this->todayCanceledOrdersId);

        if (empty($this->todayDeliveredOrdersId)) {
            $this->mainOrdersFilter = array('ID' => $buildAndCanceledOrdersId);
        } elseif (empty($buildAndCanceledOrdersId)) {
            $this->mainOrdersFilter = array(
                'ID' => $this->todayDeliveredOrdersId,
                'STATUS_ID' => $this->newOrdersStatus,
            );
        } else {
            $this->mainOrdersFilter = array(
                array(
                    'LOGIC' => 'OR',
                    array('ID' => $this->todayDeliveredOrdersId, 'STATUS_ID' => $this->newOrdersStatus),
                    array('ID' => $buildAndCanceledOrdersId),
                ),
            );
        }

        return true;
    }

    /**
     * @param array $filter
     * @return array
     * @throws ArgumentException
     */
    private function getOrders(array $filter = array()): array
    {
        $orders = array();

        $result = Order::getList(
            array(
                'order' => array('ID' => 'DESC'),
                'filter' => $filter,
                'select' => array('ID', 'DATE_INSERT', 'PRICE', 'USER_DESCRIPTION', 'STATUS_ID'),
            )
        );

        while ($row = $result->fetch()) {
            $orders[(int)$row['ID']] = array(
                'id' => (int)$row['ID'],
                'price' => (int)$row['PRICE'],
                'comment' => $row['USER_DESCRIPTION'],
                'note' => '',
                'dateCreate' => $row['DATE_INSERT'],
                'dateDelivery' => '',
                'dateBuildStart' => '',
                'dateBuildEstimated' => '',
                'dateBuild' => '',
                'dateShowAdmin' => '',
                'status' => $row['STATUS_ID'],
            );
        }

        return $orders;
    }

    /**
     * @param array $orders
     * @return array
     */
    private function groupOrders(array $orders): array
    {
        $result = array(
            'new' => array(),
            'collected' => array(),
            'canceled' => array(),
        );

        foreach ($orders as $orderId => $order) {
            if (in_array((string)$order['status'], $this->newOrdersStatus, true)) {
                $result['new'][$orderId] = $order;
            }

            if ((string)$order['status'] === $this->completedOrdersStatus) {
                $result['collected'][$orderId] = $order;
            }

            if ((string)$order['status'] === $this->canceledOrdersStatus) {
                $result['canceled'][$orderId] = $order;
            }
        }

        return $result;
    }

    /**
     * @param array $orders
     * @return array
     * @throws ArgumentException
     */
    public function getOrdersData(array $orders): array
    {
        return $this->setBasketToOrders($this->setPropertiesToOrders($orders));
    }

    /**
     * @param array $orders
     * @return array
     */
    private function getOrdersDataCached(array $orders): array
    {
        return Tools::returnResultCache(
            $this->arParams['CACHE_TIME'],
            $this->getCustomCacheId(),
            array($this, 'getOrdersData'),
            $orders
        );
    }

    /**
     * @return string
     */
    private function getCustomCacheId(): string
    {
        $cacheId = 'GermenAdminOrderListGetData|';
        $cacheId .= implode('|', $this->arParams).'|';
        $cacheId .= implode('|', $this->arResult['filter']).'|';

        return $cacheId;
    }

    /**
     * @param array $ordersId
     * @return array
     */
    private function getOrdersProperties(array $ordersId): array
    {
        $ordersProperties = array();

        if (empty($ordersId)) {
            return $ordersProperties;
        }

        $filter = array(
            'ORDER_ID' => $ordersId,
            'CODE' => array('DELIVERY_DATE', 'TEXT_SCRAP', 'BUILD_DATE', 'BUILD_START_DATE', 'DATE_SHOW_ADMIN'),
        );
        $select = array();
        $result = \CSaleOrderPropsValue::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $ordersProperties[(int)$row['ORDER_ID']][$row['CODE']] = array(
                'id' => (int)$row['ID'],
                'code' => $row['CODE'],
                'name' => $row['NAME'],
                'value' => $row['VALUE'],
            );
        }

        return $ordersProperties;
    }

    /**
     * @param array $orders
     * @return array
     */
    private function setPropertiesToOrders(array $orders): array
    {
        $orderProperties = $this->getOrdersProperties(array_keys($orders));

        foreach ($orders as $id => $fields) {
            if (!empty($orderProperties[$id]['DELIVERY_DATE']['value'])) {
                $timestamp = strtotime($orderProperties[$id]['DELIVERY_DATE']['value']);
                $diff = 1.5 * 60 * 60; // Время сборки на полтора часа раньше времени доставки

                $orders[$id]['dateDelivery'] = DateTime::createFromTimestamp($timestamp);
                $orders[$id]['dateBuildEstimated'] = DateTime::createFromTimestamp($timestamp - $diff);
            }

            if (!empty($orderProperties[$id]['TEXT_SCRAP']['value'])) {
                $orders[$id]['note'] = $orderProperties[$id]['TEXT_SCRAP']['value'];
            }

            if (!empty($orderProperties[$id]['BUILD_DATE']['value'])) {
                $timestamp = strtotime($orderProperties[$id]['BUILD_DATE']['value']);
                $orders[$id]['dateBuild'] = DateTime::createFromTimestamp($timestamp);
            }

            if (!empty($orderProperties[$id]['BUILD_START_DATE']['value'])) {
                $timestamp = strtotime($orderProperties[$id]['BUILD_START_DATE']['value']);
                $orders[$id]['dateBuildStart'] = DateTime::createFromTimestamp($timestamp);
            }

            if (!empty($orderProperties[$id]['DATE_SHOW_ADMIN']['value'])) {
                $timestamp = strtotime($orderProperties[$id]['DATE_SHOW_ADMIN']['value']);
                $orders[$id]['dateShowAdmin'] = DateTime::createFromTimestamp($timestamp);
            }
        }

        return $orders;
    }

    /**
     * @param array $ordersId
     * @return array
     * @throws ArgumentException
     */
    private function getOrdersBasket(array $ordersId): array
    {
        $ordersBasket = array();

        if (empty($ordersId)) {
            return $ordersBasket;
        }

        $result = basket::getList(
            array(
                'filter' => array('ORDER_ID' => $ordersId),
                'select' => array('ID', 'ORDER_ID', 'PRODUCT_ID', 'NAME', 'PRICE', 'QUANTITY'),
            )
        );
        while ($row = $result->Fetch()) {
            $ordersBasket[(int)$row['ORDER_ID']][(int)$row['ID']] = array(
                'id' => (int)$row['ID'],
                'productId' => (int)$row['PRODUCT_ID'],
                'imageId' => 0,
                'name' => $row['NAME'],
                'price' => (int)$row['PRICE'],
                'buildPrice' => 0,
                'buildTime' => 0,
                'quantity' => (int)$row['QUANTITY'],
                'composition' => '',
                'properties' => array(),
            );
        }

        return $ordersBasket;
    }

    /**
     * @param array $orders
     * @return array
     * @throws ArgumentException
     */
    private function setBasketToOrders(array $orders): array
    {
        $ordersBasket = $this->getOrdersBasket(array_keys($orders));
        $ordersBasket = $this->setPropertiesToBaskets($ordersBasket);
        $ordersBasket = $this->setProductsToBaskets($ordersBasket);

        foreach ($orders as $id => $fields) {
            $orders[$id]['basket'] = $ordersBasket[$id];
        }

        return $orders;
    }

    /**
     * @param array $basketItemsId
     * @return array
     */
    private function getBasketItemsProperties(array $basketItemsId): array
    {
        $basketItemsProperties = array();

        if (empty($basketItemsId)) {
            return $basketItemsProperties;
        }

        $filter = array(
            'BASKET_ID' => $basketItemsId,
            'CODE' => array('COVER', 'SUBSCRIBE', 'TYPE', 'DELIVERY', 'SIZE', 'UPSALE', 'BOOKMATE'),
        );
        $select = array();
        $result = (new \CSaleBasket())->GetPropsList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $basketItemsProperties[(int)$row['BASKET_ID']][$row['CODE']] = array(
                'id' => (int)$row['ID'],
                'code' => $row['CODE'],
                'name' => $row['NAME'],
                'value' => $row['VALUE'],
            );
        }

        return $basketItemsProperties;
    }

    /**
     * @param array $ordersBasket
     * @return array
     */
    private function setPropertiesToBaskets(array $ordersBasket): array
    {
        $basketItemsId = array();
        foreach ($ordersBasket as $orderBasket) {
            foreach ($orderBasket as $basketItemId => $basketItem) {
                $basketItemsId[] = $basketItemId;
            }
        }

        $basketItemsProperties = $this->getBasketItemsProperties($basketItemsId);

        foreach ($ordersBasket as $orderId => $orderBasket) {
            foreach ($orderBasket as $basketItemId => $basketItem) {
                $ordersBasket[$orderId][$basketItemId]['properties'] = $basketItemsProperties[$basketItemId];
            }
        }

        return $ordersBasket;
    }

    /**
     * @param array $productsId
     * @return array
     */
    private function getProducts(array $productsId): array
    {
        $items = array();

        $filter = array('ID' => $productsId);
        $select = array(
            'IBLOCK_ID',
            'ID',
            'PREVIEW_PICTURE',
            'DETAIL_PICTURE',
            'PROPERTY_COMPOSITION',
            'PROPERTY_BUILD_PRICE',
            'PROPERTY_BUILD_TIME',
        );
        $result = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $imageId = 0;
            if (!empty((int)$row['DETAIL_PICTURE'])) {
                $imageId = (int)$row['DETAIL_PICTURE'];
            }
            if (!empty((int)$row['PREVIEW_PICTURE'])) {
                $imageId = (int)$row['PREVIEW_PICTURE'];
            }

            $items[(int)$row['ID']] = array(
                'id' => (int)$row['ID'],
                'imageId' => $imageId,
                'composition' => $row['PROPERTY_COMPOSITION_VALUE']['TEXT'],
                'buildPrice' => (int)$row['PROPERTY_BUILD_PRICE_VALUE'],
                'buildTime' => (int)$row['PROPERTY_BUILD_TIME_VALUE'] * 60,
            );
        }

        return $items;
    }

    /**
     * @param array $ordersBasket
     * @return array
     */
    private function setProductsToBaskets(array $ordersBasket): array
    {
        $productsId = array();
        foreach ($ordersBasket as $orderBasket) {
            foreach ($orderBasket as $basketItem) {
                if (in_array((int)$basketItem['productId'], $productsId, true)) {
                    continue;
                }

                $productsId[] = (int)$basketItem['productId'];
            }
        }

        $products = $this->getProducts($productsId);

        foreach ($ordersBasket as $orderId => $orderBasket) {
            foreach ($orderBasket as $basketItemId => $basketItem) {
                $product = $products[$basketItem['productId']];

                $basketItem['imageId'] = empty($product['imageId']) ? 0 : $product['imageId'];
                $basketItem['composition'] = empty($product['composition']) ? '' : $product['composition'];
                $basketItem['buildPrice'] = empty($product['buildPrice']) ? 0 : $product['buildPrice'];
                $basketItem['buildTime'] = empty($product['buildTime']) ? 0 : $product['buildTime'];

                $ordersBasket[$orderId][$basketItemId] = $basketItem;
            }
        }

        return $ordersBasket;
    }

    /**
     * @param array $orders
     * @return int
     */
    private function getTodayBuildSum(array $orders): int
    {
        $sum = 0;

        foreach ($orders as $order) {
            if (
                (string)$order['status'] === $this->completedOrdersStatus ||
                in_array((string)$order['status'], $this->newOrdersStatus, true)
            ) {
                foreach ($order['basket'] as $basketItem) {
                    $sum += $basketItem['buildPrice'] * $basketItem['quantity'];
                }
            }
        }

        return $sum;
    }

    /**
     * @param string $code
     * @return array
     */
    private function getOrderPropertyByCode(string $code): array
    {
        $property = array();

        $filter = array('CODE' => $code);
        $select = array('ID', 'NAME', 'CODE');
        $result = \CSaleOrderProps::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $property = array(
                'id' => (int)$row['ID'],
                'code' => $row['CODE'],
                'name' => $row['NAME'],
            );
        }

        return $property;
    }

    /**
     * @param array $orders
     * @param int $timestamp
     * @return bool
     */
    private function setOrdersDateShowAdmin(array $orders, int $timestamp = 0): bool
    {
        $property = $this->getOrderPropertyByCode('DATE_SHOW_ADMIN');

        if (empty($property)) {
            return false;
        }

        if (empty($timestamp)) {
            $value = new DateTime();
        } else {
            $value = DateTime::createFromTimestamp($timestamp);
        }

        foreach ($orders as $order) {
            if (!empty($order['dateShowAdmin'])) {
                continue;
            }

            Content::addOrderProperty($order['id'], $property['id'], $property['code'], $property['name'], $value);
        }

        return true;
    }

    /**
     * @param array $orders
     * @param int $buildSum
     * @return array
     */
    private function setAdditionalOrdersData(array $orders, int $buildSum): array
    {
        foreach ($orders as $id => $order) {
            $timeLimit = $order['dateBuildEstimated']->getTimestamp() - time();
            if ($timeLimit < 0) {
                $timeLimit = 0;
            }

            $note = 'Собрать к '.$order['dateBuildEstimated']->format('H:i');

            if ($timeLimit < 30 * 60) {
                $note = 'Собрать как можно скорее';
            }

            if ($order['dateBuildEstimated']->format('d.m.Y') !== date('d.m.Y')) {
                $note = 'Собрать к '.$order['dateBuildEstimated']->format('d.m.y H:i');
            }

            $order['timeLimit'] = $timeLimit;
            $order['asideNote'] = $note;

            if (in_array((string)$order['status'], $this->newOrdersStatus, true)) {
                $orderBuildPrice = 0;
                $orderBuildTime = 0;

                foreach ($order['basket'] as $basketId => $basketItem) {
                    $orderBuildPrice += $basketItem['buildPrice'] * $basketItem['quantity'];
                    $orderBuildTime += $basketItem['buildTime'];

                    $image = array('src' => '');
                    if (!empty($basketItem['imageId'])) {
                        $image = \CFile::ResizeImageGet(
                            $basketItem['imageId'],
                            array('width' => 90, 'height' => 100),
                            BX_RESIZE_IMAGE_PROPORTIONAL
                        );
                    } elseif ($basketItem['properties']['TYPE']['value'] === 'Монобукеты') {
                        $image = array('src' => SITE_TEMPLATE_PATH.'/img/mono.jpg');
                    } elseif ($basketItem['properties']['TYPE']['value'] === 'Составные букеты') {
                        $image = array('src' => SITE_TEMPLATE_PATH.'/img/compose.jpg');
                    }

                    $basketItem['imageSrc'] = $image['src'];
                    $basketItem['priceFormat'] = number_format(
                        $basketItem['buildPrice'] * $basketItem['quantity'],
                        0,
                        '',
                        ' '
                    );
                    $basketItem['propertiesString'] = Content::getHistoryListBasketItemPropertiesString(
                        $basketItem['properties']
                    );
                    $basketItem['orderNote'] = $order['note'];

                    $order['basket'][$basketId] = $basketItem;
                }

                $order['basket'] = array_values($order['basket']);

                if (empty($orderBuildTime)) {
                    $orderBuildTime = 10 * 60;
                }

                $isExpiringTime = $timeLimit < 30 * 60;

                $isStartBuild = !empty($order['dateBuildStart']);
                $isBuild = !empty($order['dateBuild']);

                $orderBuildTimestamp = time() + $orderBuildTime;
                if ($isStartBuild) {
                    $orderBuildTimestamp = $order['dateBuildStart']->getTimestamp() + $orderBuildTime;
                }

                $orderBuildTimerTimestamp = $orderBuildTimestamp - time();
                if ($orderBuildTimerTimestamp < 0) {
                    $orderBuildTimerTimestamp = 0;
                }

                $order['orderBuildPrice'] = $orderBuildPrice;
                $order['orderBuildPriceFormat'] = number_format($orderBuildPrice, 0, '', ' ');
                $order['orderBuildTime'] = $orderBuildTime;
                $order['orderBuildDate'] = date('H:i', $orderBuildTimestamp);
                $order['orderBuildTimestamp'] = $orderBuildTimestamp;
                $order['orderBuildTimerTimestamp'] = $orderBuildTimerTimestamp;
                $order['isExpiringTime'] = $isExpiringTime;
                $order['isStartBuild'] = $isStartBuild;
                $order['isBuild'] = $isBuild;
                $order['ordersBuildSum'] = $buildSum;
                $order['ordersBuildSumFormat'] = number_format($buildSum, 0, '', ' ');
            }

            $orders[$id] = $order;
        }

        return $orders;
    }
}
