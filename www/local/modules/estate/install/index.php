<?php

IncludeModuleLangFile(__FILE__);

Class estate extends CModule
{
    public $MODULE_ID = 'estate';
    public $MODULE_NAME = 'abc';
    protected $_errors = false;
    protected $_installPath;

    function __construct()
    {
        $this->_installPath = $_SERVER['DOCUMENT_ROOT']
            . '/local/modules/estate/install/';
        $arModuleVersion = array();

        include($this->_installPath . '/version.php');
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = GetMessage("ESTATE_MODULE_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("ESTATE_DESCRIPTION");
    }

    function InstallDB($arParams = array())
    {
        global $DB, $DBType, $APPLICATION;
        $this->_errors = false;
        // Database tables creation
        if (!$DB->Query("SELECT 'x' FROM b_estate_entity WHERE 1=0", true)) {
            $this->_errors = $DB->RunSQLBatch(
                $this->_installPath . 'db/' . strtolower($DB->type) . '/install.sql'
            );
        }
        if ($this->_errors !== false) {
            $APPLICATION->ThrowException(implode('<br>', $this->_errors));
            return false;
        }
        $this->InstallTasks();
        RegisterModule('estate');
        CModule::IncludeModule('estate');

        RegisterModuleDependences('main', 'OnBeforeUserTypeAdd', 'estate', '\Bitrix\Estate\EstateTable', 'OnBeforeUserTypeAdd');
        RegisterModuleDependences('main', 'OnBeforeUserTypeDelete', 'estate', '\Bitrix\Estate\EstateTable', 'OnBeforeUserTypeDelete');
        RegisterModuleDependences('iblock', 'OnIBlockPropertyBuildList', 'estate', 'CIBlockPropertyEstate', 'GetUserTypeDescription');
        return true;
    }

    function UnInstallDB($arParams = array())
    {
        global $DB, $DBType, $APPLICATION;
        $this->_errors = false;
        if (!array_key_exists('save_tables', $arParams)
            || $arParams['save_tables'] != 'Y'
        ) {
            $this->_errors = $DB->RunSQLBatch(
                $this->_installPath . 'db/' . strtolower($DB->type) . '/uninstall.sql'
            );
        }

        UnRegisterModule('estate');

        UnRegisterModuleDependences('main', 'OnBeforeUserTypeAdd', 'estate', '\Bitrix\Estate\EstateTable', 'OnBeforeUserTypeAdd');
        UnRegisterModuleDependences('main', 'OnBeforeUserTypeDelete', 'estate', '\Bitrix\Estate\EstateTable', 'OnBeforeUserTypeDelete');
        UnRegisterModuleDependences('iblock', 'OnIBlockPropertyBuildList', 'estate', 'CIBlockPropertyEstate', 'GetUserTypeDescription');

        if ($this->_errors !== false) {
            $APPLICATION->ThrowException(implode('<br>', $this->_errors));
            return false;
        }
        return true;
    }

    function InstallFiles($arParams = array())
    {
        CopyDirFiles(
            $this->_installPath . 'admin/',
            $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin'
        );
        // CopyDirFiles(
        //     $this->_installPath . 'themes/',
        //     $_SERVER['DOCUMENT_ROOT'] . '/bitrix/themes/',
        //     true,
        //     true
        // );
        // CopyDirFiles(
        //     $this->_installPath . 'components/',
        //     $_SERVER['DOCUMENT_ROOT'] . '/bitrix/components',
        //     true,
        //     true
        // );
        return true;
    }

    function UnInstallFiles()
    {
        DeleteDirFiles(
            $this->_installPath . 'admin/',
            $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin'
        );
        // DeleteDirFiles(
        //     $this->_installPath . 'themes/.default/',
        //     $_SERVER['DOCUMENT_ROOT'] . '/bitrix/themes/.default'
        // );
        //DeleteDirFilesEx('/bitrix/themes/.default/icons/estate/');
        return true;
    }

    function DoInstall()
    {
        global $USER, $APPLICATION;
        if ($USER->IsAdmin() && !IsModuleInstalled("estate")) {
            if ($this->InstallDB()) {
                $this->InstallEvents();
                $this->InstallFiles();
            }
            $GLOBALS['errors'] = $this->_errors;
            $APPLICATION->IncludeAdminFile(
                GetMessage('ESTATE_INSTALL_TITLE'),
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
        if ($step < 2) {
            $APPLICATION->IncludeAdminFile(
                GetMessage('ESTATE_UNINSTALL_TITLE'),
                $this->_installPath . 'unstep1.php'
            );
            return;
        }
        $this->UnInstallDB(array(
            "save_tables" => $_REQUEST["save_tables"],
        ));
        $this->UnInstallEvents();
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
