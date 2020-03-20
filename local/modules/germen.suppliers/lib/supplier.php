<?php

namespace Germen\Suppliers;

use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\SystemException;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\NotImplementedException;
use Exception;

/**
 * Class Supplier
 * @package Germen\Suppliers
 */
class Supplier
{
    private $suppliersIblockId = 0;

    /**
     * Supplier constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return bool
     * @throws LoaderException
     */
    private function setIblockId(): bool
    {
        if (!empty($this->suppliersIblockId)) {
            return true;
        }

        if (!Loader::includeModule('iblock')) {
            return false;
        }

        $filter = array('CODE' => 'suppliers');
        $result = \CIBlock::GetList(array(), $filter);
        if ($row = $result->Fetch()) {
            $this->suppliersIblockId = (int)$row['ID'];

            return true;
        }

        return false;
    }

    /**
     * @param int $orderId
     * @return array
     * @throws ArgumentException
     * @throws SystemException
     * @throws ArgumentNullException
     * @throws LoaderException
     * @throws NotImplementedException
     */
    public function create($orderId): array
    {
        $order = new Order;
        $product = new Product;
        $compliment = new Compliment;

        $orderFields = $order->getFields($orderId);

        if (empty($orderFields['id'])) {
            return array('error' => true, 'message' => 'Не указан ид заказа');
        }

        if (empty($orderFields['deliveryTime'])) {
            return array('error' => true, 'message' => 'Не указано время доставки');
        }

        $productsId = array();
        $complimentsId = array();
        if (!empty($orderFields['productsId'])) {
            $productsId = $product->getItemsId($orderFields['productsId']);
            $complimentsId = $compliment->getItemsId($orderFields['productsId']);
        }

        if (empty($productsId)) {
            return array('error' => true, 'message' => 'Не выбраны товары');
        }

        $suppliersProductsId = $product->getSuppliersProductsId($productsId);

        if (empty($suppliersProductsId)) {
            return array('error' => true, 'message' => 'Не указаны поставщики товаров');
        }

        $suppliersFields = array();
        $allSuppliersProductsId = array();
        foreach ($suppliersProductsId as $supplierId => $supplierProductsId) {
            $token = Tools::getToken();

            $suppliersFields[] = array(
                'supplier_id' => $supplierId,
                'order_id' => $orderFields['id'],
                'products_id' => $supplierProductsId,
                'compliments_id' => $complimentsId,
                'delivery_time' => DateTime::createFromTimestamp($orderFields['deliveryTime']),
                'paid' => (int)$orderFields['paid'],
                'comment' => $orderFields['comment'],
                'note' => $orderFields['note'],
                'status' => 'create',
                'token' => $token,
                'url' => Tools::createUrl($token),
            );

            foreach ($supplierProductsId as $productId) {
                $allSuppliersProductsId[] = $productId;
            }
        }

        if (!empty(array_diff($productsId, $allSuppliersProductsId))) {
            return array('error' => true, 'message' => 'Не для всех товаров указаны поставщики');
        }

        $ids = array();
        $result = SuppliersTable::getList(
            array(
                'filter' => array(
                    'supplier_id' => array_keys($suppliersProductsId),
                    'order_id' => $orderFields['id'],
                ),
                'select' => array(
                    'id',
                    'supplier_id',
                ),
            )
        );
        while ($row = $result->fetch()) {
            $ids[$row['supplier_id']] = $row['id'];
        }

        foreach ($suppliersFields as $fields) {
            if (empty($ids[$fields['supplier_id']])) {
                $this->add($fields);
            } else {
                unset($fields['status'], $fields['token'], $fields['url']);
                $this->update($ids[$fields['supplier_id']], $fields);
            }
        }

        return array('error' => false, 'message' => '');
    }

    /**
     * @param array $fields
     * @return array
     * @throws Exception
     */
    private function add($fields): array
    {
        $return = array('error' => false, 'message' => '');

        $result = SuppliersTable::add($fields);
        if (!$result->isSuccess()) {
            $return = array('error' => true, 'message' => $result->getErrorMessages());
        }

        return $return;
    }

    /**
     * @param int $id
     * @param array $fields
     * @return array
     * @throws Exception
     */
    private function update($id, $fields): array
    {
        $return = array('error' => false, 'message' => '');

        $result = SuppliersTable::update($id, $fields);
        if (!$result->isSuccess()) {
            $return = array('error' => true, 'message' => $result->getErrorMessages());
        }

        return $return;
    }

    /**
     * @param int $deliveryTime
     * @param int $orderId
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getForNotification($deliveryTime, $orderId = 0): array
    {
        $items = array();

        $filter = array(
            '<=delivery_time' => DateTime::createFromTimestamp($deliveryTime),
            'paid' => 1,
            'status' => 'create',
        );

        if (!empty($orderId)) {
            $filter['order_id'] = $orderId;
        }

        $result = SuppliersTable::getList(
            array(
                'filter' => $filter,
                'select' => array('id', 'supplier_id', 'url'),
            )
        );
        while ($row = $result->fetch()) {
            $items[$row['id']] = array(
                'id' => $row['id'],
                'supplierId' => $row['supplier_id'],
                'url' => $row['url'],
            );
        }

        return $items;
    }

    /**
     * @param int $id
     * @param string $status
     * @return array
     * @throws Exception
     */
    private function setStatus($id, $status): array
    {
        $return = array('error' => false, 'message' => '');

        $result = SuppliersTable::update($id, array('status' => $status));
        if (!$result->isSuccess()) {
            $return = array('error' => true, 'message' => $result->getErrorMessages());
        }

        return $return;
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function setStatusSend($id): array
    {
        return $this->setStatus($id, 'send');
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function setStatusAccepted($id): array
    {
        return $this->setStatus($id, 'accepted');
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function setStatusAssembled($id): array
    {
        return $this->setStatus($id, 'assembled');
    }

    /**
     * @param array $suppliersId
     * @return array
     * @throws LoaderException
     */
    public function getPhones($suppliersId): array
    {
        $phones = array();

        if (!Loader::includeModule('iblock')) {
            return $phones;
        }

        $this->setIblockId();

        $filter = array('IBLOCK_ID' => $this->suppliersIblockId, 'ID' => $suppliersId, '!PROPERTY_PHONE' => false);
        $select = array('IBLOCK_ID', 'ID', 'PROPERTY_PHONE');
        $result = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $phones[(int)$row['ID']] = $row['PROPERTY_PHONE_VALUE'];
        }

        return $phones;
    }

    /**
     * @param string $token
     * @return array
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getByToken($token): array
    {
        $data = array();

        $result = SuppliersTable::getList(
            array(
                'filter' => array('token' => $token),
                'select' => array(
                    'id',
                    'order_id',
                    'products_id',
                    'compliments_id',
                    'delivery_time',
                    'comment',
                    'note',
                    'status',
                ),
            )
        );
        if ($row = $result->fetch()) {
            $data = array(
                'id' => $row['id'],
                'orderId' => $row['order_id'],
                'productsId' => $row['products_id'],
                'complimentsId' => $row['compliments_id'],
                'deliveryTime' => $row['delivery_time']->getTimestamp(),
                'comment' => $row['comment'],
                'note' => $row['note'],
                'status' => $row['status'],
            );
        }

        if (empty($data)) {
            return $data;
        }

        $data['courierTime'] = $data['deliveryTime'] - 90 * 60;

        $data['deliveryTimeFormat'] = date('d.m.Y H:i', $data['deliveryTime']);
        $data['courierTimeFormat'] = date('d.m.Y H:i', $data['courierTime']);

        $data['products'] = array();
        if (!empty($data['productsId'])) {
            $product = new Product;

            $data['products'] = $product->getItems($data['productsId']);
        }

        $data['compliments'] = array();
        if (!empty($data['complimentsId'])) {
            $compliment = new Compliment;

            $data['compliments'] = $compliment->getItems($data['complimentsId']);
        }

        return $data;
    }
}
