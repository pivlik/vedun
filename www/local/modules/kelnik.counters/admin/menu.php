<?php

IncludeModuleLangFile(__FILE__);

if ($USER->IsAdmin()) {
    $items = array();

    CModule::IncludeModule('kelnik.counters');


    return array(
        'parent_menu' => 'global_menu_marketing',
        "section" => "kelnik_counters",
        'sort' => 990,
        'text' => GetMessage('KELNIK_COUNTERS_ADMIN_MENU_TITLE'),
        'url' => 'kelnik_counters.php?lang=' . LANG,
        'icon' => 'iblock_menu_icon_types',
        'page_icon' => 'iblock_page_icon_elements',
//        'more_url' => array(
//            'estate_entity_edit.php',
//            'estate_rows_list.php',
//            'estate_row_edit.php'
//        ),
        'items_id' => 'menu_kelnik_counters',
//        'items' => $items
    );
} else {
    return false;
}
