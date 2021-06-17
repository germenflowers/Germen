<?php

$arUrlRewrite = array(
    array(
        'CONDITION' => '#^/couriers/(.*)#',
        'RULE' => 'token=$1',
        'ID' => '',
        'PATH' => '/couriers/index.php',
        'SORT' => 100,
    ),
    array(
        'CONDITION' => '#^/suppliers/(.*)#',
        'RULE' => 'token=$1',
        'ID' => '',
        'PATH' => '/suppliers/index.php',
        'SORT' => 100,
    ),
    array(
        'CONDITION' => '#^/admin/orders/#',
        'RULE' => '',
        'ID' => 'germen:admin.order',
        'PATH' => '/admin/orders/index.php',
        'SORT' => 100,
    ),
    array(
        'CONDITION' => '#^/admin/history/#',
        'RULE' => '',
        'ID' => 'germen:admin.order.history',
        'PATH' => '/admin/history/index.php',
        'SORT' => 100,
    ),
);
