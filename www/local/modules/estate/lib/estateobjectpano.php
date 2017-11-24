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
 * @subpackage estateobjectpano
 */
class EstateObjectPanoTable extends BaseEstate
{
    const PANO_FOLDER = '/pano/';

    public static $_instance = null;

    protected $_tableName = 'estate_object_pano';

    protected $_parentEntityId = self::ESTATE_ENTITY_OBJECT;

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
        'FILE' => array(
            'data_type'  => 'string',
            'required'   => true,
            'save'       => true,
            'content'    => 'Название файла панорамы',
            'field_type' => 'text',
        ),
        'POSITION' => array(
            'data_type'  => 'string',
            'save'       => true,
            'content'    => 'Положение указателя',
            'field_type' => 'text',
        ),
        'POSITION_ALT' => array(
            'data_type'  => 'string',
            'save'       => true,
            'content'    => 'Положение указателя альтернативное',
            'field_type' => 'text',
        ),
        'PARENT' => array(
            'data_type'    => 'integer',
            'required'     => true,
            'save'         => true,
            'content_lang' => 'ESTATE_ADMIN_ROW_PARENT_OBJECT_FIELD',
            'field_type'   => 'refSelect',
            'ref_class'    => '\Bitrix\Estate\EstateObjectTable',
        ),
        'PARENT_NAME' => array(
            'data_type'  => 'integer',
            'expression' => array(
                '(SELECT NAME FROM estate_object WHERE ID = %s)',
                'PARENT',
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
            'id'      => 'FILE',
            'content' => 'Файл',
            'sort'    => 'FILE',
        ),
        array(
            'id'           => 'ACTIVE',
            'content_lang' => 'ESTATE_ADMIN_ROWS_LIST_ACTIVE',
            'sort'         => 'ACTIVE',
        ),
        array(
            'id'           => 'PARENT_NAME',
            'content_lang' => 'ESTATE_ADMIN_ROW_PARENT_OBJECT_FIELD',
            'sort'         => 'PARENT_NAME',
        ),
    );

    protected $_filters = array(
        'ID'     => array(
            'param' => 'find_id',
            'name'  => 'ID',
        ),
        '%FILE'  => array(
            'param' => 'find_file',
            'name'  => 'Файл',
        ),
        'PARENT' => array(
            'param'     => 'find_parent',
            'name_lang' => 'ESTATE_ADMIN_ROW_PARENT_OBJECT_ID_FIELD',
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

    public static function getForJson($objectId)
    {
        $json = array(
            'def' => array(),
            'alt' => array(),
        );
        $pano = self::getAssoc(array(
            'filter' => array(
                'PARENT' => $objectId,
                'ACTIVE' => 'Y'
            )
        ));
        foreach ($pano as $item) {
            $path = self::PANO_FOLDER . $item['FILE'];
            if ($item['POSITION']) {
                $json['def'][] = array(
                    'position' => $item['POSITION'],
                    'file'     => $path,
                );
            }
            if ($item['POSITION_ALT']) {
                $json['alt'][] = array(
                    'position' => $item['POSITION_ALT'],
                    'file'     => $path,
                );
            }
        }
        return $json;
    }
}
