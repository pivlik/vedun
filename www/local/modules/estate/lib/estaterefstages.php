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
 * @subpackage estaterefstages
 */
class EstateRefStagesTable extends BaseEstate
{
    public static $_instance = null;

    protected $_tableName = 'estateref_stages';

    protected $_fieldsMap = array(
        'ID' => array(
            'data_type'    => 'integer',
            'primary'      => true,
            'autocomplete' => true,
        ),
        'NUMBER' => array(
            'data_type'  => 'integer',
            'required'   => true,
            'save'       => true,
            'content'    => 'Номер очереди',
            'field_type' => 'text',
        ),
        'NAME' => array(
            'data_type'    => 'string',
            'required'     => true,
            'save'         => true,
            'content_lang' => 'IBLOCK_FIELD_NAME',
            'field_type'   => 'text',
        ),
        'NAME_DATIVE' => array(
            'data_type'  => 'string',
            'required'   => true,
            'save'       => true,
            'content'    => 'Название в дательном падеже',
            'field_type' => 'text',
        ),
        'DELIVERY' => array(
            'data_type'    => 'string',
            'save'         => true,
            'content_lang' => 'ESTATE_ADMIN_ROW_DELIVERY_FIELD',
            'field_type'   => 'text',
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
            'id'           => 'DELIVERY',
            'content_lang' => 'ESTATE_ADMIN_ROW_DELIVERY_FIELD',
            'sort'         => 'DELIVERY',
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
