<?php
/**
 * Bitrix Framework
 * @package    bitrix
 * @subpackage estate
 */

namespace Bitrix\Estate;

use Bitrix\Main\Entity\ExpressionField;

/**
 * Class description
 * @package    estate
 * @subpackage estatepremise
 */
class EstatePremiseTable extends BaseEstate
{
    public static $_instance = null;

    protected $_tableName = 'estate_premise';

    protected $_parentEntityId = self::ESTATE_ENTITY_SECTION;

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
            'data_type'  => 'string',
            'required'   => true,
            'save'       => true,
            'content'    => 'Номер',
            'field_type' => 'text',
        ),
        'TYPE' => array(
            'data_type'  => 'integer',
            'required'   => true,
            'save'       => true,
            'content'    => 'Тип помещения',
            'field_type' => 'refSelect',
            'ref_class'  => '\Bitrix\Estate\EstateRefPremiseTypesTable',
        ),
        'PRICE' => array(
            'data_type'  => 'integer',
            'save'       => true,
            'content'    => 'Цена при 100% оплате',
            'field_type' => 'text',
        ),
        'PRICE_LEASE' => array(
            'data_type'  => 'integer',
            'save'       => true,
            'content'    => 'Стоимость аренды, р/мес',
            'field_type' => 'text',
        ),
        'AREA_TOTAL' => array(
            'data_type'  => 'integer',
            'save'       => true,
            'content'    => 'Общая площадь',
            'field_type' => 'text',
        ),
        'IMAGE' => array(
            'data_type'  => 'string',
            'save'       => true,
            'field_type' => 'file',
            'content'    => 'Изображение',
        ),
        'TEXT' => array(
            'data_type'  => 'string',
            'save'       => true,
            'field_type' => 'textarea',
            'content'    => 'Описание',
        ),
        'STATUS' => array(
            'data_type'  => 'integer',
            'save'       => true,
            'content'    => 'Статус',
            'field_type' => 'refSelect',
            'ref_class'  => '\Bitrix\Estate\EstateRefPremiseStatusesTable',
        ),
        'IMPORT_ID' => array(
            'data_type'  => 'string',
            'save'       => true,
            'content'    => 'ID выгрузки',
            'field_type' => 'text',
        ),
        'PARENT' => array(
            'data_type'    => 'integer',
            'required'     => true,
            'save'         => true,
            'content_lang' => 'ESTATE_ADMIN_ROW_PARENT_FLOOR_FIELD',
            'field_type'   => 'refSelect',
            'ref_class'    => '\Bitrix\Estate\EstateFloorTable',
        ),
        'PARENT_NAME' => array(
            'data_type'  => 'integer',
            'expression' => array(
                '(SELECT CONCAT(estate_object.NAME, " - ", estate_building.NAME,
                                " - ", estate_section.NAME)
                    FROM estate_section
                    LEFT JOIN estate_building ON estate_building.ID = estate_section.PARENT
                    LEFT JOIN estate_object ON estate_object.ID = estate_building.PARENT
                    WHERE estate_section.ID = %s
                )',
                'PARENT',
            ),
        ),
        'TYPE_NAME' => array(
            'data_type'  => 'integer',
            'expression' => array(
                '(SELECT NAME FROM estateref_premisetypes WHERE ID = %s)',
                'TYPE',
            ),
        ),
        'STATUS_NAME' => array(
            'data_type'  => 'integer',
            'expression' => array(
                '(SELECT NAME FROM estateref_premisestatuses WHERE ID = %s)',
                'STATUS',
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
            'id'      => 'NAME',
            'content' => 'Название',
            'sort'    => 'NAME',
        ),
        array(
            'id'      => 'TYPE_NAME',
            'content' => 'Тип помещения',
            'sort'    => 'TYPE_NAME',
        ),
        array(
            'id'      => 'STATUS_NAME',
            'content' => 'Статус',
            'sort'    => 'STATUS_NAME',
        ),
        array(
            'id'      => 'PRICE',
            'content' => 'Цена',
            'sort'    => 'PRICE',
        ),
        array(
            'id'      => 'PRICE_LEASE',
            'content' => 'Стоимость аренды',
            'sort'    => 'PRICE',
        ),
        array(
            'id'      => 'AREA_TOTAL',
            'content' => 'Площадь',
            'sort'    => 'AREA_TOTAL',
        ),
        array(
            'id'           => 'ACTIVE',
            'content_lang' => 'ESTATE_ADMIN_ROWS_LIST_ACTIVE',
            'sort'         => 'ACTIVE'
        ),
        array(
            'id'           => 'PARENT_NAME',
            'content_lang' => 'ESTATE_ADMIN_ROW_PARENT_SECTION_FIELD',
            'sort'         => 'PARENT_NAME',
            'filter'       => 'PARENT',
        ),
    );

    protected $_filters = array(
        'ID'     => array(
            'param' => 'find_id',
            'name' => 'ID',
        ),
        '%NAME'  => array(
            'param' => 'find_name',
            'name'  => 'Название',
        ),
        'PARENT' => array(
            'param'     => 'find_parent',
            'name_lang' => 'ESTATE_ADMIN_ROW_PARENT_SECTION_ID_FIELD',
        ),
        '%TYPE_NAME' => array(
            'param' => 'find_type_name',
            'name'  => 'Тип помещения',
        ),
        '%STATUS_NAME' => array(
            'param' => 'find_type_name',
            'name'  => 'Статус',
        ),
        'ACTIVE' => array(
            'param'     => 'find_active',
            'name_lang' => 'ESTATE_ADMIN_ROWS_LIST_ACTIVE',
            'variants'  => array(
                'Y' => 'ESTATE_ADMIN_ROWS_LIST_ACTIVE_YES',
                'N' => 'ESTATE_ADMIN_ROWS_LIST_ACTIVE_NO'
            )
        )
    );

    public function getPremises(array $params = array())
    {
        // Список помещений заданного типа
        $items = self::getAssoc($params, 'ID');

        // Карта активных элементов недвижимости
        $activeMap = BaseEstate::getActiveElementsMap();

        $sectionsIds = array();
        $buildingIds = array();
        foreach ($items as $ID => $item) {
            // Активна ли секция
            if (!isset($activeMap['SECTIONS'][$item['PARENT']])) {
                unset($items[$ID]);
                continue;
            }
            if (!isset($sectionsIds[$item['PARENT']])) {
                $sectionsIds[$item['PARENT']] = $item['PARENT'];
            }
            $buildingId = $activeMap['SECTIONS'][$item['PARENT']]['PARENT'];
            if (!isset($buildingIds[$buildingId])) {
                $buildingIds[$buildingId] = $buildingId;
            }
        }

        // Список родительских секций
        $sections = EstateSectionTable::getByIds($buildingIds);

        // Список родительских корпусов
        $buildings = EstateBuildingTable::getByIds($buildingIds);

        // Статусы помещений
        $statuses = EstateRefPremiseStatusesTable::getAssoc(array(), 'ID');

        foreach ($items as &$item) {
            $item['SECTION_NAME'] = $sections[$item['PARENT']]['NAME'];
            $buildingId = $activeMap['SECTIONS'][$item['PARENT']];
            $item['BUILDING_NAME'] = $buildings[$buildingId]['NAME'];
            $item['STATUS'] = $item['STATUS']
                              ? $statuses[$item['STATUS']]['NAME']
                              : '';
            $item['IMAGE'] = \CFile::getFileArray($item['IMAGE']);
            $item['PRICE'] = $item['PRICE']
                             ? number_format($item['PRICE'], 0, '', ' ')
                             : '';
            $item['PRICE_LEASE'] = $item['PRICE_LEASE']
                                   ? number_format($item['PRICE_LEASE'], 0, '', ' ')
                                   : '';
        }
        unset($item);

        return $items;
    }

    public function getResultCount(array $filter = array())
    {
        $row = self::getRow(array(
            'filter' => $filter,
            'select' => array(
                'cnt' => new ExpressionField('cnt', 'COUNT(*)'),
            ),
        ));
        return $row['cnt'];
    }

    public static function clearComponentsCache()
    {
        $sites = array();
        $res = \CSite::GetList($by='sort', $order='asc', array(
            'ACTIVE' => 'Y'
        ));
        while ($arSite = $res->Fetch()) {
            $sites[] = $arSite['ID'];
        }

        $components = array(
            "kelnik:estate.premise",
            "kelnik:estate.premise.order",
        );
        $obCache = new \CPHPCache;
        foreach($components as $component) {
            // Находим путь к компоненту, из его имени
            $componentPath = \CComponentEngine::MakeComponentPath($component);

            if (!$componentPath) {
                continue;
            }

            foreach ($sites as $siteId) {
                $cache_path = '/' . $siteId . $componentPath;
                $obCache->CleanDir($cache_path, "cache");
                BXClearCache(true, $cache_path);
            }
        }
    }
}
