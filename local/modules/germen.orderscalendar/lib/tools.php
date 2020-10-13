<?php

namespace Germen\Orderscalendar;

use DateTime;
use Exception;

/**
 * Class Tools
 * @package Germen\Orderscalendar
 */
class Tools
{
    public $MODULE_ID = 'germen.orderscalendar';

    /**
     * Tools constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param bool $absolutePath
     * @return string
     */
    public function getModulePath(bool $absolutePath = true): string
    {
        $modulePath = '';

        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$this->MODULE_ID)) {
            $modulePath = ($absolutePath ? $_SERVER['DOCUMENT_ROOT'] : '').'/bitrix/modules/'.$this->MODULE_ID;
        } elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/local/modules/'.$this->MODULE_ID)) {
            $modulePath = ($absolutePath ? $_SERVER['DOCUMENT_ROOT'] : '').'/local/modules/'.$this->MODULE_ID;
        }

        return $modulePath;
    }

    /**
     * @param string $string
     * @param null $timeZone
     * @return DateTime
     * @throws Exception
     */
    public static function parseDateTime(string $string, $timeZone = null): DateTime
    {
        $date = new DateTime(
            $string,
            $timeZone ?: new \DateTimeZone('UTC')
        );

        if ($timeZone) {
            $date->setTimezone($timeZone);
        }

        return $date;
    }

    /**
     * @param $datetime
     * @param null $timeZone
     * @return DateTime
     * @throws Exception
     */
    public static function stripTime($datetime, $timeZone = null): DateTime
    {
        return new DateTime($datetime->format('Y-m-d'), $timeZone ?: new \DateTimeZone('UTC'));
    }
}
