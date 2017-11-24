<?php

IncludeModuleLangFile(__FILE__);

if ($USER->IsAdmin()) {
    $items = array();

    if(!CModule::IncludeModule('estate')){
        return;
    }

    $groupsKeys = array();
    $r = \Bitrix\Estate\EstateTable::getList(array('order' => array('SORT', 'ID'), 'filter' => array('ID' => array(1, 2, 3, 4, 5, 9, 13, 16))));
    $i = 0;
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

    return array(
        'parent_menu' => 'global_menu_content',
        "section" => "estate",
        'sort' => 290,
        'text' => GetMessage('ESTATE_ADMIN_MENU_TITLE'),
        'url' => 'estate_index.php?lang=' . LANG,
        'icon' => 'iblock_menu_icon_types',
        'page_icon' => 'iblock_page_icon_elements',
        'more_url' => array(
            'estate_entity_edit.php',
            'estate_rows_list.php',
            'estate_row_edit.php'
        ),
        'items_id' => 'menu_estate',
        'items' => $items
    );
} else {
    return false;
}
