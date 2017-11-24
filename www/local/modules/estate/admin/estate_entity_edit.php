<?php

// admin initialization
define("ADMIN_MODULE_NAME", "estate");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

IncludeModuleLangFile(__FILE__);
IncludeModuleLangFile(__DIR__.'/estate_rows_list.php');

if (!$USER->IsAdmin())
{
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

if (!CModule::IncludeModule(ADMIN_MODULE_NAME))
{
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

// form
$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage('ESTATE_ADMIN_ENTITY_TITLE'), "ICON"=>"ad_contract_edit", "TITLE"=> GetMessage('ESTATE_ADMIN_ENTITY_TITLE'))
);
$tabControl = new CAdminTabControl("form_element_est", $aTabs);

$is_create_form = true;
$is_update_form = false;

$errors = array();

use Bitrix\Estate as Estate;

// get estate data
if (isset($_REQUEST['ID']) && $_REQUEST['ID'] > 0)
{
	$filter = array(
		'select' => array('ID', 'NAME', 'CLASS_NAME', 'TABLE_NAME', 'FIELDS_COUNT'),
		'filter' => array('=ID' => $_REQUEST['ID'])
	);
	$estate = Estate\EstateTable::getList($filter)->fetch();

	if (!empty($estate))
	{
		$is_update_form = true;
		$is_create_form = false;
	}
}



if ($is_create_form)
{
	// default values for create form
	$estate = array_fill_keys(array('ID', 'NAME', 'CLASS_NAME', 'TABLE_NAME'), '');

	// page title
	$APPLICATION->SetTitle(GetMessage('ESTATE_ADMIN_ENTITY_EDIT_PAGE_TITLE_NEW'));
}
else
{
	$APPLICATION->SetTitle(GetMessage('ESTATE_ADMIN_ENTITY_EDIT_PAGE_TITLE_EDIT', array('#NAME#' => $estate['NAME'])));

	//$entity = Estate\EstateTable::compileEntity($estate);
	$entityName = 'Bitrix\\Estate\\' . $estate['CLASS_NAME'];
	$entity = \Bitrix\Main\Entity\Base::getInstance($entityName);

	$entity_data_class = $entity->getDataClass();
	$entity_table_name = $estate['TABLE_NAME'];

	$estate['ROWS_COUNT'] = $entity_data_class::getCount();
}

$isEditMode = true;

// delete action
if ($is_update_form && isset($_REQUEST['action']) && $_REQUEST['action'] === 'delete' && check_bitrix_sessid())
{
	Estate\EstateTable::delete($estate['ID']);

	LocalRedirect("estate_index.php?lang=".LANGUAGE_ID);
}


// save action
if ((strlen($save)>0 || strlen($apply)>0) && $REQUEST_METHOD=="POST" && check_bitrix_sessid())
{
	$data = array(
		'NAME' => trim($_REQUEST['NAME']),
		//'CLASS_NAME' => trim($_REQUEST['CLASS_NAME']),
		'TABLE_NAME' => trim($_REQUEST['TABLE_NAME'])
	);

	if ($is_update_form)
	{
		$ID = intval($_REQUEST['ID']);
		$result = Estate\EstateTable::update($ID, $data);
	}
	else
	{
			// create
		$result = Estate\EstateTable::add($data);
		$ID = $result->getId();
	}

	if ($result->isSuccess())
	{
		if (strlen($save)>0)
		{
			LocalRedirect("estate_index.php?lang=".LANGUAGE_ID);
		}
		else
		{
			LocalRedirect("estate_entity_edit.php?ID=".$ID."&lang=".LANGUAGE_ID."&".$tabControl->ActiveTabParam());
		}
	}
	else
	{
		$errors = $result->getErrorMessages();
	}

	// rewrite original value by form value to restore form
	foreach ($data as $k => $v)
	{
		$estate[$k] = $v;
	}
}


// menu
$aMenu = array(
	array(
		"TEXT"	=> GetMessage('ESTATE_ADMIN_ROWS_RETURN_TO_LIST_BUTTON'),
		"TITLE"	=> GetMessage('ESTATE_ADMIN_ROWS_RETURN_TO_LIST_BUTTON'),
		"LINK"	=> "estate_index.php?lang=".LANGUAGE_ID,
		"ICON"	=> "btn_list",
	)
);

$context = new CAdminContextMenu($aMenu);


// view
if ($_REQUEST["mode"] == "list")
{
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_js.php");
}
else
{
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
}

$context->Show();


if (!empty($errors))
{
	CAdminMessage::ShowMessage(join("\n", $errors));
}
?>
<form name="form1" method="POST" action="<?=$APPLICATION->GetCurPage()?>">
<?=bitrix_sessid_post()?>
<input type="hidden" name="ID" value="<?=htmlspecialcharsbx($estate['ID'])?>">
<input type="hidden" name="lang" value="<?=LANGUAGE_ID?>">
<?
$tabControl->Begin();

$tabControl->BeginNextTab();

?>
<tr>
	<td width="40%"><strong><?=GetMessage('HIGHLOADBLOCK_HIGHLOAD_BLOCK_ENTITY_NAME_FIELD')?></strong></td>
	<td><?
		if (!$isEditMode):
			?><?=htmlspecialcharsEx($estate['NAME'])?><?
		else :
			?><input type="text" name="NAME" size="30" value="<?=htmlspecialcharsbx($estate['NAME'])?>"><?
		endif;
	?></td>
</tr>
<?/*<tr>
	<td><strong><?=GetMessage('ESTATE_ADMIN_ENTITY_EDIT_TABLE_NAME')?></strong></td>
	<td><?
		if (!$isEditMode):
			?><?=htmlspecialcharsEx($estate['TABLE_NAME'])?><?
		else :
			?><input type="text" name="TABLE_NAME" size="30" value="<?=htmlspecialcharsbx($estate['TABLE_NAME'])?>"><?
		endif;
		?></td>
</tr>
<tr>
	<td><strong><?=GetMessage('ESTATE_ADMIN_ENTITY_EDIT_СLASS_NAME')?></strong></td>
	<td><?
		if (!$isEditMode):
			?><?=htmlspecialcharsEx($estate['CLASS_NAME'])?><?
		else :
			?><input type="text" name="CLASS_NAME" size="30" value="<?=htmlspecialcharsbx($estate['CLASS_NAME'])?>"><?
		endif;
		?></td>
</tr>*/?>

<? if ($is_update_form): ?>
	<tr>
		<td><?=GetMessage('ESTATE_ADMIN_ENTITY_EDIT_FIELDS_COUNT')?></td>
		<td><a href="userfield_admin.php?lang=<?=LANGUAGE_ID?>&set_filter=Y&find=ESTATE_<?=intval($estate['ID'])?>&find_type=ENTITY_ID&back_url=<?=urlencode($APPLICATION->GetCurPageParam())?>">[<?=intval($estate['FIELDS_COUNT'])?>]</a></td>
	</tr>

	<tr>
		<td><?=GetMessage('ESTATE_ADMIN_ENTITY_EDIT_ROWS_COUNT')?></td>
		<td><a href="estate_rows_list.php?lang=<?=LANGUAGE_ID?>&ENTITY_ID=<?=intval($estate['ID'])?>">[<?=intval($estate['ROWS_COUNT'])?>]</a></td>
	</tr>
<? endif; ?>



<?
$disable = true;
if($isEditMode)
	$disable = false;

$tabControl->Buttons(array("disabled" => $disable, "back_url"=>"estate_index.php?lang=".LANGUAGE_ID));
$tabControl->End();
?>
</form>
<?


if ($_REQUEST["mode"] == "list")
{
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin_js.php");
}
else
{
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
}
