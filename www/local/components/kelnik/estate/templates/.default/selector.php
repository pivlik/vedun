<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>

<?php $APPLICATION->IncludeComponent(
    "kelnik:estate.selector",
    "",
    array(
        "PATH_TO_BUILDING" => $arResult["PATH_TO_BUILDING"],
        "PATH_TO_SECTION" => $arResult["PATH_TO_SECTION"],
        "PATH_TO_FLOOR" => $arResult["PATH_TO_FLOOR"],
        "PATH_TO_FLAT" => $arResult["PATH_TO_FLAT"],
        "PATH_TO_SEARCH" => $arResult["PATH_TO_SEARCH"],
        "PATH_TO_FAVORITE" => $arResult["PATH_TO_FAVORITE"],
        "IS_AJAX" => isset($_GET['AJAX']) && $_GET['AJAX'] === 'Y',
    ),
    $component
); ?>
