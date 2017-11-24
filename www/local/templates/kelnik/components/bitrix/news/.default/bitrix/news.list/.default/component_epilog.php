<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$isAjax = (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) ? ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') : FALSE;
if ($isAjax) {

    $content = ob_get_clean();
    $APPLICATION->RestartBuffer();

    $content = explode('<!-- ajax-news-items -->', $content);

    $data = array(
        'html' => $content[1],
        'nextUrl' => $arResult['NAV_RESULT']->NavPageCount > $arResult['NAV_RESULT']->NavPageNomer
                     ? $_SERVER['PHP_SELF'] . '?PAGEN_1=' . ($arResult['NAV_RESULT']->NavPageNomer + 1)
                     : '',
    );
    header('Content-type:application/json; charset=UTF-8');
    die(json_encode($data));
}
