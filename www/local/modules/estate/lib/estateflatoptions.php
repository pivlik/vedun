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
 * @subpackage estateflatfeatures
 */
class EstateFlatOptionsTable extends BaseEstate
{
    public static $_instance = null;

    protected $_tableName = 'estate_flat_options';

    protected $_fieldsMap = array(
        'ID' => array(
            'data_type'    => 'integer',
            'primary'      => true,
            'autocomplete' => true,
        ),
        'FLAT' => array(
            'data_type' => 'integer',
            'required'  => true,
        ),
        'OPTION' => array(
            'data_type' => 'integer',
            'required'  => true,
        ),
        'OPTION_NAME' => array(
            'data_type'  => 'string',
            'expression' => array(
                '(SELECT NAME FROM estateref_options WHERE ID = %s)',
                'OPTION',
            ),
        ),
        'CNT' => array(
            'data_type'  => 'integer',
            'expression' => array('count(%s)', 'FLAT'),
        ),
        'FLATTABLE' => array(
            'data_type' => 'EstateFlatTable',
            'reference' => array('=this.FLAT' => 'ref.ID'),
        ),
    );
}
