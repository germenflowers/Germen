<?php

namespace Germen\Suppliers;

use Bitrix\Main\Config\Option;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use ShortCode\Random;

/**
 * Class Tools
 * @package Germen\Suppliers
 */
class Tools
{
    /**
     * Tools constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     */
    public static function getSiteUrl(): string
    {
        return Option::get('main', 'server_name');
    }

    /**
     * @return string
     */
    public static function getToken(): string
    {
        return Random::get();
    }

    /**
     * @param string $token
     * @return string
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     */
    public static function createUrl($token): string
    {
        $siteUrl = self::getSiteUrl();

        return 'https://'.$siteUrl.'/suppliers/'.$token;
    }
}
