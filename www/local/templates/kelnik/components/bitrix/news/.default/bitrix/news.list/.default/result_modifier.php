<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$page = isset($_GET['PAGEN_1']) ? (int) $_GET['PAGEN_1'] : 1;
$perPage = $arResult['NAV_RESULT']->NavPageSize / $page;
$pages = ceil($arResult['NAV_RESULT']->NavRecordCount / $perPage);
if ($page > $pages) {
    @define("ERROR_404", "Y");
    CHTTP::SetStatus("404 Not Found");
    return;
}

$cp = $this->__component; // объект компонента

if (is_object($cp)) {
    $arResult['NAV_RESULT']->NavPageNomer = isset($_GET['PAGEN_1']) ? $_GET['PAGEN_1'] : 1;
    $cp->arResult['NAV_RESULT'] = $arResult['NAV_RESULT'];
    $cp->SetResultCacheKeys(array('NAV_RESULT'));
}

if (empty($arResult['ITEMS'])) {
    return false;
}

$curYear = date('Y');
foreach ($arResult['ITEMS'] as $key => &$arItem) {
    $activeFrom = strtotime($arItem['ACTIVE_FROM']);
    $arItem['DATE'] = date('Y-m-d', $activeFrom);
    $year = date('Y', $activeFrom);
    if ($year !== $curYear) {
        $arItem['DISPLAY_ACTIVE_FROM'] .= ' ' . $year;
    }
}
unset($arItem);
