<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (!isset($arParams['CACHE_TIME'])) {
    $arParams['CACHE_TIME'] = 3600;
}

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
$arParams['PRICE'] = !empty($_REQUEST['price']) ? str_replace(' ', '', $_REQUEST['price']) : $arParams['PRICE'];
$arParams['PAYMENT'] = !empty($_REQUEST['payment']) ? str_replace(' ', '', $_REQUEST['payment']) : false;
$arParams['TIME'] = !empty($_REQUEST['time']) ? $_REQUEST['time'] : 10;
$arParams['QUEUE'] = !empty($_REQUEST['queue']) ? $_REQUEST['queue'] : $arParams['QUEUE'];

$arParams['BANK_IBLOCK_ID'] = 15;
$arParams['PROGRAMM_IBLOCK_ID'] = 16;
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

    $queue = false;
    $minPrice = $maxPrice = $arParams['PRICE'];

    // Значения по умолчанию
    $arResult['MIN_PAYMENT_PROC'] = 0.2;
    $arResult['MIN_PAYMENT'] = $minPrice * $arResult['MIN_PAYMENT_PROC'];
    $arResult['MAX_PAYMENT'] = $maxPrice * 0.99;
    $arResult['MIN_TIME'] = 1;
    $arResult['MAX_TIME'] = 30;

    $arResult['MORTGAGE'] = array();
    $arResult['BANKS'] = array();
    $arResult['ACTIVE_MORTGAGE'] = array();


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
        $arFields['IMG'] = CFile::GetFileArray($arFields['PREVIEW_PICTURE']);
        $arResult['BANKS'][$arFields['ID']] = $arFields;
        $arResult['BANKS'][$arFields['ID']]['LINK'] = $arFields['PROPERTY_LINK_VALUE'];
    }



    //программы
    $arSelect = array(
        'ID',
        'NAME',
        'PROPERTY_BANK',
        'PROPERTY_RATE',
        'PROPERTY_MIN_PAYMENT',
        'PROPERTY_MAX_PAYMENT',
        'PROPERTY_MIN_SUMM',
        'PROPERTY_MAX_SUMM',
        'PROPERTY_MIN_TIME',
        'PROPERTY_MAX_TIME',
        'PROPERTY_LINK',
//        'PROPERTY_BUILDING',
        'PROPERTY_FOR_ARMY',
    );

    $arFilter = array(
        'IBLOCK_ID' => $arParams['PROGRAMM_IBLOCK_ID'],
        'ACTIVE' => 'Y',
    );

    $arOrder = array(
        'SORT' => 'DESC'
    );

    $minTime = array($arResult['MIN_TIME']);
    $maxTime = array();
    $minPayment = array();
    $maxPayment = array();
    $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();

        if ($arFields['PROPERTY_MIN_TIME_VALUE']) {
            $minTime[] = $arFields['PROPERTY_MIN_TIME_VALUE'];
        }
        $maxTime[] = $arFields['PROPERTY_MAX_TIME_VALUE'];
        $minPayment[] = $arFields['PROPERTY_MIN_PAYMENT_VALUE'];
        $maxPayment[] = $arFields['PROPERTY_MAX_PAYMENT_VALUE'];

        $rate = $arFields['PROPERTY_RATE_VALUE'];
        $pow = pow(1 + $rate / 1200, -$arParams['TIME'] * 12 + 1);
        $payment = round(($remain * $rate / 1200) / (1 - $pow));
        $programm = array(
            'PAYMENT' => price_format($payment),
            'RATE' => $rate,
            'ID' => $arFields['ID'],
            'BANK' => $arFields['PROPERTY_BANK_VALUE'],
            'LINK' => $arFields['PROPERTY_LINK_VALUE'],
            'FOR_ARMY' => $arFields['PROPERTY_FOR_ARMY_VALUE'],
            'MIN_PAYMENT' => $arFields['PROPERTY_MIN_PAYMENT_VALUE'],
            'MAX_TIME' => $arFields['PROPERTY_MAX_TIME_VALUE'],
            'MIN_SUMM' => $arFields['PROPERTY_MIN_SUMM_VALUE'],
            'MIN_SUMM_F' => price_format($arFields['PROPERTY_MIN_SUMM_VALUE']),
            'MIN_TIME_WORD' => plural(
                $arFields['PROPERTY_MIN_TIME_VALUE'],
                array('год', 'года', 'лет')
            ),
            'MAX_TIME_WORD' => plural(
                $arFields['PROPERTY_MAX_TIME_VALUE'],
                array('год', 'года', 'лет')
            ),
        );

        $arResult['MORTGAGE'][$arFields['ID']] = $programm;
    }




    if (!$isAjax) {
        $arResult['MIN_TIME'] = min($minTime);
        $arResult['MAX_TIME'] = max($maxTime);

        $minPayment = min($minPayment);
        $arResult['MIN_PAYMENT_PROC'] = $minPayment;
        $arResult['MIN_PAYMENT'] = $minPrice * $minPayment / 100;
    }

    $arResult['MIN_PAYMENT'] = ceil($arResult['MIN_PAYMENT'] / 50000) * 50000;
    $arResult['MAX_PAYMENT'] = round($arResult['MAX_PAYMENT'] / 50000) * 50000;
    $arResult['START_PAYMENT'] = ceil(($arResult['MAX_PAYMENT'] - $arResult['MIN_PAYMENT']) / (2 * 50000)) * 50000;
    $arResult['START_TIME'] = $arParams['TIME'];
    $arResult['MIN_TIME_WORD'] = plural(
        $arResult['START_TIME'],
        array('год', 'года', 'лет')
    );

    if (!$arParams['PAYMENT']) {
        $arParams['PAYMENT'] = $arResult['START_PAYMENT'];
    }

    if (!$arParams['TIME']) {
        $arParams['TIME'] = $arResult['START_TIME'];
    }

    // Рассчет ставок
    $firstPayment = $arParams['PAYMENT'] * 100 / $arParams['PRICE'];
    $remain = $arParams['PRICE'] - $arParams['PAYMENT'];


    //Получаем активные программы
    foreach ($arResult['MORTGAGE'] as $programm) {
        if ($programm['MIN_PAYMENT'] <= $firstPayment
            && $programm['MIN_TIME'] <= $arParams['TIME']
            && $programm['MIN_SUMM'] <= $remain
            && (!$programm['MAX_PAYMENT'] || $programm['MAX_PAYMENT'] >= $firstPayment - 0.001)
            && (!$programm['MAX_SUMM'] || $programm['MAX_SUMM'] >= $remain)
            && (!$programm['MAX_TIME'] || $programm['MAX_TIME'] >= $arParams['TIME'])
        ) {
            $arResult['ACTIVE_MORTGAGE'][] = $programm['ID'];
        }
    }

    $template = $isAjax ? 'inc_banks' : '';
    $this->IncludeComponentTemplate($template);
}
