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
 * @subpackage estateobjectsubways
 */
class EstateObjectSubwaysTable extends BaseEstate
{
    public static $_instance = null;

    protected $_tableName = 'estate_object_subways';

    protected $_fieldsMap = array(
        'ID' => array(
            'data_type'    => 'integer',
            'primary'      => true,
            'autocomplete' => true,
        ),
        'OBJECT' => array(
            'data_type' => 'integer',
            'required'  => true,
        ),
        'SUBWAY' => array(
            'data_type' => 'integer',
            'required'  => true,
        ),
        'SUBWAY_NAME' => array(
            'data_type'  => 'string',
            'expression' => array(
                '(SELECT NAME FROM estateref_subway WHERE ID = %s)',
                'SUBWAY',
            ),
        ),
        'CNT' => array(
            'data_type'  => 'integer',
            'expression' => array('count(%s)', 'OBJECT'),
        ),
        'OBJECTTABLE' => array(
            'data_type' => 'EstateObjectTable',
            'reference' => array('=this.OBJECT' => 'ref.ID'),
        ),
	    'REF_SUBWAY' => array(
		    'data_type' => 'EstateRefSubway',
		    'reference' => array(
			    '=this.SUBWAY' => 'ref.ID'
		    )
	    ),
    );
}
