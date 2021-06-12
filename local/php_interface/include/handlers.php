<?php

$eventManager = \Bitrix\Main\EventManager::getInstance();

$eventManager->addEventHandler(
    'sale',
    'OnSaleStatusOrderChange',
    array(
        '\\PDV\\Handlers\\Sale',
        'changeStatus',
    )
);

$eventManager->addEventHandler(
    'sale',
    'OnSaleOrderCanceled',
    array(
        '\\PDV\\Handlers\\Sale',
        'orderCancel',
    )
);

$eventManager->addEventHandler(
    'sale',
    'OnSaleOrderPaid',
    array(
        '\\PDV\\Handlers\\Sale',
        'orderPaid',
    )
);

$eventManager->addEventHandler(
    'iblock',
    'OnBeforeIBlockElementAdd',
    array(
        '\\PDV\\Handlers\\IBlock',
        'checkUpSaleImage',
    )
);

$eventManager->addEventHandler(
    'iblock',
    'OnBeforeIBlockElementUpdate',
    array(
        '\\PDV\\Handlers\\IBlock',
        'checkUpSaleImage',
    )
);

$eventManager->addEventHandler(
    'sale',
    'OnBeforeOrderAdd',
    array(
        '\\Germen\\Handlers\\Sale',
        'OnBeforeOrderAdd',
    )
);

$eventManager->addEventHandler(
    'sale',
    'OnOrderSave',
    array(
        '\\Germen\\Handlers\\Sale',
        'OnOrderSave',
    )
);
