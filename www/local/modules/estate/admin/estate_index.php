<?php

define("ADMIN_MODULE_NAME", "estate");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

IncludeModuleLangFile(__FILE__);
IncludeModuleLangFile(__DIR__.'/menu.php');

if (!$USER->IsAdmin()) {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

if (!CModule::IncludeModule(ADMIN_MODULE_NAME)) {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

$APPLICATION->SetTitle(GetMessage('ESTATE_ADMIN_MENU_TITLE'));

$sTableID = "b_estate_entity";
$oSort = new CAdminSorting($sTableID, "ID", "asc");
$lAdmin = new CAdminList($sTableID, $oSort);

$arHeaders = array(
    array("id"=>"ID", "content"=>"ID", "sort"=>"ID", "default"=>true),
    array("id"=>"NAME", "content"=>GetMessage('ESTATE_ADMIN_ENTITY_TITLE'), "sort"=>"NAME", "default"=>true),
    array("id"=>"TABLE_NAME", "content"=>GetMessage('ESTATE_ADMIN_ENTITY_TABLE_NAME'), "sort"=>"TABLE_NAME", "default"=>true),
    array("id"=>"CLASS_NAME", "content"=>GetMessage('ESTATE_ADMIN_ENTITY_CLASS_NAME'), "sort"=>"CLASS_NAME", "default"=>true)
);

$lAdmin->AddHeaders($arHeaders);

// menu
if ($_REQUEST["mode"] !== "list") {
    /*$aMenu = array(
        array(
            "TEXT"  => GetMessage('ESTATE_ADMIN_ADD_ENTITY_BUTTON'),
            "TITLE" => GetMessage('ESTATE_ADMIN_ADD_ENTITY_BUTTON'),
            "LINK"  => "estate_entity_edit.php?lang=".LANGUAGE_ID,
            "ICON"  => "btn_new",
        )
    );

    $context = new CAdminContextMenu($aMenu);*/
}

use Bitrix\Estate as Estate;

// select data
$rsData = Estate\EstateTable::getList(array(
    "select" => $lAdmin->GetVisibleHeaderColumns(),
    "order" => array($by => strtoupper($order))
));

$rsData = new CAdminResult($rsData, $sTableID);
$rsData->NavStart();

// build list
$lAdmin->NavText($rsData->GetNavPrint(GetMessage("PAGES")));
while($arRes = $rsData->NavNext(true, "f_")) {
    $row = $lAdmin->AddRow($f_ID, $arRes);

    $can_edit = true;

    $arActions = Array();

    $arActions[] = array(
        "ICON" => "list",
        "TEXT" => GetMessage('ESTATE_ADMIN_ROWS_LIST'),
        "ACTION" => $lAdmin->ActionRedirect("estate_rows_list.php?ENTITY_ID=".$f_ID),
        "DEFAULT" => true
    );

    $arActions[] = array(
        "ICON"=>"list",
        "TEXT" => GetMessage('ESTATE_ADMIN_FIELDS_LIST'),
        "ACTION" => $lAdmin->ActionRedirect(
            "userfield_admin.php?lang=".LANGUAGE_ID."&set_filter=Y&find=ESTATE_".intval($f_ID)."&find_type=ENTITY_ID&back_url=".urlencode($APPLICATION->GetCurPageParam())
        )
    );

    $arActions[] = array(
        "ICON"=>"edit",
        "TEXT"=>GetMessage($can_edit ? "MAIN_ADMIN_MENU_EDIT" : "MAIN_ADMIN_MENU_VIEW"),
        "ACTION"=>$lAdmin->ActionRedirect("estate_entity_edit.php?ID=".$f_ID)
    );

    $arActions[] = array(
        "ICON"=>"delete",
        "TEXT" => GetMessage("MAIN_ADMIN_MENU_DELETE"),
        "ACTION" => "if(confirm('".GetMessageJS('ESTATE_ADMIN_DELETE_ENTITY_CONFIRM')."')) ".
            $lAdmin->ActionRedirect("estate_entity_edit.php?action=delete&ID=".$f_ID.'&'.bitrix_sessid_get())
    );

    $row->AddActions($arActions);
}


// view

if ($_REQUEST["mode"] == "list") {
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_js.php");
} else {
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

    //$context->Show();
}

$lAdmin->CheckListMode();

$lAdmin->DisplayList();


if ($_REQUEST["mode"] == "list") {
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin_js.php");
} else {
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
}

