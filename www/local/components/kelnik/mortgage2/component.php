<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (!isset($arParams['CACHE_TIME'])) {
    $arParams['CACHE_TIME'] = 3600;
}


$arParams['BANK_IBLOCK_ID'] = 5;
$arParams['PROGRAMM_IBLOCK_ID'] = 7;
$arParams['RATE_IBLOCK_ID'] = 8;
$arParams['CACHE_TYPE'] = 'N';

//if($this->StartResultCache(	false,	$arParams))
{
    if (!CModule::IncludeModule('iblock')) {
        $this->AbortResultCache();
        ShowError(GetMessage('IBLOCK_MODULE_NOT_INSTALLED'));
        return;
    }

    if (!isset($arParams['TYPE'])) {
        $this->AbortResultCache();
        ShowError('Не передан тип калькулятора');
        return;
    }


    $arResult['MORTGAGE'] = array();
    $arResult['BANKS'] = array();

    // Ставки
    $arSelect = array(
        'ID',
        'NAME',
        'PROPERTY_PROGRAMM',
        'PROPERTY_RATE',
        'PROPERTY_MIN_PAYMENT',
        'PROPERTY_MAX_PAYMENT',
        'PROPERTY_MIN_SUMM',
        'PROPERTY_MAX_SUMM',
        'PROPERTY_MIN_TIME',
        'PROPERTY_MAX_TIME',
        'PROPERTY_MIN_CREDIT',
    );
    $arFilter = array(
        'IBLOCK_ID' => $arParams['RATE_IBLOCK_ID'],
        'ACTIVE' => 'Y',
    );
    $arOrder = array(
        'SORT' => 'DESC'
    );

    $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
    $arResult['rates'] = array();
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        $arFields['PROPERTY_MIN_CREDIT_VALUE'] = price_format($arFields['PROPERTY_MIN_CREDIT_VALUE']);
        $arResult['rates'][$arFields['PROPERTY_PROGRAMM_VALUE']] = $arFields;
    }

    if (count($arResult['rates'])) {
        // Программы
        $arSelect = array(
            'ID',
            'NAME',
            'PROPERTY_BANK',
        );
        $arFilter = array(
            'IBLOCK_ID' => $arParams['PROGRAMM_IBLOCK_ID'],
            'ACTIVE' => 'Y',
            'ID' => array_keys($arResult['rates']),
        );
        $arOrder = array(
            'SORT' => 'DESC'
        );
        $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($ob = $res->GetNextElement()) {

            $arFields = $ob->GetFields();
            $rate = $arResult['rates'][$arFields['ID']];
            $arResult['MORTGAGE'][$arFields['PROPERTY_BANK_VALUE']] = $rate;
        }

    }

    // Банки
    $arSelect = array(
        'ID',
        'NAME',
        'PREVIEW_PICTURE',
        'PROPERTY_LINK',
    );
    $arFilter = array(
        'IBLOCK_ID' => $arParams['BANK_IBLOCK_ID'],
        'ACTIVE' => 'Y',
    );
    $arOrder = array(
        'SORT' => 'ASC'
    );
    $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
    while ($ob = $res->GetNextElement()) {

        $arFields = $ob->GetFields();
        //$arResult['BANKS'][$arFields['ID']]['NAME'] = $arFields['NAME'];

        $arFields['IMG'] = CFile::GetFileArray($arFields['PREVIEW_PICTURE']);
        $arResult['BANKS'][$arFields['ID']] = $arFields;
        $arResult['BANKS'][$arFields['ID']]['LINK'] = $arFields['PROPERTY_LINK_VALUE'];
    }

    $this->IncludeComponentTemplate($template);
}
