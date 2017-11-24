<?php

// admin initialization
define('ADMIN_MODULE_NAME', 'estate');
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');

$isListMode = isset($_REQUEST['mode']) && $_REQUEST['mode'] === 'list';
$prolog = $isListMode ? 'prolog_admin_js.php' : 'prolog_admin_after.php';
$epilog = $isListMode ? 'epilog_admin_js.php' : 'epilog_admin.php';

IncludeModuleLangFile(__FILE__);

if (!$USER->IsAdmin()) {
	$APPLICATION->AuthForm(GetMessage('ACCESS_DENIED'));
}

if (!CModule::IncludeModule(ADMIN_MODULE_NAME)) {
	$APPLICATION->AuthForm(GetMessage('ACCESS_DENIED'));
}

use Bitrix\Estate as Estate;

if (isset($_REQUEST['ENTITY_ID']) && $_REQUEST['ENTITY_ID'] > 0) {
	$estate = Estate\EstateTable::getById($_REQUEST['ENTITY_ID'])->fetch();
}

if (empty($estate)) {
	// 404
	require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/' . $prolog);
	echo GetMessage('ESTATE_ADMIN_ROWS_LIST_NOT_FOUND');
	require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/' . $epilog);
	die();
}

$APPLICATION->SetTitle(GetMessage(
	'ESTATE_ADMIN_ROWS_LIST_PAGE_TITLE',
	array('#NAME#' => $estate['NAME'])
));

// get entity
$entityClass = 'Bitrix\\Estate\\' . $estate['CLASS_NAME'];
$entity = $entityClass::getInstance();

$sTableID = 'tbl_' . $estate['TABLE_NAME'];
$oSort = new CAdminSorting($sTableID, 'ID', 'asc');
$lAdmin = new CAdminList($sTableID, $oSort);

$arFilter = array();
if ($filters = $entity->getFilters()) {
	$FilterArr = array();
	foreach ($filters as $val) {
		$FilterArr[] = $val['param'];
	}

	$lAdmin->InitFilter($FilterArr);

	foreach ($filters as $name => $filter) {
		if (empty($$filter['param'])) {
			continue;
		}
		$arFilter[$name] = $$filter['param'];
	}
}

$headers = $entity->getHeaders();

$USER_FIELD_MANAGER->AdminListAddHeaders('ESTATE_' . $estate['ID'], $headers);

// show all by default
$columnsFilters = array();
foreach ($headers as &$header) {
	$header['default'] = true;
	if (!empty($header['filter'])) {
		$columnsFilters[$header['id']] = $header['filter'];
	}
}
unset($header);

$lAdmin->AddHeaders($headers);

if (!in_array($by, $lAdmin->GetVisibleHeaderColumns(), true)) {
	$by = 'ID';
}

// select data
$selectFields = $lAdmin->GetVisibleHeaderColumns();
$selectFields = array_merge($selectFields, array_values($columnsFilters));
$rsData = $entityClass::getList(array(
	'select' => $selectFields,
	'order'  => array($by => strtoupper($order)),
	'filter' => $arFilter
));

$rsData = new CAdminResult($rsData, $sTableID);
$rsData->NavStart();

// menu
$addUrl = 'estate_row_edit.php?ENTITY_ID=' . $estate['ID']. '&lang=' . LANGUAGE_ID;
if (isset($find_parent)) {
	$addUrl .= '&PARENT=' . $find_parent;
}
$aMenu = array(
	array(
		'TEXT'	=> GetMessage('ESTATE_ADMIN_ROWS_ADD_NEW_BUTTON'),
		'TITLE'	=> GetMessage('ESTATE_ADMIN_ROWS_ADD_NEW_BUTTON'),
		'LINK'	=> $addUrl,
		'ICON'	=> 'btn_new',
	),
	/*array(
		'TEXT'	=> GetMessage('ESTATE_ADMIN_ROWS_EDIT_ENTITY'),
		'TITLE'	=> GetMessage('ESTATE_ADMIN_ROWS_EDIT_ENTITY'),
		'LINK'	=> 'estate_entity_edit.php?ID=' . $estate['ID'] . '&lang=' . LANGUAGE_ID,
		'ICON'	=> 'btn_edit',
	)*/
);

//$context = new CAdminContextMenu($aMenu);

$lAdmin->AddAdminContextMenu($aMenu);

// build list
$lAdmin->NavText($rsData->GetNavPrint(GetMessage('PAGES')));
$childEntity = $entity->getChildEntity();

while($arRes = $rsData->NavNext(true, 'f_')) {
	$row = $lAdmin->AddRow($f_ID, $arRes);

	foreach ($columnsFilters as $column => $filter) {
		if (!isset($arRes[$column], $arRes[$fitler]))
		$link = '/bitrix/admin/estate_rows_list.php?ENTITY_ID=' . $estate['ID']
				 . '&set_filter=Y&find_' . strtolower($filter) . '=' . $arRes[$filter] . '&lang=' . LANGUAGE_ID;
		$row->AddViewField(
			$column,
			$arRes[$column] . ' [<a href="' . $link . '">фильтр</a>]'
		);
	}

	if ($childEntity) {
		foreach ($childEntity as $childEntityId) {
			$url = '/bitrix/admin/estate_rows_list.php?ENTITY_ID=' . $childEntityId
				 . '&set_filter=Y&find_parent=' . $f_ID . '&lang=' . LANGUAGE_ID;
			$addUrl = '/bitrix/admin/estate_row_edit.php?ENTITY_ID=' . $childEntityId
					. '&PARENT=' . $f_ID . '&lang=' . LANGUAGE_ID;

			if($estate['ID'] == Estate\BaseEstate::ESTATE_ENTITY_SECTION) {
				$url = '/bitrix/admin/estate_rows_list.php?ENTITY_ID=' . $childEntityId
					. '&set_filter=Y&find_parent_section=' . $f_ID . '&find_parent=' . $arRes['PARENT'] . '&lang=' . LANGUAGE_ID;
				$addUrl = '/bitrix/admin/estate_row_edit.php?ENTITY_ID=' . $childEntityId
					. '&PARENT_SECTION=' . $f_ID . '&find_parent=' . $arRes['PARENT'] . '&lang=' . LANGUAGE_ID;
			}

			$fieldName = 'f_CHILD_' . $childEntityId . '_COUNT';

			$row->AddViewField('CHILD_' . $childEntityId . '_COUNT', $$fieldName
							 . ' [<a href="' . $url . '">перейти</a>]'
							 . ' [<a href="' . $addUrl . '">добавить</a>]');
		}
	}

	$USER_FIELD_MANAGER->AddUserFields('ESTATE_' . $estate['ID'], $arRes, $row);

	$can_edit = true;

	$arActions = Array();

	$editUrl = 'estate_row_edit.php?ENTITY_ID=' . $estate['ID'] . '&ID=' . $f_ID;
	if (isset($find_parent)) {
		$editUrl .= '&PARENT=' . $find_parent;
	}
	$arActions[] = array(
		'ICON'    => 'edit',
		'TEXT'    => GetMessage($can_edit ? 'MAIN_ADMIN_MENU_EDIT' : 'MAIN_ADMIN_MENU_VIEW'),
		'ACTION'  => $lAdmin->ActionRedirect($editUrl),
		'DEFAULT' => true
	);

	$redirAction = 'estate_row_edit.php?action=delete&ENTITY_ID=' . $estate['ID']
				 . '&ID=' . $f_ID . '&' . bitrix_sessid_get();
	$arActions[] = array(
		'ICON'   => 'delete',
		'TEXT'   => GetMessage('MAIN_ADMIN_MENU_DELETE'),
		'ACTION' => 'if(confirm("' . GetMessageJS('ESTATE_ADMIN_DELETE_ROW_CONFIRM') . '")) '
				   . $lAdmin->ActionRedirect($redirAction)
	);

	$row->AddActions($arActions);

	// deny group operations (hide checkboxes)
	$row->pList->bCanBeEdited = false;
}


// view
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/' . $prolog);

/*if (!$isListMode) {
	$context->Show();
}*/


$lAdmin->CheckListMode();

if ($filters = $entity->getFilters()) {
	// создадим объект фильтра
	$filterNames = array();
	foreach ($filters as $val) {
		$filterNames[] = $val['name'];
	}
	$oFilter = new CAdminFilter(
		$sTableID . '_filter',
		$filterNames
	);
	?>
	<form name="find_form" method="get" action="<?= $APPLICATION->GetCurPage() ?>">
	<input type="hidden" name="ENTITY_ID" value="<?= (int) $_REQUEST['ENTITY_ID'] ?>" />
	<?php $oFilter->Begin(); ?>
	<?php foreach ($filters as $filter): ?>
		<tr>
		  	<td><?= $filter['name'] ?>:</td>
		  	<td>
		  		<?php if (empty($filter['variants'])): ?>
		    		<input type="text"
		    			   name="<?= $filter['param'] ?>"
		    			   size="47"
		    			   value="<?= htmlspecialchars($$filter['param']) ?>">
		    	<?php else: ?>
		    		<?php
		    		$arr = array(
						'reference'    => array_values($filter['variants']),
						'reference_id' => array_keys($filter['variants'])
		    		);
				    echo SelectBoxFromArray($filter['param'], $arr, $$filter['param'], $filter['name'], '');
				    ?>
		    	<?php endif; ?>
		  	</td>
		</tr>
	<?php endforeach; ?>

	<?php
	$oFilter->Buttons(array(
		'table_id' => $sTableID,
		'url'      => $APPLICATION->GetCurPage(),
		'form'     => 'find_form'
	));
	$oFilter->End();
	?>
	</form>
	<?php if (isset($find_parent)): ?>
		<a href="/bitrix/admin/estate_rows_list.php?ENTITY_ID=<?= $entity->getParentEntity() ?>&lang=<?= LANGUAGE_ID ?>">
			Вернуться
		</a>
		<br/><br/>
	<?php endif; ?>
	<?php
}

$lAdmin->DisplayList();

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/' . $epilog);
