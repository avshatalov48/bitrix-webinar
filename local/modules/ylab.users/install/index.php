<?php
/*
 * Основной файл отвечающий за инсталляцию/деинсталляцию модуля
 */

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\IO\Directory;

Loc::loadMessages(__FILE__);

/**
 * Class ylab_users
 */
class ylab_users extends CModule
{
    public $pathInstallComponents;
    public $pathBitrixComponents;
    public $pathVendor;

    /**
     * ylab_users constructor.
     */
    public function __construct()
    {
        $this->pathInstallComponents = __DIR__ . "/components/";
        $this->pathBitrixComponents = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/";
        $this->pathVendor = "ylab/";

        $arModuleVersion = [];

        include __DIR__ . '/version.php';

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_ID = 'ylab.users';
        $this->MODULE_NAME = Loc::getMessage('MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
    }

    /**
     * Установка модуля
     */
    public function doInstall()
    {
        $this->InstallDB();
        $this->InstallFiles();
        ModuleManager::registerModule($this->MODULE_ID);
    }

    /**
     * Удаление модуля
     */
    public function doUninstall()
    {
        $this->UnInstallDB();
        $this->UnInstallFiles();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    /**
     * Создание таблиц модуля
     */
    public function InstallDB()
    {
        /** @var \CMain $APPLICATION */
        /** @var \CDatabase $DB */
        global $DB, $APPLICATION;

        $oResult = $DB->RunSQLBatch(__DIR__ . '/db/' . strtolower($DB->type) . '/install.sql');
        if (is_array($oResult)) {
            $APPLICATION->ThrowException(implode("", $oResult));
        }
    }

    /**
     * Удаление таблиц модуля
     */
    public function UnInstallDB()
    {
        /** @var \CMain $APPLICATION */
        /** @var \CDatabase $DB */
        global $DB, $APPLICATION;

        $oResult = $DB->RunSQLBatch(__DIR__ . '/db/' . strtolower($DB->type) . '/uninstall.sql');
        if (is_array($oResult)) {
            $APPLICATION->ThrowException(implode("", $oResult));
        }
    }

    /**
     * Инсталяция файлов модуля
     */
    public function InstallFiles()
    {
        if (Directory::isDirectoryExists($this->pathInstallComponents)) {
            CopyDirFiles(
                $this->pathInstallComponents,
                $this->pathBitrixComponents,
                true,
                true
            );
        }
        return true;
    }

    /**
     * Деинсталяция каталогов только необходимого модуля, остальные не трогаем
     */
    public function UnInstallFiles()
    {
        if (Directory::isDirectoryExists($this->pathInstallComponents)) {
            $arComponents = scandir($this->pathInstallComponents . $this->pathVendor);
            foreach ($arComponents as $component) {
                if ($component == "." || $component == "..") {
                    continue;
                }
                $sDeletePath = $this->pathBitrixComponents . $this->pathVendor . $component . "/";
                Directory::deleteDirectory($sDeletePath);
            }
            // Удаление папки вендора, если она пустая
            $sDeletePath = $this->pathBitrixComponents . $this->pathVendor;
            if ([] === (array_diff(scandir($sDeletePath), array(".", "..")))) {
                Directory::deleteDirectory($sDeletePath);
            }
        }
        return true;
    }
}