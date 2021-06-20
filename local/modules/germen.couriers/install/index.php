<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Db\SqlQueryException;

/**
 * Class germen_couriers
 */
class germen_couriers extends CModule
{
    public $MODULE_ID = 'germen.couriers';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_GROUP_RIGHTS;
    public $PARTNER_NAME;
    public $PARTNER_URI;
    protected $modulePath;

    /**
     * germen_couriers constructor.
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
        $this->MODULE_NAME = Loc::getMessage('GERMEN_COURIERS_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('GERMEN_COURIERS_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('GERMEN_COURIERS_PARTNER');
        $this->PARTNER_URI = Loc::getMessage('GERMEN_COURIERS_PARTNER_URI');
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
     * @throws SqlQueryException
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
    public function InstallDB(array $aParams = array()): bool
    {
        $connection = Application::getConnection();

        $this->installCouriersTable($connection);

        RegisterModule($this->MODULE_ID);

        return true;
    }

    /**
     * @param $connection
     */
    public function installCouriersTable($connection): void
    {
        $tableName = 'germen_couriers';
        $fields = array(
            'id' => '`id` int unsigned not null auto_increment',
            'courier_id' => '`courier_id` int unsigned not null default 0',
            'order_id' => '`order_id` int unsigned not null default 0',
            'date_create' => '`date_create` timestamp not null default current_timestamp',
            'phone' => '`phone` text collate utf8_unicode_ci',
            'email' => '`email` text collate utf8_unicode_ci',
            'token' => '`token` text collate utf8_unicode_ci',
            'url' => '`url` text collate utf8_unicode_ci',
            'send_notification' => '`send_notification` tinyint unsigned not null default 0',
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
    public function UnInstallDB(array $aParams = array()): bool
    {
//        $connection = Application::getConnection();
//        $sql = 'drop table if exists germen_couriers;';
//        $connection->query($sql);

        UnRegisterModule($this->MODULE_ID);

        return true;
    }

    /**
     * @param array $arParams
     * @return bool
     */
    public function InstallFiles(array $arParams = array()): bool
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
            'OnSaleOrderBeforeSaved',
            $this->MODULE_ID,
            'germenCouriersEventHandlers',
            'OnSaleOrderBeforeSaved'
        );
    }

    /**
     *
     */
    public function UnInstallEvents(): void
    {
        $eventManager = EventManager::getInstance();
        $eventManager->unRegisterEventHandler(
            'sale',
            'OnSaleOrderBeforeSaved',
            $this->MODULE_ID,
            'germenCouriersEventHandlers',
            'OnSaleOrderBeforeSaved'
        );
    }

    /**
     *
     */
    public function InstallAgents(): void
    {
    }

    /**
     *
     */
    public function UnInstallAgents(): void
    {
    }
}
