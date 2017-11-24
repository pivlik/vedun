<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


$data = [];
$data['selects'] = $arResult['selects'];
$data['text'] = $arResult['GALLERY']['DETAIL_TEXT'];
$data['progressBar'] = [];
$data['progressBar']['is_ready'] = $arResult['GALLERY']['IS_READY'];
$data['progressBar']['ready_date'] = $arResult['GALLERY']['READY_DATE'];
$data['progressBar']['progress'] = $arResult['GALLERY']['PROGRESS'];
$data['gallery'] = [];
foreach ($arResult['GALLERY']['FOTOS'] as $foto){
    $item['thumbs'] = resizeOrPlacehold($foto['ID'], 90, 60);
    $item['normal'] = resizeOrPlacehold($foto['ID'], 420, 200);
    $item['big'] = resizeOrPlacehold($foto['ID'], 800, 600);
    $data['gallery'][] = $item;
}
echo json_encode($data);
exit();
