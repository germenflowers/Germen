<?php

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$arParams = array(
    "MENU_FILE_PATH" => SITE_DIR."mobileapp/.mobile_menu.php",
);
?>
<?php $APPLICATION->IncludeComponent(
    'bitrix:mobileapp.menu',
    '',
    $arParams,
    false,
    Array('HIDE_ICONS' => 'Y')
); ?>
    <script type="text/javascript">
      app.enableSliderMenu(true);
    </script>
<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");