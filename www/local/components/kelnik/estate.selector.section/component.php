<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

if (!CModule::IncludeModule("iblock")) {
	$this->AbortResultCache();
	ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
	return;
}

if (empty($arParams['SECTION_ID']) || empty($arParams['BUILDING_ID'])) {
    @define("ERROR_404", "Y");
    CHTTP::SetStatus("404 Not Found");
    return;
}

if(!isset($arParams['CACHE_TIME'])) {
	$arParams['CACHE_TIME'] = 3600;
}

$requiredModules = array('estate');
foreach ($requiredModules as $requiredModule) {
	if (!CModule::IncludeModule($requiredModule)) {
		ShowError(GetMessage('F_NO_MODULE'));
		return;
	}
}

use Bitrix\Estate as Estate;

// Данные для формы поиска
$flatsInstance = Estate\EstateFlatTable::getInstance();
$arResult = $flatsInstance->getSearchForm();

// Карта активных элементов недвижимости
$activeMap = Estate\BaseEstate::getActiveElementsMap();

// Активна ли секция
if (!isset($activeMap['SECTIONS'][$arParams['SECTION_ID']])) {
    @define("ERROR_404", "Y");
    CHTTP::SetStatus("404 Not Found");
    return;
}

// Данные секции
$res = Estate\EstateSectionTable::getById($arParams['SECTION_ID']);
$section = $res->fetch();

// Правильный ли в урле ID корпуса
if ($section['PARENT'] !== $arParams['BUILDING_ID']) {
    @define("ERROR_404", "Y");
    CHTTP::SetStatus("404 Not Found");
    return;
}


if (isset($_GET['AJAX']) && $_GET['AJAX'] === 'Y') {
    $APPLICATION->RestartBuffer();
    header('Content-type:application/json; charset=UTF-8');

    if (!empty($_REQUEST['getResult'])) {
        parse_str($_REQUEST['getResult'], $params);
        $_REQUEST += $params;
    }
    $filter = $flatsInstance->getSearchRequest();

    $floorInstance = Estate\EstateFloorTable::getInstance();
    $json = $floorInstance->getJson($section, $filter);

    echo json_encode($json);
    return;
}

$arResult['HOME_PATH']  = Estate\BaseEstate::ESTATE_HOME_PATH;

if($this->StartResultCache($page, $order, $filter)) {

	$this->IncludeComponentTemplate('', Estate\BaseEstate::DEFAULT_TEMPLATE_PATH);

}

