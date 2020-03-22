<?php

$arUrlRewrite = array(
    array(
        'CONDITION' => '#^/suppliers/(.*)#',
        'RULE' => 'token=$1',
        'ID' => '',
        'PATH' => '/suppliers/index.php',
        'SORT' => 100,
    ),
);
