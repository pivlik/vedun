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
class EstateFlatFeaturesTable extends BaseEstate
{
    public static $_instance = null;

    protected $_tableName = 'estate_flat_features';

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
        'FEATURE' => array(
            'data_type' => 'integer',
            'required'  => true,
        ),
        'FEATURE_NAME' => array(
            'data_type'  => 'string',
            'expression' => array(
                '(SELECT NAME FROM estateref_features WHERE ID = %s)',
                'FEATURE',
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
