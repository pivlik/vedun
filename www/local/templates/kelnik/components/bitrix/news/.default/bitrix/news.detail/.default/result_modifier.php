<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$activeFrom = strtotime($arResult['ACTIVE_FROM']);
$arResult['DATE'] = date('Y-m-d', $activeFrom);
$year = date('Y', $activeFrom);
if ($year !== date('Y')) {
    $arResult['DISPLAY_ACTIVE_FROM'] .= ' ' . $year;
}
