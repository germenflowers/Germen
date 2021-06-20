<?php

namespace Germen\Couriers\Iblock;

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;

/**
 * Class Courier
 * @package Germen\Iblock\Couriers
 */
class Courier
{
    private $iblockId = 0;

    /**
     * Courier constructor.
     * @throws LoaderException
     * @throws SystemException
     */
    public function __construct()
    {
        if (!Loader::includeModule('iblock')) {
            throw new SystemException('Не подключен модуль iblock');
        }

        if (!$this->setIblockId()) {
            throw new SystemException('Не найден iblock');
        }
    }

    /**
     * @return bool
     */
    private function setIblockId(): bool
    {
        if (!empty($this->iblockId)) {
            return true;
        }

        $filter = array('CODE' => 'couriers');
        $result = \CIBlock::GetList(array(), $filter);
        if ($row = $result->Fetch()) {
            $this->iblockId = (int)$row['ID'];

            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getList(): array
    {
        $items = array();

        $filter = array('IBLOCK_ID' => $this->iblockId);
        $select = array('IBLOCK_ID', 'ID', 'NAME', 'PROPERTY_PHONE', 'PROPERTY_EMAIL');
        $result = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $items[(int)$row['ID']] = array(
                'id' => (int)$row['ID'],
                'name' => $row['NAME'],
                'phone' => $row['PROPERTY_PHONE_VALUE'],
                'email' => $row['PROPERTY_EMAIL_VALUE'],
            );
        }

        return $items;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        $item = array();

        $filter = array('IBLOCK_ID' => $this->iblockId, 'ID' => $id);
        $select = array('IBLOCK_ID', 'ID', 'NAME', 'PROPERTY_PHONE', 'PROPERTY_EMAIL');
        $result = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        if ($row = $result->Fetch()) {
            $item = array(
                'id' => (int)$row['ID'],
                'name' => $row['NAME'],
                'phone' => $row['PROPERTY_PHONE_VALUE'],
                'email' => $row['PROPERTY_EMAIL_VALUE'],
            );
        }

        return $item;
    }
}
