<?php

namespace Germen\Suppliers;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\SystemException;

/**
 * Class Compliment
 * @package Germen\Suppliers
 */
class Compliment
{
    private $iblockId = IBLOCK_ID__UPSALE;

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
     * @param array $productsId
     * @return array
     */
    public function getItemsId($productsId): array
    {
        $itemsId = array();

        $filter = array('IBLOCK_ID' => $this->iblockId, 'ID' => $productsId);
        $select = array('IBLOCK_ID', 'ID');
        $result = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $itemsId[] = (int)$row['ID'];
        }

        return $itemsId;
    }
}
