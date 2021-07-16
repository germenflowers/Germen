<?php

namespace Germen\Couriers;

use \Bitrix\Main\SystemException;
use \Bitrix\Main\ArgumentException;
use \Bitrix\Main\ArgumentNullException;
use \Bitrix\Main\ArgumentOutOfRangeException;
use \Bitrix\Main\NotImplementedException;
use \Bitrix\Main\ObjectPropertyException;
use \Germen\Couriers\Iblock\Courier as IblockCourier;
use \PDV\Smsaero;
use \Exception;

/**
 * Class Courier
 * @package Germen\Couriers
 */
class Courier
{
    /**
     * Courier constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param int $orderId
     * @param bool $paid
     * @param string $statusId
     * @param int $courierId
     * @return bool
     * @throws ArgumentException
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws NotImplementedException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function create(int $orderId, bool $paid = false, string $statusId = '', int $courierId = 0): bool
    {
        $orderFields = (new Order)->getFields($orderId);

        if (!$orderFields['paid'] && $paid) {
            $orderFields['paid'] = $paid;
        }

        if ($orderFields['status']['id'] !== 'SC' && $statusId === 'SC') {
            $orderFields['status']['id'] = $statusId;
        }

        if (empty($orderFields['courierId']) && !empty($courierId)) {
            $orderFields['courierId'] = $courierId;
        }

        if (!$orderFields['paid']) {
            throw new SystemException('Заказ не оплачен');
        }

        if ($orderFields['status']['id'] !== 'SC') {
            throw new SystemException('Неправильный статус заказа');
        }

        if (empty($orderFields['courierId'])) {
            throw new SystemException('Курьер не назначен');
        }

        $courier = (new IblockCourier())->getById($orderFields['courierId']);

        if (empty($courier)) {
            throw new SystemException('Курьер не существует');
        }

        $result = CouriersTable::getList(
            array(
                'filter' => array(
                    'courier_id' => $orderFields['courierId'],
                    'order_id' => $orderFields['id'],
                ),
            )
        );
        if ($result->Fetch()) {
            return true;
        }

        $token = Tools::getToken();

        $result = CouriersTable::add(
            array(
                'courier_id' => $orderFields['courierId'],
                'order_id' => $orderFields['id'],
                'phone' => $courier['phone'],
                'email' => $courier['email'],
                'token' => $token,
                'url' => Tools::createUrl($token),
                'send_notification' => 0,
            )
        );
        if (!$result->isSuccess()) {
            throw new SystemException($result->getErrorMessages());
        }

        return true;
    }

    /**
     * @throws SystemException
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws Exception
     */
    public function send(int $orderId): bool
    {
        $result = CouriersTable::getList(
            array(
                'filter' => array(
                    'order_id' => $orderId,
                    'send_notification' => 0,
                ),
            )
        );
        if (
            ($row = $result->Fetch()) &&
            (new Smsaero)->sendSMS($row['phone'], $row['url'])
        ) {
            CouriersTable::update($row['id'], array('send_notification' => 1));
        }

        return false;
    }

    /**
     * @param string $token
     * @return array
     * @throws ArgumentException
     * @throws ArgumentNullException
     * @throws NotImplementedException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getByToken(string $token): array
    {
        $data = array();

        $result = CouriersTable::getList(array('filter' => array('token' => $token)));
        if ($row = $result->Fetch()) {
            $data['courier'] = array(
                'id' => (int)$row['courier_id'],
                'phone' => $row['phone'],
                'email' => $row['email'],
                'token' => $row['token'],
                'url' => $row['url'],
                'sendNotification' => (bool)$row['send_notification'],
            );
            $data['order'] = (new Order)->getFields($row['order_id']);
        }

        return $data;
    }
}
