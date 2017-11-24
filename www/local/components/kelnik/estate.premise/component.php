<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

if(!isset($arParams['CACHE_TIME'])) {
    $arParams['CACHE_TIME'] = 3600;
}

if (!isset($arParams['TYPE'])) {
    @define("ERROR_404", "Y");
    CHTTP::SetStatus("404 Not Found");
    return;
}

$arParams['PAGEN'] = !empty($_REQUEST['PAGEN_1']) ? $_REQUEST['PAGEN_1'] : 1;
$arParams['AJAX'] = !empty($_GET['AJAX']) ? $_GET['AJAX'] : false;

use Bitrix\Estate as Estate;

if($arParams['AJAX'] || $this->StartResultCache($arParams['CACHE_TIME'], serialize($arParams))) {
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

    $perPage = 9;
    $offset = $arParams['PAGEN'] * $perPage - $perPage;

    // Статусы помещений
    $arResult['STATUSES'] = Estate\EstateRefPremiseStatusesTable::getAssoc(array(), 'ID');

    $premise = Estate\EstatePremiseTable::getInstance();
    $params = array(
        'filter' => array(
            'ACTIVE' => 'Y',
            'TYPE'   => $arParams['TYPE'],
        ),
        'offset'      => $offset,
        'limit'       => $perPage,
    );
    if ($arParams['STATUS']) {
        $params['filter']['STATUS'] = $arParams['STATUS'];
    }
    $arResult['ITEMS'] = $premise->getPremises($params);

    $cnt = $premise->getResultCount($params['filter']);
    $arResult['HAS_NEXT_PAGE'] = $cnt > $offset + $perPage;

    if ($arParams['AJAX']) {
        $APPLICATION->RestartBuffer();
        header('Content-type:application/json; charset=UTF-8');

        ob_start();
        $this->IncludeComponentTemplate('ajax');
        $html = ob_get_clean();

        $data = array(
            'content' => $html,
            'hasNextPage' => $arResult['HAS_NEXT_PAGE'],
        );

        echo json_encode($data);
        die;
    }

    $this->IncludeComponentTemplate();
}



