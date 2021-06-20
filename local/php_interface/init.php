<?php

$includeFilesList = array(
    $_SERVER['DOCUMENT_ROOT'].'/local/php_interface/include/defines.php',
    $_SERVER['DOCUMENT_ROOT'].'/local/php_interface/include/autoload.php',
    $_SERVER['DOCUMENT_ROOT'].'/local/php_interface/include/handlers.php',
);

foreach ($includeFilesList as $fileToInclude) {
    if (file_exists($fileToInclude)) {
        include_once($fileToInclude);
    }
}

require_once dirname(__DIR__, 2).'/vendor/autoload.php';
