<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\Db\SqlQueryException;

/**
 * Class germen_ordercustomize
 */
class germen_ordercustomize extends CModule
{
    public $MODULE_ID = 'germen.ordercustomize';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_GROUP_RIGHTS;
    public $PARTNER_NAME;
    public $PARTNER_URI;
    protected $modulePath;

    /**
     * germen_ordercustomize constructor.
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
        $this->MODULE_NAME = Loc::getMessage('ORDER_CUSTOMIZE_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('ORDER_CUSTOMIZE_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('ORDER_CUSTOMIZE_PARTNER');
        $this->PARTNER_URI = Loc::getMessage('ORDER_CUSTOMIZE_PARTNER_URI');
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
     * @throws SqlQueryException
     */
    public function DoInstall(): void
    {
        $this->InstallDB();
        $this->InstallFiles();
        $this->InstallEvents();
    }

    /**
     * @throws SqlQueryException
     */
    public function DoUninstall(): void
    {
        $this->UnInstallEvents();
        $this->UnInstallFiles();
        $this->UnInstallDB();
    }

    /**
     * @param array $aParams
     * @return bool
     * @throws SqlQueryException
     */
    public function InstallDB(array $aParams = array()): bool
    {
        $connection = Application::getConnection();

        RegisterModule($this->MODULE_ID);

        return true;
    }

    /**
     * @param array $aParams
     * @return bool
     */
    public function UnInstallDB(array $aParams = array()): bool
    {
        $connection = Application::getConnection();

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
            'main',
            'OnAdminSaleOrderViewDraggable',
            $this->MODULE_ID,
            'onAdminSaleOrderViewDraggable',
            'onInit'
        );
        $eventManager->registerEventHandler(
            'main',
            'OnAdminSaleOrderEditDraggable',
            $this->MODULE_ID,
            'onAdminSaleOrderEditDraggable',
            'onInit'
        );
        $eventManager->registerEventHandler(
            'main',
            'OnAdminSaleOrderCreateDraggable',
            $this->MODULE_ID,
            'onAdminSaleOrderCreateDraggable',
            'onInit'
        );
        $eventManager->registerEventHandler(
            'main',
            'OnAdminSaleOrderView',
            $this->MODULE_ID,
            'onAdminSaleOrderView',
            'onInit'
        );
        $eventManager->registerEventHandler(
            'main',
            'OnAdminSaleOrderEdit',
            $this->MODULE_ID,
            'onAdminSaleOrderEdit',
            'onInit'
        );
        $eventManager->registerEventHandler(
            'main',
            'OnAdminListDisplay',
            $this->MODULE_ID,
            'onAdminListDisplay',
            'onInit'
        );
        $eventManager->registerEventHandler(
            'main',
            'OnPageStart',
            $this->MODULE_ID,
            'onPageStart',
            'onInit'
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
            'OnAdminSaleOrderViewDraggable',
            $this->MODULE_ID,
            'onAdminSaleOrderViewDraggable',
            'onInit'
        );
        $eventManager->unRegisterEventHandler(
            'main',
            'OnAdminSaleOrderEditDraggable',
            $this->MODULE_ID,
            'onAdminSaleOrderEditDraggable',
            'onInit'
        );
        $eventManager->unRegisterEventHandler(
            'main',
            'OnAdminSaleOrderCreateDraggable',
            $this->MODULE_ID,
            'onAdminSaleOrderCreateDraggable',
            'onInit'
        );
        $eventManager->unRegisterEventHandler(
            'main',
            'OnAdminSaleOrderView',
            $this->MODULE_ID,
            'onAdminSaleOrderView',
            'onInit'
        );
        $eventManager->unRegisterEventHandler(
            'main',
            'OnAdminSaleOrderEdit',
            $this->MODULE_ID,
            'onAdminSaleOrderEdit',
            'onInit'
        );
        $eventManager->unRegisterEventHandler(
            'main',
            'OnAdminListDisplay',
            $this->MODULE_ID,
            'onAdminListDisplay',
            'onInit'
        );
        $eventManager->unRegisterEventHandler(
            'main',
            'OnPageStart',
            $this->MODULE_ID,
            'onPageStart',
            'onInit'
        );
    }
}
