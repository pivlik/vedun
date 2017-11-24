<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

if (!isset($arParams['FLAT_ID']) || !(int) $arParams['FLAT_ID']) {
    @define("ERROR_404", "Y");
    CHTTP::SetStatus("404 Not Found");
    return;
}

if(!isset($arParams['CACHE_TIME'])) {
	$arParams['CACHE_TIME'] = 3600;
}

use Bitrix\Estate as Estate;

if($this->StartResultCache($arParams['CACHE_TIME'], $arParams['FLAT_ID'])) {
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

    // Карта активных элементов недвижимости
    $activeMap = Estate\BaseEstate::getActiveElementsMap();

    // Данные квартиры
    $flatsInstance = Estate\EstateFlatTable::getInstance();
    $arResult['FLAT'] = $flatsInstance->getFullFlatInfo($arParams['FLAT_ID']);

    // Активен ли родительский этаж
    if (!isset($activeMap['FLOORS'][$arResult['FLAT']['PARENT']])) {
        @define("ERROR_404", "Y");
        CHTTP::SetStatus("404 Not Found");
        return;
    }

    // Ссылка для возврата
    $catalogPath = 'http://' . $_SERVER['HTTP_HOST'] . Estate\BaseEstate::ESTATE_HOME_PATH;
    $searchPage = $catalogPath;
    $visualPage = $catalogPath . 'visual/';
    $visualPage = $flatsInstance->getVisualFloorLink($visualPage, $arResult['FLAT']);

    $arResult['BACK_URL'] = $searchPage;
    $arResult['BACK_TEXT'] = 'Перейти в поиск по параметрам';
    if (strpos($_SERVER['HTTP_REFERER'], $visualPage) === 0) {
        $arResult['BACK_URL'] = $_SERVER['HTTP_REFERER'];
        $arResult['BACK_TEXT'] = 'Выбрать другую квартиру на этаже';
    } else if (strpos($_SERVER['HTTP_REFERER'], $searchPage) === 0) {
        $arResult['BACK_URL'] = $_SERVER['HTTP_REFERER'];
        $arResult['BACK_TEXT'] = 'Вернуться к результатам поиска';
    }

    // Получаем список этажей очереди
    $stage = $arResult['FLAT']['BUILDING']['STAGE'];
    $stageBuildingIds = array();
    foreach ($activeMap['BUILDINGS'] as $ID => $building) {
        if ($building['STAGE'] === $stage) {
            $stageBuildingIds[] = $ID;
        }
    }
    $stageSections = Estate\BaseEstate::filterByParentId(
        $activeMap['SECTIONS'],
        $stageBuildingIds
    );
    $stageFloors = Estate\BaseEstate::filterByParentId(
        $activeMap['FLOORS'],
        array_keys($stageSections)
    );

//    $limit = Estate\EstateFlatTable::COUNT_ON_PAGE;
    $limit = 3;
    // Более просторные квартиры
    $params = array(
        'order' => array(
            'TYPE' => 'asc',
            'PRICE_TOTAL' => 'asc'
        ),
        'filter' => array(
            'PARENT'      => array_keys($stageFloors),
            'TYPE'        => $arResult['FLAT']['TYPE'],
            'STATUS'      => Estate\EstateFlatTable::IN_SALE_STATUS,
            '>PRICE_TOTAL' => $arResult['FLAT']['PRICE_TOTAL'] + 0.001,
        ),
        'limit' => $limit,
    );
    
    $arResult['ITEMS'] = $flatsInstance->getSameFlats($params, $arResult['FLAT']);

    $countLeft = $arResult['ITEMS']['CNT'];
    if($countLeft > $limit){
        $countLeft = $limit;
    }
    $arResult['NEXT_BTN_CNT'] = $countLeft;
    $arResult['NEXT_BTN_CNT_WORD'] = plural($arResult['NEXT_BTN_CNT'], array('предложение', 'предложения', 'предложений'));

    $arResult['NEXT_URL'] = $countLeft > 0 ? '/ajax/recommend.php'
                                      . '?FLAT_ID='.$arParams['FLAT_ID'] . '&page=2' : '';

	$this->IncludeComponentTemplate();

}

