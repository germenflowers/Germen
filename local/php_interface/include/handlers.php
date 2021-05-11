<?php

use \Bitrix\Main\EventManager;
use \Germen\Handlers\Main;
use \Germen\Handlers\Sale;
use \PDV\Handlers\IBlock;
use \PDV\Handlers\Sale as PdvSale;

$eventManager = EventManager::getInstance();

$eventManager->addEventHandler(
    'main',
    'OnAfterUserAdd',
    array(Main::class, 'OnAfterUserAdd')
);

$eventManager->addEventHandler(
    'main',
    'OnAfterUserUpdate',
    array(Main::class, 'OnAfterUserUpdate')
);

$eventManager->addEventHandler(
    'sale',
    'OnBeforeOrderAdd',
    array(Sale::class, 'OnBeforeOrderAdd')
);

$eventManager->addEventHandler(
    'sale',
    'OnOrderSave',
    array(Sale::class, 'OnOrderSave')
);

$eventManager->addEventHandler(
    'sale',
    'OnSaleStatusOrderChange',
    array(PdvSale::class, 'changeStatus')
);

$eventManager->addEventHandler(
    'sale',
    'OnSaleOrderCanceled',
    array(PdvSale::class, 'orderCancel')
);

$eventManager->addEventHandler(
    'sale',
    'OnSaleOrderPaid',
    array(PdvSale::class, 'orderPaid')
);

$eventManager->addEventHandler(
    'iblock',
    'OnBeforeIBlockElementAdd',
    array(IBlock::class, 'checkUpSaleImage')
);

$eventManager->addEventHandler(
    'iblock',
    'OnBeforeIBlockElementUpdate',
    array(IBlock::class, 'checkUpSaleImage')
);

