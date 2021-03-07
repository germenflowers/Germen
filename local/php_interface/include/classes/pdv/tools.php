<?php

namespace PDV;

use \Bitrix\Main\Loader;
use \Bitrix\Main\Data\Cache;

class Tools
{
    public const DEFAULT_TIME = 'от 60 мин.';
    private static $_aStorage = array();

    public static function isHomePage(): bool
    {
        global $APPLICATION;

        return $APPLICATION->GetCurPage(true) === '/index.php';
    }

    public static function is404(): bool
    {
        global $APPLICATION;

        if (defined('ERROR_404')) {
            return true;
        }

        return $APPLICATION->GetCurPage() === SITE_DIR.'404.php';
    }

    public static function isArticlePage(): bool
    {
        global $APPLICATION;

        return (
            $APPLICATION->GetCurPage() === SITE_DIR.'contacts/' ||
            $APPLICATION->GetCurPage() === SITE_DIR.'payment/'
        );
    }

    public static function isCarePage(): bool
    {
        global $APPLICATION;

        return $APPLICATION->GetCurPage() === SITE_DIR.'care/';
    }

    public static function isCartPage(): bool
    {
        global $APPLICATION;

        return $APPLICATION->GetCurPage() === SITE_DIR.'cart/';
    }

    public static function isOrderPage(): bool
    {
        return \CSite::InDir(SITE_DIR.'order/');
    }

    public static function isSubscribePage(): bool
    {
        global $APPLICATION;

        return $APPLICATION->GetCurPage() === SITE_DIR.'subscribe/' ||
            $APPLICATION->GetCurPage() === SITE_DIR.'subscribe/choice/';
    }

    public static function isTextPage(): bool
    {
        global $APPLICATION;

        return $APPLICATION->GetCurPage() === SITE_DIR.'about/' ||
            $APPLICATION->GetCurPage() === SITE_DIR.'agreement/' ||
            $APPLICATION->GetCurPage() === SITE_DIR.'delivery/';
    }

    public static function isFavoritePage(): bool
    {
        global $APPLICATION;

        return $APPLICATION->GetCurPage() === SITE_DIR.'favorites/';
    }

    /**
     * Склонение слов
     *
     * @param            $digit
     * @param            $expr
     * @param bool|false $onlyword
     *
     * @return string
     */
    public static function Declension($digit, $expr, $onlyword = false): string
    {
        if (!is_array($expr)) {
            $expr = array_filter(
                explode(
                    ' ',
                    $expr
                )
            );
        }

        if (empty ($expr [2])) {
            $expr [2] = $expr [1];
        }

        $i = preg_replace(
                '/[^0-9]+/s',
                '',
                $digit
            ) % 100;

        if ($onlyword) {
            $digit = '';
        }

        if ($i >= 5 && $i <= 20) {
            $res = $digit.' '.$expr [2];
        } else {
            $i %= 10;
            if ($i == 1) {
                $res = $digit.' '.$expr [0];
            } elseif ($i >= 2 && $i <= 4) {
                $res = $digit.' '.$expr [1];
            } else {
                $res = $digit.' '.$expr [2];
            }
        }

        return trim($res);
    }

    public static function getDeliveryTime()
    {
        $cache = Cache::createInstance();
        if ($cache->initCache(86400, "delivery_time")) {
            $arr = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            Loader::includeModule('sale');

            $arr = [];
            $res = \Bitrix\Sale\Delivery\Services\Table::getList(
                array(
                    'order' => array('SORT' => 'ASC'),
                    'filter' => array('ACTIVE' => 'Y'),
                )
            );
            while ($arDeliv = $res->Fetch()) {
                if ($arDeliv['CONFIG']['MAIN']['PERIOD']['TO'] > 0) {
                    $type = '';
                    switch ($arDeliv['CONFIG']['MAIN']['PERIOD']['TYPE']) {
                        case 'MIN':
                            $type = 'мин.';
                            break;
                        case 'H':
                            $type = 'ч.';
                            break;
                        case 'D':
                            $type = 'дн.';
                            break;
                        case 'M':
                            $type = 'мес.';
                            break;
                    }
                    $arr[$arDeliv['ID']] = array(
                        'NAME' => $arDeliv['NAME'],
                        'TIME' => $arDeliv['CONFIG']['MAIN']['PERIOD']['TO'].' '.$type,
                        'TIME_NUMBER' => $arDeliv['CONFIG']['MAIN']['PERIOD']['TO'],
                    );
                }
            }

            $cache->endDataCache($arr);
        }

        return $arr;
    }

    public function getTimeByDelivery()
    {
        $time = '';
        $id = intval(\Bitrix\Main\Context::getCurrent()->getRequest()->getCookie('USER_DELIVERY_ID'));
        if ($id > 0) {
            $arrDeliv = self::getDeliveryTime();
            if (isset($arrDeliv[$id]['TIME'])) {
                $time = $arrDeliv[$id]['TIME'];
            }
        } else {
            $time = self::DEFAULT_TIME;
        }

        return $time;
    }

    public function getInstagramPosts()
    {
        $cache = Cache::createInstance();
        if ($cache->initCache(86400, "ig_posts")) {
            $arrVars = $cache->getVars();
            $arr = $arrVars['data'];
        } elseif ($cache->startDataCache()) {
            Loader::includeModule('sale');
            $arr = \PDV\Instagram::getPosts();

            $cache->endDataCache(array('data' => $arr));
        }

        return $arr;
    }

    /*
    * Агент отправки смс, если заказ не оплачен
    */
    public function sendSmsNotPayedOrder()
    {
        Loader::includeModule('sale');

        $dateFrom = new \Bitrix\Main\Type\DateTime();
        $dateTo = new \Bitrix\Main\Type\DateTime();
        $dateFrom->add('-'. 11 .' minutes');
        $dateTo->add('-'. 10 .' minutes');

        $filter = array('><DATE_INSERT' => array($dateFrom->format('d.m.Y H:i:s'), $dateTo->format('d.m.Y H:i:s')));
        $filter['PAYED'] = 'N';

        $ordersIterator = \Bitrix\Sale\Internals\OrderTable::getList(
            array(
                'select' => array('ID'),
                'filter' => $filter,
                'order' => array('ID' => 'ASC'),
            )
        );
        while ($order = $ordersIterator->fetch()) {
            $phone = '';
            $rsPropsValue = \Bitrix\Sale\Internals\OrderPropsValueTable::getList(
                array(
                    'filter' => array('ORDER_ID' => $order['ID'], 'CODE' => 'PHONE'),
                    'select' => array('VALUE'),
                )
            );
            if ($arPropsValue = $rsPropsValue->fetch()) {
                $phone = trim(str_replace(array('+', '-', '(', ')', ' '), '', $arPropsValue['VALUE']));
            }

            if (!empty($phone)) {
                $textSms = '';
                $rsElem = \CIBlockElement::GetList(
                    array('sort' => 'asc', 'id' => 'desc'),
                    array('IBLOCK_ID' => IBLOCK_ID__SMS, '=CODE' => 'NOT_PAYED'),
                    false,
                    array('nPageSize' => 1),
                    array('PREVIEW_TEXT')
                );
                if ($arElem = $rsElem->GetNext()) {
                    $textSms = str_replace(
                        array('#ORDER_ID#'),
                        array($order['ID']),
                        $arElem['PREVIEW_TEXT']
                    );
                }

                if (!empty($textSms)) {
                    if ($handle = fopen($_SERVER["DOCUMENT_ROOT"].'/upload/logChangeOrder.txt', 'a+')) {
                        fwrite($handle, $phone." ".$textSms."\n");
                        fclose($handle);
                    }

                    \PDV\Smsaero::sendSMS($phone, $textSms);
                }
            }
        }

        return "\\PDV\\Tools::sendSmsNotPayedOrder();";
    }

    /**
     * Метод записывает данные в хранилище
     * @param string $sStorageKey - ключ к значению
     * @param $mData - значение
     */
    public static function setStorage(string $sStorageKey, $mData): void
    {
        self::$_aStorage[$sStorageKey] = $mData;
    }

    /**
     * Метод получает данные из хранилища
     * @param string $sStorageKey - ключ к значению
     * @return bool|mixed
     */
    public static function getStorage(string $sStorageKey)
    {
        if (!(isset(self::$_aStorage[$sStorageKey]) && self::$_aStorage[$sStorageKey])) {
            return false;
        }

        return self::$_aStorage[$sStorageKey];
    }
}