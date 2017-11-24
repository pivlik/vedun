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
 * @subpackage estateobject
 */
class EstateObjectTable extends BaseEstate
{
    public static $_instance = null;

    protected $_tableName = 'estate_object';

    protected $_childEntityId = array(
        self::ESTATE_ENTITY_BUILDING,
        self::ESTATE_ENTITY_PANO
    );

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
        'NAV_IMAGE' => array(
            'data_type'  => 'string',
            'save'       => true,
            'field_type' => 'file',
            'content'    => 'Изображение для навигатора',
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
        // 'POS_VIDEO_MP4' => array(
        //     'data_type'  => 'string',
        //     'save'       => true,
        //     'field_type' => 'file',
        //     'content'    => 'Видео смены ракурса MP4',
        // ),
        // 'POS_VIDEO_WEBM' => array(
        //     'data_type'  => 'string',
        //     'save'       => true,
        //     'field_type' => 'file',
        //     'content'    => 'Видео смены ракурса WEBM',
        // ),
        // 'POS_VIDEO_OGG' => array(
        //     'data_type'  => 'string',
        //     'save'       => true,
        //     'field_type' => 'file',
        //     'content'    => 'Видео смены ракурса OGG',
        // ),
        // 'POS_VIDEO_MP4_ALT' => array(
        //     'data_type'  => 'string',
        //     'save'       => true,
        //     'field_type' => 'file',
        //     'content'    => 'Видео смены ракурса MP4 альтернативное',
        // ),
        // 'POS_VIDEO_WEBM_ALT' => array(
        //     'data_type'  => 'string',
        //     'save'       => true,
        //     'field_type' => 'file',
        //     'content'    => 'Видео смены ракурса WEBM альтернативное',
        // ),
        // 'POS_VIDEO_OGG_ALT' => array(
        //     'data_type'  => 'string',
        //     'save'       => true,
        //     'field_type' => 'file',
        //     'content'    => 'Видео смены ракурса OGG альтернативное',
        // ),
        'IMPORT_ID' => array(
            'data_type'  => 'string',
            'save'       => true,
            'content'    => 'ID выгрузки',
            'field_type' => 'text',
        ),
        'CHILD_2_COUNT' => array(
            'data_type'  => 'integer',
            'expression' => array(
                '(SELECT COUNT(ID) FROM estate_building WHERE PARENT = %s)',
                'ID',
            ),
        ),
        // 'CHILD_16_COUNT' => array(
        //     'data_type'  => 'integer',
        //     'expression' => array(
        //         '(SELECT COUNT(ID) FROM estate_object_pano WHERE PARENT = %s)',
        //         'ID',
        //     ),
        // ),
         'OBJECT' => array(
             'data_type'      => 'string',
             'save'           => true,
             'content'        => 'Объект (инфоблок)',
             'field_type'     => 'refIblock',
             'iblock'         => 6,
         ),
//        Метро и район перенесли в ИБ Объектов
//        'SUBWAYS' => array(
//            'data_type'      => 'integer',
//            'expression'     => '1',
//            'save'           => true,
//            'content'        => 'Станции метро',
//            'field_type'     => 'refCheckboxes',
//            'ref_class'      => '\Bitrix\Estate\EstateRefSubwayTable',
//            'linking_class'  => '\Bitrix\Estate\EstateObjectSubwaysTable',
//            'linking_field1' => 'OBJECT',
//            'linking_field2' => 'SUBWAY',
//        ),
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
            'sort'         =>'ACTIVE',
        ),
        array(
            'id'           => 'CHILD_2_COUNT',
            'content_lang' => 'ESTATE_ADMIN_ROW_CHILD_BUILDINGS_FIELD',
            'sort'         => 'CHILD_2_COUNT',
        ),
        // array(
        //     'id'      => 'CHILD_16_COUNT',
        //     'content' => 'Панорамы',
        //     'sort'    => 'CHILD_16_COUNT',
        // ),
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
        'ACTIVE' => array(
            'param'     => 'find_active',
            'name_lang' => 'ESTATE_ADMIN_ROWS_LIST_ACTIVE',
            'variants'  => array(
                'Y' => 'ESTATE_ADMIN_ROWS_LIST_ACTIVE_YES',
                'N' => 'ESTATE_ADMIN_ROWS_LIST_ACTIVE_NO',
            ),
        ),
    );
}
