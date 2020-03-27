<?php

namespace Germen;

use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

/**
 * Class BuyButton
 * @package Germen
 */
class BuyButton
{
    private $defaultParams = array(
        'background' => 'fee6d2',
        'text' => 'Заказать',
        'time' => '120 мин.',
        'showIcon' => true,
    );

    private $iblockId;

    /**
     * BuyButton constructor.
     * @throws SystemException
     * @throws LoaderException
     */
    public function __construct()
    {
        if (!Loader::includeModule('iblock')) {
            throw new SystemException('Не подключен модуль iblock');
        }

        $this->iblockId = $this->getIblockId();

        if (empty($this->iblockId)) {
            throw new SystemException('Не указан ид инфоблока');
        }
    }

    /**
     * @return int
     * @throws SystemException
     * @throws ArgumentException
     * @throws ObjectPropertyException
     */
    public function getIblockId(): int
    {
        $iblockId = 0;

        $result = IblockTable::getList(
            array(
                'filter' => array('CODE' => 'buybutton'),
                'select' => array('ID'),
            )
        );
        if ($row = $result->fetch()) {
            $iblockId = (int)$row['ID'];
        }

        return $iblockId;
    }

    /**
     * @return array
     */
    public function getList(): array
    {
        $items = array();

        $filter = array('IBLOCK_ID' => $this->iblockId, 'ACTIVE' => 'Y');
        $select = array(
            'IBLOCK_ID',
            'ID',
            'PROPERTY_BACKGROUND',
            'PROPERTY_TEXT',
            'PROPERTY_TIME',
            'PROPERTY_SHOW_ICON',
        );
        $result = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $items[(int)$row['ID']] = array(
                'id' => (int)$row['ID'],
                'background' => $row['PROPERTY_BACKGROUND_VALUE'],
                'text' => $row['PROPERTY_TEXT_VALUE'],
                'time' => $row['PROPERTY_TIME_VALUE'],
                'showIcon' => !empty($row['PROPERTY_SHOW_ICON_VALUE']),
            );
        }

        return $items;
    }

    /**
     * @return array
     */
    public function getDefaultParams(): array
    {
        $defaultParams = $this->defaultParams;

        $filter = array('IBLOCK_ID' => $this->iblockId, 'CODE' => 'default');
        $select = array(
            'IBLOCK_ID',
            'ID',
            'PROPERTY_BACKGROUND',
            'PROPERTY_TEXT',
            'PROPERTY_TIME',
            'PROPERTY_SHOW_ICON',
        );
        $result = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        if ($row = $result->Fetch()) {
            $defaultParams['background'] = $row['PROPERTY_BACKGROUND_VALUE'];
            $defaultParams['text'] = $row['PROPERTY_TEXT_VALUE'];
            $defaultParams['time'] = $row['PROPERTY_TIME_VALUE'];
            $defaultParams['showIcon'] = !empty($row['PROPERTY_SHOW_ICON_VALUE']);
        }

        return $defaultParams;
    }
}
