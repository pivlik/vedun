<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

if(!isset($arParams['CACHE_TIME'])) {
    $arParams['CACHE_TIME'] = 3600;
}

if (empty($arParams['ID'])) {
    @define("ERROR_404", "Y");
    CHTTP::SetStatus("404 Not Found");
    return;
}

use Bitrix\Estate as Estate;

if($this->StartResultCache($arParams['CACHE_TIME'], $arParams['ID'])) {

    if (!CModule::IncludeModule("iblock")) {
        $this->AbortResultCache();
        ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
        return;
    }

    $requiredModules = array('estate');
    foreach ($requiredModules as $requiredModule) {
        if (!CModule::IncludeModule($requiredModule)) {
            ShowError(GetMessage('F_NO_MODULE'));
            return;
        }
    }

    $premise = Estate\EstatePremiseTable::getInstance();
    $params = array(
        'filter' => array(
            'ACTIVE' => 'Y',
            'ID'     => $arParams['ID'],
        ),
    );
    $arResult['ITEM'] = $premise->getPremises($params);
    $arResult['ITEM'] = array_shift($arResult['ITEM']);

    $this->IncludeComponentTemplate();

}

