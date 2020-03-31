<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Db\SqlQueryException;

/**
 * Class germen_suppliers
 */
class germen_suppliers extends CModule
{
    public $MODULE_ID = 'germen.suppliers';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_GROUP_RIGHTS;
    public $PARTNER_NAME;
    public $PARTNER_URI;
    protected $modulePath;

    /**
     * germen_suppliers constructor.
     */
    public function __construct()
    {
        if (!$this->setModulePath()) {
            return;
        }

        Loc::loadMessages(__FILE__);

        $arModuleVersion = array();
        include __DIR__.'/version.php';

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('GERMEN_SUPPLIERS_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('GERMEN_SUPPLIERS_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('GERMEN_SUPPLIERS_PARTNER');
        $this->PARTNER_URI = Loc::getMessage('GERMEN_SUPPLIERS_PARTNER_URI');
    }

    /**
     * @return bool
     */
    protected function setModulePath(): bool
    {
        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$this->MODULE_ID)) {
            $this->modulePath = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$this->MODULE_ID;
        } elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/local/modules/'.$this->MODULE_ID)) {
            $this->modulePath = $_SERVER['DOCUMENT_ROOT'].'/local/modules/'.$this->MODULE_ID;
        } else {
            return false;
        }

        return true;
    }

    /**
     * @throws LoaderException
     */
    public function DoInstall(): void
    {
        $this->InstallDB();
        $this->InstallFiles();
        $this->InstallEvents();
        $this->InstallAgents();
    }

    /**
     *
     */
    public function DoUninstall(): void
    {
        $this->UnInstallAgents();
        $this->UnInstallEvents();
        $this->UnInstallFiles();
        $this->UnInstallDB();
    }

    /**
     * @param array $aParams
     * @return bool
     * @throws LoaderException
     */
    public function InstallDB($aParams = array()): bool
    {
        $this->installSuppliersIblock();
        $this->installProductProperties();
        $this->installOrderStatuses();

        $connection = Application::getConnection();

        $this->installSuppliersTable($connection);

        RegisterModule($this->MODULE_ID);

        return true;
    }

    /**
     * @return bool
     * @throws LoaderException
     */
    public function installSuppliersIblock(): bool
    {
        if (!Loader::includeModule('iblock')) {
            return false;
        }

        $fields = array(
            'IBLOCK_TYPE_ID' => 'germen',
            'LID' => 's1',
            'CODE' => 'suppliers',
            'NAME' => 'Поставщики',
            'LIST_PAGE_URL' => '#SITE_DIR#/',
            'SECTION_PAGE_URL' => '#SITE_DIR#/',
            'DETAIL_PAGE_URL' => '#SITE_DIR#/',
            'INDEX_ELEMENT' => 'N',
            'INDEX_SECTION' => 'N',
            'GROUP_ID' => array(2 => 'R'),
            'FIELDS' => array(
                'CODE' => array(
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => array(
                        'UNIQUE' => 'Y',
                        'TRANSLITERATION' => 'Y',
                        'TRANS_LEN' => 255,
                        'TRANS_CASE' => 'L',
                        'TRANS_SPACE' => '-',
                        'TRANS_OTHER' => '-',
                        'TRANS_EAT' => 'Y',
                        'USE_GOOGLE' => 'N',
                    ),
                ),
                'SECTION_CODE' => array(
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => array(
                        'UNIQUE' => 'Y',
                        'TRANSLITERATION' => 'Y',
                        'TRANS_LEN' => 255,
                        'TRANS_CASE' => 'L',
                        'TRANS_SPACE' => '-',
                        'TRANS_OTHER' => '-',
                        'TRANS_EAT' => 'Y',
                        'USE_GOOGLE' => 'N',
                    ),
                ),
            ),
        );

        $filter = array('CODE' => $fields['CODE']);
        $result = CIBlock::GetList(array(), $filter);
        if ($row = $result->Fetch()) {
            $iblockId = $row['ID'];
        } else {
            $CIBlock = new CIBlock;
            $iblockId = $CIBlock->Add($fields);
        }

        if (!empty($iblockId)) {
            $CIBlockProperty = new CIBlockProperty;

            $properties = array(
                array(
                    'IBLOCK_ID' => $iblockId,
                    'NAME' => 'Телефон',
                    'CODE' => 'PHONE',
                ),
                array(
                    'IBLOCK_ID' => $iblockId,
                    'NAME' => 'Email',
                    'CODE' => 'EMAIL',
                ),
            );

            foreach ($properties as $fields) {
                $filter = array('IBLOCK_ID' => $fields['IBLOCK_ID'], 'CODE' => $fields['CODE']);
                $result = CIBlockProperty::GetList(array(), $filter);
                if (!$result->fetch()) {
                    $CIBlockProperty->Add($fields);
                }
            }
        }

        return true;
    }

    /**
     * @return bool
     * @throws LoaderException
     */
    public function installProductProperties(): bool
    {
        if (!Loader::includeModule('iblock')) {
            return false;
        }

        $productIblockId = 1;
        $complimentsIblockId = 8;

        $suppliersIblockId = 0;
        $filter = array('CODE' => 'suppliers');
        $result = CIBlock::GetList(array(), $filter);
        if ($row = $result->Fetch()) {
            $suppliersIblockId = $row['ID'];
        }

        $CIBlockProperty = new CIBlockProperty;

        $properties = array(
            array(
                'IBLOCK_ID' => $productIblockId,
                'NAME' => 'Состав букета',
                'CODE' => 'COMPOSITION',
                'USER_TYPE' => 'HTML',
            ),
//            array(
//                'IBLOCK_ID' => $productIblockId,
//                'NAME' => 'Комплимент',
//                'CODE' => 'COMPLIMENT',
//                'PROPERTY_TYPE' => 'E',
//                'LINK_IBLOCK_ID' => $complimentsIblockId,
//            ),
            array(
                'IBLOCK_ID' => $productIblockId,
                'NAME' => 'Поставщик',
                'CODE' => 'SUPPLIER',
                'PROPERTY_TYPE' => 'E',
                'LINK_IBLOCK_ID' => $suppliersIblockId,
            ),
//            array(
//                'IBLOCK_ID' => $productIblockId,
//                'NAME' => 'Дата доставки',
//                'CODE' => 'DELIVERY_DATE',
//                'USER_TYPE' => 'DateTime',
//            ),
        );

        foreach ($properties as $fields) {
            $filter = array('IBLOCK_ID' => $fields['IBLOCK_ID'], 'CODE' => $fields['CODE']);
            $result = CIBlockProperty::GetList(array(), $filter);
            if (!$result->fetch()) {
                $CIBlockProperty->Add($fields);
            }
        }

        return true;
    }

    /**
     * @return bool
     * @throws LoaderException
     */
    public function installOrderStatuses(): bool
    {
        if (!Loader::includeModule('sale')) {
            return false;
        }

        $statuses = array();
        $filter = array();
        $select = array('ID');
        $result = \CSaleStatus::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            if (!in_array($row['ID'], $statuses, true)) {
                $statuses[] = $row['ID'];
            }
        }

        $newStatuses = array(
            array(
                'id' => 'SS',
                'name' => 'Отправлен поставщику',
            ),
            array(
                'id' => 'SA',
                'name' => 'Принят поставщиком',
            ),
            array(
                'id' => 'SW',
                'name' => 'В работе у поставщика',
            ),
            array(
                'id' => 'SN',
                'name' => 'Нет в наличие у поставщика',
            ),
            array(
                'id' => 'SP',
                'name' => 'Собран поставщиком',
            ),
            array(
                'id' => 'SC',
                'name' => 'Передан курьеру поставщиком',
            ),
        );

        foreach ($newStatuses as $status) {
            if (in_array($status['id'], $statuses, true)) {
                continue;
            }

            $fields = array(
                'ID' => $status['id'],
                'SORT' => 3000,
                'LANG' => array(
                    array('LID' => 'ru', 'NAME' => $status['name'], 'DESCRIPTION' => ''),
                    array('LID' => 'en', 'NAME' => $status['name'], 'DESCRIPTION' => ''),
                ),
            );

            \CSaleStatus::Add($fields);
        }

        return true;
    }

    /**
     * @param $connection
     */
    public function installSuppliersTable($connection): void
    {
        $tableName = 'germen_suppliers';
        $fields = array(
            'id' => '`id` int unsigned not null auto_increment',
            'supplier_id' => '`supplier_id` int unsigned not null default 0',
            'order_id' => '`order_id` int unsigned not null default 0',
            'products_id' => '`products_id` text collate utf8_unicode_ci',
            'compliments_id' => '`compliments_id` text collate utf8_unicode_ci',
            'delivery_time' => '`delivery_time` timestamp not null default 0',
            'paid' => '`paid` tinyint unsigned not null default 0',
            'comment' => '`comment` text collate utf8_unicode_ci',
            'note' => '`note` text collate utf8_unicode_ci',
            'status' => '`status` text collate utf8_unicode_ci',
            'token' => '`token` text collate utf8_unicode_ci',
            'url' => '`url` text collate utf8_unicode_ci',
        );

        $tableExits = false;
        $sql = "show tables like '".$tableName."'";
        $recordSet = $connection->query($sql);
        while ($record = $recordSet->fetch()) {
            $tableExits = true;
        }

        if ($tableExits) {
            foreach ($fields as $name => $fieldSql) {
                $fieldExist = false;
                $sql = 'show columns from `'.$tableName."` where `Field` = '".$name."'";
                $recordSet = $connection->query($sql);
                while ($record = $recordSet->fetch()) {
                    $fieldExist = true;
                }

                if (!$fieldExist) {
                    $connection->query('alter table `'.$tableName.'` add '.$fieldSql);
                }
            }
        } else {
            $sql = 'create table if not exists '.$tableName.' (';

            foreach ($fields as $name => $fieldSql) {
                $sql .= $fieldSql.',';
            }

            $sql .= 'primary key (id)
) engine = InnoDB collate utf8_unicode_ci;';

            $connection->query($sql);
        }
    }

    /**
     * @param array $aParams
     * @return bool
     * @throws SqlQueryException
     */
    public function UnInstallDB($aParams = array()): bool
    {
//        $connection = Application::getConnection();
//        $sql = 'drop table if exists germen_suppliers;';
//        $connection->query($sql);

        UnRegisterModule($this->MODULE_ID);

        return true;
    }

    /**
     * @param array $arParams
     * @return bool
     */
    public function InstallFiles($arParams = array()): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function UnInstallFiles(): bool
    {
        return true;
    }

    /**
     *
     */
    public function InstallEvents(): void
    {
        $eventManager = EventManager::getInstance();
        $eventManager->registerEventHandler(
            'sale',
            'OnSaleOrderSaved',
            $this->MODULE_ID,
            'eventHandlers',
            'OnSaleOrderSaved'
        );
        $eventManager->registerEventHandler(
            'sale',
            'OnSaleOrderPaid',
            $this->MODULE_ID,
            'eventHandlers',
            'OnSaleOrderPaid'
        );
    }

    /**
     *
     */
    public function UnInstallEvents(): void
    {
        $eventManager = EventManager::getInstance();
        $eventManager->unRegisterEventHandler(
            'main',
            'OnSaleOrderSaved',
            $this->MODULE_ID,
            'eventHandlers',
            'OnSaleOrderSaved'
        );
        $eventManager->unRegisterEventHandler(
            'main',
            'OnSaleOrderPaid',
            $this->MODULE_ID,
            'eventHandlers',
            'OnSaleOrderPaid'
        );
    }

    /**
     *
     */
    public function InstallAgents(): void
    {
        CAgent::AddAgent('agents::sendNotification();', $this->MODULE_ID, 'N', 86400);
    }

    /**
     *
     */
    public function UnInstallAgents(): void
    {
        CAgent::RemoveAgent('agents::sendNotification();', $this->MODULE_ID);
    }
}
