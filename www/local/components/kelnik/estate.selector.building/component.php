<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

if (!CModule::IncludeModule("iblock")) {
	$this->AbortResultCache();
	ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
	return;
}

if (empty($arParams['BUILDING_ID'])) {
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

// Активен ли корпус
if (!isset($activeMap['BUILDINGS'][$arParams['BUILDING_ID']])) {
    @define("ERROR_404", "Y");
    CHTTP::SetStatus("404 Not Found");
    return;
}

if (!empty($_REQUEST['getResult'])) {
    parse_str($_REQUEST['getResult'], $params);
    $_REQUEST += $params;
}
$filter = $flatsInstance->getSearchRequest();

// Активные этажи корпуса
$floors = $flatsInstance->getCntByFloors($filter);
foreach ($floors as $ID => &$floor) {
    $floor['PARENT'] = $activeMap['FLOORS'][$ID]['PARENT'];
}
unset($floor);

// Секции корпуса
$sectionIds = Estate\BaseEstate::getParentsFromResult($floors);
$sections = Estate\EstateSectionTable::getAssoc(
    array('filter' => array('ID' => $sectionIds)),
    'ID'
);

foreach ($sections as &$section) {
    $url = str_replace(
        array('#BUILDING_ID#', '#SECTION_ID#'),
        array($arParams['BUILDING_ID'], $section['ID']),
        $arParams['PATH_TO_SECTION']
    );
    $section['URL'] = $url;
}
unset($section);

// Если секция всего одна, делаем редирект на нее
/*if (count($sections) === 1) {
    reset($sections);
    $section = current($sections);
    LocalRedirect($section['URL']);
    return;
}*/

if (isset($_GET['AJAX']) && $_GET['AJAX'] === 'Y') {
    $APPLICATION->RestartBuffer();
    header('Content-type:application/json; charset=UTF-8');

    // Данные корпуса
    $res = Estate\EstateBuildingTable::getById($arParams['BUILDING_ID']);
    $building = $res->fetch();

    $sectionInstance = Estate\EstateSectionTable::getInstance();
    $json = $sectionInstance->getJson($building, $sections, $floors);

    echo json_encode($json);
    return;
}

$arResult['HOME_PATH']  = Estate\BaseEstate::ESTATE_HOME_PATH;

if($this->StartResultCache($page, $order, $filter)) {

	$this->IncludeComponentTemplate('', Estate\BaseEstate::DEFAULT_TEMPLATE_PATH);

}

