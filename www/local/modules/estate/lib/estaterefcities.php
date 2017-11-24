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
 * @subpackage estaterefcities
 */
class EstateRefCitiesTable extends BaseEstate
{
    public static $_instance = null;

    protected $_tableName = 'estateref_cities';

    protected $_childEntityId = array(self::ESTATE_ENTITY_DISTRICTS, self::ESTATE_ENTITY_SUBWAY);

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
        'CHILD_7_COUNT' => array(
            'data_type'  => 'integer',
            'expression' => array(
                '(SELECT COUNT(ID) FROM estateref_districts WHERE PARENT = %s)',
                'ID',
            ),
        ),
        'CHILD_8_COUNT' => array(
            'data_type'  => 'integer',
            'expression' => array(
                '(SELECT COUNT(ID) FROM estateref_subway WHERE PARENT = %s)',
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
            'id'           => 'CHILD_7_COUNT',
            'content_lang' => 'ESTATE_ADMIN_ROW_CHILD_DISTRICTS_FIELD',
            'sort'         => 'CHILD_COUNT',
        ),
        array(
            'id'           => 'CHILD_8_COUNT',
            'content_lang' => 'ESTATE_ADMIN_ROW_CHILD_SUBWAY_FIELD',
            'sort'         => 'CHILD_COUNT',
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
