<?php
/**
 * Bitrix Framework
 *
 * @package    bitrix
 * @subpackage estate
 */

namespace Bitrix\Estate;

class EstateSearch extends BaseEstate
{
    public static $_instance = null;

    protected $_defaultSearchOrder = array('PRICE_TOTAL' => 'ASC');

    const COUNT_ON_PAGE = 20;

    /**
     * Возвращает фильтр для ORM в зависимости от параметров запроса
     *
     * @return array
     */
    public function getSearchRequest()
    {
        $filter = array('STATUS' => EstateFlatTable::IN_SALE_STATUS);

        $possibleFilters = array(
            'price' => array(
                'field' => 'PRICE_TOTAL',
                'type' => 'slider',
            ),
            'area' => array(
                'field' => 'AREA_TOTAL',
                'type' => 'slider',
            ),
            'apart' => array(
                'field' => 'TYPE',
                'type' => 'checkbox',
            ),
            'param' => array(
                'field' => 'FEATURESTABLE.FEATURE',
                'type' => 'checkbox',
            ),
            'option' => array(
                'field' => 'OPTIONSTABLE.OPTION',
                'type' => 'checkbox',
            ),
        );
        foreach ($possibleFilters as $name => $opt) {
            if (isset($_REQUEST[$name]) && is_array($_REQUEST[$name])) {
                $values = $_REQUEST[$name];
                switch ($opt['type']) {
                    case 'slider':
                        if ($opt['type'] === 'slider') {
                            if (isset($values['min']) && $values['min'] > 0) {
                                $value = str_replace(
                                    array(' ', ','),
                                    array('', '.'),
                                    $values['min']
                                );
                                $filter['>=' . $opt['field']] = $value - 0.001;
                            }
                            if (isset($values['max']) && $values['max'] > 0) {
                                $value = str_replace(
                                    array(' ', ','),
                                    array('', '.'),
                                    $values['max']
                                );
                                $filter['<=' . $opt['field']] = $value + 0.001;
                            }
                        }
                        break;

                    case 'checkbox':
                        $values = array_map('intval', array_keys($values));
                        $filter[$opt['field']] = $values;
                        break;
                }
            }
        }
        if (isset($filter['FEATURESTABLE.FEATURE'])) {
            $filter['HAVING_CNT'] = count($filter['FEATURESTABLE.FEATURE']);
        }
        if (isset($filter['OPTIONSTABLE.OPTION'])) {
            $filter['HAVING_CNT'] = count($filter['OPTIONSTABLE.OPTION']) + $filter['HAVING_CNT'];
        }

        $filter['PARENT'] = $this->_getParentByRequest();

        return $filter;
    }

    /**
     * Возвращает массив ID этажей в зависимости от запроса
     *
     * @return array
     */
    protected function _getParentByRequest()
    {
        $activeMap = BaseEstate::getActiveElementsMap();
        $parent = array_keys($activeMap['FLOORS']);

        if (!isset($_REQUEST['division']) || empty($_REQUEST['num'])) {
            return $parent;
        }

        switch ($_REQUEST['division']) {
            case 'building':
                $floors = self::filterByParentId(
                    $activeMap['FLOORS'],
                    $_REQUEST['num']
                );
                $parent = array_keys($floors);
                break;
//             case 'section':
//                 $floors = self::filterByParentId(
//                     $activeMap['FLOORS'],
//                     $_REQUEST['num']
//                 );
//                 $parent = array_keys($floors);
//                 break;
            case 'floor':
                $parent = $_REQUEST['num'];
                break;
        }
        return $parent;
    }

    /**
     * Возвращает параметры сортировки в зависимости от запроса
     *
     * @return array
     */
    public function getSearchOrder()
    {
        if (!isset($_REQUEST['sort'])) {
            return $this->_defaultSearchOrder;
        }

        //Возможные параметры для сортировки
        $possibleOrder = array(
            'number' => 'NUMBER',
            'rooms' => 'ROOMS',
            'floor' => 'REF_FLOOR.NUMBER_FOR_FLATS',
            'area' => 'AREA_TOTAL',
            'live' => 'AREA_LIVING',
            'kitchen' => 'AREA_KITCHEN',
            'price' => 'PRICE_TOTAL',
            'price-m' => 'PRICE_METER',
        );

        if (!isset($possibleOrder[$_REQUEST['sort']])) {
            return $this->_defaultSearchOrder;
        }
        $order = isset($_REQUEST['order']) && strtoupper($_REQUEST['order']) === 'DESC' ? 'DESC' : 'ASC';

        return array($possibleOrder[$_REQUEST['sort']] => $order);
    }

    /**
     * Возвращает массив с данными для поисковой формы
     *
     * @return array
     */
    public function getSearchForm()
    {
        $result = array();
        // Карта активных элементов недвижимости
        $activeMap = BaseEstate::getActiveElementsMap();
        if (empty($activeMap['FLOORS'])) {
            return array();
        }
        // Умный фильтр
        //$filter = $this->getSearchRequest();

        $filter = array();
        $filter['STATUS'] = EstateFlatTable::IN_SALE_STATUS;
        $filter['PARENT'] = array_keys($activeMap['FLOORS']);

        // Цена
        $result['FILTERS']['PRICE']['MIN'] = 0;
        $result['FILTERS'] = $this->getFilterBorders(array(), true);

        $mean = array_sum($result['FILTERS']['PRICE']) / 2;
        $result['FILTERS']['PRICE']['MEAN'] = round($mean / 100000) * 100000;
        $result['FILTERS']['PRICE']['MAX'] = ceil($result['FILTERS']['PRICE']['MAX'] / 100000) * 100000;
        $result['FILTERS']['PRICE']['MIN'] = floor($result['FILTERS']['PRICE']['MIN'] / 100000) * 100000;
        foreach ($result['FILTERS']['PRICE'] as $name => $value) {
            $result['FILTERS']['PRICE'][$name . '_FORMAT'] = price_format($value);
            $result['FILTERS']['PRICE'][$name . '_VALUE'] =
                isset($_REQUEST['price'][strtolower($name)])
                    ? str_replace(' ', '', $_REQUEST['price'][strtolower($name)])
                    : $value;
        }
        unset($value);

        // Типы квартир
        $result['FLAT_TYPES'] = EstateRefFlatTypesTable::getAssoc(
            array('filter' => array(
                '!ID' => 5,
                'ID' => $result['FILTERS']['APART'],
            )),
            'ID'
        );

        // Особенности параметры
        $result['PARAM'] = EstateRefFeaturesTable::getAssoc(
            array('filter' => array(
                'ID' => $result['FILTERS']['PARAM'],
            )),
            'ID',
            'NAME'
        );

        // Дополнительные параметры
        $result['OPTION'] = EstateRefOptionsTable::getAssoc(
            array('filter' => array(
                'ID' => $result['FILTERS']['OPTION'],
            )),
            'ID',
            'NAME'
        );

        // Список объектов ЖК привязанных к Объектам в Квартирографии
        $result['OBJECTS'] = BaseEstate::getIblockObjects();

        // Площадь
        $mean = array_sum($result['FILTERS']['AREA']) / 2;
        $result['FILTERS']['AREA']['MIN'] = floor($result['FILTERS']['AREA']['MIN']);

        $result['FILTERS']['AREA']['MEAN'] = round($mean);
        $result['FILTERS']['AREA']['MAX'] = ceil($result['FILTERS']['AREA']['MAX']);

        foreach ($result['FILTERS']['AREA'] as $name => $value) {
            $result['FILTERS']['AREA'][$name . '_VALUE'] =
                isset($_REQUEST['area'][strtolower($name)])
                    ? $_REQUEST['area'][strtolower($name)]
                    : $value;
        }

        // Общее количество подходящих квартир
        $result['CNT'] = $this->getResultCount($filter);
        $result['CNT_WORD'] = $plural = plural($result['CNT'], array('квартиру', 'квартиры', 'квартир'));

        return $result;
    }

    /**
     * Возвращает границы слайдеров и активные параметры в зависимости от фильтра
     *
     * @param array $filter     Массив с фильтрами ORM
     * @param bool $withSliders Нужно ли считать границы слайдеров
     * @return array
     */
    public function getFilterBorders(array $filter = array(), $withSliders = false)
    {
        global $DB;
        $result = array();
        $where = $this->_getFilterWhere($filter);
        $typeFilter = $filter;
        unset($typeFilter['TYPE']);

        $result['OBJECTS'] = array();
        $result['BUILDING'] = array();
        $select = 'ID, PARENT';
        $items = $this->_getQueryResult(
            $select,
            $where
        );
        // Данные о родителях от этажей до корпусов
        $floorsIds = BaseEstate::getParentsFromResult($items);
        $floors = EstateFloorTable::getByIds($floorsIds);

        // $sectionsIds = BaseEstate::getParentsFromResult($floors);
//        $sectionsIds = array_column($items, 'SECTION');
//        $sections = EstateSectionTable::getByIds($sectionsIds);

        $buildingsIds = BaseEstate::getParentsFromResult($floors);
        $buildings = EstateBuildingTable::getByIds($buildingsIds);

        $objectsIds = BaseEstate::getParentsFromResult($buildings);
        $objects = EstateObjectTable::getByIds($objectsIds);

        $activeMapObjects = BaseEstate::getIblockObjectsFullInfoWithBuilding();

        foreach ($objects as $object) {
            $result['OBJECTS'][] = $object['OBJECT'];
            //Формируем корпуса со сроками сдачи
            foreach ($activeMapObjects as $iObject) {
                if ($iObject['ID'] == $object['OBJECT']) {
                    foreach ($iObject['BUILDINGS'] as $building) {
                        if (!$building['PROPERTY_IS_READY_VALUE']) {
                            $result['BUILDING'][] = $building['ID'];
                        }
                    }
                }
            }
        }


        $result['APART'] = array();
        $typeWhere = $where;
        unset($typeWhere['TYPE']);
        $sql = "SELECT ID FROM estateref_flattypes WHERE ID IN
            (SELECT TYPE FROM estate_flat WHERE " . implode(' AND ', $typeWhere) . ")";
        $res = $DB->Query($sql);
        while ($data = $res->fetch()) {
            $result['APART'][] = $data['ID'];
        }

        $featureWhere = $where;
        unset($featureWhere['OPT']);
        $sql = "SELECT DISTINCT `FEATURE`
             FROM `estate_flat_features`
             WHERE `FLAT` IN (
                 SELECT `ID` FROM `estate_flat`
                 WHERE " . implode(' AND ', $featureWhere) . "
             )";
        $result['PARAM'] = array();
        $res = $DB->Query($sql);
        while ($data = $res->fetch()) {
            $result['PARAM'][] = $data['FEATURE'];
        }

        $optWhere = $where;
        unset($optWhere['FLAT_OPT']);
        $sql = "SELECT DISTINCT `OPTION`
             FROM `estate_flat_options`
             WHERE `FLAT` IN (
                 SELECT `ID` FROM `estate_flat`
                 WHERE " . implode(' AND ', $optWhere) . "
             )";
        $result['OPTION'] = array();
        $res = $DB->Query($sql);
        while ($data = $res->fetch()) {
            $result['OPTION'][] = $data['OPTION'];
        }

        if (!$withSliders) {
            return $result;
        }

        $result['PRICE'] = $this->_getQueryResult(
            'MIN(`PRICE_TOTAL`) as MIN, MAX(`PRICE_TOTAL`) as MAX',
            $where,
            array('PRICE_MIN', 'PRICE_MAX')
        );
        $result['PRICE'] = current($result['PRICE']);

        $result['AREA'] = $this->_getQueryResult(
            'MIN(`AREA_TOTAL`) as MIN, MAX(`AREA_TOTAL`) as MAX',
            $where,
            array('AREA_MIN', 'AREA_MAX')
        );
        $result['AREA'] = current($result['AREA']);


        return $result;
    }


    /**
     * Возвращает количество подходящих под фильтр квартир
     *
     * @param array $filter Массив с фильтрами ORM
     * @return int
     */
    public function getResultCount(array $filter = array())
    {
        $where = $this->_getFilterWhere($filter);
        $res = $this->_getQueryResult('COUNT(*) AS CNT', $where, array());
        $res = current($res);
        return (int)$res['CNT'];
    }

    /**
     * Формирует массив для WHERE sql запроса в зависимости от фильтров
     *
     * @param array $filter Массив фильтров ORM
     * @return array
     */
    protected function _getFilterWhere(array $filter = array())
    {
        $where = array("1 = 1");
        foreach ($filter as $name => $val) {
            switch ($name) {
                case '<=AREA_TOTAL':
                    $where['AREA_MIN'] = "`AREA_TOTAL` <= '" . ($val + 0.001) . "'";
                    break;
                case '>=AREA_TOTAL':
                    $where['AREA_MAX'] = "`AREA_TOTAL` >= '" . ($val - 0.001) . "'";
                    break;
                case '<=PRICE_TOTAL':
                    $where['PRICE_MIN'] = "`PRICE_TOTAL` <= '{$val}'";
                    break;
                case '>=PRICE_TOTAL':
                    $where['PRICE_MAX'] = "`PRICE_TOTAL` >= '{$val}'";
                    break;
                case 'FEATURESTABLE.FEATURE':
                    $where['OPT'] = "`ID` IN (
                        SELECT `FLAT`
                        FROM `estate_flat_features`
                        USE INDEX (`FLAT_FEATURE`)
                        WHERE `FEATURE` IN (" . implode(',', $val) . ")
                        GROUP BY `FLAT`
                        HAVING COUNT(`FEATURE`) = " . count($val) . "
                    )";
                    break;
                case 'OPTIONSTABLE.OPTION':
                    $where['FLAT_OPT'] = "`ID` IN (
                        SELECT `FLAT`
                        FROM `estate_flat_options`
                        USE INDEX (`FLAT_OPTION`)
                        WHERE `OPTION` IN (" . implode(',', $val) . ")
                        GROUP BY `FLAT`
                        HAVING COUNT(`OPTION`) = " . count($val) . "
                    )";
                    break;
                case 'STATUS':
                    $where['STATUS'] = "`STATUS` = {$val}";
                    break;
                case 'TYPE':
                case 'PARENT':
                    $val = (array)$val;
                    $where[$name] = "`{$name}` IN (" . implode(',', $val) . ")";
            }
        }
        return $where;
    }

    /**
     * Делает запрос к бд и возвращает результат в виде массива
     *
     * @param string $select Строка для SELECT запроса
     * @param array $where   Массив условий для WHERE запроса
     * @param array $unset   Массив с ключами $where, которые необходимо пропустить
     * @return array
     */
    protected function _getQueryResult($select = '*', array $where, $unset = array(), $debug = false)
    {
        global $DB;
        $result = array();
        if (empty($where)) {
            $where[] = '1 = 1';
        }

        $unset = (array)$unset;
        foreach ($unset as $name) {
            if (isset($where[$name])) {
                unset($where[$name]);
            }
        }

        $sql = "SELECT {$select}
            FROM `estate_flat`
            WHERE " . implode(' AND ', $where);
        if ($debug) {
            echo $sql;
        }
        $res = $DB->Query($sql);
        while ($data = $res->fetch()) {
            $result[] = $data;
        }
        return $result;
    }

}