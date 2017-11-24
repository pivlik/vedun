<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$requiredModules = array('estate');
foreach ($requiredModules as $requiredModule) {
    if (!CModule::IncludeModule($requiredModule)) {
        ShowError(GetMessage('F_NO_MODULE'));
        return;
    }
}

use Bitrix\Estate as Estate;

//Список Городов
$Cities = Estate\EstateRefCitiesTable::getAssoc(array(), 'ID', 'NAME');
//Список Районов
$Districts = Estate\EstateRefDistrictsTable::getAssoc(array(), 'ID', 'NAME');
//Список метро
$Subways = Estate\EstateRefSubwayTable::getAssoc(array(), 'ID', 'NAME');

//Формируем результаты по каждому ЖК
$activeMapObjects = Estate\BaseEstate::getIblockObjectsFullInfoWithBuilding();

$iObject = $activeMapObjects[$arResult['ID']];

if ($iObject['DETAIL_PICTURE']) {
    $iObject['DETAIL_PICTURE'] = Estate\Common::_getImageOrPlaceholder($iObject['DETAIL_PICTURE'], 680, 130);
}

$totalReady = 0;
$arReadyDates = array();
foreach ($iObject['BUILDINGS'] as $building) {
    $arResult['BUILDING'][$building['ID']] = $building;
    if ($building['PROPERTY_IS_READY_VALUE']) {
        $totalReady++;
    } else {
        $arReadyDates[] = $building['PROPERTY_READY_DATE_VALUE'];
    }
}

$iObject['TOTAL_READY'] = false;
if ($totalReady === count($iObject['BUILDINGS'])) {
    $iObject['TOTAL_READY'] = true;
}
$iObject['READY_DATES'] = $arReadyDates;

// Собираем район и метро
$arRaionMetro = array();
if ($iObject['PROPERTY_RAION_VALUE']) {
    $arRaionMetro[] = $Districts[$iObject['PROPERTY_RAION_VALUE']];
}
if ($iObject['PROPERTY_METRO_VALUE']) {
    $arRaionMetro[] = $Subways[$iObject['PROPERTY_METRO_VALUE']];
}


$iObject['Raion_Metro'] = $arRaionMetro;
$iObject['CITY_NAME'] = $Cities[$iObject['PROPERTY_CITY_VALUE']];


$arResult['ESTATE_INFO'] = $iObject;