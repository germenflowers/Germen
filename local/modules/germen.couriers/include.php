<?php

use \Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    'germen.couriers',
    array(
        'germenCouriersEventHandlers' => 'classes/general/eventHandlers.php',
    )
);
