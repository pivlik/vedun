<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (!isset($arParams['CACHE_TIME'])) {
    $arParams['CACHE_TIME'] = 3600;
}

use Bitrix\Estate as Estate;

if ($this->StartResultCache($arParams['CACHE_TIME'])) {
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

    if (!empty($_REQUEST['getResult'])) {
        parse_str($_REQUEST['getResult'], $params);
        $_REQUEST += $params;
    }

    $flatsInstance = Estate\EstateFlatTable::getInstance();

    $filter = $flatsInstance->getSearchRequest();

    // Данные для формы поиска
    $arResult = $flatsInstance->getSearchForm();

    $arResult['BORDERS'] = $flatsInstance->getFilterBorders($filter);

    $arResult['HOME_PATH'] = '/';
    $arResult['COMPASS_IMAGE'] = '';

    $res = Estate\EstateObjectTable::getById($arParams['objectId']);
    $object = $res->fetch();
    if ($object['COMPASS_IMAGE']) {
        $arResult['COMPASS_IMAGE'] = Estate\Common::_getImageOrPlaceholder($object['COMPASS_IMAGE'], 100, 100);
    }

    $this->IncludeComponentTemplate();

}
