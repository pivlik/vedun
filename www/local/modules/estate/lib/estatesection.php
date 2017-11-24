<?php
/**
 * Bitrix Framework
 * @package    bitrix
 * @subpackage estate
 */

namespace Bitrix\Estate;

/**
 * Class description
 * @package    estate
 * @subpackage estatesection
 */
class EstateSectionTable extends BaseEstate
{
    public static $_instance = null;

    protected $_tableName = 'estate_section';

    protected $_parentEntityId = self::ESTATE_ENTITY_BUILDING;
    protected $_childEntityId = self::ESTATE_ENTITY_FLOOR;

    protected $_fieldsMap = array(
        'ID' => array(
            'data_type'    => 'integer',
            'primary'      => true,
            'autocomplete' => true,
        ),
        'ACTIVE' => array(
            'data_type'    => 'boolean',
            'values'       => array('N','Y'),
            'save'         => true,
            'field_type'   => 'checkbox',
            'content_lang' => 'IBLOCK_FIELD_ACTIVE',
        ),
        'NAME' => array(
            'data_type'    => 'string',
            'required'     => true,
            'save'         => true,
            'field_type'   => 'text',
            'content_lang' => 'IBLOCK_FIELD_NAME',
        ),
        'MAX_FLOOR' => array(
            'data_type'  => 'integer',
            'save'       => true,
            'field_type' => 'text',
            'content'    => 'Максимальный этаж',
        ),
         'VISUAL_LINK' => array(
             'data_type'  => 'string',
             'save'       => true,
             'content'    => 'Переход по ссылке в визуальном поиске',
             'field_type' => 'text',
         ),
         'VISUAL_SKIP' => array(
             'data_type'  => 'boolean',
             'values'     => array('N','Y'),
             'save'       => true,
             'field_type' => 'checkbox',
             'content'    => 'Пропускать в выборщике',
         ),
         'LOCATION_IMAGE' => array(
             'data_type'  => 'string',
             'save'       => true,
             'field_type' => 'file',
             'content'    => 'Положение секции на плане корпуса',
         ),
         'NAV_COORD' => array(
             'data_type'  => 'string',
             'save'       => true,
             'field_type' => 'textarea',
             'content'    => 'Координаты для навигатора',
         ),
         'NAV_IMAGE' => array(
             'data_type'  => 'string',
             'save'       => true,
             'field_type' => 'file',
             'content'    => 'Изображение для навигатора',
         ),
         'NAV_COORD_ALT' => array(
             'data_type'  => 'string',
             'save'       => true,
             'field_type' => 'textarea',
             'content'    => 'Координаты для навигатора альтернативные',
         ),
         'NAV_IMAGE_ALT' => array(
             'data_type'  => 'string',
             'save'       => true,
             'field_type' => 'file',
             'content'    => 'Изображение для навигатора альтернативное',
         ),
        'COMPASS_IMAGE' => array(
            'data_type'  => 'string',
            'save'       => true,
            'field_type' => 'file',
            'content'    => 'Изображение компаса',
        ),
         'POINTER_NAME' => array(
             'data_type'  => 'string',
             'save'       => true,
             'content'    => 'Текст указателя',
             'field_type' => 'text',
         ),
         'POINTER_POSITION' => array(
             'data_type'  => 'string',
             'save'       => true,
             'content'    => 'Положение указателя',
             'field_type' => 'text',
         ),
         'POINTER_POSITION_ALT' => array(
             'data_type'  => 'string',
             'save'       => true,
             'content'    => 'Положение указателя альтернативное',
             'field_type' => 'text',
         ),
         'FLY_VIDEO_MP4' => array(
             'data_type'  => 'string',
             'save'       => true,
             'field_type' => 'file',
             'content'    => 'Видео подлета MP4',
         ),
         'FLY_VIDEO_WEBM' => array(
             'data_type'  => 'string',
             'save'       => true,
             'field_type' => 'file',
             'content'    => 'Видео подлета WEBM',
         ),
         'FLY_VIDEO_OGG' => array(
             'data_type'  => 'string',
             'save'       => true,
             'field_type' => 'file',
             'content'    => 'Видео подлета OGG',
         ),
         'FLY_VIDEO_MP4_ALT' => array(
             'data_type'  => 'string',
             'save'       => true,
             'field_type' => 'file',
             'content'    => 'Видео подлета MP4 альтернативное',
         ),
         'FLY_VIDEO_WEBM_ALT' => array(
             'data_type'  => 'string',
             'save'       => true,
             'field_type' => 'file',
             'content'    => 'Видео подлета WEBM альтернативное',
         ),
         'FLY_VIDEO_OGG_ALT' => array(
             'data_type'  => 'string',
             'save'       => true,
             'field_type' => 'file',
             'content'    => 'Видео подлета OGG альтернативное',
         ),
         'POS_VIDEO_MP4' => array(
             'data_type'  => 'string',
             'save'       => true,
             'field_type' => 'file',
             'content'    => 'Видео смены ракурса MP4',
         ),
         'POS_VIDEO_WEBM' => array(
             'data_type'  => 'string',
             'save'       => true,
             'field_type' => 'file',
             'content'    => 'Видео смены ракурса WEBM',
         ),
         'POS_VIDEO_OGG' => array(
             'data_type'  => 'string',
             'save'       => true,
             'field_type' => 'file',
             'content'    => 'Видео смены ракурса OGG',
         ),
         'POS_VIDEO_MP4_ALT' => array(
             'data_type'  => 'string',
             'save'       => true,
             'field_type' => 'file',
             'content'    => 'Видео смены ракурса MP4 альтернативное',
         ),
         'POS_VIDEO_WEBM_ALT' => array(
             'data_type'  => 'string',
             'save'       => true,
             'field_type' => 'file',
             'content'    => 'Видео смены ракурса WEBM альтернативное',
         ),
         'POS_VIDEO_OGG_ALT' => array(
             'data_type'  => 'string',
             'save'       => true,
             'field_type' => 'file',
             'content'    => 'Видео смены ракурса OGG альтернативное',
         ),
//		'IMAGE_ON_BUILDING' => array(
//			'data_type'  => 'string',
//			'save'       => true,
//			'field_type' => 'file',
//			'content'    => 'Изображение в корпусе',
//		),
        'IMPORT_ID' => array(
            'data_type'  => 'string',
            'save'       => true,
            'content'    => 'ID выгрузки',
            'field_type' => 'text',
        ),
         'LINK_SECTIONS' => array(
             'data_type'      => 'string',
             'save'           => true,
             'content'        => 'Связанные секции',
             'field_type'     => 'refSelectMulti',
             'ref_class'      => '\Bitrix\Estate\EstateSectionTable',
             'filter'         => array(
                 'PARENT' => array(
                     'PARENT'
                 ),
                 '!ID' => array(
                     'ID'
                 )
             ),
         ),
        'PARENT' => array(
            'data_type'    => 'integer',
            'required'     => true,
            'save'         => true,
            'field_type'   => 'refSelect',
            'content_lang' => 'ESTATE_ADMIN_ROW_PARENT_BUILDING_FIELD',
            'ref_class'    => '\Bitrix\Estate\EstateBuildingTable',
            'value_field'  => 'NAME_WITH_PARENT',
        ),
        'PARENT_NAME' => array(
            'data_type'  => 'integer',
            'expression' => array(
                '(SELECT CONCAT(estate_object.NAME, " - ", estate_building.NAME)
                    FROM estate_building
                    LEFT JOIN estate_object ON estate_object.ID = estate_building.PARENT
                    WHERE estate_building.ID = %s
                )',
                'PARENT',
            ),
        ),
        'NAME_WITH_PARENT' => array(
            'data_type'  => 'integer',
            'expression' => array(
                '(SELECT CONCAT(estate_object.NAME, " - ", estate_building.NAME, " - ", %s)
                    FROM estate_building
                    LEFT JOIN estate_object ON estate_object.ID = estate_building.PARENT
                    WHERE estate_building.ID = %s
                )',
                'NAME', 'PARENT'
            ),
        ),
        'CHILD_4_COUNT' => array(
            'data_type'  => 'integer',
            'expression' => array(
                '(SELECT COUNT(ID) FROM estate_floor WHERE PARENT_SECTION = %s)',
                'ID',
            ),
        ),
        // 'CHILD_13_COUNT' => array(
        //     'data_type'  => 'integer',
        //     'expression' => array(
        //         '(SELECT COUNT(ID) FROM estate_premise WHERE PARENT = %s)',
        //         'ID',
        //     ),
        // ),
    );

    protected $_headers = array(
        array(
            'id'      => 'ID',
            'content' => 'ID',
            'sort'    => 'ID',
        ),
        array(
            'id'           => 'NAME',
            'content_lang' => 'ESTATE_ADMIN_ROWS_LIST_NAME',
            'sort'         => 'NAME',
        ),
        array(
            'id'           => 'ACTIVE',
            'content_lang' => 'ESTATE_ADMIN_ROWS_LIST_ACTIVE',
            'sort'         => 'ACTIVE',
        ),
        array(
            'id'           => 'PARENT_NAME',
            'content_lang' => 'ESTATE_ADMIN_ROW_PARENT_BUILDING_FIELD',
            'sort'         => 'PARENT_NAME',
            'filter'       => 'PARENT',
        ),
        array(
            'id'           => 'CHILD_4_COUNT',
            'content_lang' => 'ESTATE_ADMIN_ROW_CHILD_FLOORS_FIELD',
            'sort'         => 'CHILD_4_COUNT',
        ),
        // array(
        //     'id'           => 'CHILD_13_COUNT',
        //     'content_lang' => 'ESTATE_ADMIN_ROW_CHILD_PREMISES_FIELD',
        //     'sort'         => 'CHILD_13_COUNT',
        // ),
    );

    protected $_filters = array(
        'ID'     => array(
            'param' => 'find_id',
            'name'  => 'ID'
        ),
        '%NAME'  => array(
            'param'     => 'find_name',
            'name_lang' => 'ESTATE_ADMIN_ROWS_LIST_NAME',
        ),
        'PARENT' => array(
            'param'     => 'find_parent',
            'name_lang' => 'ESTATE_ADMIN_ROW_PARENT_BUILDING_ID_FIELD',
        ),
        'ACTIVE' => array(
            'param'     => 'find_active',
            'name_lang' => 'ESTATE_ADMIN_ROWS_LIST_ACTIVE',
            'variants'  => array(
                'Y' => 'ESTATE_ADMIN_ROWS_LIST_ACTIVE_YES',
                'N' => 'ESTATE_ADMIN_ROWS_LIST_ACTIVE_NO',
            ),
        ),
    );

    public function getJson(array $building, array $sections, array $floors)
    {
        $json = BaseEstate::getDefaultJson();

        $navImage = \CFile::GetFileArray($building['NAV_IMAGE']);
        $navImageAlt = \CFile::GetFileArray($building['NAV_IMAGE_ALT']);
        $json['canvasImg'] = array(
            'def' => $navImage['SRC'],
            'alt' => $navImageAlt['SRC'],
        );

        if ($building['VISUAL_SKIP'] === 'Y') {
            $section = current($sections);
            $json['toStep'] = self::getSectionLink($section['PARENT'], $floors);
            return $json;
        }

        $json['viewName'] = 'Выберите секцию';
        $back = BaseEstate::getBackLink();
        $json['backText'] = $back['text'];
        $json['backLink'] = $back['link'];

        $json['videoChangePosition'] = array(
            'def' => array(
                'mp4'  => \CFile::GetPath($building['POS_VIDEO_MP4']),
                'webm' => \CFile::GetPath($building['POS_VIDEO_WEBM']),
                'ogg'  => \CFile::GetPath($building['POS_VIDEO_OGG']),
            ),
            'alt' => array(
                'mp4'  => \CFile::GetPath($building['POS_VIDEO_MP4_ALT']),
                'webm' => \CFile::GetPath($building['POS_VIDEO_WEBM_ALT']),
                'ogg'  => \CFile::GetPath($building['POS_VIDEO_OGG_ALT']),
            )
        );
        $json['videoFly'] = array(
            'def' => array(
                'mp4'  => \CFile::GetPath($building['FLY_VIDEO_MP4']),
                'webm' => \CFile::GetPath($building['FLY_VIDEO_WEBM']),
                'ogg'  => \CFile::GetPath($building['FLY_VIDEO_OGG']),
            ),
            'alt' => array(
                'mp4'  => \CFile::GetPath($building['FLY_VIDEO_MP4_ALT']),
                'webm' => \CFile::GetPath($building['FLY_VIDEO_WEBM_ALT']),
                'ogg'  => \CFile::GetPath($building['FLY_VIDEO_OGG_ALT']),
            )
        );

        // Количество квартир в секциях
        foreach ($floors as $ID => $floor) {
            $sectionId = $floor['PARENT'];
            if (!isset($sections[$sectionId]['CNT'])) {
                $sections[$sectionId]['CNT'] = 0;
            }
            $sections[$sectionId]['CNT'] += $floor['CNT'];
        }

        $startPath = BaseEstate::getStartPath();

        $json['elements'] = array('def' => array(), 'alt' => array());
        foreach ($sections as $item) {
            $link = self::_getLink($item['PARENT'], $item['ID']);
            $link = str_replace($startPath, '', $link);
            $plural = plural($item['CNT'], array('квартира', 'квартиры', 'квартир'));
            $toPage = !empty($item['VISUAL_LINK']) ? $item['VISUAL_LINK'] : false;
            if (!$toPage && $item['VISUAL_SKIP'] === 'Y') {
                $link = EstateFloorTable::getFloorLink($item, $floors);
            }
            $json['elements']['def'][] = array(
                'id'      => $item['ID'],
                'coords'  => $item['NAV_COORD'],
                'link'    => $link,
                'tooltip' => array(
                    'header'  => $item['NAME'],
                    'content' => 'Свободно ' . $item['CNT'] . ' ' . $plural,
                ),
                'toPage'  => $toPage,
            );
            $json['elements']['alt'][] = array(
                'id'      => $item['ID'],
                'coords'  => $item['NAV_COORD_ALT'],
                'link'    => $link,
                'tooltip' => array(
                    'header'  => $item['NAME'],
                    'content' => 'Свободно ' . $item['CNT'] . ' ' . $plural,
                ),
                'toPage'  => $toPage,
            );
        }
        return $json;
    }

    public static function getSectionLink($buildingId, $floors) {
        $sectionIds = BaseEstate::getParentsFromResult($floors);
        $section = self::getRow(array(
            'filter' => array(
                'ID'     => $sectionIds,
                'PARENT' => $buildingId,
            ),
            'limit' => 1
        ));
        $sectionId = $section['ID'];
        if ($section['VISUAL_SKIP'] !== 'Y') {
            return self::_getLink($buildingId, $sectionId);
        }
        return EstateFloorTable::getFloorLink($section, $floors);
    }

    protected static function _getLink($buildingId, $sectionId)
    {
        return 'building/' . $buildingId . '/section/' . $sectionId . '/';
    }
}
