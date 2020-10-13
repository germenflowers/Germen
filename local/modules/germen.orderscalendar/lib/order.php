<?php

namespace Germen\Orderscalendar;

use \Bitrix\Main\Loader;
use \Bitrix\Sale\Order as BitrixOrder;
use \Bitrix\Sale\Internals\StatusLangTable;
use \Bitrix\Main\Type\Date;
use \Bitrix\Main\Type\DateTime;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\ArgumentException;
use \Bitrix\Main\ObjectPropertyException;

/**
 * Class Order
 * @package Germen\Orderscalendar
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
     * @param int $timeStart
     * @param int $timeEnd
     * @return array
     * @throws ArgumentException
     */
    public function getOrders(int $timeStart, int $timeEnd): array
    {
        $orders = array();

        $rangeStart = Date::createFromTimestamp($timeStart);
        $rangeEnd = Date::createFromTimestamp($timeEnd);

        $result = BitrixOrder::getList(
            array(
                'order' => array('ID' => 'ASC'),
                'filter' => array('>DATE_INSERT' => $rangeStart, '<DATE_INSERT' => $rangeEnd),
                'select' => array('ID', 'STATUS_ID', 'DATE_INSERT', 'PRICE', 'PAYED'),
            )
        );
        while ($row = $result->fetch()) {
            $orders[] = array(
                'id' => (int)$row['ID'],
                'statusId' => $row['STATUS_ID'],
                'timeCreate' => $row['DATE_INSERT']->getTimestamp(),
                'url' => $this->createOrderUrl((int)$row['ID']),
                'sum' => (int)$row['PRICE'],
                'paid' => $row['PAYED'],
            );
        }

        return $orders;
    }

    /**
     * @param int $orderId
     * @return string
     */
    public function createOrderUrl(int $orderId): string
    {
        return '/bitrix/admin/sale_order_view.php?ID='.$orderId;
    }

    /**
     * @param array $orders
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function formatOrdersToEvents(array $orders): array
    {
        $events = array();

        $statusesId = array();
        foreach ($orders as $order) {
            if (!in_array($order['statusId'], $statusesId, true)) {
                $statusesId[] = $order['statusId'];
            }
        }

        $statuses = $this->getStatuses($statusesId);

        foreach ($orders as $order) {
            $dateTime = DateTime::createFromTimestamp($order['timeCreate']);

            $events[] = array(
                'title' => (int)$order['id'],
                'url' => $order['url'],
                'start' => $dateTime->format('Y-m-d\TH:i:s'),
                'end' => $dateTime->format('Y-m-d\TH:i:s'),
                'orderId' => (int)$order['id'],
                'status' => $statuses[$order['statusId']]['name'],
                'paid' => $order['paid'] === 'Y',
                'sum' => $order['sum'],
                'sumFormat' => number_format($order['sum'], 0, '', ' ').' руб.',
            );
        }

        return $events;
    }

    /**
     * @param array $statusesId
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getStatuses(array $statusesId = array()): array
    {
        $statuses = array();

        $result = StatusLangTable::getList(
            array(
                'order' => array('STATUS.SORT' => 'ASC'),
                'filter' => array('STATUS_ID' => $statusesId, 'LID' => LANGUAGE_ID),
                'select' => array('STATUS_ID', 'NAME'),
            )
        );
        while ($row = $result->fetch()) {
            $statuses[$row['STATUS_ID']] = array(
                'id' => $row['STATUS_ID'],
                'name' => $row['NAME'],
            );
        }

        return $statuses;
    }
}