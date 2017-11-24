<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>

<?php $APPLICATION->IncludeComponent(
    "kelnik:estate.flat.pdf",
    "",
    array(
        "FLAT_ID" => $arResult["VARIABLES"]["FLAT_ID"],
    ),
    $component
); ?>
