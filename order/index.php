<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оформление заказа");?>

<?$APPLICATION->IncludeComponent(
	"pdv:order", 
	".default", 
	array(
		"ID" => intval($_REQUEST["id"]),
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>