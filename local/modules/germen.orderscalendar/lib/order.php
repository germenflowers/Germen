<?php

namespace Germen\Orderscalendar;

use \Bitrix\Main\Loader;
use \Bitrix\Sale\Order as BitrixOrder;
use \Bitrix\Sale\Internals\OrderPropsValueTable;
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
     * Метод получает заказы у которых DELIVERY_DATE, DELIVERY_DATE_* попадает в диапазон от $timeStart до $timeEnd
     * а также заказы у которых вообще не указано DELIVERY_DATE
     * (такие заказы можно будет ометить в календаре другим цветом, чтобы не пропустить эту ошибку)
     *
     * Поле DELIVERY_DATE используется для указания времени доставки для обычных заказов
     * Поля DELIVERY_DATE_* используются для указания времени доставки для заказов на подписку цветов,
     * при этом DELIVERY_DATE будет использовано для первого времени доставки
     *
     * Поле DELIVERY_DATE_TO используется для того, чтобы в календаре можно было отобразить диапазон времени доставки заказа
     *
     * В методе придусмотрены поля DELIVERY_DATE_TO_*.
     * Их можно будет использовать по аналогии с DELIVERY_DATE_TO для заказов на подписку цветов
     *
     * @param int $timeStart
     * @param int $timeEnd
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getOrdersFilterDeliveryDate(int $timeStart, int $timeEnd): array
    {
        $orders = array();

        /**
         * Избавляемся от таймзоны
         */
        $rangeStart = Date::createFromTimestamp($timeStart);
        $rangeEnd = Date::createFromTimestamp($timeEnd);
        $timeStart = $rangeStart->getTimestamp();
        $timeEnd = $rangeEnd->getTimestamp();

        /**
         * Настраиваем ключи, для правильного соотвествия свойст DELIVERY_DATE и DELIVERY_DATE_TO
         */
        $settings = array(
            'DELIVERY_DATE' => array(
                'code' => 'DELIVERY_DATE',
                'key' => 0,
            ),
            'DELIVERY_DATE_2' => array(
                'code' => 'DELIVERY_DATE_2',
                'key' => 1,
            ),
            'DELIVERY_DATE_3' => array(
                'code' => 'DELIVERY_DATE_3',
                'key' => 2,
            ),
            'DELIVERY_DATE_4' => array(
                'code' => 'DELIVERY_DATE_4',
                'key' => 3,
            ),
            'DELIVERY_DATE_5' => array(
                'code' => 'DELIVERY_DATE_5',
                'key' => 4,
            ),
            'DELIVERY_DATE_6' => array(
                'code' => 'DELIVERY_DATE_6',
                'key' => 5,
            ),
            'DELIVERY_DATE_7' => array(
                'code' => 'DELIVERY_DATE_7',
                'key' => 6,
            ),
            'DELIVERY_DATE_8' => array(
                'code' => 'DELIVERY_DATE_8',
                'key' => 7,
            ),
            'DELIVERY_DATE_9' => array(
                'code' => 'DELIVERY_DATE_9',
                'key' => 8,
            ),
            'DELIVERY_DATE_10' => array(
                'code' => 'DELIVERY_DATE_10',
                'key' => 9,
            ),
            'DELIVERY_DATE_11' => array(
                'code' => 'DELIVERY_DATE_11',
                'key' => 10,
            ),
            'DELIVERY_DATE_12' => array(
                'code' => 'DELIVERY_DATE_12',
                'key' => 11,
            ),
        );

        /**
         * Получаем ид заказов у которых даты доставки попадают в диапазон от $timeStart до $timeEnd
         */
        $ordersId = array();
        $result = OrderPropsValueTable::GetList(
            array(
                'order' => array('ORDER_ID' => 'ASC'),
                'filter' => array(
                    'CODE' => array_keys($settings),
                    '!VALUE' => false,
                ),
                'select' => array('ORDER_ID', 'CODE', 'VALUE'),
            )
        );
        while ($row = $result->Fetch()) {
            $timestamp = strtotime($row['VALUE']);

            if ($timestamp > $timeStart && $timestamp < $timeEnd) {
                if (!in_array((int)$row['ORDER_ID'], $ordersId, true)) {
                    $ordersId[] = (int)$row['ORDER_ID'];
                }

                if (isset($orders[(int)$row['ORDER_ID']])) {
                    $orders[(int)$row['ORDER_ID']]['timesDelivery'][$settings[$row['CODE']]['key']] = $timestamp;
                } else {
                    $orders[(int)$row['ORDER_ID']] = array(
                        'id' => (int)$row['ORDER_ID'],
                        'timesDelivery' => array($settings[$row['CODE']]['key'] => $timestamp),
                    );
                }
            }
        }

        /**
         * Получаем ид заказов у которых не заполнена дата доставки
         */
        $result = OrderPropsValueTable::GetList(
            array(
                'order' => array('ORDER_ID' => 'ASC'),
                'filter' => array(
                    'CODE' => 'DELIVERY_DATE',
                    'VALUE' => '',
                ),
                'select' => array('ORDER_ID', 'CODE', 'VALUE'),
            )
        );
        while ($row = $result->Fetch()) {
            if (!in_array((int)$row['ORDER_ID'], $ordersId, true)) {
                $ordersId[] = (int)$row['ORDER_ID'];
            }

            $orders[(int)$row['ORDER_ID']] = array(
                'id' => (int)$row['ORDER_ID'],
                'timesDelivery' => array(),
            );
        }

        /**
         * Получаем даты доставки (до)
         */
        $ordersDeliveryDateTo = $this->getOrdersDeliveryDateTo($ordersId);

        /**
         * Получаем заказы
         */
        $result = BitrixOrder::getList(
            array(
                'order' => array('ID' => 'ASC'),
                'filter' => array('ID' => $ordersId),
                'select' => array('ID', 'STATUS_ID', 'DATE_INSERT', 'PRICE', 'PAYED'),
            )
        );
        while ($row = $result->fetch()) {
            if (empty($ordersDeliveryDateTo[(int)$row['ID']])) {
                $orders[(int)$row['ID']]['timesDeliveryTo'] = array();
            } else {
                $orders[(int)$row['ID']]['timesDeliveryTo'] = $ordersDeliveryDateTo[(int)$row['ID']];
            }

            $orders[(int)$row['ID']]['statusId'] = $row['STATUS_ID'];
            $orders[(int)$row['ID']]['timeCreate'] = $row['DATE_INSERT']->getTimestamp();
            $orders[(int)$row['ID']]['url'] = $this->createOrderUrl((int)$row['ID']);
            $orders[(int)$row['ID']]['sum'] = (int)$row['PRICE'];
            $orders[(int)$row['ID']]['paid'] = $row['PAYED'];
        }

        ksort($orders);

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
            $createDateTime = DateTime::createFromTimestamp($order['timeCreate']);

            if (empty($order['timesDelivery'])) {
                $events[] = array(
                    'title' => (int)$order['id'],
                    'url' => $order['url'],
                    'start' => $createDateTime->format('Y-m-d\TH:i:s'),
                    'end' => $createDateTime->format('Y-m-d\TH:i:s'),
                    'orderId' => (int)$order['id'],
                    'status' => $statuses[$order['statusId']]['name'],
                    'paid' => $order['paid'] === 'Y',
                    'sum' => $order['sum'],
                    'sumFormat' => number_format($order['sum'], 0, '', ' ').' руб.',
                    'timeCreate' => $order['timeCreate'],
                    'timeDelivery' => '',
                    'timeDeliveryTo' => '',
                    'emptyTimesDelivery' => true,
                    'color' => 'red'
                );
            } else {
                /**
                 * ИЗбавимся от случаев дублирования времени доставки для одного заказа
                 */
                $timesDelivery = array();

                foreach ($order['timesDelivery'] as $key => $timeDelivery) {
                    if (in_array((int)$timeDelivery, $timesDelivery, true)) {
                        continue;
                    }

                    $timesDelivery[] = (int)$timeDelivery;
                    $deliveryDateTime = DateTime::createFromTimestamp($timeDelivery);
                    $timeDeliveryTo = '';
                    $end = $deliveryDateTime;

                    if (!empty($order['timesDeliveryTo'][$key])) {
                        $timeDeliveryTo = $order['timesDeliveryTo'][$key];
                        if ($timeDeliveryTo > $timeDelivery) {
                            $end = DateTime::createFromTimestamp($timeDeliveryTo);
                        }
                    }

                    $test = array(
                        'title' => (int)$order['id'],
                        'url' => $order['url'],
                        'start' => $deliveryDateTime->format('Y-m-d\TH:i:s'),
                        'end' => $end->format('Y-m-d\TH:i:s'),
                        'orderId' => (int)$order['id'],
                        'status' => $statuses[$order['statusId']]['name'],
                        'paid' => $order['paid'] === 'Y',
                        'sum' => $order['sum'],
                        'sumFormat' => number_format($order['sum'], 0, '', ' ').' руб.',
                        'timeCreate' => $order['timeCreate'],
                        'timeDelivery' => $timeDelivery,
                        'timeDeliveryTo' => $timeDeliveryTo,
                        'emptyTimesDelivery' => false,
                    );

                    $events[] = $test;
                }
            }
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

    /**
     * @param array $ordersId
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function getOrdersDeliveryDateTo(array $ordersId): array
    {
        $items = array();

        /**
         * Настраиваем ключи, для правильного соотвествия свойст DELIVERY_DATE и DELIVERY_DATE_TO
         */
        $settings = array(
            'DELIVERY_DATE_TO' => array(
                'code' => 'DELIVERY_DATE_TO',
                'key' => 0,
            ),
            'DELIVERY_DATE_TO_2' => array(
                'code' => 'DELIVERY_DATE_TO_2',
                'key' => 1,
            ),
            'DELIVERY_DATE_TO_3' => array(
                'code' => 'DELIVERY_DATE_TO_3',
                'key' => 2,
            ),
            'DELIVERY_DATE_TO_4' => array(
                'code' => 'DELIVERY_DATE_TO_4',
                'key' => 3,
            ),
            'DELIVERY_DATE_TO_5' => array(
                'code' => 'DELIVERY_DATE_TO_5',
                'key' => 4,
            ),
            'DELIVERY_DATE_TO_6' => array(
                'code' => 'DELIVERY_DATE_TO_6',
                'key' => 5,
            ),
            'DELIVERY_DATE_TO_7' => array(
                'code' => 'DELIVERY_DATE_TO_7',
                'key' => 6,
            ),
            'DELIVERY_DATE_TO_8' => array(
                'code' => 'DELIVERY_DATE_TO_8',
                'key' => 7,
            ),
            'DELIVERY_DATE_TO_9' => array(
                'code' => 'DELIVERY_DATE_TO_9',
                'key' => 8,
            ),
            'DELIVERY_DATE_TO_10' => array(
                'code' => 'DELIVERY_DATE_TO_10',
                'key' => 9,
            ),
            'DELIVERY_DATE_TO_11' => array(
                'code' => 'DELIVERY_DATE_TO_11',
                'key' => 10,
            ),
            'DELIVERY_DATE_TO_12' => array(
                'code' => 'DELIVERY_DATE_TO_12',
                'key' => 11,
            ),
        );
        $result = OrderPropsValueTable::GetList(
            array(
                'order' => array('ORDER_ID' => 'ASC'),
                'filter' => array(
                    'ORDER_ID' => $ordersId,
                    'CODE' => array_keys($settings),
                    '!VALUE' => false,
                ),
                'select' => array('ORDER_ID', 'CODE', 'VALUE'),
            )
        );
        while ($row = $result->Fetch()) {
            $items[(int)$row['ORDER_ID']][$settings[$row['CODE']]['key']] = strtotime($row['VALUE']);
        }

        return $items;
    }
}