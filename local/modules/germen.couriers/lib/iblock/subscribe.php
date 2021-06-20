<?php

namespace Germen\Couriers\Iblock;

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;

/**
 * Class Subscribe
 * @package Germen\Couriers\Iblock
 */
class Subscribe
{
    private $iblockProductsId = IBLOCK_ID__SUBSCRIBE;
    private $iblockOffersId = IBLOCK_ID__SUBSCRIBE_OFFERS;

    /**
     * Compliment constructor.
     * @throws SystemException
     * @throws LoaderException
     */
    public function __construct()
    {
        if (!Loader::includeModule('iblock')) {
            throw new SystemException('Не подключен модуль iblock');
        }
    }

    /**
     * @param string $type
     * @return array
     */
    public static function getImage(string $type): array
    {
        $image = array('src' => '');

        if ($type === 'Монобукеты') {
            $image = array('src' => SITE_TEMPLATE_PATH.'/img/mono.jpg');
        }
        if ($type === 'Составные букеты') {
            $image = array('src' => SITE_TEMPLATE_PATH.'/img/compose.jpg');
        }

        return $image;
    }

    /**
     * @param array $items
     * @return array
     */
    public static function getImages(array $items): array
    {
        foreach ($items as $id => $item) {
            $items[$id]['image'] = self::getImage($item['type']);
        }

        return $items;
    }

}
