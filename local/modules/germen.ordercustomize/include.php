<?php

use \Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    'germen.ordercustomize',
    array(
        'onAdminSaleOrderViewDraggable' => 'classes/general/onAdminSaleOrderViewDraggable.php',
        'onAdminSaleOrderEditDraggable' => 'classes/general/onAdminSaleOrderEditDraggable.php',
        'onAdminSaleOrderCreateDraggable' => 'classes/general/onAdminSaleOrderCreateDraggable.php',
        'onAdminSaleOrderView' => 'classes/general/onAdminSaleOrderView.php',
        'onAdminSaleOrderEdit' => 'classes/general/onAdminSaleOrderEdit.php',
        'onAdminListDisplay' => 'classes/general/onAdminListDisplay.php',
        'onPageStart' => 'classes/general/onPageStart.php',
    )
);
