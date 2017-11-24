<?php
define('STOP_STATISTICS', true);
define('NO_AGENT_CHECK', true);
define('DisableEventsCheck', true);
define('BX_SECURITY_SHOW_MESSAGE', true);
define("PUBLIC_AJAX_MODE", true);
define("NOT_CHECK_PERMISSIONS", true);

use Bitrix\Main\Loader;
//use Bitrix\Main\Localization\Loc;
use Bitrix\Estate as Estate;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
header('Content-Type: application/json; charset='.LANG_CHARSET);

IncludeModuleLangFile(__DIR__.'/estate_rows_list.php');
IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/iblock/lang/' . LANG. '/lib/iblock.php');

$json = array('ret' => 0, 'message' => 'Произошла ошибка');
if (!$USER->IsAuthorized() || !check_bitrix_sessid() || empty($_REQUEST['table'])) {
    echo json_encode($json);
    exit;
}

if (!Loader::includeModule('estate')) {
    $json['message'] = 'Не удалось подключить модуль недвижимости';
    echo json_encode($json);
    exit;
}

$entity = Estate\EstateTable::getRow(array(
    'filter' => array(
        'TABLE_NAME' => $_REQUEST['table']
    ),
));
if (!$entity) {
    $json['message'] = 'Не найден модуль с таблицей ' . $_REQUEST['table'];
    echo json_encode($json);
    exit;
}

$className = 'Bitrix\\Estate\\' . $entity['CLASS_NAME'];
$map = $className::getMap();
$fields = array();
$json['map'] = $map;
foreach ($map as $name => $opt) {
    if (empty($opt['save']) && empty($opt['field_type'])) {
        continue;
    }

    if (isset($opt['content_lang'])) {
        $opt['content'] = GetMessage($opt['content_lang']);
    }
    $fields[$name] = $opt['content'];
}
$json['message'] = '';
$json['ret'] = 1;
$json['fields'] = $fields;

echo json_encode($json);
