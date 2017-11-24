<?php

// admin initialization
define('ADMIN_MODULE_NAME', 'estate');
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');

$isListMode = isset($_REQUEST['mode']) && $_REQUEST['mode'] === 'list';
$prolog = $isListMode ? 'prolog_admin_js.php' : 'prolog_admin_after.php';
$epilog = $isListMode ? 'epilog_admin_js.php' : 'epilog_admin.php';

IncludeModuleLangFile(__FILE__);
IncludeModuleLangFile(__DIR__ . '/estate_rows_list.php');

if (!$USER->IsAdmin()) {
	$APPLICATION->AuthForm(GetMessage('ACCESS_DENIED'));
}

if (!CModule::IncludeModule(ADMIN_MODULE_NAME)) {
	$APPLICATION->AuthForm(GetMessage('ACCESS_DENIED'));
}

use Bitrix\Estate as Estate;

if (isset($_REQUEST['ENTITY_ID'])) {
	$estate = Estate\EstateTable::getById($_REQUEST['ENTITY_ID'])->fetch();
}

if (empty($estate)) {
	// 404
	require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/' . $prolog);
	echo GetMessage('ESTATE_ADMIN_ROW_EDIT_NOT_FOUND');
	require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/' . $epilog);
	die();
}

$isCreateForm = true;
$isUpdateForm = false;

$isEditMode = true;

$errors = array();

// get entity
$entityClass = 'Bitrix\\Estate\\' . $estate['CLASS_NAME'];
$entity = $entityClass::getInstance();

// get row
$row = array('ID' => '');
if (!empty($_REQUEST['ID'])) {
	$row = $entityClass::getById($_REQUEST['ID'])->fetch();

	if (!empty($row)) {
		$isUpdateForm = true;
		$isCreateForm = false;
	}
}

$titleLang = $isCreateForm
			 ? 'ESTATE_ADMIN_ENTITY_ROW_EDIT_PAGE_TITLE_NEW'
			 : 'ESTATE_ADMIN_ENTITY_ROW_EDIT_PAGE_TITLE_EDIT';
$titleReplacement = $isCreateForm
			 ? array('#NAME#' => $estate['NAME'])
			 : array('#NAME#' => $estate['NAME'], '#NUM#' => $row['ID']);
$APPLICATION->SetTitle(GetMessage($titleLang, $titleReplacement));

// form tabs
$aTabs = array(
	array(
		'DIV'   => 'edit1',
		'TAB'   => $estate['NAME'],
		'ICON'  => 'ad_contract_edit',
		'TITLE' => $estate['NAME']
	),
);

$tabControl = new CAdminForm('estaterow_edit_' . time(), $aTabs);

// delete action
if ($isUpdateForm
	&& isset($_REQUEST['action'])
	&& $_REQUEST['action'] === 'delete'
	&& check_bitrix_sessid()
) {
	$entityClass::delete($row['ID'], $estate);
	$entityClass::clearComponentsCache();
	LocalRedirect('estate_rows_list.php?ENTITY_ID=' . $estate['ID']
				. ($PARENT ? '&set_filter=Y&find_parent=' . $PARENT : '')
				. '&lang=' . LANGUAGE_ID
	);
}

$PARENT = !empty($_GET['PARENT']) ? (int) $_GET['PARENT'] : false;

// save action
if ((strlen($save)>0 || strlen($apply)>0) && $REQUEST_METHOD=='POST' && check_bitrix_sessid())
{
	$data = $entity->getRequestData();
	$fieldsMap = $entityClass::getMap();

	foreach ($fieldsMap as $name => $opt) {
		if (empty($opt['save'])) {
			continue;
		}

		if (isset($opt['field_type'])
			&& $opt['field_type'] === 'file'
		) {
			/*if(is_array($HTTP_POST_FILES[$name]['name'])) {
				// Множественные файлы
				$data[$name] = array();
				foreach($HTTP_POST_FILES[$name]['name'] as $key => $value) {
					$old_id = $row[$name.'_old_id'][$key];
					$data[$name][$key] = array(
						'name' => $HTTP_POST_FILES[$name]['name'][$key],
						'type' => $HTTP_POST_FILES[$name]['type'][$key],
						'tmp_name' => $HTTP_POST_FILES[$name]['tmp_name'][$key],
						'error' => $HTTP_POST_FILES[$name]['error'][$key],
						'size' => $HTTP_POST_FILES[$name]['size'][$key],
						'del' => isset($_REQUEST[$name.'_del'])
								 && is_array($_REQUEST[$name.'_del'])
								 &&	(in_array($old_id, $_REQUEST[$name.'_del']) ||
									(array_key_exists($key, $_REQUEST[$name.'_del']) &&
									   $_REQUEST[$name.'_del'][$key] == 'Y')),
						'old_id' => $old_id
					);
				}
			} else {*/
				$data[$name] = isset($HTTP_POST_FILES[$name])
							   ? $HTTP_POST_FILES[$name]
							   : array();
				$data[$name]['del'] = !empty($_REQUEST[$name . '_del']);
				$data[$name]['old_id'] = isset($row[$name]) ? $row[$name] : '';
			//}
		}

		if ($opt['data_type'] === 'boolean'
			&& !in_array($data[$name], $opt['values'])
		) {
			$data[$name] = current($opt['values']);
		}

		//Фикс для полей типа refCheckboxes. Удаляем значения, если не выбран ни один чекбокс
		if($opt['field_type'] === 'refCheckboxes' && !array_key_exists($name, $data)){
			$data[$name] = array();
		}
	}

	$USER_FIELD_MANAGER->EditFormAddFields('ESTATE_' . $estate['ID'], $data);
	$USER_FIELD_MANAGER->checkFields('ESTATE_' . $estate['ID'], null, $data);

	/** @param Bitrix\Main\Entity\AddResult $result */
	if ($isUpdateForm) {
		$ID = intval($_REQUEST['ID']);
		$result = $entityClass::update($ID, $data, $estate);
	} else {
		$result = $entityClass::add($data, $estate);
		$ID = $result->getId();
	}
	$entityClass::clearComponentsCache();

	if($result->isSuccess()) {
		if (strlen($save)>0) {
			LocalRedirect('estate_rows_list.php?ENTITY_ID=' . $estate['ID']
						. ($PARENT ? '&set_filter=Y&find_parent=' . $PARENT : '')
						. '&lang=' . LANGUAGE_ID
			);
		} else {
			LocalRedirect('estate_row_edit.php?ENTITY_ID=' . $estate['ID']
						. '&ID=' . intval($ID)
						. ($PARENT ? '&PARENT=' . $PARENT : '')
						. '&lang=' . LANGUAGE_ID
			);
		}
	} else {
		$errors = $result->getErrorMessages();
	}
} elseif ($isUpdateForm) {
	$userFieldManager = new \CUserTypeManager();
	$fields = $userFieldManager->GetUserFields('ESTATE_' . $estate['ID']);

	// fill form default values
	foreach ($row as $k => $v) {
		if ($k === 'PARENT') continue;
		$GLOBALS[$k] = $v;

		// special for file
		if ($fields[$k]['USER_TYPE']['BASE_TYPE']=='file') {
			$GLOBALS[$k . '_old_id'] = $v;
		}
	}
}


// menu
$aMenu = array(
	array(
		'TEXT'	=> GetMessage('ESTATE_ADMIN_ROWS_RETURN_TO_LIST_BUTTON'),
		'TITLE'	=> GetMessage('ESTATE_ADMIN_ROWS_RETURN_TO_LIST_BUTTON'),
		'LINK'	=> 'estate_rows_list.php?ENTITY_ID=' . $estate['ID']
				 . ($PARENT ? '&set_filter=Y&find_parent=' . $PARENT : '')
				 . '&lang=' . LANGUAGE_ID,
		'ICON'	=> 'btn_list',
	)
);

$context = new CAdminContextMenu($aMenu);


//view

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/' . $prolog);

$context->Show();


if (!empty($errors)) {
	CAdminMessage::ShowMessage(join("\n", $errors));
}

$tabControl->BeginPrologContent();

if(method_exists($USER_FIELD_MANAGER, 'showscript')) {
	echo $USER_FIELD_MANAGER->ShowScript();
}

echo CAdminCalendar::ShowScript();

$tabControl->EndPrologContent();
$tabControl->BeginEpilogContent();
?>

	<?= bitrix_sessid_post() ?>
	<input type="hidden" name="ID" value="<?= htmlspecialcharsbx($row['ID']) ?>">
	<input type="hidden" name="ENTITY_ID" value="<?= htmlspecialcharsbx($estate['ID']) ?>">
	<input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">

	<?php $tabControl->EndEpilogContent(); ?>

	<?php $tabControl->Begin(array(
		'FORM_ACTION' => $APPLICATION->GetCurPage() . '?ENTITY_ID=' . $estate['ID']
					  . '&ID=' . IntVal($ID)
					  . ($PARENT ? '&PARENT=' . $PARENT : '')
					  . '&lang=' . LANG
	));?>

	<?php $tabControl->BeginNextFormTab(); ?>

	<?php $tabControl->AddViewField('ID', 'ID', $row['ID']); ?>

	<?php $entity->setFormFields($tabControl, $row); ?>

	<?= $tabControl->ShowUserFields('ESTATE_' . $estate['ID'], $ID, true); ?>

	<?php $ufields = $USER_FIELD_MANAGER->GetUserFields('ESTATE_' . $estate['ID'], $ID, LANGUAGE_ID); ?>

	<?php
	$tabControl->Buttons(array(
		'disabled' => $isEditMode ? false : true,
		'back_url' => 'estate_rows_list.php?ENTITY_ID=' . intval($estate['ID']) . '&lang=' . LANGUAGE_ID
	));
	$tabControl->Show();
	?>
</form>
<?php

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/' . $epilog);
