<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (!isset($arParams['FLAT_ID']) || !(int)$arParams['FLAT_ID']) {
    @define("ERROR_404", "Y");
    CHTTP::SetStatus("404 Not Found");
    return;
}

if (!isset($arParams['CACHE_TIME'])) {
    $arParams['CACHE_TIME'] = 3600;
}

use Bitrix\Estate as Estate;

if ($this->StartResultCache($arParams['CACHE_TIME'], $arParams['FLAT_ID'])) {
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
    //pre($arResult['FLAT']);

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

    if ($arResult['FLAT']['PLANOPLAN']) {

        $arResult['PLANOPLAN'] = \Bitrix\Estate\Common::getPlanoplanInfo($arResult['FLAT']['PLANOPLAN']);

        if ($arResult['PLANOPLAN']['tabs']['3d']['content']) {
            $arResult['PLANOPLAN']['images']['3d'] = \Bitrix\Estate\Common::_resizeImageOrPlaceholder($arResult['PLANOPLAN']['tabs']['3d']['content'], 670, 900);
        }
        if ($arResult['PLANOPLAN']['tabs']['2d']['content']) {
            $arResult['PLANOPLAN']['images']['2d'] = \Bitrix\Estate\Common::_resizeImageOrPlaceholder($arResult['PLANOPLAN']['tabs']['2d']['content'], 250, 210);
        }

    }

    if (!$arResult['FLAT']['BUILDING']['IMAGE_IN_OBJECT']) {
        $arResult['FLAT']['BUILDING']['IMAGE_IN_OBJECT'] = \Bitrix\Estate\Common::_getImageOrPlaceholder($arResult['FLAT']['BUILDING']['IMAGE_IN_OBJECT'], 500, 300);
    }

    // Получаем список этажей очереди
    $stage = $arResult['FLAT']['BUILDING']['STAGE'];
    $stageBuildingIds = array();
    foreach ($activeMap['BUILDINGS'] as $ID => $building) {
        if ($building['STAGE'] === $stage) {
            $stageBuildingIds[] = $ID;
        }
    }

    $stageFloors = Estate\BaseEstate::filterByParentId(
        $activeMap['FLOORS'],
        $stageBuildingIds
    );


    $arResult['HOME_PATH'] = Estate\BaseEstate::ESTATE_HOME_PATH;

    // Более просторные квартиры
    $arResult['MORE_AREA_FLATS'] = $flatsInstance->getSameFlats(
        array(
            'select' => array(
                'ID',
                'TYPE_NAME',
                'NAME',
                'PRICE_TOTAL',
                'AREA_TOTAL',
                'IMAGE',
                'SECTION',
                'PARENT',
                'ROOMS',

            ),
            'filter' => array(
                'PARENT' => array_keys($stageFloors),
                'TYPE' => $arResult['FLAT']['TYPE'],
                'STATUS' => Estate\EstateFlatTable::IN_SALE_STATUS,
//                '>AREA_TOTAL' => $arResult['FLAT']['AREA_TOTAL'] + 0.001,
            ),
            'order' => array(
                'RAND' => 'ASC'
            ),
            'limit' => 3,
        ),
        $arResult['FLAT']
    );
    $arResult['MORE_AREA_FLATS_LINK'] = $arResult['HOME_PATH']
        . '?apart[' . $arResult['FLAT']['TYPE'] . ']=1'
        . '&area[min]=' . ($arResult['FLAT']['AREA_TOTAL'] + 0.001);
    /*
        // Более дешевые квартиры
        $arResult['LESS_PRICE_FLATS'] = $flatsInstance->getSameFlats(
            array(
                'filter' => array(
                    'PARENT' => array_keys($stageFloors),
                    'TYPE'   => $arResult['FLAT']['TYPE'],
                    'STATUS' => Estate\EstateFlatTable::IN_SALE_STATUS,
                    '<PRICE_TOTAL' => $arResult['FLAT']['PRICE_TOTAL'],
                ),
                'order' => array(
                    'RAND' => 'ASC'
                ),
                'limit' => 3,
            ),
            $arResult['FLAT']
        );
        $arResult['LESS_PRICE_FLATS_LINK'] = $arResult['HOME_PATH']
                                           . '?apart[' . $arResult['FLAT']['TYPE'] . ']=1'
                                           . '&price[max]=' . ($arResult['FLAT']['PRICE_TOTAL'] - 1);

        // Квартиры по акции
        $arResult['ACTION_FLATS'] = $flatsInstance->getSameFlats(
            array(
                'filter' => array(
                    'TYPE'       => $arResult['FLAT']['TYPE'],
                    'STATUS'     => Estate\EstateFlatTable::IN_SALE_STATUS,
                    '!IS_ACTION' => 0,
                    '!ID'        => $arResult['FLAT']['ID'],
                ),
                'order' => array(
                    'RAND' => 'ASC'
                ),
                'limit' => 3,
            ),
            $arResult['FLAT']
        );

        // Главная акция
        $arSelect = array(
            'ID',
            'NAME',
            'PREVIEW_TEXT',
        );
        $arFilter = array(
            'IBLOCK_ID'              => 12,
            'ACTIVE'                 => 'Y',
            'PROPERTY_IS_MAIN_VALUE' => 'Y',
        );
        $arOrder = array(
            'SORT' => 'ASC'
        );
        $arLimit = array(
            'nTopCount' => 1,
        );
        $res = CIBlockElement::GetList($arOrder, $arFilter, false, $arLimit, $arSelect);
        if ($ob = $res->GetNextElement()) {
            $arResult['ACTION'] = $ob->GetFields();
        }*/

    // Ссылка на избранное в меню
    $favorite = Estate\EstateFavoriteTable::getInstance();
    $favorite->setMenuFavoriteLink();

    //var_dump($arResult);

    $this->IncludeComponentTemplate();

}

//Другие квартиры на этаже
