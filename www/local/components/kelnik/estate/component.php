 <?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$arComponentVariables = array('FLAT_ID', 'FLOOR_ID', 'SECTION_ID', 'BUILDING_ID',
                              'favorite', 'search');
$arDefaultUrlTemplates404 = array(
    'selector'     => 'visual/',
    'building'     => 'visual/building/#BUILDING_ID#/',
    'section'      => 'visual/building/#BUILDING_ID#/section/#SECTION_ID#/',
    'floor'        => 'visual/building/#BUILDING_ID#/section/#SECTION_ID#/floor/#FLOOR_ID#/',
    'flat'         => 'flat/#FLAT_ID#/',
    'flat-pdf'     => 'flat/#FLAT_ID#/pdf/',
    'favorite'     => 'favorite',
    'favorite-pdf' => 'favorite/pdf/',
    //'search'       => 'search'
);

if ($arParams['SEF_MODE'] == 'Y')
{
    $arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404);//, $arParams['SEF_URL_TEMPLATES']);
    $componentPage = CComponentEngine::ParseComponentPath($arParams['SEF_FOLDER'], $arUrlTemplates, $arVariables);

    if (!$componentPage) {
        $componentPage = CComponentEngine::ParseComponentPath($arParams['SEF_FOLDER'], $arUrlTemplates, $arVariables, $arParams['SEF_FOLDER'] . 'visual/index.php');
    }

    $arResult = array('VARIABLES' => $arVariables, 'ALIASES' => $arVariableAliases);
    $arResult['PATH_TO_BUILDING'] = $arParams['SEF_FOLDER'].$arParams['SEF_URL_TEMPLATES']['building'];
    $arResult['PATH_TO_SECTION'] = $arParams['SEF_FOLDER'].$arParams['SEF_URL_TEMPLATES']['section'];
    $arResult['PATH_TO_FLOOR'] = $arParams['SEF_FOLDER'].$arParams['SEF_URL_TEMPLATES']['floor'];
    $arResult['PATH_TO_FLAT'] = $arParams['SEF_FOLDER'].$arParams['SEF_URL_TEMPLATES']['flat'];
    $arResult['PATH_TO_SEARCH'] = $arParams['SEF_FOLDER'].$arParams['SEF_URL_TEMPLATES']['search'];
    $arResult['PATH_TO_FAVORITE'] = $arParams['SEF_FOLDER'].$arParams['SEF_URL_TEMPLATES']['favorite'];
}
else
{
    $arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases, $arParams['VARIABLE_ALIASES']);
    CComponentEngine::InitComponentVariables(false, $arComponentVariables, $arVariableAliases, $arVariables);

    if (isset($arVariables['FLAT_ID'])) {
        $componentPage = 'flat';
    } elseif (isset($arVariables['FLOOR_ID'])) {
        $componentPage = 'floor';
    } elseif (isset($arVariables['SECTION_ID'])) {
        $componentPage = 'section';
    } elseif (isset($arVariables['BUILDING_ID'])) {
        $componentPage = 'section';
    } elseif (isset($arVariables['favorite'])) {
        $componentPage = 'favorite';
    } elseif (isset($arVariables['search'])) {
        $componentPage = 'search';
    } else {
        $componentPage = 'selector';
    }

    $arResult = array('VARIABLES' => $arVariables, 'ALIASES' => $arVariableAliases);
    $arVarAliaces = $arParams['VARIABLE_ALIASES'];

    $arResult['PATH_TO_BUILDING'] = $APPLICATION->GetCurPageParam($arParams['VARIABLE_ALIASES']['section_id'].'=#BUILDING_ID#', $arForDel);
    $arResult['PATH_TO_SECTION'] = $APPLICATION->GetCurPageParam($arParams['VARIABLE_ALIASES']['section_id'].'=#SECTION_ID#', $arForDel);
    $arResult['PATH_TO_FLOOR'] = $APPLICATION->GetCurPageParam($arParams['VARIABLE_ALIASES']['floor_id'].'=#FLOOR_ID#', $arForDel);
    $arResult['PATH_TO_FLAT'] = $APPLICATION->GetCurPageParam($arParams['VARIABLE_ALIASES']['flat_id'].'=#FLAT_ID#', $arForDel);
    $arResult['PATH_TO_FAVORITE'] = $APPLICATION->GetCurPageParam($arParams['VARIABLE_ALIASES']['favorite'], $arForDel);
    $arResult['PATH_TO_SEARCH'] = $APPLICATION->GetCurPageParam($arParams['VARIABLE_ALIASES']['search'], $arForDel);
}

if (!$componentPage) {
    @define("ERROR_404", "Y");
    CHTTP::SetStatus("404 Not Found");
    return;
}

$this->IncludeComponentTemplate($componentPage);
?>
