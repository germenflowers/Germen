<?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
set_time_limit(0);

use \Bitrix\Main\Loader,
    \Bitrix\Main\Application,
    \Bitrix\Main\Web\Cookie;

global $USER, $APPLICATION;

$request = Application::getInstance()->getContext()->getRequest();

$result = [
    "data" => "",
    "error" => false
];

try {

    $action = htmlspecialchars($request->getPost('action'));
    $id = (int)$request->getPost('id');
    $isOrder = $request->getPost('order') === "Y";


    if ($id <= 0) {
        throw new \Exception();
    }

    switch ($action) {
        case 'setDelivery':
            $context = Application::getInstance()->getContext();
            $cookie = new Cookie('USER_DELIVERY_ID', $id);
            $cookie->setDomain($_SERVER["SERVER_NAME"]);
            $cookie->setHttpOnly(false);

            $context->getResponse()->addCookie($cookie);
            $context->getResponse()->flush("");

            $arrDeliv = \PDV\Tools::getDeliveryTime();
            $result['data'] = $arrDeliv[$id];

            break;
        case 'getDetail':

            ob_start();
            $APPLICATION->IncludeComponent(
                "bitrix:catalog.element",
                "popup",
                array(
                    "IBLOCK_ID" => IBLOCK_ID__CATALOG,
                    "ELEMENT_ID" => $id,
                    "PROPERTY_CODE" => [
                        "IMAGES",
                        "COMPOSITION",
                    ],
                    "ACTION_VARIABLE" => "action_var",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "36000000",
                    "CACHE_GROUPS" => "Y",
                    "SET_TITLE" => "N",
                    "PRICE_CODE" => array(
                        0 => "BASE",
                    ),
                    "USE_PRICE_COUNT" => "N",
                    "SHOW_PRICE_COUNT" => "1",
                    "PRICE_VAT_INCLUDE" => "Y",
                    "PRICE_VAT_SHOW_VALUE" => "N",
                    "USE_PRODUCT_QUANTITY" => "Y",
                    "PRODUCT_PROPERTIES" => array(),
                    'CONVERT_CURRENCY' => "N",
                    'HIDE_NOT_AVAILABLE' => "N",
                    'USE_ELEMENT_COUNTER' => "Y",
                    'IS_ORDER' => $isOrder,
                    'CACHE_CLEANER' => $request->getPost("clear_cache") === "Y"
                        ? mt_rand(1, 100) : "",
                ),
                false
            );

            $result['data'] = ob_get_clean();
            break;
    }

} catch (\Exception $e) {

}

echo json_encode($result);
?>