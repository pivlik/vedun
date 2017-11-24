<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $DB;
/**
 * @global CMain $APPLICATION
 * @var CBitrixComponent $this
 */

if (!isset($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 36000000;

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
if (strlen($arParams["IBLOCK_TYPE"]) <= 0)
    $arParams["IBLOCK_TYPE"] = "estate_gallery";
$arParams["IBLOCK_ID"] = trim($arParams["IBLOCK_ID"]);

$arParams["PERIOD"] = trim($arParams["PERIOD"]);
if (strlen($arParams["PERIOD"]) <= 0) {
    $arParams["PERIOD"] = $DB->IsDate($arParams["PERIOD"], "DD.MM.YYYY") ? $arParams["PERIOD"] : false;
}

$arParams["gallery"] = (int)$arParams["gallery"];
$arParams["OBJECT_ID"] = (int)$arParams["OBJECT_ID"];
if (!$arParams["OBJECT_ID"]) {
    $this->AbortResultCache();
    ShowError("Не задан ID Объекта ЖК");
    return;
}

$arParams["BUILDING_IBLOCK_ID"] = (int)$arParams["BUILDING_IBLOCK_ID"];
if (!$arParams["BUILDING_IBLOCK_ID"]) {
    $this->AbortResultCache();
    ShowError("Не задан IBLOCK ID Корпусов ЖК");
    return;
}

$arParams["BUILDING_ID"] = (int)$arParams["BUILDING_ID"];

if ($this->StartResultCache(false, array($arParams))) {
    if (!CModule::IncludeModule("iblock")) {
        $this->AbortResultCache();
        ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
        return;
    }
    if (is_numeric($arParams["IBLOCK_ID"])) {
        $rsIBlock = CIBlock::GetList(array(), array(
            "ACTIVE" => "Y",
            "ID" => $arParams["IBLOCK_ID"],
        ));
    } else {
        $rsIBlock = CIBlock::GetList(array(), array(
            "ACTIVE" => "Y",
            "CODE" => $arParams["IBLOCK_ID"],
            "SITE_ID" => SITE_ID,
        ));
    }
    if ($arResultBlockInfo = $rsIBlock->GetNext()) {

        //Получаем список категорий, которые привязаны к объекту ЖК
        $arSelect = Array(
            'ID',
            'NAME',
            'ELEMENT_CNT',
            'UF_OBJECT',
        );
        $arFilter = Array(
            'IBLOCK_ID' => $arResultBlockInfo["ID"],
            'GLOBAL_ACTIVE' => 'Y',
            '=UF_OBJECT' => $arParams["OBJECT_ID"],
        );
        $arSections = array();
        $groupList = CIBlockSection::GetList(Array($by => $order), $arFilter, array('CNT_ACTIVE' => 'Y'), $arSelect);
        while ($arGroup = $groupList->GetNext()) {
            if (!(int)$arGroup['ELEMENT_CNT']) {
                continue;
            }
            $arSections = $arGroup['ID'];
        }

        //если не найдены связи с ЖК то заканчиваем
        if(count($arSections) === 0){
            $this->AbortResultCache();
            return;
        }

        //Получаем список корпусов объекта ЖК
        $arBuilding = array();
        $rsSubElement = CIBlockElement::GetList(
            array("SORT" => "ASC"),
            array(
                "IBLOCK_ID" => $arParams["BUILDING_IBLOCK_ID"],
                "ACTIVE" => "Y",
                "=PROPERTY_ESTATE_OBJECT" => $arParams["OBJECT_ID"]
            ),
            false,
            false,
            array("ID", "NAME", "PROPERTY_IS_READY", "PROPERTY_ESTATE_OBJECT", "PROPERTY_READY_DATE")
        );
        while ($obSubElement = $rsSubElement->GetNextElement()) {
            $arSubItem = array();
            $arSubItem = $obSubElement->GetFields();
            $selectedBuilding = 0;
            $arBuilding[$arSubItem['ID']] = $arSubItem;
        }

        //SELECT
        $arSelect = array(
            "ID",
            "IBLOCK_ID",
            "NAME",
            "ACTIVE_FROM",
            "DETAIL_TEXT",
            "DETAIL_TEXT_TYPE",
        );
        //WHERE
        $arFilter = array(
            "IBLOCK_ID" => $arResultBlockInfo["ID"],
            "IBLOCK_LID" => SITE_ID,
            "ACTIVE" => "Y",
            "SECTION_ID" => $arSections
        );
        //ORDER BY
        $arSort = array(
            "ACTIVE_FROM" => "DESC"
        );

        //Формируем список Периодов для выбора
        $rsElement = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
        $rsElementsObjects = array();
        $arPeriodKeys = array();
        while ($obElement = $rsElement->GetNextElement()) {
            $rsElementsObjects[] = $obElement;
            $arItem = $obElement->GetFields();
            //если не задан период, то устанавливаем последний добавленный
            if (!$arParams["PERIOD"]) {
                $arParams["PERIOD"] = $arItem['ACTIVE_FROM'];
            }

            //получаем числовые значения времени
            $stmpCurr = MakeTimeStamp($arItem['ACTIVE_FROM'], "DD.MM.YYYY");
            $stmpFrom = MakeTimeStamp($arParams["PERIOD"], "DD.MM.YYYY");
            $stmpTo = strtotime("+1 month -1 second", $stmpFrom);
            $arItem['SELECTED'] = false;

            if ($stmpCurr >= $stmpFrom && $stmpCurr <= $stmpTo) {
                $arItem['SELECTED'] = true;
            }

            $arPeriod = array();
            $arPeriod['text'] = FormatDate("f Y", $stmpCurr);
            $arPeriod['value'] = $arItem['ACTIVE_FROM'];
            $arPeriod['isSelect'] = $arItem['SELECTED'];

            if (!in_array(FormatDate("m.Y", $stmpCurr), $arPeriodKeys)) {
                $arResult['selects']['period'][] = $arPeriod;
                $arPeriodKeys[] = FormatDate("m.Y", $stmpCurr);
            }
        }

        $arGalleryies = array();
        //Формируем список галерей по текущему периоду
        foreach ($rsElementsObjects as $itemObject) {
            $arItem = $itemObject->GetFields();
            //получаем числовые значения времени
            $stmpCurr = MakeTimeStamp($arItem['ACTIVE_FROM'], "DD.MM.YYYY");
            $stmpFrom = MakeTimeStamp($arParams["PERIOD"], "DD.MM.YYYY");
            $stmpTo = strtotime("+1 month -1 second", $stmpFrom);

            if ($stmpCurr >= $stmpFrom && $stmpCurr <= $stmpTo) {
                $arItem["PROPERTIES"] = $itemObject->GetProperties();

                foreach ($arItem["PROPERTIES"] as $key => $prop) {
                    if ($prop['PROPERTY_TYPE'] == 'F') {
                        if (is_array($prop['VALUE'])) {
                            foreach ($prop['VALUE'] as $propKey => $ID) {
                                $arItem["PROPERTIES"][$key]['VALUE'][$propKey] = CFile::GetFileArray($ID);
                            }
                        } else {
                            $arItem["PROPERTIES"][$key]['VALUE'] = CFile::GetFileArray($prop['VALUE']);
                        }
                    }
                }

                $arGalleryies[$arItem['ID']]["FOTOS"] = $arItem['PROPERTIES']['FOTO']['VALUE'];
                $arGalleryies[$arItem['ID']]["PROGRESS"] = (int)$arItem['PROPERTIES']['PROGRESS']['VALUE'];
                $arGalleryies[$arItem['ID']]["DETAIL_TEXT"] = $arItem['DETAIL_TEXT'];
                $arGalleryies[$arItem['ID']]["IS_READY"] = false;
                $arGalleryies[$arItem['ID']]["READY_DATE"] = false;
                $arGalleryies[$arItem['ID']]["NAME"] = "Общая галерея";

                if ($arItem['PROPERTIES']['BUILDING']['VALUE']) {
                    $arGalleryies[$arItem['ID']]["READY_DATE"] = $arBuilding[$arItem['PROPERTIES']['BUILDING']['VALUE']]['PROPERTY_READY_DATE_VALUE'];
                    $arGalleryies[$arItem['ID']]["IS_READY"] = $arBuilding[$arItem['PROPERTIES']['BUILDING']['VALUE']]['PROPERTY_IS_READY_VALUE'];
                    $arGalleryies[$arItem['ID']]["NAME"] = $arBuilding[$arItem['PROPERTIES']['BUILDING']['VALUE']]['NAME'];
                }
                //проверка статуса готовности по значению прогресса
                if ($arGalleryies[$arItem['ID']]["PROGRESS"] >= 100) {
                    $arGalleryies[$arItem['ID']]["IS_READY"] = true;
                }
            }
        }

        if (count($arGalleryies) > 1) {
            //Формируем список корпусов
            foreach ($arGalleryies as $key => $gal) {
                $arItem = array();
                $arItem['text'] = $gal['NAME'];
                $arItem['value'] = $key;
                $arItem['isSelect'] = false;
                if ($key == $arParams["gallery"]) {
                    $arItem['isSelect'] = true;
                    $arResult["GALLERY"] = $gal;
                }
                $arResult['selects']['gallery'][] = $arItem;
            }
        }

        //если галерея одна, то берем ее
        if (!$arResult["GALLERY"]) {
            $arResult["GALLERY"] = array_shift($arGalleryies);
        }

        if (!$arResult["GALLERY"]) {
            $arResult = false;
        }

        $this->SetResultCacheKeys(array(
            "ID",
            "IBLOCK_TYPE_ID",
            "LIST_PAGE_URL",
            "NAME",
        ));

        $this->IncludeComponentTemplate();
    } else {
        $this->AbortResultCache();
        \Bitrix\Iblock\Component\Tools::process404(
            trim($arParams["MESSAGE_404"]) ?: GetMessage("T_NEWS_NEWS_NA")
            , true
            , $arParams["SET_STATUS_404"] === "Y"
            , $arParams["SHOW_404"] === "Y"
            , $arParams["FILE_404"]
        );
    }
}



