<?php
define('STATIC_VER', 15);
define('RUBLE', 'p');

$env = getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production';
defined('APPLICATION_ENV') || define('APPLICATION_ENV', $env);

define('PATH_TO_404', '/404.php');


AddEventHandler("main", "OnBuildGlobalMenu", "OnBuildGlobalMenu");
function OnBuildGlobalMenu(&$aGlobalMenu, &$aModuleMenu)
{
    global $USER;
    if ($USER->IsAdmin()) {
        $items = array();

        if(!CModule::IncludeModule('estate')){
            return;
        }

        $groupsKeys = array();
        $r = \Bitrix\Estate\EstateTable::getList(array('order' => array('SORT', 'ID'), 'filter' => array('ID' => array(6, 7, 8, 10, 11, 12, 14, 15, 17))));
        $i = 10;
        while ($row = $r->fetch()) {
            $item = array(
                'text' => $row['NAME'],
                'url' => 'estate_rows_list.php?ENTITY_ID=' . $row['ID'] . '&lang=' . LANG,
                'module_id' => 'estate',
                'more_url' => Array(
                    'estate_rows_list.php?ENTITY_ID=' . $row['ID'],
                    'estate_row_edit.php?ENTITY_ID=' . $row['ID'],
                ),
            );

            if ($row['GROUP_NAME']) {
                if (!isset($groupsKeys[$row['GROUP_NAME']])) {
                    $group = array(
                        'text' => $row['GROUP_NAME'],
                        'module_id' => 'estate',
                        'items_id' => 'menu_estate_group_' . $row['GROUP_NAME'],
                        'dynamic' => true,
                        'more_url' => array(),
                        'url' => 'estate_rows_list.php?ENTITY_ID=' . $row['ID'] . '&lang=' . LANG,
                        'items' => array(),
                    );
                    $items[$i] = $group;
                    $groupsKeys[$row['GROUP_NAME']] = $i;
                }
                $key = $groupsKeys[$row['GROUP_NAME']];
                $items[$key]['items'][] = $item;
                $items[$key]['more_url'][] = 'estate_rows_list.php?ENTITY_ID=' . $row['ID'];
                $items[$key]['more_url'][] = 'estate_row_edit.php?ENTITY_ID=' . $row['ID'];
            } else {
                $items[$i] = $item;
            }
            ++$i;
        }

        $isExist = false;
        foreach ($aModuleMenu as &$arItem) {
            if ($arItem['items_id'] == 'menu_iblock_/references') {
                $isExist = true;
                $arItem['items'] = array_merge($arItem['items'], $items);
            }
        }
        //Добавляем раздел и пункты, если раздела нету
        if (!$isExist) {
            $Item[] = array(
                'text' => 'Справочники',
                'url' => 'iblock_admin.php?type=references&amp;lang=ru&amp;admin=N',
                'more_url' => array(
                    'iblock_admin.php?type=references&lang=ru&admin=N',
                ),
                'title' => 'Справочники',
                'parent_menu' => 'global_menu_content',
                'section' => 'iblock',
                'sort' => 230,
                'icon' => 'iblock_menu_icon_types',
                'page_icon' => 'iblock_page_icon_types',
                'module_id' => 'iblock',
                'items_id' => 'menu_iblock_/references',
                'dynamic' => 1,
                'items' => $items,
            );
            $aModuleMenu = array_merge($aModuleMenu, $Item);
        }

    }
}


AddEventHandler("main", "OnEpilog", "Redirect404");
function Redirect404()
{
    if (!defined('ADMIN_SECTION')
        && defined("ERROR_404")
        && defined("PATH_TO_404")
        && file_exists($_SERVER["DOCUMENT_ROOT"] . PATH_TO_404)
    ) {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        CHTTP::SetStatus("404 Not Found");
        include $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/header.php";
        include $_SERVER["DOCUMENT_ROOT"] . PATH_TO_404;
        include $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/footer.php";
    }
}

AddEventHandler("main", "OnBeforeEventAdd", "OnBeforeEventAddHandler");
function OnBeforeEventAddHandler(&$event, &$lid, $arFields)
{
    if ($event == "FAVORITES") {
        require_once($_SERVER["DOCUMENT_ROOT"]
            . "/local/php_interface/lib/mail_attach/mail_attach.php");
        SendAttache($event, $lid, $arFields, "/upload/pdf/{$arFields['FILE_NAME']}");
        $event = 'null';
        $lid = 'null';
    }
}

if (!function_exists(formatDelimeter)) {
    function formatDelimeter($number, $newDelimeter = ',')
    {
        return str_replace('.', $newDelimeter, $number);
    }
}

if (!function_exists(placehold)) {
    function placehold($width, $height, $text = false)
    {
        $url = 'http://placehold.it/' . $width . 'x' . $height;
        if ($text) {
            $url .= '&text=' . urlencode('no image');
        }
        return $url;
    }
}

if (!function_exists(resizeOrPlacehold)) {
    function resizeOrPlacehold($imageId, $width, $height, $text = false)
    {
        if (($image = \CFile::GetFileArray($imageId))
            && file_exists($_SERVER['DOCUMENT_ROOT'] . $image['SRC'])
        ) {
            $image = \CFile::ResizeImageGet(
                $image,
                array('width' => $width, 'height' => $height),
                BX_RESIZE_IMAGE_PROPORTIONAL,
                true
            );
        } else {
            $image = array('src' => placehold($width, $height, $text));
        }
        return $image['src'];
    }
}

if (!function_exists(mb_ucfirst)) {
    function mb_ucfirst($str, $enc = 'UTF-8')
    {
        $firstChar = mb_strtoupper(mb_substr($str, 0, 1, $enc), $enc);
        $length = mb_strlen($str, 'UTF-8');
        return $firstChar . mb_substr($str, 1, $length, $enc);
    }
}

if (!function_exists(price_format)) {
    function price_format($value)
    {
        return number_format($value, 0, '', ' ');
    }
}

if (!function_exists(plural)) {
    function plural($n, array $forms)
    {
        $i = (($n % 10 == 1 && $n % 100 != 11) ? 0 : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? 1 : 2));
        return isset($forms[$i]) ? $forms[$i] : '';
    }
}


if (!function_exists(getTotalFlatsOnSale)) {
    function getTotalFlatsOnSale()
    {
        if (!\Bitrix\Main\Loader::includeModule('estate')) {
            ShowError("ESTATE MODULE IS NOT INSTALLED");
            return false;
        }
        $filter = array(
            'STATUS' => 1,
        );
        $flatInstance = \Bitrix\Estate\EstateFlatTable::getInstance();
        $flatCount = $flatInstance->getResultCount($filter);
        return intval($flatCount);
    }
}