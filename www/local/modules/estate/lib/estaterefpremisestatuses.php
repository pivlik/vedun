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
 * @subpackage estaterefpremisestatuses
 */
class EstateRefPremiseStatusesTable extends BaseEstate
{
    public static $_instance = null;

    protected $_tableName = 'estateref_premisestatuses';

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
        'TAB_NAME' => array(
            'data_type'  => 'string',
            'required'   => true,
            'save'       => true,
            'content'    => 'Название для табов',
            'field_type' => 'text',
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
            'id'      => 'TAB_NAME',
            'content' => 'Название для табов',
            'sort'    => 'TAB_NAME',
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
