<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Db\SqlQueryException;

/**
 * Class germen_orderscalendar
 */
class germen_orderscalendar extends CModule
{
    public $MODULE_ID = 'germen.orderscalendar';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_GROUP_RIGHTS;
    public $PARTNER_NAME;
    public $PARTNER_URI;
    protected $modulePath;

    /**
     * germen_orderscalendar constructor.
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
        $this->MODULE_NAME = Loc::getMessage('GERMEN_ORDERSCALENDAR_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('GERMEN_ORDERSCALENDAR_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('GERMEN_ORDERSCALENDAR_PARTNER');
        $this->PARTNER_URI = Loc::getMessage('GERMEN_ORDERSCALENDAR_PARTNER_URI');
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
     */
    public function InstallDB($aParams = array()): bool
    {
        $connection = Application::getConnection();

        RegisterModule($this->MODULE_ID);

        return true;
    }

    /**
     * @param array $aParams
     * @return bool
     */
    public function UnInstallDB($aParams = array()): bool
    {
        $connection = Application::getConnection();

        UnRegisterModule($this->MODULE_ID);

        return true;
    }

    /**
     * @param array $arParams
     * @return bool
     */
    public function InstallFiles($arParams = array()): bool
    {
        CopyDirFiles(
            $this->modulePath.'/install/admin/',
            $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin',
            true,
            true
        );

        return true;
    }

    /**
     * @return bool
     */
    public function UnInstallFiles(): bool
    {
        DeleteDirFiles(
            $this->modulePath.'/install/admin/',
            $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin'
        );

        return true;
    }

    /**
     *
     */
    public function InstallEvents(): void
    {
        $eventManager = EventManager::getInstance();
    }

    /**
     *
     */
    public function UnInstallEvents(): void
    {
        $eventManager = EventManager::getInstance();
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
