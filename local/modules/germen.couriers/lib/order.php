<?php

namespace Germen\Couriers;

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\ArgumentException;
use \Bitrix\Main\ArgumentNullException;
use \Bitrix\Main\ArgumentOutOfRangeException;
use \Bitrix\Main\NotImplementedException;
use \Bitrix\Main\SystemException;
use \Bitrix\Sale\Order as BitrixOrder;
use \Germen\Couriers\Iblock\Product;
use \Germen\Couriers\Iblock\Compliment;
use \Germen\Couriers\Iblock\Subscribe;

/**
 * Class Order
 * @package Germen\Couriers
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
     * @throws SystemException
     */
    public function getFields(int $orderId): array
    {
        $fields = array();

        $order = BitrixOrder::load($orderId);
        if ($order === null) {
            return $fields;
        }

        $courierId = 0;
        $deliveryDate = '';
        $note = '';
        $address = '';
        $flat = '';
        $userName = '';
        $userPhone = '';
        $isSurprise = false;

        $properties = $order->getPropertyCollection()->getArray();
        foreach ($properties['properties'] as $property) {
            if ($property['CODE'] === 'COURIER') {
                $courierId = (int)$property['VALUE'][0];
            }

            if ($property['CODE'] === 'DELIVERY_DATE') {
                $deliveryDate = $property['VALUE'][0];
            }

            if ($property['CODE'] === 'TEXT_SCRAP') {
                $note = $property['VALUE'][0];
            }

            if ($property['CODE'] === 'ADDRESS') {
                $address = $property['VALUE'][0];
            }

            if ($property['CODE'] === 'APPARTMENT') {
                $flat = $property['VALUE'][0];
            }

            if ($property['CODE'] === 'NAME_RECIPIENT') {
                $userName = $property['VALUE'][0];
            }

            if ($property['CODE'] === 'PHONE_RECIPIENT') {
                $userPhone = $property['VALUE'][0];
            }

            if ($property['CODE'] === 'SURPRISE') {
                $isSurprise = $property['VALUE'][0] === 'Y';
            }
        }

        $statuses = $this->getStatuses();

        return array(
            'id' => $orderId,
            'courierId' => $courierId,
            'status' => $statuses[$order->getField('STATUS_ID')],
            'paid' => $order->isPaid(),
            'deliveryDate' => $deliveryDate,
            'comment' => trim($order->getField('USER_DESCRIPTION')),
            'note' => $note,
            'address' => $address,
            'flat' => $flat,
            'userName' => $userName,
            'userPhone' => $userPhone,
            'isSurprise' => $isSurprise,
            'basket' => $this->getOrderBasketItems($order->getBasket()),
        );
    }

    /**
     * @param $orderBasket
     * @return array
     */
    private function getOrderBasketItems($orderBasket): array
    {
        $basket = array();
        $productsId = array();
        $complimentsId = array();
        $products = array();
        $compliments = array();
        $subscribes = array();

        foreach ($orderBasket as $basketItem) {
            $id = (int)$basketItem->getId();
            $productId = (int)$basketItem->getProductId();
            $properties = $basketItem->getPropertyCollection()->getPropertyValues();

            $isUpsale = false;
            $isSubscribe = false;

            if ((int)$properties['UPSALE']['VALUE'] === 1) {
                $isUpsale = true;
                $complimentsId[] = $productId;
            } elseif ((int)$properties['SUBSCRIBE']['VALUE'] === 1) {
                $isSubscribe = true;
                $subscribes[$productId] = array(
                    'id' => $productId,
                    'type' => $properties['TYPE']['VALUE'],
                    'image' => Subscribe::getImage($properties['TYPE']['VALUE'])['src'],
                );
            } else {
                $productsId[] = $productId;
            }

            $basket[$id] = array(
                'id' => $id,
                'productId' => $productId,
                'name' => $basketItem->getField('NAME'),
                'quantity' => (int)$basketItem->getQuantity(),
                'image' => '',
                'composition' => '',
                'isUpsale' => $isUpsale,
                'isSubscribe' => $isSubscribe,
            );
        }

        if (!empty($productsId)) {
            $products = (new Product())->getItems($productsId, array('width' => 80, 'height' => 92));
        }

        if (!empty($complimentsId)) {
            $compliments = (new Compliment())->getItems($complimentsId, array('width' => 80, 'height' => 92));
        }

        foreach ($basket as $id => $item) {
            if (!empty($products[$item['productId']])) {
                $basket[$id]['image'] = $products[$item['productId']]['image'];
            } elseif (!empty($compliments[$item['productId']])) {
                $basket[$id]['image'] = $compliments[$item['productId']]['image'];
            } elseif (!empty($subscribes[$item['productId']])) {
                $basket[$id]['image'] = $subscribes[$item['productId']]['image'];
            }
        }

        return $basket;
    }

    /**
     * @return array
     */
    public function getStatuses(): array
    {
        $items = array();

        $order = array('SORT' => 'ASC');
        $filter = array('TYPE' => 'O');
        $select = array('ID', 'NAME');
        $result = \CSaleStatus::GetList($order, $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $items[$row['ID']] = array(
                'id' => $row['ID'],
                'name' => $row['NAME'],
            );
        }

        return $items;
    }

    /**
     * @param int $orderId
     * @param string $statusId
     * @return bool
     * @throws ArgumentException
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     */
    public function setStatus(int $orderId, string $statusId): bool
    {
        $order = BitrixOrder::load($orderId);

        if (is_null($order)) {
            return false;
        }

        $order->setField('STATUS_ID', $statusId);

        $result = $order->save();
        if ($result->isSuccess()) {
            return true;
        }

        return false;
    }
}
