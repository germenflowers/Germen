<?php

namespace Germen\Suppliers;

use Bitrix\Main\Loader;
use Bitrix\Sale\Order as BitrixOrder;
use Bitrix\Main\LoaderException;
use Bitrix\Main\SystemException;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\NotImplementedException;

/**
 * Class Order
 * @package Germen\Suppliers
 */
class Order
{
    /**
     * Order constructor.
     * @throws SystemException
     * @throws LoaderException
     */
    public function __construct()
    {
        if (!Loader::includeModule('sale')) {
            throw new SystemException('Не подключен модуль sale');
        }
    }

    /**
     * @param int $orderId
     * @return array
     * @throws ArgumentException
     * @throws ArgumentNullException
     * @throws NotImplementedException
     */
    public function getFields($orderId): array
    {
        $fields = array();

        $order = BitrixOrder::load($orderId);
        if ($order === null) {
            return $fields;
        }

        $propertyCollection = $order->getPropertyCollection();
        $basket = $order->getBasket();

        $productsId = array();
        foreach ($basket as $basketItem) {
            $productsId[] = (int)$basketItem->getProductId();
        }

        $deliveryTimePropertyId = 0;
        $notePropertyId = 0;

        $filter = array('CODE' => array('TIME', 'TEXT_SCRAP'));
        $select = array('ID', 'CODE');
        $result = \CSaleOrderProps::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            if ($row['CODE'] === 'TIME') {
                $deliveryTimePropertyId = (int)$row['ID'];
            }

            if ($row['CODE'] === 'TEXT_SCRAP') {
                $notePropertyId = (int)$row['ID'];
            }
        }

        $deliveryTime = 0;
        if (!empty($deliveryTimePropertyId)) {
            $deliveryTime = $propertyCollection->getItemByOrderPropertyId($deliveryTimePropertyId)->getValue();
            if (is_array($deliveryTime)) {
                $deliveryTime = current($deliveryTime);
            }
            $deliveryTime = strtotime($deliveryTime);
        }

        $note = '';
        if (!empty($notePropertyId)) {
            $note = trim($propertyCollection->getItemByOrderPropertyId($notePropertyId)->getValue());
        }

        $fields = array(
            'id' => (int)$orderId,
            'productsId' => $productsId,
            'paid' => $order->isPaid(),
            'comment' => trim($order->getField('USER_DESCRIPTION')),
            'note' => $note,
            'deliveryTime' => $deliveryTime,
        );

        return $fields;
    }
}
