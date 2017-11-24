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

$arParams["OBJECT_ID"] = (int)$arParams["OBJECT_ID"];

if (!$arParams["OBJECT_ID"]) {
    $this->AbortResultCache();
    ShowError("Не задан ID Объекта ЖК");
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
    if ($arResult = $rsIBlock->GetNext()) {
        $arSections = array();
        //Получаем спискок категорий, которые привязаны к Объекту
        $arFilter = Array('IBLOCK_ID' => $arResult["ID"], 'GLOBAL_ACTIVE' => 'Y', 'UF_OBJECT' => $arParams["OBJECT_ID"]);
        $db_list = CIBlockSection::GetList(Array($by => $order), $arFilter);
        while ($arSect = $db_list->GetNext()) {
            $arSections[] = $arSect['ID'];
        }

        if (count($arSections)) {
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
                "PROPERTY_DOC",
            );
            //WHERE
            $arFilter = array(
                "IBLOCK_ID" => $arResult["ID"],
                "IBLOCK_LID" => SITE_ID,
                "ACTIVE" => "Y",
                "SECTION_ID" => $arSections
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

                $itemDoc = array();
                $itemDoc['ID'] = $arItem['ID'];
                $itemDoc['IBLOCK_ID'] = $arItem['IBLOCK_ID'];
                $itemDoc["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
                $itemDoc["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];
                $itemDoc['NAME'] = $arItem['NAME'];
                $itemDoc['DOC'] = CFile::GetFileArray($arItem['PROPERTY_DOC_VALUE']);
                $extName = mb_strtolower(pathinfo($itemDoc['DOC']['FILE_NAME'], PATHINFO_EXTENSION));
                $itemDoc['DOC_EXT'] = $extName;
                $itemDoc['DOC_SIZE_F'] = CFile::FormatSize($itemDoc['DOC']['FILE_SIZE'], 1);;

                $arResult["ITEMS"][] = $itemDoc;
            }
        }
        if (!count($arResult["ITEMS"])) {
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


