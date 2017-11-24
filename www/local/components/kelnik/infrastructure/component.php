<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (!isset($arParams['CACHE_TIME'])) {
    $arParams['CACHE_TIME'] = 3600;
}


$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
if (strlen($arParams["IBLOCK_TYPE"]) <= 0)
    $arParams["IBLOCK_TYPE"] = "infrastrucutre";
$arParams["IBLOCK_ID"] = trim($arParams["IBLOCK_ID"]);

$arParams["OBJECT_ID"] = (int)trim($arParams["OBJECT_ID"]);
if (!$arParams["OBJECT_ID"]) {
    ShowError("Не корректный ID Объекта");
    return;
}


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

    if ($res = $rsIBlock->GetNext()) {

        $arResult["ESTATE_INFO"] = array();
        $arEstates = array();
        //SELECT
        $arSelect = array(
            "ID",
            "IBLOCK_ID",
            "NAME",
            "DETAIL_PAGE_URL",
            "LIST_PAGE_URL",
            "DETAIL_TEXT",
            "DETAIL_TEXT_TYPE",
            "PREVIEW_TEXT",
            "PREVIEW_TEXT_TYPE",
            "PREVIEW_PICTURE",
            "DETAIL_PICTURE",
            "PROPERTY_MAP",
            "PROPERTY_SHORT_DESCRIPTION",
            "PROPERTY_MARKER_FOTO",
            "PROPERTY_MARKER",
            "PROPERTY_MARKER_3D",
            "PROPERTY_MAP_CENTER_COORD",
            "PROPERTY_MAP_ZOOM",
            "PROPERTY_MARKER_3D_COORD",
        );
        //WHERE
        $arFilter = array(
            "ID" => $arParams["OBJECT_ID"],
            "IBLOCK_ID" => 6,
            "IBLOCK_LID" => SITE_ID,
            "ACTIVE" => "Y",
        );
        //ORDER BY
        $arSort = array(
            "SORT" => "ASC"
        );
        $estateElement = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
        while ($obElement = $estateElement->GetNextElement()) {
            $arFields = $obElement->GetFields();

            $arItem = array();
            $arItem['ID'] = $arFields['ID'];
            $arItem['NAME'] = $arFields['NAME'];
            $arItem['MAP'] = $arFields['PROPERTY_MAP_VALUE'];
            $arItem['SHORT_DESCRIPTION'] = htmlspecialcharsback($arFields['PROPERTY_SHORT_DESCRIPTION_VALUE']['TEXT']);
            $arItem['MAP_CENTER_COORD'] = $arFields['PROPERTY_MAP_CENTER_COORD_VALUE'] ? $arFields['PROPERTY_MAP_CENTER_COORD_VALUE'] : $arItem['MAP'];
            $arItem['MAP_ZOOM'] = $arFields['PROPERTY_MAP_ZOOM_VALUE'] > 0 ? (int)$arFields['PROPERTY_MAP_ZOOM_VALUE'] : 0;
            $arItem['MARKER_3D_COORD'] = $arFields['PROPERTY_MARKER_3D_COORD_VALUE'];
            if ($arFields['PROPERTY_MARKER_FOTO_VALUE']) {
                $arItem['MARKER_FOTO'] = CFile::GetFileArray($arFields['PROPERTY_MARKER_FOTO_VALUE']);
            }
            if ($arFields['PROPERTY_MARKER_VALUE']) {
                $arItem['MARKER'] = CFile::GetFileArray($arFields['PROPERTY_MARKER_VALUE']);
            }
            if ($arFields['PROPERTY_MARKER_3D_VALUE']) {
                $arItem['MARKER_3D'] = CFile::GetFileArray($arFields['PROPERTY_MARKER_3D_VALUE']);
            }
            if ($object['MARKER_FOTO']['SRC']) {
                $object['SHORT_DESCRIPTION'] = '<img src="' . $object['MARKER_FOTO']['SRC'] . '">' . $object['SHORT_DESCRIPTION'];
            }

            $arResult["ESTATE_INFO"] = $arItem;
        }


        $arResult["GROUP_MARKERS"] = array();
        $arMarkerGroups = array();
        $arSelect = Array(
            'ID',
            'NAME',
            'PICTURE',
            'ELEMENT_CNT',
            'UF_ESTATE',
        );
        $arFilter = Array(
            'IBLOCK_ID' => $res["ID"],
            'GLOBAL_ACTIVE' => 'Y'
        );
        $groupList = CIBlockSection::GetList(Array($by => $order), $arFilter, array('CNT_ACTIVE' => 'Y'), $arSelect);
        while ($arGroup = $groupList->GetNext()) {
            if (!(int)$arGroup['ELEMENT_CNT']) {
                continue;
            }
            if ($arGroup['PICTURE']) {
                $arGroup['PICTURE'] = CFile::GetFileArray($arGroup['PICTURE']);
            }
            $arMarkerGroups[$arGroup['ID']] = $arGroup;
        }

        //SELECT
        $arSelect = array(
            "ID",
            "IBLOCK_ID",
            "IBLOCK_SECTION_ID",
            "NAME",
            "DETAIL_PAGE_URL",
            "LIST_PAGE_URL",
            "DETAIL_TEXT",
            "DETAIL_TEXT_TYPE",
            "PREVIEW_TEXT",
            "PREVIEW_TEXT_TYPE",
            "PREVIEW_PICTURE",
            "DETAIL_PICTURE",
        );
        $arSelect[] = "PROPERTY_MAP";
        //WHERE
        $arFilter = array(
            "IBLOCK_ID" => $res["ID"],
            "IBLOCK_LID" => SITE_ID,
            "ACTIVE" => "Y",
            "PROPERTY_ESTATE" => 11,
        );
        //ORDER BY
        $arSort = array(
            "SORT" => "ASC"
        );


        $rsElement = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
        while ($obElement = $rsElement->GetNextElement()) {
            $arItem = $obElement->GetFields();

            $arButtons = CIBlock::GetPanelButtons(
                $arItem["IBLOCK_ID"],
                $arItem["ID"],
                0,
                array("SECTION_BUTTONS" => false, "SESSID" => false)
            );
            $arItem["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
            $arItem["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];

            if (isset($arItem["PREVIEW_PICTURE"])) {
                $arItem["PREVIEW_PICTURE"] = (0 < $arItem["PREVIEW_PICTURE"] ? CFile::GetFileArray($arItem["PREVIEW_PICTURE"]) : false);
                if ($arItem["PREVIEW_PICTURE"]) {
                    if ($arItem["PREVIEW_PICTURE"]["ALT"] == "")
                        $arItem["PREVIEW_PICTURE"]["ALT"] = $arItem["NAME"];
                    if ($arItem["PREVIEW_PICTURE"]["TITLE"] == "")
                        $arItem["PREVIEW_PICTURE"]["TITLE"] = $arItem["NAME"];
                }
            }
            if (isset($arItem["DETAIL_PICTURE"])) {
                $arItem["DETAIL_PICTURE"] = (0 < $arItem["DETAIL_PICTURE"] ? CFile::GetFileArray($arItem["DETAIL_PICTURE"]) : false);
                if ($arItem["DETAIL_PICTURE"]) {
                    if ($arItem["DETAIL_PICTURE"]["ALT"] == "")
                        $arItem["DETAIL_PICTURE"]["ALT"] = $arItem["NAME"];
                    if ($arItem["DETAIL_PICTURE"]["TITLE"] == "")
                        $arItem["DETAIL_PICTURE"]["TITLE"] = $arItem["NAME"];
                }
            }

            $arItem["PROPERTIES"] = $obElement->GetProperties();
            //добавляем группу с этим маркером
            $arResult["GROUP_MARKERS"][$arItem['IBLOCK_SECTION_ID']] = $arMarkerGroups[$arItem['IBLOCK_SECTION_ID']];

            //Подгатавливаем инфу по объекту
            $object = array();
            $object['NAME'] = $arItem["NAME"];
            $object['DETAIL_PICTURE'] = $arItem["DETAIL_PICTURE"];
            $object['DETAIL_TEXT'] = $arItem["DETAIL_TEXT"];
            $object['SECTION_ID'] = $arItem["IBLOCK_SECTION_ID"];
            $object['MARKER'] = $arResult['GROUP_MARKERS'][$arItem['IBLOCK_SECTION_ID']]['PICTURE'];
            if ($arItem['PREVIEW_PICTURE']) {
                $object['MARKER'] = $arItem['PREVIEW_PICTURE'];
            }
            $object['MAP'] = $arItem["PROPERTIES"]['MAP']['VALUE'];

            if ($object['DETAIL_PICTURE']['SRC']) {
                $object['DETAIL_TEXT'] = '<img src="' . $object['DETAIL_PICTURE']['SRC'] . '">' . $object['DETAIL_TEXT'];
            }

            $arResult['ITEMS'][] = $object;
        }

        if (!count($arResult['GROUP_MARKERS'])) {
            $arResult = false;
        }

        $this->SetResultCacheKeys(array(
            "ID",
            "IBLOCK_TYPE_ID",
            "LIST_PAGE_URL",
            "NAME",
            'GROUP_MARKERS'
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


