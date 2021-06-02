<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\ArgumentException;
use \Bitrix\Main\ObjectPropertyException;
use \Bitrix\Main\UI\PageNavigation;
use \Bitrix\Main\Type\Date;
use \Bitrix\Main\Type\DateTime;
use \Bitrix\Sale\Order;
use \Bitrix\Sale\Basket;
use \Germen\Tools;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * Class GermenAdminOrderList
 */
class GermenAdminOrderList extends CBitrixComponent
{
    private $nav;
    private $mainOrdersFilter = array();

    /**
     * @param array $arParams
     * @return array
     */
    public function onPrepareComponentParams($arParams): array
    {
        if (empty($arParams['FILTER_NAME'])) {
            $arParams['FILTER_NAME'] = 'filter';
        }

        if (empty($arParams['PAGER_ID'])) {
            $arParams['PAGER_ID'] = 'admin-order-history';
        }

        if (empty($arParams['PAGE_ELEMENT_COUNT'])) {
            $arParams['PAGE_ELEMENT_COUNT'] = 10;
        }

        if (empty($arParams['DISPLAY_BOTTOM_PAGER'])) {
            $arParams['DISPLAY_BOTTOM_PAGER'] = 'N';
        }

        if (empty($arParams['DISPLAY_TOP_PAGER'])) {
            $arParams['DISPLAY_TOP_PAGER'] = 'N';
        }

        if (empty($arParams['PAGER_BASE_LINK_ENABLE'])) {
            $arParams['PAGER_BASE_LINK_ENABLE'] = 'N';
        }

        if (empty($arParams['PAGER_DESC_NUMBERING'])) {
            $arParams['PAGER_DESC_NUMBERING'] = 'N';
        }

        if (empty($arParams['PAGER_DESC_NUMBERING_CACHE_TIME'])) {
            $arParams['PAGER_DESC_NUMBERING_CACHE_TIME'] = 36000;
        }

        if (empty($arParams['PAGER_SHOW_ALL'])) {
            $arParams['PAGER_SHOW_ALL'] = 'N';
        }

        if (empty($arParams['PAGER_SHOW_ALWAYS'])) {
            $arParams['PAGER_SHOW_ALWAYS'] = 'N';
        }

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
     */
    public function executeComponent(): void
    {
        $this->checkModules();
        $this->initPageNavigation();
        $this->initMainOrdersFilter();

        $this->arResult['filter'] = $this->initFilter();

        if ($this->arParams['CACHE_TYPE'] === 'Y' || $this->arParams['CACHE_TYPE'] === 'A') {
            $orders = $this->getOrdersDataCached($this->getOrders());
        } else {
            $orders = $this->getOrdersData($this->getOrders());
        }

        $this->arResult['orders'] = $this->groupOrders($orders);
        $this->arResult['pageNavigation'] = $this->getPageNavigation();

        $this->includeComponentTemplate();
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
     *
     */
    private function initPageNavigation(): void
    {
        $this->nav = new PageNavigation($this->arParams['PAGER_ID']);
        $this->nav->allowAllRecords($this->arParams['PAGER_SHOW_ALL'] === 'Y');
        $this->nav->setPageSize($this->arParams['PAGE_ELEMENT_COUNT']);
        $this->nav->initFromUri();
    }

    /**
     * @return string
     */
    private function getPageNavigation(): string
    {
        global $APPLICATION;

        ob_start();
        $APPLICATION->IncludeComponent(
            'bitrix:main.pagenavigation',
            $this->arParams['PAGER_TEMPLATE'],
            array(
                'NAV_OBJECT' => $this->nav,
                'SEF_MODE' => 'N',
            ),
            false
        );
        return (string)ob_get_clean();
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
        $cacheId .= $this->nav->getId().'|';
        $cacheId .= $this->nav->getCurrentPage().'|';
        $cacheId .= implode('|', $this->arParams).'|';
        $cacheId .= implode('|', $this->arResult['filter']).'|';

        return $cacheId;
    }

    /**
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     */
    private function getOrders(): array
    {
        $orders = array();

        $filter = array();

        if (!empty($this->arResult['filter'])) {
            $filter = array_merge($filter, $this->arResult['filter']);
        }

        $result = Order::getList(
            array(
                'order' => array('ID' => 'DESC'),
                'filter' => $filter,
                'select' => array('ID', 'DATE_INSERT', 'PRICE', 'USER_DESCRIPTION', 'STATUS_ID'),
                'count_total' => true,
                'offset' => $this->nav->getOffset(),
                'limit' => $this->nav->getLimit(),
            )
        );

        $this->nav->setRecordCount($result->getCount());

        while ($row = $result->fetch()) {
            $orders[(int)$row['ID']] = array(
                'id' => (int)$row['ID'],
                'price' => (int)$row['PRICE'],
                'comment' => $row['USER_DESCRIPTION'],
                'note' => '',
                'dateCreate' => $row['DATE_INSERT'],
                'dateDelivery' => '',
                'dateBuildEstimated' => '',
                'status' => $row['STATUS_ID'],
            );
        }

        return $orders;
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

        $filter = array('ORDER_ID' => $ordersId, 'CODE' => array('DELIVERY_DATE', 'TEXT_SCRAP'));
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
                'quantity' => (int)$row['QUANTITY'],
                'composition' => '',
                'comment' => '',
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
        $select = array('IBLOCK_ID', 'ID', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'PROPERTY_COMPOSITION');
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
                'composition' => $row['PROPERTY_COMPOSITION_VALUE'],
                'comment' => '',
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

        $product = $this->getProducts($productsId);

        foreach ($ordersBasket as $orderId => $orderBasket) {
            foreach ($orderBasket as $basketItemId => $basketItem) {
                $ordersBasket[$orderId][$basketItemId]['imageId'] = $product[$basketItem['productId']]['imageId'];
                $ordersBasket[$orderId][$basketItemId]['composition'] = $product[$basketItem['productId']]['composition'];
                $ordersBasket[$orderId][$basketItemId]['comment'] = $product[$basketItem['productId']]['comment'];
            }
        }

        return $ordersBasket;
    }

    /**
     *
     */
    private function initMainOrdersFilter(): void
    {
        $this->mainOrdersFilter = array(
            'STATUS_ID' => array('N', 'NP', 'NN', 'SP', 'Q'),
            // N, NP, NN - новые заказы, SP - собранные заказы, Q - отмененные заказы
            '>=DATE_INSERT' => Date::createFromTimestamp(strtotime('today')),
        );
    }

    /**
     * @return array
     */
    private function initFilter(): array
    {
        if (empty($this->arParams['FILTER_NAME'])) {
            return array();
        }

        global ${$this->arParams['FILTER_NAME']};

        if (!empty($this->mainOrdersFilter['STATUS_ID'])) {
            ${$this->arParams['FILTER_NAME']}['STATUS_ID'] = $this->mainOrdersFilter['STATUS_ID'];
        }

        if (!empty($this->mainOrdersFilter['>=DATE_INSERT'])) {
            ${$this->arParams['FILTER_NAME']}['>=DATE_INSERT'] = $this->mainOrdersFilter['>=DATE_INSERT'];
        }

        if (empty(${$this->arParams['FILTER_NAME']})) {
            return array();
        }

        return ${$this->arParams['FILTER_NAME']};
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
            if (in_array((string)$order['status'], array('N', 'NP', 'NN'), true)) {
                $result['new'][$orderId] = $order;
            }

            if ((string)$order['status'] === 'SP') {
                $result['collected'][$orderId] = $order;
            }

            if ((string)$order['status'] === 'Q') {
                $result['canceled'][$orderId] = $order;
            }
        }

        return $result;
    }
}
