<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

//if (!empty($arParams['TYPE'])) {
//    $_REQUEST['apart'][$arParams['TYPE']] = 1;
//}

if (!isset($arParams['CACHE_TIME'])) {
    $arParams['CACHE_TIME'] = 3600;
}

use Bitrix\Estate as Estate;

if ($this->StartResultCache($arParams['CACHE_TIME'], serialize($_REQUEST))) {
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
    // Данные для формы поиска
    $searchInstance = Estate\EstateSearch::getInstance();
    $arResult = $searchInstance->getSearchForm();

    // Ссылка на избранное в меню
    $favorite = Estate\EstateFavoriteTable::getInstance();
    $favorite->setMenuFavoriteLink();

    $arResult['ACTIVE_SORT'] = !empty($_REQUEST['sort']) ? htmlspecialcharsex($_REQUEST['sort']) : 'price';
    $arResult['ACTIVE_ORDER'] = !empty($_REQUEST['order']) ? $_REQUEST['order'] : 'asc';
    $arResult['ORDER_CLASS'] = 'b-btn_style_sort-'
        . ($arResult['ACTIVE_ORDER'] === 'asc' ? 'asc' : 'desc');


    // Карта активных элементов недвижимости
    $activeMap = Estate\BaseEstate::getActiveElementsMap();
    //Формируем результаты по каждому ЖК
    $activeMapObjects = Estate\BaseEstate::getIblockObjectsFullInfoWithBuilding();

    $floorIds = $activeMap['FLOORS'];
    if ($_REQUEST['objects']) {
        $reqObjects = $_REQUEST['objects'];
        if (is_array($reqObjects)) {
            $filterObjects = array();
            $reqObjects = array_keys($reqObjects);
            foreach ($reqObjects as $iObjId) {
                $filterObjects[] = $activeMapObjects[$iObjId]['OBJECT_ID'];
            }
            unset($iObjId);
            $buildingIds = Estate\BaseEstate::filterByParentId($activeMap['BUILDINGS'], $filterObjects);
//            $sectionIds = Estate\BaseEstate::filterByParentId($activeMap['SECTIONS'], array_keys($buildingIds));
            $floorIds = Estate\BaseEstate::filterByParentId($activeMap['FLOORS'], array_keys($buildingIds));
        }
    }

    // Поисковые фильтры
    $filter = $searchInstance->getSearchRequest();
    if (count($floorIds)) {
        $filter['PARENT'] = array_keys($floorIds);
    }

    $arResult['CNT'] = $searchInstance->getResultCount($filter);
    $arResult['CNT_WORD'] = plural($arResult['RESULT'][$object]['CNT'], array('квартиру', 'квартиры', 'квартир'));

    $arResult['BORDERS'] = $searchInstance->getFilterBorders($filter);


    $favorite = Estate\EstateFavoriteTable::getInstance();
    $favoriteIds = $favorite->getFavoriteFlats();
    $arResult['TOTAL_FAVORITES'] = count($favoriteIds);

    foreach ($activeMapObjects as &$iObject) {
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
    }


    foreach ($activeMapObjects as $object) {
        $arResult['RESULT'][$object['ID']]['INFO'] = $object;
        //Устанавливаем фильтр по данному объекту
        Estate\BaseEstate::setObject($object['ID']);
        // Карта активных элементов недвижимости
        $activeMap = Estate\BaseEstate::getActiveElementsMap();

        // Параметры отображения списка квартир
        $countOnPage = Estate\EstateFlatTable::COUNT_ON_PAGE;
        $page = !empty($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;
        $limit = $countOnPage * $page;

        // Поисковые фильтры
        $filter = $searchInstance->getSearchRequest();
        $filter['PARENT'] = array_keys($activeMap['FLOORS']);

        $order = $searchInstance->getSearchOrder();

        $arResult['RESULT'][$object['ID']]['CNT'] = $searchInstance->getResultCount($filter);
        $arResult['RESULT'][$object['ID']]['CNT_WORD'] = plural($arResult['RESULT'][$object['ID']]['CNT'], array('квартиру', 'квартиры', 'квартир'));
        $arResult['RESULT'][$object['ID']]['SHOW_CNT'] = $limit;
        if ($limit > $arResult['RESULT'][$object['ID']]['CNT']) {
            $arResult['RESULT'][$object['ID']]['SHOW_CNT'] = $arResult['RESULT'][$object['ID']]['CNT'];
        }
        $arResult['RESULT'][$object['ID']]['SHOW_CNT_WORD'] = plural($arResult['RESULT'][$object['ID']]['CNT'], array('квартиры', 'квартир', 'квартир'));


        $params = array(
            'order' => $order,
            'limit' => $limit,
            'offset' => 0,
            'filter' => $filter,
        );

        $flatsInstance = Estate\EstateFlatTable::getInstance();
        $arItems = $flatsInstance->getFlatsList($params);

        foreach ($arItems as &$flat) {
            if ($flat['PLANOPLAN']) {
                $flat['PLANOPLAN_CONTENT'] = \Bitrix\Estate\Common::getPlanoplanInfo($flat['PLANOPLAN']);
                if ($flat['PLANOPLAN_CONTENT']['tabs']['2d']['content']) {
                    $flat['PLANOPLAN_CONTENT']['images']['2d'] = \Bitrix\Estate\Common::_resizeImageOrPlaceholder($flat['PLANOPLAN_CONTENT']['tabs']['2d']['content'], 670, 900);
                }
            }
            $flat['inFavorite'] = in_array($flat['ID'], $favoriteIds);
        }
        $arResult['RESULT'][$object['ID']]['ITEMS'] = $arItems;


        $request = $_GET;
        $arResult['RESULT'][$object['ID']]['page'] = $page;
        $arResult['RESULT'][$object['ID']]['next_page'] = $page + 1;
        $arResult['RESULT'][$object['ID']]['NEXT_URL'] = $arResult['RESULT'][$object['ID']]['CNT'] > $limit
            ? '/ajax/search.php?' . http_build_query($request)
            : '';
    }

    $this->IncludeComponentTemplate();

}
