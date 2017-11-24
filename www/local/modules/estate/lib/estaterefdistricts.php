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
 * @subpackage estaterefdistricts
 */
class EstateRefDistrictsTable extends BaseEstate
{
    public static $_instance = null;

    protected $_tableName = 'estateref_districts';

    protected $_parentEntityId = self::ESTATE_ENTITY_CITIES;

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
        'PARENT' => array(
            'data_type'    => 'integer',
            'required'     => true,
            'save'         => true,
            'content_lang' => 'ESTATE_ADMIN_ROW_PARENT_CITY_FIELD',
            'field_type'   => 'refSelect',
            'ref_class'    => '\Bitrix\Estate\EstateRefCitiesTable',
        ),
        'PARENT_NAME' => array(
            'data_type'  => 'integer',
            'expression' => array(
                '(SELECT NAME FROM estateref_cities WHERE ID = %s)',
                'PARENT'
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
            'id'           => 'PARENT_NAME',
            'content_lang' => 'ESTATE_ADMIN_ROW_PARENT_CITY_FIELD',
            'sort'         => 'PARENT_NAME',
        ),
    );

    protected $_filters = array(
        'ID'     => array(
            'param' => 'find_id',
            'name'  => 'ID',
        ),
        '%NAME'  => array(
            'param'     => 'find_name',
            'name_lang' => 'ESTATE_ADMIN_ROWS_LIST_NAME'
        ),
        'PARENT' => array(
            'param'     => 'find_parent',
            'name_lang' => 'ESTATE_ADMIN_ROW_PARENT_CITY_FIELD'
        ),
    );
}
