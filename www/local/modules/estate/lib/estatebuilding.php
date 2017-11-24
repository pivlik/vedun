<?php
/**
 * Bitrix Framework
 *
 * @package    bitrix
 * @subpackage estate
 */

namespace Bitrix\Estate;

/**
 * Class description
 *
 * @package    estate
 * @subpackage estateobject
 */
class EstateBuildingTable extends BaseEstate
{
    public static $_instance = null;

    protected $_tableName = 'estate_building';

    protected $_parentEntityId = self::ESTATE_ENTITY_OBJECT;
    protected $_childEntityId = array(
        self::ESTATE_ENTITY_SECTION,
        self::ESTATE_ENTITY_FLOOR
    );

    protected $_fieldsMap = array(
        'ID' => array(
            'data_type' => 'integer',
            'primary' => true,
            'autocomplete' => true,
        ),
        'ACTIVE' => array(
            'data_type' => 'boolean',
            'values' => array('N', 'Y'),
            'save' => true,
            'content_lang' => 'IBLOCK_FIELD_ACTIVE',
            'field_type' => 'checkbox',
        ),
        'NAME' => array(
            'data_type' => 'string',
            'required' => true,
            'save' => true,
            'content_lang' => 'IBLOCK_FIELD_NAME',
            'field_type' => 'text',
        ),
        // 'STAGE' => array(
        //     'data_type'  => 'string',
        //     'save'       => true,
        //     'expression' => array(
        //         '(SELECT 1)',
        //     ),
        // ),
        'DELIVERY' => array(
            'data_type' => 'string',
            'save' => true,
            'expression' => array(
                '(SELECT 1)',
            ),
        ),
        'MAX_FLOOR' => array(
            'data_type' => 'integer',
            'save' => true,
            'field_type' => 'text',
            'content' => 'Максимальный этаж',
        ),
        'IMAGE_IN_OBJECT' => array(
            'data_type' => 'string',
            'save' => true,
            'field_type' => 'file',
            'content' => 'Изображение на плане объекта',
        ),
        // 'LOCATION_IMAGE' => array(
        //     'data_type'  => 'string',
        //     'save'       => true,
        //     'field_type' => 'file',
        //     'content'    => 'Положение корпуса на генплане',
        // ),
        // 'VISUAL_LINK' => array(
        //     'data_type'  => 'string',
        //     'save'       => true,
        //     'content'    => 'Переход по ссылке в визуальном поиске',
        //     'field_type' => 'text',
        // ),
        // 'VISUAL_SKIP' => array(
        //     'data_type'  => 'boolean',
        //     'values'     => array('N','Y'),
        //     'save'       => true,
        //     'field_type' => 'checkbox',
        //     'content'    => 'Пропускать в выборщике',
        // ),
        'NAV_COORD' => array(
            'data_type' => 'string',
            'save' => true,
            'content' => 'Координаты для навигатора',
            'field_type' => 'textarea',
        ),
        'NAV_IMAGE' => array(
            'data_type' => 'string',
            'save' => true,
            'field_type' => 'file',
            'content' => 'Изображение для навигатора',
        ),
        'NAV_COORD_ALT' => array(
            'data_type' => 'string',
            'save' => true,
            'content' => 'Координаты для навигатора альтернативные',
            'field_type' => 'textarea',
        ),
        'NAV_IMAGE_ALT' => array(
            'data_type' => 'string',
            'save' => true,
            'field_type' => 'file',
            'content' => 'Изображение для навигатора альтернативное',
        ),
        'COMPASS_IMAGE' => array(
            'data_type'  => 'string',
            'save'       => true,
            'field_type' => 'file',
            'content'    => 'Изображение компаса',
        ),
        'POINTER_NAME' => array(
            'data_type' => 'string',
            'save' => true,
            'content' => 'Текст указателя',
            'field_type' => 'text',
        ),
        'POINTER_POSITION' => array(
            'data_type' => 'string',
            'save' => true,
            'content' => 'Положение указателя',
            'field_type' => 'text',
        ),
        'POINTER_POSITION_ALT' => array(
            'data_type' => 'string',
            'save' => true,
            'content' => 'Положение указателя альтернативное',
            'field_type' => 'text',
        ),
        'FLY_VIDEO_MP4' => array(
            'data_type' => 'string',
            'save' => true,
            'field_type' => 'file',
            'content' => 'Видео подлета MP4',
        ),
        'FLY_VIDEO_WEBM' => array(
            'data_type' => 'string',
            'save' => true,
            'field_type' => 'file',
            'content' => 'Видео подлета WEBM',
        ),
        'FLY_VIDEO_OGG' => array(
            'data_type' => 'string',
            'save' => true,
            'field_type' => 'file',
            'content' => 'Видео подлета OGG',
        ),
        'FLY_VIDEO_MP4_ALT' => array(
            'data_type' => 'string',
            'save' => true,
            'field_type' => 'file',
            'content' => 'Видео подлета MP4 альтернативное',
        ),
        'FLY_VIDEO_WEBM_ALT' => array(
            'data_type' => 'string',
            'save' => true,
            'field_type' => 'file',
            'content' => 'Видео подлета WEBM альтернативное',
        ),
        'FLY_VIDEO_OGG_ALT' => array(
            'data_type' => 'string',
            'save' => true,
            'field_type' => 'file',
            'content' => 'Видео подлета OGG альтернативное',
        ),
        'POS_VIDEO_MP4' => array(
            'data_type' => 'string',
            'save' => true,
            'field_type' => 'file',
            'content' => 'Видео смены ракурса MP4',
        ),
        'POS_VIDEO_WEBM' => array(
            'data_type' => 'string',
            'save' => true,
            'field_type' => 'file',
            'content' => 'Видео смены ракурса WEBM',
        ),
        'POS_VIDEO_OGG' => array(
            'data_type' => 'string',
            'save' => true,
            'field_type' => 'file',
            'content' => 'Видео смены ракурса OGG',
        ),
        'POS_VIDEO_MP4_ALT' => array(
            'data_type' => 'string',
            'save' => true,
            'field_type' => 'file',
            'content' => 'Видео смены ракурса MP4 альтернативное',
        ),
        'POS_VIDEO_WEBM_ALT' => array(
            'data_type' => 'string',
            'save' => true,
            'field_type' => 'file',
            'content' => 'Видео смены ракурса WEBM альтернативное',
        ),
        'POS_VIDEO_OGG_ALT' => array(
            'data_type' => 'string',
            'save' => true,
            'field_type' => 'file',
            'content' => 'Видео смены ракурса OGG альтернативное',
        ),
        'IMPORT_ID' => array(
            'data_type' => 'string',
            'save' => true,
            'content' => 'ID выгрузки',
            'field_type' => 'text',
        ),
        'PARENT' => array(
            'data_type' => 'integer',
            'required' => true,
            'save' => true,
            'content_lang' => 'ESTATE_ADMIN_ROW_PARENT_OBJECT_FIELD',
            'field_type' => 'refSelect',
            'ref_class' => '\Bitrix\Estate\EstateObjectTable',
        ),
        // 'STAGE_NAME' => array(
        //     'data_type'  => 'string',
        //     'save'       => true,
        //     'expression' => array(
        //         '(SELECT NAME FROM estateref_stages WHERE ID = %s)',
        //         'STAGE',
        //     ),
        // ),
        'LINK_SECTIONS' => array(
            'data_type' => 'string',
            'save' => true,
            'content' => 'Связанные корпуса',
            'field_type' => 'refSelectMulti',
            'ref_class' => '\Bitrix\Estate\EstateBuildingTable',
            'filter' => array(
                'PARENT' => array(
                    'PARENT'
                ),
                '!ID' => array(
                    'ID'
                )
            ),
        ),
        'BUILDING' => array(
            'data_type' => 'string',
            'save' => true,
            'content' => 'Корпус (инфоблок)',
            'field_type' => 'RefBuildingWithObjects',
            'iblock' => 10,
        ),
        'PARENT_OBJECT' => array(
            'data_type' => 'integer',
            'save' => true,
            'expression' => array(
                '(SELECT OBJECT FROM estate_object WHERE ID = %s)',
                'PARENT',
            ),
        ),
        'PARENT_NAME' => array(
            'data_type' => 'string',
            'save' => true,
            'expression' => array(
                '(SELECT NAME FROM estate_object WHERE ID = %s)',
                'PARENT',
            ),
        ),
        'NAME_WITH_PARENT' => array(
            'data_type' => 'integer',
            'expression' => array(
                '(SELECT CONCAT(estate_object.NAME, " - ", %s)
                    FROM estate_object WHERE ID = %s
                )',
                'NAME', 'PARENT'
            ),
        ),
        'CHILD_3_COUNT' => array(
            'data_type' => 'integer',
            'expression' => array(
                '(SELECT COUNT(ID) FROM estate_section WHERE PARENT = %s)',
                'ID',
            ),
        ),
        'CHILD_4_COUNT' => array(
            'data_type' => 'integer',
            'expression' => array(
                '(SELECT COUNT(ID) FROM estate_floor WHERE PARENT = %s)',
                'ID',
            ),
        ),
    );

    protected $_headers = array(
        array(
            'id' => 'ID',
            'content' => 'ID',
            'sort' => 'ID',
        ),
        array(
            'id' => 'NAME',
            'content_lang' => 'ESTATE_ADMIN_ROWS_LIST_NAME',
            'sort' => 'NAME',
        ),
//         array(
//             'id'      => 'STAGE_NAME',
//             'content' => 'Очередь',
//             'sort'    => 'STAGE_NAME',
//         ),
        array(
            'id' => 'ACTIVE',
            'content_lang' => 'ESTATE_ADMIN_ROWS_LIST_ACTIVE',
            'sort' => 'ACTIVE',
        ),
        array(
            'id' => 'PARENT_NAME',
            'content_lang' => 'ESTATE_ADMIN_ROW_PARENT_OBJECT_FIELD',
            'sort' => 'PARENT_NAME',
            'filter' => 'PARENT',
        ),
        array(
            'id' => 'CHILD_3_COUNT',
            'content_lang' => 'ESTATE_ADMIN_ROW_CHILD_SECTIONS_FIELD',
            'sort' => 'CHILD_3_COUNT',
        ),
        array(
            'id' => 'CHILD_4_COUNT',
            'content_lang' => 'ESTATE_ADMIN_ROW_CHILD_FLOORS_FIELD',
            'sort' => 'CHILD_4_COUNT',
        ),
    );

    protected $_filters = array(
        'ID' => array(
            'param' => 'find_id',
            'name' => 'ID',
        ),
        '%NAME' => array(
            'param' => 'find_name',
            'name_lang' => 'ESTATE_ADMIN_ROWS_LIST_NAME',
        ),
        // 'STAGE' => array(
        //     'param' => 'find_stage',
        //     'name'  => 'ID очереди',
        // ),
        'PARENT' => array(
            'param' => 'find_parent',
            'name_lang' => 'ESTATE_ADMIN_ROW_PARENT_OBJECT_ID_FIELD',
        ),
        'ACTIVE' => array(
            'param' => 'find_active',
            'name_lang' => 'ESTATE_ADMIN_ROWS_LIST_ACTIVE',
            'variants' => array(
                'Y' => 'ESTATE_ADMIN_ROWS_LIST_ACTIVE_YES',
                'N' => 'ESTATE_ADMIN_ROWS_LIST_ACTIVE_NO',
            ),
        ),
    );


    public function getJson(array $object, array $activeBuildings, array $floors = array())
    {
        $json = BaseEstate::getDefaultJson();

        $navImage = \CFile::GetFileArray($object['NAV_IMAGE']);
        $navImageAlt = \CFile::GetFileArray($object['NAV_IMAGE_ALT']);
        $json['canvasImg'] = array(
            'def' => $navImage['SRC'],
            'alt' => $navImageAlt['SRC'],
        );

        $json['viewName'] = 'Выберите корпус';

        $json['videoChangePosition'] = array(
            'def' => array(
                'mp4' => \CFile::GetPath($object['POS_VIDEO_MP4']),
                'webm' => \CFile::GetPath($object['POS_VIDEO_WEBM']),
                'ogg' => \CFile::GetPath($object['POS_VIDEO_OGG'])
            ),
            'alt' => array(
                'mp4' => \CFile::GetPath($object['POS_VIDEO_MP4_ALT']),
                'webm' => \CFile::GetPath($object['POS_VIDEO_WEBM_ALT']),
                'ogg' => \CFile::GetPath($object['POS_VIDEO_OGG_ALT'])
            )
        );

        // Все корпуса объекта
        $buildings = self::getAssoc(
            array('filter' => array(
                '!NAV_COORD' => false,
                'PARENT' => $object['ID'],
            )),
            'ID'
        );

        // Панорамы
        $json['pano'] = EstateObjectPanoTable::getForJson($object['ID']);

        // Количество квартир в корпусах
        $activeMap = BaseEstate::getActiveElementsMap();
        foreach ($floors as $ID => $floor) {
            $buildingId = $activeMap['FLOORS'][$ID]['PARENT'];
            // $buildingId = $activeMap['SECTIONS'][$sectionId]['PARENT'];
            if (!isset($buildings[$buildingId]['CNT'])) {
                $buildings[$buildingId]['CNT'] = 0;
                // $buildings[$buildingId]['SECTIONS'] = array();
            }
            $buildings[$buildingId]['CNT'] += $floor['CNT'];
            // $buildings[$buildingId]['SECTIONS'][] = $sectionId;
        }

        $json['elements'] = array('def' => array(), 'alt' => array());
        foreach ($buildings as $item) {
            $link = 'building/' . $item['ID'] . '/';
            $status = (int)in_array($item['ID'], $activeBuildings);
            $toPage = !empty($item['VISUAL_LINK']) ? $item['VISUAL_LINK'] : false;
            $toStep = false;
            if ($status && !$toPage && $item['VISUAL_SKIP'] === 'Y') {
                $link = EstateSectionTable::getSectionLink($item['ID'], $floors);
            }
            $plural = plural(
                $item['CNT'],
                array(
                    'квартира',
                    'квартиры',
                    'квартир',
                )
            );
            if ($status) {
                $json['elements']['def'][] = array(
                    'id' => $item['ID'],
                    'coords' => $item['NAV_COORD'],
                    'link' => $link,
                    'status' => $status,
                    'pointer' => array(
                        'position' => $item['POINTER_POSITION'],
                        'header' => $item['NAME'] . ' корпус',
                        'content' => ($item['CNT']) ? $item['CNT'] . ' ' . $plural : '',
                    ),
                    'toPage' => $toPage,
                );
                $json['elements']['alt'][] = array(
                    'id' => $item['ID'],
                    'coords' => $item['NAV_COORD_ALT'],
                    'link' => $link,
                    'status' => $status,
                    'pointer' => array(
                        'position' => $item['POINTER_POSITION_ALT'],
                        'header' => $item['NAME'] . ' корпус',
                        'content' => ($item['CNT']) ? $item['CNT'] . ' ' . $plural : '',
                    ),
                    'toPage' => $toPage,
                );
            }

        }
        return $json;
    }
}
