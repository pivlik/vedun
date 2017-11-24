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
 * @subpackage estatefloor
 */
class EstateFloorTable extends BaseEstate
{
    public static $_instance = null;

    protected $_tableName = 'estate_floor';

    protected $_parentEntityId = self::ESTATE_ENTITY_BUILDING;
    protected $_childEntityId = self::ESTATE_ENTITY_FLAT;

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
            'content_lang' => 'IBLOCK_FIELD_ACTIVE',
            'field_type'   => 'checkbox',
        ),
        'NAME' => array(
            'data_type'    => 'string',
            'required'     => true,
            'save'         => true,
            'content_lang' => 'IBLOCK_FIELD_NAME',
            'field_type'   => 'text',
        ),
        // 'VISUAL_LINK' => array(
        //     'data_type'  => 'string',
        //     'save'       => true,
        //     'content'    => 'Переход по ссылке в визуальном поиске',
        //     'field_type' => 'text',
        // ),
        'NAV_COORD' => array(
            'data_type'  => 'string',
            'save'       => true,
            'content'    => 'Координаты для навигатора',
            'field_type' => 'textarea',
        ),
        'NAV_COORD_ALT' => array(
            'data_type'  => 'string',
            'save'       => true,
            'content'    => 'Координаты для навигатора альтернативные',
            'field_type' => 'textarea',
        ),
        'NAV_IMAGE' => array(
            'data_type'  => 'string',
            'save'       => true,
            'field_type' => 'file',
            'content'    => 'Изображение для навигатора',
        ),
        'COMPASS_IMAGE' => array(
            'data_type'  => 'string',
            'save'       => true,
            'field_type' => 'file',
            'content'    => 'Изображение компаса',
        ),
        // 'NAV_IMAGE_ALT' => array(
        //     'data_type'  => 'string',
        //     'save'       => true,
        //     'field_type' => 'file',
        //     'content'    => 'Изображение для навигатора альтернативное',
        // ),
        'IMPORT_ID' => array(
            'data_type'  => 'string',
            'save'       => true,
            'content'    => 'ID выгрузки',
            'field_type' => 'text',
        ),
        'PARENT' => array(
            'data_type'    => 'integer',
            'required'     => true,
            'save'         => true,
            'content_lang' => 'ESTATE_ADMIN_ROW_PARENT_BUILDING_FIELD',
            'field_type'   => 'refSelect',
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
                '(SELECT CONCAT(estate_object.NAME, " - ", estate_building.NAME, " - этаж ", %s)
                    FROM estate_building
                    LEFT JOIN estate_object ON estate_object.ID = estate_building.PARENT
                    WHERE estate_building.ID = %s
                )',
                'NAME', 'PARENT'
            ),
        ),
        'PARENT_SECTION' => array(
            'data_type'    => 'integer',
            'required'     => true,
            'save'         => true,
            'content_lang' => 'ESTATE_ADMIN_ROW_PARENT_SECTION_FIELD',
            'field_type'   => 'refSelect',
            'ref_class'    => '\Bitrix\Estate\EstateSectionTable',
        ),
        'PARENT_SECTION_NAME' => array(
            'data_type'  => 'integer',
            'expression' => array(
                '(SELECT NAME FROM estate_section WHERE ID = %s)',
                'PARENT_SECTION',
            ),
        ),
        'NUMBER' => array(
            'data_type'  => 'integer',
            'expression' => array(
                '(SELECT NAME+0)'
            ),
        ),
        'NUMBER_FOR_FLATS' => array(
            'data_type'  => 'integer',
            'expression' => array(
                '(SELECT estate_estate_flat_ref_floor.NAME+0)'
            ),
        ),
        'CHILD_5_COUNT' => array(
            'data_type'  => 'integer',
            'expression' => array(
                '(SELECT COUNT(ID) FROM estate_flat WHERE PARENT = %s)',
                'ID',
            ),
        ),
        'ACTIVE_CHILD_COUNT' => array(
            'data_type'  => 'integer',
            'expression' => array(
                '(SELECT COUNT(ID) FROM estate_flat WHERE PARENT = %s AND STATUS = 1)',
                'ID',
            ),
        ),
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
            'id'           => 'PARENT_SECTION_NAME',
            'content_lang' => 'ESTATE_ADMIN_ROW_PARENT_SECTION_FIELD',
            'sort'         => 'PARENT_SECTION_NAME',
            'filter'       => 'PARENT_SECTION',
        ),
        array(
            'id'           => 'CHILD_5_COUNT',
            'content_lang' => 'ESTATE_ADMIN_ROW_CHILD_FLATS_FIELD',
            'sort'         => 'CHILD_5_COUNT',
        ),
    );

    protected $_filters = array(
        'ID'     => array(
            'param' => 'find_id',
            'name'  => 'ID',
        ),
        '%NAME'  => array(
            'param'     => 'find_name',
            'name_lang' => 'ESTATE_ADMIN_ROWS_LIST_NAME',
        ),
        'PARENT' => array(
            'param'     => 'find_parent',
            'name_lang' => 'ESTATE_ADMIN_ROW_PARENT_BUILDING_ID_FIELD',
        ),
        'PARENT_SECTION' => array(
            'param'     => 'find_parent_section',
            'name_lang' => 'ESTATE_ADMIN_ROW_PARENT_SECTION_ID_FIELD',
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

    public function getJson(array $building, array $floors)
    {
        $json = BaseEstate::GetDefaultJson();

        $navImage = \CFile::GetFileArray($building['NAV_IMAGE']);
        $navImageAlt = \CFile::GetFileArray($building['NAV_IMAGE_ALT']);
        $json['canvasImg'] = array(
            'def' => $navImage['SRC'],
            'alt' => $navImageAlt['SRC'],
        );

        if ($building['VISUAL_SKIP'] === 'Y') {
            $json['toStep'] = self::getFloorLink($building, $floors);
            return $json;
        }

        $json['viewName'] = 'Выберите этаж';
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

        $flatsInstance = EstateFlatTable::getInstance();
        // Этажи секции
        $res = self::getAssoc(
            array('filter' => array('ID' => array_keys($floors))),
            'ID'
        );
        foreach ($res as $ID => $floor) {
            $floors[$ID] = array_merge($floors[$ID], $floor);
        }

        $startPath = BaseEstate::getStartPath();

        $json['elements'] = array('def' => array(), 'alt' => array());
        foreach ($floors as $item) {
            $link = self::_getLink($item['PARENT'], $item['ID']);
            $link = str_replace($startPath, '', $link);
            $plural = plural(
                $item['CNT'],
                array(
                    '<span>квартира</span>',
                    '<span>квартиры</span>',
                    '<span>квартир</span>',
                )
            );
            $toPage = !empty($item['VISUAL_LINK']) ? $item['VISUAL_LINK'] : false;
            $json['elements']['def'][] = array(
                'id'      => $item['ID'],
                'coords'  => $item['NAV_COORD'],
                'link'    => $link,
                'tooltip' => array(
                    'header'  => '<b>' . $item['NAME'] . ' этаж</b>',
                    'content' => $item['CNT'] . ' ' . $plural,
                ),
                'toPage'  => $toPage,
            );
            $json['elements']['alt'][] = array(
                'id'      => $item['ID'],
                'coords'  => $item['NAV_COORD_ALT'],
                'link'    => $link,
                'tooltip' => array(
                    'header'  => $item['NAME'] . ' <div>этаж</div>',
                    'content' => $item['CNT'] . ' ' . $plural,
                ),
                'toPage'  => $toPage,
            );
        }

        return $json;
    }

    public static function getFloorLink($building, $floors) {
        $floor = self::getRow(array(
            'filter' => array(
                'ID'     => array_keys($floors),
                'PARENT' => $building['ID'],
            ),
            'limit' => 1
        ));
        $floorId = $floor['ID'];
        return self::_getLink($building['ID'], $floorId);
    }

    protected static function _getLink($buildingId, $floorId)
    {
        return 'building/' . $buildingId . '/floor/' . $floorId . '/';
    }
}
