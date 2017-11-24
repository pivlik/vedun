<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain $APPLICATION
 * @var CBitrixComponent $this
 */

if (!isset($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 36000000;

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
if (strlen($arParams["IBLOCK_TYPE"]) <= 0)
    $arParams["IBLOCK_TYPE"] = "masters";
$arParams["IBLOCK_ID"] = trim($arParams["IBLOCK_ID"]);

if (!is_array($arParams["PROPERTY_CODE"]))
    $arParams["PROPERTY_CODE"] = array();
foreach ($arParams["PROPERTY_CODE"] as $key => $val)
    if ($val === "")
        unset($arParams["PROPERTY_CODE"][$key]);

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
    if ($arResult = $rsIBlock->GetNext()) {
        //SELECT
        $arSelect = array(
            "ID",
            "IBLOCK_ID",
            "IBLOCK_SECTION_ID",
            "NAME",
            "ACTIVE_FROM",
            "TIMESTAMP_X",
            "DETAIL_PAGE_URL",
            "LIST_PAGE_URL",
            "DETAIL_TEXT",
            "DETAIL_TEXT_TYPE",
            "PREVIEW_TEXT",
            "PREVIEW_TEXT_TYPE",
            "PREVIEW_PICTURE",
            "DETAIL_PICTURE",
        );
        $bGetProperty = count($arParams["PROPERTY_CODE"]) > 0;
        if ($bGetProperty)
            $arSelect[] = "PROPERTY_*";
        //WHERE
        $arFilter = array(
            "IBLOCK_ID" => $arResult["ID"],
            "IBLOCK_LID" => SITE_ID,
            "ACTIVE" => "Y",
        );
        //ORDER BY
        $arSort = array(
            "SORT" => "ASC"
        );

        $arResult["ELEMENTS"] = array();
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

            if ($bGetProperty)
                $arItem["PROPERTIES"] = $obElement->GetProperties();

            //Получаем значение привязанных блоков
            $arSubResult = array();
            foreach ($arItem["PROPERTIES"] as $key => $prop) {
                if ($prop['PROPERTY_TYPE'] == 'F') {
                    if(is_array($prop['VALUE'])) {
                        foreach ($prop['VALUE'] as $propKey => $ID) {
                            $arItem["PROPERTIES"][$key]['VALUE'][$propKey] = CFile::GetFileArray($ID);
                        }
                    } else {
                        $arItem["PROPERTIES"][$key]['VALUE'] = CFile::GetFileArray($prop['VALUE']);
                    }
                }
//                $rsSubElement = CIBlockElement::GetList(array("SORT" => "ASC"), array("ID" => $prop['VALUE'], "ACTIVE" => "Y"), false, false, array("ID", "NAME", "IBLOCK_ID", "PROPERTY_*"));
//                while ($obSubElement = $rsSubElement->GetNextElement()) {
//                    $arSubItem = array();
//                    $arSubItem = $obSubElement->GetFields();
//                    $arSubItem["PROPERTIES"] = $obSubElement->GetProperties();
//
//                    $arSubResult[$key][] = $arSubItem;
//                }
            }

//            $arItem["PROPERTIES_VALUES"] = $arSubResult;
            $arResult["ITEMS"][] = $arItem;
        }

        if(!count($arResult["ITEMS"])){
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

if (isset($arResult["ID"])) {
    return $arResult["ITEMS"];
}


