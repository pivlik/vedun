<?php

IncludeModuleLangFile(__FILE__);

Class kelnik_counters extends CModule
{
    public $MODULE_ID = 'kelnik.counters';
    public $MODULE_NAME = 'Kelnik counters';
    protected $_errors = false;
    protected $_installPath;

    function __construct()
    {
        $this->_installPath = $_SERVER['DOCUMENT_ROOT']
            . '/local/modules/kelnik.counters/install/';
        $arModuleVersion = array();

        include($this->_installPath . '/version.php');
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = GetMessage("KELNIK_COUNTERS_MODULE_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("KELNIK_COUNTERS_MODULE_DESCRIPTION");
    }

    function InstallDB($arParams = array())
    {
        global $DB, $DBType, $APPLICATION;
        RegisterModule('kelnik.counters');
        return true;
    }

    function UnInstallDB($arParams = array())
    {
        global $DB, $DBType, $APPLICATION;
        $this->_errors = false;
        UnRegisterModule('kelnik.counters');
//        if ($this->_errors !== false) {
//            $APPLICATION->ThrowException(implode('<br>', $this->_errors));
//            return false;
//        }
        return true;
    }

    function InstallFiles($arParams = array())
    {
        CopyDirFiles(
            $this->_installPath . 'admin/',
            $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin'
        );
        return true;
    }

    function UnInstallFiles()
    {
//        DeleteDirFiles(
//            $this->_installPath . 'admin/',
//            $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin'
//        );
        return true;
    }

    function DoInstall()
    {
        global $USER, $APPLICATION;
        if ($USER->IsAdmin() && !IsModuleInstalled("kelnik.counters")) {
            if ($this->InstallDB()) {
//                $this->InstallEvents();
                $this->InstallFiles();
            }
            $GLOBALS['errors'] = $this->_errors;
            $APPLICATION->IncludeAdminFile(
                GetMessage('KELNIK_COUNTERS_INSTALL_TITLE'),
                $this->_installPath . 'step.php'
            );
        }
    }

    function DoUninstall()
    {
        global $USER, $DB, $APPLICATION, $step;
        $step = (int) $step;
        if (!$USER->IsAdmin()) {
            return;
        }
//        if ($step < 2) {
//            $APPLICATION->IncludeAdminFile(
//                GetMessage('KELNIK_COUNTERS_INSTALL_TITLE'),
//                $this->_installPath . 'unstep1.php'
//            );
//            return;
//        }
//        $this->UnInstallDB(array(
//            "save_tables" => $_REQUEST["save_tables"],
//        ));
//        $this->UnInstallEvents();
        $this->UnInstallDB();
        $this->UnInstallFiles();
    }

    function GetModuleTasks()
    {
        return array();
    }

    function InstallEvents()
    {
        return true;
    }

    function UnInstallEvents()
    {
        return true;
    }
}
