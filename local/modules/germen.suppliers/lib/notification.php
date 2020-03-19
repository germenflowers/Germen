<?php

namespace Germen\Suppliers;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Exception;
use PDV\Smsaero;

/**
 * Class Notification
 * @package Germen\Suppliers
 */
class Notification
{
    private $timeDiff = 24 * 60 * 60;

    /**
     * Notification constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param int $orderId
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws Exception
     */
    public function send($orderId = 0): void
    {
        $deliveryTime = time() + $this->timeDiff;

        $supplier = new Supplier;

        if (empty($orderId)) {
            $suppliersOrders = $supplier->getForNotification($deliveryTime);
        } else {
            $suppliersOrders = $supplier->getForNotification($deliveryTime, $orderId);
        }

        $data = array();
        $suppliersId = array();
        foreach ($suppliersOrders as $id => $fields) {
            $data[] = array(
                'id' => $fields['id'],
                'supplierId' => $fields['supplierId'],
                'url' => $fields['url'],
            );

            $suppliersId[] = $fields['supplierId'];
        }

        $phones = $supplier->getPhones($suppliersId);

        foreach ($data as $fields) {
            if (!empty($phones[$fields['supplierId']])) {
                $this->sendSms($phones[$fields['supplierId']], $fields['url']);
                $supplier->setStatusSend($fields['id']);
            }
        }
    }

    /**
     * @param string $phone
     * @param string $url
     * @return mixed
     */
    public function sendSms($phone, $url)
    {
        return (new Smsaero)->sendSMS($phone, $url);
    }
}
