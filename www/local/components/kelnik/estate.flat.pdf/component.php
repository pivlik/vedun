<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (!CModule::IncludeModule("iblock")) {
    $this->AbortResultCache();
    ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
    return;
}

if (!isset($arParams['FLAT_ID']) || !(int)$arParams['FLAT_ID']) {
    @define("ERROR_404", "Y");
    CHTTP::SetStatus("404 Not Found");
    return;
}

if (!isset($arParams['CACHE_TIME'])) {
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

// Карта активных элементов недвижимости
$activeMap = Estate\BaseEstate::getActiveElementsMap();

// Данные квартиры
$flatsInstance = Estate\EstateFlatTable::getInstance();
$arResult['FLAT'] = $flatsInstance->getFullFlatInfo($arParams['FLAT_ID']);

// Активна ли квартира
if ((int)$arResult['FLAT']['STATUS'] !== Estate\EstateFlatTable::IN_SALE_STATUS) {
    @define("ERROR_404", "Y");
    CHTTP::SetStatus("404 Not Found");
    return;
}

// Активен ли родительский этаж
if (!isset($activeMap['FLOORS'][$arResult['FLAT']['PARENT']])) {
    @define("ERROR_404", "Y");
    CHTTP::SetStatus("404 Not Found");
    return;
}

if ($arResult['FLAT']['PLANOPLAN']) {

    $str = file_get_contents(
        "http://widget.planoplan.com/data/?hash=" . $arResult['FLAT']['PLANOPLAN'] . "&lang=&width=&height=&callback=CallbackRegistry.f_933182223383395"
    );
    $str = str_replace('CallbackRegistry.f_933182223383395(', '', $str);
    $str = substr($str, 0, -2);
    $arResult['PLANOPLAN'] = json_decode($str, true);
    $arResult['PLANOPLAN']['tabs']['3d']['content'] = 'http:' . $arResult['PLANOPLAN']['tabs']['3d']['content'];
    $arResult['PLANOPLAN']['tabs']['2d']['content'] = 'http:' . $arResult['PLANOPLAN']['tabs']['2d']['content'];
}


require $_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/lib/dompdf/dompdf_config.inc.php';

if ($this->StartResultCache($arParams['FLAT_ID'])) {
    $this->IncludeComponentTemplate();
}
$html = ob_get_clean();

//echo $html;
$dompdf = new DOMPDF();
$dompdf->set_paper('A4', 'landscape');
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("flat.pdf", array("Attachment" => 0));

die;
