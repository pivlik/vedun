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
 * @subpackage estaterefflattypes
 */
class EstateRefFlatTypesTable extends BaseEstate
{
    public static $_instance = null;

    protected $_tableName = 'estateref_flattypes';

    protected $_fieldsMap = array(
        'ID' => array(
            'data_type'    => 'integer',
            'primary'      => true,
            'autocomplete' => true,
        ),
        'NAME' => array(
            'data_type'    => 'string',
            'required'     => true,
            'save'         => true,
            'content_lang' => 'IBLOCK_FIELD_NAME',
            'field_type'   => 'text',
        ),
        'ROOMS' => array(
            'data_type'  => 'integer',
            'required'   => true,
            'save'       => true,
            'content'    => 'Количество комнат',
            'field_type' => 'text',
        ),
//        'IMPORT_ID' => array(
//            'data_type'  => 'integer',
//            'required'   => true,
//            'save'       => true,
//            'content'    => 'ID импорта',
//            'field_type' => 'text',
//        ),
//		'FULL_NAME' => array(
//			'data_type'  => 'string',
//			'save'       => true,
//			'content'    => 'Полное название',
//			'field_type' => 'text',
//		),
//		'SINGLE_NAME' => array(
//			'data_type'  => 'string',
//			'save'       => true,
//			'content'    => 'Название в ед. числе ',
//			'field_type' => 'text',
//		),
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
    );

    protected $_filters = array(
        'ID'    => array(
            'param' => 'find_id',
            'name'  => 'ID',
        ),
        '%NAME' => array(
            'param'     => 'find_name',
            'name_lang' => 'ESTATE_ADMIN_ROWS_LIST_NAME',
        ),
    );
}
