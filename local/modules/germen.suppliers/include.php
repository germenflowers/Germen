<?php

use \Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    'germen.suppliers',
    array(
        'eventHandlers' => 'classes/general/eventHandlers.php',
        'agents' => 'classes/general/agents.php',
    )
);
