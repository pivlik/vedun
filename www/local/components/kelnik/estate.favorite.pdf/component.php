<?php
header('Content-type:application/json; charset=UTF-8');
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

if (!CModule::IncludeModule("iblock")) {
    $this->AbortResultCache();
    ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
    return;
}

if(!isset($arParams['CACHE_TIME'])) {
    $arParams['CACHE_TIME'] = 3600;
}

$requiredModules = array('estate');
foreach ($requiredModules as $requiredModule) {
    if (!CModule::IncludeModule($requiredModule)) {
        ShowError(GetMessage('F_NO_MODULE'));
        return;
    }
}

use Bitrix\Estate as Estate;

$favorite = Estate\EstateFavoriteTable::getInstance();
$favoriteIds = $favorite->getFavoriteFlats();

if (!count($favoriteIds)) {
    $this->IncludeComponentTemplate();
    return;
}

$flatsInstance = Estate\EstateFlatTable::getInstance();

$params = array(
    'filter' => array(
        'ID' => $favoriteIds,
    ),
);
$arResult['ITEMS'] = $flatsInstance->getFlatsList($params);

$arResult['HOME_PATH'] = 'http://' . $_SERVER['HTTP_HOST'] . Estate\BaseEstate::ESTATE_HOME_PATH;

require $_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/lib/dompdf/dompdf_config.inc.php';


if($this->StartResultCache($arParams['FLAT_ID'])) {
    $this->IncludeComponentTemplate();
}
$html = ob_get_clean();

if (!is_dir($_SERVER['DOCUMENT_ROOT'] . '/upload/pdf')) {
    mkdir($_SERVER['DOCUMENT_ROOT'] . '/upload/pdf');
}
$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$pdf = $dompdf->output();

$fileName = $favorite->getPdfFileName();
$f = fopen($_SERVER['DOCUMENT_ROOT'] . '/upload/pdf/' . $fileName, 'w');
fwrite($f, $pdf);
fclose($f);
