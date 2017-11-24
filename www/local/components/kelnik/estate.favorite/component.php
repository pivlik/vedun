<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

if(!isset($arParams['CACHE_TIME'])) {
	$arParams['CACHE_TIME'] = 3600;
}

use Bitrix\Estate as Estate;

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

$favorite = Estate\EstateFavoriteTable::getInstance();
$favoriteIds = $favorite->getFavoriteFlats();

if ($favoriteIds) {
    $flatsInstance = Estate\EstateFlatTable::getInstance();

    $params = array(
        'filter' => array(
            'ID' => $favoriteIds,
        ),
    );
    $arResult['ITEMS'] = $flatsInstance->getFlatsList($params);

    foreach ($arResult['ITEMS'] as &$flat) {
        if($flat['PLANOPLAN']){
            $str = file_get_contents(
                "http://widget.planoplan.com/data/?hash=" . $flat['PLANOPLAN'] . "&lang=&width=&height=&callback=CallbackRegistry.f_933182223383395"
            );
            $str = str_replace('CallbackRegistry.f_933182223383395(', '', $str);
            $str = substr($str, 0, -2);
            $flat['PLANOPLAN_CONTENT'] = json_decode($str, true);

            if ($flat['PLANOPLAN_CONTENT']['tabs']['2d']['content']) {
                $flat['PLANOPLAN_CONTENT']['images']['2d'] = \Bitrix\Estate\Common::_resizeImageOrPlaceholder($flat['PLANOPLAN_CONTENT']['tabs']['2d']['content'], 670, 900);
            }
        }
        $flat['inFavorite'] = in_array($flat['ID'], $favoriteIds);
    }
}

$arResult['HOME_PATH'] = Estate\BaseEstate::ESTATE_HOME_PATH;

// Ссылка на избранное в меню
$favorite->setMenuFavoriteLink();

$this->IncludeComponentTemplate();
